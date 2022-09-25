<?php

namespace App\Listeners;

use App\Driver;
use App\Events\DriverAcceptOrder;
use App\Events\DriverFound;
use App\Events\DriverNotFound;
use App\Events\OrderEvent;
use App\Events\OrderEvents;
use App\Notifications\NewAdditionalOrderNotification;
use App\Notifications\OrderCanceledNotification;
use App\Notifications\OrderFinishedNotification;
use App\Notifications\OrderPaidNoification;
use App\Notifications\OrderStartedNotification;
use App\Notifications\TriggerFinishNotification;
use App\Notifications\TriggerStartNotification;
use App\Order;
use App\Traits\GetNearestDriver;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Events\Dispatcher;
use Illuminate\Queue\InteractsWithQueue;

class OrderListener
{
    use GetNearestDriver;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handleOrderCreated(OrderEvent $event)
    {
        $order = $event->order;

        // generate order identifier
        $this->generateOrderIdentifier($order);

        // if not additional ordert then search driver
        if (! $order->isAdditionalOrder()) {
            $selectedDriver = $this->searchAvailableDriverWithOrder($order);

            if (!empty($selectedDriver)) {
                event(new DriverFound($selectedDriver, $order));
            } else {
                event(new DriverNotFound($order));
            }
        } else {
            $driver = $order->driver;

            // notify order info to driver
            ($driver->user)->notify(new NewAdditionalOrderNotification($order));

            event(new DriverAcceptOrder($order, $driver));
        }
    }

    public function handlePaidOrder(OrderEvent $event)
    {
        $order = $event->order;

        if ($order->getOrderType() == 'time') {
            $start_time = Carbon::createFromFormat('Y-m-d H:i:s', $order->start_datetime);

            if ($start_time->floatDiffInHours(now()) >= 1) {
                $order->update(['status' => Order::WAITING_DRIVER_STATUS]);
                $event->order->driver->update(['order_status' => Driver::ONGOING_STATUS]);
            } else {
                $order->update(['status' => Order::WAITING_ORDER_TIME_STATUS]);
                $event->order->driver->update(['order_status' => Driver::UNAVAILABLE_STATUS]);
            }
        } else {
            $order->update(['status' => $order->isLaterOrder() ? Order::WAITING_ORDER_TIME_STATUS : Order::WAITING_DRIVER_STATUS]);

            $event->order->driver->update(['order_status' => $order->isLaterOrder() ? Driver::UNAVAILABLE_STATUS : Driver::ONGOING_STATUS]);
        }

        if ($event->order->driver) {
            ($event->order->driver->user)->notify(new OrderPaidNoification($event->order));
        }
    }

    public function handleOrderStarted(OrderEvent $event)
    {
        $event->order->update(['status' => Order::ONGOING_STATUS]);

        $event->order->driver->update(['order_status' => Driver::ONGOING_STATUS]);

        ($event->order->user)->notify(new OrderStartedNotification($event->order));
    }

    public function handleOrderCanceled(OrderEvent $event)
    {
        if ($event->order->driver) {
            if (! $event->order->isCanceledOrder()) {
                $event->order->driver->update(['order_status' => Driver::UNAVAILABLE_STATUS]);
            }

            ($event->order->driver->user)->notify(new OrderCanceledNotification($event->order));
        }
    }

    public function handleFinishingOrder(OrderEvent $event)
    {
        if ($event->order->driver) {
            // trigger driver to upload final photo
            ($event->order->driver->user)->notify(new TriggerFinishNotification($event->order));
        }
    }

    public function handleStartingOrder(OrderEvent $event)
    {
        if ($event->order->driver) {
            // trigger driver to upload intial photo
            ($event->order->driver->user)->notify(new TriggerStartNotification($event->order));
        }
    }

    public function handleFinishedOrder(OrderEvent $event)
    {
        if (! $event->order->isOrderFinished()) {
            $event->order->update(['status' => Order::FINISHED_STATUS]);

            $this->addEarning($event->order->driver, $event->order->transaction->total_price);
        }

        ($event->order->user)->notify(new OrderFinishedNotification($event->order));
    }

    protected function addEarning(Driver $driver, $amount)
    {
        // check if driver has earning relationship
        if (! $driver->earning) {
            $driver->earning()->create(['amount' => 0]);
        }

        $fee = Fee::find(1)->percentage_amount;

        $driver->earning->update(['amount' => $driver->earning->amount + ($amount * (100 - $fee) / 100)]);
    }

    protected function generateOrderIdentifier(Order $order)
    {
        $block_1 = 'MYSUPIR';

        $block_2 = 'TS';
        if ($order->getOrderType() == 'trip') {
            if ($order->isLaterOrder()) {
                $block_2 = 'TN';
            }
        } else {
            $block_2 = 'TM';
        }

        $identifier = $block_1.'-'.$block_2.'-'.$order->id;
        $order->update(['identifier' => $identifier]);
    }

    /**
    * Register the listeners for the subscriber.
    *
    * @param Dispatcher $events
    */
    public function subscribe($events)
    {
        $events->listen(
            OrderEvents::CREATED,
            'App\Listeners\OrderListener@handleOrderCreated'
        );

        $events->listen(
            OrderEvents::CANCELED,
            'App\Listeners\OrderListener@handleOrderCanceled'
        );

        $events->listen(
            OrderEvents::STARTING,
            'App\Listeners\OrderListener@handleStartingOrder'
        );

        $events->listen(
            OrderEvents::STARTED,
            'App\Listeners\OrderListener@handleOrderStarted'
        );

        $events->listen(
            OrderEvents::FINISHING,
            'App\Listeners\OrderListener@handleFinishingOrder'
        );

        $events->listen(
            OrderEvents::FINISHED,
            'App\Listeners\OrderListener@handleFinishedOrder'
        );

        $events->listen(
            OrderEvents::PAID,
            'App\Listeners\OrderListener@handlePaidOrder'
        );
    }
}

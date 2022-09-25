<?php

namespace App\Http\Controllers\Api;

use App\Events\OrderEvent;
use App\Events\OrderEvents;
use App\Http\Controllers\Controller;
use App\Order;
use App\OrderReport;
use App\Traits\CurrentDriver;
use Illuminate\Http\Request;

class OrderReportController extends Controller
{
    use CurrentDriver;

    public function initOrder(Order $order, Request $request)
    {
        $currentDriver = $this->getCurrentDriver();
        abort_if($order->driver_id != $currentDriver->id, 403, 'Access denied');
        abort_if($order->isCanceledOrder(), 403, 'Order canceled by user or payment has expired.');
        abort_if(! $order->transaction->isPaymentSettled(), 403, 'Payment has not been paid.');
        abort_if($order->isWaitingTime(), 403, 'Order has not started yet.');
        abort_if(! empty($order->initialPhoto), 403, 'Photo already exists.');

        $request->validate([
            'photos' => 'required|array|min:1|max:5',
            'photos.*' => 'nullable|image|max:15120'
        ]);

        $photos = [];
        foreach ($request->file('photos') as $i => $photo) {
            if (!empty($photo)) {
                $path = $photo->store('order/'.$order->getOrderIdentifier().'/initial_photo');
                if ($path) {
                    $photos[$i] = $path;
                }
            }
        }

        $initialReport = OrderReport::create([
            'order_id' => $order->id,
            'photos' => $photos,
            'initial' => true
        ]);

        if ($initialReport) {
            event(OrderEvents::STARTED, new OrderEvent($order));
        }

        // event(OrderEvents::STARTED, new OrderEvent($order));

        return response()->json(['message' => 'ok'], 201);
    }

    public function finishOrder(Order $order, Request $request)
    {
        $currentDriver = $this->getCurrentDriver();
        abort_if($order->driver_id != $currentDriver->id, 403, 'Access denied');
        abort_if($order->isCanceledOrder(), 403, 'Order canceled by user or payment has expired.');
        abort_if(!$order->isOnGoingOrder(), 403, 'Order still on going.');
        abort_if(!empty($order->finalPhoto), 403, 'Photo already exists.');

        $request->validate([
            'photos' => 'required|array|min:1|max:5',
            'photos.*' => 'nullable|image|max:15120'
        ]);

        $photos = [];
        foreach ($request->file('photos') as $i => $photo) {
            if (!empty($photo)) {
                $path = $photo->store('order/'.$order->getOrderIdentifier().'/final_photo');
                if ($path) {
                    $photos[$i] = $path;
                }
            }
        }

        $finalReport = OrderReport::create([
            'order_id' => $order->id,
            'photos' => $photos,
            'final' => true
        ]);

        if ($finalReport) {
            event(OrderEvents::FINISHED, new OrderEvent($order));
        }

        return response()->json(['message' => 'ok'], 201);
    }
}

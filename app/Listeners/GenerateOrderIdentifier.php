<?php

namespace App\Listeners;

use App\Events\OrderCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateOrderIdentifier
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  OrderCreated  $event
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $order = $event->order;

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
}

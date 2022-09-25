<?php

namespace App\Listeners;

use App\Events\DriverAcceptOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Chat;

class CreateChatConversation
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
     * @param  object  $event
     * @return void
     */
    public function handle(DriverAcceptOrder $event)
    {
        $driver = $event->driver;
        $user = $event->order->user;

        if (! $event->order->isAdditionalOrder()) {
            $conversation = Chat::createConversation([$driver->user, $user], [
                'order_identifier' => $event->order->getOrderIdentifier()
            ]);

            if ($conversation) {
                $event->order->update(['conversation_id' => $conversation->id]);
            }
        } else {
            $parentOrder = $event->order->parent_order;

            $event->order->update(['conversation_id' => $parentOrder->conversation_id]);
        }
    }
}

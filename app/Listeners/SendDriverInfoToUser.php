<?php

namespace App\Listeners;

use App\Events\DriverAcceptOrder;
use App\Notifications\DriverFoundNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendDriverInfoToUser
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
     * @param  DriverAcceptOrder  $event
     * @return void
     */
    public function handle(DriverAcceptOrder $event)
    {
        ($event->order->user)->notify(new DriverFoundNotification($event->driver, $event->order));
    }
}

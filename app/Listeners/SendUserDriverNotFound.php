<?php

namespace App\Listeners;

use App\Events\DriverNotFound;
use App\Notifications\DriverNotFoundNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendUserDriverNotFound
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
     * @param  DriverNotFound  $event
     * @return void
     */
    public function handle(DriverNotFound $event)
    {
        ($event->order->user)->notify(new DriverNotFoundNotification($event->order));
    }
}

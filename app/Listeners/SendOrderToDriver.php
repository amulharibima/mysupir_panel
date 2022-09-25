<?php

namespace App\Listeners;

use App\Events\DriverFound;
use App\Notifications\NewOrderNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendOrderToDriver
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
     * @param  DriverFound  $event
     * @return void
     */
    public function handle(DriverFound $event)
    {
        $driver = $event->driver;

        ($driver->user)->notify(new NewOrderNotification($event->order));
    }
}

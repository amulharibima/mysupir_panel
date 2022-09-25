<?php

namespace App\Listeners;

use App\Events\DriverFound;
use App\Events\DriverNotFound;
use App\Events\OrderCreated;
use App\Traits\GetNearestDriver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SearchAvailableDriver
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

    /**
     * Handle the event.
     *
     * @param  object  $event
     *
     * @return void
     */
    public function handle(OrderCreated $event)
    {
        $selectedDriver = $this->searchAvailableDriverWithOrder($event->order);

        if (!empty($selectedDriver)) {
            event(new DriverFound($selectedDriver, $event->order));
        } else {
            event(new DriverNotFound($event->order));
        }
    }
}

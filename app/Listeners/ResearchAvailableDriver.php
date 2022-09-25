<?php

namespace App\Listeners;

use App\Events\DriverDeclineOrder;
use App\Events\DriverFound;
use App\Events\DriverNotFound;
use App\Events\TripBasedOrderCreated;
use App\Traits\GetNearestDriver;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ResearchAvailableDriver
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
    public function handle(DriverDeclineOrder $event)
    {
        $selectedDriver = $this->searchAvailableDriverWithOrder($event->order);

        if (!empty($selectedDriver)) {
            event(new DriverFound($selectedDriver, $event->order));
        } else {
            event(new DriverNotFound($event->order));
        }
    }
}

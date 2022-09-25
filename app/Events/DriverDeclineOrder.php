<?php

namespace App\Events;

use App\Driver;
use App\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DriverDeclineOrder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $driver;
    public $order;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Driver $driver, Order $order)
    {
        $this->driver = $driver;
        $this->order = $order;
    }
}

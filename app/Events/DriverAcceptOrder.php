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

class DriverAcceptOrder
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;
    public $driver;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Order $order, Driver $driver)
    {
        $this->order = $order;
        $this->driver = $driver;
    }
}

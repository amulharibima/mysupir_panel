<?php

namespace App\Events;

use App\Panic;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PanicCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $panic;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Panic $panic)
    {
        $this->panic = $panic;
    }
}

<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderEvents
{
    public const CREATED = 'created';
    public const PAID = 'paid';
    public const STARTING = 'starting'; // User triggering driver to send initial photo
    public const STARTED = 'started';
    public const FINISHING = 'finishing'; // User triggering driver to send final photo
    public const FINISHED = 'finished';
    public const CANCELED = 'canceled';

    // use Dispatchable, InteractsWithSockets, SerializesModels;

    // /**
    //  * Create a new event instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     //
    // }
}

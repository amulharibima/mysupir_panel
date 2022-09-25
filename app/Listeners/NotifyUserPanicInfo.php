<?php

namespace App\Listeners;

use App\Events\PanicCreated;
use App\Notifications\DriverPanicInfoNotification;
use App\Notifications\NotifyDriverPanicInfo;
use App\Notifications\UserPanicInfoNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyUserPanicInfo
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
     * @param  PanicCreated  $event
     * @return void
     */
    public function handle(PanicCreated $event)
    {
        $user = $event->panic->user;

        if($user->hasRole('user')) {
            $user->notify(new UserPanicInfoNotification($event->panic));
        }

        if($user->hasRole('driver')) {
            $user->notify(new DriverPanicInfoNotification($event->panic));
        }
    }
}

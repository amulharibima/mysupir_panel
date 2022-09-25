<?php

namespace App\Listeners;

use App\Events\PanicCreated;
use App\Notifications\UserPanicNotification;
use App\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Queue\InteractsWithQueue;

class NotifyAdminPanicInfo
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
        $admin = $this->getAdmin();

        $admin->notify(new UserPanicNotification($event->panic));
    }

    protected function getAdmin()
    {
        $admin = User::whereHas('roles', function (Builder $query) {
            $query->where('name', 'admin');
        })->first();

        return $admin;
    }
}

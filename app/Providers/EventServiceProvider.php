<?php

namespace App\Providers;

use App\Events\DriverAcceptOrder;
use App\Events\DriverDeclineOrder;
use App\Events\DriverFound;
use App\Events\DriverNotFound;
use App\Events\PanicCreated;
use App\Listeners\CreateChatConversation;
use App\Listeners\MidtransTransactionStatusNotificationSubscriber;
use App\Listeners\NotifyAdminPanicInfo;
use App\Listeners\NotifyUserPanicInfo;
use App\Listeners\OrderListener;
use App\Listeners\ResearchAvailableDriver;
use App\Listeners\SendDriverInfoToUser;
use App\Listeners\SendOrderToDriver;
use App\Listeners\SendUserDriverNotFound;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        // Registered::class => [
        //     SendEmailVerificationNotification::class,
        // ],
        DriverFound::class => [
            SendOrderToDriver::class
        ],
        DriverNotFound::class => [
            SendUserDriverNotFound::class
        ],
        DriverAcceptOrder::class => [
            CreateChatConversation::class,
            SendDriverInfoToUser::class,
        ],
        DriverDeclineOrder::class => [
            ResearchAvailableDriver::class
        ],
        PanicCreated::class => [
            NotifyAdminPanicInfo::class,
            NotifyUserPanicInfo::class,
        ]
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        MidtransTransactionStatusNotificationSubscriber::class,
        OrderListener::class
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}

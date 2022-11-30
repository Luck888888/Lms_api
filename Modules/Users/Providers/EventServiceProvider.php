<?php

namespace Modules\Users\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Users\Listeners\UserChangePasswordEventSubscriber;
use Modules\Users\Listeners\UserNotificationEventSubscriber;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        //
    ];

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        UserNotificationEventSubscriber::class,
        UserChangePasswordEventSubscriber::class,
    ];
}

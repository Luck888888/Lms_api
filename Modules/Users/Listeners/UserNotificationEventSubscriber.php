<?php

namespace Modules\Users\Listeners;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Modules\Users\Events\UserCreateAccount;
use Modules\Users\Notifications\UserCreateAccountNotification;

class UserNotificationEventSubscriber implements ShouldQueue
{
    use Dispatchable;

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
     * @return void
     */
    public function sendUserCreateAccountNotification(UserCreateAccount $event)
    {
        $user = $event->user;
        if ($user->hasRole(['student', 'teacher'])) {
            Notification::send($user, new UserCreateAccountNotification($event->password));
        }
    }

    /**
     * @param $events
     *
     * @return string[]
     */
    public function subscribe($events)
    {
        return [
            UserCreateAccount::class => 'sendUserCreateAccountNotification',
        ];
    }
}

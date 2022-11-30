<?php

namespace Modules\Users\Listeners;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Modules\Users\Events\UserChangePassword;
use Modules\Users\Notifications\UserChangePasswordNotification;

class UserChangePasswordEventSubscriber implements ShouldQueue
{
    use Dispatchable;

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function sendUserChangePasswordNotification(UserChangePassword $event)
    {
        $user = $event->user;
        Notification::send($user, new UserChangePasswordNotification($event->password));
    }

    /**
     * @param $events
     *
     * @return string[]
     */
    public function subscribe($events)
    {
        return [
            UserChangePassword::class => 'sendUserChangePasswordNotification',
        ];
    }
}

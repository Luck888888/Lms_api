<?php

namespace Modules\Users\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserChangePasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $password;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($password)
    {
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail_message = new MailMessage();

        $site_url = config('app.crm_admin_url');

        $mail_message
            ->subject(__('message.user_change_password.title'))
            ->greeting(__('message.greeting', ['username' => $notifiable->full_name]))
            ->line(__('message.user_change_password.line_1'))
            ->line(__('message.user_change_password.email', [ 'email' => $notifiable->email]))
            ->line(__('message.user_change_password.password', ['password' => $this->password]))
            ->action(__('message.user_change_password.action'), $site_url);

        return $mail_message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

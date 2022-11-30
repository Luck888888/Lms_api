<?php

namespace Modules\Curriculums\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Database\Eloquent\Collection;

class CurriculumAddStudentNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $curriculum;
    public $action_url = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($curriculum)
    {
        $this->curriculum = $curriculum;
        $site_url = config('app.crm_admin_url');
        $this->action_url = $site_url . '/curriculums/' . $this->curriculum->id . '/contracts';
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database','broadcast'];
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
        $mail_message
            ->subject(__('message.curriculum_add_student.email.subject'))
            ->greeting(__('message.greeting', ['username' => $notifiable->full_name,]))
            ->line(
                __('message.curriculum_add_student.email.line_1', [
                    "curriculum_name" => $this->curriculum->name,
                    "years_of_study"  => $this->curriculum->years_of_study
                ])
            )
            ->action(__('message.curriculum_add_student.email.action'), $this->action_url);
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
        $title = __('message.curriculum_add_student.database.title');

        $message = __('message.greeting', ['username' => $notifiable->full_name,]);

        $message .= __(
            'message.curriculum_add_student.database.line_1',
            [
                "curriculum_name" => $this->curriculum->name,
                "years_of_study"  => $this->curriculum->years_of_study
            ]
        );

        return get_notification_response(
            $title,
            $message,
            $this->action_url,
            [
                'curriculum_add_student' => [
                    'id'             => $this->curriculum->id,
                    'name'           => $this->curriculum->name,
                    "years_of_study" => $this->curriculum->years_of_study
                ],
                'student'                => [
                    'full_name' => $notifiable->full_name,
                ]
            ]
        );
    }

    /**
     * @param $notifiable
     *
     * @return BroadcastMessage
     */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}

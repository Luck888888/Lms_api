<?php

namespace Modules\Curriculums\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CurriculumSignContractNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $curriculum;
    public $role_user;
    public $student;
    public $action_url = null;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($curriculum, $student, $role_user)
    {
        $this->curriculum = $curriculum;
        $this->student = $student;
        $this->role_user = $role_user;
        $site_url = config('app.crm_admin_url');
        $this->action_url = $site_url . '/curriculums/' . $this->curriculum->id;
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
            ->subject(__('message.curriculum_sign_contract.email.subject'))
            ->greeting(__('message.greeting', ['username' => $notifiable->full_name,]));

        if ($this->role_user == 'student') {
            $mail_message->line(
                __('message.curriculum_sign_contract.email.line_1', [
                    "curriculum_name" => $this->curriculum->name
                ])
            )->action(__('message.curriculum_sign_contract.email.action'), $this->action_url);
        }

        if ($this->role_user == 'administrator') {
            $mail_message->line(
                __('message.curriculum_sign_contract.email.line_2', [
                    "curriculum_name" => $this->curriculum->name,
                    "student_name"    => $this->student->full_name,
                ])
            );
        }
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
        $title = __('message.curriculum_sign_contract.database.title');
        $message = __('message.greeting', ['username' => $notifiable->full_name,]);
        if ($this->role_user == 'student') {
            $message .= __(
                "message.curriculum_sign_contract.database.line_1",
                [
                    "curriculum_name" => $this->curriculum->name
                ]
            );

            $options = [
                'curriculum_sign_contract' => [
                    'id'   => $this->curriculum->id,
                    'name' => $this->curriculum->name,
                ],
                'student'                  => [
                    'full_name' => $notifiable->full_name,
                ]
            ];
        }
        if ($this->role_user == 'administrator') {
            $message .= __(
                "message.curriculum_sign_contract.database.line_2",
                [
                    "curriculum_name" => $this->curriculum->name,
                    "student_name"    => $this->student->full_name,
                ]
            );

            $options = [
                'curriculum_sign_contract' => [
                    'id'   => $this->curriculum->id,
                    'name' => $this->curriculum->name,
                ],
                'student'                  => [
                    'full_name' => $this->student->full_name,
                ]
            ];
        }

        return get_notification_response(
            $title,
            $message,
            $this->action_url,
            $options
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

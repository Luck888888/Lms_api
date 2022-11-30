<?php

namespace Modules\Curriculums\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Modules\Curriculums\Events\CurriculumAddStudent;
use Modules\Curriculums\Notifications\CurriculumAddStudentNotification;
use Modules\Users\Entities\User;

class CurriculumAddStudentEventSubscriber implements ShouldQueue
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
     * @param CurriculumAddStudent $event
     * @return void
     */
    public function sendCurriculumAddStudentNotification(CurriculumAddStudent $event)
    {
        $students = User::find($event->user_ids);

        if ($students) {
            Notification::send($students, new CurriculumAddStudentNotification($event->curriculum));
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
            CurriculumAddStudent::class => 'sendCurriculumAddStudentNotification',
        ];
    }
}

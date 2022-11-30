<?php

namespace Modules\Curriculums\Listeners;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Notification;
use Modules\Curriculums\Events\CurriculumSignContract;
use Modules\Curriculums\Notifications\CurriculumSignContractNotification;
use Modules\Users\Entities\User;

class CurriculumSingContractEventSubscriber implements ShouldQueue
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
     * @param CurriculumSignContract $event
     * @return void
     */
    public function sendCurriculumSignContractStudentNotification(CurriculumSignContract $event)
    {
        $user = User::find($event->student);

        if ($user) {
            Notification::send($user, new CurriculumSignContractNotification($event->curriculum, $user, 'student'));
        }
    }

    /**
     * Handle the event.
     *
     * @param CurriculumSignContract $event
     * @return void
     */
    public function sendCurriculumSignContractAdminNotification(CurriculumSignContract $event)
    {
        $admin = User::role('administrator')->get();
        $user = User::find($event->student);

        if ($admin && $event->curriculum->years_of_study == 3) {
            Notification::send($admin, new CurriculumSignContractNotification($event->curriculum, $user, 'administrator'));
        }
    }

    /**
     * @param $events
     *
     * @return string[]
     */
    public function subscribe($events)
    {

        return[
            $events->listen(
                CurriculumSignContract::class,
                [$this, 'sendCurriculumSignContractStudentNotification']
            ),
            $events->listen(
                CurriculumSignContract::class,
                [$this, 'sendCurriculumSignContractAdminNotification']
            ),
        ];
    }
}

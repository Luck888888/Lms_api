<?php

namespace App\Console;

use App\Console\Commands\TestEmailCommand;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\Courses\Jobs\EndCoursesNotificationsJob;
use Modules\Courses\Jobs\StartCoursesNotificationsJob;
use Modules\Homeworks\Jobs\EndHomeworkNotificationsJob;
use Modules\Homeworks\Jobs\HomeworkReportNotificationsJob;
use Modules\Homeworks\Jobs\StartHomeworkNotificationsJob;
use Modules\Lessons\Jobs\StartLessonNotificationsJob;
use Modules\Tickets\Jobs\TicketExpiredNotificationsJob;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        TestEmailCommand::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->job(new StartLessonNotificationsJob())->daily()->at('18:00');

        $schedule->job(new StartHomeworkNotificationsJob())->daily()->at('09:00');
        $schedule->job(new EndHomeworkNotificationsJob())->daily()->at('18:00');

        $schedule->job(new StartCoursesNotificationsJob())->daily()->at('18:00');
        $schedule->job(new EndCoursesNotificationsJob())->daily()->at('09:00');

        $schedule->job(new HomeworkReportNotificationsJob())->daily()->at('09:00');

        $schedule->job(new TicketExpiredNotificationsJob())->daily()->at('18:00');

        $schedule->command('crm:clean:reset_tokens')->daily()->at('00:00');

        /**
         * For testing
         */
        //$schedule->job(new EndCoursesNotificationsJob)->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}

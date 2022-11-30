<?php

namespace Modules\Curriculums\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\Curriculums\Listeners\CurriculumAddStudentEventSubscriber;
use Modules\Curriculums\Listeners\CurriculumSingContractEventSubscriber;

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
        CurriculumAddStudentEventSubscriber::class,
        CurriculumSingContractEventSubscriber::class,
    ];
}

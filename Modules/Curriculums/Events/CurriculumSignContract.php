<?php

namespace Modules\Curriculums\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Modules\Curriculums\Entities\Curriculum;

class CurriculumSignContract
{
    use SerializesModels;
    use Dispatchable;

    public $curriculum;
    public $student;


    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Curriculum $curriculum, $student)
    {
        $this->curriculum = $curriculum;
        $this->student = $student;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}

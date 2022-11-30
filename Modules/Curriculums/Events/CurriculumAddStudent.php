<?php

namespace Modules\Curriculums\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Curriculums\Entities\Curriculum;

class CurriculumAddStudent
{
    use SerializesModels;
    use Dispatchable;

      public $curriculum;
      public $user_ids;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Curriculum $curriculum, array $user_ids)
    {
         $this->curriculum = $curriculum;
         $this->user_ids = $user_ids;
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

<?php

namespace Modules\Curriculums\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Curriculums\Entities\Curriculum;

class CurriculumStudentsService
{
    /**
     * @param $curriculum_id
     * @param array $user_ids
     *
     * @return mixed
     */
    public function add($curriculum_id, array $user_ids)
    {
        $curriculum = Curriculum::find($curriculum_id);
        if (!$curriculum) {
            throw new ModelNotFoundException("Curriculum not found.", 404);
        }
        $curriculum->students()->syncWithoutDetaching($user_ids);
        $curriculum->refresh();

        return $curriculum->students;
    }

    /**
     * @param $curriculum_id
     * @param array $user_ids
     *
     * @return mixed
     */
    public function delete($curriculum_id, array $user_ids)
    {
        $curriculum = Curriculum::find($curriculum_id);
        if (!$curriculum) {
            throw new ModelNotFoundException("Curriculum not found.", 404);
        }
        $curriculum->students()->detach($user_ids);
        $curriculum->refresh();

        return $curriculum->students;
    }
}

<?php

namespace Modules\Curriculums\Services;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Modules\Curriculums\Entities\Curriculum;
use Modules\Users\Entities\StudentContract;

class CurriculumStudentsContractsService
{
    /**
     * @param $curriculum_id
     * @param $student_id
     *
     * @return StudentContract
     */
    public function get($curriculum_id, $student_id): StudentContract
    {
        $curriculum_contract = StudentContract::where('contractable_id', $curriculum_id)
                                              ->where('student_id', $student_id)
                                              ->where('contractable_type', Curriculum::class)
                                              ->first();

        if (!$curriculum_contract) {
            throw new ModelNotFoundException("Curriculum student contract not found.", 404);
        }
        return $curriculum_contract;
    }

    /**
     * @param $student_id
     * @param $curriculum_id
     *
     * @return mixed
     */
    public function findOrCreate($curriculum_id, $student_id)
    {
        try {
            $curriculum_contract = $this->get($curriculum_id, $student_id);
        } catch (ModelNotFoundException $exception) {
            $curriculum_contract = StudentContract::create([
                'contractable_id' => $curriculum_id,
                'contractable_type' => Curriculum::class,
                'student_id' => $student_id
            ]);
        }
        if (!is_a($curriculum_contract, StudentContract::class)) {
            return false;
        }
        return $curriculum_contract;
    }
}

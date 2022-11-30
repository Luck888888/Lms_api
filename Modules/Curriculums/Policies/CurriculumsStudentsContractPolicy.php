<?php

namespace Modules\Curriculums\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Users\Entities\User;

class CurriculumsStudentsContractPolicy
{
    use HandlesAuthorization;

    /**
     * @param \Modules\Users\Entities\User $user
     * @param $curriculum_id
     *
     * @return bool
     */
    public function createContract(User $user, $curriculum_id): bool
    {
        if ($user->hasRole(['student']) && is_student_has_access_to_curriculum($user->id, $curriculum_id)) {
            return true;
        }

        return false;
    }

    /**
     * @param \Modules\Users\Entities\User $user
     * @param $curriculum_id
     *
     * @return bool
     */
    public function view(User $user, $curriculum_id): bool
    {
        if ($user->hasRole(['student']) && is_student_sign_curriculum_contract($user->id, $curriculum_id)) {
            return false;
        }

        return true;
    }

    /**
     * @param \Modules\Users\Entities\User $user
     * @param $curriculum_id
     *
     * @return bool
     */
    public function viewCourse(User $user, $curriculum_id): bool
    {
        if ($user->hasRole(['administrator','teacher'])) {
            return true;
        }
        if ($user->hasRole(['student']) && is_student_sign_curriculum_contract($user->id, $curriculum_id)) {
                return true;
        }

        return false;
    }
}

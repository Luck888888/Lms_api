<?php

namespace Modules\Curriculums\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Users\Entities\User;

class CurriculumsStudentPolicy
{
    use HandlesAuthorization;

    /**
     * @param \Modules\Users\Entities\User $user
     * @param $curriculum_id
     *
     * @return bool
     */
    public function create(User $user, $curriculum_id): bool
    {
        if ($user->hasRole(['administrator'])) {
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
    public function delete(User $user, $curriculum_id): bool
    {
        if ($user->hasRole(['administrator'])) {
            return true;
        }

        return false;
    }
}

<?php

namespace Modules\Users\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Users\Entities\User;

class UserPolicy
{
    use HandlesAuthorization;


    public function getAll(User $user): bool
    {
        if ($user->hasRole(['administrator', 'teacher'])) {
            return true;
        }
        return false;
    }

    public function view(User $user): bool
    {
        if ($user->hasRole(['administrator', 'teacher'])) {
            return true;
        }
        return false;
    }

    public function create(User $user): bool
    {
        if ($user->hasRole(['administrator'])) {
            return true;
        }

        return false;
    }

    public function update(User $user): bool
    {
        if ($user->hasRole(['administrator'])) {
            return true;
        }

        return false;
    }

    public function delete(User $user, $user_id): bool
    {
        if ($user->hasRole(['administrator']) && $user->id != $user_id) {
            return true;
        }

        return false;
    }

    public function loginAsUser(User $user): bool
    {
        if ($user->hasRole(['administrator'])) {
            return true;
        }

        return false;
    }
}

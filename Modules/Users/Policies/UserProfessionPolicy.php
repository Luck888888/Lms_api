<?php

namespace Modules\Users\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use Modules\Users\Entities\User;

class UserProfessionPolicy
{
    use HandlesAuthorization;

    public function show(User $user): bool
    {
        if ($user->hasRole(['administrator'])) {
            return true;
        }
        return false;
    }
}

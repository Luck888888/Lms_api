<?php

namespace Modules\Users\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Users\Entities\StudentContract;

trait Contractable
{
    public function contracts(): MorphMany
    {
        return $this->morphMany(StudentContract::class, 'contractable');
    }
}

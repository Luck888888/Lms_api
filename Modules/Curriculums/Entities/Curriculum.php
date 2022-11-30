<?php

namespace Modules\Curriculums\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Courses\Entities\Course;
use Modules\Files\Entities\File;
use Modules\Users\Entities\User;
use Modules\Users\Traits\Contractable;

class Curriculum extends Model
{
    use Contractable;

    protected $table = 'curriculums';

    protected $fillable = [
        "name", "description", "start_at", "end_at", "contract_file_id", "years_of_study"
    ];

    protected $dates = [
        "start_at", "end_at", "updated_at", "created_at"
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function students()
    {
        return $this->belongsToMany(User::class, "curriculum_student");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function contractFile(): BelongsTo
    {
        return $this->belongsTo(File::class, 'contract_file_id');
    }
}

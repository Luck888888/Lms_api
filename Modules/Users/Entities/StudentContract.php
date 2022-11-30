<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Model;

class StudentContract extends Model
{
    protected $fillable = [
        'student_id','contractable_id','contractable_type'
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function contractable()
    {
        return $this->morphTo();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}

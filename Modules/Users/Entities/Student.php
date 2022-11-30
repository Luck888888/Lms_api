<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Curriculums\Entities\Curriculum;
use Modules\Files\Entities\File;
use Modules\Homeworks\Entities\HomeworkReport;
use Spatie\Permission\Traits\HasRoles;

class Student extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use HasRoles;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'full_name', 'birth_date', 'phone',
        'address', 'sex', 'status', 'profession', 'is_archived', 'passport',
        'religion', 'religion_type'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_archived'       => 'boolean'
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'created_at', 'updated_at', 'birth_date'
    ];

    /**
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = bcrypt($value);
        }
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function photo()
    {
        return $this->belongsTo(File::class, 'photo_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function curriculums()
    {
        return $this->belongsToMany(Curriculum::class, "curriculum_student");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function homeworks()
    {
        return $this->hasMany(HomeworkReport::class, 'student_id');
    }
}

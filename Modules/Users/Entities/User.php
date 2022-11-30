<?php

namespace Modules\Users\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Courses\Entities\Course;
use Modules\Curriculums\Entities\Curriculum;
use Modules\Files\Entities\File;
use Modules\Homeworks\Entities\HomeworkReport;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email', 'password', 'full_name', 'birth_date', 'phone',
        'address', 'sex', 'status', 'profession', 'is_archived', 'passport',
        'religion', 'religion_type','zip_code'
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
        'email_verified_at' => 'datetime'
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class, "course_student");
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function homeworkReports()
    {
        return $this->hasMany(HomeworkReport::class, 'student_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function profession()
    {
        return $this->belongsTo(Profession::class, 'profession_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function contracts()
    {
        return $this->hasMany(StudentContract::class, 'student_id');
    }

    /**
     * @return bool
     */
    public function isUserHasAccess(): bool
    {
        return $this->status == 'enabled';
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */
    public function receivesBroadcastNotificationsOn(): string
    {
        return 'users.' . $this->id;
    }
}

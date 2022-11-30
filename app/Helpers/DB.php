<?php

/**
 * TODO экспериментальный хэлпер для прямых запросов к БД
 * Цель:
 * - сократить время запроса к БД
 * - сделать запросы более оптимальнымии (если получится =) )
 * Доработки:
 * - добавить кеш
 */

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Modules\Courses\Entities\Course;
use Modules\Curriculums\Entities\Curriculum;
use Modules\Users\Entities\User;

if (!function_exists('is_user_has_roles')) {
    function is_user_has_roles($user_id, array $roles)
    {
        return DB::table('users')
                 ->leftJoin('model_has_roles', function ($join) {
                     $join->on('model_has_roles.model_id', '=', 'users.id')
                          ->where('model_has_roles.model_type', '=', User::class);
                 })
                 ->leftJoin('roles', function ($join) {
                     $join->on('roles.id', '=', 'model_has_roles.role_id');
                 })
                 ->where('users.id', $user_id)
                 ->whereIn('roles.name', $roles)
                 ->exists();
    }
}

if (!function_exists('is_user_has_access_to_course')) {
    /**
     * TODO обязательно отрефакторить
     * Проверяем имеет ли пользователь доступ к курсу
     * @param $user_id
     * @param $course_id
     *
     * @return bool
     */
    function is_user_has_access_to_course($user_id, $course_id)
    {

        $users_check = DB::table('course_student')
                           ->where('course_id', $course_id)
                           ->where('user_id', $user_id)
                           ->union(
                               DB::table('course_teacher')
                                 ->where('course_id', $course_id)
                                 ->where('user_id', $user_id)
                           )
                           ->exists();

        return $users_check;
    }
}

if (!function_exists('is_student_has_access_to_course')) {
    /**
     * TODO обязательно отрефакторить
     * Проверяем имеет ли пользователь доступ к курсу
     * @param $user_id
     * @param $course_id
     *
     * @return bool
     */
    function is_student_has_access_to_course($user_id, $course_id)
    {

        $students_check = DB::table('course_student')
            ->where('course_id', $course_id)
            ->where('user_id', $user_id)
            ->exists();

        return $students_check;
    }
}

if (!function_exists('is_student_has_access_to_curriculum')) {
    /**
     * Проверяем имеет ли пользователь доступ к учебной программе
     * @param $user_id
     * @param $curriculum_id
     *
     * @return bool
     */
    function is_student_has_access_to_curriculum($user_id, $curriculum_id)
    {
        $students_check = DB::table('curriculum_student')
                            ->where('user_id', $user_id)
                            ->where('curriculum_id', $curriculum_id)
                            ->exists();

        return $students_check;
    }
}

if (!function_exists('is_user_has_access_to_lesson')) {
    /**
     * TODO обязательно отрефакторить, тестовая версия
     * Проверяем имеет ли пользователь доступ к уроку
     * @param $user_id
     * @param $lesson_id
     *
     * @return bool
     */
    function is_user_has_access_to_lesson($user_id, $lesson_id): bool
    {
        return DB::table('lessons')
                 ->leftJoin('course_student', function ($join) use ($user_id) {
                     $join->on('lessons.course_id', '=', 'course_student.course_id')
                          ->where('course_student.user_id', '=', $user_id);
                 })
                 ->leftJoin('course_teacher', function ($join) use ($user_id) {
                     $join->on('lessons.course_id', '=', 'course_teacher.course_id')
                          ->where('course_teacher.user_id', '=', $user_id);
                 })
                 ->where('lessons.id', $lesson_id)
                 ->exists();
    }
}

if (!function_exists('is_user_has_access_to_homework')) {
    /**
     * TODO обязательно отрефакторить
     * Проверяем имеет ли пользователь доступ к уроку
     * @param $user_id
     * @param $homework_id
     *
     * @return bool
     */
    function is_user_has_access_to_homework($user_id, $homework_id): bool
    {
        return DB::table('homeworks')
          ->leftJoin('course_student', function ($join) use ($user_id) {
              $join->on('homeworks.course_id', '=', 'course_student.course_id')
                   ->where('course_student.user_id', '=', $user_id);
          })
          ->leftJoin('course_teacher', function ($join) use ($user_id) {
              $join->on('homeworks.course_id', '=', 'course_teacher.course_id')
                   ->where('course_teacher.user_id', '=', $user_id);
          })
          ->where('homeworks.id', $homework_id)
          ->where(function ($query) {
              $query->whereNotNull(['course_student.user_id', 'course_teacher.user_id'], 'OR');
          })
          ->exists();
    }
}

if (!function_exists('is_user_can_update_or_create_homework_reports')) {
    /**
     * TODO обязательно отрефакторить
     *
     * @param $user_id
     * @param $homework_id
     *
     * @return bool
     */
    function is_user_can_update_or_create_homework_reports($user_id, $homework_id): bool
    {
        $now = \Carbon\Carbon::now()->toDateTimeString();

        return DB::table('homeworks')
                 ->leftJoin('course_student', function ($join) use ($user_id) {
                     $join->on('homeworks.course_id', '=', 'course_student.course_id')
                          ->where('course_student.user_id', '=', $user_id);
                 })
                 ->leftJoin('homework_report_additional_access', function ($join) use ($user_id) {
                     $join->on('homeworks.id', '=', 'homework_report_additional_access.homework_id')
                          ->where('homework_report_additional_access.student_id', '=', $user_id);
                 })
                 ->where('homeworks.id', $homework_id)
                 ->whereNotNull('course_student.user_id')
                 ->whereRaw("(( '$now' between homeworks.open_at AND homeworks.close_at ) OR ( '$now' between homework_report_additional_access.open_at AND homework_report_additional_access.close_at ))")
                 ->exists();
    }
}

if (!function_exists('is_course_survey_opened')) {
    /**
     * TODO Выполнить рефакторинг разобраться с часовыми поясами
     * @param $course_id
     *
     * @return bool
     */
    function is_course_survey_opened($course_id, $user): bool
    {
//        $sub_days = (int) config('courses.survey.open_before_end');
//        $duration = (int) config('courses.survey.duration');
//        $add_days = $duration - $sub_days - 1;
//
//        return DB::table('courses')
//          ->where('id', $course_id)
//          ->whereRaw('( now() between date_sub(end_at, INTERVAL ' . $sub_days . ' DAY) AND date_add(end_at, INTERVAL ' . $add_days . ' DAY)) ')
//          ->exists();

        $course = \Modules\Courses\Entities\Course::find($course_id);

        if (!$course) {
            return false;
        }
        return $course->getIsSurveyOpened($user);
    }
}

if (!function_exists('get_course_students_count')) {
    /**
     * @param $course_id
     *
     * @return int
     */
    function get_course_students_count($course_id): int
    {
        //$ttl = 60 * 60 * 24; 1 day
        $ttl = 5 * 60; //5 min
        return Cache::remember(
            'course_' . $course_id . '_students_count',
            $ttl,
            function () use ($course_id) {
                return DB::table('course_student')
                         ->where('course_id', $course_id)
                         ->count();
            }
        );
    }
}

if (!function_exists('get_course_contracts')) {
    /**
     * @param $course_id
     *
     * @return \Illuminate\Support\Collection
     */
    function get_course_contracts($course_id): Collection
    {
        $contracts = DB::table('course_student')
                       ->select([
                                    'users.id', 'users.full_name', 'files.adapter', 'files.path', 'files.filename', 'files.created_at'
                                ])
                       ->leftJoin('users', function ($join) {
                           $join->on('users.id', '=', 'course_student.user_id');
                       })
                       ->leftJoin('course_contracts', function ($join) {
                           $join->on('course_contracts.course_id', '=', 'course_student.course_id')
                                ->on('course_contracts.user_id', '=', 'users.id');
                       })
                       ->leftJoin('files', function ($join) {
                           $join->on('course_contracts.file_id', '=', 'files.id');
                       })
                       ->where('course_student.course_id', $course_id)
                       ->get();

        return $contracts;
    }
}
if (!function_exists('get_student_courses_complete_statuses')) {
    /**
     * @param $student_id
     *
     * @return array
     */
    function get_student_courses_complete_statuses($student_id): array
    {
        $result = DB::table('course_student')
                       ->where('course_student.user_id', $student_id)
                       ->get();

        return $result->pluck('is_completed', 'course_id')->toArray();
    }
}

if (!function_exists('get_teachers_by_homework_id')) {
    /**
     * @param $homework
     *
     * @return object
     */
    function get_teachers_by_homework_id($homework): object
    {
        $user_ids = DB::table('homeworks')
            ->leftJoin('course_teacher', function ($join) use ($homework) {
                $join->on('homeworks.course_id', '=', 'course_teacher.course_id');
            })
            ->where('homeworks.id', $homework)
            ->whereNotNull('course_teacher.user_id')
            ->pluck('user_id');

        $teachers = User::find($user_ids);

        return $teachers;
    }
}

if (!function_exists('get_passed_course_survey_students_ids')) {

    /**
     * @param $course_id
     *
     * @return array
     */
    function get_passed_course_survey_students_ids($course_id): array
    {
        $user_ids = DB::table('survey_answers')
            ->select('participant_id')
            ->where('course_id', $course_id)
            ->whereNotNull('participant_id')
            ->distinct()
            ->pluck('participant_id')->toArray();

        return $user_ids;
    }
}

if (!function_exists('is_student_has_access_to_curriculum_after_sign_contract')) {
    /**
     * @param $user_id
     * @param $curriculum_id
     *
     * @return boolean
     */
    function is_student_sign_curriculum_contract($user_id, $curriculum_id): bool
    {

        $students_check = DB::table('student_contracts')
                            ->where('student_id', $user_id)
                            ->where('contractable_id', $curriculum_id)
                            ->where('contractable_type', Curriculum::class)
                            ->exists();

        return $students_check;
    }
}

if (!function_exists('is_student_has_access_to_course_after_sign_contract')) {
    /**
     * @param $user_id
     * @param $course_id
     *
     * @return boolean
     */
    function is_student_sign_course_contract($user_id, $course_id): bool
    {

        $students_check = DB::table('student_contracts')
                             ->where('student_id', $user_id)
                             ->where('contractable_id', $course_id)
                             ->where('contractable_type', Course::class)
                             ->exists();

        return $students_check;
    }
}

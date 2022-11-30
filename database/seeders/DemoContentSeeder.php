<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Modules\Courses\Dto\CourseDto;
use Modules\Courses\Services\CourseService;
use Modules\Courses\Services\CourseStudentsService;
use Modules\Curriculums\Dto\CurriculumDto;
use Modules\Curriculums\Services\CurriculumService;
use Modules\Curriculums\Services\CurriculumStudentsService;
use Modules\Files\Dto\AttachmentDto;
use Modules\Files\Services\AttachmentService;
use Modules\Homeworks\Dto\HomeworkDto;
use Modules\Homeworks\Services\HomeworkService;
use Modules\Lessons\Dto\LessonDto;
use Modules\Lessons\Services\LessonService;
use Modules\Users\Entities\User;

class DemoContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $curriculum_service = new CurriculumService();
        $curriculum_student_service = new CurriculumStudentsService();

        $course_service = new CourseService();
        $course_student_service = new CourseStudentsService();

        $lesson_service = new LessonService();

        $homework_service = new HomeworkService();

        $attachment_service = new AttachmentService();

        $path = database_path("storage/demo_content.json");
        $arr = json_file_to_array($path);
        foreach ($arr["curriculums"] as $curriculum_data) {
            $curriculum_dto = new CurriculumDto([
                "name"        => $curriculum_data["name"],
                "description" => $curriculum_data["description"],
                "start_at"    => $curriculum_data["start_at"],
                "end_at"      => $curriculum_data["end_at"],
            ]);
            $curriculum = $curriculum_service->create($curriculum_dto);

            $userIds = User::whereIn('email', $curriculum_data['students'])->pluck('id')->toArray();
            $curriculum_student_service->add($curriculum->id, $userIds);

            foreach ($curriculum_data['courses'] as $course_data) {
                $teacherIds = User::whereIn('email', $course_data['teachers'])->pluck('id')->toArray();

                $course_dto = new CourseDto([
                    "name"          => $course_data["name"],
                    "description"   => $course_data["description"],
                    "start_at"      => $course_data["start_at"],
                    "end_at"        => $course_data["end_at"],
                    "is_active"     => $course_data["is_active"],
                    "teachers"      => $teacherIds,
                    "curriculum_id" => $curriculum["id"]
                ]);
                $course = $course_service->create($course_dto);
                $course_student_service->add($course->id, $userIds);

                foreach ($course_data['lessons'] as $lesson_data) {
                    $lesson_dto = new LessonDto([
                        "name"        => $lesson_data["name"],
                        "description" => $lesson_data["description"],
                        "start_at"    => $lesson_data["start_at"],
                        "end_at"      => $lesson_data["end_at"],
                        "course_id"   => $course["id"]
                    ]);
                    $lesson = $lesson_service->create($lesson_dto);

                    foreach ($lesson_data["attachments"] as $attachment_data) {
                        $attachment_dto = new AttachmentDto([
                            "name"  => $attachment_data["name"],
                            "type"  => $attachment_data["type"],
                            "value" => $attachment_data["value"],
                            "file"  => UploadedFile::fake()->image('test.jpg')
                        ]);
                        $attachment_service->create($attachment_dto);
                    }
                }

                foreach ($course_data['homeworks'] as $homework_data) {
                    $homework_dto = new HomeworkDto([
                        "name"        => $homework_data["name"],
                        "description" => $homework_data["description"],
                        "open_at"     => $homework_data["open_at"],
                        "close_at"    => $homework_data["close_at"],
                        "course_id"   => $course["id"],
                        "lessons"     => [$lesson->id],
                    ]);
                    $homework_service->create($homework_dto);

                    foreach ($homework_data["attachments"] as $attachment_data) {
                        $attachment_dto = new AttachmentDto([
                            "name"  => $attachment_data["name"],
                            "type"  => $attachment_data["type"],
                            "value" => $attachment_data["value"],
                            "file"  => UploadedFile::fake()->image('test.jpg')
                        ]);
                        $attachment_service->create($attachment_dto);
                    }
                }
            }
        }
    }
}

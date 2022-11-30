<?php

namespace Modules\Curriculums\Tests\Feature\CurriculumsStudent;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Curriculums\Entities\Curriculum;
use Modules\Users\Entities\User;
use Modules\Users\Tests\TestAuthUser;

class ApiCurriculumsStudentTest extends TestAuthUser
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->withExceptionHandling();
        $this->refreshDatabase();
    }

    /**
     * Run a specific seeder before each test.
     *
     * @var string
     */
    protected $seeder = DatabaseSeeder::class;

    /** Index  **/
    public function test_curriculum_student_index_without_token()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders([])
             ->getJson('/api/v1/curriculums/'.$curriculum_id.'/students', [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_student_index_not_found()
    {
        $curriculum_id = 111111;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->getJson('/api/v1/curriculums/'.$curriculum_id.'/students', [])
             ->assertStatus(404)
             ->assertJson(['success' => false]);
        $this->assertAuthenticated();
    }

    public function test_curriculum_student_index_with_permissions_teacher()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
                         ->getJson('/api/v1/curriculums/'.$curriculum_id.'/students', [])
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'curriculum' => [
                    'id',
                    'name',
                    'students' => [
                        '*' => [
                            'id',
                            'full_name',
                            'email',
                            'photo_url'
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function test_curriculum_student_index_with_permissions_student()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
                         ->getJson('/api/v1/curriculums/'.$curriculum_id.'/students', [])
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'curriculum' => [
                    'id',
                    'name',
                    'students' => [
                        '*' => [
                            'id',
                            'full_name',
                            'email',
                            'photo_url'
                        ]
                    ]
                ]
            ]
        ]);
    }

    public function test_curriculum_student_index_with_permissions_admin()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->getJson('/api/v1/curriculums/'.$curriculum_id.'/students', [])
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'curriculum' => [
                    'id',
                    'name',
                    'students' => [
                        '*' => [
                            'id',
                            'full_name',
                            'email',
                            'photo_url'
                        ]
                    ]
                ]
            ]
        ]);
    }

    /** Store  **/
    public function test_curriculum_student_store_without_token()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders([])
             ->postJson('/api/v1/curriculums/'.$curriculum_id.'/students', [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_student_store_not_found()
    {
        $curriculum_id=111111;

        $student_id = User::where('email','student1@test.com')->pluck('id');

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->postJson('/api/v1/curriculums/'.$curriculum_id.'/students',["students" => [$student_id[0]]])
             ->assertStatus(404)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_student_store_invalid_data()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->postJson('/api/v1/curriculums/'.$curriculum_id.'/students',["students" => ['fail']])
             ->assertStatus(400)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_student_store_without_permissions_teacher()
    {
         $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

         $student_id = User::whereIn('email',['student1@test.com','student2@test.com'])->pluck('id');

           $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
                ->postJson('/api/v1/curriculums/' . $curriculum_id . '/students',["students" =>  [$student_id[0],$student_id[1]]])
                ->assertStatus(403)
                ->assertJson(['success' => false]);
    }

    public function test_curriculum_student_store_without_permissions_student()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $student_id = User::whereIn('email',['student1@test.com','student2@test.com'])->pluck('id');

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
             ->postJson('/api/v1/curriculums/' . $curriculum_id . '/students',["students" => [$student_id[0],$student_id[1]]])
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_student_store_successful()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $student_id = User::whereIn('email',['student1@test.com','student2@test.com'])->pluck('id');

      $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                       ->postJson('/api/v1/curriculums/' . $curriculum_id . '/students',["students" => [$student_id[0],$student_id[1]]])
                       ->assertStatus(200)
                       ->assertJson(['success' => true]);
                 $this->assertAuthenticated();
                 $this->assertArrayHasKey('data', $response);
                 $this->assertDatabaseHas('curriculum_student', [
                     'curriculum_id' => $curriculum_id,
                     'user_id'       => ["students" => [$student_id[0],$student_id[1]]],
                 ]);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                '*' => [
                    'id',
                    'full_name',
                    'email',
                    'photo_url'
                ]
            ]
        ]);
    }

    /** Destroy  **/
    public function test_curriculum_student_destroy_without_token()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders([])
             ->deleteJson('/api/v1/curriculums/'.$curriculum_id.'/students', [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_student_destroy_without_permissions_teacher()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $student_id = User::whereIn('email',['student1@test.com','student2@test.com'])->pluck('id');

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
             ->deleteJson('/api/v1/curriculums/'.$curriculum_id.'/students',["students" => [$student_id[0],$student_id[1]]])
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_student_destroy_without_permissions_student()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $student_id = User::whereIn('email',['student1@test.com','student2@test.com'])->pluck('id');

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
             ->deleteJson('/api/v1/curriculums/'.$curriculum_id.'/students',["students" => [$student_id[0],$student_id[1]]])
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_student_destroy_not_found()
    {
        $curriculum_id = 111111;

        $student_id = User::whereIn('email',['student1@test.com','student2@test.com'])->pluck('id');

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->deleteJson('/api/v1/curriculums/'.$curriculum_id.'/students',["students" => [$student_id[0],$student_id[1]]])
             ->assertStatus(404)
             ->assertJson(['success' => false]);
        $this->assertAuthenticated();
    }

    public function test_curriculum_student_destroy_successful()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $student_id = User::whereIn('email',['student1@test.com','student2@test.com'])->pluck('id');

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->deleteJson('/api/v1/curriculums/'.$curriculum_id.'/students',["students" => [$student_id[0],$student_id[1]]])
             ->assertStatus(200)
             ->assertJson(['success' => true]);
        $this->assertAuthenticated();
        $this->assertDatabaseMissing('curriculum_student', [
            'curriculum_id' => $curriculum_id,
            'user_id'       => ["students" => [$student_id[0],$student_id[1]]]
        ]);
    }

}

<?php

namespace Modules\Curriculums\Tests\Feature\Curriculums;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Curriculums\Entities\Curriculum;
use Modules\Users\Tests\TestAuthUser;

class ApiCurriculumsTest extends TestAuthUser
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
    public function test_curriculum_without_token()
    {
        $this->withHeaders([])
             ->getJson('/api/v1/curriculums', [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_index_admin_successful()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->getJson('/api/v1/curriculums', [])
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'start_at',
                        'end_at',
                        'created_at'
                    ]
                ]
            ]
        ]);
    }

    public function test_curriculum_index_teacher_successful()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
                         ->getJson('/api/v1/curriculums', [])
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
        $this->assertAuthenticated();
        $this->assertArrayHasKey('data', $response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'start_at',
                        'end_at',
                        'created_at'
                    ]
                ]
            ]
        ]);
    }

    public function test_curriculum_index_student_successful()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
                         ->getJson('/api/v1/curriculums', [])
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'data' => [
                    '*' => [
                        'id',
                        'name',
                        'description',
                        'start_at',
                        'end_at',
                        'created_at'
                    ]
                ]
            ]
        ]);
    }

    /** Show  **/
    public function test_curriculum_show_without_token()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders([])
             ->getJson('/api/v1/curriculums/'.$curriculum_id, [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_show_admin_successful()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->getJson('/api/v1/curriculums/'.$curriculum_id, [])
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'description',
                'students' => [
                    '*' => [
                        'id',
                        'full_name',
                        'email',
                        'photo_url'
                    ]
                ],
                'courses'  => [
                    '*' => [
                        'id',
                        'name',
                        'start_at',
                        'end_at'
                    ]
                ],
                'start_at',
                'end_at'
            ]
        ]);
    }

    public function test_curriculum_show_teacher_successful()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
                         ->getJson('/api/v1/curriculums/'.$curriculum_id, [])
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'description',
                'students' => [
                    '*' => [
                        'id',
                        'full_name',
                        'email',
                        'photo_url'
                    ]
                ],
                'courses'  => [
                    '*' => [
                        'id',
                        'name',
                        'start_at',
                        'end_at'
                    ]
                ],
                'start_at',
                'end_at'
            ]
        ]);
    }

    public function test_curriculum_show_student_successful()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
                         ->getJson('/api/v1/curriculums/'.$curriculum_id, [])
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'description',
                'students' => [
                    '*' => [
                        'id',
                        'full_name',
                        'email',
                        'photo_url'
                    ]
                ],
                'courses'  => [
                    '*' => [
                        'id',
                        'name',
                        'start_at',
                        'end_at'
                    ]
                ],
                'start_at',
                'end_at'
            ]
        ]);
    }

    public function test_curriculum_show_not_found()
    {
        $curriculum_id = 111111;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->getJson('/api/v1/curriculums/'.$curriculum_id, [])
             ->assertStatus(404)
             ->assertJson(['success' => false]);
        $this->assertAuthenticated();
    }

    /** Store  **/
    public function test_curriculum_store_without_token()
    {
        $this->withHeaders([])
             ->postJson('/api/v1/curriculums/', [])
             ->assertStatus(401)
             ->assertJson([
                'success' => false,
                'message' => 'Unauthenticated.'
            ]);
    }

    public function test_curriculum_store_without_permissions_teacher()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
             ->postJson('/api/v1/curriculums/')
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_store_without_permissions_student()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
             ->postJson('/api/v1/curriculums/')
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_store_fail()
    {
        $curriculum = [
            'start_at' => 'fail',
            'end_at'   => 'fail'
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
              ->postJson('/api/v1/curriculums/', $curriculum)
              ->assertStatus(400)
              ->assertJson(["success" => false])
              ->assertJsonStructure([
                  'success',
                  'message',
                  'data' => [
                      'start_at',
                      'end_at'
                  ]
              ]);
    }

    public function test_curriculum_store_successful()
    {
        $curriculum = [
            'name'        => 'test',
            'description' => 'test',
            'start_at'    => '01-04-2022',
            'end_at'      => '17-09-2022'
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->postJson('/api/v1/curriculums/', $curriculum)
                         ->assertStatus(201);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
                    $this->assertDatabaseHas('curriculums', [
                        'name'        => $curriculum['name'],
                        'description' => $curriculum['description'],]);
        $response->assertJson(["success" => true]);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'description',
                'students',
                'courses',
                'start_at',
                'end_at'
            ]
        ]);
        $curriculum_response = Curriculum::find($response['data']['id']);
        $this->assertEquals($curriculum['name'], $curriculum_response->name);
        $this->assertEquals($curriculum['description'], $curriculum_response->description);
    }

    /** Update  **/
    public function test_curriculum_update_without_token()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders([])
             ->patchJson('/api/v1/curriculums/'.$curriculum_id, [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_update_without_permissions_teacher()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
             ->patchJson('/api/v1/curriculums/'.$curriculum_id,[])
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_update_without_permissions_student()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
             ->patchJson('/api/v1/curriculums/'.$curriculum_id,[])
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_update_not_found()
    {
        $curriculum_id = 111111;

        $curriculum = [
            'name'        => 'test',
            'description' => 'test',
            'start_at'    => '01-04-2022',
            'end_at'      => '17-09-2022'
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->patchJson('/api/v1/curriculums/'.$curriculum_id, $curriculum)
             ->assertStatus(404)
             ->assertJson(['success' => false]);
        $this->assertAuthenticated();
    }

    public function test_curriculum_update_fail()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $curriculum = [
            'name'        => '',
            'description' => '',
            'start_at'    => 'fail',
            'end_at'      => 'fail'
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->patchJson('/api/v1/curriculums/'. $curriculum_id, $curriculum)
             ->assertStatus(400)
             ->assertJson(['success' => false])
             ->assertJsonStructure([
                "success" ,
                'message',
                 'data' => [
                     "name",
                     "description",
                     "start_at",
                     "end_at"
                 ]
             ]);
    }

    public function test_curriculum_update_successful()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $curriculum = [
            'name'        => 'test',
            'description' => 'test',
            'start_at'    => '01-04-2022',
            'end_at'      => '17-09-2022'
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->patchJson('/api/v1/curriculums/'.$curriculum_id, $curriculum)
                         ->assertStatus(200);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
                    $this->assertDatabaseHas('curriculums', [
                        'name'        => $curriculum['name'],
                        'description' => $curriculum['description']]);
        $response->assertJson(["success" => true]);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'name',
                'description',
                'students' => [
                    '*' => [
                        'id',
                        'full_name',
                        'email',
                        'photo_url'
                    ]
                ],
                'courses'  => [
                    '*' => [
                        'id',
                        'name',
                        'start_at',
                        'end_at'
                    ]
                ],
                'start_at',
                'end_at'
            ]
        ]);
        $curriculum_response = Curriculum::find($response['data']['id']);
        $this->assertEquals($curriculum['name'], $curriculum_response->name);
        $this->assertEquals($curriculum['description'], $curriculum_response->description);
    }

    /** Destroy  **/
    public function test_curriculum_destroy_without_token()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders([])
             ->deleteJson('/api/v1/curriculums/'.$curriculum_id, [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_destroy_without_permissions_teacher()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
             ->deleteJson('/api/v1/curriculums/'.$curriculum_id,[])
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_destroy_without_permissions_student()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
             ->deleteJson('/api/v1/curriculums/'.$curriculum_id,[])
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_curriculum_destroy_not_found()
    {
        $curriculum_id = 111111;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->deleteJson('/api/v1/curriculums/'.$curriculum_id)
             ->assertStatus(404)
             ->assertJson(['success' => false]);
        $this->assertAuthenticated();
    }

    public function test_curriculum_destroy_successful()
    {
        $curriculum_id = Curriculum::where('name', 'Web technologies')->first()->id;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
            ->deleteJson('/api/v1/curriculums/'.$curriculum_id, [])
            ->assertStatus(200)
            ->assertJson(['success' => true]);
        $this->assertAuthenticated();
        $this->assertDatabaseMissing('curriculums', [
            'id' => $curriculum_id,
        ]);
    }

}

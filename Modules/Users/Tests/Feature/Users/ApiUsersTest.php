<?php

namespace Modules\Users\Tests\Feature\Users;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Users\Entities\User;
use Modules\Users\Tests\TestAuthUser;

class ApiUsersTest extends TestAuthUser
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

    public function test_users_index_without_token()
    {
        $this->withHeaders([])
             ->getJson('/api/v1/users', [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_user_index_without_permissions_student()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
             ->getJson('/api/v1/users', [])
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_user_index_permissions_teacher_successful()
    {
      $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
                       ->getJson('/api/v1/users', [])
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
                        'photo_url',
                        'full_name',
                        'email',
                        'roles',
                        'status',
                        'is_archived',
                        'curriculums',
                        'created_at'
                    ]
                ]
            ]
        ]);
    }


    public function test_user_index_admin_successful()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->json('get', '/api/v1/users', [])
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
                        'photo_url',
                        'full_name',
                        'email',
                        'roles',
                        'status',
                        'is_archived',
                        'curriculums',
                        'created_at'
                    ]
                ]
            ]
        ]);
    }

    /** Store  **/

    public function test_user_store_without_token()
    {
        $this->withHeaders([])
             ->postJson('/api/v1/users')
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_user_store_without_permissions_teacher()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
             ->postJson('/api/v1/users')
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_user_store_without_permissions_student()
    {
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
             ->postJson('/api/v1/users')
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_user_store_fail()
    {
        $user_credentials = [
            'email'    => 'fail',
            'password' => 'fail'
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->postJson('/api/v1/users', $user_credentials)
             ->assertStatus(400)
             ->assertJson([
                "success" => false,
                'message' => 'The given data was invalid.',
                'data'    => [
                    "email" => [
                        "The email must be a valid email address."
                    ],
                    "password"=> [
                        "The password must include both upper and lower case letters.",
                        "The password must include at least one number.",
                        "The password must include at least one symbol.",
                        "The password must be at least 6 characters."

                    ]
                ]
            ]);
    }

    public function test_user_store_successful()
    {
        $user_credentials = [
            'email'         => 'student2@gmail.com',
            'password'      => 'Student_123456',
            'passport'      => 'HRS88S99M18',
            'photo_url'     => 'null',
            'full_name'     => 'Michael',
            'birth_date'    => '13-07-1999',
            'phone'         => '+8781218245',
            'address'       => 'Brazil,Broadway 44',
            'sex'           => 'male',
            'status'        => 'enabled',
            'profession'    => 'student',
            'is_archived'   => 0,
            'roles'         => ['student'],
            'religion'      => 'christianity',
            'religion_type' => 'traditional',
            'curriculums'   => [],
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->postJson('/api/v1/users', $user_credentials)
                         ->assertStatus(201);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
        $response->assertJson(["success" => true]);

        $user = User::find($response['data']['id']);
        $this->assertEquals($user_credentials['email'], $user->email);
        $this->assertEquals($user_credentials['passport'], $user->passport);
        $this->assertEquals($user_credentials['full_name'], $user->full_name);
    }

    /** Show  **/

    public function test_user_show_without_token()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $this->withHeaders([])
             ->getJson('/api/v1/users/'.$user_id, [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_user_show_without_permissions_student()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
             ->getJson('/api/v1/users/'.$user_id, [])
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_user_show_with_permissions_teacher_successful()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

       $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
                        ->getJson('/api/v1/users/'.$user_id, [])
                        ->assertStatus(200)
                        ->assertJson(['success' => true]);
                   $this->assertAuthenticated();
                   $this->assertArrayHasKey('data', $response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'email',
                'passport',
                'photo_url',
                'full_name',
                'birth_date',
                'phone',
                'address',
                'sex',
                'status',
                'profession',
                'is_archived',
                'roles',
                'religion',
                'religion_type',
                'curriculums'
            ]
        ]);
    }

    public function test_user_show_not_found()
    {
        $user_id = 111111;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->getJson('/api/v1/users/'.$user_id, [])
             ->assertStatus(404)
             ->assertJson(['success' => false]);
        $this->assertAuthenticated();
    }

    public function test_user_show_successful()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->getJson('/api/v1/users/'.$user_id, [])
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
                    $this->assertAuthenticated();
                    $this->assertArrayHasKey('data', $response);
        $response->assertJsonStructure([
            'success',
            'message',
            'data' => [
                'id',
                'email',
                'passport',
                'photo_url',
                'full_name',
                'birth_date',
                'phone',
                'address',
                'sex',
                'status',
                'profession',
                'is_archived',
                'roles',
                'religion',
                'religion_type',
                'curriculums'
            ]
        ]);
    }

    /** Update  **/

    public function test_user_update_without_token()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $user_update_credentials = [
            'email'    => 'test@gmail.com',
            'password' => 'Ab_123456'
        ];
        $this->withHeaders([])
             ->patchJson('/api/v1/users/'.$user_id, $user_update_credentials)
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_user_update_without_permissions_teacher()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $user_update_credentials = [
            'email'    => 'test@gmail.com',
            'password' => 'Ab_123456'
        ];
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
             ->patchJson('/api/v1/users/'.$user_id, $user_update_credentials)
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_user_update_without_permissions_student()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $user_update_credentials = [
            'email'    => 'test@gmail.com',
            'password' => 'Ab_123456'
        ];
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
             ->patchJson('/api/v1/users/'.$user_id, $user_update_credentials)
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_user_update_not_found()
    {
        $user_id = 111111;

        $user_update_credentials = [
            'email'    => 'test@gmail.com',
            'password' => 'Ab_123456'
        ];
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->patchJson('/api/v1/users/'.$user_id, $user_update_credentials)
             ->assertStatus(404)
             ->assertJson(['success' => false]);
        $this->assertAuthenticated();
    }

    public function test_user_update_fail()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $user_update_credentials = [
            'email'    => 'fail',
            'password' => 'fail'
        ];
        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->patchJson('/api/v1/users/'.$user_id, $user_update_credentials)
             ->assertStatus(400)
             ->assertJson([
                "success" => false,
                'message' => 'The given data was invalid.',
                'data'    => [
                    "email" => [
                        "The email must be a valid email address."
                    ],
                    "password"=> [
                        "The password must include both upper and lower case letters.",
                        "The password must include at least one number.",
                        "The password must include at least one symbol.",
                        "The password must be at least 6 characters."

                    ]
                ]
            ]);
    }

    public function test_user_update_successful()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $user_update_credentials = [
            'email'   => 'test@gmail.com',
            'phone'   => '+8878787878',
            'address' => '5th Avenue',
        ];
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->patchJson('/api/v1/users/'.$user_id,$user_update_credentials)
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
                    $this->assertAuthenticated();
                    $this->assertDatabaseHas('users', [
                        'email'   => $user_update_credentials['email'],
                        'phone'   => $user_update_credentials['phone'],
                        'address' => $user_update_credentials['address'],
                    ]);
        $user = User::find($response['data']['id']);
        $this->assertEquals($user_update_credentials['email'], $user->email);
        $this->assertEquals($user_update_credentials['phone'], $user->phone);
        $this->assertEquals($user_update_credentials['address'], $user->address);
    }

    /** Delete  **/

    public function test_user_destroy_without_token()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $this->withHeaders([])
             ->deleteJson('/api/v1/users/'.$user_id, [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_user_destroy_without_permissions_teacher()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('teacher')])
             ->deleteJson('/api/v1/users/'.$user_id, [])
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_user_destroy_without_permissions_student()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('student')])
             ->deleteJson('/api/v1/users/'.$user_id, [])
             ->assertStatus(403)
             ->assertJson(['success' => false]);
    }

    public function test_user_destroy_not_found()
    {
        $user_id = 111111;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->deleteJson('/api/v1/users/'.$user_id, [])
             ->assertStatus(404)
             ->assertJson(['success' => false]);
        $this->assertAuthenticated();

    }

    public function test_user_destroy_successful()
    {
        $user_id = User::where('email', 'student1@test.com')->first()->id;

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->deleteJson('/api/v1/users/'.$user_id, [])
             ->assertStatus(200)
             ->assertJson(['success' => true]);
        $this->assertAuthenticated();
    }

}

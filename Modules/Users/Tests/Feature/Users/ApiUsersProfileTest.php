<?php

namespace Modules\Users\Tests\Feature\Users;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Users\Entities\User;
use Modules\Users\Tests\TestAuthUser;

class ApiUsersProfileTest extends TestAuthUser
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

    /** Show  **/
    public function test_user_profile_show_without_token()
    {
        $this->withHeaders([])
             ->getJson('/api/v1/profile', [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_user_profile_show_successful()
    {
        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->getJson( '/api/v1/profile', [])
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
    public function test_user_profile_update_empty_credentials()
    {
        $user_profile_credentials = [
            'email'   => '',
            'phone'   => '',
            'address' => '',
        ];

         $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
              ->patchJson( '/api/v1/profile',$user_profile_credentials)
              ->assertStatus(400)
              ->assertJson([
                "success" => false,
                'message' => 'The given data was invalid.',
                'data'    => [
                    "email" => [
                        "The email must be a string.",
                        "The email must be a valid email address."
                    ],
                    "phone" => [
                        "The phone must be a string.",
                        "The phone must be at least 8 characters."
                    ],
                    "address"=> [
                        "The address must be a string."
                    ]
                ]
            ]);
    }

    public function test_user_profile_update_successful()
    {
        $user_profile_credentials = [
            'email'   => 'test@gmail.com',
            'phone'   => '+8878787878',
            'address' => '5th Avenue',
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->patchJson('/api/v1/profile',$user_profile_credentials)
                         ->assertStatus(200)
                         ->assertJson(["success" => true]);
                    $this->assertDatabaseHas('users', [
                        'email'   => $user_profile_credentials['email'],
                        'phone'   => $user_profile_credentials['phone'],
                        'address' => $user_profile_credentials['address'],
                    ]);

        $user = User::find($response['data']['id']);
        $this->assertEquals($user_profile_credentials['email'], $user->email);
        $this->assertEquals( $user_profile_credentials['phone'], $user->phone);
        $this->assertEquals($user_profile_credentials['address'], $user->address);
    }

    /** Profile/Password **/
    public function test_user_profile_update_password_fail()
    {
        $user_profile_credentials = [
            'current_password'   => 'fail123456',
            'password'   => 'Ab123',
            'password_confirmation' => 'Ab*123456',
        ];

        $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
             ->patchJson('/api/v1/profile/password', $user_profile_credentials)
             ->assertStatus(400)
             ->assertJson(['success' => false,
                           'message' => "The given data was invalid.",
                           'data'    => [
                               'current_password' => ['validation.current_password'],
                               'password'         => [
                                   'The password must include at least one symbol.',
                                   'The password must be at least 6 characters.'
                               ],
                           ]
             ]);
    }

    public function test_user_profile_update_password_successful()
    {
        $user_profile_credentials = [
            'current_password'      => 'a123456',
            'password'              => 'Ab_123456',
            'password_confirmation' => 'Ab_123456',
        ];

        $response = $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                         ->patchJson('/api/v1/profile/password',$user_profile_credentials)
                         ->assertStatus(200)
                         ->assertJson(['success' => true]);
        $response->assertSessionHasNoErrors();
    }

}

<?php

namespace Modules\Users\Tests\Feature\Auth;

use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Users\Tests\TestAuthUser;

class AuthenticationUserTest extends TestAuthUser
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

    public function test_user_login_successful()
    {
        $user_login_credentials = ['email' => 'admin@test.com', 'password' => 'a123456'];

            $response = $this->postJson('/api/v1/login', $user_login_credentials)
                             ->assertStatus(200)
                             ->assertJson(['success' => true]);
                        $this->assertArrayHasKey('data', $response);
                        $this->assertArrayHasKey('access_token', $response['data']);
       }

       public function test_user_login_fail_credentials()
       {
           $user_login_fail_credentials = ['email' => 'admin@fail', 'password' => 'a123456fail'];

           $this->json('post', '/api/v1/login',$user_login_fail_credentials)
                ->assertStatus(401)
                ->assertJson(['success' => false]);
           $this->assertGuest();
       }

       public function test_user_login_empty_credentials()
       {
           $user_login_empty_credentials=['email' => '', 'password' => ''];

           $this->json('post', '/api/v1/login',$user_login_empty_credentials)
                ->assertStatus(400)
                ->assertJson(['success' => false, 'message' => 'The given data was invalid.',
                             'data' => [
                                 'email'    => ['The email field is required.'],
                                 'password' => ['The password field is required.'],
                             ]
               ]);
       }

    public function test_user_logout_without_token()
    {
        $this->withHeaders([])
             ->postJson('/api/v1/logout', [])
             ->assertStatus(401)
             ->assertJson(['success' => false]);
    }

    public function test_user_logout_successful()
    {
            $this->withHeaders(['Authorization' => 'Bearer ' . $this->getToken('administrator')])
                 ->postJson('/api/v1/logout', [])
                 ->assertStatus(200)
                 ->assertJson(['success' => true]);
            $this->assertAuthenticated();

    }

}

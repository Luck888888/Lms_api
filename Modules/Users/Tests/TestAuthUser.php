<?php

namespace Modules\Users\Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Tests\CreatesApplication;

abstract class TestAuthUser extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Indicates whether the default seeder should run before each test.
     *
     * @var bool
     */
    protected $seed = true;

    protected function getToken($user_role): string
    {
        $path = module_path("Users", "Resources/storage/users.json");
        $arr = json_file_to_array($path);
        foreach ($arr["data"] as $item) {
            if (in_array($user_role, $item['roles'])) {
                $user_login = [
                    "email"    => $item["email"],
                    "password" => $item["password"]
                ];
                $response = $this->postJson('/api/v1/login', $user_login);
                $token_user = $response['data']['access_token'];
                return $token_user;
            }
        }
        return 0;
    }
}

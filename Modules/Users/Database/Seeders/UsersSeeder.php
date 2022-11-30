<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Modules\Users\Dto\UserDto;
use Modules\Users\Entities\User;
use Modules\Users\Services\UserService;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::reguard();

        $path = module_path("Users", "Resources/storage/users.json");
        $arr = json_file_to_array($path);
        foreach ($arr["data"] as $item) {
            $user_role = new UserDto($item);
            (new UserService())->create($user_role);
        }
    }
}

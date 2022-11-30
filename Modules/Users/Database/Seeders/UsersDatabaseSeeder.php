<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class UsersDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Model::unguard();

        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
//TODO временно закомментировал так как данный Seeder должен запускаться один раз для тестов
//     a RolesSeeder и PermissionsSeeder при каждом деплое их нужно будет разграничить
//            UsersSeeder::class,
        ]);
    }
}

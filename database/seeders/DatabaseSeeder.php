<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Surveys\Database\Seeders\SurveysDatabaseSeeder;
use Modules\Users\Database\Seeders\PermissionsSeeder;
use Modules\Users\Database\Seeders\RolesSeeder;
use Modules\Users\Database\Seeders\UsersSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            RolesSeeder::class,
            PermissionsSeeder::class,
            UsersSeeder::class,
            DemoContentSeeder::class
        ]);
    }
}

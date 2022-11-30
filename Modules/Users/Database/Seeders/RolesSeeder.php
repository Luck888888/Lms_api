<?php

namespace Modules\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $path = module_path("Users", "Resources/storage/roles.json");
        $arr = json_file_to_array($path);
        foreach ($arr["data"] as $item) {
            Role::firstOrCreate([
                "name" => $item["name"],
                "guard_name" => $item["guard_name"]
            ]);
        }
    }
}

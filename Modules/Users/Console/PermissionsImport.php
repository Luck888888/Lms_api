<?php

namespace Modules\Users\Console;

use Illuminate\Console\Command;
use Nwidart\Modules\Facades\Module;
use Spatie\Permission\Models\Permission;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class PermissionsImport
 *
 * @package Modules\Users\Console
 *
 * TODO сделать выбор файла(ов) модуля для выбора
 */
class PermissionsImport extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'modules:permissions:import';

    protected $signature = 'modules:permissions:import {--single : Choose single module for import}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import permissions from modules.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->alert("Start permissions import");

        if ($this->option("single")) {
            $this->importSingleModuleHandler();
        } else {
            $this->importAllModulesHandler();
        }

        $this->info("Permissions imported");
    }

    /**
     *
     */
    public function importAllModulesHandler()
    {
        $modules = Module::all();
        foreach ($modules as $key => $module) {
            $this->importModule($key);
        }
    }

    /**
     *
     */
    public function importSingleModuleHandler()
    {
        $modules = array_keys(Module::all());
        $module_key = $this->choice('Choose module:', $modules);
        if ($module_key) {
            $this->importModule($module_key);
        }
    }

    /**
     * @param $module_key
     */
    public function importModule($module_key)
    {
        $path = module_path($module_key, "Resources/storage/permissions.json");
        if (file_exists($path)) {
            $this->info("Start import from $module_key module");
            $arr = json_file_to_array($path);
            foreach ($arr["data"] as $item) {
                $permission = Permission::firstOrCreate(
                    [
                        "name"        => $item["name"],
                        "description" => $item["description"],
                        "guard_name"  => $item["guard_name"],
                    ]
                );
                if ($permission) {
                    $permission->syncRoles($item['roles']);
                }
            }
            $this->info("Import from $module_key module finished");
        }
    }
}

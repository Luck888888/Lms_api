<?php

namespace Modules\Users\Providers;

use App\Traits\Policiesable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Modules\Users\Console\CleanReseatTokensCommand;
use Modules\Users\Console\PermissionsImport;
use Modules\Users\Entities\User;
use Modules\Users\Policies\UserPolicy;
use Modules\Users\Policies\UserProfessionPolicy;

class UsersServiceProvider extends ServiceProvider
{
    use Policiesable;

    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Users';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'users';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->validators();
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->commands([
            PermissionsImport::class,
            CleanReseatTokensCommand::class,
        ]);
        $this->registerPolicies([
            User::class                 => UserPolicy::class,
            UserProfessionPolicy::class => UserProfessionPolicy::class,
        ]);

        Validator::extend('validation_roles', function ($attribute, $value, $parameters, $validator) {
            if (is_array($value) && in_array('student', $value) && count($value) > 1) {
                $validator->setCustomMessages(['validation_roles' => 'Invalid set of role fields']);
                return false;
            }
            return true;
        });

        Validator::extend('profession_check', function ($attribute, $value, $parameters, $validator) {
            if (is_numeric($value) && $value > 0) {
                $profession_check_id = DB::table('professions')->where('id', $value)->exists();
                if (!$profession_check_id) {
                    $validator->setCustomMessages(['profession_check' => 'Invalid set of profession fields']);
                    return false;
                } else {
                    return true;
                }
            }
            if (!is_string($value) || empty(trim($value))) {
                $validator->setCustomMessages(['profession_check' => 'Profession name is incorrect']);
                return false;
            }
            return true;
        });
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouteServiceProvider::class);
        $this->app->register(EventServiceProvider::class);
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'),
            $this->moduleNameLower
        );
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [];
    }

    /**
     *
     */
    public function validators()
    {
    }
}

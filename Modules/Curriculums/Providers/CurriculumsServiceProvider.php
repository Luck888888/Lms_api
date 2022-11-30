<?php

namespace Modules\Curriculums\Providers;

use App\Traits\Policiesable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Factory;
use Modules\Curriculums\Policies\CurriculumsStudentPolicy;
use Modules\Curriculums\Policies\CurriculumsStudentsContractPolicy;

class CurriculumsServiceProvider extends ServiceProvider
{
    use Policiesable;

    /**
     * @var string $moduleName
     */
    protected $moduleName = 'Curriculums';

    /**
     * @var string $moduleNameLower
     */
    protected $moduleNameLower = 'curriculums';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));
        $this->registerPolicies([
            CurriculumsStudentPolicy::class          => CurriculumsStudentPolicy::class,
            CurriculumsStudentsContractPolicy::class => CurriculumsStudentsContractPolicy::class,
        ]);

        Validator::extend('sign_contract', function ($attribute, $value, $parameters, $validator) {
            if (!in_array($value, [1, true])) {
                $validator->setCustomMessages(['sign_contract' => $attribute . ' attribute must be true']);
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
}

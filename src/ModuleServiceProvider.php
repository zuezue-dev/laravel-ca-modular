<?php

namespace Escapepixel\LaravelCAModules;

use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{

    protected $commands = [
        'Escapepixel\LaravelCAModules\Commands\DeleteModuleCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeControllerCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeMigrationCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeModuleCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeRequestCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeResourceCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeEntityCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeDriverInterfaceCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeDriverImplementCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeProviderCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeRouteProviderCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeUseCaseInterfaceCommand',
        'Escapepixel\LaravelCAModules\Commands\MakeTestCommand',
    ];

    /**
     * Booting the package.
     */
    public function boot()
    {
        $configPath = __DIR__ . '/../config/config.php';
        $stubsPath = dirname(__DIR__) . '/src/Commands/stubs';

        $this->publishes([
            $configPath => config_path('modules.php'),
        ], 'config');

        $this->publishes([
            $stubsPath => base_path('stubs/modules'),
        ], 'stubs');
    }

    /**
     * Register all modules.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'modules');
        $this->commands($this->commands);
    }

}

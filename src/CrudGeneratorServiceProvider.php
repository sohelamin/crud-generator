<?php

namespace Appzcoder\CrudGenerator;

use Illuminate\Support\ServiceProvider;

use Appzcoder\CrudGenerator\Commands\CrudCommand;
use Appzcoder\CrudGenerator\Commands\CrudControllerCommand;
use Appzcoder\CrudGenerator\Commands\CrudModelCommand;
use Appzcoder\CrudGenerator\Commands\CrudMigrationCommand;
use Appzcoder\CrudGenerator\Commands\CrudViewCommand;
use Appzcoder\CrudGenerator\Commands\CrudLangCommand;
use Appzcoder\CrudGenerator\Commands\CrudApiCommand;
use Appzcoder\CrudGenerator\Commands\CrudApiControllerCommand;

class CrudGeneratorServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/crudgenerator.php' => config_path('crudgenerator.php'),
        ]);

        $this->publishes([
            __DIR__ . '/../publish/views/' => base_path('resources/views/'),
        ]);

        if (\App::VERSION() <= '5.2') {
            $this->publishes([
                __DIR__ . '/../publish/css/app.css' => public_path('css/app.css'),
            ]);
        }

        $this->publishes([
            __DIR__ . '/stubs/' => base_path('resources/crud-generator/'),
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(
            CrudCommand::class,
            CrudControllerCommand::class,
            CrudModelCommand::class,
            CrudMigrationCommand::class,
            CrudViewCommand::class,
            CrudLangCommand::class,
            CrudApiCommand::class,
            CrudApiControllerCommand::class
        );
    }
}

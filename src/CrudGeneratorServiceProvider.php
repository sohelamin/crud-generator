<?php namespace Appzcoder\CrudGenerator;

use Illuminate\Support\ServiceProvider;

class CrudGeneratorServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
        $this->commands(
        	'Appzcoder\CrudGenerator\Commands\AppzcoderCrudCommand',
			'Appzcoder\CrudGenerator\Commands\AppzcoderCrudControllerCommand',
			'Appzcoder\CrudGenerator\Commands\AppzcoderCrudModelCommand',
			'Appzcoder\CrudGenerator\Commands\AppzcoderCrudMigrationCommand',
			'Appzcoder\CrudGenerator\Commands\AppzcoderCrudViewCommand'
        );
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

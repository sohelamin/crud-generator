<?php
abstract class TestCase extends Orchestra\Testbench\TestCase
{
    protected $consoleOutput;

    protected function getPackageProviders($app)
    {
        return [\Appzcoder\CrudGenerator\CrudGeneratorServiceProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('view.paths', [__DIR__ . '/temp/views']);

        $app['config']->set('crudgenerator', [
            'custom_template' => false,
            'path' => base_path('resources/crud-generator/'),
            'view_columns_number' => 3,
            'dynamic_view_template' => [
                'index' => ['formHeadingHtml', 'formBodyHtml', 'crudName', 'crudNameCap', 'modelName', 'viewName', 'routeGroup', 'primaryKey'],
                'form' => ['formFieldsHtml'],
                'create' => ['crudName', 'crudNameCap', 'modelName', 'modelNameCap', 'viewName', 'routeGroup', 'viewTemplateDir'],
                'edit' => ['crudName', 'crudNameSingular', 'crudNameCap', 'modelNameCap', 'modelName', 'viewName', 'routeGroup', 'primaryKey', 'viewTemplateDir'],
                'show' => ['formHeadingHtml', 'formBodyHtml', 'formBodyHtmlForShowView', 'crudName', 'crudNameSingular', 'crudNameCap', 'modelName', 'viewName', 'routeGroup', 'primaryKey'],
            ]
        ]);
    }

    public function setUp()
    {
        parent::setUp();

        exec('rm -rf ' . __DIR__ . '/temp/*');
        exec('rm -rf ' . app_path() . '/*');
        exec('rm -rf ' . database_path('migrations') . '/*');
    }

    public function tearDown()
    {
        parent::tearDown();

        $this->consoleOutput = '';
    }

    public function resolveApplicationConsoleKernel($app)
    {
        $app->singleton('artisan', function ($app) {
            return new \Illuminate\Console\Application($app, $app['events'], $app->version());
        });

        $app->singleton('Illuminate\Contracts\Console\Kernel', Kernel::class);
    }

    public function consoleOutput()
    {
        return $this->consoleOutput ?: $this->consoleOutput = $this->app[Kernel::class]->output();
    }
}

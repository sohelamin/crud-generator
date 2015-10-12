<?php

namespace Appzcoder\CrudGenerator\Commands;

use File;
use Illuminate\Console\Command;

class CrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:generate
                            {name : The name of the Crud.}
                            {--fields= : Fields name for the form & model.}
                            {--route=yes : Include Crud route to routes.php? yes|no.}
                            {--pk=id : The name of the primary key.}
                            {--view-path= : The name of the view path.}
                            {--url-prefix= : The url prefix.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Crud including controller, model, views & migrations.';

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
        $name = ucwords(strtolower($this->argument('name')));

        if ($this->option('fields')) {
            $fields = $this->option('fields');
            $primaryKey = $this->option('pk');
            $viewPath = $this->option('view-path');
            $urlPrefix = $this->option('url-prefix') ? strtolower($this->option('url-prefix')) . '/' : '';

            $fillableArray = explode(',', $fields);
            foreach ($fillableArray as $value) {
                $data[] = preg_replace("/(.*?):(.*)/", "$1", trim($value));
            }

            $commaSeparetedString = implode("', '", $data);
            $fillable = "['" . $commaSeparetedString . "']";

            $this->call('crud:controller', ['name' => $name . 'Controller', '--crud-name' => $name, '--view-path' => $viewPath, '--url-prefix' => $urlPrefix]);
            $this->call('crud:model', ['name' => str_plural($name), '--fillable' => $fillable]);
            $this->call('crud:migration', ['name' => str_plural(strtolower($name)), '--schema' => $fields, '--pk' => $primaryKey]);
            $this->call('crud:view', ['name' => $name, '--fields' => $fields, '--path' => $viewPath, '--url-prefix' => $urlPrefix]);
        } else {
            $this->call('make:controller', ['name' => $name . 'Controller']);
            $this->call('make:model', ['name' => $name]);
        }

        // Updating the Http/routes.php file
        $routeFile = app_path('Http/routes.php');
        if (file_exists($routeFile) && (strtolower($this->option('route')) === 'yes')) {
            $isAdded = File::append($routeFile, "\nRoute::resource('" . $urlPrefix . strtolower($name) . "', '" . $name . "Controller');");
            if ($isAdded) {
                $this->info('Crud/Resource route added to ' . $routeFile);
            } else {
                $this->info('Unable to add the route to ' . $routeFile);
            }
        }
    }
}

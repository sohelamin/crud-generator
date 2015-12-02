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
                            {--namespace= : Namespace of the controller.}';

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
        $name = $this->argument('name');

        $controllerNamespace = ($this->option('namespace')) ? $this->option('namespace') . '\\' : '';

        if ($this->option('fields')) {
            $fields = $this->option('fields');
            $primaryKey = $this->option('pk');
            $viewPath = $this->option('view-path');

            $fieldsArray = explode(',', $fields);
            $requiredFields = '';
            $requiredFieldsStr = '';

            foreach ($fieldsArray as $item) {
                $fillableArray[] = preg_replace("/(.*?):(.*)/", "$1", trim($item));

                $itemArray = explode(':', $item);
                $currentField = trim($itemArray[0]);
                $requiredFieldsStr .= (isset($itemArray[2])
                    && (trim($itemArray[2]) == 'req'
                        || trim($itemArray[2]) == 'required'))
                ? "'$currentField' => 'required', " : '';
            }

            $commaSeparetedString = implode("', '", $fillableArray);
            $fillable = "['" . $commaSeparetedString . "']";

            $requiredFields = ($requiredFieldsStr != '') ? "[" . $requiredFieldsStr . "]" : '';

            $this->call('crud:controller', ['name' => $controllerNamespace . $name . 'Controller', '--crud-name' => $name, '--view-path' => $viewPath, '--required-fields' => $requiredFields]);
            $this->call('crud:model', ['name' => $name, '--fillable' => $fillable, '--table' => str_plural(strtolower($name))]);
            $this->call('crud:migration', ['name' => str_plural(strtolower($name)), '--schema' => $fields, '--pk' => $primaryKey]);
            $this->call('crud:view', ['name' => $name, '--fields' => $fields, '--view-path' => $viewPath]);
        } else {
            $this->call('make:controller', ['name' => $controllerNamespace . $name . 'Controller']);
            $this->call('make:model', ['name' => $name]);
        }

        // Updating the Http/routes.php file
        $routeFile = app_path('Http/routes.php');
        if (file_exists($routeFile) && (strtolower($this->option('route')) === 'yes')) {
            $controller = ($controllerNamespace != '') ? $controllerNamespace . '\\' . $name . 'Controller' : $name . 'Controller';

            $isAdded = File::append($routeFile, "\nRoute::resource('" . strtolower($name) . "', '" . $controller . "');");
            if ($isAdded) {
                $this->info('Crud/Resource route added to ' . $routeFile);
            } else {
                $this->info('Unable to add the route to ' . $routeFile);
            }
        }
    }

}

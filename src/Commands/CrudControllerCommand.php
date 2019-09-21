<?php

namespace Appzcoder\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Support\Str;

class CrudControllerCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:controller
                            {name : The name of the controler.}
                            {--crud-name= : The name of the Crud.}
                            {--model-name= : The name of the Model.}
                            {--model-namespace= : The namespace of the Model.}
                            {--controller-namespace= : Namespace of the controller.}
                            {--view-path= : The name of the view path.}
                            {--fields= : Field names for the form & migration.}
                            {--validations= : Validation rules for the fields.}
                            {--route-group= : Prefix of the route group.}
                            {--pagination=25 : The amount of models per page for index pages.}
                            {--force : Overwrite already existing controller.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new resource controller.';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Controller';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return config('crudgenerator.custom_template')
        ? config('crudgenerator.path') . '/controller.stub'
        : __DIR__ . '/../stubs/controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string $rootNamespace
     *
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\\' . ($this->option('controller-namespace') ? $this->option('controller-namespace') : 'Http\Controllers');
    }

    /**
     * Determine if the class already exists.
     *
     * @param  string  $rawName
     * @return bool
     */
    protected function alreadyExists($rawName)
    {
        if ($this->option('force')) {
            return false;
        }
        return parent::alreadyExists($rawName);
    }

    /**
     * Build the model class with the given name.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $viewPath = $this->option('view-path') ? $this->option('view-path') . '.' : '';
        $crudName = strtolower($this->option('crud-name'));
        $crudNameSingular = Str::singular($crudName);
        $modelName = $this->option('model-name');
        $modelNamespace = $this->option('model-namespace');
        $routeGroup = ($this->option('route-group')) ? $this->option('route-group') . '/' : '';
        $routePrefix = ($this->option('route-group')) ? $this->option('route-group') : '';
        $routePrefixCap = ucfirst($routePrefix);
        $perPage = intval($this->option('pagination'));
        $viewName = Str::snake($this->option('crud-name'), '-');
        $fields = $this->option('fields');
        $validations = rtrim($this->option('validations'), ';');

        $validationRules = '';
        if (trim($validations) != '') {
            $validationRules = "\$this->validate(\$request, [";

            $rules = explode(';', $validations);
            foreach ($rules as $v) {
                if (trim($v) == '') {
                    continue;
                }

                // extract field name and args
                $parts = explode('#', $v);
                $fieldName = trim($parts[0]);
                $rules = trim($parts[1]);
                $validationRules .= "\n\t\t\t'$fieldName' => '$rules',";
            }

            $validationRules = substr($validationRules, 0, -1); // lose the last comma
            $validationRules .= "\n\t\t]);";
        }

        if (\App::VERSION() < '5.3') {
            $snippet = <<<EOD
        if (\$request->hasFile('{{fieldName}}')) {
            \$file = \$request->file('{{fieldName}}');
            \$fileName = str_random(40) . '.' . \$file->getClientOriginalExtension();
            \$destinationPath = storage_path('/app/public/uploads');
            \$file->move(\$destinationPath, \$fileName);
            \$requestData['{{fieldName}}'] = 'uploads/' . \$fileName;
        }
EOD;
        } else {
            $snippet = <<<EOD
        if (\$request->hasFile('{{fieldName}}')) {
            \$requestData['{{fieldName}}'] = \$request->file('{{fieldName}}')
                ->store('uploads', 'public');
        }
EOD;
        }


        $fieldsArray = explode(';', $fields);
        $fileSnippet = '';
        $whereSnippet = '';

        if ($fields) {
            $x = 0;
            foreach ($fieldsArray as $index => $item) {
                $itemArray = explode('#', $item);

                if (trim($itemArray[1]) == 'file') {
                    $fileSnippet .= str_replace('{{fieldName}}', trim($itemArray[0]), $snippet) . "\n";
                }

                $fieldName = trim($itemArray[0]);

                $whereSnippet .= ($index == 0) ? "where('$fieldName', 'LIKE', \"%\$keyword%\")" . "\n                " : "->orWhere('$fieldName', 'LIKE', \"%\$keyword%\")" . "\n                ";
            }

            $whereSnippet .= "->";
        }

        return $this->replaceNamespace($stub, $name)
            ->replaceViewPath($stub, $viewPath)
            ->replaceViewName($stub, $viewName)
            ->replaceCrudName($stub, $crudName)
            ->replaceCrudNameSingular($stub, $crudNameSingular)
            ->replaceModelName($stub, $modelName)
            ->replaceModelNamespace($stub, $modelNamespace)
            ->replaceModelNamespaceSegments($stub, $modelNamespace)
            ->replaceRouteGroup($stub, $routeGroup)
            ->replaceRoutePrefix($stub, $routePrefix)
            ->replaceRoutePrefixCap($stub, $routePrefixCap)
            ->replaceValidationRules($stub, $validationRules)
            ->replacePaginationNumber($stub, $perPage)
            ->replaceFileSnippet($stub, $fileSnippet)
            ->replaceWhereSnippet($stub, $whereSnippet)
            ->replaceClass($stub, $name);
    }

    /**
     * Replace the viewName fo the given stub.
     *
     * @param string $stub
     * @param string $viewName
     *
     * @return $this
     */
    protected function replaceViewName(&$stub, $viewName)
    {
        $stub = str_replace('{{viewName}}', $viewName, $stub);

        return $this;
    }

    /**
     * Replace the viewPath for the given stub.
     *
     * @param  string  $stub
     * @param  string  $viewPath
     *
     * @return $this
     */
    protected function replaceViewPath(&$stub, $viewPath)
    {
        $stub = str_replace('{{viewPath}}', $viewPath, $stub);

        return $this;
    }

    /**
     * Replace the crudName for the given stub.
     *
     * @param  string  $stub
     * @param  string  $crudName
     *
     * @return $this
     */
    protected function replaceCrudName(&$stub, $crudName)
    {
        $stub = str_replace('{{crudName}}', $crudName, $stub);

        return $this;
    }

    /**
     * Replace the crudNameSingular for the given stub.
     *
     * @param  string  $stub
     * @param  string  $crudNameSingular
     *
     * @return $this
     */
    protected function replaceCrudNameSingular(&$stub, $crudNameSingular)
    {
        $stub = str_replace('{{crudNameSingular}}', $crudNameSingular, $stub);

        return $this;
    }

    /**
     * Replace the modelName for the given stub.
     *
     * @param  string  $stub
     * @param  string  $modelName
     *
     * @return $this
     */
    protected function replaceModelName(&$stub, $modelName)
    {
        $stub = str_replace('{{modelName}}', $modelName, $stub);

        return $this;
    }

    /**
     * Replace the modelNamespace for the given stub.
     *
     * @param  string  $stub
     * @param  string  $modelNamespace
     *
     * @return $this
     */
    protected function replaceModelNamespace(&$stub, $modelNamespace)
    {
        $stub = str_replace('{{modelNamespace}}', $modelNamespace, $stub);

        return $this;
    }

    /**
     * Replace the modelNamespace segments for the given stub
     *
     * @param $stub
     * @param $modelNamespace
     *
     * @return $this
     */
    protected function replaceModelNamespaceSegments(&$stub, $modelNamespace)
    {
        $modelSegments = explode('\\', $modelNamespace);
        foreach ($modelSegments as $key => $segment) {
            $stub = str_replace('{{modelNamespace[' . $key . ']}}', $segment, $stub);
        }

        $stub = preg_replace('{{modelNamespace\[\d*\]}}', '', $stub);

        return $this;
    }

    /**
     * Replace the routePrefix for the given stub.
     *
     * @param  string  $stub
     * @param  string  $routePrefix
     *
     * @return $this
     */
    protected function replaceRoutePrefix(&$stub, $routePrefix)
    {
        $stub = str_replace('{{routePrefix}}', $routePrefix, $stub);

        return $this;
    }

    /**
     * Replace the routePrefixCap for the given stub.
     *
     * @param  string  $stub
     * @param  string  $routePrefixCap
     *
     * @return $this
     */
    protected function replaceRoutePrefixCap(&$stub, $routePrefixCap)
    {
        $stub = str_replace('{{routePrefixCap}}', $routePrefixCap, $stub);

        return $this;
    }

    /**
     * Replace the routeGroup for the given stub.
     *
     * @param  string  $stub
     * @param  string  $routeGroup
     *
     * @return $this
     */
    protected function replaceRouteGroup(&$stub, $routeGroup)
    {
        $stub = str_replace('{{routeGroup}}', $routeGroup, $stub);

        return $this;
    }

    /**
     * Replace the validationRules for the given stub.
     *
     * @param  string  $stub
     * @param  string  $validationRules
     *
     * @return $this
     */
    protected function replaceValidationRules(&$stub, $validationRules)
    {
        $stub = str_replace('{{validationRules}}', $validationRules, $stub);

        return $this;
    }

    /**
     * Replace the pagination placeholder for the given stub
     *
     * @param $stub
     * @param $perPage
     *
     * @return $this
     */
    protected function replacePaginationNumber(&$stub, $perPage)
    {
        $stub = str_replace('{{pagination}}', $perPage, $stub);

        return $this;
    }

    /**
     * Replace the file snippet for the given stub
     *
     * @param $stub
     * @param $fileSnippet
     *
     * @return $this
     */
    protected function replaceFileSnippet(&$stub, $fileSnippet)
    {
        $stub = str_replace('{{fileSnippet}}', $fileSnippet, $stub);

        return $this;
    }

    /**
     * Replace the where snippet for the given stub
     *
     * @param $stub
     * @param $whereSnippet
     *
     * @return $this
     */
    protected function replaceWhereSnippet(&$stub, $whereSnippet)
    {
        $stub = str_replace('{{whereSnippet}}', $whereSnippet, $stub);

        return $this;
    }
}

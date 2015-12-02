<?php

namespace Appzcoder\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

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
                            {--view-path= : The name of the view path.}
                            {--required-fields=null : Required fields for validations.}';

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
        return __DIR__ . '/../stubs/controller.stub';
    }

    /**
     * Get the default namespace for the class.
     *
     * @param  string  $rootNamespace
     * @return string
     */
    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\Http\Controllers';
    }

    /**
     * Build the model class with the given name.
     *
     * @param  string  $name
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $viewPath = $this->option('view-path') ? strtolower($this->option('view-path')) . '.' : '';
        $crudName = strtolower($this->option('crud-name'));
        $crudNameCap = $this->option('crud-name');
        $crudNamePlural = str_plural($crudName);
        $crudNamePluralCap = str_plural($crudNameCap);
        $crudNameSingular = str_singular($crudName);

        $validationRules = '';
        if ($this->option('required-fields') != '') {
            $validationRules = "\$this->validate(\$request, " . $this->option('required-fields') . ");\n";
        }

        return $this->replaceNamespace($stub, $name)
            ->replaceViewPath($stub, $viewPath)
            ->replaceCrudName($stub, $crudName)
            ->replaceCrudNameCap($stub, $crudNameCap)
            ->replaceCrudNamePlural($stub, $crudNamePlural)
            ->replaceCrudNamePluralCap($stub, $crudNamePluralCap)
            ->replaceCrudNameSingular($stub, $crudNameSingular)
            ->replaceValidationRules($stub, $validationRules)
            ->replaceClass($stub, $name);
    }

    /**
     * Replace the viewPath for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceViewPath(&$stub, $viewPath)
    {
        $stub = str_replace(
            '{{viewPath}}', $viewPath, $stub
        );

        return $this;
    }

    /**
     * Replace the crudName for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceCrudName(&$stub, $crudName)
    {
        $stub = str_replace(
            '{{crudName}}', $crudName, $stub
        );

        return $this;
    }

    /**
     * Replace the crudNameCap for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceCrudNameCap(&$stub, $crudNameCap)
    {
        $stub = str_replace(
            '{{crudNameCap}}', $crudNameCap, $stub
        );

        return $this;
    }

    /**
     * Replace the crudNamePlural for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceCrudNamePlural(&$stub, $crudNamePlural)
    {
        $stub = str_replace(
            '{{crudNamePlural}}', $crudNamePlural, $stub
        );

        return $this;
    }

    /**
     * Replace the crudNamePluralCap for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceCrudNamePluralCap(&$stub, $crudNamePluralCap)
    {
        $stub = str_replace(
            '{{crudNamePluralCap}}', $crudNamePluralCap, $stub
        );

        return $this;
    }

    /**
     * Replace the crudNameSingular for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceCrudNameSingular(&$stub, $crudNameSingular)
    {
        $stub = str_replace(
            '{{crudNameSingular}}', $crudNameSingular, $stub
        );

        return $this;
    }

    /**
     * Replace the validationRules for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceValidationRules(&$stub, $validationRules)
    {
        $stub = str_replace(
            '{{validationRules}}', $validationRules, $stub
        );

        return $this;
    }

}

<?php

namespace Appzcoder\CrudGenerator\Commands;

use File;
use Illuminate\Console\Command;

class CrudViewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:view
                            {name : The name of the Crud.}
                            {--fields= : The fields name for the form.}
                            {--view-path= : The name of the view path.}
                            {--route-group= : Prefix of the route group.}
                            {--pk=id : The name of the primary key.}
                            {--localize=yes : Localize the view? yes|no.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create views for the Crud.';

    /**
     * View Directory Path.
     *
     * @var string
     */
    protected $viewDirectoryPath;

    /**
     *  Form field types collection.
     *
     * @var array
     */
    protected $typeLookup = [
        'string' => 'text',
        'char' => 'text',
        'varchar' => 'text',
        'text' => 'textarea',
        'mediumtext' => 'textarea',
        'longtext' => 'textarea',
        'json' => 'textarea',
        'jsonb' => 'textarea',
        'binary' => 'textarea',
        'password' => 'password',
        'email' => 'email',
        'number' => 'number',
        'integer' => 'number',
        'bigint' => 'number',
        'mediumint' => 'number',
        'tinyint' => 'number',
        'smallint' => 'number',
        'decimal' => 'number',
        'double' => 'number',
        'float' => 'number',
        'date' => 'date',
        'datetime' => 'datetime-local',
        'timestamp' => 'datetime-local',
        'time' => 'time',
        'boolean' => 'radio',
    ];

    /**
     * Form's fields.
     *
     * @var array
     */
    protected $formFields = [];

    /**
     * Html of Form's fields.
     *
     * @var string
     */
    protected $formFieldsHtml = '';

    /**
     * Number of columns to show from the table. Others are hidden.
     *
     * @var integer
     */
    protected $defaultColumsToShow = 3;

    /**
     * Name of the Crud.
     *
     * @var string
     */
    protected $crudName = '';

    /**
     * Crud Name in capital form.
     *
     * @var string
     */
    protected $crudNameCap = '';

    /**
     * Crud Name in singular form.
     *
     * @var string
     */
    protected $crudNameSingular = '';

    /**
     * Primary key of the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Name of the Model.
     *
     * @var string
     */
    protected $modelName = '';

    /**
     * Name or prefix of the Route Group.
     *
     * @var string
     */
    protected $routeGroup = '';

    /**
     * Html of the form heading.
     *
     * @var string
     */
    protected $formHeadingHtml = '';

    /**
     * Html of the form body.
     *
     * @var string
     */
    protected $formBodyHtml = '';

    /**
     * Html of view to show.
     *
     * @var string
     */
    protected $formBodyHtmlForShowView = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->viewDirectoryPath = config('crudgenerator.custom_template')
        ? config('crudgenerator.path')
        : __DIR__ . '/../stubs/';

        if (config('crudgenerator.view_columns_number')) {
            $this->defaultColumsToShow = config('crudgenerator.view_columns_number');
        }
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->crudName = $this->argument('name');
        $this->crudNameCap = ucwords($this->crudName);
        $this->crudNameSingular = str_singular($this->crudName);
        $this->modelName = ucwords($this->crudNameSingular);
        $this->primaryKey = $this->option('pk');
        $this->routeGroup = ($this->option('route-group')) ? $this->option('route-group') . '/' : $this->option('route-group');

        $viewDirectory = config('view.paths')[0] . '/';
        if ($this->option('view-path')) {
            $userPath = $this->option('view-path');
            $path = $viewDirectory . $userPath . '/' . $this->crudName . '/';
        } else {
            $path = $viewDirectory . $this->crudName . '/';
        }

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $fields = $this->option('fields');
        $fieldsArray = explode(',', $fields);

        $this->formFields = array();

        if ($fields) {
            $x = 0;
            foreach ($fieldsArray as $item) {
                $itemArray = explode(':', $item);
                $this->formFields[$x]['name'] = trim($itemArray[0]);
                $this->formFields[$x]['type'] = trim($itemArray[1]);
                $this->formFields[$x]['required'] = (isset($itemArray[2]) && (trim($itemArray[2]) == 'req' || trim($itemArray[2]) == 'required')) ? true : false;

                $x++;
            }
        }

        foreach ($this->formFields as $item) {
            $this->formFieldsHtml .= $this->createField($item);
        }

        $i = 0;
        foreach ($this->formFields as $key => $value) {
            if ($i == $this->defaultColumsToShow) {
                break;
            }

            $field = $value['name'];
            $label = ucwords(str_replace('_', ' ', $field));
            if($this->option('localize') == 'yes') {
                $label = 'trans(\'' . $this->crudName . '.' . $field . '\')';
            }
            $this->formHeadingHtml .= '<th>{{ ' . $label . ' }}</th>';

            if ($i == 0) {
                $this->formBodyHtml .= '<td><a href="{{ url(\'%%routeGroup%%%%crudName%%\', $item->id) }}">{{ $item->' . $field . ' }}</a></td>';
            } else {
                $this->formBodyHtml .= '<td>{{ $item->' . $field . ' }}</td>';
            }
            $this->formBodyHtmlForShowView .= '<td> {{ $%%crudNameSingular%%->' . $field . ' }} </td>';

            $i++;
        }

        // For index.blade.php file
        $indexFile = $this->viewDirectoryPath . 'index.blade.stub';
        $newIndexFile = $path . 'index.blade.php';
        if (!File::copy($indexFile, $newIndexFile)) {
            echo "failed to copy $indexFile...\n";
        } else {
            $this->templateIndexVars($newIndexFile);
        }

        // For create.blade.php file
        $createFile = $this->viewDirectoryPath . 'create.blade.stub';
        $newCreateFile = $path . 'create.blade.php';
        if (!File::copy($createFile, $newCreateFile)) {
            echo "failed to copy $createFile...\n";
        } else {
            $this->templateCreateVars($newCreateFile);
        }

        // For edit.blade.php file
        $editFile = $this->viewDirectoryPath . 'edit.blade.stub';
        $newEditFile = $path . 'edit.blade.php';
        if (!File::copy($editFile, $newEditFile)) {
            echo "failed to copy $editFile...\n";
        } else {
            $this->templateEditVars($newEditFile);
        }

        // For show.blade.php file
        $showFile = $this->viewDirectoryPath . 'show.blade.stub';
        $newShowFile = $path . 'show.blade.php';
        if (!File::copy($showFile, $newShowFile)) {
            echo "failed to copy $showFile...\n";
        } else {
            $this->templateShowVars($newShowFile);
        }

        $this->info('View created successfully.');
    }

    /**
     * Update values between %% with real values in index view.
     *
     * @param  string $newIndexFile
     *
     * @return void
     */
    public function templateIndexVars($newIndexFile)
    {
        File::put($newIndexFile, str_replace('%%formHeadingHtml%%', $this->formHeadingHtml, File::get($newIndexFile)));
        File::put($newIndexFile, str_replace('%%formBodyHtml%%', $this->formBodyHtml, File::get($newIndexFile)));
        File::put($newIndexFile, str_replace('%%crudName%%', $this->crudName, File::get($newIndexFile)));
        File::put($newIndexFile, str_replace('%%crudNameCap%%', $this->crudNameCap, File::get($newIndexFile)));
        File::put($newIndexFile, str_replace('%%modelName%%', $this->modelName, File::get($newIndexFile)));
        File::put($newIndexFile, str_replace('%%routeGroup%%', $this->routeGroup, File::get($newIndexFile)));
        File::put($newIndexFile, str_replace('%%primaryKey%%', $this->primaryKey, File::get($newIndexFile)));
    }

    /**
     * Update values between %% with real values in create view.
     *
     * @param  string $newCreateFile
     *
     * @return void
     */
    public function templateCreateVars($newCreateFile)
    {
        File::put($newCreateFile, str_replace('%%crudName%%', $this->crudName, File::get($newCreateFile)));
        File::put($newCreateFile, str_replace('%%crudNameCap%%', $this->crudNameCap, File::get($newCreateFile)));
        File::put($newCreateFile, str_replace('%%modelName%%', $this->modelName, File::get($newCreateFile)));
        File::put($newCreateFile, str_replace('%%routeGroup%%', $this->routeGroup, File::get($newCreateFile)));
        File::put($newCreateFile, str_replace('%%formFieldsHtml%%', $this->formFieldsHtml, File::get($newCreateFile)));

    }

    /**
     * Update values between %% with real values in edit view.
     *
     * @param  string $newEditFile
     *
     * @return void
     */
    public function templateEditVars($newEditFile)
    {
        File::put($newEditFile, str_replace('%%crudName%%', $this->crudName, File::get($newEditFile)));
        File::put($newEditFile, str_replace('%%crudNameSingular%%', $this->crudNameSingular, File::get($newEditFile)));
        File::put($newEditFile, str_replace('%%crudNameCap%%', $this->crudNameCap, File::get($newEditFile)));
        File::put($newEditFile, str_replace('%%modelName%%', $this->modelName, File::get($newEditFile)));
        File::put($newEditFile, str_replace('%%routeGroup%%', $this->routeGroup, File::get($newEditFile)));
        File::put($newEditFile, str_replace('%%formFieldsHtml%%', $this->formFieldsHtml, File::get($newEditFile)));
        File::put($newEditFile, str_replace('%%primaryKey%%', $this->primaryKey, File::get($newEditFile)));
    }

    /**
     * Update values between %% with real values in show view.
     *
     * @param  string $newShowFile
     *
     * @return void
     */
    public function templateShowVars($newShowFile)
    {
        File::put($newShowFile, str_replace('%%formHeadingHtml%%', $this->formHeadingHtml, File::get($newShowFile)));
        File::put($newShowFile, str_replace('%%formBodyHtml%%', $this->formBodyHtmlForShowView, File::get($newShowFile)));
        File::put($newShowFile, str_replace('%%crudNameSingular%%', $this->crudNameSingular, File::get($newShowFile)));
        File::put($newShowFile, str_replace('%%crudNameCap%%', $this->crudNameCap, File::get($newShowFile)));
        File::put($newShowFile, str_replace('%%modelName%%', $this->modelName, File::get($newShowFile)));
        File::put($newShowFile, str_replace('%%primaryKey%%', $this->primaryKey, File::get($newShowFile)));
    }

    /**
     * Form field wrapper.
     *
     * @param  string $item
     * @param  string $field
     *
     * @return void
     */
    protected function wrapField($item, $field)
    {
        $formGroup =
            <<<EOD
            <div class="form-group {{ \$errors->has('%1\$s') ? 'has-error' : ''}}">
                {!! Form::label('%1\$s', %2\$s, ['class' => 'col-sm-3 control-label']) !!}
                <div class="col-sm-6">
                    %3\$s
                    {!! \$errors->first('%1\$s', '<p class="help-block">:message</p>') !!}
                </div>
            </div>\n
EOD;
        $labelText = "'".ucwords(strtolower(str_replace('_', ' ', $item['name'])))."'";

        if($this->option('localize') == 'yes') {
            $labelText = 'trans(\'' . $this->crudName . '.' . $item['name'] . '\')';
        }

        return sprintf($formGroup, $item['name'], $labelText, $field);
    }

    /**
     * Form field generator.
     *
     * @param  string $item
     *
     * @return string
     */
    protected function createField($item)
    {
        switch ($this->typeLookup[$item['type']]) {
            case 'password':
                return $this->createPasswordField($item);
                break;
            case 'datetime-local':
            case 'time':
                return $this->createInputField($item);
                break;
            case 'radio':
                return $this->createRadioField($item);
                break;
            default: // text
                return $this->createFormField($item);
        }
    }

    /**
     * Create a specific field using the form helper.
     *
     * @param  string $item
     *
     * @return string
     */
    protected function createFormField($item)
    {
        $required = ($item['required'] === true) ? ", 'required' => 'required'" : "";

        return $this->wrapField(
            $item,
            "{!! Form::" . $this->typeLookup[$item['type']] . "('" . $item['name'] . "', null, ['class' => 'form-control'$required]) !!}"
        );
    }

    /**
     * Create a password field using the form helper.
     *
     * @param  string $item
     *
     * @return string
     */
    protected function createPasswordField($item)
    {
        $required = ($item['required'] === true) ? ", 'required' => 'required'" : "";

        return $this->wrapField(
            $item,
            "{!! Form::password('" . $item['name'] . "', ['class' => 'form-control'$required]) !!}"
        );
    }

    /**
     * Create a generic input field using the form helper.
     *
     * @param  string $item
     *
     * @return string
     */
    protected function createInputField($item)
    {
        $required = ($item['required'] === true) ? ", 'required' => 'required'" : "";

        return $this->wrapField(
            $item,
            "{!! Form::input('" . $this->typeLookup[$item['type']] . "', '" . $item['name'] . "', null, ['class' => 'form-control'$required]) !!}"
        );
    }

    /**
     * Create a yes/no radio button group using the form helper.
     *
     * @param  string $item
     *
     * @return string
     */
    protected function createRadioField($item)
    {
        $field =
            <<<EOD
            <div class="checkbox">
                <label>{!! Form::radio('%1\$s', '1') !!} Yes</label>
            </div>
            <div class="checkbox">
                <label>{!! Form::radio('%1\$s', '0', true) !!} No</label>
            </div>
EOD;

        return $this->wrapField($item, sprintf($field, $item['name']));
    }
}

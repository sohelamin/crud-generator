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
                            {--view-path= : The name of the view path.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create views for the Crud.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $crudName = strtolower($this->argument('name'));
        $crudNameCap = ucwords($crudName);
        $crudNameSingular = str_singular($crudName);
        $crudNameSingularCap = ucwords($crudNameSingular);
        $crudNamePlural = str_plural($crudName);
        $crudNamePluralCap = ucwords($crudNamePlural);

        $viewDirectory = base_path('resources/views/');
        if ($this->option('view-path')) {
            $userPath = $this->option('view-path');
            $path = $viewDirectory . $userPath . '/' . $crudName . '/';
        } else {
            $path = $viewDirectory . $crudName . '/';
        }

        if (!File::isDirectory($path)) {
            File::makeDirectory($path, 0755, true);
        }

        $fields = $this->option('fields');
        $fieldsArray = explode(',', $fields);

        $formFields = array();
        $x = 0;
        foreach ($fieldsArray as $item) {
            $itemArray = explode(':', $item);
            $formFields[$x]['name'] = trim($itemArray[0]);
            $formFields[$x]['type'] = trim($itemArray[1]);
            $x++;
        }

        $formFieldsHtml = '';
        foreach ($formFields as $item) {
            $label = ucwords(strtolower(str_replace('_', ' ', $item['name'])));

            if ($item['type'] == 'string'
                || $item['type'] == 'char'
                || $item['type'] == 'varchar'
            ) {
                $formFieldsHtml .=
                "<div class=\"form-group\">
                        {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class=\"col-sm-6\">
                            {!! Form::text('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>";
            } elseif ($item['type'] == 'text'
                || $item['type'] == 'mediumtext'
                || $item['type'] == 'longtext'
                || $item['type'] == 'json'
                || $item['type'] == 'jsonb'
                || $item['type'] == 'binary'
            ) {
                $formFieldsHtml .=
                "<div class=\"form-group\">
                        {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class=\"col-sm-6\">
                            {!! Form::textarea('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>";
            } elseif ($item['type'] == 'password') {
                $formFieldsHtml .=
                "<div class=\"form-group\">
                        {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class=\"col-sm-6\">
                            {!! Form::password('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>";
            } elseif ($item['type'] == 'email') {
                $formFieldsHtml .=
                "<div class=\"form-group\">
                        {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class=\"col-sm-6\">
                            {!! Form::email('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>";
            } elseif ($item['type'] == 'number'
                || $item['type'] == 'integer'
                || $item['type'] == 'bigint'
                || $item['type'] == 'mediumint'
                || $item['type'] == 'tinyint'
                || $item['type'] == 'smallint'
                || $item['type'] == 'decimal'
                || $item['type'] == 'double'
                || $item['type'] == 'float'
            ) {
                $formFieldsHtml .=
                "<div class=\"form-group\">
                        {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class=\"col-sm-6\">
                            {!! Form::number('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>";
            } elseif ($item['type'] == 'date') {
                $formFieldsHtml .=
                "<div class=\"form-group\">
                        {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class=\"col-sm-6\">
                            {!! Form::date('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>";
            } elseif ($item['type'] == 'datetime') {
                $formFieldsHtml .=
                "<div class=\"form-group\">
                        {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class=\"col-sm-6\">
                            {!! Form::input('datetime-local', '" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>";
            } elseif ($item['type'] == 'time') {
                $formFieldsHtml .=
                "<div class=\"form-group\">
                        {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class=\"col-sm-6\">
                            {!! Form::input('time', '" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>";
            } elseif ($item['type'] == 'boolean') {
                $formFieldsHtml .=
                "<div class=\"form-group\">
                        {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class=\"col-sm-6\">
                            {!! Form::radio('" . $item['name'] . "', '1', ['class' => 'form-control']) !!}Yes
                            {!! Form::radio('" . $item['name'] . "', '0', true, ['class' => 'form-control']) !!}No
                        </div>
                    </div>";
            } else {
                $formFieldsHtml .=
                "<div class=\"form-group\">
                        {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class=\"col-sm-6\">
                            {!! Form::text('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>";
            }
        }

        // Form fields and label
        $formHeadingHtml = '';
        $formBodyHtml = '';
        $formBodyHtmlForShowView = '';

        $i = 0;
        foreach ($formFields as $key => $value) {
            if ($i == 3) {
                break;
            }

            $field = $value['name'];
            $label = ucwords(str_replace('_', ' ', $field));
            $formHeadingHtml .= '<th>' . $label . '</th>';

            if ($i == 0) {
                $formBodyHtml .= '<td><a href="{{ url(\'/%%crudName%%\', $item->id) }}">{{ $item->' . $field . ' }}</a></td>';
            } else {
                $formBodyHtml .= '<td>{{ $item->' . $field . ' }}</td>';
            }
            $formBodyHtmlForShowView .= '<td> {{ $%%crudNameSingular%%->' . $field . ' }} </td>';

            $i++;
        }

        // For index.blade.php file
        $indexFile = __DIR__ . '/../stubs/index.blade.stub';
        $newIndexFile = $path . 'index.blade.php';
        if (!File::copy($indexFile, $newIndexFile)) {
            echo "failed to copy $indexFile...\n";
        } else {
            File::put($newIndexFile, str_replace('%%formHeadingHtml%%', $formHeadingHtml, File::get($newIndexFile)));
            File::put($newIndexFile, str_replace('%%formBodyHtml%%', $formBodyHtml, File::get($newIndexFile)));
            File::put($newIndexFile, str_replace('%%crudName%%', $crudName, File::get($newIndexFile)));
            File::put($newIndexFile, str_replace('%%crudNameCap%%', $crudNameCap, File::get($newIndexFile)));
            File::put($newIndexFile, str_replace('%%crudNamePlural%%', $crudNamePlural, File::get($newIndexFile)));
            File::put($newIndexFile, str_replace('%%crudNamePluralCap%%', $crudNamePluralCap, File::get($newIndexFile)));
        }

        // For create.blade.php file
        $createFile = __DIR__ . '/../stubs/create.blade.stub';
        $newCreateFile = $path . 'create.blade.php';
        if (!File::copy($createFile, $newCreateFile)) {
            echo "failed to copy $createFile...\n";
        } else {
            File::put($newCreateFile, str_replace('%%crudName%%', $crudName, File::get($newCreateFile)));
            File::put($newCreateFile, str_replace('%%crudNameSingularCap%%', $crudNameSingularCap, File::get($newCreateFile)));
            File::put($newCreateFile, str_replace('%%formFieldsHtml%%', $formFieldsHtml, File::get($newCreateFile)));
        }

        // For edit.blade.php file
        $editFile = __DIR__ . '/../stubs/edit.blade.stub';
        $newEditFile = $path . 'edit.blade.php';
        if (!File::copy($editFile, $newEditFile)) {
            echo "failed to copy $editFile...\n";
        } else {
            File::put($newEditFile, str_replace('%%crudNameCap%%', $crudNameCap, File::get($newEditFile)));
            File::put($newEditFile, str_replace('%%crudNameSingular%%', $crudNameSingular, File::get($newEditFile)));
            File::put($newEditFile, str_replace('%%crudNameSingularCap%%', $crudNameSingularCap, File::get($newEditFile)));
            File::put($newEditFile, str_replace('%%formFieldsHtml%%', $formFieldsHtml, File::get($newEditFile)));
        }

        // For show.blade.php file
        $showFile = __DIR__ . '/../stubs/show.blade.stub';
        $newShowFile = $path . 'show.blade.php';
        if (!File::copy($showFile, $newShowFile)) {
            echo "failed to copy $showFile...\n";
        } else {
            File::put($newShowFile, str_replace('%%formHeadingHtml%%', $formHeadingHtml, File::get($newShowFile)));
            File::put($newShowFile, str_replace('%%formBodyHtml%%', $formBodyHtmlForShowView, File::get($newShowFile)));
            File::put($newShowFile, str_replace('%%crudNameSingular%%', $crudNameSingular, File::get($newShowFile)));
            File::put($newShowFile, str_replace('%%crudNameSingularCap%%', $crudNameSingularCap, File::get($newShowFile)));
        }

        // For layouts/master.blade.php file
        $layoutsDirPath = base_path('resources/views/layouts/');
        if (!File::isDirectory($layoutsDirPath)) {
            File::makeDirectory($layoutsDirPath);
        }

        $layoutsFile = __DIR__ . '/../stubs/master.blade.stub';
        $newLayoutsFile = $layoutsDirPath . 'master.blade.php';

        if (!File::exists($newLayoutsFile)) {
            if (!File::copy($layoutsFile, $newLayoutsFile)) {
                echo "failed to copy $layoutsFile...\n";
            }
        }

        $this->info('View created successfully.');
    }
}

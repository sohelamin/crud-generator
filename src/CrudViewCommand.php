<?php

namespace Appzcoder\CrudGenerator;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class CrudViewCommand extends Command
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:view';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create view files for crud operation';

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
    public function fire()
    {
        $crudName = strtolower($this->argument('name'));
        $crudNameCap = ucwords($crudName);
        $crudNameSingular = str_singular($crudName);
        $crudNameSingularCap = ucwords($crudNameSingular);
        $crudNamePlural = str_plural($crudName);
        $crudNamePluralCap = ucwords($crudNamePlural);
        $viewDirectory = base_path('resources/views/');
        $path = $viewDirectory . $crudName . '/';
        if (!is_dir($path)) {
            mkdir($path);
        }

        $fields = $this->option('fields');
        $fieldsArray = explode(',', $fields);

        $formFields = array();
        $x = 0;
        foreach ($fieldsArray as $item) {
            $array = explode(':', $item);
            $formFields[$x]['name'] = trim($array[0]);
            $formFields[$x]['type'] = trim($array[1]);
            $x++;
        }

        $formFieldsHtml = '';
        foreach ($formFields as $item) {
            $label = ucwords(strtolower(str_replace('_', ' ', $item['name'])));

            if ($item['type'] == 'string') {
                $formFieldsHtml .=
                "<div class=\"form-group\">
                        {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                        <div class=\"col-sm-6\">
                            {!! Form::text('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                        </div>
                    </div>";
            } elseif ($item['type'] == 'text') {
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
        $indexFile = __DIR__ . '/stubs/index.blade.stub';
        $newIndexFile = $path . 'index.blade.php';
        if (!copy($indexFile, $newIndexFile)) {
            echo "failed to copy $indexFile...\n";
        } else {
            file_put_contents($newIndexFile, str_replace('%%formHeadingHtml%%', $formHeadingHtml, file_get_contents($newIndexFile)));
            file_put_contents($newIndexFile, str_replace('%%formBodyHtml%%', $formBodyHtml, file_get_contents($newIndexFile)));
            file_put_contents($newIndexFile, str_replace('%%crudName%%', $crudName, file_get_contents($newIndexFile)));
            file_put_contents($newIndexFile, str_replace('%%crudNameCap%%', $crudNameCap, file_get_contents($newIndexFile)));
            file_put_contents($newIndexFile, str_replace('%%crudNamePlural%%', $crudNamePlural, file_get_contents($newIndexFile)));
            file_put_contents($newIndexFile, str_replace('%%crudNamePluralCap%%', $crudNamePluralCap, file_get_contents($newIndexFile)));
        }

        // For create.blade.php file
        $createFile = __DIR__ . '/stubs/create.blade.stub';
        $newCreateFile = $path . 'create.blade.php';
        if (!copy($createFile, $newCreateFile)) {
            echo "failed to copy $createFile...\n";
        } else {
            file_put_contents($newCreateFile, str_replace('%%crudName%%', $crudName, file_get_contents($newCreateFile)));
            file_put_contents($newCreateFile, str_replace('%%crudNameSingularCap%%', $crudNameSingularCap, file_get_contents($newCreateFile)));
            file_put_contents($newCreateFile, str_replace('%%formFieldsHtml%%', $formFieldsHtml, file_get_contents($newCreateFile)));
        }

        // For edit.blade.php file
        $editFile = __DIR__ . '/stubs/edit.blade.stub';
        $newEditFile = $path . 'edit.blade.php';
        if (!copy($editFile, $newEditFile)) {
            echo "failed to copy $editFile...\n";
        } else {
            file_put_contents($newEditFile, str_replace('%%crudNameCap%%', $crudNameCap, file_get_contents($newEditFile)));
            file_put_contents($newEditFile, str_replace('%%crudNameSingular%%', $crudNameSingular, file_get_contents($newEditFile)));
            file_put_contents($newEditFile, str_replace('%%crudNameSingularCap%%', $crudNameSingularCap, file_get_contents($newEditFile)));
            file_put_contents($newEditFile, str_replace('%%formFieldsHtml%%', $formFieldsHtml, file_get_contents($newEditFile)));
        }

        // For show.blade.php file
        $showFile = __DIR__ . '/stubs/show.blade.stub';
        $newShowFile = $path . 'show.blade.php';
        if (!copy($showFile, $newShowFile)) {
            echo "failed to copy $showFile...\n";
        } else {
            file_put_contents($newShowFile, str_replace('%%formHeadingHtml%%', $formHeadingHtml, file_get_contents($newShowFile)));
            file_put_contents($newShowFile, str_replace('%%formBodyHtml%%', $formBodyHtmlForShowView, file_get_contents($newShowFile)));
            file_put_contents($newShowFile, str_replace('%%crudNameSingular%%', $crudNameSingular, file_get_contents($newShowFile)));
            file_put_contents($newShowFile, str_replace('%%crudNameSingularCap%%', $crudNameSingularCap, file_get_contents($newShowFile)));
        }

        // For layouts/master.blade.php file
        $layoutsDirPath = base_path('resources/views/layouts/');
        if (!is_dir($layoutsDirPath)) {
            mkdir($layoutsDirPath);
        }

        $layoutsFile = __DIR__ . '/stubs/master.blade.stub';
        $newLayoutsFile = $layoutsDirPath . 'master.blade.php';

        if (!file_exists($newLayoutsFile)) {
            if (!copy($layoutsFile, $newLayoutsFile)) {
                echo "failed to copy $layoutsFile...\n";
            } else {
                file_get_contents($newLayoutsFile);
            }
        }

        $this->info('View created successfully.');

    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            ['name', InputArgument::REQUIRED, 'Name of the Crud.'],
        ];
    }

    /*
     * Get the console command options.
     *
     * @return array
     */

    protected function getOptions()
    {
        return [
            ['fields', null, InputOption::VALUE_OPTIONAL, 'The fields of the form.', null],
        ];
    }

}

<?php

namespace Appzcoder\CrudGenerator\Commands;

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
                            {--url-prefix= : The url prefix.}';

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

        if (!is_dir($path)) {
            mkdir($path, 0755, true);
        }

        $urlPrefix = $this->option('url-prefix') ? strtolower($this->option('url-prefix')) . '/' : '';

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

            switch ($item['type']) {
                case ('string'):
                    $formFieldsHtml .=
                    "<div class=\"form-group\">
                            {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class=\"col-sm-6\">
                                {!! Form::text('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>";
                break;

                case ('text'):
                    $formFieldsHtml .=
                    "<div class=\"form-group\">
                            {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class=\"col-sm-6\">
                                {!! Form::textarea('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>";
                case ('password'):
                    $formFieldsHtml .=
                    "<div class=\"form-group\">
                            {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class=\"col-sm-6\">
                                {!! Form::password('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>";
                break;

                case ('email'):
                    $formFieldsHtml .=
                    "<div class=\"form-group\">
                            {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class=\"col-sm-6\">
                                {!! Form::email('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>";
                break;

                default:
                    $formFieldsHtml .=
                    "<div class=\"form-group\">
                            {!! Form::label('" . $item['name'] . "', '" . $label . ": ', ['class' => 'col-sm-3 control-label']) !!}
                            <div class=\"col-sm-6\">
                                {!! Form::text('" . $item['name'] . "', null, ['class' => 'form-control']) !!}
                            </div>
                        </div>";
            } // switch
        } // foreach

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
                $formBodyHtml .= '<td><a href="{{ url(\'%%urlPrefix%%%%crudName%%\', $item->id) }}">{{ $item->' . $field . ' }}</a></td>';
            } else {
                $formBodyHtml .= '<td>{{ $item->' . $field . ' }}</td>';
            }
            $formBodyHtmlForShowView .= '<td> {{ $%%crudNameSingular%%->' . $field . ' }} </td>';

            $i++;
        }

        // For index.blade.php file
        $indexFile = __DIR__ . '/../stubs/index.blade.stub';
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
            file_put_contents($newIndexFile, str_replace('%%urlPrefix%%', $urlPrefix, file_get_contents($newIndexFile)));
        }

        // For create.blade.php file
        $createFile = __DIR__ . '/../stubs/create.blade.stub';
        $newCreateFile = $path . 'create.blade.php';
        if (!copy($createFile, $newCreateFile)) {
            echo "failed to copy $createFile...\n";
        } else {
            file_put_contents($newCreateFile, str_replace('%%crudName%%', $crudName, file_get_contents($newCreateFile)));
            file_put_contents($newCreateFile, str_replace('%%crudNameSingularCap%%', $crudNameSingularCap, file_get_contents($newCreateFile)));
            file_put_contents($newCreateFile, str_replace('%%formFieldsHtml%%', $formFieldsHtml, file_get_contents($newCreateFile)));
            file_put_contents($newCreateFile, str_replace('%%urlPrefix%%', $urlPrefix, file_get_contents($newCreateFile)));
        }

        // For edit.blade.php file
        $editFile = __DIR__ . '/../stubs/edit.blade.stub';
        $newEditFile = $path . 'edit.blade.php';
        if (!copy($editFile, $newEditFile)) {
            echo "failed to copy $editFile...\n";
        } else {
            file_put_contents($newEditFile, str_replace('%%crudNameCap%%', $crudNameCap, file_get_contents($newEditFile)));
            file_put_contents($newEditFile, str_replace('%%crudNameSingular%%', $crudNameSingular, file_get_contents($newEditFile)));
            file_put_contents($newEditFile, str_replace('%%crudNameSingularCap%%', $crudNameSingularCap, file_get_contents($newEditFile)));
            file_put_contents($newEditFile, str_replace('%%formFieldsHtml%%', $formFieldsHtml, file_get_contents($newEditFile)));
            file_put_contents($newEditFile, str_replace('%%urlPrefix%%', $urlPrefix, file_get_contents($newEditFile)));
        }

        // For show.blade.php file
        $showFile = __DIR__ . '/../stubs/show.blade.stub';
        $newShowFile = $path . 'show.blade.php';
        if (!copy($showFile, $newShowFile)) {
            echo "failed to copy $showFile...\n";
        } else {
            file_put_contents($newShowFile, str_replace('%%formHeadingHtml%%', $formHeadingHtml, file_get_contents($newShowFile)));
            file_put_contents($newShowFile, str_replace('%%formBodyHtml%%', $formBodyHtmlForShowView, file_get_contents($newShowFile)));
            file_put_contents($newShowFile, str_replace('%%crudNameSingular%%', $crudNameSingular, file_get_contents($newShowFile)));
            file_put_contents($newShowFile, str_replace('%%crudNameSingularCap%%', $crudNameSingularCap, file_get_contents($newShowFile)));
            file_put_contents($newShowFile, str_replace('%%urlPrefix%%', $urlPrefix, file_get_contents($newShowFile)));
        }

        // For layouts/master.blade.php file
        $layoutsDirPath = base_path('resources/views/layouts/');
        if (!is_dir($layoutsDirPath)) {
            mkdir($layoutsDirPath);
        }

        $layoutsFile = __DIR__ . '/../stubs/master.blade.stub';
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
}

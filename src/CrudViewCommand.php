<?php namespace Appzcoder\CrudGenerator;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CrudViewCommand extends Command {

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
        $crudNamePlural = str_plural($crudName);
        $viewDirectory = $this->laravel['path'].'/../resources/views/';        
        $path = $viewDirectory.$crudName.'/'; 
        if(!is_dir($path)) {
            mkdir($path);   
        } 

        $fields = $this->option('fields');
        $fieldsArray = explode(',', $fields);        

        $data = array();
        $x = 0;
        foreach ($fieldsArray as $item) {
            $array = explode(':', $item);
            $data[$x]['name'] = trim($array[0]);
            $data[$x]['type'] = trim($array[1]);
            $x++;
        }

        $formFields = '';
        foreach ($data as $item) {
            $label = ucwords(strtolower(str_replace('_', ' ', $item['name'])));

            if( $item['type']=='string' ) {
                $formFields .= 
                    "<div class=\"form-group\">
                        {!! Form::label('".$item['name']."', '".$label.": ') !!}
                        {!! Form::text('".$item['name']."', null, ['class' => 'form-control']) !!}
                    </div>";
            } elseif( $item['type']=='text' ) {
                $formFields .= 
                    "<div class=\"form-group\">
                        {!! Form::label('".$item['name']."', '".$label.": ') !!}
                        {!! Form::textarea('".$item['name']."', null, ['class' => 'form-control']) !!}
                    </div>";
            } elseif( $item['type']=='password' ) {
                $formFields .= 
                    "<div class=\"form-group\">
                        {!! Form::label('".$item['name']."', '".$label.": ') !!}
                        {!! Form::password('".$item['name']."', null, ['class' => 'form-control']) !!}
                    </div>";
            } elseif( $item['type']=='email' ) {
                $formFields .= 
                    "<div class=\"form-group\">
                        {!! Form::label('".$item['name']."', '".$label.": ') !!}
                        {!! Form::email('".$item['name']."', null, ['class' => 'form-control']) !!}
                    </div>";                             
            } else {
                $formFields .= 
                    "<div class=\"form-group\">
                        {!! Form::label('".$item['name']."', '".$label.": ') !!}
                        {!! Form::text('".$item['name']."', null, ['class' => 'form-control']) !!}
                    </div>";
            }       
        }
        //var_dump($formFields); exit;

        // For index.blade.php file
        $indexFile = __DIR__.'/stubs/index.blade.stub';
        $newIndexFile = $path.'index.blade.php';
        if (!copy($indexFile, $newIndexFile)) {
            echo "failed to copy $indexFile...\n";
        } else {
            file_put_contents($newIndexFile,str_replace('%%crudName%%',$crudName,file_get_contents($newIndexFile)));
            file_put_contents($newIndexFile,str_replace('%%crudNameCap%%',$crudNameCap,file_get_contents($newIndexFile)));
            file_put_contents($newIndexFile,str_replace('%%crudNameSingular%%',$crudNameSingular,file_get_contents($newIndexFile)));
            file_put_contents($newIndexFile,str_replace('%%crudNamePlural%%',$crudNamePlural,file_get_contents($newIndexFile)));
        }

        // For create.blade.php file
        $createFile = __DIR__.'/stubs/create.blade.stub';
        $newCreateFile = $path.'create.blade.php';
        if (!copy($createFile, $newCreateFile)) {
            echo "failed to copy $createFile...\n";
        } else {
            file_put_contents($newCreateFile,str_replace('%%crudName%%',$crudName,file_get_contents($newCreateFile)));
            file_put_contents($newCreateFile,str_replace('%%crudNameCap%%',$crudNameCap,file_get_contents($newCreateFile)));
            file_put_contents($newCreateFile,str_replace('%%crudNameSingular%%',$crudNameSingular,file_get_contents($newCreateFile)));
            file_put_contents($newCreateFile,str_replace('%%crudNamePlural%%',$crudNamePlural,file_get_contents($newCreateFile)));
            file_put_contents($newCreateFile,str_replace('%%formFields%%',$formFields,file_get_contents($newCreateFile)));
        }

        // For edit.blade.php file
        $editFile = __DIR__.'/stubs/edit.blade.stub';
        $newEditFile = $path.'edit.blade.php';
        if (!copy($editFile, $newEditFile)) {
            echo "failed to copy $editFile...\n";
        } else {
            file_put_contents($newEditFile,str_replace('%%crudName%%',$crudName,file_get_contents($newEditFile)));
            file_put_contents($newEditFile,str_replace('%%crudNameCap%%',$crudNameCap,file_get_contents($newEditFile)));
            file_put_contents($newEditFile,str_replace('%%crudNameSingular%%',$crudNameSingular,file_get_contents($newEditFile)));
            file_put_contents($newEditFile,str_replace('%%crudNamePlural%%',$crudNamePlural,file_get_contents($newEditFile)));
            file_put_contents($newEditFile,str_replace('%%formFields%%',$formFields,file_get_contents($newEditFile)));
        }        

        // For show.blade.php file
        $showFile = __DIR__.'/stubs/show.blade.stub';
        $newShowFile = $path.'show.blade.php';
        if (!copy($showFile, $newShowFile)) {
            echo "failed to copy $showFile...\n";
        } else {
            file_put_contents($newShowFile,str_replace('%%crudName%%',$crudName,file_get_contents($newShowFile)));
            file_put_contents($newShowFile,str_replace('%%crudNameCap%%',$crudNameCap,file_get_contents($newShowFile)));
            file_put_contents($newShowFile,str_replace('%%crudNameSingular%%',$crudNameSingular,file_get_contents($newShowFile)));
            file_put_contents($newShowFile,str_replace('%%crudNamePlural%%',$crudNamePlural,file_get_contents($newShowFile)));
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

<?php

namespace Appzcoder\CrudGenerator\Commands;

use File;
use Illuminate\Console\Command;

class CrudLangCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:lang
                            {name : The name of the Crud.}
                            {--fields= : The fields name for the form.}
                            {--locale=en : The locale for the file.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create lang file for the Crud.';

    /**
     * Name of the Crud.
     *
     * @var string
     */
    protected $crudName = '';

    /**
     * The locale string.
     *
     * @var string
     */
    protected $locale;

    /**
     * Form's fields.
     *
     * @var array
     */
    protected $formFields = [];

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
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->crudName = $this->argument('name');
        $this->locale = $this->option('locale');
        $path = config('view.paths')[0] . '/../lang/'.$this->locale.'/';

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

        $indexFile = $this->viewDirectoryPath . 'lang.stub';
        $newLangFile = $path . $this->crudName.'.php';
        if (!File::copy($indexFile, $newLangFile)) {
            echo "failed to copy $indexFile...\n";
        } else {
            $this->templateVars($newLangFile);
        }

        $this->info('Lang ['.$this->locale.'] created successfully.');
    }

    private function templateVars($newLangFile)
    {
        $messages = [];
        foreach($this->formFields as $field) {
            $index = $field['name'];
            $text = ucwords(strtolower(str_replace('_', ' ', $index)));
            $messages[] = "'$index' => '$text'";
        }

        File::put($newLangFile, str_replace('%%messages%%', implode(",\n",$messages), File::get($newLangFile)));
    }
}

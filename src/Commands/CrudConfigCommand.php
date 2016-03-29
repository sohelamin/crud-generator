<?php

namespace Appzcoder\CrudGenerator\Commands;

use File;
use Illuminate\Console\Command;

class CrudConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:config
                            {name : The name of the config.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new config.';

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
     * Stub file
     *
     * @var string
     */
    protected $stub = '';
    

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->crudName = strtolower( $this->argument('name') );
        $this->crudNameCap = ucwords( $this->crudName );

        $this->stub = config('crudgenerator.custom_template') ? config('crudgenerator.path') . 'config.stub' : __DIR__ . '/../stubs/config.stub';

        $newConfigFile = config_path() . '/'. $this->crudName .'.php';
        if ( ! File::copy($this->stub, $newConfigFile)) {
            $this->info("failed to copy $stub...\n");
        } else {
            File::put($newConfigFile, str_replace('%%crudNameLower%%', $this->crudName, File::get($newConfigFile)));
            File::put($newConfigFile, str_replace('%%crudNameUpper%%', $this->crudNameCap, File::get($newConfigFile)));

            $this->info('Config created successfully.');
        }
    }

}

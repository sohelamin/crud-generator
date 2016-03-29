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
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $crudName = strtolower( $this->argument('name') );
        $crudNameCap = ucwords($crudName);

        $stub = config('crudgenerator.custom_template') ? config('crudgenerator.path') . 'config.stub' : __DIR__ . '/../stubs/config.stub';

        $newConfigFile = config_path() . '/'. $crudName .'.php';
        if ( ! File::copy($stub, $newConfigFile)) {
            $this->info("failed to copy $stub...\n");
        } else {
            File::put($newConfigFile, str_replace('%%crudNameLower%%', $crudName, File::get($newConfigFile)));
            File::put($newConfigFile, str_replace('%%crudNameUpper%%', $crudNameCap, File::get($newConfigFile)));

            $this->info('Config created successfully.');
        }
    }

}

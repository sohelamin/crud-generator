<?php

namespace Appzcoder\CrudGenerator;

use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;

class CrudMigrationCommand extends GeneratorCommand
{

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'crud:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration file';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'Migration';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/stubs/migration.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace($this->laravel->getNamespace(), '', $name);
        $datePrefix = date('Y_m_d_His');
        return database_path('/migrations/') . $datePrefix . '_create_' . $name . '_table.php';
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
        $tableName = strtolower($this->getNameInput());
        $className = 'Create' . ucwords($tableName) . 'Table';

        $schema = $this->option('schema');
        $fields = explode(',', $schema);

        $data = array();
        $x = 0;
        foreach ($fields as $field) {
            $array = explode(':', $field);
            $data[$x]['name'] = trim($array[0]);
            $data[$x]['type'] = trim($array[1]);
            $x++;
        }

        $schemaFields = '';
        foreach ($data as $item) {
            if ($item['type'] == 'string') {
                $schemaFields .= "\$table->string('" . $item['name'] . "');";
            } elseif ($item['type'] == 'text') {
                $schemaFields .= "\$table->text('" . $item['name'] . "');";
            } elseif ($item['type'] == 'integer') {
                $schemaFields .= "\$table->integer('" . $item['name'] . "');";
            } elseif ($item['type'] == 'date') {
                $schemaFields .= "\$table->date('" . $item['name'] . "');";
            } else {
                $schemaFields .= "\$table->string('" . $item['name'] . "');";
            }
        }

        $schemaUp = "
            Schema::create('" . $tableName . "', function(Blueprint \$table)
            {
            \$table->increments('id');
            " . $schemaFields . "
            \$table->timestamps();
            });
            ";

        $schemaDown = "Schema::drop('" . $tableName . "');";
        return $this->replaceSchemaUp($stub, $schemaUp)->replaceSchemaDown($stub, $schemaDown)->replaceClass($stub, $className);
    }

    /**
     * Replace the schema_up for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceSchemaUp(&$stub, $schemaUp)
    {
        $stub = str_replace(
            '{{schema_up}}', $schemaUp, $stub
        );

        return $this;
    }

    /**
     * Replace the schema_down for the given stub.
     *
     * @param  string  $stub
     * @return $this
     */
    protected function replaceSchemaDown(&$stub, $schemaDown)
    {
        $stub = str_replace(
            '{{schema_down}}', $schemaDown, $stub
        );

        return $this;
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return [
            ['schema', null, InputOption::VALUE_REQUIRED, 'The schema name.', null],
        ];
    }

}

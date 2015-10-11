<?php

namespace Appzcoder\CrudGenerator\Commands;

use Illuminate\Console\GeneratorCommand;

class CrudMigrationCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crud:migration
                            {name : The name of the migration.}
                            {--schema= : The name of the schema.}
                            {--pk=id : The name of the primary key.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new migration.';

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
        return __DIR__ . '/../stubs/migration.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     * @return string
     */
    protected function getPath($name)
    {
        $name = strtolower(str_replace($this->laravel->getNamespace(), '', $name));
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

        $tableName = strtolower($this->argument('name'));
        $className = 'Create' . ucwords($tableName) . 'Table';

        $schema = $this->option('schema');
        $fields = explode(',', $schema);

        $data = array();
        $x = 0;
        foreach ($fields as $field) {
            $fieldArray = explode(':', $field);
            $data[$x]['name'] = trim($fieldArray[0]);
            $data[$x]['type'] = trim($fieldArray[1]);
            $x++;
        }

        $schemaFields = '';
        foreach ($data as $item) {
            if ($item['type'] == 'string') {
                $schemaFields .= "\$table->string('" . $item['name'] . "');\n";
            } elseif ($item['type'] == 'text') {
                $schemaFields .= "\$table->text('" . $item['name'] . "');\n";
            } elseif ($item['type'] == 'integer') {
                $schemaFields .= "\$table->integer('" . $item['name'] . "');\n";
            } elseif ($item['type'] == 'date') {
                $schemaFields .= "\$table->date('" . $item['name'] . "');\n";
            } else {
                $schemaFields .= "\$table->string('" . $item['name'] . "');\n";
            }
        }

        $primaryKey = strtolower($this->option('pk'));

        $schemaUp = "
            Schema::create('" . $tableName . "', function(Blueprint \$table) {
                \$table->increments('" . $primaryKey . "');
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
}

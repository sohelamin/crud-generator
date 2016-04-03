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
        return config('crudgenerator.custom_template')
        ? config('crudgenerator.path') . '/migration.stub'
        : __DIR__ . '/../stubs/migration.stub';
    }

    /**
     * Get the destination class path.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function getPath($name)
    {
        $name = str_replace($this->laravel->getNamespace(), '', $name);
        $datePrefix = date('Y_m_d_His');

        return database_path('/migrations/') . $datePrefix . '_create_' . $name . '_table.php';
    }

    /**
     * Add indents to multiline text
     *
     * @param string $text
     * @param integer $indent indent in tabs (PSR-2)
     * @return array
     */
    protected function indentSchemaFields($text, $indent)
    {
        $lines = explode(PHP_EOL, $text);

        // skipping first line
        $preparedLines[] = array_shift($lines);

        foreach ($lines as $line) {
            $preparedLines[] = str_pad('', $indent * 4) . $line;
        }

        return implode(PHP_EOL, $preparedLines);
    }

    /**
     * Build the model class with the given name.
     *
     * @param  string  $name
     *
     * @return string
     */
    protected function buildClass($name)
    {
        $stub = $this->files->get($this->getStub());

        $tableName = $this->argument('name');
        $className = 'Create' . ucwords($tableName) . 'Table';

        $schema = $this->option('schema');
        $fields = explode(',', $schema);

        $data = array();

        if ($schema) {
            $x = 0;
            foreach ($fields as $field) {
                $fieldArray = explode(':', $field);
                $data[$x]['name'] = trim($fieldArray[0]);
                $data[$x]['type'] = trim($fieldArray[1]);
                $x++;
            }
        }

        $schemaFields = '';
        foreach ($data as $item) {
            switch ($item['type']) {
                case 'char':
                    $schemaFields .= "\$table->char('" . $item['name'] . "');\n";
                    break;

                case 'date':
                    $schemaFields .= "\$table->date('" . $item['name'] . "');\n";
                    break;

                case 'datetime':
                    $schemaFields .= "\$table->dateTime('" . $item['name'] . "');\n";
                    break;

                case 'time':
                    $schemaFields .= "\$table->time('" . $item['name'] . "');\n";
                    break;

                case 'timestamp':
                    $schemaFields .= "\$table->timestamp('" . $item['name'] . "');\n";
                    break;

                case 'text':
                    $schemaFields .= "\$table->text('" . $item['name'] . "');\n";
                    break;

                case 'mediumtext':
                    $schemaFields .= "\$table->mediumText('" . $item['name'] . "');\n";
                    break;

                case 'longtext':
                    $schemaFields .= "\$table->longText('" . $item['name'] . "');\n";
                    break;

                case 'json':
                    $schemaFields .= "\$table->json('" . $item['name'] . "');\n";
                    break;

                case 'jsonb':
                    $schemaFields .= "\$table->jsonb('" . $item['name'] . "');\n";
                    break;

                case 'binary':
                    $schemaFields .= "\$table->binary('" . $item['name'] . "');\n";
                    break;

                case 'number':
                case 'integer':
                    $schemaFields .= "\$table->integer('" . $item['name'] . "');\n";
                    break;

                case 'bigint':
                    $schemaFields .= "\$table->bigInteger('" . $item['name'] . "');\n";
                    break;

                case 'mediumint':
                    $schemaFields .= "\$table->mediumInteger('" . $item['name'] . "');\n";
                    break;

                case 'tinyint':
                    $schemaFields .= "\$table->tinyInteger('" . $item['name'] . "');\n";
                    break;

                case 'smallint':
                    $schemaFields .= "\$table->smallInteger('" . $item['name'] . "');\n";
                    break;

                case 'boolean':
                    $schemaFields .= "\$table->boolean('" . $item['name'] . "');\n";
                    break;

                case 'decimal':
                    $schemaFields .= "\$table->decimal('" . $item['name'] . "');\n";
                    break;

                case 'double':
                    $schemaFields .= "\$table->double('" . $item['name'] . "');\n";
                    break;

                case 'float':
                    $schemaFields .= "\$table->float('" . $item['name'] . "');\n";
                    break;

                default:
                    $schemaFields .= "\$table->string('" . $item['name'] . "');\n";
                    break;
            }
        }

        $primaryKey = $this->option('pk');

        $schemaUp = "
            Schema::create('" . $tableName . "', function(Blueprint \$table) {
                \$table->increments('" . $primaryKey . "');
                " . $this->indentSchemaFields($schemaFields, 4) . "
                \$table->timestamps();
            });
            ";

        $schemaDown = "Schema::drop('" . $tableName . "');";

        return $this->replaceSchemaUp($stub, $schemaUp)
            ->replaceSchemaDown($stub, $schemaDown)
            ->replaceClass($stub, $className);
    }

    /**
     * Replace the schema_up for the given stub.
     *
     * @param  string  $stub
     * @param  string  $schemaUp
     *
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
     * @param  string  $schemaDown
     *
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

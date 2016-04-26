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
        $className = 'Create' . str_replace(' ', '', ucwords(str_replace('_', ' ', $tableName))) . 'Table';

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

        $tabIndent = '    ';

        $schemaFields = '';
        foreach ($data as $item) {
            switch ($item['type']) {
                case 'char':
                    $schemaFields .= "\$table->char('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'date':
                    $schemaFields .= "\$table->date('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'datetime':
                    $schemaFields .= "\$table->dateTime('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'time':
                    $schemaFields .= "\$table->time('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'timestamp':
                    $schemaFields .= "\$table->timestamp('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'text':
                    $schemaFields .= "\$table->text('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'mediumtext':
                    $schemaFields .= "\$table->mediumText('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'longtext':
                    $schemaFields .= "\$table->longText('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'json':
                    $schemaFields .= "\$table->json('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'jsonb':
                    $schemaFields .= "\$table->jsonb('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'binary':
                    $schemaFields .= "\$table->binary('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'number':
                case 'integer':
                    $schemaFields .= "\$table->integer('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'bigint':
                    $schemaFields .= "\$table->bigInteger('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'mediumint':
                    $schemaFields .= "\$table->mediumInteger('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'tinyint':
                    $schemaFields .= "\$table->tinyInteger('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'smallint':
                    $schemaFields .= "\$table->smallInteger('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'boolean':
                    $schemaFields .= "\$table->boolean('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'decimal':
                    $schemaFields .= "\$table->decimal('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'double':
                    $schemaFields .= "\$table->double('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                case 'float':
                    $schemaFields .= "\$table->float('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;

                default:
                    $schemaFields .= "\$table->string('" . $item['name'] . "');\n" . $tabIndent . $tabIndent . $tabIndent;
                    break;
            }
        }

        $primaryKey = $this->option('pk');

        $schemaUp =
            "Schema::create('" . $tableName . "', function(Blueprint \$table) {
            \$table->increments('" . $primaryKey . "');
            " . $schemaFields . "\$table->timestamps();
        });";

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

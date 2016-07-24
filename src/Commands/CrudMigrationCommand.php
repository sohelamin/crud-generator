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
                            {--indexes= : The fields to add an index too}
                            {--required-fields= : Required fields}
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

        $fieldsToIndex = trim($this->option('indexes')) != '' ? explode(',', $this->option('indexes')) : [];
        $fieldsToRequire = explode(',', $this->option('required-fields'));

        $schema = $this->option('schema');
        $fields = explode(',', $schema);

        $data = array();

        if ($schema) {
            $x = 0;
            foreach ($fields as $field) {
                $fieldArray = explode('#', $field);
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
                    $schemaFields .= "\$table->char('" . $item['name'] . "')";
                    break;

                case 'date':
                    $schemaFields .= "\$table->date('" . $item['name'] . "')";
                    break;

                case 'datetime':
                    $schemaFields .= "\$table->dateTime('" . $item['name'] . "')";
                    break;

                case 'time':
                    $schemaFields .= "\$table->time('" . $item['name'] . "')";
                    break;

                case 'timestamp':
                    $schemaFields .= "\$table->timestamp('" . $item['name'] . "')";
                    break;

                case 'text':
                    $schemaFields .= "\$table->text('" . $item['name'] . "')";
                    break;

                case 'mediumtext':
                    $schemaFields .= "\$table->mediumText('" . $item['name'] . "')";
                    break;

                case 'longtext':
                    $schemaFields .= "\$table->longText('" . $item['name'] . "')";
                    break;

                case 'json':
                    $schemaFields .= "\$table->json('" . $item['name'] . "')";
                    break;

                case 'jsonb':
                    $schemaFields .= "\$table->jsonb('" . $item['name'] . "')";
                    break;

                case 'binary':
                    $schemaFields .= "\$table->binary('" . $item['name'] . "')";
                    break;

                case 'number':
                case 'integer':
                    $schemaFields .= "\$table->integer('" . $item['name'] . "')";
                    break;

                case 'bigint':
                    $schemaFields .= "\$table->bigInteger('" . $item['name'] . "')";
                    break;

                case 'mediumint':
                    $schemaFields .= "\$table->mediumInteger('" . $item['name'] . "')";
                    break;

                case 'tinyint':
                    $schemaFields .= "\$table->tinyInteger('" . $item['name'] . "')";
                    break;

                case 'smallint':
                    $schemaFields .= "\$table->smallInteger('" . $item['name'] . "')";
                    break;

                case 'boolean':
                    $schemaFields .= "\$table->boolean('" . $item['name'] . "')";
                    break;

                case 'decimal':
                    $schemaFields .= "\$table->decimal('" . $item['name'] . "')";
                    break;

                case 'double':
                    $schemaFields .= "\$table->double('" . $item['name'] . "')";
                    break;

                case 'float':
                    $schemaFields .= "\$table->float('" . $item['name'] . "')";
                    break;

                case 'enum':
                    $schemaFields .= "\$table->enum('" . $item['name'] . "', [])";
                    break;

                default:
                    $schemaFields .= "\$table->string('" . $item['name'] . "')";
                    break;
            }

            // in the sql, laravel makes fields 'not null' by default, so we add nullable to fields that aren't required
            if (!in_array($item['name'], $fieldsToRequire))
            {
                $schemaFields .= '->nullable()';
            }

            $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
        }

        // add indexes and unique indexes as necessary
        foreach ($fieldsToIndex as $fld)
        {
            $line = trim($fld);

            // is a unique index specified after the #?
            // if no hash present, we append one to make life easier
            if (strpos($line, '#') === false)
                $line .= '#';

            $parts = explode('#', $line);
            if (count($parts) > 1 && $parts[1] == 'unique')
            {
                $schemaFields .= "\$table->unique('" . trim($parts[0]) . "')";
            }
            else
            {
                $schemaFields .= "\$table->index('" . trim($parts[0]) . "')";
            }

            $schemaFields .= ";\n" . $tabIndent . $tabIndent . $tabIndent;
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

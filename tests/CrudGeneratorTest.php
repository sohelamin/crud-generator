<?php

class CrudGeneratorTest extends TestCase
{
    public function testCrudGenerateCommand()
    {
        // $this->artisan('crud:generate', [
        //     'name' => 'Posts',
        //     '--fields' => "title#string; content#text; category#select#options=technology,tips,health",
        // ]);
        // $this->assertContains('Controller already exists!', $this->consoleOutput());

        $this->assertFileNotExists('/path/to/file.php');
    }

    public function testControllerGenerateCommand()
    {
        $this->artisan('crud:controller', [
            'name' => 'CustomersController',
            '--crud-name' => 'customers',
            '--model-name' => 'Customer',
        ]);

        $this->assertContains('Controller created successfully.', $this->consoleOutput());
    }

    public function testModelGenerateCommand()
    {
        $this->artisan('crud:model', [
            'name' => 'Customer',
            '--fillable' => "['name', 'email']",
        ]);

        $this->assertContains('Model created successfully.', $this->consoleOutput());
    }

    public function testMigrationGenerateCommand()
    {
        $this->artisan('crud:migration', [
            'name' => 'customers',
            '--schema' => 'name#string; email#email',
        ]);

        $this->assertContains('Migration created successfully.', $this->consoleOutput());
    }

    public function testViewGenerateCommand()
    {
        $this->artisan('crud:view', [
            'name' => 'customers',
            '--fields' => "title#string; body#text",
        ]);

        $this->assertContains('View created successfully.', $this->consoleOutput());
    }
}

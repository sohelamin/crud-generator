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
    }

    public function testControllerGenerateCommand()
    {
        $this->artisan('crud:controller', [
            'name' => 'CustomersController',
            '--crud-name' => 'customers',
            '--model-name' => 'Customer',
        ])->expectsOutput('Controller created successfully.');

        $this->assertFileExists(app_path('Http/Controllers') . '/CustomersController.php');
    }

    public function testModelGenerateCommand()
    {
        $this->artisan('crud:model', [
            'name' => 'Customer',
            '--fillable' => "['name', 'email']",
        ])->expectsOutput('Model created successfully.');

        $this->assertFileExists(app_path() . '/Customer.php');
    }

    public function testMigrationGenerateCommand()
    {
        $this->artisan('crud:migration', [
            'name' => 'customers',
            '--schema' => 'name#string; email#email',
        ])->expectsOutput('Migration created successfully.');
    }

    public function testViewGenerateCommand()
    {
        $this->artisan('crud:view', [
            'name' => 'customers',
            '--fields' => "title#string; body#text",
        ])->expectsOutput('View created successfully.');

        $this->assertDirectoryExists(config('view.paths')[0] . '/customers');
    }
}

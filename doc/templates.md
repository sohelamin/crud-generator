## Custom Templates
The package allows user to extensively customize or use own templates.

For this, you need to follow these things:

1. Just make sure you've published all assets of this package. If you didn't just run this command
    ```
    php artisan vendor:publish --provider="Appzcoder\CrudGenerator\CrudGeneratorServiceProvider"
    ```

2. To override the default template with yours turn on ```custom_template``` option in **config/crudgenerator.php**
    ```
    'custom_template' => true,
    ```

3. Now you can customize everything from this directory **resources/crud-generator/**

4. Even if you need to use any custom variable just add those on the file **config/crudgenerator.php**

[&larr; Back to index](README.md)

## Custom Templates

The package allows user to extensively customize or use own templates.

### All Templates

To customize or change the template, you need to follow these steps:

1. Just make sure you've published all assets of this package. If you didn't just run this command.
    ```php
    php artisan vendor:publish --provider="Appzcoder\CrudGenerator\CrudGeneratorServiceProvider"
    ```
2. To override the default template with yours, turn on `custom_template` option in the `config/crudgenerator.php` file.
    ```php
    'custom_template' => true,
    ```

3. Now you can customize everything from this `resources/crud-generator/` directory.

4. Even if you need to use any custom variable just add those in the `config/crudgenerator.php` file.

### Form Helper

You can use any form helper package for your forms. In order to do that, you just need to mention the helper package name while generating the main CRUD or views with this option `--form-helper`. This generator use plain `html` as default helper.
Also, the `laravelcollective/html` helper is included in the core, So it will do everything for you when you just run command with the option `--form-helper=laravelcollective`.

To use the any other form helper, you need to follow these steps:

1. Make sure you've installed & configured the desire helper package.

2. For use custom helper template, you should turn on `custom_template` option in the `config/crudgenerator.php` file.

3. Now put your files into `resources/crud-generator/views/` directory. Suppose your helper is `myformhelper` then you should create a directory as `resources/crud-generator/views/myformhelper`. You can also copy the template files from other helper directory, then modify as yours.

4. You're ready to generate the CRUD with your helper.
    ```
    php artisan crud:generate Posts --fields='title#string; content#text; category#select#options={"technology": "Technology", "tips": "Tips", "health": "Health"}' --view-path=admin --controller-namespace=Admin --route-group=admin --form-helper=myformhelper
    ```

[&larr; Back to index](README.md)

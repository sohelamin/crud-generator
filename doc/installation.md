## Installation
Open your terminal(CLI), go to the root directory of your Laravel project, then follow the following procedure.

For Laravel >= 5.5 you need to follow these steps
---

1. Run
    ```
    composer require appzcoder/crud-generator --dev
    composer require laravelcollective/html
    ```

2. Publish vendor files of this package.
    ```
    php artisan vendor:publish --provider="Appzcoder\CrudGenerator\CrudGeneratorServiceProvider"
    ```

For Laravel < 5.5 you need to follow these steps
---

1. Run
    ```
    composer require appzcoder/crud-generator --dev
    ```
2. Since the package is only use in local developmnet, add the provider in app/Providers/AppServiceProvider.php.
    ```php
    public function register()
    {
        if ($this->app->environment() == 'local') {
            $this->app->register('Appzcoder\CrudGenerator\CrudGeneratorServiceProvider');
        }
    }
    ```
3. Install **laravelcollective/html** helper package if you haven't installed it already.
    * Run

    ```
    composer require laravelcollective/html
    ```

    * Add service provider & aliases to **config/app.php**.
    ```php
    'providers' => [
        ...

        Collective\Html\HtmlServiceProvider::class,
    ],

    'aliases' => [
        ...

        'Form' => Collective\Html\FormFacade::class,
        'HTML' => Collective\Html\HtmlFacade::class,
    ],
    ```

4. Run ```composer dump-autoload```

5. Publish vendor files of this package.
    ```
    php artisan vendor:publish --provider="Appzcoder\CrudGenerator\CrudGeneratorServiceProvider"
    ```

Note: You should have configured database for this operation.

[&larr; Back to index](README.md)

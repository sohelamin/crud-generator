## Installation

To get started, you should add the `appzcoder/crud-generator` Composer dependency to your project:
```
composer require appzcoder/crud-generator --dev
```
Once the package is installed, you should register the `Appzcoder\CrudGenerator\CrudGeneratorServiceProvider` service provider. Normally, Laravel 5.5+ will register the service provider automatically. If you're using an older verson of Laravel (<5.5) then just manually add the provider to `app/Providers/AppServiceProvider.php` file.

```php
public function register()
{
    if ($this->app->environment() == 'local') {
        $this->app->register('Appzcoder\CrudGenerator\CrudGeneratorServiceProvider');
    }
}
```

After that, publish its assets using the `vendor:publish` Artisan command:
```
php artisan vendor:publish --provider="Appzcoder\CrudGenerator\CrudGeneratorServiceProvider"
```

If you want to use `laravelcollective/html` form helper package for your CRUD's form then just install it.
```
composer require laravelcollective/html
```

Check the [docs](https://laravelcollective.com/docs/master/html) for the details of `laravelcollective/html`.

[&larr; Back to index](README.md)

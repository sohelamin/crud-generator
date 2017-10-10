## Installation

To get started, you should add the `appzcoder/crud-generator` Composer dependency to your project:
```
composer require appzcoder/crud-generator --dev
```
Once the package is installed, you should register the `Appzcoder\CrudGenerator\CrudGeneratorServiceProvider` service provider. Normally, Laravel 5.5+ will register the service provider automatically.

After that, publish its assets using the `vendor:publish` Artisan command:
```
php artisan vendor:publish --provider="Appzcoder\CrudGenerator\CrudGeneratorServiceProvider"
```

### Laravel older 5.5

If you're using an older verson of Laravel (<5.5) then just manually add the provider to `app/Providers/AppServiceProvider.php` file.

```php
public function register()
{
    if ($this->app->environment() == 'local') {
        $this->app->register('Appzcoder\CrudGenerator\CrudGeneratorServiceProvider');
    }
}
```

And since, we're using `laravelcollective/html` as dependency you should add its service provider in the `config/app.php` file. Check the [docs](https://laravelcollective.com/docs/master/html) for details.

```php
'providers' => [
    //...

    Collective\Html\HtmlServiceProvider::class,
],

'aliases' => [
    //...

    'Form' => Collective\Html\FormFacade::class,
    'HTML' => Collective\Html\HtmlFacade::class,
],
```

[&larr; Back to index](README.md)

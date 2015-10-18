# Laravel 5 CRUD Generator
Laravel CRUD Generator

### Requirements
    Laravel >=5.1
    PHP >= 5.5.9

## Installation

1. Run
    ```
    composer require appzcoder/crud-generator
    ```

2. Add service provider to **/config/app.php** file.
    ```php
    'providers' => [
        ...

        Appzcoder\CrudGenerator\CrudGeneratorServiceProvider::class,
        // Use the line bellow for "laravelcollective/html" package otherwise remove it.
        Collective\Html\HtmlServiceProvider::class,
    ],

    // Use the lines bellow for "laravelcollective/html" package otherwise remove it.
    'aliases' => [
        ...

        'Form'      => Collective\Html\FormFacade::class,
        'HTML'      => Collective\Html\HtmlFacade::class,
    ],
    ```
3. Run **composer update**

Note: You should have configured database as well for this operation.

## Commands

#### Crud command:

```
php artisan crud:generate Person --fields="name:string, email:string, age:number, message:text"
```

You can also easily include route, set primary key, set views directory etc through options **--route**, **--pk**, **--view-path** as bellows:

```
php artisan crud:generate Person --fields="name:string, email:string, age:number, message:text" --route="yes" --pk="id" --view-path="admin"
```

-----------
-----------


#### Other commands (optional):

For controller generator:

```
php artisan crud:controller PersonController --crud-name="Person" --view-path="directory"
```

For model generator:

```
php artisan crud:model Person --fillable="['name', 'email', 'message']"
```

For migration generator:

```
php artisan crud:migration person --schema="name:string, email:string, age:number, message:text"
```

For view generator:

```
php artisan crud:view Person --fields="name:string, email:string, age:number, message:text" --view-path="directory"
```

By default, the generator will attempt to append the crud route to your *routes.php* file. If you don't want the route added, you can use the option ```--route=no```.

#### After creating all resources run migrate command *(and, if necessary, include the route for your crud as well)*.

```
php artisan migrate
```

If you chose not to add the crud route in automatically (see above), you will need to include the route manually.
```php
Route::resource('person', 'PersonController');
```

### Supported crud fields for migration and views
string, char, varchar, password, email, date, datetime, time, timestamp, text, mediumtext, longtext, json, jsonb, binary, number, integer, bigint, mediumint, tinyint, smallint, boolean, decimal, double, float

##Author

[Sohel Amin](http://www.sohelamin.com)

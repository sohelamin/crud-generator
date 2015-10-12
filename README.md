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
    
2. Add service provider into **/config/app.php** file.
    ```php
    'providers' => [
        ...
    
        Appzcoder\CrudGenerator\CrudGeneratorServiceProvider::class,
    ],
    ```
    
    Add bellow lines for "laravelcollective/html" package if you've not done yet.

    ```php
    'providers' => [
        ...
    
        Collective\Html\HtmlServiceProvider::class,
    ],
    
    'aliases' => [
    
        ...
    
        'Form'		=> Collective\Html\FormFacade::class, 
    	'HTML'		=> Collective\Html\HtmlFacade::class,
    ],
    ```
3. Run **composer update**

Note: You should have configured database as well for this operation.

## Commands

#### Crud command:

```
php artisan crud:generate Person --fields="name:string, email:string, phone:integer, message:text"
```

You can also easily include route, set primary key, set view directory etc through options **--route**, **--pk**, **--view-path** as bellows:

```
php artisan crud:generate Person --fields="name:string, email:string, phone:integer, message:text" --route=yes --pk=id --view-path=admin
```

-----------
-----------


#### Others command (optional):

For controller generator: 

```
php artisan crud:controller PersonController --crud-name="Person"
```

For model generator: 

```
php artisan crud:model Person --fillable="['name', 'email', 'message']"
```

For migration generator: 

```
php artisan crud:migration person --schema="name:string, email:string, phone:integer, message:text"
```

For view generator: 

```
php artisan crud:view Person --fields="name:string, email:string, phone:integer, message:text"
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

##Author

[Sohel Amin](http://www.sohelamin.com)

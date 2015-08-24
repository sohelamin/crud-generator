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
php artisan crud:migration Person --schema="name:string, email:string, phone:integer, message:text"
```

For view generator: 

```
php artisan crud:view Person --fields="name:string, email:string, phone:integer, message:text"
```

#### After creating all resources run migrate command and include the route for your crud as well.

```
php artisan migrate
```

```php
Route::resource('person', 'PersonController');
```

##Author

<a href="http://www.sohelamin.com">Sohel Amin</a>

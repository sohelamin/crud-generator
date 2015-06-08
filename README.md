# Laravel5 Crud Generator
Laravel CRUD Generator

## Installation

1. Run 
    ```
    composer require "appzcoder/crud-generator":"dev-master"
    ```
    
2. Add service provider into **/config/app.php** file.
    ```php
    'providers' => [
        ...
    
        'Appzcoder\CrudGenerator\CrudGeneratorServiceProvider',
    ],
    ```
    
    Add bellow lines for "illuminate/html" package if you've not done yet.

    ```php
    'providers' => [
        ...
    
        'Illuminate\Html\HtmlServiceProvider',
    ],
    
    'aliases' => [
    
        ...
    
        'Form'=> 'Illuminate\Html\FormFacade', 
        'HTML'=> 'Illuminate\Html\HtmlFacade',
    ],
    ```
3. Run **composer update**

Note: You should have configured database as well for this operation.

## Commands

#### Crud command:

```
php artisan crud:generate crud-name --fields="name:string, email:string, phone:integer, message:text"
```

-----------
-----------


#### Others command (optional):

For controller generator: 

```
php artisan crud:controller NameController --crud-name="Name"
```

For model generator: 

```
php artisan crud:model Name --fillable="['name', 'email', 'message']"
```

For migration generator: 

```
php artisan crud:migration migration-name --schema="name:string, email:string, phone:integer, message:text"
```

For view generator: 

```
php artisan crud:view crud-name --fields="name:string, email:string, phone:integer, message:text"
```

##Author

<a href="http://www.sohelamin.com">Sohel Amin</a>

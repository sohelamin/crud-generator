# Laravel5 Crud Generator
Laravel CRUD Generator

## Installation

1. Run 
    ```
    composer require "appzcoder/crud-generator":"dev-master"
    ```
    or add ***"appzcoder/crud-generator": "dev-master"*** on your composer require section.
  
2. Add service provider into **/config/app.php** file.
    ```php
    'Appzcoder\CrudGenerator\CrudGeneratorServiceProvider',
    ```

3. Run **composer update**

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

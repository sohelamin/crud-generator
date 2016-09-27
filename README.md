# Laravel 5 CRUD Generator

[![Total Downloads](https://poser.pugx.org/appzcoder/crud-generator/d/total.svg)](https://packagist.org/packages/appzcoder/crud-generator)
[![Latest Stable Version](https://poser.pugx.org/appzcoder/crud-generator/v/stable.svg)](https://packagist.org/packages/appzcoder/crud-generator)
[![Latest Unstable Version](https://poser.pugx.org/appzcoder/crud-generator/v/unstable.svg)](https://packagist.org/packages/appzcoder/crud-generator)
[![License](https://poser.pugx.org/appzcoder/crud-generator/license.svg)](https://packagist.org/packages/appzcoder/crud-generator)

### Requirements
    Laravel >=5.1
    PHP >= 5.5.9

## Installation

1. Run
    ```
    composer require appzcoder/crud-generator
    ```

2. Add the service provider to **config/app.php**.
    ```php
    'providers' => [
        ...

        Appzcoder\CrudGenerator\CrudGeneratorServiceProvider::class,
    ],
    ```
3. Install **laravelcollective/html** helper package.
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

## Commands

#### Crud command:


```
php artisan crud:generate Posts --fields="title#string; content#text; category#select#options=technology,tips,health" --view-path=admin --controller-namespace=Admin --route-group=admin
```

Options:

| Option    | Description |
| ---       | ---     |
| `--fields` | Fields name for the form & migration. e.g. ```--fields="title#string; content#text; category#select#options=technology,tips,health; user_id#integer#unsigned"``` |
| `--route` | Include Crud route to routes.php? yes or no |
| `--pk` | The name of the primary key |
| `--view-path` | The name of the view path |
| `--controller-namespace` | The namespace of the controller - sub directories will be created |
| `--model-namespace` | The namespace that the model will be placed in - directories will be created |
| `--route-group` | Prefix of the route group |
| `--pagination` | The amount of models per page for index pages |
| `--indexes` | The fields to add an index to. append "#unique" to a field name to add a unique index. Create composite fields by separating fieldnames with a pipe (```--indexes="title,field1|field2#unique"``` will create normal index on title, and unique composite on fld1 and fld2) |
| `--foreign-keys` | Any foreign keys for the table. e.g. ```--foreign-keys="user_id#id#users#cascade"``` where user_id is the column name, id is the name of the field on the foreign table, users is the name of the foreign table, and cascade is the operation 'ON DELETE' together with 'ON UPDATE' |
| `--validations` | Validation rules for the form "col_name#rules_set" e.g. ```"title#min:10|max:30|required"``` - See https://laravel.com/docs/master/validation#available-validation-rules |
| `--relationships` | The relationships for the model. e.g. ```--relationships="comments#hasMany#App\Comment"``` in the format |
| `--localize` | Allow to localize. e.g. localize=yes  |
| `--locales`  | Locales language type. e.g. locals=en |

-----------


#### Other commands (optional):

For controller:

```
php artisan crud:controller PostsController --crud-name=posts --model-name=Post --view-path="directory" --route-group=admin
```

For model:

```
php artisan crud:model Post --fillable="['title', 'body']"
```

For migration:

```
php artisan crud:migration posts --schema="title#string; body#text"
```

For view:

```
php artisan crud:view posts --fields="title#string; body#text" --view-path="directory" --route-group=admin
```

By default, the generator will attempt to append the crud route to your ```Route``` file. If you don't want the route added, you can use this option ```--route=no```.

After creating all resources, run migrate command. *If necessary, include the route for your crud as well.*

```
php artisan migrate
```

If you chose not to add the crud route in automatically (see above), you will need to include the route manually.
```php
Route::resource('posts', 'PostsController');
```

### Supported Field Types

These fields are supported for migration and view's form:

#### Form Field Types:
* text
* textarea
* password
* email
* number
* date
* datetime
* time
* radio
* select
* file

#### Migration Field Types:
* string
* char
* varchar
* date
* datetime
* time
* timestamp
* text
* mediumtext
* longtext
* json
* jsonb
* binary
* integer
* bigint
* mediumint
* tinyint
* smallint
* boolean
* decimal
* double
* float
* enum


### Custom Generator's Stub Template

You can customize the generator's stub files/templates to achieve your need.

1. Make sure you've published package's assets.
    ```
    php artisan vendor:publish --provider="Appzcoder\CrudGenerator\CrudGeneratorServiceProvider"
    ```

2. Turn on custom_template support on **config/crudgenerator.php**
    ```
    'custom_template' => true,
    ```
3. From the directory **resources/crud-generator/** you can modify or customize the stub files.

#### If you're still looking for easier one then try this [Admin Panel](https://github.com/appzcoder/laravel-admin)

## Author

[Sohel Amin](http://www.sohelamin.com)

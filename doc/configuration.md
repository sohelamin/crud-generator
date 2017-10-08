## Configuration

You will find a configuration file located at `config/crudgenerator.php`

### Custom Template

When you want to use your own custom template files, then you should turn it on and it will use the files from `resources/crud-generator/`

```php
'custom_template' => false,
```

### Path

You can change your template path easily, the default path is `resources/crud-generator/`.

```php
'path' => base_path('resources/crud-generator/'),
```

### View Columns

When generating CRUD or the views, the generator will assume the column number to show for the CRUD grid or detail automatically from the config. You can change it.

```php
'view_columns_number' => 3,
```

### Custom Delimiter

Set your delimiter which you use for your template vars. The default delimiter is `%%` in everywhere.

```php
'custom_delimiter' => ['%%', '%%'],
```
Note: You should use the delimiter same as yours template files.

### View Template Vars

This configuration will help you to use any custom template vars in the views `index`, `form`, `create`, `edit`, `show`

```php
'dynamic_view_template' => [],
```

[&larr; Back to index](README.md)

## Usage

### CRUD Command

```
php artisan crud:generate Posts --fields='title#string; content#text; category#select#options={"technology": "Technology", "tips": "Tips", "health": "Health"}' --view-path=admin --controller-namespace=Admin --route-group=admin --form-helper=html
```

#### CRUD fields from a JSON file:

```json
{
    "fields": [
        {
            "name": "title",
            "type": "string"
        },
        {
            "name": "content",
            "type": "text"
        },
        {
            "name": "category",
            "type": "select",
            "options": {
                "technology": "Technology",
                "tips": "Tips",
                "health": "Health"
            }
        },
        {
            "name": "user_id",
            "type": "integer#unsigned"
        }
    ],
    "foreign_keys": [
        {
            "column": "user_id",
            "references": "id",
            "on": "users",
            "onDelete": "cascade"
        }
    ],
    "relationships": [
        {
            "name": "user",
            "type": "belongsTo",
            "class": "App\\User"
        }
    ],
    "validations": [
        {
            "field": "title",
            "rules": "required|max:10"
        }
    ]
}
```

```
php artisan crud:generate Posts --fields_from_file="/path/to/fields.json" --view-path=admin --controller-namespace=Admin --route-group=admin --form-helper=html
```

### Other Commands

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
php artisan crud:view posts --fields="title#string; body#text" --view-path="directory" --route-group=admin --form-helper=html
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

### API Commands

For api crud:

```
php artisan crud:api Posts --fields='title#string; content#text' --controller-namespace=Api
```

For api controller:

```
php artisan crud:api-controller Api\\PostsController --crud-name=posts --model-name=Post
```

### File Upload
If you want to add file on a CRUD just mention the field type as `file` eg. ```--fields='avatar#file;```

All the files will upload to `storage\app\public\uploads` directory. So you should symbolic the storage dir to public access.
```
php artisan storage:link
```
Get your uploaded file as:
```php
$file = Storage::disk('public')->get('uploads\filename.jpg');
```

[&larr; Back to index](README.md)

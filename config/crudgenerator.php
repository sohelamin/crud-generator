<?php

return [

    'custom_template' => false,

    /*
    |--------------------------------------------------------------------------
    | Crud Generator Template Stubs Storage Path
    |--------------------------------------------------------------------------
    |
    | Here you can specify your custom template path for the generator.
    |
     */

    'path' => base_path('resources/crud-generator/'),

    /**
     * Columns number to show in view's table.
     */
    'view_columns_number' => 3,

    /*
    |--------------------------------------------------------------------------
    | Dynamic templating
    |--------------------------------------------------------------------------
    |
    | Here you can specify your customs templates for the generator.
    | You can set new templates or delete some templates if you do not want them.
    | You can also choose which values are passed to the views and you can specify a custom delimiter for all templates
    |
     */
    //'custom_delimiter' => ['#!', '!#'],
    'dynamic_view_template' => [
        'index' => ['formHeadingHtml', 'formBodyHtml', 'crudName', 'crudNameCap', 'modelName', 'viewName', 'routeGroup', 'primaryKey'],
        'form'  => ['formFieldsHtml'],
        'create' => ['crudName', 'crudNameCap', 'modelName', 'viewName', 'routeGroup', 'viewTemplateDir'],
        'edit' => ['crudName', 'crudNameSingular', 'crudNameCap', 'modelName', 'viewName', 'routeGroup', 'primaryKey', 'viewTemplateDir'],
        'show' => ['formHeadingHtml', 'formBodyHtml', 'crudName', 'crudNameCap', 'modelName', 'viewName', 'routeGroup', 'primaryKey']
        /*
         * Add new stubs templates here if you need to, like action, datatable...
         * custom_template needs to be activated for this to work
         */
    ]






];

<?php

if (!function_exists('get_enum_values')) {
    /**
     * Return the given object. Useful for chaining.
     *
     * @param  mixed  $object
     * @return mixed
     */
    function get_enum_values($model, $column)
    {
        $model_class = "App\\$model";

        return $model_class::getEnumValues($column);
    }
}

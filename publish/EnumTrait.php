<?php

namespace App;

use DB;

trait EnumTrait
{
    /**
     * Get enum values from a column.
     *
     * @param  string $column
     *
     * @return array
     */
    public static function getEnumValues($column)
    {
        $instance = new static;
        $type = DB::select(DB::raw('SHOW COLUMNS FROM ' . $instance->getTable() . ' WHERE Field = "' . $column . '"'))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);

        $enum = [];
        foreach (explode(',', $matches[1]) as $value) {
            $v = trim($value, "'");
            $enum = array_add($enum, $v, $v);
        }

        return $enum;
    }
}

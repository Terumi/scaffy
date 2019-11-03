<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class TemplateManager
{
    public static function relation_one_to_one($relation)
    {
        $content = Storage::disk('scaffy')->get('stubs/relations/one_to_one.stub');
        $content = str_replace('#related_to#', strtolower($relation->relates_to), $content);
        $content = str_replace('//related_model', 'App\\' . $relation->relates_to, $content);
        $content = str_replace('//foreign_key', $relation->foreignKey, $content);
        $content = str_replace('//local_key', $relation->localKey, $content);
        return $content;
    }

    public static function relation_one_to_many($relation)
    {
        $content = Storage::disk('scaffy')->get('stubs/relations/one_to_many.stub');
        $content = str_replace('#related_to#', strtolower($relation->relates_to), $content);
        $content = str_replace('//related_model', 'App\\' . $relation->relates_to, $content);
        $content = str_replace('//foreign_key', $relation->foreignKey, $content);
        $content = str_replace('//local_key', $relation->localKey, $content);
        return $content;
    }

    public static function relation_many_to_many($relation)
    {
        $content = Storage::disk('scaffy')->get('stubs/relations/one_to_many.stub');
        $content = str_replace('#related_to#', strtolower($relation->relates_to), $content);
        $content = str_replace('//pivot_table', $relation->pivotTable, $content);
        $content = str_replace('//related_model', 'App\\' . $relation->relates_to, $content);
        $content = str_replace('//foreign_key', $relation->foreignKey, $content);
        $content = str_replace('//local_key', $relation->localKey, $content);
        return $content;
    }
}
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

    public static function route_group($model)
    {
        $content = Storage::disk('scaffy')->get('stubs/route_group.stub');
        $content = str_replace('#_name', strtolower($model), $content);
        $content = str_replace('#_controller', $model . 'Controller', $content);
        return $content;
    }

    public static function migration($column)
    {
        $content = '$table->' . $column->type . '(\''. $column->name .'\');'. "\n";
        return $content;
    }

    /*public static function add_additional_routes_to_routes_file(Content $extra_content)
    {
        $content = Storage::disk('routes')->get('web.php');
        $content .= $extra_content->body;
        return $content;
    }*/
}
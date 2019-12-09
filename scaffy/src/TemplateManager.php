<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class TemplateManager
{
    public static function relation_one_to_one($relation)
    {
        $content = Storage::disk('scaffy')->get('stubs/relations/one_to_one.stub');
        $content = str_replace('#related_to#', strtolower($relation->related_model), $content);
        $content = str_replace('//related_model', 'App\\' . $relation->related_model, $content);
        $content = str_replace('//foreign_key', $relation->key_written_on_current_table, $content);
        $content = str_replace('//local_key', $relation->key_on_original_table, $content);
        return $content;
    }

    public static function relation_one_to_many($relation)
    {
        $content = Storage::disk('scaffy')->get('stubs/relations/one_to_many.stub');
        $content = str_replace('#related_to#', strtolower($relation->related_model), $content);
        $content = str_replace('//related_model', 'App\\' . $relation->related_model, $content);
        $content = str_replace('//foreign_key', $relation->key_written_on_current_table, $content);
        $content = str_replace('//local_key', $relation->key_on_original_table, $content);
        return $content;
    }

    public static function relation_many_to_many($relation)
    {
        $content = Storage::disk('scaffy')->get('stubs/relations/one_to_many.stub');
        //$content = str_replace('#related_to#', strtolower($relation->relates_to), $content);
        //$content = str_replace('//pivot_table', $relation->pivotTable, $content);
        //$content = str_replace('//related_model', 'App\\' . $relation->relates_to, $content);
        //$content = str_replace('//foreign_key', $relation->foreignKey, $content);
        //$content = str_replace('//local_key', $relation->localKey, $content);
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
        $content = '$table->' . $column->type . '(\'' . $column->name . '\');' . "\n";
        return $content;
    }

    public static function belongs_to_relation_in_migration($relation)
    {
        $content = "\$table->integer('$relation->key_written_on_current_table');\n";
        $content .= "\$table->foreign('$relation->key_written_on_current_table')->references('$relation->key_on_original_table')->on('$relation->relates_to_table_name');\n";
        return $content;
    }

    public static function belongs_to_many_relation_in_migration($model, $relation)
    {
        if ($relation->model_one == $model) {
            $content = "\$table->integer('$relation->pivot_key');\n";
            $content .= "\$table->foreign('$relation->key_written_on_current_table')->references('$relation->key_on_original_table')->on('$relation->relates_to_table_name');\n";
        } else {
            $content = "\$table->integer('$relation->key_written_on_current_table');\n";
            $content .= "\$table->foreign('$relation->key_written_on_current_table')->references('$relation->key_on_original_table')->on('$relation->relates_to_table_name');\n";
        }

        return $content;
    }

    public static function pivot_quick_table($relation)
    {
        $content = Storage::disk('scaffy')->get('stubs/quick_pivot.stub');
        $content = str_replace('DummyClass', $relation->model_one . $relation->model_two, $content);
        $content = str_replace('#table_name', strtolower($relation->pivot), $content);
        $content = str_replace('#table_one', strtolower($relation->table_one), $content);
        $content = str_replace('#table_one', strtolower($relation->table_two), $content);
        $content = str_replace('#pivot_key_one', strtolower($relation->pivot_key_one), $content);
        $content = str_replace('#key_one', strtolower($relation->key_one), $content);
        $content = str_replace('#pivot_key_two', strtolower($relation->pivot_key_two), $content);
        $content = str_replace('#key_two', strtolower($relation->key_two), $content);
        return $content;
    }

    public static function belongs_to_relation_in_model()
    {
        $content = Storage::disk('scaffy')->get('stubs/relations/one_to_one.stub');
    }

    public static function inverse_relation_one_to_one($relation)
    {
        $content = Storage::disk('scaffy')->get('stubs/relations/inverse_one_to_one.stub');
        $content = str_replace('#related_to#', strtolower($relation->relates_to), $content);
        $content = str_replace('//related_model', 'App\\' . $relation->relates_to, $content);
        $content = str_replace('//foreign_key', $relation->key_on_original_table, $content);
        $content = str_replace('//local_key', $relation->key_written_on_current_table, $content);
        return $content;
    }


    /*public static function add_additional_routes_to_routes_file(Content $extra_content)
    {
        $content = Storage::disk('routes')->get('web.php');
        $content .= $extra_content->body;
        return $content;
    }*/
}
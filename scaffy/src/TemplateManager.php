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
        $content = str_replace('#related_to#', strtolower($relation->relates_to), $content);
        $content = str_replace('//related_model', 'App\\' . $relation->relates_to, $content);
        $content = str_replace('//foreign_key', $relation->key_on_original_table, $content);
        $content = str_replace('//local_key', $relation->key_written_on_current_table, $content);
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

    public static function belongs_to($relation)
    {
        $content = new Content('stubs/relations/belongs_to.stub');
        $content->replace('#relates_to', $relation->relates_to);
        $content->replace('#foreign_key', $relation->key_written_on_current_table);
        $content->replace('#local_key', $relation->key_on_original_table);
        return $content;
    }

    public static function has_many($model, $relation)
    {
        $content = new Content('stubs/relations/has_many.stub');
        $content->replace('#function_name', strtolower($model) . 's');
        $content->replace('#model', $model);
        $content->replace('#foreign_key', $relation->key_written_on_current_table);
        $content->replace('#local_key', $relation->key_on_original_table);
        return $content;
    }

    public static function has_one($relation, $model = null, $inverted = false)
    {
        $content = new Content('stubs/relations/has_one.stub');

        if ($inverted) {
            $content->replace('#function_name', strtolower($model));
            $content->replace('#model', $model);
            $content->replace('#foreign_key', $relation->key_written_on_current_table);
            $content->replace('#local_key', $relation->key_on_original_table);
        } else {
            $content->replace('#function_name', strtolower($relation->relates_to));
            $content->replace('#model', $relation->relates_to);
            $content->replace('#foreign_key', $relation->key_on_original_table);
            $content->replace('#local_key', $relation->key_written_on_current_table);
        }
        return $content;
    }


    public static function many_to_many($model, $relation)
    {
        $inverted = false;

        if ($model == $relation->model_two)
            $inverted = true;

        $content = new Content('stubs/relations/many_to_many.stub');
        $content->replace('#model_one', $inverted ? strtolower($relation->model_one) . 's' : strtolower($relation->model_two) . 's');
        $content->replace('#model_two', $inverted ? $relation->model_one : $relation->model_two);
        $content->replace('#pivot', $relation->pivot);
        $content->replace('#foreign_key', $inverted ? $relation->pivot_key_two : $relation->pivot_key_one);
        $content->replace('#local_key', $inverted ? $relation->pivot_key_one : $relation->pivot_key_two);

        return $content;
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
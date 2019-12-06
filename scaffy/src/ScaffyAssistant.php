<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class ScaffyAssistant
{
    /**
     * @return array
     */
    public static function get_models(): array
    {
        $file_names = Storage::disk('scaffy')->directories('config');
        return self::remove_config_folder_from_name($file_names);
    }

    /**
     * @param $model
     * @return array
     */
    public static function get_model_files($model)
    {
        $file_names = Storage::disk('scaffy')->files('config/' . $model);
        return self::remove_config_folder_from_name($file_names);
    }

    /**
     * @param array $file_names
     * @return array
     */
    protected static function remove_config_folder_from_name(array $file_names): array
    {
        array_walk($file_names, [self::class, 'extract_name']);
        return $file_names;
    }

    /**
     * @param String $victim
     */
    public static function extract_name(String &$victim)
    {
        $victim = str_replace('config/', '', $victim);
    }

    public static function get_model_config_file($model)
    {
        $file_name = 'model.json';
        return self::get_file($model, $file_name);
    }

    public static function get_relations_config_file($model)
    {
        $file_name = 'relations.json';
        return self::get_file($model, $file_name);
    }

    /**
     * @param $model
     * @param string $file_name
     * @return mixed
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    protected static function get_file($model, string $file_name)
    {
        if (!Storage::disk('scaffy')->has('config/' . $model . '/' . $file_name))
            return false;

        $file = Storage::disk('scaffy')->get('config/' . $model . '/' . $file_name);
        return json_decode($file);
    }

    public static function determine_relation_fields($relation)
    {
        if (count($relation) > 1)
            die(var_dump($relation));

        echo "------------------\n";
        
        //todo: make it return an array

        return $relation;
    }
}
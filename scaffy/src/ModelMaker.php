<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class ModelMaker
{
    public static function run()
    {
        //read the models
        $models = ScaffyAssistant::get_models();

        //iterate over them and do
        foreach ($models as $model) {
            $content = new Content('stubs/model.stub');

            $model_config = ScaffyAssistant::get_model_config_file($model);
            $content->replace('_model_name', $model_config->model_name);
            $content->replace('_table_name', $model_config->table);

            $content->save($model);
        }

        foreach ($models as $model) {
            echo $model;
            echo "\n";
            self::add_relations_to_model($model);
            echo "-------------------------------------\n";

        }
    }


    private static function add_relations_to_model($model)
    {

        //  if (!is_array($relations)) {
        //      $relations = [$relations];
        //  };

        $relations = ScaffyAssistant::get_relations_config_file($model);

        if (!$relations)
            return;

        $content = new Content("$model.php");

        foreach ($relations as $relation) {
            switch ($relation->type) {
                case 'One to One':
                    $content->add(TemplateManager::has_one($relation), '//relations');

                    $invert_content = new Content($relation->relates_to . ".php");
                    $invert_content->add(TemplateManager::has_one($relation, $model, true), '//relations');
                    $invert_content->save($relation->relates_to);

                    break;
                case 'One to Many':
                    //$content = new Content("$relation->relates_to.php");
                    $content->add(TemplateManager::belongs_to($relation), '//relations');
                    $invert_content = new Content($relation->relates_to . ".php");
                    $invert_content->add(TemplateManager::has_many($model, $relation), '//relations');
                    $invert_content->save($relation->relates_to);

                    break;
                case 'Many to Many':
                    continue;
                    //$content->add(TemplateManager::relation_many_to_many($relation), '//relations');
                    break;
                case 'Has One Through':
                    //todo
                    break;
                case 'Has Many Through':
                    //todo
                    break;
            }
            $content->save($model);

        }
        //$content->replace("//relations", '');
    }
}
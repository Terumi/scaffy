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

            $relations = ScaffyAssistant::get_relations_config_file($model);
            if ($relations) {
                self::add_relations_to_model($content, $relations);
            }
            $content->save($model);
        }
        die();
    }


    private static function add_relations_to_model(Content &$content, $relations)
    {
        if (!is_array($relations)) {
            $relations = [$relations];
        };

        foreach ($relations as $relation) {

            switch ($relation->relationType) {
                case 'One to One':
                    $with = TemplateManager::relation_one_to_one($relation) . "//relations";
                    $content->replace("//relations", $with);
                    break;
                case 'One to Many':
                    $with = TemplateManager::relation_one_to_many($relation) . "//relations";
                    $content->replace("//relations", $with);
                    break;
                case 'Many to Many':
                    $with = TemplateManager::relation_many_to_many($relation) . "//relations";
                    $content->replace("//relations", $with);
                    break;
                case 'Has One Through':
                    //todo
                    break;
                case 'Has Many Through':
                    //todo
                    break;
            }
            $content->append($relation->relates_to);
        }
        $content->replace("//relations", '');
    }
}
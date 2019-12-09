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
            self::add_relations_to_model($model);
        }


        die();
    }


    private static function add_relations_to_model($model)
    {


        /* todo:
                for one to one
                this goes to table_one model
                    public function phone()
                    {
                     return $this->hasOne('App\Phone', 'foreign_key', 'local_key');
                    }

                and this to table_two
                    public function user()
                    {
                        return $this->belongsTo('App\User', 'foreign_key', 'local_key');
                    }

                 */

        /* todo:
        for one to many
        this is on "Post" table
            public function comments()
            {
                return $this->hasMany('App\Comment', 'foreign_key', 'local_key');
            }

        and this on "Comment" table:
            public function post()
            {
                return $this->belongsTo('App\Post', 'foreign_key', 'other_key');
            }

         */

        //  if (!is_array($relations)) {
        //      $relations = [$relations];
        //  };

        $relations = ScaffyAssistant::get_relations_config_file($model);

        foreach ($relations as $relation) {
            switch ($relation->type) {
                case 'One to One':
                    $relation->related_model = $model;
                    $content = new Content("$relation->relates_to.php");
                    $content->add(TemplateManager::relation_one_to_one($relation), "//relations");
                    $content->save($relation->relates_to);

                    //write the inverse belongsTo
                    $content = new Content("$model.php");
                    $content->add(TemplateManager::inverse_relation_one_to_one($relation), "//relations");
                    $content->save($model);

                    break;
                case 'One to Many':
                    $relation->related_model = $model;
                    $content = new Content("$relation->relates_to.php");
                    $content->add(TemplateManager::relation_one_to_many($relation), '//relations');
                    $content->save($relation->relates_to);

                    $content = new Content("$model.php");
                    $content->add(TemplateManager::inverse_relation_one_to_one($relation), "//relations");
                    $content->save($relation->relates_to);


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
        }
        //$content->replace("//relations", '');
    }
}
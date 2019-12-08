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

        if (!is_array($relations)) {
            $relations = [$relations];
        };

        foreach ($relations as $relation) {
            switch ($relation->type) {
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
        }
        $content->replace("//relations", '');
    }
}
<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class RouteMaker
{
    public static function run()
    {

        $original_content = new Content('web.php', 'routes');
        $original_content = $original_content->initialize();

        //read the models
        $models = ScaffyAssistant::get_models();
        //get the basic routes stub
        $content = new Content('stubs/routes.stub');
        //iterate over them and do
        foreach ($models as $model) {
            $inner_content = TemplateManager::route_group($model);
            $content->add($inner_content, '#routes#');

        }
        $original_content->append($content->body);
        $original_content->save('web');
    }
}
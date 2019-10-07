<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class RouteMaker
{

    /**
     * @return Content
     */
    protected static function getContent(): Content
    {
        $route_content = new Content('routes', 'web.php', 'routes.stub');
        return $route_content;
    }

    public static function initialize_routes()
    {
        $route_content = self::getContent();
        $route_content->initialize();
    }

    public static function addRoutes($config)
    {

        $model_controller = $config['model_name'] . "Controller";
        $new_routes = "#routes#\n";
        $new_routes .= "\tRoute::get('" . $config['name'] . "/index', '" . $model_controller . "@index');\n";
        $new_routes .= "\tRoute::get('" . $config['name'] . "/create', '" . $model_controller . "@create');\n";
        $new_routes .= "\tRoute::post('" . $config['name'] . "/store', '" . $model_controller . "@store');\n";
        $new_routes .= "\tRoute::get('" . $config['name'] . "/{id}/edit', '" . $model_controller . "@edit');\n";
        $new_routes .= "\tRoute::post('" . $config['name'] . "/update', '" . $model_controller . "@update');\n";
        $new_routes .= "\tRoute::post('" . $config['name'] . "/delete', '" . $model_controller . "@delete');\n";

        $route_content = self::getContent();
        $route_content->replace('#routes#', $new_routes);
        $route_content->save();
    }
}
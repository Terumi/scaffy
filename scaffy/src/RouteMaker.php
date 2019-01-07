<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class RouteMaker
{

    public static function clear_old_routes(){
        $route_contents = Storage::disk('routes')->get('web.php');
        $route_contents = ScaffyAssistant::delete_all_between('# Scaffy_Routes_Start', '# Scaffy_Routes_End', $route_contents);
        $routes_appended = Storage::disk('stubs')->get('routes.stub');
        Storage::disk('routes')->put('web.php', $route_contents.$routes_appended);
    }

    public static function addRoutes($config)
    {
        $route_contents = Storage::disk('routes')->get('web.php');
        $model_controller = $config['model_name']."Controller";
        $new_routes = "#routes#\n";
        $new_routes .= "Route::get('".$config['name']."/index', '".$model_controller."@index');\n";
        $new_routes .= "Route::get('".$config['name']."/create', '".$model_controller."@create');\n";
        $new_routes .= "Route::post('".$config['name']."/store', '".$model_controller."@store');\n";
        $new_routes .= "Route::get('".$config['name']."/{id}/edit', '".$model_controller."@edit');\n";
        $new_routes .= "Route::post('".$config['name']."/update', '".$model_controller."@update');\n";
        $new_routes .= "Route::post('".$config['name']."/delete', '".$model_controller."@delete');\n";


        $route_contents = str_replace('#routes#', $new_routes, $route_contents);
        Storage::disk('routes')->put('web.php', $route_contents);
    }



}
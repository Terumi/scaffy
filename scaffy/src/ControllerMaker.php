<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class ControllerMaker
{

    public static function create_controller_files($config)
    {

        $path = base_path('scaffy/stubs/controller.stub');
        $contents = file_get_contents($path);

        $model_name = $config['model_name'];
        $key = $config['name'];

        $contents = str_replace('Dummy', $key, $contents);
        $contents = str_replace('model_name', strtolower($model_name), $contents);
        $contents = str_replace('Model', $model_name, $contents);

        $file_name = "{$model_name}Controller.php";
        Storage::disk('controllers')->put($file_name, $contents);

        self::set_controller_methods(strtolower($model_name), $config['migration'], $file_name);
    }


    protected static function set_controller_methods($model_name, $item, $file_name)
    {
        $create_stub = "";
        foreach ($item['fields'] as $field_key => $field) {

            $create_stub .= '$' . $model_name . '->' . $field_key . ' = $req->get(\'' . $field_key . '\');' . "\n";

            //add relations
            //if (!empty($field['references'])) {
            //    $table_stub .= '$table->foreign(\'' . $field_key . '\')->references(\'' . $field['references']['column'] . "')->on('" . $field['references']['on'] . "');\n";
            // }
        }

        $contents = Storage::disk('controllers')->get($file_name);
        $contents = str_replace('#model_fields#', $create_stub, $contents);
        Storage::disk('controllers')->put($file_name, $contents);
    }

    /* public function create_migration_file($key)
     {
         $file_name = $this->create_migration_file($key);

         //add fields to migration
         $this->add_fields_to_migration_file($config['migrations'][$key], $file_name);

     }*/

}
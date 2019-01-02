<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class ControllerMaker
{

    public function create_controller_files($migration, $key)
    {

        $path = base_path('scaffy/stubs/controller.stub');
        $contents = file_get_contents($path);

        $model = ScaffyAssistant::makeModelName($key);

        $contents = str_replace('DummyClass', $key . 'Controller', $contents);
        $contents = str_replace('model_name', strtolower($model), $contents);
        $contents = str_replace('Model', $model, $contents);

        $file_name = "{$model}Controller.php";
        Storage::disk('controllers')->put($file_name, $contents);

        $this->set_controller_methods(strtolower($model), $migration, $file_name);
    }


    private function set_controller_methods($model_name, $item, $file_name)
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
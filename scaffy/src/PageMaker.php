<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class PageMaker
{

    public function create_index_file($migration, $key)
    {

        $path = base_path('scaffy/stubs/index_page.stub');
        $contents = file_get_contents($path);
        $key = implode('_', array_map('ucfirst', explode('_', $key)));

        //replace the class name
        $contents = str_replace('DummyClass', $key, $contents);
        //replace the table name
        $contents = str_replace('DummyTable', strtolower($key), $contents);

        $file_name = "{$timestamp}_{$key}.php";
        Storage::disk('migrations')->put($file_name, $contents);


        $this->add_fields_to_migration_file($migration, $file_name);

        //this is to ensure proper file name creation
        sleep(1);

    }

    public function create_creation_file(){
        $path = base_path('scaffy/stubs/create_page.stub');
        $contents = file_get_contents($path);
    }

    public function create_edit_file(){
        $path = base_path('scaffy/stubs/edit_page.stub');
        $contents = file_get_contents($path);
    }


    private function add_fields_to_migration_file($item, $file_name)
    {
        $table_stub = "";
        foreach ($item['fields'] as $field_key => $field) {

            $table_stub .= '$table->' . $field['type'] . "('" . $field_key . "')";

            //add constraints
            if (!empty($field['constraints'])) {
                foreach ($field['constraints'] as $constraint) {
                    switch ($constraint) {
                        case "unique":
                            $table_stub .= "->unique()";
                            break;
                    }
                }
            }
            //todo add modifiers
            $table_stub .= ";\n";

            //add relations
            if (!empty($field['references'])) {
                $table_stub .= '$table->foreign(\'' . $field_key . '\')->references(\'' . $field['references']['column'] . "')->on('" . $field['references']['on'] . "');\n";
            }
        }

        $contents = Storage::disk('migrations')->get($file_name);
        $contents = str_replace('#table_fields#', $table_stub, $contents);
        Storage::disk('migrations')->put($file_name, $contents);
    }

   /* public function create_migration_file($key)
    {
        $file_name = $this->create_migration_file($key);

        //add fields to migration
        $this->add_fields_to_migration_file($config['migrations'][$key], $file_name);

    }*/

}
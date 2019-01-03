<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class PageMaker
{

    public static function create_index_file($config)
    {
        $key = $config['name'];
        $path = base_path('scaffy/stubs/index_page.stub');
        $contents = file_get_contents($path);

        //replace the class name
        $contents = str_replace('#page_title#', strtolower($config['model_name']), $contents);

        $table_content_titles = "";
        $table_content_items = "";

        foreach ($config['pages']['index']['shown_fields'] as $key => $options) {

            $table_content_titles .= "<th>{$key}</th>\n";

            if (isset($options['reference'])) {
                $table_content_items .= "<td>{{\$" . strtolower($config['model_name']) . "->" . $options['reference'] . "}}</td>\n";
            } else {
                $table_content_items .= "<td>{{\$" . strtolower($config['model_name']) . "->" . $key . "}}</td>\n";
            }
        }

        $contents = str_replace('#table_content_titles#', $table_content_titles, $contents);

        $contents = str_replace('#table_content_items#', $table_content_items, $contents);
        $contents = str_replace('model', strtolower($config['model_name']), $contents);

        $file_name = strtolower($config['model_name']) . "_index.blade.php";
        Storage::disk('views')->put($file_name, $contents);
    }

    public function create_creation_file()
    {
        $path = base_path('scaffy/stubs/create_page.stub');
        $contents = file_get_contents($path);
    }

    public function create_edit_file()
    {
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

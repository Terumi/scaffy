<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class MigrationMaker
{

    public static function create_migration_file($config)
    {
        $migration = $config['migration'];
        $timestamp = date('Y_m_d_His');
        $path = base_path('scaffy/stubs/migration.stub');
        $contents = file_get_contents($path);
        $key = implode('', array_map('ucfirst', explode('_', $config['name'])));

        //replace the class name
        $contents = str_replace('DummyClass', $key, $contents);
        //replace the table name
        $contents = str_replace('DummyTable', strtolower($config['name']), $contents);

        $file_name = "{$timestamp}_{$key}_Migration.php";
        Storage::disk('migrations')->put($file_name, $contents);
        self::add_fields_to_migration_file($migration, $file_name);
        //this is to ensure proper file name creation
        sleep(1);
    }

    protected static function add_fields_to_migration_file($item, $file_name)
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
<?php

namespace ffy\scaffy;

use Exception;
use Illuminate\Support\Facades\Storage;

class MigrationMaker
{
    public static function run()
    {
        //read the models
        $models = ScaffyAssistant::get_models();

        foreach ($models as $model) {
            echo $model;

            $content = new Content('stubs/migration.stub');
            $model_config = ScaffyAssistant::get_model_config_file($model);

            $content->replace('DummyClassMigration', $model_config->model_name . "Migration");
            $content->replace('DummyTable', $model_config->table);

            //get model migration file
            try {
                $columns = Storage::disk('scaffy')->get('config/' . $model . '/migration.json');
            } catch (Exception $exception) {
                echo "model $model does not have a migration config file. \n";
                continue;
            }

            $migration_config = json_decode($columns);
            $inner_content = '';
            foreach ($migration_config->table->fields as $column) {
                $inner_content .= TemplateManager::migration($column);
                //attach it to a common text
            }
            $content->add($inner_content, '#table_fields#');

            // lookup for relations
            try {
                $relations = Storage::disk('scaffy')->get('config/' . $model . '/relations.json');
            } catch (Exception $exception) {
                //save the file
                $content->save($model . "Migration");
                echo "model $model does not have a relation file. \n";
                //and exit
                continue;
            }

            //check if something relates to this model

            //attach text to outer file


            $content->add('asd', '#table_fields#');
        }

    }

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
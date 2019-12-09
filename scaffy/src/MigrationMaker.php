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
            echo $model . ":\n";

            $content = new Content('stubs/migration.stub');
            self::add_names($model, $content);
            //get model migration file
            $columns = self::get_migration_columns($model);
            if (!$columns)
                continue; //the model does not have a migration file so, we are good to go.

            self::attach_columns($columns, $content);

            // get the model relation file
            $relations = self::get_relation_columns($model, $content);
            if (!$relations)
                continue; //the model does not have a relations file so, we are good to go.

            //attach relations
            self::attach_relations($model, $relations, $content);
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

    protected
    static function add_fields_to_migration_file($item, $file_name)
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

    /**
     * @param $model
     * @param Content $content
     */
    protected static function add_names($model, Content $content): void
    {
        $model_config = ScaffyAssistant::get_model_config_file($model);
        $content->replace('DummyClassMigration', $model_config->model_name . "Migration");
        $content->replace('DummyTable', $model_config->table);
    }

    /**
     * @param $model
     * @return string
     */
    protected static function get_migration_columns($model): string
    {
        try {
            $columns = Storage::disk('scaffy')->get('config/' . $model . '/migration.json');
        } catch (Exception $exception) {
            echo "model $model does not have a migration config file. \n";
            $columns = false;
        }
        return $columns;
    }

    /**
     * @param string $columns
     * @param Content $content
     */
    protected static function attach_columns(string $columns, Content $content): void
    {
        $inner_content = '';
        $migration_config = json_decode($columns);
        foreach ($migration_config->table->fields as $column) {
            //attach it
            $inner_content .= TemplateManager::migration($column);
        }
        $content->add($inner_content, '#table_fields#');
    }

    /**
     * @param $model
     * @param Content $content
     * @return string
     */
    protected static function get_relation_columns($model, Content $content): string
    {
        try {
            $relations = Storage::disk('scaffy')->get('config/' . $model . '/relations.json');
        } catch (Exception $exception) {
            //save the file
            $content->save($model . "Migration");
            echo "model $model does not have a relation file. \n";
            //and exit
            return false;
        }
        return $relations;
    }

    protected static function attach_relations(string $model, string $relations, Content $content)
    {
        $relations = json_decode($relations);

        $relation_content = '';
        foreach ($relations as $relation) {

            switch ($relation->type) {
                case "One to One":
                case "One to Many":
                    $relation_content .= TemplateManager::belongs_to_relation_in_migration($relation);
                    break;
                case "Many to Many":
                    // no relation columns needed in existing tables
                    // but we need a new table
                    self::new_pivot_table($relation);
                    break;
                //todo add more relations
            }
        }

        $content->add($relation_content, '#table_fields#');
        //save the migration file
        $content->save($model . "Migration");
    }

    private static function new_pivot_table($relation)
    {
        $content = new Content('');
        $content->body = TemplateManager::pivot_quick_table($relation);
        $content->save($relation->model_one . $relation->model_two . "Migration");
    }


    /*
    protected static function attach_relations(string $relations, Content $content)
    {
        $relations = json_decode($relations);

        $relation_content = '';
        foreach ($relations as $relation) {

            //echo "---------------";
            //$relation_fields = ScaffyAssistant::determine_relation_fields($relation);
            //if (count($relation_fields))
            //    $relation_content .= TemplateManager::relation_in_migration($relation_fields);

            $relation_content .= TemplateManager::relation_in_migration($relation_fields);
            die(var_dump($relation));
            die();

        }
        $content->add($relation_content, '#table_fields#');
    }

    public function create_migration_file($key)
     {
         $file_name = $this->create_migration_file($key);

         //add fields to migration
         $this->add_fields_to_migration_file($config['migrations'][$key], $file_name);

     }*/

}
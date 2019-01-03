<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class ModelMaker
{


    public static function create_model_file($config)
    {
        $path = base_path('scaffy/stubs/model.stub');
        $contents = file_get_contents($path);

        $key = $config['name'];
        $modelName = $config['model_name'];

        //replace the class name
        $contents = str_replace('DummyClass', $modelName, $contents);
        //replace the table name
        $contents = str_replace('TableName', strtolower($key), $contents);

        $file_name = $modelName . ".php";
        Storage::disk('models')->put($file_name, $contents);

        self::add_model_relations($config['migration'], $file_name);
    }

    protected static function add_model_relations($migration, string $model_file_name): void
    {
        $model_stub = '';
        foreach ($migration['fields'] as $field_key => $field) {

            //add relations
            if (!empty($field['references'])) {
                switch ($field['references']['relation_type']) {
                    case '1-1':
                        $model_stub .= 'public function ' . $field['references']['relationName'] . '(){return $this->hasOne(\'App\\' . ScaffyAssistant::makeModelName($field['references']['on']) . '\', \''. $field['references']['column'].'\', \''. $field_key .'\');}';
                        break;
                    case '1-N':

                        break;
                    case 'N-N':

                        break;
                }
            }
        }

        $contents = Storage::disk('models')->get($model_file_name);
        $contents = str_replace('#relations#', $model_stub, $contents);
        Storage::disk('models')->put($model_file_name, $contents);
    }


}
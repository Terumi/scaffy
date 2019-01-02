<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class ModelMaker
{


    public function create_model_file($migration , $key)
    {
        $path = base_path('scaffy/stubs/model.stub');
        $contents = file_get_contents($path);

        $key = ScaffyAssistant::makeModelName($key);

        //replace the class name
        $contents = str_replace('DummyClass', $key, $contents);
        //replace the table name
        $contents = str_replace('TableName', strtolower($key), $contents);

        $file_name = $key . ".php";
        Storage::disk('models')->put($file_name, $contents);

        $this->add_model_relations($migration, $file_name);

    }

    public function add_model_relations($migration, string $model_file_name): void
    {
        $model_stub = '';
        foreach ($migration['fields'] as $field_key => $field) {

            //add relations
            if (!empty($field['references'])) {
                switch ($field['references']['relation_type']) {
                    case '1-1':
                        $model_stub .= 'public function ' . $field['references']['relationName'] . '(){return $this->hasOne(\'App\\' . ScaffyAssistant::makeModelName($field['references']['on']) . '\');}';
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
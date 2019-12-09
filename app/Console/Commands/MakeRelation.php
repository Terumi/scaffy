<?php

namespace App\Console\Commands;

use ffy\scaffy\ScaffyAssistant;
use Illuminate\Support\Facades\Storage;

class MakeRelation extends ScaffyCommand
{
    protected $signature = 'scaffy:relation';
    protected $description = 'Creates all the necessary things for migrations';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        // which model...
        // relates to which one
        $model_one = $this->show_model_options('Which model');
        $model_two = $this->show_model_options('Relates to which model');
        // get table names
        $table_one = ScaffyAssistant::get_model_config_file($model_one)->table;
        $table_two = ScaffyAssistant::get_model_config_file($model_two)->table;

        // what is the relation type
        $relation_type = $this->choice('What is the relation type?', ScaffyAssistant::getRelationTypes());
        switch ($relation_type) {
            case 'One to One':
                $relation_type_name = "belongsTo";
                break;
            case 'One to Many':
                $relation_type_name = "belongsTo";
                break;
            case 'Many to Many':
                $relation_type_name = "belongsToMany";
                break;
            case 'Has One Through':
                //todo
                break;
            case 'Has Many Through':
                //todo
                break;
        }


        switch ($relation_type) {
            case "One to One":
            case "One to Many":
                $localKey = $this->ask("How to write the foreign key on the $table_two table");
                $foreignKey = $this->ask("What is the column on $table_one table this refers to");

                $contents['relates_to'] = $model_one;
                $contents['relates_to_table_name'] = $table_one;
                $contents['key_written_on_current_table'] = $localKey;
                $contents['key_on_original_table'] = $foreignKey;
                $contents['type'] = $relation_type;
                $file = "config/$model_two/relations.json";

                $this->save_file($file, $contents);

                break;
            case "Many to Many":
                //return $this->belongsToMany('App\Role', 'role_user', 'user_id', 'role_id');
                $pivot = $this->ask("What name is the pivot table going to have");
                $key_one = $this->ask("Which is the key of the $table_one table");
                $pivot_key_one = $this->ask("How is going to be referenced in the pivot table");
                $key_two = $this->ask("What is the key of the $table_two table");
                $pivot_key_two = $this->ask("How is going to be referenced in the pivot table");
                $contents['model_one'] = $model_one;
                $contents['model_two'] = $model_two;
                $contents['table_one'] = $table_one;
                $contents['table_two'] = $table_two;
                $contents['pivot'] = $pivot;
                $contents['key_one'] = $key_one;
                $contents['key_two'] = $key_two;
                $contents['pivot_key_one'] = $pivot_key_one;
                $contents['pivot_key_two'] = $pivot_key_two;
                $contents['type'] = $relation_type;

                $file = "config/$model_one/relations.json";
                $this->save_file($file, $contents);
                $file = "config/$model_two/relations.json";
                $this->save_file($file, $contents);

                break;
        }

    }

    protected function save_file(string $file, array $contents): void
    {
        if (!Storage::disk('scaffy')->exists($file)) {
            $asd[] = $contents;
            Storage::disk('scaffy')->put($file, json_encode($asd));
        } else {

            $existing_contents = json_decode(Storage::disk('scaffy')->get($file), true);

            if (is_array($existing_contents)) {
                foreach ($existing_contents as $existing_content) {
                    $new_contents[] = $existing_content;
                }
            } else {
                if (!is_null($existing_contents))
                    $new_contents[] = $existing_contents;
            }

            $new_contents[] = $contents;

            Storage::disk('scaffy')->put($file, json_encode($new_contents));
        }
    }
}

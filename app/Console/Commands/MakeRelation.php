<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;

class MakeRelation extends ScaffyCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffy:relation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates all the necessary things for migrations';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        //which model...
        $model_one = $this->show_model_options('Which model');
        //relates to which one?
        $model_two = $this->show_model_options('Relates to which model');

        $file_one = "config/$model_one/relations.json";
        $file_two = "config/$model_two/relations.json";

        $contents['relates_to'] = $model_two;
        $invert_contents['relates_to'] = $model_one;

        //type of relation
        $relation_types = [
            'One to One', 'One to Many', 'Many to Many', 'Has One Through', 'Has Many Through'
        ];

        $relation_type = $this->choice('Type?', $relation_types);


        switch ($relation_type) {
            case 'One to One':
                $this->set_keys($contents, $invert_contents);
                $contents['relationType'] = $relation_type;
                $invert_contents['relationType'] = 'belongsTo';

                break;
            case 'One to Many':
                $this->set_keys($contents, $invert_contents);
                $contents['relationType'] = $relation_type;
                $invert_contents['relationType'] = 'belongsTo';

                break;
            case 'Many to Many':
                $contents['pivotTable'] = $this->ask('Pivot Table');
                $this->set_keys($contents, $invert_contents);
                $contents['relationType'] = $relation_type;
                $invert_contents['relationType'] = 'belongsToMany';

                break;
            case 'Has One Through':
                //todo
                break;
            case 'Has Many Through':
                //todo
                break;
        }
        $this->save_file($file_one, $contents);
        $this->save_file($file_two, $invert_contents);
    }

    protected function save_file(string $file, array $contents): void
    {
        if (!Storage::disk('scaffy')->exists($file)) {
            Storage::disk('scaffy')->put($file, json_encode($contents));
        } else {
            $existing_contents[] = json_decode(Storage::disk('scaffy')->get($file), true);
            $existing_contents[] = $contents;

            Storage::disk('scaffy')->put($file, json_encode($existing_contents));
        }
    }

    /**
     * @param $contents
     * @param $invert_contents
     */
    protected function set_keys(&$contents, &$invert_contents): void
    {
        $contents['localKey'] = $this->ask('First Model ID');
        $contents['foreignKey'] = $this->ask('Second Model ID');
        $invert_contents['localKey'] = $contents['foreignKey'];
        $invert_contents['foreignKey'] = $contents['localKey'];
    }
}

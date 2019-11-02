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

        //type of relation
        $relation_types = [
            'One to One', 'One to Many', 'Many to Many', 'Has One Through', 'Has Many Through'
        ];

        $relation_type = $this->choice('Type?', $relation_types);
        $contents = ['relates_to' => $model_two, 'relationType' => $relation_type];
        $file = "config/$model_one/relations.json";

        switch ($relation_type) {
            case 'One to One':
                $contents['localKey'] = $this->ask('Local Key');
                $contents['foreignKey'] = $this->ask('Foreign Key');
                break;
            case 'One to Many':
                $contents['localKey'] = $this->ask('Local Key');
                $contents['foreignKey'] = $this->ask('Foreign Key');
                break;
            case 'Many to Many':
                $contents['pivotTable'] = $this->ask('Pivot Table');
                $contents['localKey'] = $this->ask('First Model ID');
                $contents['foreignKey'] = $this->ask('Second Model ID');
                break;
            case 'Has One Through':
                //todo
                break;

            case 'Has Many Through':
                //todo
                break;
        }

        if (!Storage::disk('scaffy')->exists($file)) {
            Storage::disk('scaffy')->put($file, json_encode($contents));
        } else {
            $existing_contents = json_decode(Storage::disk('scaffy')->get($file), true);
            $existing_contents[] = $contents;

            Storage::disk('scaffy')->put($file, json_encode($existing_contents));
        }

        die("done \n");
    }
}

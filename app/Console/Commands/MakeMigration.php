<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MakeMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scaffy:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a migration';

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
        $contents = '';
        $file_names = Storage::disk('scaffy')->files('models');
        $data_types = [
            'increments',
            'integer',
            'string',
            'text',
            'datetime'
        ];

        array_walk($file_names, [$this, 'extract_name']);

        $fields = [];
         

        $question = "For which model?";
        foreach ($file_names as $index => $name) {
            $question .= "\n" . $index . ". " . $name;
        }

        $model = $this->choice('For which model?', $file_names);

        $name = $model;
        $file = "migrations/$name.json";

        $ask_more = true;
        while ($ask_more) {

            $add = $this->confirm('Add field?', true);

            if ($add) {
                $field_name = $this->ask('Name');
                $field_type = $this->choice('Type?', $data_types);

                $contents .= "\$table->$field_type('$field_name')";

            } else {
                $ask_more = false;
            }
        }



        Storage::disk('scaffy')->put($file, $contents);
        die('ok');

    }


    /*
     * "fields": {
      "id": {
        "type": "increments"
      },
      "name": {
        "type": "string"
      },
      "telephone": {
        "type": "string"
      },
      "email": {
        "type": "string"
      }
    }
     */

    private function extract_name(String &$victim)
    {
        $victim = str_replace('models/', '', $victim);
        $victim = str_replace('.json', '', $victim);
    }

}

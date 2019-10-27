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
        $contents = ["fields" => []];

        $data_types = [
            'increments',
            'integer',
            'string',
            'text',
            'datetime'
        ];

        $file_names = Storage::disk('scaffy')->files('models');
        array_walk($file_names, [$this, 'extract_name']);

        if (!count($file_names))
            die("no models\nexiting...\n");

        $model = $this->choice('For which model?', $file_names);

        $name = $model;
        $file = "migrations/$name.json";

        $ask_more = true;
        while ($ask_more) {
            $ask_more = $this->confirm('Add field?', true);
            if ($ask_more) {
                $field_name = $this->ask('Name');
                $field_type = $this->choice('Type?', $data_types);
                $contents['fields'][] = ["name" => $field_name, "type" => $field_type];
            }
        }

        Storage::disk('scaffy')->put($file, json_encode($contents));
        die('ok');
    }

    private function extract_name(String &$victim)
    {
        $victim = str_replace('models/', '', $victim);
        $victim = str_replace('.json', '', $victim);
    }

}

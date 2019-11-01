<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Storage;

class MakeMigration extends ScaffyCommand
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
        $data_types = [
            'increments',
            'integer',
            'string',
            'text',
            'datetime'
        ];

        $model = $this->show_model_options();

        $name = $model;
        $file = "config/$name/migration.json";

        $ask_more = true;
        while ($ask_more) {
            $ask_more = $this->confirm('Add field?', true);
            if ($ask_more) {
                $field_name = $this->ask('Name');
                $field_type = $this->choice('Type?', $data_types);
                $contents['table']['fields'][] = ["name" => $field_name, "type" => $field_type];
            }
        }

        Storage::disk('scaffy')->put($file, json_encode($contents));
        die("ok\n");
    }
}

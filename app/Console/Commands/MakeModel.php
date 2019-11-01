<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class MakeModel extends Command
{

    protected $signature = 'scaffy:model';
    protected $description = 'Displays scaffy model options';

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
        $name = $this->ask('Model Name');
        //$title = $this->ask('Model Title');
        $table = $this->ask('Table name');

        $content = [
            "model_name" => $name,
            //"title" => $title,
            "table" => $table
        ];

        Storage::disk('scaffy')->put("config/" . $name . "/model.json", json_encode($content));
    }
}

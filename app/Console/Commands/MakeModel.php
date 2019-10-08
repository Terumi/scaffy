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
        $name = $this->ask('What is the model name?');
        $title = $this->ask('What is the model title?');

        $content = [
            "model_name" => $name,
            "title" => $title
        ];

        Storage::disk('scaffy')->put("models/" . $name . ".json", json_encode($content));
    }
}

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
        $file_names = Storage::disk('scaffy')->files('models');
        array_walk($file_names, [$this, 'extract_name']);

        $question = "For which model?";
        foreach ($file_names as $index => $name) {
            $question .= "\n" . $index . ". " . $name;
        }

        $model = -1;
        while (empty($file_names[$model])) {
            $model = $this->ask($question);
        }

        $name = $file_names[$model];
        //todo make content

        Storage::disk('scaffy')->put("migrations/" . $name . ".json", "something");

    }

    private function extract_name(String &$victim)
    {
        $victim = str_replace('models/', '', $victim);
        $victim = str_replace('.json', '', $victim);
    }

}

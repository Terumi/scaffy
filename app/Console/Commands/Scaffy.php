<?php

namespace App\Console\Commands;

use ffy\scaffy\ControllerMaker;
use ffy\scaffy\MigrationMaker;
use ffy\scaffy\ModelMaker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Scaffy extends Command
{
    protected $signature = 'scaffy:run';
    protected $description = 'shows what is about to get executed';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $path = base_path('scaffy/config.json');
        $config = json_decode(file_get_contents($path), true);

        $no_of_tables = count($config['migrations']);
        $this->info("got to create migrations for $no_of_tables tables");


        foreach ($config['migrations'] as $key => $migration) {
            $this->info("creating {$key} migration file");

            $this->info("creating {$key} migration files");
            $mima = new MigrationMaker();
            $mima->create_migration_file($migration, $key);

            $this->info("creating {$key} models");
            $moma = new ModelMaker();
            $moma->create_model_file($migration, $key);

            $this->info("creating {$key} controller");
            $coma = new ControllerMaker();
            $coma->create_controller_files($migration, $key);




        }

    }




}

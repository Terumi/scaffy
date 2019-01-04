<?php

namespace App\Console\Commands;

use ffy\scaffy\ControllerMaker;
use ffy\scaffy\MigrationMaker;
use ffy\scaffy\ModelMaker;
use ffy\scaffy\PageMaker;
use ffy\scaffy\RouteMaker;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class Scaffy extends Command
{
    protected $signature = 'scaffy:run';
    protected $description = 'Runs scaffolding procedure';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {

        $files = Storage::disk('config')->files();

        RouteMaker::clear_old_routes();

        foreach ($files as $file) {
            $config = json_decode(Storage::disk('config')->get($file), true);

            //MigrationMaker::create_migration_file($config);
            ModelMaker::create_model_file($config);
            ControllerMaker::create_controller_files($config);
            PageMaker::create_index_file($config);
            RouteMaker::addRoutes($config);
        }



    }


}

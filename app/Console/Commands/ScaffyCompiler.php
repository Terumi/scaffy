<?php

namespace App\Console\Commands;

use ffy\scaffy\ModelMaker;
use Illuminate\Console\Command;

class ScaffyCompiler extends Command
{
/**
 * The name and signature of the console command.
 *
 * @var string
 */
protected $signature = 'scaffy:compile';

/**
 * The console command description.
 *
 * @var string
 */
protected $description = 'Creates the scaffolding files';

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
    ModelMaker::run();
}
}

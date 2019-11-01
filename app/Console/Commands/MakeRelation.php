<?php

namespace App\Console\Commands;

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

        //return $this->hasOne('App\Phone', 'foreign_key', 'local_key');

        //id of first model
        //id of second one

        //add to model
        //add to migration
    }
}

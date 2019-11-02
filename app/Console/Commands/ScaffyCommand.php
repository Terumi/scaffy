<?php

namespace App\Console\Commands;

use ffy\scaffy\ScaffyAssistant;
use Illuminate\Console\Command;

abstract class ScaffyCommand extends Command
{

    /**
     * @param string $question
     * @return string
     */
    protected function show_model_options($question = "Select Model"): string
    {
        $file_names = ScaffyAssistant::get_models();
        $file_names[] = "Cancel";

        if (!count($file_names))
            die("no models\nexiting...\n");

        $model = $this->choice($question, $file_names);

        if ($model == 'Cancel')
            die("Bye!\n");

        return $model;
    }
}
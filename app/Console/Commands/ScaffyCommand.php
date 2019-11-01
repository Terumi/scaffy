<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

abstract class ScaffyCommand extends Command
{

    /**
     * @return array
     */
    protected function get_models(): array
    {
        $file_names = Storage::disk('scaffy')->directories('config');
        array_walk($file_names, [$this, 'extract_name']);
        return $file_names;
    }

    /**
     * @param string $question
     * @return string
     */
    protected function show_model_options($question = "Select Model"): string
    {
        $file_names = $this->get_models();
        $file_names[] = "Cancel";

        if (!count($file_names))
            die("no models\nexiting...\n");

        $model = $this->choice($question, $file_names);

        if ($model == 'Cancel')
            die("Bye!\n");

        return $model;
    }

    protected function extract_name(String &$victim)
    {
        $victim = str_replace('config/', '', $victim);
    }
}
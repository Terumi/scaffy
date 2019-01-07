<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class PageMaker
{

    public static function create_index_file($config)
    {
        $path = base_path('scaffy/stubs/index_page.stub');
        $contents = file_get_contents($path);

        //replace the class name
        $contents = str_replace('#page_title#', strtolower($config['model_name']), $contents);

        $table_content_titles = "";
        $table_content_items = "";

        foreach ($config['pages']['index']['shown_fields'] as $key => $options) {

            $table_content_titles .= "<th>{$key}</th>\n";

            if (isset($options['reference'])) {
                $table_content_items .= "<td>{{\$" . strtolower($config['model_name']) . "->" . $options['reference'] . "}}</td>\n";
            } else {
                $table_content_items .= "<td>{{\$" . strtolower($config['model_name']) . "->" . $key . "}}</td>\n";
            }
        }

        $contents = str_replace('#table_content_titles#', $table_content_titles, $contents);

        $contents = str_replace('#table_content_items#', $table_content_items, $contents);
        $contents = str_replace('model', strtolower($config['model_name']), $contents);

        $file_name = strtolower($config['model_name']) . "_index.blade.php";
        Storage::disk('views')->put($file_name, $contents);
    }

    public static function create_creation_file($config)
    {
        $contents = Storage::disk('stubs')->get('create_page.stub');
        $form_contents = "";
        foreach ($config['pages']['create']['shown_fields'] as $key => $options) {

            $form_control = "";
            switch ($options['type']) {
                case 'text':
                    $form_control = Storage::disk('stubs')->get('form_partials/text.stub');
                    $form_control = str_replace('#name#', $key, $form_control);
                    if (isset($options['placeholder']))
                        $form_control = str_replace('#placeholder#', $options['placeholder'], $form_control);
                    if (isset($options['smalltext']))
                        $form_control = str_replace('#smalltext#', $options['smalltext'], $form_control);

                    break;
                case 'email':
                    $form_control = Storage::disk('stubs')->get('form_partials/email.stub');
                    $form_control = str_replace('#name#', $key, $form_control);
                    if (isset($options['placeholder']))
                        $form_control = str_replace('#placeholder#', $options['placeholder'], $form_control);
                    if (isset($options['smalltext']))
                        $form_control = str_replace('#smalltext#', $options['smalltext'], $form_control);

                    break;
                case 'textarea':
                    $form_control = Storage::disk('stubs')->get('form_partials/textarea.stub');
                    $form_control = str_replace('#name#', $key, $form_control);
                    if (isset($options['placeholder']))
                        $form_control = str_replace('#placeholder#', $options['placeholder'], $form_control);
                    if (isset($options['smalltext']))
                        $form_control = str_replace('#smalltext#', $options['smalltext'], $form_control);
                    break;
                case 'file':
                    $form_control = Storage::disk('stubs')->get('form_partials/file.stub');

                    break;
                case 'dropdown':
                    $form_control = Storage::disk('stubs')->get('form_partials/dropdown.stub');
                    $form_control = str_replace('#name#', $key, $form_control);
                    if (isset($options['related_items']))
                        $form_control = str_replace('related_items', $options['related_items'], $form_control);
                    if (isset($options['related_item']))
                        $form_control = str_replace('single_item', $options['related_item'], $form_control);
                    if (isset($options['related_value']))
                        $form_control = str_replace('related_value', $options['related_value'], $form_control);
                    if (isset($options['related_display_value']))
                        $form_control = str_replace('related_display_value', $options['related_display_value'], $form_control);
                    if (isset($options['smalltext']))
                        $form_control = str_replace('#smalltext#', $options['smalltext'], $form_control);
                    break;
                case 'checkbox':
                    $form_control = Storage::disk('stubs')->get('form_partials/checkbox.stub');

                    break;

            }
            $form_contents .= $form_control;
        }

        $key = $config['model_name'];
        $contents = str_replace('#form#', $form_contents, $contents);
        $contents = str_replace('#url#', strtolower($key)."/store", $contents);
        Storage::disk('views')->put(strtolower($key) . "_create.blade.php", $contents);
    }

    public static function create_edit_file($config)
    {
        $key = $config['model_name'];
        $contents = Storage::disk('stubs')->get('edit_page.stub');
        Storage::disk('views')->put(strtolower($key) . "_edit.blade.php", $contents);
    }
}

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
        $key = $config['model_name'];
        $contents = Storage::disk('stubs')->get('create_page.stub');
        Storage::disk('views')->put(strtolower($key)."_create.blade.php", $contents);
    }

    public static function create_edit_file($config)
    {
        $key = $config['model_name'];
        $contents = Storage::disk('stubs')->get('edit_page.stub');
        Storage::disk('views')->put(strtolower($key)."_edit.blade.php", $contents);
    }
}

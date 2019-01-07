<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class ControllerMaker
{

    public static function create_controller_files($config)
    {

        $path = base_path('scaffy/stubs/controller.stub');
        $contents = file_get_contents($path);

        $model_name = $config['model_name'];

        $contents = str_replace('Dummy', $model_name, $contents);
        $contents = str_replace('model_name', strtolower($model_name), $contents);
        $contents = str_replace('Model', $model_name, $contents);

        $file_name = "{$model_name}Controller.php";
        Storage::disk('controllers')->put($file_name, $contents);

        self::set_controller_methods(strtolower($model_name), $config['migration'], $file_name);
    }


    protected static function set_controller_methods($model_name, $item, $file_name)
    {
        $contents = Storage::disk('controllers')->get($file_name);

        $relations = "";
        $imports = "";
        $withs = "";

        foreach ($item['fields'] as $field_key => $field) {

            if (!empty($field['references'])) {
                $imports .= self::add_imports($field);
                $relations .= self::add_relations($field);
                $withs .= self::add_withs($field);
            }
        }

        if (!empty($imports))
            $contents = str_replace("#imports#", $imports, $contents);
        if (!empty($relations))
            $contents = str_replace("#relations#", $relations, $contents);

        $contents = str_replace(";#withs#", empty($withs) ? ";" : $withs.";", $contents);
        $contents = str_replace('#model_fields#', self::create_method($model_name, $item), $contents);

        Storage::disk('controllers')->put($file_name, $contents);
    }

    private static function create_method($model_name, $item)
    {
        $create_stub = "";
        foreach ($item['fields'] as $field_key => $field) {
            $create_stub .= '$' . $model_name . '->' . $field_key . ' = $req->get(\'' . $field_key . '\');' . "\n";
        }

        return $create_stub;
    }

    private static function add_imports($field)
    {
        return "use App\\".$field['references']['relationName'].";\n";
    }

    private static function add_relations($field)
    {
        return "$" . $field['references']['variableName'] . "= " . $field['references']['relationName'] . "::all();";
    }

    private static function add_withs($field)
    {
        return "->with(['" . $field['references']['variableName'] . "' => \${$field['references']['variableName']}])";
    }
}
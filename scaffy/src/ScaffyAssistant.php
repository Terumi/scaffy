<?php

namespace ffy\scaffy;

class ScaffyAssistant
{
    public static function makeModelName($key)
    {
        return implode('', array_map('ucfirst', explode('_', $key)));
    }

    public static function makeLowerCaseModelName($key)
    {
        return implode('', array_map('strtolower', explode('_', $key)));
    }

    public static function delete_all_between($beginning, $end, $string)
    {
        $beginningPos = strpos($string, $beginning);
        $endPos = strpos($string, $end);
        if ($beginningPos === false || $endPos === false) {
            return $string;
        }
        $textToDelete = substr($string, $beginningPos, ($endPos + strlen($end)) - $beginningPos);

        return self::delete_all_between($beginning, $end, str_replace($textToDelete, '', $string));
    }

}
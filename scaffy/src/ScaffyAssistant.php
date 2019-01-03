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
}
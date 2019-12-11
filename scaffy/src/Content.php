<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class Content
{

    public $body;
    private $disk;
    private $file;
    private $stub;


    public function __construct($stub, $disk = 'scaffy')
    {
        $this->body = $stub == '' ? '' : Storage::disk($disk)->get($stub);
    }

    public function replace(string $what, string $with)
    {
        $this->body = str_replace($what, $with, $this->body);
    }

    public function append(string $what)
    {
        $this->body .= $what;
    }

    public function add(string $what, string $after)
    {
        $this->body = str_replace($after, $what . "\n $after", $this->body);
    }

    public function deleteBetween($beginning, $end)
    {
        $beginningPos = strpos($this->body, $beginning);
        $endPos = strpos($this->body, $end);

        if ($beginningPos === false || $endPos === false) {
            return $this->body;
        }
        $textToDelete = substr($this->body, $beginningPos, ($endPos + strlen($end)) - $beginningPos);
        $this->body = str_replace($textToDelete, '', $this->body);
    }

    public function initialize()
    {
        $this->deleteBetween('###start', '###end');
        return $this;
    }

    public function save($name)
    {
        Storage::disk('scaffy')->put("$name.php", $this->body);
    }

    public function __toString()
    {
        return $this->body;
    }
}
<?php

namespace ffy\scaffy;

use Illuminate\Support\Facades\Storage;

class Content
{

    public $body;
    private $disk;
    private $file;
    private $stub;


    public function __construct($disk, $file, $stub = null)
    {
        $this->disk = $disk;
        $this->file = $file;
        $this->stub = $stub;
        $this->body = Storage::disk($disk)->get($file);
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
        $to_be_appeded = is_null($this->stub) ? '' : Storage::disk('stubs')->get($this->stub);
        Storage::disk($this->disk)->put($this->file, $this->body . $to_be_appeded);
    }

    public function replace(string $what, string $with)
    {
        $this->body = str_replace($what, $with, $this->body);
    }

    public function save(){
        Storage::disk($this->disk)->put($this->file, $this->body);
    }


}
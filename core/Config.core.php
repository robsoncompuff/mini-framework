<?php

class Config{

    private $path = __DIR__."/../config/";

    public function __construct($label){

    }

    protected function getConfig($label){
        $fileContent = file_get_contents($this->path.$label.".json");
        return json_decode($fileContent, true);
    }
}

?>
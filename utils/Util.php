<?php
class Util{

    public function __construct(){

    }

    public function traceDie($var){
        echo '<pre>';
        print_r($var);
        echo '</pre>';
        die();
    }

    public function trace($var){
        echo '<pre>';
        print_r($var);
        echo '</pre>';
    }
}

?>



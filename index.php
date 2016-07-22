<?php

//Verify ambient and define global variable
if(substr_count('www.',$_SERVER['HTTP_HOST'])){
    $ambient = 'production';
}else{
    $ambient = 'development';
}

//Load Util classes
require_once __DIR__.'/utils/Util.php';
$Util = new Util();

//Load core classes of the framework
require_once __DIR__ . '/core/Config.core.php';
require_once __DIR__ . '/core/Database.core.php';
require_once __DIR__ . '/core/Framework.core.php';
require_once __DIR__ . '/core/Session.core.php';
require_once __DIR__ . '/core/Url.core.php';
require_once __DIR__ . '/core/Controller.core.php';

// Init session
$Session = Session::getInstance();
$Database = new Database();

//Load model classes
require_once __DIR__ . '/app/models/Call.php';
require_once __DIR__ . '/app/models/Region.php';


//Load Dbclasses
require_once __DIR__ . '/app/dbs/DbCall.php';
require_once __DIR__ . '/app/dbs/DbRegion.php';

//Configure the level of error that is displayed according to the ambient
$Framework = new Framework($ambient);
if($Framework->getReportingError()){
    ini_set('error_reporting',E_ALL);
}

if($Framework->getDisplayError()){
    ini_set('display_errors','On');
}

//Determines whether request is for a static file or controller
if(substr_count($_SERVER['REQUEST_URI'],'/assets/')){

    //Delivery static files
    $explodedUrl = explode('?',$_SERVER['REQUEST_URI']);
    $url = $explodedUrl[0];
    if(substr_count($_SERVER['REQUEST_URI'],'/assets/css/')){
        header("Content-type: text/css", true);
        print_r(file_get_contents(__DIR__.$url));
    }elseif(substr_count($_SERVER['REQUEST_URI'],'/assets/js/')){
        header('Content-type: application/javascript', true);
        print_r(file_get_contents(__DIR__.$url));
    }elseif(substr_count($_SERVER['REQUEST_URI'],'/assets/svg/')){
        $imagePath = __DIR__.$url;
        $mimeType = mime_content_type($imagePath);
        header("Content-type: image/svg+xml");
        echo file_get_contents($imagePath);
        die();
    }elseif(substr_count($_SERVER['REQUEST_URI'],'/assets/imgs/')){
        $imagePath = __DIR__.$url;
        $mimeType = mime_content_type($imagePath);
        header("Content-type: {$mimeType}");
        echo file_get_contents($imagePath);
        die();
    }
}else{
    //Init URL class and choose a controller/action to execute
    $Url = new Url($_SERVER['REQUEST_URI'],$_REQUEST);
    $Controller = new Controller($Url,$Framework->getVersion());
}

?>

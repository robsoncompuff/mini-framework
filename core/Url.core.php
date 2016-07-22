<?php
class Url extends Config{

    //Private attributes
    private $uri;

    private $type;

    private $title;

    private $description;

    private $keywords;

    private $robots;

    private $controller;

    private $action;

    private $params;

    private $config;


    //Getter and Setters
    public function getUri(){
        return $this->uri;
    }

    public function setUri($uri){
        $this->uri = $uri;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getKeywords()
    {
        return $this->keywords;
    }

    public function setKeywords($keywords){
        $this->keywords = $keywords;
    }

    public function getRobots()
    {
        return $this->robots;
    }

    public function setRobots($robots)
    {
        $this->robots = $robots;
    }

    public function getController(){
        return $this->controller;
    }

    public function setController($controller){
        $controller = ucfirst(strtolower(trim($controller)));
        $this->controller = $controller;
    }

    public function getAction(){
        return $this->action;
    }

    public function setAction($action){
        $action = strtolower(trim($action));
        $this->action = $action;
    }

    public function getParams(){
        return $this->params;
    }

    public function setParams($params){
        $this->params = $params;
    }

    //On construct load url config and process
    public function __construct($uri,$queryStringData){
        $this->config = $this->getConfig('url');
        $this->process($uri, $queryStringData);
    }

    //process URL request
    private function process($requestURI, $queryString){
        $dynamicParams = array();
        if(!empty($requestURI)){
            $explodedURI = explode('?',$requestURI);
            $requestURI = $explodedURI[0];
            $explodedURI = explode('/',$requestURI);
            $uriLength = count($explodedURI);
            for($i = 0; $i < $uriLength; $i++){
                $requestURI = trim(strtolower(implode('/',$explodedURI)));
                if(!empty($this->config[$requestURI])){
                   $this->setUri($this->config[$requestURI]['uri']);
                   $this->setType($this->config[$requestURI]['type']);
                   $this->setTitle($this->config[$requestURI]['title']);
                   $this->setDescription($this->config[$requestURI]['description']);
                   $this->setKeywords($this->config[$requestURI]['keywords']);
                   $this->setRobots($this->config[$requestURI]['robots']);
                   $this->setController($this->config[$requestURI]['controller']);
                   $this->setAction($this->config[$requestURI]['action']);

                   $params = $this->config[$requestURI]['params'];
                   if(!empty($params['dynamic']) && !empty($dynamicParams)){
                       $i = 0;
                       $dynamicParams = array_reverse($dynamicParams);
                       foreach($params['dynamic'] as $idx => $param){
                           $params['dynamic'][$idx] = $dynamicParams[$i];
                           $i++;
                       }
                   }
                   break;
                }
                $dynamicParams[] = array_pop($explodedURI);
            }
            if(empty($this->getUri())){
                $this->setUri($this->config['/404']['uri']);
                $this->setType($this->config['/404']['type']);
                $this->setTitle($this->config['/404']['title']);
                $this->setDescription($this->config['/404']['description']);
                $this->setKeywords($this->config['/404']['keywords']);
                $this->setRobots($this->config['/404']['robots']);
                $this->setController($this->config['/404']['controller']);
                $this->setAction($this->config['/404']['action']);

            }else{
                $params['queryString'] = $queryString;
            }
            $this->setParams($params);
        }else{
            new Exception("The arg 'requestURI' can't be empty");
        }
    }
}
?>
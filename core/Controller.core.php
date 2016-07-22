<?php
class Controller{

    //constants
    const TYPE_AJAX = 'ajax';
    const TYPE_URL = 'url';

    //private attributes
    private $data;

    private $Url;

    protected $errors = false;

    //Getters and Setters
    public function getData(){
        return $this->data;
    }

    private function setData($data){
        $this->data = $data;
    }

    public function getUrl(){
        return $this->Url;
    }

    public function setUrl($Url){
        $this->Url = $Url;
    }

    public function getFeedback(){
        global $Session;
        $feedback = $Session->get('feedback');
        $Session->clean('feedback');
        return $feedback;
    }

    public function setFeedback($type,$msg){
        global $Session;
        $Session->set('feedback',array('type' => $type, 'msg' => $msg));
    }

    public function getErrors(){
        return $this->errors;
    }

    //Process params from URL and pass for controller/action
    public function __construct($Url,$version){
        $this->setUrl($Url);
        $this->setData($this->processParams($Url->getParams()));
        $this->exec($version);
    }

    private function processParams($params){
        $data = array();

        if(!empty($params['static'])){
            foreach($params['static'] as $idx => $static){
                $data[$idx] = $static;
            }
        }

        if(!empty($params['dynamic'])){
            foreach($params['dynamic'] as $idx => $dynamic){
                $data[$idx] = $dynamic;
            }
        }

        if(!empty($params['queryString'])){
            foreach($params['queryString'] as $idx => $queryString){
                $data[$idx] = $queryString;
            }
        }

        return $data;
    }

    //validations
    protected function validate($data, $attributeName, $validationType){
        if($validationType == 'isEmpty'){
            if(empty($data[$attributeName])){
                $this->errors[$attributeName][] = "O campo acima não pode ser vazio.";
             }
        }

        if(empty($this->validation)){
            return true;
        }else{
            return false;
        }
    }

    //Import Controller and call action for execute
    private function exec($version){
        $controllerPath = __DIR__ . '/../app/controllers/' .$this->getUrl()->getType().'/'.$this->getUrl()->getController().'Controller.php';

        if(file_exists($controllerPath)){
            require_once $controllerPath;

            if($this->getUrl()->getType() == Controller::TYPE_AJAX){
                //Todo process AJAX request
            }elseif($this->getUrl()->getType() == Controller::TYPE_URL){
                $className = $this->getUrl()->getController().'Controller';
                if (class_exists($className)) {
                    $Class = new $className;
                    $methodName = $this->getUrl()->getAction().'Action';
                    if(method_exists($Class,$methodName)){
                        $data = $this->getData();
                        $data['feedback'] = $this->getFeedback();
                        $result = $Class->$methodName($data);
                        if(!empty($result)){
                            $data = array_merge($result, $data);
                        }
                        if($data['errors'] = $Class->getErrors()){
                            $data['feedback'] = $this->getFeedback();
                            $result = $Class->formAction($data);
                            if(!empty($result)){
                                $data = array_merge($result, $data);
                            }
                            $templatePath = __DIR__ . '/../app/views/' .strtolower($this->getUrl()->getController()).'/FormView.php';
                        }else{
                            $templatePath = __DIR__ . '/../app/views/' .strtolower($this->getUrl()->getController()).'/'.ucfirst($this->getUrl()->getAction()).'View.php';
                        }
                        $headerTemplatePath = __DIR__ . '/../app/views/html/HeaderView.php';
                        $footerTemplatePath = __DIR__ . '/../app/views/html/FooterView.php';
                        if(file_exists($templatePath)){
                            require_once $headerTemplatePath;
                            require_once $templatePath;
                            require_once $footerTemplatePath;
                        }else{
                            throw new Exception("The view '{$this->getUrl()->getController()}View.php' does not exist. Please create view");
                        }
                    }else{
                        throw new Exception("The method '{$methodName}' does not exist on '{$className}'. Please create method");
                    }
                }else{
                    throw new Exception("The class '{$className}' does not exist. Please create class");
                }
            }
        }else{
            throw new Exception("The controller '{$this->getUrl()->getType()}"."/"."{$this->getUrl()->getController()}'Controller.php does not exist. Please create file");
        }
    }

    //Redirect
    protected function redirect($url, $httpCode = false){
        if(!empty($httpCode)){
            header("Location: {$url}", true, $httpCode);
        }else{
            header("Location: {$url}");
        }
    }
}
?>
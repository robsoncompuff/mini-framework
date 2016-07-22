<?php
class Call{

    //Constants
    const TYPE_PHONE = 'phone';
    const TYPE_EMAIL = 'email';
    const TYPE_CHAT = 'chat';

    const REASON_SUGGESTION = 'suggestion';
    const REASON_PRAISE = 'praise';
    const REASON_DOUBT = 'doubt';

    //Private Attributes
    private $id;

    private $Region;

    private $type;

    private $reason;

    private $details;

    private $created;

    private $Db;


    //Getters and Setters of the class
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getRegion(){
        return $this->Region;
    }

    public function setRegion($Region){
        if (!($Region instanceof Region)) {
            throw new InvalidArgumentException("The parameter isn't a Region instance");
        }
        $this->Region = $Region;
    }

    public function getType($translated = false){
        if($translated){
            switch ($this->type) {
                case 'email':
                    return 'Email';
                    break;
                case 'chat':
                    return 'Chat';
                    break;
                case 'phone':
                    return 'Telefone';
                    break;
            }
        }else{
            return $this->type;
        }
    }

    public function setType($type){
        if(in_array($type, array(self::TYPE_CHAT,self::TYPE_EMAIL,self::TYPE_PHONE))){
            $this->type = $type;
        }else{
            $this->type = false;
        }
    }

    public function getReason($translated = false){
        if($translated){
            switch ($this->reason) {
                case 'suggestion':
                    return 'Sugestão';
                    break;
                case 'praise':
                    return 'Elogio';
                    break;
                case 'doubt':
                    return 'Dúvida';
                    break;
            }
        }else{
            return $this->reason;
        }
    }

    public function setReason($reason){
        if(in_array($reason,array(self::REASON_DOUBT,self::REASON_PRAISE,self::REASON_SUGGESTION))){
            $this->reason = $reason;
        }else{
            $this->reason = false;
        }
    }

    public function getDetails(){
        return $this->details;
    }

    public function setDetails($details){
        $this->details = $details;
    }

    public function getCreated($format = 'full'){
        if(!empty($this->created)){
            switch ($format) {
                case 'full':
                    return date('d/m/Y H:i:s',strtotime($this->created));
                    break;

                case 'onlyDate':
                    return date('d/m/Y',strtotime($this->created));
                    break;

                case 'onlyHour':
                    return date('H:i:s',strtotime($this->created));
                    break;
            }
        }
        return false;
    }

    private function setCreated($created){
        $this->created = $created;
    }

    //Load instance of the class
    private function load($type = false, $reason = false, $Region = false, $details = false, $created = false){
        if(!empty($Region)){
            $this->setRegion($Region);
        }
        if(!empty($reason)) {
            $this->setReason($reason);
        }
        if(!empty($type)) {
            $this->setType($type);
        }
        if(!empty($details)) {
            $this->setDetails($details);
        }
        if(!empty($created)) {
            $this->setCreated($created);
        }
    }

    public function __construct($type = false, $reason = false, $Region = false, $details = false, $created = false){
        $this->Db = new DbCall();
        $this->load($type, $reason, $Region, $details, $created);
    }

    //Get Call by ID
    public function getByID($callId){
        $result = $this->Db->getByID($callId);
        $Region = new Region();
        $Region = $Region->getById($result['region']);
        return new Call($result['type'],$result['reason'],$Region,$result['details'],$result['created']);
    }

    //Get All Calls
    public function getAll(){
        $results = $this->Db->getAll();
        if(!empty($results)){
            $Region = new Region();
            foreach($results as $result){
                $Region = $Region->getById($result['regionID']);
                $data[] = new Call($result['type'],$result['reason'],$Region,$result['details'],$result['created']);
            }
            return $data;
        }else{
            return false;
        }
    }

    //Get All Calls grouped by date and region.
    public function getGroupedByDate(){
        $Calls = $this->getAll();
        if(!empty($Calls)){
            foreach($Calls as $Call){
                $data[$Call->getCreated('onlyDate')][$Call->getRegion()->getName()][] = $Call;
            }
            return $data;
        }else{
            return false;
        }
    }

    //Save a new call
    public function save(){
        return $this->Db->save($this);
    }
}
?>
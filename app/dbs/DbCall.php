<?php

class DbCall{

    private $Db;

    //Set this Db with Database instance
    public function __construct(){
        global $Database;
        $this->Db = $Database;
    }

    //Get from database a Call by ID
    public function getByID($callId){
        $callId = $this->Db->escape($callId);
        $query = "SELECT * FROM olx_call WHERE id = {$callId} LIMIT 1;";
        return $this->Db->query($query,true);
    }

    //Get all calls from database
    public function getAll(){
        $query = "SELECT OC.*
                  FROM olx_call OC
                  JOIN olx_region ORG
                  ON OC.regionID = ORG.ID
                  WHERE OC.status = 'active'
                  ORDER BY created DESC, ORG.name ASC;";
        return $this->Db->query($query);
    }

    //Save a new call on database
    public function save($Call){
        $region = $this->Db->escape($Call->getRegion()->getId());
        $type = $this->Db->escape($Call->getType());
        $reason = $this->Db->escape($Call->getReason());
        $details = $this->Db->escape($Call->getDetails());
        $date = date('Y-m-d H:i:s');
        $query = "INSERT INTO olx_call VALUES ('','{$region}','{$type}','{$reason}','{$details}','{$date}', 'active');";
        return $this->Db->execute($query);
    }
}
?>
<?php

class DbRegion{

    private $Db;

    //Getter and Setters of the class
    public function getDb(){
        return $this->Db;
    }

    public function setDb($Db){
        $this->Db = $Db;
    }

    //Set this Db with Database instance
    public function __construct(){
        global $Database;
        $this->setDb($Database);
    }

    //Get all regions from database
    public function getAll(){
        $query = "SELECT *
                  FROM olx_region ORG
                  ORDER BY ORG.name ASC;";

        return $this->getDb()->query($query);
    }

    //Get region by id on database
    public function getById($Id){
        $Id = $this->getDb()->escape($Id);

        $query = "SELECT *
                  FROM olx_region ORG
                  WHERE ORG.ID = {$Id}
                  LIMIT 1;";

        return $this->getDb()->query($query,true);
    }
}
?>
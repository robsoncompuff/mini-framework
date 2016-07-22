<?php
class Region{

    //Private Attributes
    private $id;

    private $name;

    private $uf;

    private $Db;

    //Getters and Setters of the class
    public function getId(){
        return $this->id;
    }

    public function setId($id){
        $this->id = $id;
    }

    public function getName(){
        return $this->name;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getUf(){
        return $this->uf;
    }

    public function setUf($uf){
        $this->uf = $uf;
    }

    public function __construct($name = false, $uf = false){
        $this->Db = new DbRegion();

        $this->setName($name);
        $this->setUf($uf);
    }

    //Get All Regions
    public function getAll(){
        $results = $this->Db->getAll();

        $regions = array();
        foreach($results as $region){
            $Region = new Region();
            $Region->setId($region['id']);
            $Region->setName($region['name']);
            $Region->setUf($region['uf']);
            $regions[] = $Region;
        }
        return $regions;
    }

    //Get Region by ID
    public function getById($regionId){
        $region = $this->Db->getById($regionId);
        if(!empty($region)){
            $Region = new Region();
            $Region->setId($region['id']);
            $Region->setName($region['name']);
            $Region->setUf($region['uf']);
            return $Region;
        }else{
            return false;
        }
    }
}
?>
<?php
class Database extends Config{

    private $Db = false;

    private $config;

    private $user;

    private $pass;

    private $database;

    private $host;

    private $port;

    private $queryStack = array();

    private $queryCount = 0;

    private $charset;

    private $autocommit;

    private $debug;

    public function __construct(){
        global $ambient;

        //Load database config
        $this->config = $this->getConfig('database');

        //Parse config to object properties
        $this->host = $this->config[$ambient]['hostname'];
        $this->user = $this->config[$ambient]['user'];
        $this->pass = $this->config[$ambient]['password'];
        $this->port = $this->config[$ambient]['port'];
        $this->database = $this->config[$ambient]['database'];

        $this->debug = $this->config[$ambient]['debug'];
        $this->charset = $this->config[$ambient]['charset'];
        $this->autocommit = $this->config[$ambient]['autocommit'];

        //Create connection
        $this->Db = new mysqli('p:'.$this->host, $this->user, $this->pass, $this->database, $this->port);

        //Define connection charset
        if (!$this->Db->set_charset($this->charset)) {
            throw new Exception("Error loading character set {$this->charset}: %s\n", $this->Db->error);
        }

        //Verify database connection
        if ($this->Db->connect_error) {
            throw new Exception("Database->__construct: Can't connect to database. Error: ".$this->Db->connect_error, 20);
        }

        //Set autocommit mode
        $this->Db->autocommit($this->autocommit);
    }

    public function escapeRecursive($data){
        array_walk_recursive($data, array($this, 'realEscapeArray'));
        return $data;
    }

    private function realEscapeArray(&$val, $key){
        $val = $this->Db->real_escape_string($val);
    }

    public function escape($string){
        return $this->Db->real_escape_string($string);
    }

    private function getCaller(){
        $trace=debug_backtrace();

        if(isset($trace[2])){
            $caller=$trace[2];
        } else {
            $caller=$trace[1];
        }

        if($caller['class'] == 'Database'){
            if(isset($trace[3])){
                $caller=$trace[3];
            } else if(isset($trace[2])){
                $caller=$trace[2];
            } else {
                $caller=$trace[1];
            }
        }

        return $caller['class'].'->'.$caller['function'];
    }

    public function replace($table, $values){
        $insertColumns = array();
        $insertValues = array();

        foreach ($values as $field => $value){
            if(!isset($value)){
                $insertValues[] = 'NULL';
            } else if(is_array($value)) {
                $setValue = array();
                foreach ($value as $set){
                    $setValue[] = $this->escape($set);
                }
                $insertValues[] = join(',', $setValue);
            } else {
                $insertValues[] = $this->escape($value);
            }
            $insertColumns[] = $this->escape($field);
        }

        $insertColumns = join(',', $insertColumns);
        $insertValues = "'".join("','", $insertValues)."'";


        $query = "REPLACE INTO ".$table.
            "(".$insertColumns.")
             VALUES(".$insertValues.");";

        return $this->execute($query);
    }

    public function execute($query, $clean = true){
        if ($this->Db->multi_query($query)) {
            $insertion = ($this->Db->insert_id) ? $this->Db->insert_id : $this->Db->affected_rows;
            if($clean){
                $this->clean();
            }
            return $insertion;
        } else {
            throw new Exception("Database->query: Can't complete query called from {$this->getCaller()}. Error: ".$this->Db->error, 20);
        }
    }

    public function query($query, $unique = false){
        if ($this->debug){
            ++$this->queryCount;
            $this->queryStack[] = $this->getCaller();
        }

        $this->execute($query, false);
        return $this->result($unique);
    }

    public function queryLoop($query, $length = 5000){
        $results = array();

        $limitStart = 0;
        $limitLength = $length;

        do{
            $limit = "LIMIT {$limitStart}, {$limitLength}";
            $limitStart += $limitLength;
            $result = $this->query(str_replace('//LIMIT//', $limit, $query));
            //print_r("Got ".count($result)."\n");
            foreach($result as $row){
                $results[] = $row;
            }
            if(count($result) < $length){
                break;
            }
        } while ($result);

        return $results;
    }

    private function result($unique = false){
        $resultSet = 1;
        do{
            $result = $this->Db->store_result();
            $resultArray[$resultSet] = array();
            if ($result) {
                while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
                    $resultArray[$resultSet][] = $row;
                }
                $result->free();
            }
            /* incremment result set ID */
            if ($this->Db->more_results()) {
                $resultSet++;
            }
        } while ($this->Db->more_results() && $this->Db->next_result());

        if(count($resultArray) == 1){
            $resultArray = $resultArray[1];
            if($unique){
                if(isset($resultArray[0])){
                    $resultArray = $resultArray[0];
                }
            }
        } else if($unique) {
            foreach ($resultArray as $index => $result){
                if(isset($result[0])){
                    $resultArray[$index] = $result[0];
                } else {
                    $resultArray[$index] = array();
                }
            }
        }
        return $resultArray;
    }

    private function clean(){
        do {
            //Store first result set
            if ($result = $this->Db->store_result()) {
                $result->free();
            }
        } while ($this->Db->more_results() && $this->Db->next_result());
    }

    public function getInsertionID(){
        return $this->Db->insert_id;
    }

    public function getAffectedRows(){
        return $this->Db->affected_rows;
    }

    public function __destruct(){
        $this->Db->close();
        if($this->debug){
            echo "<script type=\"text/javascript\">alert('Total of database queries: {$this->queryCount}');</script>";
            if($this->queryCount){
                echo '<script type="text/javascript">alert(\'Query calls: \n'.join('\n', $this->queryStack).'\');</script>';
            }
        }
    }
}
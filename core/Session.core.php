<?php

class Session{

    const STARTED = true;
    const NOT_STARTED = false;

    // The state of the session
    private $sessionState = self::NOT_STARTED;

    // The Singleton instance of the class
    private static $instance;

    private function __construct(){}

    public static function getInstance(){
        if (!isset(self::$instance)){
            self::$instance = new self;
        }

        self::$instance->startSession();

        return self::$instance;
    }

    public function startSession(){
        if ( $this->sessionState == self::NOT_STARTED ){
            $this->sessionState = session_start();
        }
        return $this->sessionState;
    }

    public function set( $name , $value ){
        $_SESSION[$name] = $value;
    }

    public function get( $name ){
        if($this->exist($name)){
            return $_SESSION[$name];
        }else{
            return false;
        }
    }

    private function exist( $name ){
        return isset($_SESSION[$name]);
    }

    public function clean( $name ){
        if(!empty($_SESSION[$name])){
            unset( $_SESSION[$name] );
        }
    }

    public function destroy(){
        if ( $this->sessionState == self::STARTED ){
            $this->sessionState = !session_destroy();
            unset( $_SESSION );
            return !$this->sessionState;
        }
        return false;
    }
}
?>
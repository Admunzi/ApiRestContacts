<?php
namespace App\Models;

class Users extends DBAbstractModel {
    private static $instancia;
    public static function getInstancia(){
        if (!isset(self::$instancia)) {
            $miclase = __CLASS__;
            self::$instancia = new $miclase;
        }
        return self::$instancia;
    }
    public function __clone(){
        trigger_error('La clonación no es permitida!.', E_USER_ERROR);
    }

    public function login($user,$psw){
        $this->query= "
            SELECT * FROM users WHERE user = :user and psw = :psw";

        // Load the parameters
        $this->parametros['user'] = $user;
        $this->parametros['psw'] = $psw;
        
        // We execute query that returns records.
        $this->get_results_from_query();
                
        if(count($this->rows) == 1) {
            foreach ($this->rows[0] as $property=>$value) {
                $this->$property = $value;
            }
            $this->mensaje = 'sh found';
        }
        else {
            $this->mensaje = 'sh not found';
        }
        return $this->rows[0]??null;
    }

    public function get($id=''){}
    public function set(){}
    public function edit(){}
    public function delete($id=''){}
}


?>
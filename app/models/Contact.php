<?php
namespace App\Models;

#Importar modelo de abstraccion de base de datos
// require_once('DBAbstractModel.php');


class Contact extends DBAbstractModel {
    /*CONSTRUCCIÓN DEL MODELO SINGLETON*/
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

    private $id;
    private $name;
    private $tlf;
    private $mail   ;

    public function setNombre($name) {
        $this->name = $name;
    }
    public function setTlf($tlf) {
        $this->tlf = $tlf ;
    }
    public function setEmail($mail) {
        $this->mail = $mail ;
    }

    public function set($user_data=array()) {
        foreach ($user_data as $campo=>$value) {
            $$campo = $value;
        }
        $this->query = "INSERT INTO contacts(name, tlf, mail) VALUES(:name, :tlf, :mail)";
        $this->parametros['name']= $name;
        $this->parametros['tlf']= $tlf;
        $this->parametros['mail']= $mail;
        $this->get_results_from_query();
        $this->mensaje = 'SH agregado correctamente';
    }

    public function edit($user_data=array()) {
        foreach ($user_data as $campo=>$value) {
            $$campo = $value;
        }
        $this->query = "UPDATE contacts SET name= :name, tlf= :tlf, mail= :mail, updated_at = CURRENT_TIMESTAMP WHERE id= :id";
        $this->parametros['name']= $name;
        $this->parametros['tlf']= $tlf;
        $this->parametros['mail']= $mail;
        $this->parametros['id']= $id;
        $this->get_results_from_query();
        $this->mensaje = 'SH editado correctamente';
    }

    public function delete($id=''){
        $this->query = "DELETE FROM contacts WHERE id = :id";
        $this->parametros['id']=$id;
        $this->get_results_from_query();
        $this->mensaje = 'sh removed';
    }

    public function get($id=''){
        if($id != '') {
            $this->query = "SELECT * FROM contacts WHERE id = :id";
            //Cargamos los parámetros.
            $this->parametros['id']= $id;
            //Ejecutamos consulta que devuelve registros.
            $this->get_results_from_query();
        }
        
        if(count($this->rows) == 1) {
            foreach ($this->rows[0] as $property=>$value) {
                $this->$property = $value;
            }
            $this->mensaje = 'sh found';
        }
        else {
            $this->mensaje = 'sh not found';
        }
        return $this->rows;
    }
    public function getAll(){
        $this->query = "SELECT * FROM contacts";
        //Ejecutamos consulta que devuelve registros.
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
        return $this->rows;
    }

}
?>
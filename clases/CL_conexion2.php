<?php

class CL_conexion2 {
    
    private $server = "mysql:host=localhost;dbname=citivillas";
   // private $user = "u704762597_sig";
    private $user = "root";
private $pass = "";
    private $con = null;
    private $prepare = null;
    private $options = array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, 
    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8", 
    PDO::ATTR_PERSISTENT => true);

    public function __construct() {
        try {
            if ($this->con === null) {
                //date_default_timezone_set("America/Bogota");
                $this->con = new PDO($this->server, $this->user, $this->pass,$this->options);
                $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->con->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
                $this->con->setAttribute(PDO::ATTR_PERSISTENT, true);
                $this->con->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
//                $this->con->setAttribute(PDO::ATTR_HTTP_HEADER,[ 'Content-Type: application/json; charset=utf-8']);
            }
        } catch (PDOException $e) {
            echo "There is a problem in database connection: " . $e->getMessage();
        }
    }

    /**
     * 
     * @param type $sencente la sentencia sql
     * @return int 1 si la sentencia fue ejecutada con exito, 0 si no 
     * insert y update
     */
    public function ejecutarInsertUpdateDelete($sencente) {
        try {
            //echo $sencente; exit();
            $this->prepare = $this->con->prepare($sencente);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * 
     * @param type $sentence la sentencia sql
     * @return type arreglo con los datos de la sentencia, por lo general es select
     */
    public function retornar($sentence) {
        try {
            $this->prepare = $this->con->prepare($sentence);
            $this->prepare->execute();                
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /**
     * 
     * @param type $sentence la sentencia sql
     * @return int si la consulta es efectiva retorna el ultimo id creado sino retorna
     */
    public function retornarUltimoIdCreado($sentence) {
        try {
            $this->prepare = $this->con->prepare($sentence);
            if ($this->prepare->execute()) {
                return $this->con->lastInsertId();
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }
}

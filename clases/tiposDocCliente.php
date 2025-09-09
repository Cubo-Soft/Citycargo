<?php

include_once '../clases/conexion.php';
;

class tiposDocCliente {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $arregloRetorno;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function __destruct() {
        $this->con = null;
    }
    
    public function retornarTiposDocCliente() {
        try {
            $this->consulta = "select * "
                    . "from tiposDocCliente;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

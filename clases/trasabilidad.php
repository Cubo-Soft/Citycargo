<?php

include_once '../clases/conexion.php';

class trasabilidad {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;
    private $control = null;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function __destruct() {
        $this->con = null;
    }

    public function retornarFechaPago($idservicio) {
        try {
            $this->consulta = "select max(fecha) as fecha "
                    . "from trasabilidad "
                    . "where idservicio=" . $idservicio . " "
                    . "and evento='CUENTA DE COBRO A CONDUCTOR';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

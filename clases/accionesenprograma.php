<?php

include './conexion.php';

class accionesenprograma {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;
    private $control = null;

    public function crearAccion($boton, $fecha, $idempleado) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into accionesenprograma values(null,'" . $boton . "','" . $fecha . "'," . $idempleado . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo=$this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

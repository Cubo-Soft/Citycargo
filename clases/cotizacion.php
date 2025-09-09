<?php

include '../clases/conexion.php';

class cotizacion {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;

    public function retornarNumeroCotizacion() {
        try {
            $this->con = new Conexion();
            $this->consulta="SELECT num_numeroCotizacion FROM numerosCotizacion ORDER BY num_id DESC LIMIT 1;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con=null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

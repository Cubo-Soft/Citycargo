<?php

include_once '../clases/conexion.php';

class asunto {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $retorno;

    public function crearAsunto($evento) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select * from asunto where evento='" . $evento . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (count($this->arreglo) >= 1) {
                $this->retorno = 0;
            } else {
                $this->consulta = "insert into asunto (id,evento,estado) "
                        . "values(null,'" . $evento . "',1);";
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                $this->retorno = 1;
            }

            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function consultarAgendaAsesor($cedula,$fechaInicial,$fechaFinal){
        try {
            $this->con=new Conexion();
            $this->consulta="";
            $this->con=null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
        }

}

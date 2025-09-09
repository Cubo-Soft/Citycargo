<?php

include '../clases/conexion.php';

class seguimientoServicio {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $retorno;

    public function cambiarManifiesto($idservicio, $manifiesto) {
        try {
            $this->retorno = 0;
            $this->con = new Conexion();
            $this->consulta = "update seguimiento_servicio "
                    . "set manifiesto=" . $manifiesto . " "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno = +1;
            } else {
                $this->retorno = +0;
            }
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function crearPlanDeRuta($datos, $archivo) {
        try {
            $rutaCarpeta = "../documentos/planesderuta/";
            $nombreArchivo = "pdr_" . date("Ymd") . "_" . $datos["idservicio"] . "." . pathinfo($archivo["planderuta"]["name"], PATHINFO_EXTENSION);
            $rutaGuardarArchivo = $rutaCarpeta . $nombreArchivo;
            if (move_uploaded_file($archivo["planderuta"]["tmp_name"], $rutaGuardarArchivo)) {
                $this->retorno["pdr"] = 1;
                $this->con = new Conexion();
                $this->consulta = "update seguimiento_servicio "
                        . "set planderuta='" . $rutaGuardarArchivo . "' "
                        . "where idservicio=" . $datos["idservicio"] . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->retorno["modificacion"] = 1;
                } else {
                    $this->retorno["modificacion"] = 0;
                }

                $this->retorno["planderuta"] = $rutaGuardarArchivo;
            } else {
                $this->retorno["pdr"] = 0;
                $this->retorno["modificacion"] = 0;
            }
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarDatosSeguimiento($idservicio) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select placa "
                    . "from servicios "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->retorno["placa"] = $this->arreglo[0]["placa"];

            $this->consulta = "select guia,origen,destino,empresa "
                    . "from datosmostrar "
                    . "where idservicio=" . $idservicio . " "
                    . "order by guia asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->retorno["datosmostrar"] = $this->arreglo;

            $this->consulta = "select guia,fechaHora,ubicacion,observacion "
                    . "from seguimiento "
                    . "where idservicio=" . $idservicio . " "
                    . "order by guia asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->retorno["seguimiento"] = $this->arreglo;

            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    function retornarDatosSeguimientoPorGuia($guia,$idservicio){
        try {
            $this->con=new Conexion();
            
             $this->consulta = "select placa "
                    . "from servicios "
                    . "where idservicio=" . $idservicio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->retorno["placa"] = $this->arreglo[0]["placa"];
            
             $this->consulta = "select guia,origen,destino,empresa "
                    . "from datosmostrar "
                    . "where guia=" . $guia . " "
                    . "order by iddatosmostrar asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->retorno["datosmostrar"] = $this->arreglo;
            
             $this->consulta = "select guia,fechaHora,ubicacion,observacion "
                    . "from seguimiento "
                    . "where guia=" . $guia . " "
                    . "order by fechaHora asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->retorno["seguimiento"] = $this->arreglo;
            
            $this->con=null;
            
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
        }

}

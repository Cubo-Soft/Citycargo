<?php

include '../clases/conexion.php';

class calificaciones {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;
    private $control = null;
    private $retorno = null;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function __destruct() {
        $this->con = null;
    }

    public function retornarCalificacionesPlaca($placa) {
        try {
            $this->consulta = "select detalle,calificacion,idservicio "
                    . "from calificacionPlaca "
                    . "where placa='" . $placa . "'";            
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarCalificacionesPropietario($cedula) {
        try {
            $this->consulta = "select detalle,calificacion,idservicio from calificacionCedula where cedula=" . $cedula . ";";            
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarCalificacionesConductor($cedula) {
        try {
            $this->consulta = "select calificacion from calificacionCedula where cedula=" . $cedula . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarCalificacionCedula($cedula) {
        try {
            $this->consulta = "select idservicio,calificacion "
                    . "from calificacionCedula "
                    . "where cedula=" . $cedula . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function crearCalificacion($arreglo, $mismoPropietario) {
        try {
            $this->retorno = null;
                        
            if ($mismoPropietario === '1') {
                $this->retorno = $this->crearCalificacionPropietario($arreglo[0]["cedulaPropietario"], $arreglo[0]["idservicio"], $arreglo[0]["calificacionPropietario"], $arreglo[0]["comentarioPropietario"]);
                $this->retorno += $this->crearCalificacionPlaca($arreglo[0]["placa"], $arreglo[0]["idservicio"], $arreglo[0]["calificacionPlaca"], $arreglo[0]["comentarioPlaca"]);
            }

            if ($mismoPropietario === '0') {
                $this->retorno = $this->crearCalificacionPropietario($arreglo[0]["cedulaPropietario"], $arreglo[0]["idservicio"], $arreglo[0]["calificacionPropietario"], $arreglo[0]["comentarioPropietario"]);
                $this->retorno += $this->crearCalificacionPlaca($arreglo[0]["placa"], $arreglo[0]["idservicio"], $arreglo[0]["calificacionPlaca"], $arreglo[0]["comentarioPlaca"]);
                $this->retorno += $this->crearCalificacionConductor($arreglo[0]["cedulaConductor"], $arreglo[0]["idservicio"], $arreglo[0]["calificacionConductor"], $arreglo[0]["comentarioConductor"]);
            }

            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * Estas funciones estan tambien en la clase Seguimiento.php
     */

    private function crearCalificacionPropietario($cedula, $idservicio, $calificacion, $Detalle) {
        try {
            $this->consulta = "insert into calificacionCedula (id,cedula,idservicio,calificacion,Detalle) "
                    . "values (null," . $cedula . "," . $idservicio . "," . $calificacion . ",'" . $Detalle . "');";
            //var_dump($this->consulta);exit();
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function crearCalificacionConductor($cedula, $idservicio, $calificacion, $Detalle) {
        try {
            $this->consulta = "insert into calificacionCedula (id,cedula,idservicio,calificacion,Detalle) "
                    . "values (null," . $cedula . "," . $idservicio . "," . $calificacion . ",'" . $Detalle . "');";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function crearCalificacionPlaca($placa, $idservicio, $calificacion, $Detalle) {
        try {
            $this->consulta = "insert into calificacionPlaca (id,placa,idservicio,calificacion,Detalle) "
                    . "values (null,'" . $placa . "'," . $idservicio . "," . $calificacion . ",'" . $Detalle . "');";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function retornarConductor($placa) {
        try {
            $this->consulta = "select c.cond_identificacion "
                    . "from conductores as c,conductor_vehiculo as cv "
                    . "where c.cond_identificacion=cv.identificacion "
                    . "and c.perfil=10 "
                    . "and c.estado='ACTIVO' "
                    . "and cv.placa='" . $placa . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->retorno = $this->prepare->fetch();
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

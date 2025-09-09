<?php

/**
 * Description of pruebasentrega
 * Clase para trabajar las imagenes de las pruebas de entrega 
 * en la interfaz de seguimiento del servicio S
 *
 * @author Wilmer P. Silva
 */
include_once '../clases/conexion.php';

class pruebasentrega {

    private $con, $prepare, $arreglo, $consulta, $retorno;

    public function crearPruebasEntrega($datos, $opcion) {
        try {
            if ($opcion === 1) {
                $this->consulta = "insert into pruebasentrega (id,idservicio,guia,ruta,cedula,fecha,estado) "
                        . "values(null," . $datos["idservicio"] . "," . $datos["guia"] . ",'" . $datos["ruta"] . "'," . $datos["cedula"] . ",'" . date("Y-m-d H:m:s") . "',1);";
            }
            $this->con = new Conexion();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->retorno = $this->con->lastInsertId();

            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPruebasEntrega($datos, $opcion) {
        try {

            if ($opcion === 1) {
                $this->consulta = "select * from pruebasentrega "
                        . "where idservicio=" . $datos["idservicio"] . " "
                        . "and estado=1;";
            }

            if ($opcion === 2) {
                $this->consulta = "select guia,id from pruebasentrega "
                        . "where idservicio=" . $datos["idservicio"] . " "
                        . "and estado=1 "
                        . "group by guia "
                        . "order by guia asc;";
            }

            $this->con = new Conexion();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->retorno = $this->prepare->fetchAll();
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function actualizarPruebasEntrega($datos, $opcion) {
        try {
            $this->consulta = "update pruebasentrega ";
            if ($opcion === 1) {
                $this->consulta .= "set estado=0 "
                        . "where id=" . $datos["id"] . ";";                
            }
            
            //echo $this->consulta; exit();
            
            $this->con = new Conexion();
            $this->prepare = $this->con->prepare($this->consulta);
            $this->retorno = $this->prepare->execute();
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

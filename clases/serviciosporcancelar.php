<?php

include '../clases/conexion.php';

class serviciosporcancelar {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $lista = null;
    private $control = null;

    /*
     * 20190411
     * Esta función esta también en la clase rol_boton
     */

    public function crearServicio($idservicio, $idempleado, $fecha, $motivo) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into serviciosporcancelar values "
                    . "(null," . $idservicio . "," . $idempleado . ",'" . $fecha . "','" . $motivo . "','CANCELAR');";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarServiciosPorCancelar($opcion) {
        try {
            $this->con = new Conexion();
              $this->consulta = "select spc.id,spc.idservicio,spc.fecha,spc.motivo,concat(e.emp_nombres,' ',e.emp_apellidos) as nombreEmpleado "
                    . "from serviciosporcancelar as spc,empleados as e "
                    . "where spc.idempleado=e.emp_cedula "
                    . "and spc.estado='CANCELAR' ";
            
            if($opcion===1){
                $this->consulta.="and spc.motivo='Servicio finalizado con novedad.'";
            }

            if($opcion===2){
                $this->consulta.="and spc.motivo<>'Servicio finalizado con novedad.'";
            }
            
            $this->consulta.="order by spc.idservicio asc;";
            
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarEstado($id) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update serviciosporcancelar "
                    . "set estado='MANTENIDO' "
                    . "where id=" . $id . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarEstadoCancelado($id) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update serviciosporcancelar "
                    . "set estado='CANCELADO' "
                    . "where idservicio=" . $id . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

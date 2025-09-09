<?php

include_once '../clases/conexion.php';

class modulos_empleados {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $arreglos = array();

    public function crearBoton($idboton, $cedula) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into empleado_botones (cedulaempleado,idboton) "
                    . "values(" . $cedula . "," . $idboton . ");";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function borrarBoton($idboton, $cedula) {
        try {
            $this->con = new Conexion();
            $this->consulta = "delete from empleado_botones "
                    . "where cedulaempleado=" . $cedula . " "
                    . "and idboton=" . $idboton . " ";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarBotones($cedula) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select b.clsboostrap,b.valor,b.id_boton,b.title,b.id "
                    . "from empleado_botones as eb,botones as b "
                    . "where eb.idboton=b.id_boton "
                    . "and eb.cedulaempleado=" . $cedula . " "
                    . "order by b.valor asc;";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPermiso($cedula,$nombrepermiso) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from permisosmodulos "
                    . "where cedula=" . $cedula . " "
                    . "and nombrepermiso='".$nombrepermiso."';";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarValorPermiso($id, $estado) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update "
                    . "permisosmodulos "
                    . "set estado=" . $estado . " "
                    . "where id=" . $id . " ";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function createPermiso($cedula, $nombremodulo, $nombrepermiso, $descripcion) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into permisosmodulos (id,nombremodulo,idboton,nombrepermiso,cedula,estado,fecha,descripcion) "
                    . "values (null,'" . $nombremodulo . "',0,'" . $nombrepermiso . "'," . $cedula . ",1,'" . date("Y-m-d") . "','" . $descripcion . "');";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->arreglo = $this->prepare->execute();
            $this->con = null;
            //return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

<?php

include '../clases/conexion.php';

class direcciones {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $retorno;

    public function retornarDireccionesCliente($nit, $tipo, $estado) {
        try {
            $retorno = array();
            $this->con = new Conexion();
            $this->consulta = "select d.iddireccion,d.telefono,d.direccion,m.mun_nombre "
                    . "from direcciones as d,municipios as m "
                    . "where d.ciudad=m.mun_id "
                    . "and d.documento=" . $nit . " "
                    . "and d.tipo=" . $tipo . " "
                    . "and d.estado='" . $estado . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            if (count($this->arreglo) === 0) {
                $this->consulta = "select cli_direccion,"
                        . "from "
                        . "where ";
                $retorno["resultado"] = 0;
            } else {
                $retorno["resultado"] = $this->arreglo;
            }
            $this->con = null;
            return $retorno;
        } catch (PDOException $ex) {
            echo $ex->getTraceAsString();
        }
    }

    public function cambiarEstadoDireccion($iddireccion, $estado) {
        try {
            $this->con = new Conexion();
            $this->consulta = "update direcciones "
                    . "set estado='" . $estado . "' "
                    . "where iddireccion=" . $iddireccion . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->retorno = $this->prepare->execute();
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function crearDireccion($telefono, $direccion, $idciudad, $nit, $tipo) {
        try {
            $this->con = new Conexion();
            $this->consulta = "insert into direcciones (iddireccion,documento,telefono,direccion,ciudad,estado,tipo) "
                    . "values(null," . $nit . "," . $telefono . ",'" . $direccion . "'," . $idciudad . ",1," . $tipo . ");";
//            echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->retorno = $this->prepare->execute();
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornaDirPorCiuCli($nit, $idciudad) {
        try {
            $this->con = new Conexion();
            $this->consulta = "select telefono,direccion,"
                    . "(select mun_nombre from municipios where mun_id=ciudad) nombreCiudad,ciudad,iddireccion "
                    . "from direcciones "
                    . "where documento=" . $nit . " "
                    . "and ciudad=".$idciudad." "
                    . "and tipo=1 "
                    . "and estado=1 "
                    . "order by direccion asc;";
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

}

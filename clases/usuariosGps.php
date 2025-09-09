<?php

include '../clases/conexion.php';

class usuariosGps {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $retorno;

    public function cambiarCrearOperadorGPS($placa, $operador) {
        try {
            $this->retorno = 0;
            $this->con = new Conexion();
            $this->consulta = "select operador "
                    . "from usuariosgps "
                    . "where placa='" . $placa . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) > 0) {
                $this->consulta = "update usuariosgps "
                        . "set operador='" . $operador . "' "
                        . "where placa='" . $placa . "';";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->retorno = +1;
                } else {
                    $this->retorno = +0;
                }
            } else {
                $this->consulta = "insert into usuariosgps (idusuariogps,placa,operador,usuario,clave) "
                        . "values (null,'" . $placa . "','" . $operador . "','','');";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->retorno = +1;
                } else {
                    $this->retorno = +0;
                }
            }
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarCrearUsuarioGPS($placa, $usuario) {
        try {
            $this->retorno = 0;
            $this->con = new Conexion();
            $this->consulta = "select usuario "
                    . "from usuariosgps "
                    . "where placa='" . $placa . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) > 0) {
                $this->consulta = "update usuariosgps "
                        . "set usuario='" . $usuario . "' "
                        . "where placa='" . $placa . "';";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->retorno = +1;
                } else {
                    $this->retorno = +0;
                }
            } else {
                $this->consulta = "insert into usuariosgps (idusuariogps,placa,operador,usuario,clave) "
                        . "values (null,'" . $placa . "','','" . $usuario . "','');";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->retorno = +1;
                } else {
                    $this->retorno = +0;
                }
            }
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarCrearClaveGPS($placa, $clave) {
        try {
            $this->retorno = 0;
            $this->con = new Conexion();
            $this->consulta = "select clave "
                    . "from usuariosgps "
                    . "where placa='" . $placa . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            if (count($this->arreglo) > 0) {
                $this->consulta = "update usuariosgps "
                        . "set clave='" . $clave . "' "
                        . "where placa='" . $placa . "';";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->retorno = +1;
                } else {
                    $this->retorno = +0;
                }
            } else {
                $this->consulta = "insert into usuariosgps (idusuariogps,placa,operador,usuario,clave) "
                        . "values (null,'" . $placa . "','','','" . $clave . "');";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    $this->retorno = +1;
                } else {
                    $this->retorno = +0;
                }
            }
            $this->con = null;
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

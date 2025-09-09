<?php

include_once '../clases/conexion.php';

class otrosCostos {

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

    public function crearCosto($idservicio, $numeroGuia, $concepto, $valor, $cedulaEmpleado, $fechaHora) {
        try {
            $valorTotal = 0;
            $valorOtro = 0;
            $valorActual = $this->retornarValorTotal($numeroGuia);
            $this->consulta = "insert into otrosCostos(id,idservicio,numeroGuia,concepto,valor,cedulaEmpleado,fechaHora) "
                    . "values (null," . $idservicio . "," . $numeroGuia . ",'" . $concepto . "'," . $valor . "," . $cedulaEmpleado . ",'" . $fechaHora . "')";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno = 1;
            } else {
                $this->retorno = 0;
            }
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function consultarCostos($numeroGuia) {
        try {
            $this->consulta = "select id,numeroGuia,concepto,valor "
                    . "from otrosCostos "
                    . "where numeroGuia=" . $numeroGuia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function borrarCosto($id) {
        try {
            $this->consulta = "delete from otrosCostos where id=" . $id . "";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->retorno += 1;
            } else {
                $this->retorno += 0;
            }
            return $this->retorno;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    private function retornarValorTotal($numeroGuia) {
        try {
            $total = 0;
            $totalGeneral = 0;
            $this->consulta = "select valor "
                    . "from otrosCostos "
                    . "where numeroGuia=" . $numeroGuia . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();

            for ($index1 = 0; $index1 < count($this->arreglo); $index1++) {
                $totalGeneral = $totalGeneral + $this->arreglo[$index1]["valor"];
            }

            return $totalGeneral;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarAgregarCostos($cedula) {
        try {
            $this->consulta = "select id from empleado_botones where cedulaempleado=" . $cedula . " "
                    . "and idboton=27;";
            //echo $this->consulta;
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo[0]["id"];
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function returnPermisosModulos($nombreModulo, $cedula) {
        try {
            $this->consulta = "select estado "
                    . "from permisosmodulos "
                    . "where cedula=" . $cedula . " "
                    . "and nombreModulo='" . $nombreModulo . "';";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

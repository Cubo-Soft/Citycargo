<?php

include '../clases/conexion.php';

class impuestos {

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

    public function retornarImpuestos() {
        try {
            $this->consulta = "select vi.valimp_id,vi.valimp_valor,vi.valimp_ano,vi.base,i.imp_nombre "
                    . "from valoresimpuestos as vi,impuestos as i "
                    . "where vi.valimp_imp_id=i.imp_id;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            return $this->prepare->fetchAll();
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function modificarAnio($anio) {
        try {
            $this->consulta = "update valoresimpuestos "
                    . "set valimp_ano=" . $anio . ";";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                return 1;
            } else {
                return 0;
            }
        } catch (PDOException $ex) {
            
        }
    }

    public function modificarValor($id, $valor) {
        try {
            $this->consulta = "update valoresimpuestos "
                    . "set valimp_valor=" . $valor . " "
                    . "where valimp_id=" . $id . ";";
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

    public function modificarBase($id, $valor) {
        try {
            $this->consulta = "update valoresimpuestos "
                    . "set base=" . $valor . " "
                    . "where valimp_id=" . $id . ";";
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

}

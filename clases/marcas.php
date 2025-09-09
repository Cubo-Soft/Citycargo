<?php

include '../clases/conexion.php';

class marcas {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;

    public function __construct() {
        $this->con = new Conexion();
    }

    public function __destruct() {
        $this->con = null;
    }

    public function crearMarca($marca) {
        try {
            $this->consulta = "insert into marcasvehiculos (id,marca,estado)"
                    . "values(null,'" . $marca . "',1);";
            $this->prepare = $this->con->prepare($this->consulta);
            if ($this->prepare->execute()) {
                $this->consulta = "select * from marcasvehiculos;";                
                $this->prepare = $this->con->prepare($this->consulta);
                $this->prepare->execute();
                return $this->prepare->fetchAll();
            } else {
                return 0;
            }
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

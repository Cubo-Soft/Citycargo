<?php

include '../clases/conexion.php';

class valorDeclarado {

    public $con, $arreglo, $retorno, $resultado, $consulta, $prepare;

    public function cambiarValorDeclarado($guia, $nuevoValor, $idservicio) {
        try {
            $this->con = new Conexion();

            $this->consulta = "select * "
                    . "from valordeclarado "
                    . "where guia=" . $guia . ";";            
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
           
            if (count($this->arreglo) > 0) {
                $this->consulta = "update valordeclarado set valor=" . $nuevoValor . " "
                        . "where guia=" . $guia . ";";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                $this->consulta = "insert into valordeclarado (id,guia,idservicio,valor) "
                        . "values (null," . $guia . "," . $idservicio . "," . $nuevoValor . ")";
                $this->prepare = $this->con->prepare($this->consulta);
                if ($this->prepare->execute()) {
                    return 1;
                } else {
                    return 0;
                }
            }


            $this->con = null;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    /*
     * 201810251140
     * No estaba buscando si ya estaba creado. Se modifica para buscar
     * si ya existe entonces se cambia no se inserta
     */

    public function verificarValorDeclarado($guia) {
        try {            
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from valordeclarado "
                    . "where guia=" . $guia . "; ";            
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

<?php

include_once '../clases/conexion.php';

class roles {

    private $con;
    private $prepare;
    private $arreglo;
    private $consulta;
    private $arreglos = array();

    public function retornarEmpleados() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select emp_cedula,concat(emp_nombres,' ',emp_apellidos) as nombres "
                    . "from empleados "
                    . "where estado='A' "
                    . "order by nombres asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarRoles() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * from roles;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    public function retornarBotones() {
        try {
            $this->con = new Conexion();
            $this->consulta = "select * "
                    . "from botones "
                    . "order by valor asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->arreglo = $this->prepare->fetchAll();
            $this->con = null;
            return $this->arreglo;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
    
    
}

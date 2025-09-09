<?php

include_once '../clases/CL_conexion2.php';

class CL_tipovehiculo
{

    private $conn, $consulta, $retorno, $respuesta;

    public function __construct()
    {
        $this->conn = new CL_conexion2();
    }

    public function __destruct()
    {
        $this->conn = null;
    }

    public function retornarTiposVehiculos($parametro1, $parametro2)
    {
        try {

            $this->consulta = "select * 
            from tipovehiculo 
            where id_estados_tablas=1";
            $this->respuesta = $this->conn->retornar($this->consulta);

            return $this->respuesta;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarEmpleados()
    {
        try {
            $this->consulta = "select *, concat(empleados.emp_nombres,' ',empleados.emp_apellidos) as nombreCompleto "
                . "from empleados "
                . "where estado='A' "
                . "order by emp_nombres asc;";
            $this->respuesta = $this->conn->retornar($this->consulta);
            return $this->respuesta;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }
}

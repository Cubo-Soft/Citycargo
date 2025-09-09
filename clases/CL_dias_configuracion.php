<?php


include_once '../clases/CL_conexion2.php';

class CL_dias_configuracion
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

    public function retornarDiasConfiguracion($parametro1, $parametro2)
    {
        try {

            if($parametro2===1){
                $this->consulta = "select * 
            from dias_configuracion 
            order by id;";
            }
            
            if($parametro2===2){
                $this->consulta = "select * 
            from dias_configuracion 
            where estado=1";
            }


            $this->respuesta = $this->conn->retornar($this->consulta);

            return $this->respuesta;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function crearDiasConfiguracion($parametro1, $parametro2)
    {
        try {

            $this->consulta = "insert into dias_configuracion (id,tiempo,estado,cantidad) 
            values (null," . $parametro1["tiempo"] . "," . $parametro1["estado"] . "," . $parametro1["cantidad"] . ");";
            return $this->conn->retornarUltimoIdCreado($this->consulta);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function actualizarDiasConfiguracion($parametro1, $parametro2)
    {
        try {

            if ($parametro2 === 1) {
                $this->consulta = "update dias_configuracion set estado=0;";
            }

            if ($parametro2 === 2) {
                $this->consulta = "update dias_configuracion 
            set estado=" . $parametro1["estado"] . ",cantidad=" . $parametro1["cantidad"] . " 
            where id=" . $parametro1["id"] . ";";
            }

            if ($parametro2 === 3) {
                $this->consulta = "update dias_configuracion 
            set estado=" . $parametro1["estado"] . " 
            where id=" . $parametro1["id"] . ";";
            }

            if ($parametro2 === 4) {
                $this->consulta = "update dias_configuracion 
            set cantidad=" . $parametro1["cantidad"] . " 
            where id=" . $parametro1["id"] . ";";
            }

            return $this->conn->ejecutarInsertUpdateDelete($this->consulta);
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }
}

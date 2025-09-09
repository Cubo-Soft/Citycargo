<?php 

include_once '../clases/CL_conexion2.php';

class CL_estudio_seguridad {

    private $conn,$consulta,$retorno,$respuesta;    

    public function __construct()
    {
        $this->conn=new CL_conexion2();
    }

    public function __destruct()
    {
        $this->conn=null;
    }

    public function retornarEstudioSeguridad($placa,$parametro2){
        try{
            
            $this->consulta="select * from estudio_seguridad where placa='".$placa."'";
            $this->respuesta=$this->conn->retornar($this->consulta);

            return $this->respuesta;

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

    public function crearEstudioSeguridad($parametro1,$parametro2){
        try{
            
            $this->consulta="insert into estudio_seguridad (id,placa,cedulaempleado,fechaestudio) values (null,'".$parametro1["placa"]."',".$parametro1["cedula"].",'".date("Y-m-d")."');";            
            $this->respuesta=$this->conn->ejecutarInsertUpdateDelete($this->consulta);
            return $this->respuesta;

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

    public function cambiarEstudioSeguridad($placa,$datos){
        try{
            
            $this->consulta="update estudio_seguridad set ".$datos["campo"]."='".$datos["valor"]."' where placa='".$placa."';";
            
            //echo $this->consulta; //exit();
            
            $this->respuesta=$this->conn->ejecutarInsertUpdateDelete($this->consulta);
            return $this->respuesta;

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

}
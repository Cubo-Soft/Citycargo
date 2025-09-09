<?php 

include_once '../clases/CL_conexion2.php';

class CL_usuariosgps {

    private $conn,$consulta,$retorno,$respuesta;    

    public function __construct()
    {
        $this->conn=new CL_conexion2();
    }

    public function __destruct()
    {
        $this->conn=null;
    }

    public function retornarUsuariosGps($placa,$parametro2){
        try{
            
            $this->consulta="select * from usuariosgps where placa='".$placa."';";
            $this->respuesta=$this->conn->retornar($this->consulta);

            return $this->respuesta;

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

}


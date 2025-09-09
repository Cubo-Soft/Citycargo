<?php 


include_once '../clases/CL_conexion2.php';

class CL_parentescos {

    private $conn,$consulta,$retorno,$respuesta;    

    public function __construct()
    {
        $this->conn=new CL_conexion2();
    }

    public function __destruct()
    {
        $this->conn=null;
    }

    public function retornarParentescos($parametro1,$parametro2){
        try{
            
            $this->consulta="select * 
            from parentescos  
            where id_estados=1 
            order by nombre";
            $this->respuesta=$this->conn->retornar($this->consulta);

            return $this->respuesta;

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

    public function crearParentescos($nombreParentesco,$parametro2){
        try{
            
            $this->consulta="insert into parentescos (id,nombre,estado) 
            values (null,'".$nombreParentesco."',1)";
            return $this->conn->retornarUltimoIdCreado($this->consulta);            

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

}


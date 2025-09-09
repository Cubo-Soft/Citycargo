<?php 

include_once '../clases/CL_conexion2.php';

class CL_documentosoporte {

    private $conn,$consulta,$retorno,$respuesta;    

    public function __construct()
    {
        $this->conn=new CL_conexion2();
    }

    public function __destruct()
    {
        $this->conn=null;
    }

    public function retornarDocumentosoporte($parametro1,$parametro2){
        try{
            
            $this->consulta="select * 
            from documentosoporte  
            where id_estados_tablas=1";
            $this->respuesta=$this->conn->retornar($this->consulta);

            return $this->respuesta;

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

    public function crearDocumentosoporte($documentosoporte,$parametro2){
        try{
            
            $this->consulta="insert into documentosoporte (id,nombre,id_estados_tablas) 
            values (null,'".$documentosoporte."',1)";
            return $this->conn->retornarUltimoIdCreado($this->consulta);            

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

}


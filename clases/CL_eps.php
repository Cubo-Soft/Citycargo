<?php 

include_once '../clases/CL_conexion2.php';

class CL_eps {

    private $conn,$consulta,$retorno,$respuesta;    

    public function __construct()
    {
        $this->conn=new CL_conexion2();
    }

    public function __destruct()
    {
        $this->conn=null;
    }

    public function retornarEps($parametro1,$parametro2){
        try{
            
            $this->consulta="select * 
            from eps 
            where id_estados_tablas=1 
            order by nombre asc";
            $this->respuesta=$this->conn->retornar($this->consulta);

            return $this->respuesta;

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

    public function crearEps($nombreEps,$parametro2){
        try{
            
            $this->consulta="insert into eps (id,nombre,id_estados_tablas) 
            values (null,'".$nombreEps."',1)";
            return $this->conn->retornarUltimoIdCreado($this->consulta);            

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

}


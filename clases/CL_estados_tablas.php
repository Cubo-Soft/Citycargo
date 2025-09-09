<?php 

include_once '../clases/CL_conexion2.php';

class CL_estados_tablas {

    private $conn,$consulta,$retorno,$respuesta;    

    public function __construct()
    {
        $this->conn=new CL_conexion2();
    }

    public function __destruct()
    {
        $this->conn=null;
    }

    public function retornarEstadosTablas($parametro1,$parametro2){
        try{
            
            $this->consulta="select * 
            from estados_tablas  
            where id_estados=1";
            $this->respuesta=$this->conn->retornar($this->consulta);

            return $this->respuesta;

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

    public function crearEstadosTablas($arreglo,$parametro2){
        try{
            
            $this->consulta="insert into estados_tablas (id,id_estados,nombre_estado) 
            values (null,'".$arreglo["id_estado"]."','".$arreglo["nombre_estado"]."')";
            return $this->conn->retornarUltimoIdCreado($this->consulta);            

        }catch(Exception $exc){
            echo $exc->getTraceAsString();
        }
    }

}


<?php

include_once './clases/CL_conexion2.php';

 class CL_clase_general extends CL_conexion2{
    
//     private $sentence;

    public function retornar($sentencia)
    {
        parent::retornar($sentencia);
    }
  

 }

$OB_clase_general=new CL_clase_general();
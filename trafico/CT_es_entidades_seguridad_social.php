<?php 
include_once '../clases/CL_clase_general.php';

$OB_clase_general=new CL_clase_general();

if($_POST["caso"]==='1'){
    
    $sentencia="SELECT * FROM es_entidades_seguridad_social;";
    $retorno["es_entidades_seguridad_social"]=$OB_clase_general->retornar($sentencia);

    echo json_encode($retorno);

}
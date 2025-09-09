<?php 

include_once '../clases/CL_clase_general.php';

$OB_clase_general=new CL_clase_general();
$retorno=array();

if($_POST["caso"]==='1'){

    $sentencia="SELECT  es_nombres_entidades_seguridad_social.nombre,es_entidades_seguridad_social.nombre as nombreEntidad,"
    ."es_nombres_estados.nombre as nombreEstado "
    ."FROM es_nombres_entidades_seguridad_social,es_entidades_seguridad_social "
    ."WHERE es_nombres_entidades_seguridad_social.id_es_entidades_seguridad_social=es_entidades_seguridad_social.id "
    ."AND es_nombres_entidades_seguridad_social.id_es_nombres_estados=es_nombres_estados.id ";
    
    echo $sentencia;

    //$retorno["es_nombres_entidades_seguridad_social"]=$OB_clase_general->retornar($sentencia);    

    //echo json_encode($retorno);

}

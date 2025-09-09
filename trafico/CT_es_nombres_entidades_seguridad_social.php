<?php 

include_once '../clases/CL_clase_general.php';

$OB_clase_general=new CL_clase_general();
$retorno=array();

$sentencia="SELECT es_nombres_entidades_seguridad_social.id,es_nombres_entidades_seguridad_social.nombre,es_entidades_seguridad_social.nombre as nombreEntidad,"
."es_nombres_estados.nombre as nombreEstado,es_entidades_seguridad_social.id as id_es_entidades_seguridad_social,es_nombres_estados.id as id_es_nombres_estados "
."FROM es_nombres_entidades_seguridad_social,es_entidades_seguridad_social,es_nombres_estados "
."WHERE es_nombres_entidades_seguridad_social.id_es_entidades_seguridad_social=es_entidades_seguridad_social.id "
."AND es_nombres_entidades_seguridad_social.id_es_nombres_estados=es_nombres_estados.id ";

if($_POST["caso"]==='1'){
    
    $retorno["es_nombres_entidades_seguridad_social"]=$OB_clase_general->retornar($sentencia);    

    echo json_encode($retorno);

}

if($_POST["caso"]==='2'){

    $sentencia.="AND es_nombres_entidades_seguridad_social.id=".$_POST["datosAEnviar"]["id"].";";

    $retorno["es_nombres_entidades_seguridad_social"]=$OB_clase_general->retornar($sentencia);    
    echo json_encode($retorno);

}

if($_POST["caso"]==='3'){

    $sentencia="UPDATE es_nombres_entidades_seguridad_social "
    ."SET id_es_entidades_seguridad_social=".$_POST["datosAEnviar"]["id_es_entidades_seguridad_social"].","
    ."nombre='".$_POST["datosAEnviar"]["nombre"]."',"
    ."id_es_nombres_estados=".$_POST["datosAEnviar"]["id_es_nombres_estados"]." "
    ."WHERE id=".$_POST["datosAEnviar"]["id"]." ";

    echo json_encode($OB_clase_general->ejecutarInsertUpdateDelete($sentencia));

}

if($_POST["caso"]==='4'){

    $sentencia="INSERT INTO es_nombres_entidades_seguridad_social (id,id_es_entidades_seguridad_social,nombre,id_es_nombres_estados) "
    ."VALUES (null,".$_POST["datosAEnviar"]["id_es_entidades_seguridad_social"].",'".$_POST["datosAEnviar"]["nombre"]."',7);";

    echo json_encode($OB_clase_general->retornarUltimoIdCreado($sentencia));

}

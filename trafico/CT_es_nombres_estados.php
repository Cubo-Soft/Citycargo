<?php

include_once '../clases/CL_clase_general.php';

$OB_clase_general=new CL_clase_general();
$retorno=array();

if($_POST["caso"]==='1'){

    $sentencia="SELECT es_nombres_estados.id,es_nombres_estados.nombre,es_estados.nombre as nombreEstado,es_nombres_estados.id_estados "
    ."FROM es_nombres_estados,es_estados "
    ."WHERE es_nombres_estados.id_estados=es_estados.id ";
    $retorno["es_nombres_estados"]=$OB_clase_general->retornar($sentencia);

    $sentencia="SELECT * FROM es_estados;";
    $retorno["es_estados"]=$OB_clase_general->retornar($sentencia);

    echo json_encode($retorno);

}

if($_POST["caso"]==='2'){

    $sentencia="insert into es_nombres_estados(id,id_estados,nombre) "
    ."values (null,".$_POST["datosAEnviar"]["id_estados"].",'".$_POST["datosAEnviar"]["nombre"]."')";

    echo json_encode($OB_clase_general->ejecutarInsertUpdateDelete($sentencia));

}

if($_POST["caso"]==='3'){
    $sentencia="SELECT * FROM es_nombres_estados WHERE id=".$_POST["datosAEnviar"]["id"].";";    
    echo json_encode($OB_clase_general->retornar($sentencia));
}

if($_POST["caso"]==='4'){
    $sentencia="update es_nombres_estados set id_estados=".$_POST["datosAEnviar"]["id_estados"].",nombre='".$_POST["datosAEnviar"]["nombre"]."' where id=".$_POST["datosAEnviar"]["id"].";";        
    echo json_encode($OB_clase_general->ejecutarInsertUpdateDelete($sentencia));
}

if($_POST["caso"]==='5'){

    $sentencia="SELECT * from es_nombres_estados WHERE id_estados=".$_POST["datosAEnviar"]["id_estados"].";";
    $retorno["es_nombres_estados"]=$OB_clase_general->retornar($sentencia);
    echo json_encode($retorno);
}
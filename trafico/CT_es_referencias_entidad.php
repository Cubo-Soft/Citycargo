<?php 

include_once '../clases/CL_clase_general.php';

$OB_clase_general=new CL_clase_general();

if($_POST["caso"]==='1'){
    
    $sentencia="SELECT * FROM es_documentos where id_es_nombres_estados=5;";
    $retorno["es_documentos"]=$OB_clase_general->retornar($sentencia);

    echo json_encode($retorno);

}

if($_POST["caso"]==='2'){
    
    $sentencia="SELECT id_es_documentos FROM es_documentos_entidad "
    ."WHERE id=".$_POST["datosAEnviar"]["id"].";";

    $retorno["es_documentos_entidad"]=$OB_clase_general->retornar($sentencia);

    $id_es_documentos=$retorno["es_documentos_entidad"][0]["id_es_documentos"];

    $sentencia="UPDATE es_documentos SET URL='".$_POST["datosAEnviar"]["url"]."' "
    ."WHERE id=".$id_es_documentos.";";
    echo json_encode($OB_clase_general->ejecutarInsertUpdateDelete($sentencia));

}

if($_POST["caso"]==='3'){
    $sentencia="SELECT es_referencias_entidad.id,es_tipo_referencia.nombre as nombreReferencia,"
    ."es_nombre_vinculo.nombre as nombreVinculo "
    ."FROM es_referencias_entidad,es_nombre_vinculo,es_tipo_referencia "
    ."WHERE es_referencias_entidad.id_es_tipo_referencia=es_tipo_referencia.id "
    ."AND es_referencias_entidad.id_es_nombre_vinculo=es_nombre_vinculo.id ";

    $retorno["es_referencias_entidad"]=$OB_clase_general->retornar($sentencia);

    echo json_encode($retorno);
}

if($_POST["caso"]==='4'){

    $sentencia="INSERT INTO es_referencias_entidad (id,id_es_tipo_referencia,id_es_nombre_vinculo) "
    ."VALUES (null,".$_POST["datosAEnviar"]["id_es_tipo_referencia"].",".$_POST["datosAEnviar"]["id_es_nombre_vinculo"].")";
    echo json_encode($OB_clase_general->retornarUltimoIdCreado($sentencia));
}

if($_POST["caso"]==='5'){
    $sentencia="DELETE FROM es_referencias_entidad WHERE id=".$_POST["datosAEnviar"]["id"].";";
    echo json_encode($OB_clase_general->ejecutarInsertUpdateDelete($sentencia));
}
<?php

include_once '../clases/CL_clase_general.php';

$OB_clase_general = new CL_clase_general();

if ($_POST["caso"] === '1') {

    $sentencia = "SELECT es_seguridad_social_entidad.id,es_entidades_seguridad_social.nombre as entidadSeguridadSocial,"
        . "es_entidad.nombre as nombreEntidad "
        . "FROM es_seguridad_social_entidad,es_entidades_seguridad_social,es_entidad "
        . "WHERE es_seguridad_social_entidad.id_entidad_seguridad_social=es_entidades_seguridad_social.id "
        . "AND es_seguridad_social_entidad.id_entidad=es_entidad.id;";

    $retorno["es_seguridad_social_entidad"] = $OB_clase_general->retornar($sentencia);
    echo json_encode($retorno);
}

if ($_POST["caso"] === '2') {

    $sentencia = "INSERT INTO es_seguridad_social_entidad (id,id_entidad_seguridad_social,id_entidad) "
        . "values (null," . $_POST["datosAEnviar"]["id_entidad_seguridad_social"] . "," . $_POST["datosAEnviar"]["id_entidad"] . ")";

    echo json_encode($OB_clase_general->retornarUltimoIdCreado($sentencia));
}

if($_POST["caso"]==='3'){

    $sentencia="DELETE FROM es_seguridad_social_entidad WHERE id=".$_POST["datosAEnviar"]["id"].";";
    echo json_encode($OB_clase_general->ejecutarInsertUpdateDelete($sentencia));

}

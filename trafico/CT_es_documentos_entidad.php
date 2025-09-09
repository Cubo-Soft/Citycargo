<?php

include_once '../clases/CL_clase_general.php';

$OB_clase_general = new CL_clase_general();
$retorno = array();

$sentencia = "SELECT es_documentos_entidad.id,es_documentos.nombre as nombreDocumento,es_entidad.nombre as nombreEntidad,"
    . "es_documentos.url "
    . "FROM es_documentos_entidad,es_documentos,es_entidad "
    . "WHERE es_documentos_entidad.id_es_documentos=es_documentos.id "
    . "AND es_documentos_entidad.id_es_entidad=es_entidad.id ";

if ($_POST["caso"] === '1') {
    $retorno["es_documentos_entidad"] = $OB_clase_general->retornar($sentencia);
    echo json_encode($retorno);
}

if ($_POST["caso"] === '2') {

    //verificar si la relación ya existe
    $sentencia = "SELECT id FROM es_documentos_entidad WHERE id_es_documentos=" . $_POST["datosAEnviar"]["id_es_documentos"] . " AND id_es_entidad=" . $_POST["datosAEnviar"]["id_es_entidad"] . ";";
    if (count($OB_clase_general->retornar($sentencia)) > 0) {
        $retorno["es_documentos_entidad"] = -1;
    } else {
        $sentencia = "INSERT INTO es_documentos_entidad (id,id_es_documentos,id_es_entidad) "
            . "values (null," . $_POST["datosAEnviar"]["id_es_documentos"] . "," . $_POST["datosAEnviar"]["id_es_entidad"] . ");";
        if ($OB_clase_general->ejecutarInsertUpdateDelete($sentencia)) {
            $retorno["es_documentos_entidad"] = 1;
        }

        if (strlen($_POST["datosAEnviar"]["url"]) > 0) {
            $sentencia = "UPDATE es_documentos SET URL='" . $_POST["datosAEnviar"]["url"] . "' "
                . "WHERE id=" . $_POST["datosAEnviar"]["id_es_documentos"] . ";";
            $OB_clase_general->ejecutarInsertUpdateDelete($sentencia);
        }
    }

    echo json_encode($retorno);
}

if ($_POST["caso"] === '3') {

    $sentencia = "DELETE FROM es_documentos_entidad WHERE id=" . $_POST["datosAEnviar"]["id"] . ";";

    echo json_encode($OB_clase_general->ejecutarInsertUpdateDelete($sentencia));
}

if ($_POST["caso"] === '4') {

    $sentencia = "SELECT es_documentos_entidad.id,es_documentos.nombre AS nombreDocumento,es_entidad.nombre AS nombreEntidad,es_documentos.url "
        . "FROM es_documentos_entidad "
        . "INNER JOIN es_documentos ON es_documentos_entidad.id_es_documentos = es_documentos.id "
        . "INNER JOIN es_entidad ON es_documentos_entidad.id_es_entidad = es_entidad.id "
        . "WHERE es_documentos_entidad.id_es_entidad =" . $_POST["datosAEnviar"]["id_es_entidad"] . " "
        . "AND (es_documentos_entidad.id_es_entidad, es_documentos_entidad.id_es_documentos) "
        . "NOT IN (SELECT id_entidad, id_documentos FROM es_cu_estudio_documentos) "
        . "AND es_documentos_entidad.id_es_documentos NOT IN ( "
        . "SELECT es_cu_estudio_documentos.id_documentos "
        . "FROM es_estudio_entidad "
        . "INNER JOIN es_cu_estudio_documentos ON es_estudio_entidad.id_es_estudio = es_cu_estudio_documentos.es_estudio_id "
        . "WHERE es_estudio_entidad.documento = '" . $_POST["datosAEnviar"]["documento"] . "');";

    //echo $sentencia; 

    echo json_encode($OB_clase_general->retornar($sentencia));
}

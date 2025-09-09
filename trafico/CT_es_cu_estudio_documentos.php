<?php

include_once '../clases/CL_clase_general.php';
include_once '../varios_php/varios.php';

$OB_clase_general = new CL_clase_general();
$retorno = array();

if ($_POST["caso"] === '1') {

    if (validarExtensionArchivo($_FILES["fl1_" . $_POST["id_es_documentos_entidad"]]["name"])) {

        $id_estudio = $_POST["id_estudio"];

        $sentencia = "SELECT * FROM es_estudio WHERE id=" . $id_estudio . ";";
        $retorno["es_estudio"] = $OB_clase_general->retornar($sentencia);

        if (empty($retorno["es_estudio"])) {

            $sentencia = "INSERT INTO es_estudio (id,fecha,empleado,id_es_nombres_estados) "
                . "values (null,'" . date("Y-m-d") . "'," . $_POST["cedula_empleado"] . ",1);";

            $id_estudio = intval($OB_clase_general->retornarUltimoIdCreado($sentencia));

            $retorno["id_estudio"] = $id_estudio;

            $sentencia = "SELECT * FROM es_estudio WHERE id=" . $id_estudio . ";";
            $retorno["es_estudio"] = $OB_clase_general->retornar($sentencia);

            $sentencia = "INSERT INTO es_estudio_entidad (id,id_es_entidad,id_es_estudio,documento) "
                . "values (id," . $_POST["id_es_entidad"] . "," . $id_estudio . ",'" . $_POST["documento"] . "');";

            $id_es_estudio_entidad = intval($OB_clase_general->retornarUltimoIdCreado($sentencia));

            $sentencia = "SELECT * FROM es_estudio_entidad WHERE es_estudio_entidad.id_estudio=" . $id_estudio . ";";
            $retorno["es_estudio_entidad"] = $OB_clase_general->retornar($sentencia);

            $sentencia = "SELECT * FROM es_documentos_entidad WHERE id=" . $_POST["id_es_documentos_entidad"] . ";";
            $retorno["es_documentos_entidad"] = $OB_clase_general->retornar($sentencia);

            $id_es_documentos = $retorno["es_documentos_entidad"][0]["id_es_documentos"];
            $id_es_entidad = $retorno["es_documentos_entidad"][0]["id_es_entidad"];

            var_dump($_FILES["fl1_" . $_POST["id_es_documentos_entidad"]]);
        }
    } else {
        $retorno["es_fotos_entidad"] = null;
    }

    echo json_encode($retorno);
}

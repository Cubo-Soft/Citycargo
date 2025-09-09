<?php

include '../clases/seguimiento.php';

$seguimiento = new seguimiento();

if ($_POST["caso"] === '1') {

    echo json_encode($seguimiento->crearSeguimiento($_POST, $_FILES));
    $seguimiento->verificarEstadoServicio($_POST["idservicio"]);
}

if ($_POST["caso"] === '2') {
    echo json_encode($seguimiento->retornarSeguimientoGuia($_POST["guia"]));
}

if ($_POST["caso"] === '3') {
    echo json_encode($seguimiento->cambiarEstadoSeguimiento($_POST["idseguimiento"],$_POST["idservicio"]));
}
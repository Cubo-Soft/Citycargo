<?php

include '../clases/calificaciones.php';

$calificaciones = new calificaciones();
$retorno = array();

if ($_POST["caso"] === '1') {    
    echo json_encode($calificaciones->crearCalificacion($_POST["arreglo"], $_POST["mismoPropietario"]));
}

if ($_POST["caso"] === '2') {
    echo json_encode($calificaciones->retornarCalificacionesPlaca($_POST["placa"]));
}

if ($_POST["caso"] === '3') {
    echo json_encode($calificaciones->retornarCalificacionesPropietario($_POST["cedulaPropietario"]));
}

if ($_POST["caso"] === '4') {
    echo json_encode($calificaciones->retornarCalificacionesConductor($_POST["cedulaConductor"]));
}

if ($_POST["caso"] === '5') {
    echo json_encode($calificaciones->retornarCalificacionCedula($_POST["cedula"]));
}
<?php

include '../clases/asesor_empresa.php';

$asesor = new asesor_empresa();

if ($_POST["opcion"] === '1') {
    echo json_encode($asesor->retornarAsesorEmpresa($_POST["nit"]));
}

if ($_POST["opcion"] === '2') {
    $id = $asesor->retornarNitActual($_POST["nit"], $_POST["cedula"]);
    if (count($id) === 0) {
        echo json_encode($asesor->agregarAsesorEmpresa($_POST["cedula"], $_POST["nit"]));
    } else {
        echo json_encode($asesor->cambiarAsesorEmpresa($_POST["cedula"], $id[0]["id"]));
    }
}

if ($_POST["opcion"] === '3') {
    echo json_encode($asesor->retornarDirecciones($_POST["nit"], $_POST["tipo"],0));
}

if ($_POST["opcion"] === '4') {
    echo json_encode($asesor->retornarDireccion($_POST["iddireccion"]));
}

if ($_POST["opcion"] === '5') {
    echo json_encode($asesor->crearDireccion($_POST["documento"], $_POST["telefono"], $_POST["direccion"], $_POST["ciudad"], $_POST["tipo"],0));
}

if ($_POST["opcion"] === '6') {
    echo json_encode($asesor->retornarEmpresa($_POST["nit"]));
}

if ($_POST["opcion"] === '7') {
    echo json_encode($asesor->crearDireccion($_POST["documento"], $_POST["telefono"], $_POST["direccion"], $_POST["ciudad"], $_POST["tipo"],1));
}
<?php

include '../clases/servicios.php';

$servicios = new servicios();

$detencion = $_POST["detencion"];
$numeroServicio = $_POST["numeroServicio"];
$placa = $_POST["placa"];
$cedulaPropietario = $_POST["cedulaPropietario"];
$cedulaConductor = $_POST["cedulaConductor"];
$direccionOrigen = $_POST["direccionOrigen"];
$telefonoOrigen = $_POST["telefonoOrigen"];
$ciudadOrigen = $_POST["ciudadOrigen"];
$fecha =$_POST["fecha"];

if ($_POST["variasEmpresas"] === '1') {

    $guia = $_POST["guia"];
    $planilla = $_POST["planilla"];
    $auxiliar = $_POST["auxiliar"];
    $nitEmpresa = $_POST["nitEmpresa"];
    $direccionDestino = $_POST["direccionDestino"];
    $telefonoDestino = $_POST["telefonoDestino"];
    $ciudadDestino = $_POST["ciudadDestino"];
    echo json_encode($servicios->crearServicioVarios($detencion, $numeroServicio, $placa, $cedulaPropietario, $cedulaConductor, $direccionOrigen, $telefonoOrigen, $ciudadOrigen, $guia, $planilla, $auxiliar, $nitEmpresa, $direccionDestino, $telefonoDestino, $ciudadDestino,$fecha));
} else {

    $guia = $_POST["guia"];
    $planilla = $_POST["planilla"];
    $auxiliar = $_POST["auxiliar"];
    $nitEmpresa = $_POST["nitEmpresa"];
    $direccionDestino = $_POST["direccionDestino"];
    $telefonoDestino = $_POST["telefonoDestino"];
    $ciudadDestino = $_POST["ciudadDestino"];
    $valorContratista = $_POST["valorContratista"];
    $auxiliarAyudante = $_POST["auxiliarAyudante"];
    $parqueadero = $_POST["parqueadero"];
    $otros = $_POST["otros"];
    $costoServicio = $_POST["costoServicio"];
    $valorFacturar = $_POST["valorFacturar"];
    
    echo json_encode($servicios->crearServiciosVariosEmpresas($detencion, $numeroServicio, $placa, $cedulaPropietario, $cedulaConductor, $direccionOrigen, $telefonoOrigen, $ciudadOrigen, $guia, $planilla, $auxiliar, $nitEmpresa, $direccionDestino, $telefonoDestino, $ciudadDestino, $valorContratista, $auxiliarAyudante, $parqueadero, $otros, $costoServicio, $valorFacturar,$fecha));
}
        








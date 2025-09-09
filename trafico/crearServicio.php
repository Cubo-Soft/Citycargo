<?php

include '../clases/servicios.php';

$planilla = $_POST["planilla"];
$idservicio = $_POST["numeroServicio"];
$idempleado = $_POST["idempleado"];
$fecha = date('Y-m-d h:m:s');
$idcliente = $_POST["cli_documento"];
$placa = $_POST["placa"];
$auxiliar = $_POST["auxiliar"];
if ($_POST["guia"] === '') {
    $guia = 0;
} else {
    $guia = $_POST["guia"];
}
$direccionorigen = $_POST["direccionorigen"];
$telefonoorigen = $_POST["telefonoorigen"];
if (empty($telefonoorigen)) {
    $telefonoorigen = 0;
}
$idciudadorigen = $_POST["idciudadorigen"];
$direcciondestino = $_POST["direcciondestino"];
$telefonodestino = $_POST["telefonodestino"];
if (empty($telefonodestino)) {
    $telefonodestino = 0;
}
$idciudaddestino = $_POST["idciudaddestino"];
$cedulapropietario = $_POST["cedulaPropietario"];
$cedulaconductor = $_POST["cedulaConductor"];
$valortotal = $_POST["valortotal"];

if ($valortotal < 0) {
    $valortotal = 0;
}

$valorapagar = $_POST["valorapagar"];

$opcion = $_POST["evento"];

$servicio = new servicios();

$a["creacionServicio"] = $servicio->modificarServicio($idservicio, $idempleado, $fecha, $idcliente, $placa, $guia, $planilla, $auxiliar, $direccionorigen, $telefonoorigen, $idciudadorigen, $direcciondestino, $telefonodestino, $idciudaddestino, $cedulapropietario, $cedulaconductor, $valortotal, $valorapagar, $opcion);

$control=0;

if ($_POST["auxiliarCarga"] > 0) {
    $b = $servicio->crearSubvalores($idservicio, $_POST["auxiliarCarga"], 'AUXILIAR', $opcion);    
}

if ($_POST["parqueadero"] > 0) {
    $b = $servicio->crearSubvalores($idservicio, $_POST["parqueadero"], 'PARQUEADERO', $opcion);
}

if ($_POST["otros"] > 0) {
    $b = $servicio->crearSubvalores($idservicio, $_POST["otros"], 'OTROS', $opcion);
}

echo json_encode(array($a));

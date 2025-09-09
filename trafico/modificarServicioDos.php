<?php

include '../clases/servicios.php';

$servicio = new servicios();

$auxiliarcarga = $_POST["auxiliarcarga"];
$parqueadero = $_POST["parqueadero"];
$otros = $_POST["otros"];
$valortotal = $_POST["valortotal"];
$valorapagar = $_POST["valorapagar"];
$valorfacturar = $_POST["valorfacturar"];
$idservicio = $_POST["idservicio"];

echo json_decode($servicio->modificarServicioDos($idservicio, $auxiliarcarga, $parqueadero, $otros, $valortotal, $valorapagar, $valorfacturar));

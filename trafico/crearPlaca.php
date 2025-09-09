<?php

include '../clases/vehiculo.php';

$resulta=null;
$placa=$_POST["placa"];

$vehiculo=new vehiculos();
echo json_encode($vehiculo->crearPlaca($placa, 'ACTIVO'));
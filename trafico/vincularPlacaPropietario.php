<?php

include '../clases/conductores.php';

$placa=$_POST["placa"];
$cedula=$_POST["cedula"];

$conductor= new conductores();
echo $conductor->vincularPlacaPropietario($placa, $cedula);
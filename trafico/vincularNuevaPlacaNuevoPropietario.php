<?php

include '../clases/conductores.php';
//
$placa=$_POST["placa"];
$cedula=$_POST["cedula"];
$nombre=$_POST["nombre"];
//
$conductor = new conductores();
echo $conductor->vincularNuevaPlacaNuevoPropietario($placa, $cedula, $nombre);
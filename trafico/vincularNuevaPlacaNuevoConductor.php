<?php

include '../clases/conductores.php';

$cedula=$_POST["cedula"];
$placa=$_POST["placa"];
$nombre=$_POST["nombre"];

$conductor=new conductores();
echo $conductor->vincularNuevaPlacaNuevoCondcutor($cedula, $placa, $nombre);
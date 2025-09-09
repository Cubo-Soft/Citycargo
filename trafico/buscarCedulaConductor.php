<?php

include '../clases/conductores.php';

$identificacion=$_POST["cedula"];
$conductor=new conductores();
echo $conductor->buscarPropietario($identificacion,2);
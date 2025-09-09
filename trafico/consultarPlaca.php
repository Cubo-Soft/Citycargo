<?php

include '../clases/vehiculo.php';
include '../clases/funcionesVarias.php';

/* 
 * 20171201
 * Consulta si la placa existe o no
 */

$vehiculo=new vehiculos();
$placa= limpiarVariable($_POST["placa"]);

echo $vehiculo->retornarEstadoPlaca($placa);
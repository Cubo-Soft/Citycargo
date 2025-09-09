<?php

include '../clases/cliente.php';

$nombreEmpresa=$_POST["nombreEmpresa"];
$cliente = new cliente();

$cliente->verificarNombreEmpresa($nombreEmpresa);
<?php

include '../clases/servicios.php';

$numeroServicio=$_POST["numeroServicio"];

$servicio=new servicios();
echo $servicio->verificarEstadoNumeroServicio($numeroServicio);


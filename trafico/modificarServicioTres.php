<?php

include '../clases/servicios.php';

$servicio=new servicios();
echo json_encode($servicio->modificarServicioTres($_POST["numeroServicio"], $_POST["variasEmpresas"]));
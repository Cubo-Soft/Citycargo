<?php

include '../clases/servicios.php';

$servicio=new servicios();
echo json_encode($servicio->cambiarCedulaServicio($_POST["cedula"], $_POST["servicio"]));

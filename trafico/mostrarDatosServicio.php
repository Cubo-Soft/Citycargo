<?php

include '../clases/servicios.php';

$servicio=new servicios();
echo json_encode($servicio->mostrarDatosServicio($_POST["idservicio"]));
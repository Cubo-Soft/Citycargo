<?php

include '../clases/servicios.php';

$servicio=new servicios();
echo json_encode($servicio->reasignarServicio($_POST["idservicio"], $_POST["placa"],$_POST["cedulaPropietario"]));
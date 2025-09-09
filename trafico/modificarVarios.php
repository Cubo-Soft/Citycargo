<?php

include '../clases/servicios.php';

$servicio=new servicios();
echo json_encode($servicio->modificarVarios($_POST["idservicio"], $_POST["valor"], $_POST["detalle"]));
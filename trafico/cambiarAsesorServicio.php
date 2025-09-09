<?php

include '../clases/servicios.php';

$servicio=new servicios();
echo json_encode($servicio->cambiarAsesorServicio($_POST["idservicio"], $_POST["idempleado"]));

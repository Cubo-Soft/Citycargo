<?php

include '../clases/servicios.php';

$servicio=new servicios();
echo json_encode($servicio->modificarComentarioServicio($_POST["idservicio"], $_POST["comentario"]));
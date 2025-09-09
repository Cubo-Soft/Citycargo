<?php

include '../clases/servicios.php';

$servicio = new servicios();
echo json_encode($servicio->crearValorDeclarado($_POST["guia"], $_POST["valorDeclarado"], $_POST["idservicio"]));


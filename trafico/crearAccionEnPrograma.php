<?php

include '../clases/accionesenprograma.php';

$accion=new accionesenprograma();
echo json_decode($accion->crearAccion($_POST["boton"], date("Y-m-d h:m:s"), $_POST["idempleado"]));
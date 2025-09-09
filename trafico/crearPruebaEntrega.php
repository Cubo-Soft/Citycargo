<?php

include '../clases/servicios.php';

$servicio=new servicios();
echo json_encode($servicio->crearPruebaEntrega($_POST["guia"]));


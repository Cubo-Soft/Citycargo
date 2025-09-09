<?php

include '../clases/servicios.php';

$servicio=new servicios();
echo json_encode($servicio->cambiarFactura($_POST["factura"], $_POST["idservicio"]));
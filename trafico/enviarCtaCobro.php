<?php

include '../clases/servicios.php';

$servicio=new servicios();
echo json_encode($servicio->enviarCtaCobro($_POST["servicio"], $_POST["variasEmpresas"]));
$servicio->equivocacionPosibleAnticipo($_POST["servicio"]);

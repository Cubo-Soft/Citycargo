<?php

include '../clases/conductores.php';

$conductor=new conductores();
$resultado=$conductor->retornarPlacaConductor($_POST["identificacion"]);
echo json_encode($resultado);
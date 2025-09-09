<?php

include '../clases/servicios.php';

$servicio=new servicios(); 
$resultado["resultado"]=$servicio->borrarSubValor($_POST["idvalor"]);
echo json_encode($resultado);
//var_dump($_POST);
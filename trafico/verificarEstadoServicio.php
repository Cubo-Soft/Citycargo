<?php

include '../clases/servicios.php';

$servicio = new servicios();

switch ($_POST["condicion"]) {
    case 1:        
        echo $servicio->verificarEstadoNumeroServicio($_POST["idservicio"]);
}


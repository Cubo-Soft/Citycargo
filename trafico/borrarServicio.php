<?php

include '../clases/servicios.php';

$numeroServicio = $_POST["numeroServicio"];
$opcion = intval($_POST["opcion"]);
$servicio = new servicios();

if (isset($_POST["guia"])) {
    $guia = $_POST["guia"];
} else {
    $guia = $_POST["motivo"];
}

if ($opcion === 0) {
    echo json_encode($servicio->cancelarServicio($numeroServicio, $guia, 0, $motivo));
} else {
    echo json_encode($servicio->cancelarServicio($numeroServicio, $guia, 1, $motivo));
}
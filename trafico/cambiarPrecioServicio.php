<?php

include '../clases/servicios.php';

$servicio = new servicios();

if ($_POST["opcion"] === '0') {
    echo json_decode($servicio->cambiarPrecioServicio($_POST["valor"], $_POST["idservicio"], 0));
}


if ($_POST["opcion"] === '1') {
    echo json_decode($servicio->cambiarPrecioServicio($_POST["valor"], $_POST["idservicio"], 1));
}

if ($_POST["opcion"] === '2') {
    echo json_decode($servicio->cambiarPrecioServicio($_POST["valor"], $_POST["guia"], 2));
}

if ($_POST["opcion"] === '3') {
    echo json_decode($servicio->cambiarPrecioServicio($_POST["valor"], $_POST["guia"], 3));
}
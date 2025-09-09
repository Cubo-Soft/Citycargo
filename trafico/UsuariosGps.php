<?php

include '../clases/usuariosGps.php';

$usuarioGPS = new usuariosGps();

if ($_POST["caso"] === '1') {
    echo json_decode($usuarioGPS->cambiarCrearOperadorGPS($_POST["placa"], $_POST["operador"]));
}

if ($_POST["caso"] === '2') {
    echo json_decode($usuarioGPS->cambiarCrearOperadorGPS($_POST["placa"], $_POST["operador"]));
}

if ($_POST["caso"] === '3') {
    echo json_decode($usuarioGPS->cambiarCrearUsuarioGPS($_POST["placa"], $_POST["usuario"]));
}

if ($_POST["caso"] === '4') {
    echo json_decode($usuarioGPS->cambiarCrearClaveGPS($_POST["placa"], $_POST["clave"]));
}
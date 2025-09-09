<?php

include '../clases/direcciones.php';

$direccion = new direcciones();

switch ($_POST["caso"]) {
    case '1':
        echo json_encode($direccion->retornarDireccionesCliente($_POST["nit"], $_POST["tipo"], $_POST["estado"]));
        break;

    case '2':
        echo json_encode($direccion->cambiarEstadoDireccion($_POST["iddireccion"], $_POST["estado"]));
        break;
    case '3':
        echo json_encode($direccion->crearDireccion($_POST["telefono"], $_POST["direccion"], $_POST["idciudad"], $_POST["nit"], $_POST["tipo"]));
        break;
    case '4':
        echo json_encode($direccion->retornaDirPorCiuCli($_POST["nit"], $_POST["idciudad"]));
        break;
}
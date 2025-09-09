<?php

include_once '../clases/servicioguias.php';

$servicio_guias = new servicioguias();

if ($_POST["caso"] === '1') {
    echo json_encode($servicio_guias->cambiarValorManejo($_POST["numeroGuia"], $_POST["valorManejo"]));
}

if ($_POST["caso"] === '2') {
    echo json_encode($servicio_guias->cambiarPlanilla($_POST["planilla"], $_POST["guia"]));
}

if ($_POST["caso"] === '3') {
    echo json_encode($servicio_guias->cambiarRemision($_POST["remision"], $_POST["guia"]));
}

if ($_POST["caso"] === '4') {
    echo json_encode($servicio_guias->cambiarFacturaCliente($_POST["factura"], $_POST["guia"]));
}

if ($_POST["caso"] === '5') {
    echo json_encode($servicio_guias->cambiarOrdenCompra($_POST["ordenCompra"], $_POST["guia"]));
}

if($_POST["caso"]==='6'){
    echo json_encode($servicio_guias->retornarValorTotal($_POST["guia"]));
}
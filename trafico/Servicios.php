<?php

include '../clases/servicios.php';

$servicio = new servicios();

if ($_POST["caso"] === '1') {
    echo json_encode($servicio->cambiarEstadoCtaCobro($_POST["idservicio"]));
}

if ($_POST["caso"] === '2') {
    echo json_encode($servicio->cambiarGuia($_POST["guiaNueva"], $_POST["guiaAnterior"], $_POST["idservicio"]));
}

if ($_POST["caso"] === '3') {
    echo json_encode($servicios->eliminarServicioVariasGuias($_POST["id"], $_POST["idServicio"]));
}

if ($_POST["caso"] === '4') {   
   echo json_encode($servicio->crearServicioTres($_POST));
}

if ($_POST["caso"] === '5') {
    echo json_encode($servicio->retornarServicioPorIdServicio($_POST["idservicio"]));
}

if ($_POST["caso"] === '6') {
    echo json_encode($servicio->retornarNumeroServicioPorGuia($_POST["guia"]));
}

if ($_POST["caso"] === '7') {
    echo json_encode($servicio->retornarNumeroServicioPorAnticipo($_POST["anticipo"]));
}

if ($_POST["caso"] === '8') {
    echo json_encode($servicio->cambiarValorACobrar($_POST["guia"], $_POST["valor"], $_POST["idservicio"]));
}

if ($_POST["caso"] === '9') {
    echo json_encode($servicio->cambiarValorAPagar($_POST["guia"], $_POST["valor"], $_POST["idservicio"]));
}

if ($_POST["caso"] === '10') {
    echo json_encode($servicio->cambiarValorAuxiliar($_POST["guia"], $_POST["valor"], $_POST["idservicio"]));
}

if ($_POST["caso"] === '11') {
    echo json_encode($servicio->cambiarValorParqueadero($_POST["guia"], $_POST["valor"], $_POST["idservicio"]));
}

//if ($_POST["caso"] === '12') {
//    echo json_encode($servicio->cambiarValorOtros($_POST["guia"], $_POST["valor"], $_POST["idservicio"]));
//}

if ($_POST["caso"] === '13') {
    echo json_encode($servicio->cambiarNotasGuia($_POST["guia"], $_POST["valor"], $_POST["idservicio"]));
}

if ($_POST["caso"] === '14') {
    echo json_encode($servicio->cancelarServicio($_POST["idservicio"], 0, 2, $_POST["motivo"]));
}

    //if ($_POST["caso"] === '15') {
    //    echo json_encode($servicio->retornarServiciosPorNumeroFactura($_POST["factura"]));
    //}

if ($_POST["caso"] === '16') {
    echo json_encode($servicio->cambiarFactura($_POST["factura"], $_POST["idservicio"],$_POST["guia"]));
}

if ($_POST["caso"] === '17') {
    echo json_encode($servicio->cambiarEstadoCtaCobro($_POST["idservicio"]));
}

if ($_POST["caso"] === '18') {
    $retorno = array();
    $datosServicioPorGuia = $servicio->retornarServicioPorGuia($_POST["guia"]);
    echo json_encode($servicio->retornarServicioPorIdServicio($datosServicioPorGuia[0]["idservicio"]));    
}

if($_POST["caso"]==='19'){    
    $arreglo["idservicio"]=$_POST["datos"]["idservicio"];
    $arreglo["guia"]=$_POST["datos"]["guia"];
    echo json_encode($servicio->retornarUsuarioFechaServicio($arreglo));
}
<?php

include '../clases/procesosGuias.php';
include '../clases/funcionesVarias.php';

$procesosGuias = new procesosGuias();

if ($_POST["caso"] === '1') {

    $datos = json_decode(stripslashes($_POST['numeroGuia']));

    for ($a = 0; $a < count($datos); $a++) {

        if (!empty($datos[$a])) {
            $procesosGuias->cambiarGuias($_POST["documento"], $datos[$a]);
        }
    }

    $procesosGuias->retornarGuiasPendientes($_POST["documento"]);
}

if ($_POST['caso'] === '2') {
    $procesosGuias->retornarGuiasPendientes($_POST["documento"]);
}

/*
 * 2021/05/12 Por ahora no se necesita ésta función   
 */
//if ($_POST['caso'] === '3') {
//    $documento = $procesosGuias->pagarGuias($_POST["numeroGuia"], $fechaPagada, 2);
//    $procesosGuias->retornarGuiasPendientes($documento[0]["identificacion"]);
//}
if ($_POST['caso'] === '4') {    
    $procesosGuias->retornarGuiasPendientes($_POST);
}

if ($_POST['caso'] === '5') {
    $documento = limpiarVariable($_POST["documento"]);
    $fecha = $_POST["fecha"];
    $numeroInicial = intval(limpiarVariable($_POST["numeroInicial"]));
    $numeroFinal = intval(limpiarVariable($_POST["numeroFinal"]));

    $numeroGuia = $numeroInicial;
    $retorno = 0;
    $vueltas = 0;
    $a = array();

    for ($numeroGuia; $numeroGuia <= $numeroFinal; $numeroGuia++) {
        $vueltas += 1;        
        if ($procesosGuias->ingresarGuias($documento, $numeroGuia, $_POST["nombreEmpresa"]) === 1) {
            $retorno += 1;
        }
    }   
    
    if ($retorno === $vueltas) {
        echo json_encode($a["mensaje"] = 1);
    } else {
        echo json_encode($a["mensaje"] === 0);
    }
}

if ($_POST['caso'] === '6') {
    $procesosGuias->retornarGuiaExistente($_POST["numeroInicial"]);
}

if ($_POST['caso'] === '7') {
    $procesosGuias->retornarUltimaGuia();
}

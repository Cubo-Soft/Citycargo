<?php

session_start();

include '../clases/procesosGuias.php';

$guia = new procesosGuias();

if ($_POST["decision"] === '1') {

    //$resultado = $guia->verificarGuia($_POST["guia"], $_POST["idempleado"]);
    //if ($resultado === false) {
    $resultado = $guia->verificarGuiaServicio($_POST["guia"], $_POST["idempleado"]);
    //}

    if ($resultado === false) {
        echo json_encode(false);
    } else {
        echo json_encode($resultado);
    }
}

if ($_POST["decision"] === '2') {
    $resultado = $guia->ingresarGuias($_SESSION['emp_cedula'], date('Y-m-d H:m:s'), $_POST["guia"]);
    if ($resultado) {
        echo json_encode(array('resultado' => '1'));
    } else {
        echo json_encode(array('resultado' => '0'));
    }
}

/*
     * 20210512 
     * Se dejó de usar por bastantes repeticiones en los mensajes a gerencia
     * Se estaba llamando en ../js/js_serviciosVariosTres.js 
 * por parte de la function crearGuiaPorCancelar(guia, idservicio) {
     
if ($_POST["decision"] === '3') {
    echo json_encode($guia->crearGuiaPorCancelar($_POST["idservicio"], $_SESSION['emp_cedula'], $_POST["guia"]));
}
 * 
 */
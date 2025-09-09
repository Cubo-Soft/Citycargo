<?php

session_start();

include_once '../clases/otrosCostos.php';

$otrosCostos = new otrosCostos();

if ($_POST["caso"] === '1') {
    echo json_encode($otrosCostos->crearCosto($_POST["idservicio"], $_POST["numeroGuia"], $_POST["concepto"], $_POST["valor"], $_SESSION["emp_cedula"], date("Y-m-d h:m:s")));
}

if ($_POST["caso"] === '2') {
    echo json_encode($otrosCostos->consultarCostos($_POST["numeroGuia"]));
}

if($_POST["caso"]==='3'){
    echo json_encode($otrosCostos->borrarCosto($_POST["id"]));
}
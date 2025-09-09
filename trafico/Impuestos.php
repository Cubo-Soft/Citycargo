<?php

include_once '../clases/impuestos.php';

$impuestos = new impuestos();

if ($_POST["caso"] === '1') {
    echo json_encode($impuestos->modificarAnio($_POST["anio"]));
}

if($_POST["caso"]==='2'){
    echo json_encode($impuestos->modificarValor($_POST["id"],$_POST["valor"]));
}

if($_POST["caso"]==='3'){
    echo json_encode($impuestos->modificarBase($_POST["id"],$_POST["valor"]));
}
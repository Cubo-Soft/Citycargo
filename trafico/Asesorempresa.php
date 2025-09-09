<?php

include '../clases/asesor_empresa.php';

$asesorEmpresa = new asesor_empresa();

if ($_POST["caso"] === '1') {
    echo json_encode($asesorEmpresa->retornarDatosAgenda($_POST["cedula"], 2, $_POST["fechaInicial"], $_POST["fechaFinal"],''));
}

if($_POST["caso"]==='2'){
    echo json_encode($asesorEmpresa->retornarDatosAgenda($_POST["cedula"], 3, $_POST["fechaInicial"], $_POST["fechaFinal"],$_POST["nitEmpresa"]));
}
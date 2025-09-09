<?php

include '../clases/servicios.php';

$servicio = new servicios();

if ($_POST["condicion"] === '2') {
    echo json_encode($servicio->retornarServicioPorGuia($_POST["guia"]));
}

if ($_POST["condicion"] === '1') {
    echo json_encode($servicio->serviciosPorFechasEmpresa($_POST["fechaInicial"], $_POST["fechaFinal"], $_POST["nitEmpresa"]));
}

if($_POST["condicion"]==='3'){
    echo json_encode($servicio->serviciosPorNumeroCuentaCobro($_POST["numeroCuentaCobro"]));
}

if($_POST["condicion"]==='4'){
    echo json_encode($servicio->actualizarFechaTransferencia($_POST["numeroCuentaCobro"], $_POST["fechaTransferencia"]));
}
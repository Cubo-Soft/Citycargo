<?php

include_once '../clases/entregas.php';

$entregas = new entregas();

if ($_POST["caso"] === '1') {
    $insercion = 0;
    $vlr = 0;
    $valor = 0;
    for ($index = 0; $index < count($_POST["arregloEntregas"]); $index++) {
        $valor = str_replace('.', '', $_POST["arregloEntregas"][$index]["valor"]);
        $insercion+=$entregas->crearEntrega($_POST["arregloEntregas"][$index]["idservicio"], $_POST["arregloEntregas"][$index]["guia"], $_POST["arregloEntregas"][$index]["unidades"], $_POST["arregloEntregas"][$index]["guiaEntrega"], $_POST["arregloEntregas"][$index]["dlDirDes"], $_POST["arregloEntregas"][$index]["planilla"], $_POST["arregloEntregas"][$index]["remision"], $_POST["arregloEntregas"][$index]["factura"], $_POST["arregloEntregas"][$index]["ordenCompra"], 0, 0, 0, 0, 0, 0, $valor, $_POST["arregloEntregas"][$index]["Notas"], $_POST["arregloEntregas"][$index]["nit"]);
    }
    if (count($_POST["arregloEntregas"]) === $insercion) {
        echo json_encode(1);
    } else {
        echo json_encode(0);
        $entregas->borrarEntrega($_POST["arregloEntregas"][$index]["idservicio"]);
    }
}

if ($_POST["caso"] === '2') {
    echo json_encode($entregas->retornarEntregas($_POST["idservicio"]));
}

if ($_POST["caso"] === '3') {
    echo json_encode($entregas->borrarEntregas($_POST["idservicio"]));
}
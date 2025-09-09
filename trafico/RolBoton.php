<?php

include_once '../clases/rol_boton.php';

$rol_boton = new rol_boton();

if ($_POST["caso"] === '1') {
    $fechaInicial = $_POST["fechaInicial"];
    $fechaFinal = $_POST["fechaFinal"];
    $retorno[0] = count($rol_boton->retornarAnticiposPendientes(0, 0, $fechaInicial, $fechaFinal));
    $retorno[1] = $rol_boton->retornarAnticiposPendientes(1, 1, $fechaInicial, $fechaFinal);        
    echo json_encode($retorno);
}

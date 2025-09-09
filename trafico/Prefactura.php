<?php

session_start();

include_once '../clases/prefactura.php';

$prefactura = new prefactura();

if ($_POST["opcion"] === "1") {
    echo json_encode($prefactura->retornarDatosFactura($_POST["guia"]));
}

if ($_POST["opcion"] === "2") {

    $factura = $_POST["factura"];
    $guias = $_POST["guias"];
    $retorno = 0;
    $resultado = 0;

    $facRepetida = $prefactura->consultarFacturaServicio($factura);    
    
    for ($index = 0; $index < count($guias); $index++) {

        if (intval($guias[$index]) !== 0 || $guias[$index] !== '') {

            if (count($facRepetida) > 0) {
                $guiaAnterior = intval($facRepetida[0]["numeroguia"]);
                $facAnterior = intval($facRepetida[0]["factura"]);
                if ($facAnterior === intval($factura)) {
                    //20231219 se comenta para evitar que continue generando estos mensajes 
                    //$prefactura->crearMensajeFacturaRepetida($guiaAnterior, $guias[$index], $factura, $_SESSION["emp_cedula"]);
                }
            }            
            
            //echo $prefactura->modificarFactura($factura, $guias[$index]);
            
            if ($prefactura->modificarFactura($factura, $guias[$index])) {
                $resultado += 1;
            } else {
                $resultado = 0;
                $retorno = 0;
            }
        }
    }    
    
    if($resultado>0){
        echo json_encode(1);
    } else {
        echo json_encode(0);
    }    

}

if ($_POST["opcion"] === '3') {
    echo json_encode($prefactura->cambiarFacturaGrupo($_POST["servicios"], $_POST["facturaNueva"]));
}
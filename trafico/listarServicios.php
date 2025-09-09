<?php

include '../clases/servicios.php';

$servicio = new servicios();

$respuesta = null;

switch ($_POST["opcion"]) {
    case 1:
        $respuesta = $servicio->serviciosPorFechas($_POST["fechaInicial"], $_POST["fechaFinal"], 0, $_POST["opcion2"], null);
        break;
    case 2:
        $respuesta = $servicio->serviciosPorFechasAsesor($_POST["fechaInicial"], $_POST["fechaFinal"], $_POST["idasesor"]);
        break;
    case 3:
        $respuesta = $servicio->reporteUIAF($_POST["fechaInicial"], $_POST["fechaFinal"]);
        break;
    case 4:
        $respuesta = $servicio->retornarMunicipio($_POST["idmunicipio"]);
        break;
    case 5:
        $respuesta = $servicio->serviciosPorFechasEmpresa($_POST["fechaInicial"], $_POST["fechaFinal"], $_POST["nitEmpresa"]);
        break;
    case 6:
        $respuesta = $servicio->mostrarOtrosValores($_POST["idservicio"]);
        break;
    case 7:
        $respuesta = $servicio->serviciosPorFechas($_POST["fechaInicial"], $_POST["fechaFinal"], 1, $_POST["opcion2"], null);
        break;
    case 8:
        $respuesta = $servicio->serviciosPorFechas($_POST["fechaInicial"], $_POST["fechaFinal"], 2, $_POST["opcion2"], null);
        break;
    case 9:
        $respuesta = $servicio->agrupadoPorFactura($_POST["fechaInicial"], $_POST["fechaFinal"]);
        break;
    case 10:
        $respuesta = $servicio->serviciosPorFechas($_POST["fechaInicial"], $_POST["fechaFinal"], 0, 3, $_POST["placa"]);
        break;    
    case 11:
        $respuesta = $servicio->serviciosPorFechas($_POST["factura"], null, 3, 0, null);
        break;
    case 12:
        $respuesta=$servicio->retornarSeguimientosAsesor($_POST["fechaInicial"], $_POST["fechaFinal"], $_POST["idasesor"]);
        break;
    default:
        break;
}

echo json_encode($respuesta);


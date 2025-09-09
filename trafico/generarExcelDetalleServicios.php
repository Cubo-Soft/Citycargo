<?php

include_once '../clases/servicioguias.php';

$servicioguias = new servicioguias();

$guias = $_POST["guias"];

$factura = $_POST["factura"];
$nombreEmpresa = $_POST["nombreEmpresa"];
$nit = $_POST["nit"];
$telefono = $_POST["telefono"];
$direccion = $_POST["direccion"];

$tabla = "<!DOCTYPE html>"
        . "<html>"
        . "<body>"
        . "<table border='1px' id='tablaDetalleServicios'>"
        . "<thead>"
        . "<tr>"
        . "<th colspan='6' rowspan='2'><center><font size='+5' color='blue'>USA POSTAL S.A.</font></center>"
        . "<th colspan='15' align=center' >DETALLE SERVICIOS"
        . "<tr>"
        . "<th>CLIENTE<td colspan='6' >" . $nombreEmpresa . "<th colspan='5' ><th colspan='3' >N&Uacute;MERO DE FACTURA"
        . "<tr>"
        . "<th colspan='6'><font size='+3' color='red'>2555555</font>"
        . "<th>NIT<td colspan='6' >" . $nit . "<th colspan='3'>TEL&Eacute;FONO<td colspan='2'>" . $telefono . ""
        . "<th colspan='3' rowspan='2' >" . $factura . ""
        . "<tr>"
        . "<th colspan='6'> NIT: 830112988-3"
        . "<th>DIRECCI&Oacute;N<td colspan='6' >" . $direccion . "<th colspan='5' >"
        . "<tr>"
        . "<th rowspan='3'>FECHA<th rowspan='3'>GU&Iacute;A<th rowspan='3'>UNIDADES<th rowspan='3'>VALOR DECLARADO"
        . "<th colspan='2' style='text-align: center;'>ORIGEN<th colspan='2'>DESTINO"
        . "<th rowspan='3'>TIPO DE VEH&Iacute;CULO<th colspan='5'>ENTREGAS<th colspan='4'>DOCUMENTO CLIENTE"
        . "<th rowspan='3'>VALOR TRANSPORTE<th rowspan='3'>VALOR MANEJO<th rowspan='3'>VALOR TOTAL"
        . "<tr>"
        . "<th rowspan='2'>CIUDAD"
        . "<th rowspan='2'>DIRECCI&Oacute;N"
        . "<th rowspan='2'>CIUDAD"
        . "<th rowspan='2'>DIRECCI&Oacute;N"
        . "<th rowspan='2'>GU&Iacute;A"
        . "<th rowspan='2'>UNIDADES"
        . "<th rowspan='2'>VALOR DECLARADO"
        . "<th colspan='2' >DESTINO"
        . "<th rowspan='2' >PLANILLA"
        . "<th rowspan='2' >REMISI&Oacute;N"
        . "<th rowspan='2' >FACTURA"
        . "<th rowspan='2' >ORDEN DE COMPRA"
        . "<tr>"
        . "<th>CIUDAD"
        . "<th>DIRECCI&Oacute;N"
        . "<tbody>";

$datos = array();
$datosGuias = array();
$servicioRetorno = array();
$entregas = array();
$tipoVehiculo = array();
$tipVeh = null;
$planilla = null;
$remision = null;
$facturaCliente = null;
$ordeCompra = null;
$valorTransporte = null;
$valorManejo = null;
$valorTotal = null;
$totalManejo = null;
$totalTransporte = null;
$totalGeneral = null;

$cantidadDatos=0;

for ($index = 0; $index < count($guias); $index++) {
        
    if (intval($guias[$index]) !== 0 || $guias[$index] !== '') {

        $datos = $servicioguias->retornarDatosGuia($guias[$index]);
        
        if(count($datos)>0){
            $cantidadDatos+=1;
        }
        
        $datosGuias = $datos["datosGuias"];
        //var_dump($datosGuias);
        $servicioRetorno = $datos["servicio"];
        //var_dump($servicioRetorno);
        $entregas = $datos["entregas"];
        $tipoVehiculo = $datos["tipoVehiculo"];
        //var_dump($tipoVehiculo);

        for ($index1 = 0; $index1 < count($datosGuias); $index1++) {

            if ($tipoVehiculo[0]["tipovehiculo"] === '') {
                $tipVeh = '';
            } else {
                $tipVeh = $tipoVehiculo[0]["tipovehiculo"];
            }

            $tabla .= "<tr>"
                    . "<td>" . $servicioRetorno[$index1]["fechaServicio"] . "</td>"
                    . "<td>" . $datosGuias[$index1]["numeroGuia"] . "</td>"
                    . "<td>" . $datosGuias[$index1]["unidades"] . "</td>"
                    . "<td>" . number_format($datosGuias[$index1]["valorDeclarado"], 2) . "</td>"
                    . "<td>" . $datosGuias[$index1]["ciudadOrigen"] . "</td>"
                    . "<td>" . $datosGuias[$index1]["direccionOrigen"] . "</td>"
                    . "<td>" . $datosGuias[$index1]["ciudadDestino"] . "</td>"
                    . "<td>" . $datosGuias[$index1]["direccionDestino"] . "</td>"
                    . "<td>" . $tipVeh . "</td>";

            $planilla = $datosGuias[$index1]["planilla"];
            $remision = $datosGuias[$index1]["remision"];
            $factura = $datosGuias[$index1]["factura"];
            $ordeCompra = $datosGuias[$index1]["ordenCompra"];
            $valorManejo = $datosGuias[$index1]["valorManejo"];
            $valorTransporte = $datosGuias[$index1]["valorCobrado"];

            if (count($entregas) > 0) {

                //var_dump($entregas);

                for ($index2 = 0; $index2 < count($entregas); $index2++) {

                    $planilla = $entregas[$index2]["planilla"];
                    $remision = $entregas[$index2]["remision"];
                    $factura = $entregas[$index2]["factura"];
                    $ordenCompra = $entregas[$index2]["ordenCompra"];
                    $valorTransporte = $entregas[$index2]["valorCobrado"];
                    $valorManejo = $entregas[$index2]["valorManejo"];

                    if ($valorManejo > 0) {
                        $valorManejo = ($entregas[$index2]["valorCobrado"] * $valorManejo) / 100;
                        $vlrTransporte = $valorTransporte + $valorManejo;
                        $totalTransporte = $totalTransporte + $vlrTransporte;
                    } else {
                        $totalTransporte = $totalTransporte + $valorTransporte;
                        $vlrTransporte = $valorTransporte;
                    }

                    $tabla .= "<tr>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td></td>"
                            . "<td>" . $entregas[$index2]["guiaEntrega"] . "</td>"
                            . "<td>" . $entregas[$index2]["unidades"] . "</td>"
                            . "<td>" . number_format($entregas[$index2]["valorDeclarado"], 2) . "</td>"
                            . "<td>" . $entregas[$index2]["ciudadDestino"] . "</td>"
                            . "<td>" . $entregas[$index2]["direccionDestino"] . "</td>"
                            . "<td>" . $planilla . "</td>"
                            . "<td>" . $remision . "</td>"
                            . "<td>" . $factura . "</td>"
                            . "<td>" . $ordenCompra . "</td>"
                            . "<td>" . number_format($valorTransporte, 2) . "</td>"
                            . "<td>" . number_format($valorManejo, 2) . "</td>"
                            . "<td>" . number_format($vlrTransporte, 2) . "</td>"
                            . "</tr>";
                }
            } else {

                if ($valorManejo > 0) {
                    $valorManejo = ($datosGuias[$index1][17] * $datosGuias[$index1][1]) / 100;
                    $vlrTransporte = $valorTransporte + $valorManejo;
                    $totalTransporte = $totalTransporte + $vlrTransporte;
                } else {
                    $totalTransporte = $totalTransporte + $valorTransporte;
                    $vlrTransporte = $valorTransporte;
                }

                $tabla .= "<td></td>"
                        . "<td></td>"
                        . "<td></td>"
                        . "<td></td>"
                        . "<td></td>"
                        . "<td>" . $planilla . "</td>"
                        . "<td>" . $remision . "</td>"
                        . "<td>" . $factura . "</td>"
                        . "<td>" . $ordeCompra . "</td>"
                        . "<td>" . number_format($valorTransporte, 2) . "</td>"
                        . "<td>" . number_format($valorManejo, 2) . "</td>"
                        . "<td>" . number_format($vlrTransporte, 2) . "</td>"
                        . "</tr>";
            }
        }
    }
}

//echo $cantidadDatos;

$tabla .= "<tr>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td></td>"
        . "<td>" . number_format($totalTransporte, 2) . "</td>"
        . "</tr>"
        . "</body>"
        . "</html>";

echo $tabla;

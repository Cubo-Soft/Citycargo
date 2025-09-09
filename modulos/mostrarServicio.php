<?php
session_start();

include_once '../clases/funcionesVarias.php';

if (@$_GET["idservicio"] === null || $_GET["idservicio"] === '') {

    if (@$_POST["guia"] !== '0') {

        include '../clases/servicios.php';
        $servicio = new servicios();
        $datos = $servicio->retornarServicioPorGuia(@$_POST["guia"]);

        if (@$datos[0]["idservicio"] !== null || @$datos[0]["idservicio"] > 0) {
            header('Location: mostrarServicio.php?idservicio=' . $datos[0]["idservicio"]);
        }
    }
    ?>
    <!DOCTYPE html>
    <!--
    To change this license header, choose License Headers in Project Properties.
    To change this template file, choose Tools | Templates
    and open the template in the editor.
    -->
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Mostrar servicios</title>
            <link rel="icon" href="../imagenes/favicon.ico">

            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        

            <script src="../js/js_mostrarServicio.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script> 
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>    
            <script src="../js/jquery.number.min.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">            
        </head>
        <body>
            <form method="post" action="../modulos/mostrarServicio.php" >
                <input type="hidden" id="idempleado" name="idempleado" value="<?= $_SESSION["emp_cedula"] ?>" /> 
                <input type="hidden" id="departamento" name="departamento" value="<?= $_SESSION["departamento"] ?>" />
                <div id="contenedor">
                    <div id="contenedor-index" class="row input-sm">                       
                        <div id="divImagenUsa" class="col-sm-4 input-sm"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-sm-4 input-sm">
                            <h1>Mostrar servicios</h1>
                        </div>                                        
                        <div id="divDatosIniciales" class="col-sm-4 input-sm">   
                            <ul class="list-group">
                                <li class="list-group-item ">
                                    <span class="badge"><?= $_SESSION["nombre_usuario"]; ?></span>
                                    Usuario
                                </li>
                                <li class="list-group-item ">
                                    <span class="badge"><?= $_SESSION["departamento"]; ?></span>
                                    Departamento
                                </li>                                        
                                <li class="list-group-item ">
                                    <span class="badge"><?= date('Y-m-d'); ?></span>
                                    Fecha
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div id="contenedor-index2" class="panel panel-success">
                        <div class="panel-heading">                                        
                            <h3 class="panel-title">Datos servicio</h3>
                        </div>                        
                        <div class="panel-body" id="divServicio">
                            <div class='col-lg-12'>
                                <div class='col-lg-3' >
                                    <h4>N&uacute;mero de gu&iacute;a a consultar</h4>
                                </div>
                                <div class='col-lg-2' >
                                    <input type='number' name='guia' id='guia' value="<?= $guia; ?>" class='form-control form-control-sm' >
                                </div>              
                                <div class="col-lg-7"></div>
                            </div>  
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="panel-body">
                            <button name="boton" id="botonRegresar" type="button" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" class="btn btn-success btn-ls botonPropio" value="REGRESAR"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"> </button>
                            <button name="boton" id="botonConsultarGuia" type="submit" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" class="btn btn-success btn-ls botonPropio" value="REGRESAR"> CONSULTAR GU&Iacute;A <img src="../imagenes/document_16.png"> </button>
                            <button name="boton" id="botonSalir" type="button" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" class="btn btn-success btn-ls botonPropio" value="SALIR"> SALIR <img src="../imagenes/salir.png"> </button>
                        </div>
                    </div>                    
                </div>
            </form>
        </body>
    </html>
    <?php
} else {

    include '../clases/servicios.php';
    $ultimaGuia = null;

    if (is_null($_SESSION["rol_id"])) {
        header("Location: ../index.php?null=null");
    } else {

        $totalCobrado = 0;
        $totalPagado = 0;
        $totalAnticipo = 0;
        $porcentajeGanancia = 0;
        $diferencia = 0;
        $estado = '';
        $colspan = '25';
        $size = '9';
        $listaMotivos = '';
        $totalAuxiliares = 0;
        $totalParqueaderos = 0;
        $totalOtros = 0;
        $valorTotalCostos = 0;
        $fecha = '';
        $posibleAnticipo = 0;
        $totalAnticipos = 0;

        $servicio = new servicios();

        $datosServicio = $servicio->retornarServicioPorIdServicio($_GET["idservicio"]);
        ?>
        <!DOCTYPE html>
        <!--
        To change this license header, choose License Headers in Project Properties.
        To change this template file, choose Tools | Templates
        and open the template in the editor.
        -->
        <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                <title>Servicios</title>
                <link rel="icon" href="../imagenes/favicon.ico">

                <link href="../css/css2.css" rel="stylesheet" type="text/css" />
                <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
                <?= retornarRecursosBootstrap(); ?>
                <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        

                <script src="../js/js_mostrarServicio.js?n=<?= rand(0, 10) ?>" type="text/javascript"></script> 
                <script src="../js/accionesenprograma.js" type="text/javascript"></script>
                <script src="../js/js_comunes.js" type="text/javascript"></script>
                <script src="../js/cambioColores.js" type="text/javascript"></script>    
                <script src="../js/jquery.number.min.js" type="text/javascript"></script>
                <!-- Evitar cache -->
                <meta http-equiv="Expires" content="0">
                <meta http-equiv="Last-Modified" content="0">
                <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
                <meta http-equiv="Pragma" content="no-cache">
            </head>
            <body>
                <form method="post" action="../trafico/gestionarServicios.php" name="formuarlio_index" >
                    <input type="hidden" id="idempleado" name="idempleado" value="<?= $_SESSION["emp_cedula"] ?>" /> 
                    <input type="hidden" id="departamento" name="departamento" value="<?= $_SESSION["departamento"] ?>" />
                    <div id="contenedor">
                        <div id="contenedor-index" class="row input-sm">                       
                            <div id="divImagenUsa" class="col-sm-4 input-sm"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                            <div id="texoDocumento" class="col-sm-4 input-sm">
                                <h1>Mostrar servicios</h1>
                            </div>                                        
                            <div id="divDatosIniciales" class="col-sm-4 input-sm">   
                                <ul class="list-group">
                                    <li class="list-group-item ">
                                        <span class="badge"><?= $_SESSION["nombre_usuario"]; ?></span>
                                        Usuario
                                    </li>
                                    <li class="list-group-item ">
                                        <span class="badge"><?= $_SESSION["departamento"]; ?></span>
                                        Departamento
                                    </li>                                        
                                    <li class="list-group-item ">
                                        <span class="badge"><?= date('Y-m-d'); ?></span>
                                        Fecha
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div id="contenedor-index2" class="panel panel-success">
                            <div class="panel-heading">                                        
                                <h3 class="panel-title">Datos servicio</h3>
                            </div>                        
                            <div class="panel-body" id="divServicio">

                                <?php
                                include_once '../clases/otrosCostos.php';
                                $otrosCostos = new otrosCostos();
                                $sobreCostos = 0;
                                //var_dump($_SESSION);
                                $idBoton = intval($otrosCostos->retornarAgregarCostos($_SESSION["emp_cedula"]));

                                $arregloPermisos = $otrosCostos->returnPermisosModulos('mostrarServicio', $_SESSION["emp_cedula"]);

                                $cambiarValorFlete = 0;

                                if (count($arregloPermisos) > 0) {
                                    if ($arregloPermisos[0]["estado"] === '1') {
                                        $cambiarValorFlete = 1;
                                    }
                                }

                                $tabla = '<table class="table table-hover" id="tablaServicio">';
                                if (count($datosServicio["mensajesCancelacion"]) !== 0) {

                                    $tabla .= '<tr><th colspan="' . $colspan . '">Mensajes de cancelaci&oacute;n</th></tr>';

                                    $tabla .= '<tr><td colspan="5">Fecha</td><td colspan="5" >Empleado</td><td colspan="6" >Mensaje</td></tr>';
                                    for ($i = 0; $i < count($datosServicio["mensajesCancelacion"]); $i++) {
                                        $tabla .= '<tr><td colspan="5" >' . substr($datosServicio["mensajesCancelacion"][0]["fecha"], 0, 10) . '</td><td colspan="5" >' . $datosServicio["mensajesCancelacion"][0]["nombresEmpleado"] . '</td><td colspan="6" >' . $datosServicio["mensajesCancelacion"][0]["motivo"] . '</td><td></td></tr>';
                                    }
                                }

                                //var_dump($datosServicio);

                                $tabla .= "<tr><th colspan=" . $colspan . ">Datos servicio</th></tr>";
                                $tabla .= "<tr><td>Servicio</td><td><input type='number' value='" . $datosServicio["servicio"][0]["idservicio"] . "' disabled='disabled' /></td><td><button id='idservicio' value='" . $datosServicio["servicio"][0]["idservicio"] . "' type='button' >Crear mensaje</button></td><td>Fecha</td><td>" . $datosServicio["servicio"][0]["fechaServicio"] . "</td><td>Crea servicio</td><td>" . $datosServicio["empleado"][0]["emp_nombres"] . " " . $datosServicio["empleado"][0]["emp_apellidos"] . "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                                $tabla .= "<tr class='table-active'><th colspan=" . $colspan . ">Datos veh&iacute;culo, propietario y conductor</th></tr>";
                                $tabla .= "<tr><td>Placa</td><td>"
                                        . "" . $datosServicio["servicio"][0]["placa"] . " <input type='hidden' id='placa' name='placa' value='" . $datosServicio["servicio"][0]["placa"] . "' />"
                                        . "</td>"
                                        . "<td>C.C. Propietario</td>"
                                        . "<td>" . $datosServicio["propietario"][0]["cond_identificacion"] . "</td>"
                                        . "<td>Nombre</td><td>" . $datosServicio["propietario"][0]["cond_nombres"] . " " . $datosServicio["propietario"][0]["cond_apellidos"] . "</td>"
                                        . "<td>C.C. Conductor</td><td>" . $datosServicio["conductor"][0]["cond_identificacion"] . "</td><td>Nombre</td>"
                                        . "<td>" . $datosServicio["conductor"][0]["cond_nombres"] . " " . $datosServicio["conductor"][0]["cond_apellidos"] . "</td><td>Manifiesto</td><td>" . $datosServicio["seguimientos"][0]["manifiesto"] . "</td><td></td><td></td><td></td><td></td><td></td></tr>";
                                $tabla .= "<tr class='table-active'><th colspan=" . $colspan . ">Datos gu&iacute;as</th></tr>";
                                $tabla .= "<tr><td>Gu&iacute;a</td><td>Cliente</td><td>Asesor</td><td>Origen</td><td>Destino</td><td>Planilla</td><td>Remisi&oacute;n</td><td>Factura de entrega del cliente</td><td>Orden de compra</td><td>Valor flete</td><td>Auxilar</td><td>Parqueadero</td><td>Otros</td><td>Valor total costos</td><td>Valor a facturar</td><td>Utilidad</td><td>Valor anticipo</td><td>Valor declarado</td><td>Estado</td><td>Factura</td><td>Cuenta de cobro</td><td>Fecha transferencia</td><td>Notas</td></tr>";

                                $cantidad = count($datosServicio["datosGuias"]);

                                for ($index = 0; $index < count($datosServicio["datosGuias"]); $index++) {
                                    if ($datosServicio["anticipos"][$index]["prueba_entrega"] === 'P') {
                                        $estado = 'Pagada';
                                    } else if ($datosServicio["anticipos"][$index]["prueba_entrega"] === 'N') {
                                        $estado = 'Pendiente pago';
                                    }

                                    if ($index + 1 === $cantidad) {
                                        $ultimaGuia = $datosServicio["datosGuias"][$index]["numeroGuia"];
                                    }

                                    $tabla .= "<tr><td>" . $datosServicio["datosGuias"][$index]["numeroGuia"] . "</td>"
                                            . "<td>" . $datosServicio["datosGuias"][$index]["cli_nombre"] . "</td>"
                                            . "<td>" . $datosServicio["asesoresServicio"][$index]["nombreAsesor"] . "</td>"
                                            . "<td>" . $datosServicio["datosGuias"][$index]["ciudadOrigen"] . "</td>"
                                            . "<td>" . $datosServicio["datosGuias"][$index]["ciudadDestino"] . "</td>"
                                            . "<td><input type='text' id='p-" . $datosServicio["datosGuias"][$index]["numeroGuia"] . "'  class='form-control input-sm' value='" . $datosServicio["datosGuias"][$index]["planilla"] . "' onblur='cambiarPlanilla(this);' /></td>"
                                            . "<td><input type='text' id='r-" . $datosServicio["datosGuias"][$index]["numeroGuia"] . "'  class='form-control input-sm' value='" . $datosServicio["datosGuias"][$index]["remision"] . "' onblur='cambiarRemision(this);' /></td>"
                                            . "<td><input type='text' id='f-" . $datosServicio["datosGuias"][$index]["numeroGuia"] . "'  class='form-control input-sm' value='" . $datosServicio["datosGuias"][$index]["factura"] . "' onblur='cambiarFactura(this);' /></td>"
                                            . "<td><input type='text' id='o-" . $datosServicio["datosGuias"][$index]["numeroGuia"] . "'  class='form-control input-sm' value='" . $datosServicio["datosGuias"][$index]["ordenCompra"] . "' onblur='cambiarOrdenCompra(this);' /></td>";

                                    if ($cambiarValorFlete === 1) {
                                        $tabla .= "<td><input type='text' id='2-" . $datosServicio["datosGuias"][$index]["numeroGuia"] . "' value='" . number_format($datosServicio["datosGuias"][$index]["valorPagado"]) . "' class='form-control input-sm' onblur='cambiarValorPagado(this,1)' /></td>";
                                    } else {
                                        $tabla .= "<td>" . number_format($datosServicio["datosGuias"][$index]["valorPagado"]) . "</td>";
                                    }

                                    $tabla .= "<td>" . number_format($datosServicio["datosGuias"][$index]["auxiliar"]) . "</td>"
                                            . "<td>" . number_format($datosServicio["datosGuias"][$index]["parqueadero"]) . "</td>"
                                            . "<td></td>"
                                            . "<td></td>"
                                            . "<td>" . number_format($datosServicio["datosGuias"][$index]["valorCobrado"]) . "</td>"
                                            . "<td></td>"
                                            . "<td>" . number_format($datosServicio["anticipos"][$index]["val_valorAdelanto"]) . "</td>"
                                            . "<td>" . number_format($datosServicio["datosGuias"][$index]["valorDeclarado"]) . "</td>"
                                            . "<td>" . $estado . '</td>'
                                            . "<td>" . $datosServicio["facturas"][$index]["factura"] . "</td>"
                                            . "<td>" . $datosServicio["datosGuias"][$index]["numeroCuentacobro"] . "</td>"
                                            . "<td>" . $datosServicio["datosGuias"][$index]["fechaTransferencia"] . "</td>"
                                            . "<td><textarea id='n-" . $datosServicio["datosGuias"][$index]["numeroGuia"] . "' cols = '20' onblur='cambiarNotasGuia(this)'>" . $datosServicio["datosGuias"][$index]["notas"] . "</textarea></td></tr>";

                                    $totalCobrado += intval($datosServicio["datosGuias"][$index]["valorCobrado"]);
                                    $totalPagado += intval($datosServicio["datosGuias"][$index]["valorPagado"]);
                                    $totalAnticipo += intval($datosServicio["anticipos"][$index]["val_valorAdelanto"]);
                                    $totalAuxiliares += intval($datosServicio["datosGuias"][$index]["auxiliar"]);
                                    $totalParqueaderos += intval($datosServicio["datosGuias"][$index]["parqueadero"]);
                                    $trCts = $otrosCostos->consultarCostos($datosServicio["datosGuias"][$index]["numeroGuia"]);

                                    if (isset($trCts[0]["valor"])) {
                                        if (intval($trCts[0]["valor"]) !== null) {
                                            $tabla .= "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Otros</td><td>Costos</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                                            for ($index2 = 0; $index2 < count($trCts); $index2++) {
                                                $tabla .= "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>" . $trCts[$index2]["concepto"] . "</td><td>" . number_format($trCts[$index2]["valor"]) . "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                                                $sobreCostos = $sobreCostos + $trCts[$index2]["valor"];
                                            }
                                        }
                                    }

                                    if ($idBoton !== 0) {
                                        $tabla .= "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Agregar</td><td>Costo</td><td></td><td><input size='10' type='text' id='c-" . $datosServicio["datosGuias"][$index]["numeroGuia"] . "' placeholder='Detalle costo'/></td><td><input type='number' id='v-" . $datosServicio["datosGuias"][$index]["numeroGuia"] . "' style='width: 7em;' value='0'/></td><td><input type='button' value='Crear costo' id='b-" . $datosServicio["datosGuias"][$index]["numeroGuia"] . "' onclick='agregarSobreCosto(this,2)' /></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                                    }
                                }

                                $totalOtros = $totalOtros + $sobreCostos;

                                if (count($datosServicio["sobreanticipos"]) > 0) {
                                    $tabla .= "<tr class='table-active'><th colspan=" . $colspan . ">Sobreanticipos</th></tr>";
                                    $tabla .= "<tr><td>Gu&iacute;a</td>"
                                            . "<td>Nro. Sobreanticipo</td>"
                                            . "<td>Fecha</td>"
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
                                            . "<td></td></tr>";
                                    for ($index1 = 0; $index1 < count($datosServicio["sobreanticipos"]); $index1++) {
                                        if (@$datosServicio["sobreanticipos"][$index]["prueba_entrega"] === 'P') {
                                            $estado = 'Pagada';
                                        } else if (@$datosServicio["sobreanticipos"][$index]["prueba_entrega"] === 'N') {
                                            $estado = 'Pendiente pago';
                                        }
                                        $tabla .= "<tr><td>" . $datosServicio["sobreanticipos"][$index1]["val_numeroGuia"] . "</td>"
                                                . "<td>" . $datosServicio["sobreanticipos"][$index1]["val_numeroAnticipo"] . "</td>"
                                                . "<td>" . substr($datosServicio["sobreanticipos"][$index1]["val_fechaAnticipo"], 0, 10) . "</td>"
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
                                                . "<td>" . number_format($datosServicio["sobreanticipos"][$index1]["val_valorAdelanto"]) . "</td>"
                                                . "<td></td>"
                                                . "<td>" . $estado . '</td>'
                                                . "<td></td>"
                                                . "<td></td></tr>";
                                        $totalAnticipo += intval($datosServicio["sobreanticipos"][$index1]["val_valorAdelanto"]);
                                    }
                                }
                                $valorTotalCostos = $totalAuxiliares + $totalParqueaderos + $totalOtros + $totalPagado;
                                $diferencia = $totalCobrado - $valorTotalCostos;
                                $porcentajeGanancia = 100 - (($valorTotalCostos * 100) / $totalCobrado);
                                $saldoAnticipos = $totalPagado - $totalAnticipo;
                                $posibleAnticipo = ($totalPagado - $totalAnticipo) / 2;

                                $tabla .= "<tr>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "<th>Totales</th><th>" . number_format($totalPagado) . "</th><th>" . number_format($totalAuxiliares) . "</th>
                                    <th>" . number_format($totalParqueaderos) . "</th><th>" . number_format($totalOtros) . "</th><th>" . number_format($valorTotalCostos) . "</th>
                                        <th>" . number_format($totalCobrado) . "</th><th>" . number_format($diferencia) . "</th><th>" . number_format($totalAnticipo) . "</th><td></td><td></td><th>Ganancia:" . intval($porcentajeGanancia) . "%</th><td></td></tr>";

                                if ($estado === 'Pendiente pago') {

                                    $tabla .= "<tr><th colspan=" . $colspan . ">Crear sobreanticipo <input type='checkbox' id='mostrar'/></th></tr>";
                                    $tabla .= "<tr id='sobreAnticipo1'><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><td></td>
                                  <td>Valor a pagar por anticipos</td>
                                  <td><input type='text' value='" . number_format($saldoAnticipos) . "' class='form-control-sm' size='" . $size . "' disabled='disabled' />
                                  <input type='hidden' id='saldoAnticipos' value='" . number_format($saldoAnticipos) . "' class='form-control-sm' size='" . $size . "' /></td>
                                  <td></td>                                        
                                  <td></td><td></td><th></th><td></td></tr>";

                                    $tabla .= "<tr id='sobreAnticipo2'>
                                    <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td>Gu&iacute;a</td>
                                  <td><td><input type='text' name='guia' id='guia' class='form-control-sm' size='" . $size . "' value='" . $ultimaGuia . "'/></td>
                                  <td>Valor sobre anticipo</td>
                                  <td><input type='text' name='valorSobreAnticipo' id='valorSobreAnticipo' class='form-control-sm' size='" . $size . "' value='" . number_format($posibleAnticipo) . "' /></td>
                                  <td></td>                                        
                                  <td></td><td></td><th></th><td></td></tr>";

                                    $saldoAnticipo = $saldoAnticipos - $posibleAnticipo;
                                    $tabla .= "<tr id='sobreAnticipo3'>
                                    <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td></td>
                                  <td>Saldo</td>
                                  <td><input type='text' name='saldoSobreAnticipo' id='saldoSobreAnticipo' class='form-control-sm' size='" . $size . "' value='" . number_format($saldoAnticipo) . "' /></td>                                  
                                  <td><input type='button' value='Crear sobre anticipo' id='crearSobreAnticipo'/></td><td></td><th></th><td></td></tr>";
                                }

                                $tabla .= "</table></div>";

                                echo $tabla;
                                ?>                                
                            </div>
                            <div id="mensajesGestionarServicios"></div>                        
                            <div class="panel-body">
                                <div class="panel-body">
                                    <button name="boton" id="botonRegresar" type="button" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" class="btn btn-success btn-ls botonPropio" value="REGRESAR"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"> </button>                                    
                                    <button name="boton" id="botonConsultarGuia" type="button" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" class="btn btn-success btn-ls botonPropio" value="REGRESAR"> CONSULTAR GU&Iacute;A <img src="../imagenes/document_16.png"> </button>
                                    <button name="boton" id="botonSalir" type="button" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" class="btn btn-success btn-ls botonPropio" value="SALIR"> SALIR <img src="../imagenes/salir.png"> </button>
                                </div>
                            </div>
                        </div>
                        <div id="mensajes">   
                            <?php
                            if (@$_GET["msj"] === '1') {
                                echo '<div class="alert alert-dismissible alert-warning">'
                                . 'El servicio seleccionado con n&uacute;mero: ' . $_GET["idservicio"] . ' no puede ser borrado ya tiene anticipo generado'
                                . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                </form>            
            </body>
        </html>
        <?php
    }
}

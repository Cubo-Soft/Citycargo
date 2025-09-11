<?php
session_start();

include_once '../clases/rol_boton.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    $rol_boton = new rol_boton();
    $clientes = $rol_boton->retornarClientes();

    $cantidadGuias = $rol_boton->retornarGuiasPorFacturar();
    $cantidadServicios = $rol_boton->retornarServiciosPorFacturar(0);
    $cantidadAnticipos = $rol_boton->retornarAnticiposPendientes(1, 1,null,null);

    $lista = '<select name="idCliente" id="idCliente" class="form-control col-lg-8" >'
            . '<option value="0">...</option>';
    for ($index = 0; $index < count($clientes); $index++) {
        $lista .= "<option value=" . $clientes[$index]["cli_documento"] . ">" . $clientes[$index]["cli_nombre"] . "</option>";
    }
    $lista .= "</select>";

    $dia = date('d') - 1;
    $fecha = date('Y-m-d');
    $nuevafecha = strtotime('-' . $dia . ' day', strtotime($fecha));
    $fechaPrimera = date('Y-m-d', $nuevafecha);
    
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
            <meta charset="iso-8859-1">
            <title>Detalle servicios</title>
            <link rel="icon" href="../imagenes/camion256.png">
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>            
            <script src="../js/js_detalladoServicios.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>             
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <script src="../js/jquery.number.js" type="text/javascript"></script>
            <script src="../js/table2excel.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>            
            <div>
                <div id="contenedor-index">   
                    <div id="contenedor-index" class="row">                       
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCAEGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1>Detalle servicios</h1>
                        </div>                                        
                        <div id="divDatosIniciales" class="col-lg-4">   
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $_SESSION["nombre_usuario"]; ?></span>
                                    Usuario
                                </li>
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $_SESSION["departamento"]; ?></span>
                                    Departamento
                                </li>                                        
                                <li class="list-group-item">
                                    <span class="badge"><?php echo date('Y-m-d'); ?></span>
                                    Fecha
                                </li>
                            </ul>
                        </div>
                    </div>
                    <form method="post" action="../modulos/detalladoServicios.php" name="formuarlio_index" >                        
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Listar para detallado de servicios por empresa</h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-lg-12 panel"  >                                   

                                    <div class="col-xs-2 text-center">
                                        <h4>Lista de clientes</h4>
                                    </div>
                                    <div class="col-xs-10">
                                        <?= $lista; ?>
                                    </div>
                                </div>                               
                                <div class="col-lg-12 panel">
                                    <div class="col-xs-2 text-center">
                                        <h4>Fecha inicial</h4>
                                    </div>
                                    <div class="col-xs-3">
                                        <input type="date" name="fechaInicio" id="fechaInicio" class="form-control" value="<?= $fechaPrimera; ?>" />
                                    </div>
                                    <div class="col-xs-2 text-center">
                                        <h4>Fecha final</h4>
                                    </div>
                                    <div class="col-xs-3">
                                        <input type="date" name="fechaFinal" id="fechaFinal" value="<?= date('Y-m-d'); ?>"class="form-control" />
                                    </div>
                                    <div class="col-xs-2" >
                                        <button id="botonConsultar" name="boton" type="submit" class="form-control" value="CONSULTAR">CONSULTAR </button>
                                    </div>                                   
                                </div>
                            </div>
                            <div id="mensajes">     
                                <?php
                                if (@$_GET["cn"] == '2') {
                                    $mensaje = "'El número de identificación : " . $_GET['i'] . ", no registra anticipos pendientes'";
                                    echo '<script>alert(' . $mensaje . ');</script>';
                                }
                                if (@$_GET["pj"] == 'n') {
                                    echo '<script>alert("El conductor no existe, \n para agregar anticipo y crear conductor \n por favor use el boton ANTICIPOS");</script>';
                                }
                                ?>
                            </div>                            
                            <?php
                            switch (@$_POST["boton"]) {
                                case "CONSULTAR":
                                    $datosCliente = $rol_boton->retornarTelDirCliente($_POST["idCliente"]);
                                    $resultado = $rol_boton->retornarDatosCliente($_POST["idCliente"], $_POST["fechaInicio"], $_POST["fechaFinal"]);
                                    ?>
                                    <div id="cuerpoPrefactura">
                                        <?php
                                        if (empty($resultado)) {
                                            echo 'No se encuentran datos con los par&aacute;metros indicados';
                                        } else {
                                            ?>
                                            <table class="table table-condensed table-hover " id="tablaExcel">                                                
                                                <thead>
                                                <caption>Detallado servicios</caption>
                                                <tr>      
                                                    <td></td>
                                                    <td>NIT</td>                                                        
                                                    <td><?= $resultado[0]["nit"] ?></td>
                                                    <td>Empresa</td>
                                                    <td colspan="4"><?= $resultado[0]["cli_nombre"] ?></td>
                                                    <td>Fecha inicial</td>
                                                    <td><?= $_POST["fechaInicio"] ?></td>
                                                    <td>Fecha final</td>
                                                    <td><?= $_POST["fechaFinal"] ?></td>
                                                    <td></td>
                                                    <td></td>                                                                                                   
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>   
                                                        <th></th>
                                                        <th>Fecha</th>
                                                        <th>Origen</th>
                                                        <th>Destino</th>       
                                                        <th>Id servicio</th> 
                                                        <th>Planilla</th>
                                                        <th>Factura</th>                                                        
                                                        <th>Val declarado</th>
                                                        <th>Val manejo</th>
                                                        <th>Val total empresa</th>
                                                        <th>Val a facturar</th>                                                        
                                                        <th>Guías</th>
                                                        <th><input type="checkbox" name="chbPE" id="chbPE" /></th>
                                                    </tr>                                                
                                                    <?php
                                                    $origen = null;
                                                    $destino = null;
                                                    $idservicio = 0;
                                                    $cantidad = count($resultado);
                                                    $b = 0;
                                                    $idservAnterior = 0;
                                                    $valorManejo = 0;
                                                    $valorTotalEmpresa = 0;
                                                    $guiasBuscadas = array();
                                                    $contador=0;

                                                    for ($a = 0; $a <= count($resultado) - 1; $a++) {
                                                        if ($a === 0) {
                                                            echo '<input type="hidden" id="nitEmpresa" name="nitEmpresa" value="' . $resultado[$a]["nit"] . '" />'
                                                            . '<input type="hidden" id="nombreEmpresa" name="nombreEmpresa" value="' . $resultado[$a]["cli_nombre"] . '" />'
                                                            . '<input type="hidden" id="telefonoEmpresa" name="telefonoEmpresa" value="' . $datosCliente[$a]["cli_telefono"] . '" />'
                                                            . '<input type="hidden" id="direccionEmpresa" name="direccionEmpresa" value="' . $datosCliente[$a]["cli_direccion"] . '" />';
                                                            $idservAnterior = $resultado[$a]["idservicio"];
                                                        }
                                                        
                                                        //var_dump($guiasBuscadas);

                                                       // if ($resultado[$a]["nit"] === $_POST["idCliente"] && in_array($resultado[$a]["numeroGuia"], $guiasBuscadas)===false) {
                                                            
                                                            $guiasBuscadas[$a]=$resultado[$a]["numeroGuia"];            
                                                            
                                                            echo '<tr>'
                                                            . '<td>'.($contador+1).'</td>'
                                                            . '<td>' . $resultado[$a]["fechaServicio"] . '</td>';
                                                            $origen = $rol_boton->retornarMunicipioOrigen($resultado[$a]["numeroGuia"], $resultado[$a]["idservicio"]);
                                                            $entregas = $rol_boton->retornarEntregas($resultado[$a]["idservicio"]);
                                                            echo '<td>' . $origen[0]["mun_nombre"] . '</td>';
                                                            
                                                            $contador+=1;
                                                            
                                                            if (count($entregas) > 0) {

                                                                $destino = $rol_boton->retornarMunicipioDestino($resultado[$a]["numeroguia"], $resultado[$a]["idservicio"]);
                                                                echo '<td></td>'
                                                                . '<td>' . $resultado[$a]["idservicio"] . '</td>'
                                                                . '<td></td>'
                                                                . '<td></td>'
                                                                . '<td>$ ' . number_format($resultado[$a]["valorDeclarado"]) . '</td>';

                                                                if ($resultado[$a]["valorManejo"] !== 0) {
                                                                    $valorManejo = ($resultado[$a]["valorDeclarado"] * $resultado[$a]["valorManejo"]) / 100;
                                                                    $valorTotalEmpresa = $resultado[$a]["valorCobrado"] + $valorManejo;
                                                                } else {
                                                                    $valorTotalEmpresa = $resultado[$a]["valorCobrado"];
                                                                }

                                                                $valorTotalEmpresa = $valorTotalEmpresa - ($resultado[$a]["auxiliar"] + $resultado[$a]["parqueadero"] + $resultado[$a]["otros"]);

                                                                echo '<td>$ ' . number_format($valorManejo) . '</td>'
                                                                . '<td>$ ' . number_format($valorTotalEmpresa) . '</td>'
                                                                . '<td>$ ' . number_format($resultado[$a]["valorCobrado"]) . '</td>'
                                                                . '<td>' . $resultado[$a]["numeroGuia"] . '</td>'
                                                                . '<td><input type="checkbox" id="' . $resultado[$a]["numeroGuia"] . '-' . $a . '" onclick="chequearSimilares(this)" /></td>'
                                                                . '</tr>';

                                                                for ($index1 = 0; $index1 < count($entregas); $index1++) {

                                                                    $b = $index1 + 1;
                                                                    echo '<tr bgcolor="#F5F5F5" >'
                                                                    . '<td></td>'
                                                                    . '<td></td>'
                                                                    . '<td>' . $entregas[$index1]["ciudadDestino"] . '</td>'
                                                                    . '<td>' . $entregas[$index1]["idservicio"] . '</td>'
                                                                    . '<td>' . $entregas[$index1]["planilla"] . '</td>'
                                                                    . '<td>' . $entregas[$index1]["factura"] . '</td>'
                                                                    . '<td></td>'
                                                                    . '<td></td>';

                                                                    if ($entregas[$index1]["valorManejo"] !== 0) {
                                                                        $valorManejo = ($entregas[$index1]["valorDeclarado"] * $entregas[$index1]["valorManejo"]) / 100;
                                                                        $valorEmpresa = $entregas[$index1]["valorCobrado"] + $valorManejo;
                                                                    } else {
                                                                        $valorEmpresa = $entregas[$index1]["valorCobrado"];
                                                                    }

                                                                    echo '<td></td>'
                                                                    . '<td>$' . number_format($valorEmpresa) . '</td>'
                                                                    . '<td>' . $entregas[$index1]["guiaEntrega"] . '</td>'
                                                                    . '<td></td>'
                                                                    . '</tr>';
                                                                }
                                                            } else {
                                                                $destino = $rol_boton->retornarMunicipioDestino($resultado[$a]["numeroGuia"], $resultado[$a]["idservicio"]);
                                                                echo '<td>' . $destino[0]["mun_nombre"] . '</td>'
                                                                . '<td>' . $resultado[$a]["idservicio"] . '</td>'
                                                                . '<td>' . $resultado[$a]["planilla"] . '</td>'
                                                                . '<td>' . $resultado[$a]["factura"] . '</td>'
                                                                . '<td>$ ' . number_format($resultado[$a]["valorDeclarado"]) . '</td>';

                                                                if ($resultado[$a]["valorManejo"] !== 0) {
                                                                    $valorManejo = ($resultado[$a]["valorDeclarado"] * $resultado[$a]["valorManejo"]) / 100;
                                                                    $valorTotalEmpresa = $resultado[$a]["valorCobrado"] + $valorManejo;
                                                                } else {
                                                                    $valorTotalEmpresa = $resultado[$a]["valorCobrado"];
                                                                }

                                                                $valorTotalEmpresa = $valorTotalEmpresa - ($resultado[$a]["auxiliar"] + $resultado[$a]["parqueadero"] + $resultado[$a]["otros"]);

                                                                echo '<td>$ ' . number_format($valorManejo) . '</td>'
                                                                . '<td>$ ' . number_format($valorTotalEmpresa) . '</td>'
                                                                . '<td>$ ' . number_format($resultado[$a]["valorCobrado"]) . '</td>'
                                                                . '<td>' . $resultado[$a]["numeroGuia"] . '</td>'
                                                                . '<td><input type="checkbox" id="' . $resultado[$a]["numeroGuia"] . '-' . $a . '" onclick="chequearSimilares(this)" /></td>';
                                                                echo '</tr>';
                                                            }
                                                        //}
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td></td>                                                        
                                                        <td></td>  
                                                        <td></td>                                                        
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td><input type="button" id="calcularTotal" name="calcularTotal" value="Calcular total" onclick="iniciarAccion();" class="form form-control" /></td>
                                                        <td><strong>Total a facturar</strong></td><td><input type="text" id="valorAcumuladoMostrar" class="form-control input-sm" style="width: 100px" value="0"><input type="hidden" id="valorAcumulado" value="0"/></td>   
                                                        <td></td>                                                                                                         
                                                        <td></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <?php
                                    break;
                                case "GENERAREXCEL":
                                    ?>
                                    <div id="cuerpoPrefactura">

                                    </div>
                                    <?php
                                    break;
                            }
                            ?>
                        </div>                        
                        <div class="panel-body" id="mensajes2"></div>  
                        <div class="panel-body">                        
                            <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                            
                            <button name="boton" id="botonGenerarExcel" type="button" class="btn btn-success btn-ls botonPropio" value="GENERAREXCEL" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GENERAR EXCEL <img src="../imagenes/excel.ico"></button>                                                    
                            <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>               
                        </div>                       
                    </form>  
                </div>               
            </div>             
        </body>
    </html>
    <?php
}

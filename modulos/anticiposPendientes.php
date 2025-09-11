<?php
session_start();

include_once '../clases/rol_boton.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
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
            <title>Servicios por pagar</title>
            <link rel="icon" href="../imagenes/camion256.png">
            
                <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>            
            <script src="../js/js_anticiposPendientes.js?n=<?= rand(0, 3) ?>"" type="text/javascript"></script>             
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>              

            <script src="../js/datatable.js" type="text/javascript"></script>
            <link href="../css/datatable.css" rel="stylesheet" type="text/css"/>

            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>            
            <div>
                <div id="contenedor-index">   
                    <div id="contenedor-index" class="row">                       
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1>Servicios por pagar</h1>
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
                    <div class="panel-body">
                        <?php
                        if (count($_POST) === 0 || $_POST["consultar"] === '0') {
                            $fecha_actual = date("Y-m-d");
                            $fechaMesAnterior = date("Y-m-01", strtotime($fecha_actual . "- 1 month"));
                            $l = new DateTime($fechaMesAnterior);
                            //echo $l->format("Y-m-t");
                            ?>
                            <p class="alert alert-success"><b>
                                Antes de mostrar la lista de servicios por pagar, por favor seleccione la fecha inicial y la fecha final de consulta. <br>
                                De manera predeterminada, la fecha inicial es el mes inmediatamente anterior al actual.</b>
                            </p>
                            <!--<input type="hidden" id="fechaInicial" value="0" />
                            <input type="hidden" id="fechaFinal" value="0" />-->
                            <form name="frmConsulta" action="anticiposPendientes.php" method="POST" enctype="multipart/form-data">
                                <input type="hidden" name="consultar" id="consultar" value="1" />
                                <table class="table table-condensed table-striped">
                                    <thead>                                        
                                        <tr>
                                            <th>Fecha inicial</th>
                                            <th>Fecha final</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input type="date" value="<?= $fechaMesAnterior ?>" class="form form-control" name="fechaInicial" id="fechaInicial" /></td>
                                            <td><input type="date" value="<?= $l->format("Y-m-t") ?>" class="form form-control" name="fechaFinal" id="fechaFinal" /></td>
                                            <td><input type="button" value="Iniciar consulta" class="btn btn-success" id="btnIniciarConsultar" name="btnIniciarConsultar" /></td>
                                        </tr>
                                        <tr>                                            
                                            <td colspan="2">
                                                <div id="mensajeUno">

                                                </div>
                                            </td>                                         
                                            <td>
                                                <input type="submit" value="Continuar consulta" class="btn btn-success" id="btnContinuarConsultar" />
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </form>
                        </div>
                        <div class="panel-body">                        
                            <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                        
                            <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>               
                        </div>
                        <?php
                    } else {
                        $rol_boton = new rol_boton();
                        $serviciosFacturar = $rol_boton->retornarAnticiposPendientes(0, 0, $_POST["fechaInicial"], $_POST["fechaFinal"]);
                        $cantidad = count($serviciosFacturar);
                        $mensaje = null;
                        $guias = array();
                        //var_dump($serviciosFacturar);
                        ?>
                        <input type="hidden" id="fechaInicial" value="<?= $_POST["fechaInicial"] ?>" />
                        <input type="hidden" id="fechaFinal" value="<?= $_POST["fechaFinal"] ?>" />
                        <form method="post" action="../modulos/anticiposPendientes.php" name="formuarlio_index" >                        
                            <input type="hidden" name="consultar" id="consultar" value="0" />
                            <div class="panel panel-success">
                                <div class="panel-heading">
                                    <h3 class="panel-title">Listado</h3>
                                </div>
                                <div class="panel-body altura">
                                    <div class="col-lg-12 panel" id="divFacturas" >
                                        <table class="table table-condensed table-hover " id="tablaExcel" >
                                            <thead>
                                                <tr>
                                                    <th>Prueba Entrega</th>
                                                    <th>No.</th>                                                    
                                                    <th>Servicio</th>
                                                    <th>Guía</th>
                                                    <th>Estado prueba de entrega</th>                                                    
                                                    <th>Fecha Servicio</th>
                                                    <th>Fecha Anticipo</th>
                                                    <th>Placa</th>
                                                    <th>Propietario</th>
                                                    <th>Cliente</th>
                                                    <th>Vlr. Cliente</th>
                                                    <th>Vlr. Conductor</th>
                                                    <th>Vlr. Anticipo</th>                                                
                                                    <th>Saldo</th>                                                    
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                $totalPagar = 0;
                                                $totalAdelantos = 0;
                                                $totalServicios = 0;
                                                $estado = null;
                                                if ($cantidad > 0) {
                                                    for ($index1 = 0; $index1 < count($serviciosFacturar); $index1++) {
                                                        if (!in_array($serviciosFacturar[$index1]["val_numeroGuia"], $guias)) {
                                                            $total = intval($serviciosFacturar[$index1]["valorservicio"]) - intval($serviciosFacturar[$index1]["val_valorAdelanto"]);
                                                            $totalPagar = $totalPagar + $total;
                                                            $totalAdelantos = $totalAdelantos + $serviciosFacturar[$index1]["val_valorAdelanto"];
                                                            $totalServicios = $totalServicios + $serviciosFacturar[$index1]["valorservicio"];
                                                            $guias[$index1] = $serviciosFacturar[$index1]["val_numeroGuia"];
                                                            ?>
                                                            <tr>
                                                                <td><?php
                                                                    $cantidadGuiones = mb_substr_count($serviciosFacturar[$index1]["val_numeroGuia"], '-');

                                                                    $pruebaEntrega = $rol_boton->validarPruebaEntrega($serviciosFacturar[$index1]["val_numeroGuia"]);
                                                                    if (count($pruebaEntrega) === 0) {
                                                                        echo "<input type='button' id='" . $serviciosFacturar[$index1]["val_numeroGuia"] . "' name='" . $serviciosFacturar[$index1]["val_numeroGuia"] . "' class='btn btn-warning btn-sm' value='Pendiente' onclick='crearPruebaEntrega(this);' />";
                                                                        $estado = 'Pendiente';
                                                                    } else {
                                                                        echo "<input type='button' value='Entregada' class='btn btn-success btn-sm ' />";
                                                                        $estado = 'Entregada';
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?= $index1 + 1 ?></td>
                                                                <td><?= $serviciosFacturar[$index1]["idservicio"] ?></td>
                                                                <td><?= $serviciosFacturar[$index1]["val_numeroGuia"] ?></td>                                                                
                                                                <td><?= $estado ?></td>                                                              
                                                                <td>
                                                                    <?= $serviciosFacturar[$index1]["fechaServicio"] ?>
                                                                </td>
                                                                <?php
                                                                $cantidadCaracteres = iconv_strlen($serviciosFacturar[$index1]["val_fechaAnticipo"]);
                                                                if ($cantidadCaracteres === 10) {
                                                                    $fecha = $serviciosFacturar[$index1]["val_fechaAnticipo"];
                                                                } else {
                                                                    $fecha = substr($serviciosFacturar[$index1]["val_fechaAnticipo"], 0, -6);
                                                                }

                                                                echo '<td>' . $fecha . '</td>'
                                                                . '<td>' . $serviciosFacturar[$index1]["placa"] . '</td>'
                                                                . '<td>' . $serviciosFacturar[$index1]["nombresPropietario"] . '</td>'
                                                                . '<td>' . $serviciosFacturar[$index1]["cliente"] . '</td>'
                                                                . '<td>' . number_format(@$rol_boton->retornarValorACobrar($serviciosFacturar[$index1]["val_numeroGuia"]), 0, ".", ".") . '</td>';
                                                                ?>

                                                                <td><?= number_format($serviciosFacturar[$index1]["valorservicio"], 0, ".", "."); ?></td>
                                                                <td><?= number_format($serviciosFacturar[$index1]["val_valorAdelanto"], 0, ".", "."); ?></td>                                                    
                                                                <td><?= number_format(intval($serviciosFacturar[$index1]["valorservicio"]) - intval($serviciosFacturar[$index1]["val_valorAdelanto"]), 0, ".", "."); ?></td>

                                                                <?php
                                                            }
                                                        }
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
                                                    <td></td>
                                                    <td></td>
                                                    <td align="right"><strong>Totales</strong></td>
                                                    <td><?= number_format($totalServicios, 0, ".", "."); ?></td>
                                                    <td><?= number_format($totalAdelantos, 0, ".", "."); ?></td>
                                                    <td><?= number_format($totalPagar, 0, ".", "."); ?></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div> 
                                </div>
                            </div>                        
                            <div id="mensajes">
                                <?php
                                if ($mensaje === 1) {
                                    echo'<div class="alert alert-dismissible alert-danger">Se han encontrado <strong> gu&iacute;as pendientes </strong> por <strong>prueba de entrega</strong>. Se muestran en los botones de color naranja<br>Al presionar sobre dicho bot&oacute;n, se ingresa la prueba de entrega</div>';
                                }
                                ?>
                            </div>                        
                            <div class="panel-body">                        
                                <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                                <button name="boton" id="botonGenerarExcel" type="button" class="btn btn-success btn-ls botonPropio" value="GENERAREXCEL" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GENERAR EXCEL <img src="../imagenes/excel.ico"></button>
                                <button name="boton" id="botonNuevaConsulta" name="botonNuevaConsulta" type="submit" class="btn btn-success btn-ls botonPropio" value="NUEVA CONSULTA" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > NUEVA CONSULTA <img src="../imagenes/plus_16.png"></button>
                                <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>               
                            </div>
                        </form>  
                    </div>
                </div>             
                <?php
            }
            ?>
        </body>
    </html>
    <?php
}

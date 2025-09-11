<?php
session_start();

include_once '../clases/rol_boton.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    $rol_boton = new rol_boton();
    $serviciosFacturar = $rol_boton->retornarServiciosPorFacturar(1);    
    $idempleado = $_SESSION["emp_cedula"];
    $valorEmpresa = null;
    $valorIncoherente = null;

    $guias = array();
    $mensaje = null;
    $idservicio = null;
    $mes = null;
    $fechaActual = new DateTime(date("Y-m-d"));
    $atrasados = null;
    $total = 0;
    $placaPropietario = null;
    $placaConductor = null;
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
            <title>Servicios por facturar</title>
            <link rel="icon" href="../imagenes/camion256.png">
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>            
            <script src="../js/js_facturasyguias.js?n=<?= rand(0, 3) ?>"" type="text/javascript"></script> 
            <script src="../js/cancelarServicio.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>            
            <script src="../js/accionesenprograma.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>
            <script src="../js/cambioColores.js?1" type="text/javascript"></script>
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
                            <h1>Servicios por facturar</h1>
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
                    <div class="col-lg-12 panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">Listado</h3>
                        </div>
                        <div class="col-lg-12 panel-body altura">
                            <div class="col-lg-12 panel" id="divFacturas" >
                                <table class="table table-hover" id="tablaExcel">
                                    <thead>
                                        <tr>             
                                            <th scope="col"></th>
                                            <th scope="col">Empresa</th>
                                            <th scope="col">Fecha servicio</th>
                                            <th scope="col">Placa</th>
                                            <th scope="col">Propietario</th>
                                            <th scope="col">Tel&eacute;fono</th> 
                                            <th scope="col">Conductor</th>
                                            <th scope="col">Tel&eacute;fono</th> 
                                            <th scope="col">Gu&iacute;as</th>
                                            <th scope="col">Vlr. Facturar</th>
                                            <th scope="col">Servicio</th>
                                            <th scope="col">Mensaje</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php                                               
                                        for ($index1 = 0; $index1 < count($serviciosFacturar); $index1++) {
                                            $idservicio = $serviciosFacturar[$index1]["idservicio"];

                                            $placaPropietario = $rol_boton->retornarPlacaPropietario($idservicio, 1);
                                            $placaConductor = $rol_boton->retornarPlacaPropietario($idservicio, 2);

                                            $fechaServicio = new DateTime($serviciosFacturar[$index1]["fecha"]);
                                            $diferencia = $fechaActual->diff($fechaServicio);
                                            if ($diferencia->days >= 60) {
                                                echo '<tr bgcolor="#F6CECE" >';
                                                $atrasados = 1;
                                            } else {
                                                echo '<tr>';
                                            }
                                            ?>
                                        <td><?= $index1 + 1 ?></td>
                                        <td><?= $serviciosFacturar[$index1]["empresa"] ?></td>
                                        <td><?= $serviciosFacturar[$index1]["fecha"] ?></td>
                                        <td><?= $placaPropietario[0]["placa"] ?></td>
                                        <td><?= $placaPropietario[0]["nombrePropietario"] ?></td>
                                        <td><?= $placaPropietario[0]["cond_telefono"] ?></td>
                                        <td><?= $placaConductor[0]["nombreConductor"] ?></td>
                                        <td><?= $placaConductor[0]["cond_telefono"] ?></td>
                                        <td><?= $serviciosFacturar[$index1]["numeroGuia"] ?></td>
                                        <td><?= number_format($serviciosFacturar[$index1]["valorCobrado"],0,".",".") ?></td>
                                        <td><?= $serviciosFacturar[$index1]["idservicio"] ?></td>                                        
                                        <?php
                                        if (in_array($serviciosFacturar[$index1]["numeroGuia"], $guias)) {                                            
                                            ?>
                                            <td><button id="mensaje" name="mensaje" value="M" class="btn btn-danger btn-xs" onclick="enviarACancelar('<?= $idservicio ?>', '<?= $idempleado ?>')" title="Al dar click, crea mensaje de cancelación para este servicio" />M</button></td>
                                            <?php
                                        } else {
                                            $guias[$index1] = $serviciosFacturar[$index1]["numeroGuia"];
                                            ?>
                                            <td></td> 
                                            <?php
                                        }
                                        ?>                                        

                                        </tr>
                                        <?php
                                        $total += $serviciosFacturar[$index1]["valorCobrado"];
                                    }
                                    ?>
                                    <tr>
                                        <td scope="col"></td>
                                        <td scope="col"></td>
                                        <td scope="col"></td>
                                        <td scope="col"></td>
                                        <td scope="col"></td>                                         
                                        <td scope="col"></td>     
                                        <td scope="col"></td>                                        
                                        <th scope="col" colspan="2">Total Vlr. Facturar</th>
                                        <th scope="col"><?= number_format($total, 0, ".", ".") ?></th>
                                        <td scope="col"></td>
                                        <td scope="col"></td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </div>  
                    <div id="mensajes" class="col-lg-12 panel panel-success">
                        <div class="alert alert-dismissible alert-success">Los servicios por facturar de mas de 60 d&iacute;as se encuentran en color rojo</div>
                        <?php
//                        if ($mensaje === 1) {
//                            echo'<div class="alert alert-dismissible alert-danger">Se han encontrado <strong> gu&iacute;as repetidas </strong> en servicios.Puede enviar mensaje de revisión con el botón rojo pequeño con letra M</div>';
//                        }
//
//                        if ($atrasados === 1) {
//                            echo '<div class="alert alert-dismissible alert-danger">Hay servicios de <strong>meses anteriores por facturar.</strong>Estan de color rojo</div>';
//                        }
                        ?>
                    </div>
                    <div class="panel-body">                        
                        <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                        <button name="boton" id="botonGenerarExcel" type="button" class="btn btn-success btn-ls botonPropio" value="GENERAREXCEL" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GENERAR EXCEL <img src="../imagenes/excel.ico"></button>
                        <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>               
                    </div>
                </div>
            </div>             
        </body>
    </html>
    <?php
}

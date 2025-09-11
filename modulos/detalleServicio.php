<?php
session_start();

include_once '../clases/rol_boton.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    $rol_boton = new rol_boton();
    $serviciosFacturar = $rol_boton->retornarAnticiposPendientes(0);
    //var_dump($serviciosFacturar);
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
            <title>Men&uacute;</title>
            <link rel="icon" href="../imagenes/camion256.png">
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
            <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>
            <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">            
            <script src="../js/js_anticiposPendientes.js?n=<?= rand(0,3)?>" type="text/javascript"></script> 
            <script src="../js/bootstrap.min.js" type="text/javascript"></script>
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
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
                    <div id="mensajes">

                    </div>
                    <form method="post" action="../modulos/prefactura.php" name="formuarlio_index" >                        
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title">Listado</h3>
                            </div>
                            <div class="panel-body altura">
                                <div class="col-lg-12 panel" id="divFacturas" >
                                    <table class="table table-condensed table-hover ">
                                        <thead>
                                            <tr>
                                                <th>Id Servicio</th>
                                                <th>Guias</th>
                                                <th>Fecha Ant.</th>
                                                <th>Placa</th>
                                                <th>Propietario</th>
                                                <th>Vlr. Servicio</th>
                                                <th>Vlr. Adelanto</th>                                                
                                                <th>Diferencia</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $cantidad = count($serviciosFacturar);
                                            $totalPagar=0;
                                            $totalAdelantos=0;
                                            $totalServicios=0;

                                            for ($index1 = 0; $index1 < $cantidad; $index1++) {
                                                $total = intval($serviciosFacturar[$index1]["valorservicio"]) - intval($serviciosFacturar[$index1]["val_valorAdelanto"]);
                                                $totalPagar = $totalPagar + $total;
                                                $totalAdelantos=$totalAdelantos+$serviciosFacturar[$index1]["val_valorAdelanto"];
                                                $totalServicios=$totalServicios+$serviciosFacturar[$index1]["valorservicio"];
                                                ?>
                                                <tr>
                                                    <td><?= $serviciosFacturar[$index1]["idservicio"] ?></td>
                                                    <td><?= $serviciosFacturar[$index1]["val_numeroGuia"] ?></td>
                                                    <td><?= substr($serviciosFacturar[$index1]["val_fechaAnticipo"], 0, -6) ?></td>
                                                    <td><?= $serviciosFacturar[$index1]["placa"] ?></td>
                                                    <td><?= $serviciosFacturar[$index1]["nombresPropietario"] ?></td>
                                                    <td>$ <?= number_format($serviciosFacturar[$index1]["valorservicio"]); ?></td>
                                                    <td>$ <?= number_format($serviciosFacturar[$index1]["val_valorAdelanto"]); ?></td>                                                    
                                                    <td>$ <?= number_format(intval($serviciosFacturar[$index1]["valorservicio"]) - intval($serviciosFacturar[$index1]["val_valorAdelanto"])); ?></td>
                                                </tr>
                                                <?php } ?>
                                                <tr>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td align="right"><strong>Totales</strong></td>
                                                    <td>$ <?= number_format($totalServicios); ?></td>
                                                    <td>$ <?= number_format($totalAdelantos); ?></td>
                                                    <td>$ <?= number_format($totalPagar); ?></td>
                                                </tr>
                                        </tbody>
                                    </table>
                                </div> 
                            </div>
                        </div>                        
                        <div class="panel-body">                        
                            <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                            <!--<button onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" id="botonConsultar" name="boton" type="submit" class="btn btn-success btn-ls botonPropio" value="CONSULTAR">CONSULTAR <img src="../imagenes/clipboard_16.png"> </button>-->
                            <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>               
                        </div>
                    </form>  
                </div>
            </div>             
        </body>
    </html>
    <?php
}
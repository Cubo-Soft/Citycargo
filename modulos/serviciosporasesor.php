<?php
session_start();

include_once '../clases/rol_boton.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
    $rol_boton = new rol_boton();

    $listado = null;

    $listado = retornarLista(3);

    $cantidadPorSeguimiento = $rol_boton->seguimientosPendientes();    
    
    $cantidadAgendas = $rol_boton->retornarCantAgendaPendientes($_SESSION["emp_cedula"]);
    $listaPlacas = $rol_boton->retornarPlacas();
    $placa = retornarListaPlacas($listaPlacas, 2);    
    ?>
    <!DOCTYPE html>    
    <html>
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Men&uacute;</title>
            <link rel="icon" href="../imagenes/camion256.png">
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>
            <script src="../js/terceraMascara.js" type="text/javascript"></script>
            <script src="../js/js_consultasServicios.js" type="text/javascript"></script>
            <script src="../js/js_serviciosporasesor.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>             
            <script src="../js/js_funcionesVarias.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>            
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>
            <form method="post" action="../trafico/redirigir.php" name="formuarlio_index" >
                <div id="contenedor">
                    <div id="contenedor-index" class="row input-sm">                       
                        <div id="divImagenUsa" class="col-sm-4 input-sm"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-sm-4 input-sm">
                            <h1>Servicios por asesor</h1>
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
                            <h3 class="panel-title">Men&uacute; principal</h3>
                        </div>  
                        <div class="panel-body">
                            <div class='col-lg-12' >
                                <input type="hidden" id="idasesor" name="idasesor" value="<?= $_SESSION["emp_cedula"]; ?>" />
                                <input type="hidden" id="rol_id" name="rol_id" value="<?= $_SESSION["rol_id"]; ?>" />                                
                                <table class="table table-bordered" >
                                    <thead>
                                        <tr><th colspan="2" scope="col" >Consultas</th></tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2">Cantidad de servicios</td>
                                            <td>Calificaci&oacute;n por placa</td>                                            
                                        </tr>                                         
                                        <tr>
                                            <td colspan="2"><?= $listado ?></td>                                            
                                            <td><?= $placa; ?></td>                                            
                                        </tr>
                                    </tbody>
                                </table>                                
                            </div>                                                       
                            <div id="mensajes" class="col-lg-12">                        
                            </div>
                            <button type="button" value="REGRESAR" id="botonRegresar" name="botonRegresar" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                            <button name="boton" id="botonGenerarExcel" type="button" class="btn btn-success btn-ls botonPropio" value="GENERAREXCEL" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GENERAR EXCEL <img src="../imagenes/excel.ico"></button>
                            <button name="boton" id="botonSalir" type="button" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" class="btn btn-success btn-ls botonPropio" value="SALIR"> SALIR <img src="../imagenes/salir.png"> </button>
                        </div>  
                    </div>                    
                </div> 
            </form>           
        </body>
    </html>
    <?php
}
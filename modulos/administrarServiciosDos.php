<?php
session_start();

include_once '../clases/rol_boton.php';
include_once '../clases/funcionesVarias.php';

$buscarPor = null;
$idservicio = '0';
$guia = '0';
$anticipo = '0';

if (is_null($_SESSION["rol_id"])) {

    header("Location: ../index.php?null=null");
} else {

    //$borrado = '';

    /* if ($_SESSION["rol_id"] === '6' || $_SESSION["rol_id"] === '2') { */
    $borrado = "<input type='hidden' id='borrado' value='2' />";
    /* } else { */
    //$borrado = "<input type='hidden' id='borrado' value='3' />";
    /* } */

    if (isset($_GET["idservicio"])) {
        $idservicio = $_GET["idservicio"];
    }

    if (isset($_GET["idserv"])) {
        $idservicio = $_GET["idserv"];
    }

    if (isset($_GET["guia"])) {
        $guia = $_GET["guia"];
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
            <meta charset="iso-8859-1">
            <title>Administrar Servicios</title>
            <link rel="icon" href="../imagenes/favicon.ico">
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>            
            <script src="../js/js_administrarServiciosDos.js?n=<?= rand(0, 20) ?>" type="text/javascript"></script> 
            <script src="../js/js_comunes.js" type="text/javascript"></script>            
            <script src="../js/jquery.number.js" type="text/javascript"></script>            
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>            
            <script src="../js/terceraMascara.js" type="text/javascript"></script>
            <script src="../js/js_comunes.js" type="text/javascript"></script>
            <script src="../js/js_gestionarSeguimiento.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>                        
            <div>   
                <div id="contenedor-index" class="row">                       
                    <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_cotizacion"/></div>
                    <div id="texoDocumento" class="col-lg-4">
                        <h1>Administrar servicios</h1>
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
                <form method="post" action="#" name="formuarlio_index" >        
                    <input type="hidden" id="departamento" name="departamento" value="<?= $_SESSION["departamento"] ?>" />
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title"><strong>Consultar por:</strong></h3>
                        </div>
                        <div class="panel-body">
                            <table class="table table-hover">
                                <tr>
                                    <td>N&uacute;mero gu&iacute;a</td>                                          
                                    <td>N&uacute;mero servicio</td>
                                </tr>
                                <tr>                                    
                                    <td><input type="number" name="guia" id="guia" value="<?= $guia ?>" class="form-control input-sm" /></td>                                    
                                    <td><input type="number" name="idservicio" id="idservicio" value="<?= $idservicio ?>" class="form-control input-sm" /></td>                                        
                                </tr>
                                <tr>
                                    <td></td>
                                    <td><?= $borrado; ?></td>                                
                                </tr>
                            </table>                                 
                        </div>                                                                                                  
                        <div class="divBotonesAccion">
                            <table class="table" id="tblBotonesAccion">
                                <tr>
                                    <td><input type="button" class="btn btn-link" id="btnInfoGuias" value="Info. de gu&iacute;as" /></td>
                                    <td><input type="button" class="btn btn-link" id="btnSeguimientos" value="Seguimientos" /></td>
                                    <td><input type="button" class="btn btn-link" id="btnPruebasEntrega" value="Pruebas de entrega" /></td>
                                </tr>
                            </table>
                        </div>
                    </div>                    
                    <div id="mensajesGenerales"></div>
                    <div id="mensajes"></div>        
                    <div class="panel-body">                        
                        <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                            
                        <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>               
                    </div>
                </form>  
            </div>
        </body>
    </html>
    <?php
}

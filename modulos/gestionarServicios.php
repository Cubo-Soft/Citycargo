<?php
session_start();

include '../clases/servicios.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
    $servicio = new servicios();
    $lista = $servicio->retornarClientes();
    $guia = 0;
    $servicios = null;

    $cantidadServicios = $servicio->retornarServiciosPorFacturar(0);
    $cantidadAnticipos = $servicio->retornarAnticiposPendientes(1);
    $cantidadCancelar = $servicio->retornarServiciosPorCancelar();
    $cancelarServicios = "";

    if (isset($_GET["guia"])) {
        $guia = $_GET["guia"];
        $servicios = $servicio->retornarServicioPorGuia($guia);
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
            <title>Servicios CITYCARGO</title>
            <link rel="icon" href="../imagenes/favicon.ico">
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../js/js_gestionarServicios.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script> 
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <script src="../js/js_comunes.js" type="text/javascript"></script>
            <script src="../js/jquery.number.min.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>
            <form method="post" action="../trafico/gestionarServicios.php" name="formuarlio_index" >
                <input type="hidden" id="emp_cedula" name="emp_cedula" value="<?= $_SESSION["emp_cedula"] ?>" /> 
                <input type="hidden" id="departamento" name="departamento" value="<?= $_SESSION["departamento"] ?>" />
                <div id="contenedor">
                    <div id="contenedor-index" class="row input-sm">                       
                        <div id="divImagenUsa" class="col-sm-4 input-sm"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-sm-4 input-sm">
                            <h1>Gesti&oacute;n de servicios</h1>
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
                    <div class="col-lg-12 alert alert-success">
                        <div class="col-lg-2"><strong>Consultar por número de:</strong></div>
                        <div class="col-lg-10">
                            <select id="selectOpcion" class="form form-control">
                                <option value="0">...</option>
                                <option value="1">guía</option>
                                <option value="2">cuenta de cobro</option>
                            </select>
                        </div>                        
                    </div>                                       
                    <br/>                    
                    <br/>
                    <br/>
                    <br/>
                    <div id="contenedor-index2" class="alert alert-warning">
                        <div class="panel-heading">                                        
                            <h3 class="panel-title"><strong>Buscar servicio por número de guía</strong></h3>
                        </div>                        
                        <div class="panel-body" id="divOpciones">                            
                            <div class='col-lg-3'>
                                <div>
                                    <h4>Guia</h4>
                                </div>
                                <div>
                                    <input type='number' name='guia' id='guia' value="<?= $guia; ?>" class='form-control form-control-sm'>
                                </div>                            
                            </div>                            
                            <div class='col-lg-3'>
<!--                                <div>
                                    <h4>Fecha Inicial</h4>
                                </div>
                                <div>
                                    <input type='date' name='fechaInicial' id='fechaInicial' class='form-control form-control-sm'>
                                </div>-->
                            </div>
                            <div class='col-lg-3'>
<!--                                <div>
                                    <h4>Fecha Final</h4>
                                </div>
                                <div>
                                    <input type='date' name='fechaFinal' id='fechaFinal' value="<?= date("Y-m-d") ?>" class='form-control form-control-sm'>
                                </div>-->
                            </div>
                            <div class='col-lg-3'>
<!--                                <div>
                                    <h4>Clientes</h4>
                                </div>
                                <div>
                                    <?php //echo $lista; ?>
                                </div>-->
                            </div>
                        </div>
                        <div id="mensajesGestionarServicios">   
                            <?php
                            if ($servicios !== null) {
                                echo "<div class='alert alert-dismissible alert-success altura2'>"
                                . "<table class='table table-hover'><tr><th>Servicio</th><th>Fecha</th><th>Placa</th><th>Gu&iacute;a</th></tr>";
                                for ($index = 0; $index < count($servicios); $index++) {
                                    echo "<tr><td><a class='btn btn-success btn-xs' href='../modulos/mostrarServicio.php?idservicio=" . $servicios[$index]["idservicio"] . "' >" . $servicios[$index]["idservicio"] . "</a></td><td>" . substr($servicios[$index]["fecha"], 0, 10) . "</td><td>" . $servicios[$index]["placa"] . "</td><td>" . $servicios[$index]["guia"] . "</td></tr>";
                                }
                            }
                            echo "</table>"
                            . "</div>";
                            ?>
                        </div>                                                
                    </div>
                    <div id="contenedor-index3" class="alert alert-success">
                        <div class="panel-heading">                                        
                            <h3 class="panel-title"><strong>Asignar fecha de pago por número de cuenta de cobro</strong></h3>
                        </div>                        
                        <div class="panel-body" id="divOpciones">                            
                            <div class='col-lg-12 success'>
                                <div class="col-lg-3">
                                    <h4>Buscar número cuenta de cobro</h4>
                                </div>
                                <div class="col-lg-3">
                                    <input type='number' name='numeroCuentaCobro' id='numeroCuentaCobro' value="" class='form-control'>
                                </div>
                                <div id="divTextoFechaTransferencia" class="col-lg-3">
                                    
                                </div>
                                <div id="divBotonCrearFechaTransferencia" class="col-lg-3">
                                    
                                </div>
                            </div>                                                                                   
                        </div>
                        <div id="mensajesGestionarServiciosDos">   

                        </div>                                                
                    </div>
                    <div class="panel-body">
                        <div class="panel-body">
                            <button name="boton" id="botonRegresar" type="button" onmouseleave="colorSale(this);
                                    " onmouseenter="colorEntra(this);
                                    " class="btn btn-success btn-ls botonPropio" value="REGRESAR"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"> </button>                                                                        
                            <button name="boton" id="botonSalir" type="button" onmouseleave="colorSale(this);
                                    " onmouseenter="colorEntra(this);
                                    " class="btn btn-success btn-ls botonPropio" value="SALIR"> SALIR <img src="../imagenes/salir.png"> </button>
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
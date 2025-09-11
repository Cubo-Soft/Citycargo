<?php
session_start();

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
            <title>Modificaciones y Cancelaciones</title>
            <link rel="icon" href="../imagenes/camion256.png">
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">
            <script src="../bootstrap-3.3.7-dist/js/jquery-3.1.1.min.js" type="text/javascript"></script>            
            <script src="../js/js_modificaciones.js?n=<?= rand(0,3)?>" type="text/javascript"></script> 
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
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
                            <h1>Modificaciones y cancelaciones</h1>
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
                        <div class="panel-body" id="divOpciones">  
                            <div class="col-lg-6">
                                <div class="col-lg-12">
                                    <div class="page-header">                                        
                                        <legend>Anticipos</legend>                                        
                                    </div>                                                                       
                                    <div class="col-lg-12">
                                        <div class="col-lg-3">
                                            <h4>Acci&oacute;n</h4>
                                        </div> 
                                        <div class="col-lg-9">
                                            <select class="form-control" id="accionesAnticipos" name="accionesAnticipos">                   
                                                <option value="0">...</option>
                                                <option value="Modificar">Modificar</option>
                                                <option value="Adicionar">Adicionar</option>
                                                <option value="Cancelar">Cancelar</option>
                                                <!--<option value="Otro">Otro...</option>-->
                                            </select>
                                            <div id="agregarOtro"></div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12"> 
                                        <div class="col-lg-3">
                                            <h4>N&uacute;mero</h4>
                                        </div>
                                        <div class="col-lg-9">
                                            <input type="number" id="numeroAnticipo" name="numeroAnticipo" class="form-control" placeholder="Anticipo a modificar" />
                                        </div>
                                    </div>                                     
                                </div>
                                <div id="mensajesModificaciones" class="col-lg-12"></div>
                            </div> 
                            <div class="col-lg-6">
                                <div class="col-lg-12">
                                    <div class="page-header">                                        
                                        <legend>Servicios</legend>                                        
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="col-lg-3">
                                            <h4>Motivo</h4>
                                        </div>
                                        <div class="col-lg-9">
                                            <select id="motivos" name="motivos" class="form-control">
                                                <option value="0">...</option>
                                                <option value="Valor errado">Valor errado</option>
                                                <option value="Cambio de conductor">Cambio de conductor</option>
                                                <option value="Valor del servicio">Valor del servicio</option>
                                                <option value="Cliente cancela">Cliente cancela</option>                                
                                            </select>                                    
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="col-lg-3">
                                            <h4>Gu&iacute;a</h4>
                                        </div>
                                        <div class="col-lg-9">
                                            <input type="number" id="numeroGuia" name="numeroGuia" class="form-control" placeholder="Gu&iacute;a del servicio a cancelar" />
                                        </div>
                                    </div>
                                </div>                                 
                            </div>

                        </div>                        
                    </div>                    
                    <div id="mensajesGenerales"></div>
                    <div class="panel-body">
                        <div class="panel-body">
                            <button name="boton" id="botonRegresar" type="button" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" class="btn btn-success btn-ls botonPropio" value="REGRESAR"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"> </button>                                                                                        
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
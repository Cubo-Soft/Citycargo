<?php
session_start();
include_once '../clases/vehiculo.php';
include_once '../clases/funcionesVarias.php';
if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
    $listaMarcas = null;
    $placa = null;
    $vehiculo = new vehiculos();
    $lista = $vehiculo->retornarMarcasVehiculos();

    $listaMarcas = "<select class='form-control' id='listaMarcas'>"
            . "<option value='0'>...</option>";
    for ($index = 0; $index < count($lista); $index++) {
        $listaMarcas .= "<option value='" . $lista[$index]["id"] . "'>" . $lista[$index]["marca"] . "</option>";
    }
    $listaMarcas .= "<!--<option value='-1'>Crear marca</option>-->";
    $listaMarcas .= "</select>";

    if (@$_GET["pl"] !== '') {
        $placa = @$_GET["pl"];
    } else {
        $placa = '';
    }
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Veh&iacute;culos</title>
            <link rel="icon" href="../imagenes/favicon.ico">            

            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        

            <script src="../js/js_vehiculos.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>            
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>
            <form action="#" method="POST">
                <div id="contenedor">
                    <div id="contenedor-index" class="row">                       
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1>Gesti&oacute;n de veh&iacute;culos</h1>
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
                    <div id="datosCliente" class="col-lg-12">
                        <fieldset>    
                            <legend></legend>
                            <div class="form-group">
                                <label for="lblPlaca" class="col-lg-2 control-label">Placa</label>
                                <div class="col-lg-10">
                                    <input class="form-control" id="placaVehiculo" name="placaVehiculo" placeholder="Placa del vehículo" type="text" value="<?= $placa ?>" >                                    
                                </div>
                                <div id="msjPlaca"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblPlaca" class="col-lg-2 control-label">Marca</label>
                                <div class="col-lg-10" id="divListaMarcas">
                                    <?= $listaMarcas ?>                                    
                                </div>
                                <div id="msjMarcas"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblPlaca" class="col-lg-2 control-label">Modelo</label>
                                <div class="col-lg-10" id="divListaMarcas">
                                    <input class="form-control" id="modeloVehiculo" name="modeloVehiculo" title="Año del módelo del vehículo" type="number"  >
                                </div>
                                <div id="msjModelo"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblPlaca" class="col-lg-2 control-label">Tipo de carroceria</label>
                                <div class="col-lg-10" id="divListaMarcas">
                                    <?= retornarListaCarrocerias(); ?>
                                </div>
                                <div id="msjTipoCarroceria"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblPlaca" class="col-lg-2 control-label">Capacidad carga</label>
                                <div class="col-lg-10" id="divListaMarcas">
                                    <div class="col-lg-8">
                                        <input type="number" value="0" class="form-control" id="capacidadCarga" name="capacidadCarga" title="Capacidad de carga expresada en kilogramos" >
                                    </div>
                                    <div class="col-lg-4">
                                        Expresada en kilogramos. Ej. 3000 
                                    </div>
                                </div>
                                <div id="msjCarga"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblPlaca" class="col-lg-2 control-label">Medidas</label>
                                <div class="col-lg-10" id="divListaMarcas">
                                    <div class="col-lg-3">
                                        <div class="col-lg-4">
                                            Ancho
                                        </div>
                                        <div class="col-lg-8">
                                            <input class="form-control" id="ancho" name="ancho" placeholder="Ancho" type="number" value="0" step="0.01" min="0" max="1000" >
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="col-lg-4">
                                            Alto
                                        </div>
                                        <div class="col-lg-8">
                                            <input class="form-control" id="alto" name="alto" placeholder="Alto" type="number" value="0" step="0.01" min="0" max="1000" >
                                        </div>
                                    </div>
                                    <div class="col-lg-3">
                                        <div class="col-lg-4">
                                            Largo
                                        </div>
                                        <div class="col-lg-8">
                                            <input class="form-control" id="largo" name="largo" placeholder="Largo" type="number" value="0" step="0.01" min="0" max="1000" >
                                        </div>
                                    </div> 
                                    <div class="col-lg-3">
                                        <div class="col-lg-4">
                                            Vol. veh&iacute;culo
                                        </div>
                                        <div class="col-lg-8">
                                            <input class="form-control" id="volVehiculo" name="volVehiculo" placeholder="Volúmen vehículo" type="text"  >
                                        </div>
                                    </div>
                                </div>
                                <div id="msjCarga"></div>
                            </div>                          
                            <div class="form-group">
                                <label for="lblTipoVehiculo" class="col-lg-2 control-label">Tipo veh&iacute;culo</label>
                                <div class="col-lg-10">
                                    <?= retornarListaVehiculos(); ?>
                                </div>
                                <div id="msjEstadoVehiculo"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblEstadoVehiculo" class="col-lg-2 control-label">Estado</label>
                                <div class="col-lg-10">
                                    <select name="estadoVehiculo" id="estadoVehiculo" class="form-control col-lg-8">
                                        <option value="0">...</option>  
                                        <option value="ACTIVO">ACTIVO</option>  
                                        <option value="INACTIVO">INACTIVO</option>  
                                    </select>
                                </div>
                                <div id="msjEstadoVehiculo"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblReportarNovedades" class="col-lg-2 control-label">Reportar novedades</label>
                                <div class="col-lg-10">
                                    <select class="form form-control" id="reportar_novedad" name="reportar_novedad" />
                                    <option value="1">SI</option>
                                    <option value="0">NO</option>
                                    </select>
                                </div>
                                <div id="msjEstadoVehiculo"></div>
                            </div>
                        </fieldset>
                    </div>
                    <div class="col-lg-12" id="mensajes">
                        <div class="alert alert-dismissible alert-success">Para las <strong>medidas del veh&iacute;culo</strong>; por favor use el punto como separador de decimales</div>
                    </div>
                    <div id="botones" class="col-lg-12" >   
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" id="botonRegresar" name="botonRegresar" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                        
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="CREAR CONDUCTOR" name="botonCrearPlaca" id="botonCrearPlaca" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > CREAR PLACA <img src="../imagenes/save_16.png"></button>                            
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="MODIFICAR CONDUCTOR" name="botonModificarPlaca" id="botonModificarPlaca" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MODIFICAR PLACA <img src="../imagenes/camion_16.png"></button>                            
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" name="botonSalir" id="botonSalir" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > SALIR <img src="../imagenes/salir.png"></button>                            
                    </div>
                </div>   
            </form> 
        </body>
    </html>
    <?php
    if (@$_GET["pj"] == "1") {
        echo '<script>alert("Conductor creado con éxito");</script>';
    }
    if (@$_GET["pj"] == "2") {
        echo '<script>alert("No hay datos para crear el conductor");</script>';
    }
}


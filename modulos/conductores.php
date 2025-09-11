<?php
session_start();

$identificacion = null;

include_once '../clases/conductores.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
    $conductor = new conductores();

    if (isset($_GET["id"])) {
        $cedula = $_GET["id"];
    } else {
        $cedula = '';
    }
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Conductores</title>
            <link rel="icon" href="../imagenes/camion256.png">            

            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        

            <script src="../js/js_conductores.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>
            <script src="../js/js_funcionesVarias.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>
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
            <form action="#" method="POST" id="formularioConductor">
                <div id="contenedor">
                    <div id="contenedor-index" class="row">                       
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1>Gesti&oacute;n conductores y propietarios</h1>
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
                                <div class="col-lg-6">
                                    <label for="lblnitEmpresa" class="col-lg-3 control-label">C&eacute;dula</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" id="cedulaConductor" name="cedulaConductor" placeholder="C&eacute;dula del conductor" type="number" value="<?= $cedula; ?>">
                                        <input type="hidden" name="idConductor" id="idConductor" value="" />                                    
                                    </div>
                                    <div id="msjIdConductor"></div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="lblnombresConductor" class="col-lg-3 control-label">Nombres</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" id="nombresConductor" name="nombresConductor" placeholder="Nombres conductor" type="text" onkeyup="cambiaTamanio(this);">
                                    </div>
                                    <div id="msjNombresConductor"></div>
                                </div>

                            </div>                            
                            <div class="form-group">
                                <div class="col-lg-6">
                                    <label for="lblapellidosConductor" class="col-lg-3 control-label">Apellidos</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" id="apellidosConductor" name="apellidosConductor" placeholder="Apellidos conductor" type="text" onkeyup="cambiaTamanio(this);">
                                    </div>
                                    <div id="msjApellidosConductor"></div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="lbldireccionConductor" class="col-lg-3 control-label">Direcci&oacute;n</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" id="direccionConductor" name="direccionConductor" placeholder="Direcci&oacute;n del conductor" type="text" onkeyup="cambiaTamanio(this);">
                                    </div>
                                    <div id="msjDireccionConductor"></div>
                                </div>

                            </div>                            
                            <div class="form-group">
                                <div class="col-lg-6">
                                    <label for="lbltelefonoConductor" class="col-lg-3 control-label">Tel&eacute;fono</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" id="telefonoConductor" name="telefonoConductor" placeholder="Tel&eacute;fono del conductor" type="number">
                                    </div>
                                    <div id="msjTelefonoConductor"></div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="lblEmail" class="col-lg-3 control-label">Correo electr&oacute;nico</label>
                                    <div class="col-lg-9">
                                        <input class="form-control" id="email" name="email" placeholder="Correo electrónico" type="email">
                                    </div>
                                    <div id="msjTelefonoConductor"></div>
                                </div>

                            </div>                            
                            <div class="form-group">
                                <div class="col-lg-6">
                                    <label for="select" class="col-lg-3 control-label">Placas</label>
                                    <div class="col-lg-9" id="placas">
                                        <?php $conductor->retornarListaPlacas(); ?>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="select" class="col-lg-3 control-label">Perfil</label>
                                    <div class="col-lg-9">
                                        <select id="perfil" name="perfil" class="form-control">
                                            <option value="0">...</option>
                                            <option value="10">CONDUCTOR</option>
                                            <option value="9">PROPIETARIO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>                                                        
                            <div class="form-group">
                                <div class="col-lg-6">
                                    <label for="select" class="col-lg-3 control-label">Estado</label>
                                    <div class="col-lg-9">
                                        <select id="estadoConductor" name="estadoConductor" class="form-control">
                                            <option value="0">...</option>
                                            <option value="ACTIVO">ACTIVO</option>
                                            <option value="INACTIVO">INACTIVO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="select" class="col-lg-3 control-label">Estado relaci&oacute;n veh&iacute;culo</label>
                                    <div class="col-lg-9">
                                        <select id="estadoRelacion" name="estadoRelacion" class="form-control">
                                            <option value="0">...</option>
                                            <option value="ACTIVO">ACTIVO</option>
                                            <option value="INACTIVO">INACTIVO</option>
                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <div class="col-lg-6">
                                    <label for="select" class="col-lg-3 control-label">Municipio</label>
                                    <div class="col-lg-9">
                                        <?php $conductor->retornarMunicipios(); ?>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <label for="select" class="col-lg-3 control-label">Reportar novedades</label>
                                    <div class="col-lg-9">
                                            <select class="form form-control" id="reportar_novedad" name="reportar_novedad" />
                                            <option value="1">SI</option>
                                            <option value="0">NO</option>
                                            </select>                                        
                                        <div class="col-lg-9">
                                        </div>
                                    </div>                            
                                    </fieldset>
                                </div>
                                <div id="mensajes" class="col-lg-12" >                        
                                    <?php
                                    if (@$_GET["msj"] == "1") {
                                        echo '<script>alert("Ha sido dirigido al módulo de Conductores para ingresar información faltante del conductor.\nen las'
                                        . 'casillas que no tienen datos\nPresione la tecla Tab |<-- -->| dos veces\nCuando termine de ingresar los datos, presione en el botón Modificar\n!!!Gracias!!!");'
                                        . '$(document).ready(function () {'
                                        . '$("#idConductor").focus();'
                                        . '});</script>';
                                    }

                                    if (@$_GET["pj"] == "1") {
                                        echo '<div class="alert alert-dismissible alert-danger" >Conductor creado con éxito</div>';
                                    }
                                    if (@$_GET["pj"] == "2") {
                                        echo '<div class="alert alert-dismissible alert-danger" >No hay datos para crear el conductor</div>';
                                    }
                                    if (@$_GET["pj"] == "3") {
                                        echo '<div class="alert alert-dismissible alert-danger" >Se debe crear el conductor <strong>' . $_GET["id"] . '</strong> y/o vincularlo a un veh&iacute;culo. ¿Si es un n&uacute;mero de c&eacute;dula?</div>';
                                    }
                                    if (@$_GET["pj"] == "4") {
                                        echo '<div class="alert alert-dismissible alert-danger" >El v&iacute;nculo del conductor: <strong>' . $_GET["id"] . '</strong> el veh&iacute;culo es INACTIVO, por favor verifique. </div>';
                                    }
                                    ?>
                                </div>
                                <div id="botones" class="col-lg-12" >   
                                    <button type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" name="boton" id="botonRegresar" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                    
                                    <button type="button" class="btn btn-success btn-ls botonPropio" value="CREAR CONDUCTOR" name="botonCrearConductor" id="botonCrearConductor" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" title="Crea un conductor o propietario" > CREAR <img src="../imagenes/save_16.png"></button>                            
                                    <button type="button" class="btn btn-success btn-ls botonPropio" value="MODIFICAR CONDUCTOR" name="botonModificarConductor" id="botonModificarConductor" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" title="Modifica datos de un conductor" > MODIFICAR <img src="../imagenes/user_16.png"></button>                        
                                    <button type="button" class="btn btn-success btn-ls botonPropio" value="AGREGAR PLACA" name="botonAgregarPlaca" id="botonAgregarPlaca" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" title="Agrega una placa a un propietario ya creado" > AGREGAR PLACA <img src="../imagenes/placa_25.png"></button>
                                    <button type="button" class="btn btn-success btn-ls botonPropio" value="LIMPIAR" name="botonLimpiar" id="botonLimpiar" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > LIMPIAR <img src="../imagenes/clipboard_16.png"></button>                    
                                    <button type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" name="botonSalir" id="botonSalir" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > SALIR <img src="../imagenes/salir.png"></button>                            
                                </div>
                            </div>   
                            </form>                   
                            </body>
                            </html>
                            <?php
                        }
<?php
session_start();
$identificacion = null;
$listaAsesores = null;

//var_dump($_SESSION);

include_once '../clases/cliente.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
    $cliente = new cliente();
    $asesores = $cliente->retornarAsesores();

    if ($_SESSION["departamento"] === "COMERCIAL") {
        $listaAsesores = "<input type='number' id='listaAsesores' name='listaAsesores' value='" . $_SESSION["emp_cedula"] . "' class='form-control' />";
    } else {
        $listaAsesores = "<select class='form-control' name='listaAsesores' id='listaAsesores'>"
                . "<option value='0'>...</option>";
        for ($index3 = 0; $index3 < count($asesores[0]); $index3++) {
            $listaAsesores .= "<option value='" . $asesores[0][$index3]["emp_cedula"] . "'>" . $asesores[0][$index3]["nombreAsesor"] . "</option>";
        }

        for ($index3 = 0; $index3 < count($asesores[1]); $index3++) {
            $listaAsesores .= "<option value='" . $asesores[1][$index3]["emp_cedula"] . "'>" . $asesores[1][$index3]["nombreAsesor"] . "</option>";
        }

        $listaAsesores .= "</select>";
    }

    $municipio = $cliente->retornarMunicipios();

    $listaMunicipios = "<select class='form-control' name='listaMunicipios' id='listaMunicipios'>"
            . "<option value='0'>...</option>";
    for ($index1 = 0; $index1 < count($municipio); $index1++) {
        $listaMunicipios .= "<option value='" . $municipio[$index1]["mun_id"] . "'>" . $municipio[$index1]["mun_nombre"] . "</option>";
    }
    $listaMunicipios .= "</select>";
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Clientes</title>
            <link rel="icon" href="../imagenes/favicon.ico">            
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>            
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>            
            
            <script src="../js/js_clientes.js?n=<?= rand(0,3)?>" type="text/javascript"></script>
            <script src="../js/bootstrap.min.js" type="text/javascript"></script>      
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/digitoVerificacion.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>            
            <div id="contenedor">
                <div id="contenedor-index" class="row">                       
                    <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                    <div id="texoDocumento" class="col-lg-4">
                        <h1>Gesti&oacute;n de clientes</h1>
                    </div>                                        
                    <div id="divDatosIniciales" class="col-lg-4">                           
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="badge"><?php echo $_SESSION["nombre_usuario"]; ?></span>
                                Usuario
                            </li>
                            <li class="list-group-item">
                                <span class="badge" id="departamento"><?php echo $_SESSION["departamento"]; ?></span>
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
                            <label for="lblnitEmpresa" class="col-lg-3 control-label">NIT</label>
                            <div class="col-lg-5">
                                <input class="form-control" id="nit" name="nit" placeholder="NIT de la empresa sin digito de verificaci&oacute;n" type="number" value="">                                    
                                <input type="hidden" name="idCliente" id="idCliente" value="" />                                    
                            </div>                                
                            <label for="lblnitEmpresa" class="col-lg-2 control-label">Digito verificaci&oacute;n</label>
                            <div class="col-lg-2">
                                <input class="form-control" id="digitoVerificacion" name="digitoVerificacion" type="number" value="">                                    
                                <input type="hidden" name="idCliente" id="idCliente" value="" />                                    
                            </div>                                
                        </div>
                        <div class="form-group">
                            <label for="lblnombreCliente" class="col-lg-3 control-label">NOMBRE</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="nombreCliente" name="nombreCliente" placeholder="Nombre cliente" type="text" onkeyup="cambiaTamanio(this);">
                            </div>                                
                        </div>
                        <div class="form-group">
                            <label for="lblapellidosConductor" class="col-lg-3 control-label">CONTACTO</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="contactoCliente" name="contactoCliente" placeholder="Contacto cliente" type="text" onkeyup="cambiaTamanio(this);">
                            </div>                                
                        </div>
                        <div class="form-group">
                            <label for="lbldireccionCliente" class="col-lg-3 control-label">DIRECCI&Oacute;N</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="direccionCliente" name="direccionCliente" placeholder="Direcci&oacute;n del cliente" type="text" onkeyup="cambiaTamanio(this);">
                            </div>                                
                        </div>
                        <div class="form-group">
                            <label for="lblCorreoCliente" class="col-lg-3 control-label">CORREO ELECTRONICO</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="correoCliente" name="correoCliente" placeholder="Correo electrónico del cliente" type="email">
                            </div>                                
                        </div>                           
                        <div class="form-group">
                            <label for="lblTelefonoUno" class="col-lg-3 control-label">TEL&Eacute;FONO</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="telefonoUno" name="telefonoUno" placeholder="Tel&eacute;fono contacto" type="text" value="0">
                            </div>                                
                        </div> 
                        <div class="form-group">
                            <label for="lblObjeto" class="col-lg-3 control-label">OBJETO EMPRESA</label>
                            <div class="col-lg-9">
                                <input class="form-control" id="objeto" name="objeto" placeholder="Pequeña descripci&oacute;n del objeto de la empresa no mayor a 30 caracteres" type="text" >
                            </div>                                
                        </div> 
                        <div class="form-group">
                            <label for="lblListaAsesores" class="col-lg-3 control-label">ASESOR</label>
                            <div class="col-lg-9">
                                <?= $listaAsesores ?>
                            </div>                                
                        </div> 
                        <div class="form-group">
                            <label for="lblTelefonoDos" class="col-lg-3 control-label">MUNICIPIOS</label>
                            <div class="col-lg-9">
                                <?= $listaMunicipios ?>
                            </div>                                
                        </div> 
                        <div class="form-group">
                            <label for="select" class="col-lg-3 control-label">ESTADO CLIENTE</label>
                            <div class="col-lg-9">
                                <select id="estadoCliente" name="estadoCliente" class="form-control">
                                    <option value="0">...</option>
                                    <option value="ACTIVO">ACTIVO</option>
                                    <option value="INACTIVO">INACTIVO</option>
                                </select>
                            </div>
                        </div>
                    </fieldset>
                </div>
                <div id="mensajes" class="col-lg-12" ></div>
                <div id="botones" class="col-lg-12" >   
                    <form action="../trafico/redirigir.php" method="POST">
                        <button type="submit" class="btn btn-success btn-ls botonPropio" value="REGRESAR" name="boton" id="botonRegresar" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="CREAR CLIENTE" name="botonCrearCliente" id="botonCrearCliente" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" title="Crea un cliente" > CREAR <img src="../imagenes/save_16.png"></button>                        
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="LIMPIAR" name="botonLimpiar" id="botonLimpiar" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > LIMPIAR <img src="../imagenes/clipboard_16.png"></button>                    
                        <?php
                        if ($_SESSION["departamento"] !== 'COMERCIAL') {
                            echo '<button type="button" class="btn btn-success btn-ls botonPropio" value="DIRECCIONES" name="boton" id="botonDirecciones" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);"> DIRECCIONES <img src="../imagenes/home_16.png"></button>'
                            . '<button type="button" class="btn btn-success btn-ls botonPropio" value="MODIFICAR CLIENTE" name="botonModificarCliente" id="botonModificarCliente" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" title="Modifica datos del cliente" > MODIFICAR <img src="../imagenes/user_16.png"></button>';
                        }
                        ?>                        
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" name="botonSalir" id="botonSalir" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > SALIR <img src="../imagenes/salir.png"></button>                            
                    </form>                    
                </div>
            </div>                             
        </body>
    </html>
    <?php
}
<?php
session_start();

include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    if (@$_GET["pj"] === null || $_GET["pj"] === '') {

        include_once '../clases/rol_boton.php';
        $rol_boton = new rol_boton();
        $clientes = $rol_boton->retornarClientes();
        $lista = '<select name="idCliente" id="idCliente" class="form-control">'
                . '<option value="0">...</option>';
        for ($index = 0; $index < count($clientes); $index++) {
            $lista .= "<option value=" . $clientes[$index]["cli_documento"] . ">" . $clientes[$index]["cli_nombre"] . "</option>";
        }
        $lista .= "</select>";
        ?>
        <!DOCTYPE html>
        <!--
        To change this license header, choose License Headers in Project Properties.
        To change this template file, choose Tools | Templates
        and open the template in the editor.
        -->
        <html>
            <head>
                <meta charset="UTF-8">
                <title>Nota Cr&eacute;dito</title>            
                <link rel="icon" href="../imagenes/camion256.png">

                <link href="../css/css2.css" rel="stylesheet" type="text/css" />
                <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
                <?= retornarRecursosBootstrap(); ?>
                <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        

                <script src="../js/js_notaCredito.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>   
                <script src="../js/numeros_letras.js" type="text/javascript"></script>            
                <script src="../js/accionesenprograma.js" type="text/javascript"></script>
                <script src="../js/cambioColores.js" type="text/javascript"></script>
                <!-- Evitar cache -->
                <meta http-equiv="Expires" content="0">
                <meta http-equiv="Last-Modified" content="0">
                <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
                <meta http-equiv="Pragma" content="no-cache">
            </head>
            <body>
                <form method="POST" action="../trafico/notaCredito.php" id="formularioCotizacion" >
                    <div id="contenedor-index" class="row paddinMargin">
                        <div id="divImagenUsa" class="col-xs-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-xs-4">
                            <h1>Nota cr&eacute;dito</h1>
                        </div> 
                        <div id="divDatosIniciales" class="col-sm-4">
                            <ul class="list-group">                                
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $_SESSION["nombre_usuario"]; ?></span>
                                    Usuario
                                </li>                               
                                <li class="list-group-item">
                                    <span class="badge"><?php echo date('Y-m-d'); ?></span>
                                    Fecha
                                </li>
                                <li class="list-group-item">
                                    <span class="badge">Pendiente</span>
                                    <input type="hidden" id="numeroCotizacion" name="numeroCotizacion" value="0" />
                                    Consecutivo
                                </li>
                            </ul>
                        </div>
                    </div>     
                    <div id="datosCliente"class="panel panel-success small paddinMargin">
                        <div class="col-lg-12 panel">
                            <div class="col-lg-2 text-center">
                                <h4>Lista de clientes</h4>
                            </div>
                            <div class="col-lg-10">
                                <?= $lista; ?>    
                            </div>
                        </div>
                        <div id="mensajes" class="col-lg-12"></div>
                        <div id="botones" class="col-lg-12" >                        
                            <button type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" id="botonRegresar" name="botonRegresar" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png" ></button>
                            <button type="button" value="REALIZAR CONSULTA" id="botonRealizarConsulta" name="botonRealizarConsulta" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > REALIZAR CONSULTA <img src="../imagenes/document_16.png"></button>
                            <button type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" id="botonSalir" name="botonSalir" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>
                        </div>
                    </div>
                </form>
            </body>
        </html>

        <?php
    } else {

        include_once '../clases/conexion.php';
        require_once '../fpdf17/fpdf.php';

        $conexion = new Conexion();

        $nit = 0;

        $numeroCotizacion = "select max(num_numeroCuenta) as numeroCuenta from numeroscuentas;";
        $prepare = $conexion->prepare($numeroCotizacion);
        $prepare->execute();
        $resultado = $prepare->fetchAll();
        $numeroCuenta = $resultado[0]["numeroCuenta"];

        if ($_GET["pj"] != null) {
            $nit = $_GET["pj"];

            $consulta = "select c.cli_id,c.cli_nombre,c.cli_contacto,c.cli_direccion,m.mun_nombre,"
                    . "c.cli_correo,c.cli_telefono,c.cli_id_municipio as mun_id "
                    . "from cliente as c,municipios as m "
                    . "where c.cli_id_municipio=m.mun_id "
                    . "and c.cli_documento='" . $_GET["pj"] . "';";
            $prepare = $conexion->prepare($consulta);
            $prepare->execute();
            $arreglo = $prepare->fetchAll();

            if (@$arreglo[0]["cli_nombre"] <> null) {
                $clienteNo = 0;
                $cli_contacto = $arreglo[0]["cli_contacto"];
                $cli_telefono = $arreglo[0]["cli_telefono"];
                $cli_direccion = $arreglo[0]["cli_direccion"];
                $mun_nombre = $arreglo[0]["mun_nombre"];
                $cli_correo = $arreglo[0]["cli_correo"];
                $cli_id = $arreglo[0]["cli_id"];
                $cli_nombre = $arreglo[0]["cli_nombre"];

                $consulta = "select mun_id,mun_nombre from municipios;";

                $prepare = $conexion->prepare($consulta);
                $prepare->execute();
                $arreglo = $prepare->fetchAll(PDO::FETCH_ASSOC);

                $mostrarCiudad = "<select id='idCiudadCliente' name='idCiudadCliente' class='form-control input-sm' >";
                for ($i = 0; $i < count($arreglo); $i++) {
                    $nombreCiudad = $arreglo[$i]["mun_nombre"];
                    if ($nombreCiudad === $mun_nombre) {
                        $mostrarCiudad .= "<option value=" . $arreglo[$i]["mun_id"] . " selected>" . $arreglo[$i]["mun_nombre"] . "</option>";
                    } else {
                        $mostrarCiudad .= "<option value=" . $arreglo[$i]["mun_id"] . ">" . $arreglo[$i]["mun_nombre"] . "</option>";
                    }
                }
                $mostrarCiudad .= "</select>";
            } else {
                $clienteNo = 1;
                $cli_contacto = "";
                $cli_telefono = "";
                $cli_direccion = "";
                $cli_correo = "";
                $cli_id = "";
                $cli_nombre = "";

                $consulta = "select mun_id,mun_nombre from municipios;";

                $prepare = $conexion->prepare($consulta);
                $prepare->execute();
                $arreglo = $prepare->fetchAll(PDO::FETCH_ASSOC);

                $mostrarCiudad = "<select id='idCiudadCliente' name='idCiudadCliente'>";
                for ($i = 0; $i < count($arreglo); $i++) {
                    if ($i == 0) {
                        $mostrarCiudad .= "<option value='...'>...</option>";
                    } else {
                        $mostrarCiudad .= "<option value=" . $arreglo[$i]["mun_id"] . ">" . $arreglo[$i]["mun_nombre"] . "</option>";
                    }
                }
                $mostrarCiudad .= "</select>";
            }
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
                <meta charset="UTF-8">
                <title>Nota Cr&eacute;dito</title>            
                <link rel="icon" href="../imagenes/camion256.png">
                <link href="../css/css2.css" rel="stylesheet" type="text/css" />
                <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
                <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
                <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">             
                <script src="../js/js_notaCredito.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>   
                <script src="../js/numeros_letras.js" type="text/javascript"></script>
                <script src="../js/bootstrap.min.js" type="text/javascript"></script>            
                <script src="../js/accionesenprograma.js" type="text/javascript"></script>
                <script src="../js/cambioColores.js" type="text/javascript"></script>
                <!-- Evitar cache -->
                <meta http-equiv="Expires" content="0">
                <meta http-equiv="Last-Modified" content="0">
                <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
                <meta http-equiv="Pragma" content="no-cache">
            </head>
            <body class="container">
                <form method="POST" action="../trafico/generarpdfnotacredito.php" id="formularioCotizacion" >
                    <div id="contenedor-index" class="row paddinMargin">
                        <div id="divImagenUsa" class="col-xs-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-xs-4">
                            <h1>NOTA CR&Eacute;DITO</h1>
                        </div> 
                        <div id="divDatosIniciales" class="col-sm-4">
                            <ul class="list-group">                                
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $_SESSION["nombre_usuario"]; ?></span>
                                    Usuario
                                </li>                               
                                <li class="list-group-item">
                                    <span class="badge"><?php echo date('Y-m-d'); ?></span>
                                    Fecha
                                </li>
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $numeroCuenta; ?></span>
                                    <input type="hidden" id="numeroCotizacion" name="numeroCotizacion" value="<?= $numeroCuenta ?>" />
                                    Consecutivo
                                </li>
                            </ul>
                        </div>
                    </div>     
                    <div id="datosCliente"class="panel panel-success small paddinMargin">
                        <div class="panel-heading">                                        
                            <h3 class="panel-title">Datos del cliente</h3>
                        </div>  
                        <div class="table table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td colspan="10"><div id="divTblDatosCliente">DATOS DEL CLIENTE</div></td>
                                </tr>
                                <tr>
                                    <td>NIT</td>
                                    <td><input type="text" value="<?= $nit; ?>" class="form-control input-sm" disabled="disabled" />
                                        <input type="hidden" name="nit_cliente" id="nit_cliente"  value="<?= $nit; ?>" />                                   
                                    <td>CONTACTO</td>
                                    <td><input type="text" value="<?php echo $cli_contacto; ?>" class="form-control input-sm"  disabled="disabled"/>
                                        <input type="hidden" name="contacto_cliente" id="contacto_cliente" value="<?php echo $cli_contacto; ?>"/></td>
                                </tr>
                                <tr>
                                    <td>NOMBRE O RAZ&Oacute;N SOCIAL</td>
                                    <td><input type="text" disabled="disabled" value="<?php echo $cli_nombre; ?>" class="form-control input-sm" />
                                        <input type="hidden" name="nombre_cliente" id="nombre_cliente" value="<?php echo $cli_nombre; ?>" /></td>
                                    <td>TEL&Eacute;FONO</td>
                                    <td><input type="text" disabled="disabled" value="<?php echo $cli_telefono; ?>" class="form-control input-sm" />
                                        <input type="hidden" name="telefono_cliente" id="telefono_cliente" value="<?php echo $cli_telefono; ?>" /></td>
                                </tr>
                                <tr>
                                    <td>DIRECCI&Oacute;N</td>
                                    <td><input type="text" disabled="disabled" value="<?php echo $cli_direccion; ?>" class="form-control input-sm" />
                                        <input type="hidden" name="direccion_cliente" id="direccion_cliente" value="<?php echo $cli_direccion; ?>"/></td>
                                    <td>CIUDAD</td>
                                    <td><?php echo $mostrarCiudad; ?></td>                                   
                                </tr>
                                <tr>                                
                                    <td>CORREO ELECTRONICO</td>
                                    <td><input type="text" disabled="disabled" value="<?php echo $cli_correo; ?>" class="form-control input-sm" />
                                        <input type="hidden" name="correoElectronico_cliente" id="correoElectronico_cliente" value="<?php echo $cli_correo; ?>" /></td>
                                    <td></td>
                                    <td></td>                                
                                </tr>
                                <tr>
                                    <td colspan="4"><div>Para realizar cambios al cliente, dar click <a href="../modulos/clientes.php?nit='<?= $nit ?>'" target="_blank"> aqu&iacute;</a></div></td>
                                </tr>
                            </table>
                        </div>
                        <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $cli_id; ?>" />                        
                    </div> 
                    <div id="mensaje">

                    </div>
                    <div id="valorServicio" class="panel panel-success small">
                        <div class="panel-heading">                                        
                            <h3 class="panel-title">Valor del servicio</h3>
                        </div>
                        <div id="divEncabezadoTabla" class="table table-responsive">
                            <table  class="table table-striped" >
                                <tr>
                                    <td><span class="color">No.</span></td>                                                                                                   
                                    <td><span class="color">No. GUIA</span></td>                                                                      
                                    <td><span class="color">N&Uacute;MERO FAC.</span></td>                                        
                                    <td><span class="color">FECHA FAC.</span></td>     
                                    <td><span class="color">V/R UNITARIO</span></td>
                                </tr>
                            </table>
                        </div>
                        <div id="cabezoteValorServicio" class="table table-responsive">
                            <table id="tablaValorServicio" class="table table-striped">                            
                                <?php
                                for ($i = 1; $i < 16; $i++) {
                                    echo '<tr>'
                                    . '<td><span class="color">' . $i . '</span></td>'
                                    . '<td><input type="text" name="guia' . $i . '" id="guia' . $i . '" value="0" onfocusout="traerDatosGuia(this);" /></td>'
                                    . '<td><input type="text" name="numFac' . $i . '" id="numFac' . $i . '" value="0" maxlength="13" /></td>'
                                    . '<td><input type="date" id="fecFac' . $i . '" name="fecFac' . $i . '" /></td>'
                                    . '<td><input type="text" name="vlrUnit' . $i . '" id="vlrUnit' . $i . '" value="0" /></td>'
                                    . '</tr>';
                                }
                                ?>

                            </table>  
                        </div>
                        <div id="cuerpoValorServicio">
                            <table id="tablaCuerpoValorServicio">
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td class="">VALOR EN LETRAS</td>
                                    <td><input type="text" id="vlrLetras" name="vlrLetras" value="" width="150px"/></td>                                        
                                    <td><input type="button" value="TOTAL" id="botontotal" name="botontotal"/></td>
                                    <td><input type="text" name="vaSerOrigen15" id="vaSerOrigen15" value="" /></td>
                                </tr>                                    
                            </table>                                
                        </div>                            
                    </div>
                    <div id="descripcionServicio" class="panel panel-success small">
                        <div class="panel-heading">                                        
                            <h3 class="panel-title">Descripci&oacute;n</h3>
                        </div>                        
                        <textarea name="detalle_servicio" id="detalle_servicio" cols="130" rows="4"></textarea>       
                    </div>
                    <div id="botones">                        
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" id="botonRegresar" name="botonRegresar" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png" ></button>
                        <button type="submit" class="btn btn-success btn-ls botonPropio" value="GENERAR PDF" id="botonGenerarPdf" name="boton" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GENERAR NOTA CR&Eacute;DITO <img src="../imagenes/pdf_16.png"></button> 
                        <button type="button" value="LISTAR CLIENTES" id="listarClientes" name="boton" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > LISTAR CLIENTES <img src="../imagenes/empleados_16.jpg"></button>
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" id="botonSalir" name="botonSalir" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>
                    </div>

                    <div id="mensajes">

                    </div>
                </form>        
            </body>        
        </html>
        <?php
        if ($clienteNo == 1) {
            echo '<script>alert("EL CLIENTE NO EXISTE, POR FAVOR LLENE TODOS LOS DATOS CORRESPONDIENTES");</script>';
        }
    }
}
$conexion = null;


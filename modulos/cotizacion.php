<?php
session_start();

require_once '../fpdf17/fpdf.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    if(isset($_GET["pj"])){
        $pj=$_GET["pj"];
    }else{
        $pj=null;
    }
    
    if ($pj === null || $pj === '1') {
        
        include_once '../clases/rol_boton.php';
        include_once '../clases/funcionesVarias.php';
        $rol_boton = new rol_boton();
        $clientes = $rol_boton->retornarClientes();
        $lista = retornarListaClientes($clientes);
        $arregloCotizaciones=$rol_boton->retornarNumeroCotizacion();
        $numeroCotizacion=$arregloCotizaciones[0]["numeroCotizacion"];
        ?>

        <!DOCTYPE html>    
        <html>
            <head>
                <meta charset="UTF-8">
                <title>Cotizaci&oacute;n</title>            
                <link rel="icon" href="../imagenes/favicon.ico">

                <link href="../css/css2.css" rel="stylesheet" type="text/css" />
                <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
                <?= retornarRecursosBootstrap(); ?>
                <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        

                <script src="../js/js_cotizacion.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>
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
                <form method="POST" action="../trafico/cotizacion.php" id="formularioCotizacion" >                
                    <div id="contenedor-index" class="row paddinMargin">
                        <div id="divImagenUsa" class="col-sm-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-sm-4">
                            <h1>Cotizaci&oacute;n</h1>
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
                                    <span class="badge"><?php echo $numeroCotizacion; ?></span>
                                    <input type="hidden" name="numeroCotizacion" value="<?= $numeroCotizacion; ?>" />
                                    Consecutivo
                                </li>
                            </ul>
                        </div>
                        <div></div>
                        <div class="col-lg-12 panel">
                            <div class="col-lg-2 text-center">
                                <h4>Lista de clientes</h4>
                            </div>
                            <div class="col-lg-10">
                                <?= $lista; ?>    
                            </div>
                        </div>
                    </div>
                    <div id="botones">                        
                        <button type="button" value="REGRESAR" id="botonRegresar" name="REGRESAR" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                        <button type="button" value="INICIAR COTIZACION" id="iniciarCotizacion" name="boton" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > INICIAR COTIZACI&Oacute;N <img src="../imagenes/document_16.png"></button> 
                        <button type="button" value="SALIR" id="botonSalir" name="botonSalir" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>
                    </div>
                    <div id="mensajes"></div>
                </form>
            </body>
        </html>

        <?php
    } else {

        include_once '../clases/conexion.php';
        include_once '../clases/funcionesVarias.php';
        $conexion = new Conexion();
        $consulta = "select max(num_numeroCotizacion) as numeroCotizacion from numeroscotizacion;";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();
        $numeroCotizacion = $resultado[0]["numeroCotizacion"];
        ?>
        <!DOCTYPE html>    
        <html>
            <head>
                <meta charset="UTF-8">
                <title>Cotizaci&oacute;n CITYCARGO</title>            
                <link rel="icon" href="../imagenes/favicon.ico">
                
                <link href="../css/css2.css" rel="stylesheet" type="text/css" />
                <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
                <?= retornarRecursosBootstrap(); ?>
                <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>                        
                
                <script src="../js/js_cotizacion.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>
                <script src="../js/bootstrap.min.js" type="text/javascript"></script>                        
                <script type="text/javascript">
                    jQuery(function ($) {
                        $.datepicker.regional['es'] = {
                            closeText: 'Cerrar',
                            prevText: '&#x3c;Ant',
                            nextText: 'Sig&#x3e;',
                            currentText: 'Hoy',
                            monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio',
                                'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
                            monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun',
                                'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
                            dayNames: ['Domingo', 'Lunes', 'Martes', 'Mi&eacute;rcoles', 'Jueves', 'Viernes', 'S&aacute;bado'],
                            dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mi&eacute;', 'Juv', 'Vie', 'S&aacute;b'],
                            dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'S&aacute;'],
                            weekHeader: 'Sm',
                            dateFormat: 'dd/mm/yy',
                            firstDay: 1,
                            isRTL: false,
                            showMonthAfterYear: false,
                            yearSuffix: ''};
                        $.datepicker.setDefaults($.datepicker.regional['es']);
                    });
                </script>
                <script src="../js/accionesenprograma.js" type="text/javascript"></script>
                <script src="../js/cambioColores.js" type="text/javascript"></script>
                <!-- Evitar cache -->
                <meta http-equiv="Expires" content="0">
                <meta http-equiv="Last-Modified" content="0">
                <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
                <meta http-equiv="Pragma" content="no-cache">
            </head>
            <body class="container">
                <form method="POST" action="../trafico/generarpdf.php" id="formularioCotizacion" >                
                    <div id="contenedor-index" class="row paddinMargin">
                        <div id="divImagenUsa" class="col-sm-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-sm-4">
                            <h1>COTIZACI&Oacute;N</h1>
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
                                    <span class="badge"><?php echo $numeroCotizacion; ?></span>
                                    <input type="hidden" name="numeroCotizacion" value="<?= $numeroCotizacion; ?>" />
                                    Consecutivo
                                </li>
                            </ul>
                        </div>
                    </div>                                        
                    <div id="datosCliente"class="panel panel-success small paddinMargin">
                        <?php
                        echo '<input type="hidden" id="departamento" value="' . $_SESSION["departamento"] . '" />';
                        ?>
                        <div class="panel-heading">                                        
                            <h3 class="panel-title">Datos del cliente</h3>
                        </div>
                        <div class="table table-responsive">
                            <table class="table table-striped">                        
                                <tr>
                                    <td>NIT</td>
                                    <td><input type="text" value="<?= $_GET['pj']; ?>" disabled class="form-control input-sm" />
                                        <input type="hidden" name="nit_cliente" id="nit_cliente"  value="<?php
                                        if ($_GET["pj"] != null) {
                                            echo $_GET["pj"];
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

                                                $mostrarCiudad = "<select id='idCiudadCliente' name='idCiudadCliente' class='form-control input-sm'>";
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
                                        ?>" />                                   
                                    <td>CONTACTO</td>
                                    <td><input type="text" name="contacto_cliente" id="contacto_cliente" value="<?php echo $cli_contacto; ?>" class="form-control input-sm"/></td>
                                </tr>
                                <tr>
                                    <td>NOMBRE O RAZ&Oacute;N SOCIAL</td>
                                    <td><input type="text" name="nombre_cliente" id="nombre_cliente" value="<?php echo $cli_nombre; ?>" class="form-control input-sm" /></td>
                                    <td>TEL&Eacute;FONO</td>
                                    <td><input type="text" name="telefono_cliente" id="telefono_cliente" value="<?php echo $cli_telefono; ?>" class="form-control input-sm" /></td>
                                </tr>
                                <tr>
                                    <td>DIRECCI&Oacute;N</td>
                                    <td><input type="text" name="direccion_cliente" id="direccion_cliente" value="<?php echo $cli_direccion; ?>" class="form-control input-sm" /></td>                                
                                    <td>CIUDAD</td>
                                    <td><?php echo $mostrarCiudad; ?></td>                                   
                                </tr>
                                <tr>                                                                
                                    <td>CORREO ELECTRONICO</td>
                                    <td><input type="text" name="correoElectronico_cliente" id="correoElectronico_cliente" value="<?php echo $cli_correo; ?>" class="form-control input-sm" /></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="1">TIPO DE SERVICIO:</td>
                                    <td colspan="3"><div>
                                            <select id="tipoServicio" name="tipoServicio" class='form-control input-sm' >
                                                <option value="...">...</option>
                                                <option value="M_I_C_I">CARGA INMEDIATA</option>                                    
                                                <option value="C_M_S_P">CARGA MASIVA, SEMIMASIVA Y PAQUETEO</option>
                                                <option value="M_E">MENSAJER&Iacute;A EXPRESA</option>
                                                <option value="M_L_N">MUDANZAS LOCALES Y NACIONALES</option>
                                                <option value="P_E_L">PROYECTOS ESPECIALES EN LOG&Iacute;STICA</option>
                                            </select>                                                                
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="1">DESCRIPCI&Oacute;N DEL SERVICIO</td>
                                    <td colspan="3"><textarea name="detalle_servicio" id="detalle_servicio" cols="130" rows="4"></textarea></td>
                                </tr>
                            </table>
                        </div>                    
                    </div> 
                    <input type="hidden" name="id_cliente" id="id_cliente" value="<?php echo $cli_id; ?>" />            
                    <!--<div id="cabezoteValorServicio2" class="panel panel-success small paddinMargin">-->
                        <div class="panel-heading">                                        
                            <h3 class="panel-title">Datos del servicio</h3>
                        </div>
                        <div class="table table-responsive">
                            <div id="divEncabezadoTabla" class="table table-responsive">
                                <table  class="table table-striped" >
                                    <tr>
                                        <td><span class="color">No.</span></td>                                    
                                        <td class="tamanioTr" ><span class="color">ORIGEN</span></td>                                
                                        <td class="tamanioTr"><span class="color" >DESTINO</span></td> 
                                        <td class="tamanioTr"><span class="color">CANTIDAD</span></td>                                                                      
                                        <td class="tamanioTr"><span class="color">V/R MIN. DEC.</span></td>                                        
                                        <td class="tamanioTr"><span class="color">V/R FLETE</span></td>     
                                        <td class="tamanioTr"><span class="color">V/R SEGURO</span></td>
                                        <td class="tamanioTr"><span class="color">OT. CARGOS</span></td>
                                        <td class="tamanioTr"><span class="color">V/R TOTAL</span></td>
                                    </tr>
                                </table>
                            </div>    
                            <div id="cuerpoDescripcionServicio" class="table table-responsive">
                                <table id="tablaValorServicio" class="table table-striped">                                                            
                                    <?php
                                    for ($i = 1; $i < 16; $i++) {
                                        echo '<tr>'
                                        . '<td><input type="text" value="' . $i . '" disabled size="1" /></td>'
                                        . '<td><input type="text" id="origen' . $i . '" name="origen' . $i . '" class="tamanioTr2" /></td>'
                                        . '<td><input type="text" id="destino' . $i . '" name="destino' . $i . '" class="tamanioTr2" /></td>'
                                        . '<td><input type="text" name="cantidad' . $i . '" id="cantidad' . $i . '"  maxlength="13" class="tamanioTr2" /></td>'
                                        . '<td><input type="text" name="vaSerVlrDeclarar' . $i . '" id="vaSerVlrDeclarar' . $i . '" value="0" class="input1TablaDescripcionServicio" class="tamanioTr2" /></td>'
                                        . '<td><input type="text" name="vaSerVlrFlete' . $i . '" id="vaSerVlrFlete' . $i . '" value="0" class="input1TablaDescripcionServicio" class="tamanioTr2" /></td>'
                                        . '<td><input type="text" name="vaSerVlrSeguro' . $i . '" id="vaSerVlrSeguro' . $i . '" value="0" class="input1TablaDescripcionServicio" class="tamanioTr2" /></td>'
                                        . '<td><input type="text" name="vaOtrosCargos' . $i . '" id="vaOtrosCargos' . $i . '" value="0" class="input1TablaDescripcionServicio" class="tamanioTr2" /></td>'
                                        . '<td><input type="text" name="vaSerVlrTotal' . $i . '" id="vaSerVlrTotal' . $i . '" value="0" class="input1TablaDescripcionServicio" class="tamanioTr2" /></td>'
                                        . '</tr>';
                                    }
                                    ?>

                                </table>  
                            </div>
                        </div>                    
                    <!--</div>-->
                    <!--<div id="cuerpoValorServicio" class="panel panel-success small paddinMargin">-->
                        <div>
                            <table>
                                <tr>
                                    <td class="tamanioTr" ></td>
                                    <td class="tamanioTr" ></td>
                                    <td class="tamanioTr" ></td>
                                    <td class="tamanioTr" ></td>
                                    <td class="tamanioTr" ></td>
                                    <td class="tamanioTr" ></td>
                                    <td></td>
                                    <td><input type="button" value="TOTAL" id="botontotal" name="botontotal" class="botonTotal" /></td>
                                    <td><input type="text" name="vaSerOrigen15" id="vaSerOrigen15" value="" /></td>
                                </tr>                                
                            </table>
                        </div>
                        <div>
                            <div>FORMA DE PAGO</div>
                            <div>
                                <select id="formaPago" name="formaPago" class='form-control input-sm'>
                                    <option value="...">...</option>
                                    <option value="CONTADO">CONTADO</option>                                
                                    <option value="CREDITO">CREDITO</option>                                
                                    <option value="OTRO">OTRO</option>                                
                                </select>
                            </div>
                            <div>OBSERVACIONES</div>
                            <div>
                                <textarea name="observaciones_forma_pago" id="observaciones_forma_pago" cols="50" rows="3"></textarea>
                            </div>
                            <div id="div-formaPago">Espeficique el tipo de pago:<input type="text" name="textformaPago" id="textformaPago" /></div>
                        </div>
                    <!--</div>-->
                    <div id="botones">                        
                        <button type="button"  value="REGRESAR" id="botonRegresar" name="REGRESAR" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                        <button type="submit" value="GENERAR PDF" id="botonGenerarPdf" name="boton" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GENERAR PDF <img src="../imagenes/pdf_16.png"></button> 
                        <button type="button" value="LISTAR CLIENTES" id="listarClientes" name="boton" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > LISTAR CLIENTES <img src="../imagenes/empleados_16.jpg"></button> 
                        <button type="button" value="SALIR" id="botonSalir" name="botonSalir" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>
                    </div>
                    <div id="mensajes"></div>
                </form>        
            </body>        
        </html>
        <?php
    }
    if (@$clienteNo == 1) {
        echo '<script>alert("EL CLIENTE NO EXISTE, POR FAVOR LLENE TODOS LOS DATOS CORRESPONDIENTES");</script>';
    }
}
$conexion = null;


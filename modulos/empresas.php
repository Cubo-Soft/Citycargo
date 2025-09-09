<?php
session_start();

include_once '../clases/municipios.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    $mun = new municipios();
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
            <title>Empresas</title>
            <link rel="icon" href="../imagenes/favicon.ico">            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
            <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet"> 
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>   
            <script src="../js/js_empresas.js?n=<?= rand(0,3)?>" type="text/javascript"></script>
            <script src="../js/bootstrap.min.js" type="text/javascript"></script>   
            <script>
                $(document).ready(function () {
                    $('#nitEmpresa').blur(function () {
                        $.ajax({
                            url: "../trafico/consultarEmpresa.php",
                            method: "POST",
                            data: {"nitEmpresa": $('#nitEmpresa').val()},
                            success: function (data) {
                                var obj = jQuery.parseJSON(data);
                                console.log(obj);
                                if (obj === false) {
                                    alert('No se registran datos para la identificacion del cliente');
                                } else {
                                    $('#idEmpresa').val(obj[0]['cli_id']);
                                    $('#nombreEmpresa').val(obj[0]['cli_nombre']);
                                    $('#contactoEmpresa').val(obj[0]['cli_contacto']);
                                    $('#direccionEmpresa').val(obj[0]['cli_direccion']);
                                    $('#correoEmpresa').val(obj[0]['cli_correo']);
                                    $('#telefonoEmpresa').val(obj[0]['cli_telefono']);
                                    $('#faxEmpresa').val(obj[0]['cli_fax']);
                                    $('#listaMunicipios option[value=' + obj[0]['mun_id'] + ']').attr('selected', true);
                                    $('#nitEmpresa').prop('disabled', true);
                                }
                            },
                            error: function () {
                                $('#mensajes').html("Se esta generando un erro al consultar la empresa con AJAX, por favor informe al desarrollador del produclto");
                            }
                        });
                    });

                    $('#botonAnticipos').click(function () {
                        var identCon = prompt("Digite el número de cédula del conductor");
                        if (identCon !== "") {
                            window.location.href = "../modulos/anticipos.php?pj=" + identCon;
                        } else {
                            alert("Para retornar a anticipos debe digitar un número de cedula");
                        }
                    });

                    $("#botonCrearEmpresa").click(function () {

                        if ($("#nitEmpresa").val() === "") {
                            $("#msjNitEmpresa").html("Digite el NIT de la empresa");
                            $("#nitEmpresa").focus().css({"background-color": "#D9FDFF"});
                            return false;
                        }

                        if ($("#nombreEmpresa").val() === "") {
                            $("#msjNombreEmpresa").html("Digite el NIT de la empresa");
                            $("#nombreEmpresa").focus().css({"background-color": "#D9FDFF"});
                            return false;
                        }

                        if ($("#contactoEmpresa").val() === "") {
                            $("#msjContactoEmpresa").html("Digite el NIT de la empresa");
                            $("#contactoEmpresa").focus().css({"background-color": "#D9FDFF"});
                            return false;
                        }

                        if ($("#direccionEmpresa").val() === "") {
                            $("#msjDireccionEmpresa").html("Digite el NIT de la empresa");
                            $("#direccionEmpresa").focus().css({"background-color": "#D9FDFF"});
                            return false;
                        }

                        if ($("#correoEmpresa").val() === "") {
                            $("#msjCorreoEmpresa").html("Digite el NIT de la empresa");
                            $("#correoEmpresa").focus().css({"background-color": "#D9FDFF"});
                            return false;
                        }

                        if ($("#telefonoEmpresa").val() === "") {
                            $("#msjTelefonoEmpresa").html("Digite el NIT de la empresa");
                            $("#telefonoEmpresa").focus().css({"background-color": "#D9FDFF"});
                            return false;
                        }

                        if ($("#faxEmpresa").val() === "") {
                            $("#msjFaxEmpresa").html("Digite el NIT de la empresa");
                            $("#faxEmpresa").focus().css({"background-color": "#D9FDFF"});
                            return false;
                        }

                        return true;
                    });

                    $("#botonCuentaCobro").click(function () {
                        var identCon = prompt("Digite el número de cédula del conductor");
                        if (identCon !== "") {
                            window.location.href = "../modulos/cuentaCobro.php?pj=" + identCon;
                        } else {
                            alert("Para retornar a anticipos debe digitar un número de cedula");
                        }
                    });

                    $("#botonBorrar").click(function () {
                        location.reload();
                    });

                    $("#botonGuias").click(function () {
                        window.location.href = "../modulos/procesosGuias.php?";
                    });
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
        <body>
            <form class="form-horizontal" action="../trafico/crearEmpresa.php" method="POST" >
                <div id="contenedor">
                    <div id="contenedor-index" class="row">                       
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1>Gesti&oacute;n empresas</h1>
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
                                <label for="lblnitEmpresa" class="col-lg-2 control-label">NIT</label>
                                <div class="col-lg-10">
                                    <input class="form-control" id="nitEmpresa" name="nitEmpresa" placeholder="NIT Empresa" type="number">
                                    <input type="hidden" id="idEmpresa" name="idEmpresa" />
                                </div>
                                <div id="msjNitEmpresa"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblNombreEmpresa" class="col-lg-2 control-label">NOMBRE EMPRESA</label>
                                <div class="col-lg-10">
                                    <input class="form-control" id="nombreEmpresa" name="nombreEmpresa" placeholder="Nombre empresa" type="text">
                                </div>
                                <div id="msjNombreEmpresa"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblContacto" class="col-lg-2 control-label">CONTACTO</label>
                                <div class="col-lg-10">
                                    <input class="form-control" id="contactoEmpresa" name="contactoEmpresa" placeholder="Persona de contacto" type="text">
                                </div>
                                <div id="msjContactoEmpresa"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblDireccion" class="col-lg-2 control-label">DIRECCI&Oacute;N</label>
                                <div class="col-lg-10">
                                    <input class="form-control" id="direccionEmpresa" name="direccionEmpresa" placeholder="Direcci&oacute;n de la empresa" type="text">
                                </div>
                                <div id="msjDireccionEmpresa"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblCorreoEmpresa" class="col-lg-2 control-label">CORREO</label>
                                <div class="col-lg-10">
                                    <input class="form-control" id="correoEmpresa" name="correoEmpresa" placeholder="Correo electr&oacute;nico de la empresa" type="email">
                                </div>
                                <div id="msjCorreoEmpresa"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblTelefono" class="col-lg-2 control-label">TEL&Eacute;FONO</label>
                                <div class="col-lg-10">
                                    <input class="form-control" id="telefonoEmpresa" name="telefonoEmpresa" placeholder="Tel&eacute;fono de la empresa" type="number">
                                </div>
                                <div id="msjTelefonoEmpresa"></div>
                            </div>
                            <div class="form-group">
                                <label for="lblFax" class="col-lg-2 control-label">FAX</label>
                                <div class="col-lg-10">
                                    <input class="form-control" id="faxEmpresa" name="faxEmpresa" placeholder="Fax de la empresa" type="number">
                                </div>
                                <div id="msjFaxEmpresa"></div>
                            </div>                               
                            <div class="form-group">
                                <label for="select" class="col-lg-2 control-label">MUNICIPIO</label>
                                <div class="col-lg-10">
                                    <?php $mun->retornarMunicipios(); ?>
                                </div>
                            </div>
                        </fieldset>                    
                    </div>
                    <div id="mensajes"></div>
                    <div id="botones">   
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" id="botonRegresar" name="botonRegresar" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                            
                        <button type="submit" class="btn btn-success btn-ls botonPropio" value="CREAR EMPRESA" name="boton" id="botonCrearEmpresa" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > CREAR EMPRESA <img src="../imagenes/empresa_16.png"></button>                            
                        <button type="submit" class="btn btn-success btn-ls botonPropio" value="MODIFICAR EMPRESA" name="boton" id="botonModificarEmpresa" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MODIFICAR EMPRESA <img src="../imagenes/empresa_16.png"></button>                            
                        <!--<button type="button" class="btn btn-success btn-ls botonPropio" value="GUIAS" name="boton" id="botonGuias" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GUIAS <img src="../imagenes/pencil_16.png"></button>                            
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="ANTICIPOS" name="boton" id="botonAnticipos" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > ANTICIPOS <img src="../imagenes/bundle_16.ico"></button>                            
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="CUENTA DE COBRO" name="boton" id="botonCuentaCobro" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > CUENTA DE COBRO <img src="../imagenes/cuentaCobro_16.ico"></button>-->
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" name="boton" id="boton" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > SALIR <img src="../imagenes/salir.png"></button>                            
                    </div>
                </div>
            </form>
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>                                
            <script src="js/bootstrap.min.js"></script>
        </body>
    </html>
    <?php
    if (@$_GET["pj"] == "1") {
        echo '<script>alert("Empresa creada con éxito");</script>';
    }
    if (@$_GET["pj"] == "2") {
        echo '<script>alert("Ya existe una empresa con este número de NIT");</script>';
    }

    if (@$_GET["pj"] == "3") {
        echo '<script>alert("Empresa modificada con éxito");</script>';
    }
}


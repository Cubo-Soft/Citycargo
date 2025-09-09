var completo = null, arreglo = null, arregloPlacas = null;
$(document).ready(function () {

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });
    $("#botonVehiculos").click(function () {
        $(location).attr('href', '../modulos/vehiculos.php');
    });
    $("#listaAsesores").change(function () {
        $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Los servicios vinculados al anterior asesor no ser&aacute;n modificados</div>');
    });
    $("#botonDirecciones").click(function () {
        $(location).attr('href', '../modulos/direcciones.php');
    });
    
    $("#botonListar").click(function () {
        $(location).attr('href', '../modulos/clientes_usuarios_guias.php');
    });
    
    /*
     * Modifica los datos del cliente en la base de datos
     */
    $("#botonModificarCliente").click(function () {

        if (verficarFormulario() === 10) {

            if (confirm('Se van a modificar los datos del cliente\n¿Esta seguro?') && parseInt($("#idCliente").val()) > 0) {

                $.ajax({
                    url: "../trafico/Clientes.php",
                    data: {
                        'caso': '3',
                        'cli_id': $("#idCliente").val(),
                        'municipio': $("#listaMunicipios").val(),
                        'nit': $("#nit").val(),
                        'nombreCliente': $("#nombreCliente").val(),
                        'contacto': $("#contactoCliente").val(),
                        'direccion': $("#direccionCliente").val(),
                        'correo': $("#correoCliente").val(),
                        'telefonoUno': $("#telefonoUno").val(),
                        'objeto': $("#objeto").val(),
                        'cedulaAsesor': $("#listaAsesores").val(),
                        'estadoCliente': $("#estadoCliente").val(),
                    },
                    type: "POST",
                    success: function (data) {
                        var obj = JSON.parse(data);
                        if (parseInt(obj.estado) === 8) {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-success"><strong>Se ha modificado el cliente de manera exitosa</div>');
                        } else {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Ha ocurrido un error al modificar el cliente. Por favor presione F5 e intentelo nuevamente. Si persiste por favor informe</div>');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Ha ocurrido un error en AJAX funcion botonModificarCliente");
                    }
                });
            } else {
                $("#mensajes").html('<div class="alert alert-dismissible alert-info"><strong>Si no decidio hacer el cambio, en realidad el cliente existe?</div>');
            }
        }
    });
    $("#botonCrearCliente").click(function () {

        if (verficarFormulario() === 10) {
            $.ajax({
                url: "../trafico/Clientes.php",
                data: {
                    'caso': '2',
                    'nit': $("#nit").val(),
                    'nombreCliente': $("#nombreCliente").val(),
                    'contacto': $("#contactoCliente").val(),
                    'direccion': $("#direccionCliente").val(),
                    'correo': $("#correoCliente").val(),
                    'telefonoUno': $("#telefonoUno").val(),
                    'objeto': $("#objeto").val(),
                    'cedulaAsesor': $("#listaAsesores").val(),
                    'estadoRelacion': $("#estadoCliente").val(),
                    'municipio': $("#listaMunicipios").val()
                },
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj === 2) {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-success"><strong>Correcto!</strong> Se ha creado el cliente.</div>');
                        $("#idCliente").val('');
                        $("#nombreCliente").val('');
                        $("#contactoCliente").val('');
                        $("#direccionCliente").val('');
                        $("#correoCliente").val('');
                        $('#telefonoUno').val('0');
                        $("#objeto").val('');
                        $("#listaAsesores").val('0');
                        $("#listaMunicipios").val('0');
                        $("#estadoCliente").val('0');
                        $("#botonCrearCliente").hide();
                    } else {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> Ha ocurrido un error al crear al cliente, por favor informar.</div>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX botonCrearConductor.click");
                }
            });
        } else {
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Por favor revise que todos los datos de la empresa se encuentren correctos</div>');
        }

    });

    $("#nit").blur(function () {

        if ($("#nit").val() > 0) {
            
            if ($("#departamento").text() === 'COMERCIAL') {
                $("#nit").val('');
                $("#nit").focus();
                $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Para crear una empresa por favor llene el formulario, luego presione el bot&oacute;n CREAR</div>');
            } else {
                $.ajax({
                    url: "../trafico/Clientes.php",
                    data: {'caso': '1',
                        'nit': $("#nit").val()
                    },
                    type: "POST",
                    success: function (data) {
                        var obj = JSON.parse(data);
                        if (obj.empresa.length > 0) {
                            $("#idCliente").val(obj.empresa[0].cli_id);
                            $("#nombreCliente").val(obj.empresa[0].cli_nombre);
                            $("#contactoCliente").val(obj.empresa[0].cli_contacto);
                            $("#direccionCliente").val(obj.empresa[0].cli_direccion);
                            $("#correoCliente").val(obj.empresa[0].cli_correo);
                            $("#listaMunicipios").val(obj.empresa[0].mun_id);
                            $("#telefonoUno").val(obj.empresa[0].cli_telefono);
                            $("#objeto").val(obj.empresa[0].cli_objeto);
                            $("#estadoCliente").val(obj.empresa[0].estado);
                            if (obj.asesor.length === 0) {
                                $("#mensajes").html('<div class="alert alert-dismissible alert-warning">No se registra asesor para esta empresa. Por favor seleccionelo y presione el bot&oacute;n MODIFICAR</div>');
                            } else {
                                $("#listaAsesores").val(obj.asesor[0].cedula);
                            }
                            $("#botonCrearCliente").hide();
                        } else {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-warning">No se registran datos. ¿Desea <strong>CREAR</strong> cliente? Por favor tenga presente el <strong>D&iacute;gito de verificaci&oacute;n</strong></div>');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Ha ocurrido un error en AJAX funcion $('#nit').blur(function () {");
                    }
                });
            }
        }
    });
    $("#botonLimpiar").click(function () {
        limpiar();
    });
});

function limpiar() {
    $("#idCliente").val('');
    $("#nombreCliente").val('');
    $("#contactoCliente").val('');
    $("#direccionCliente").val('');
    $("#correoCliente").val('');
    $('#telefonoUno').val('0');
    $("#objeto").val('');
    $("#listaAsesores").val('0');
    $("#listaMunicipios").val('0');
    $("#estadoCliente").val('0');
    $('#mensajes').html('');
    $("#botonCrearCliente").show();
    $("#nit").val('');
}

function cambiaTamanio(valor) {
    var id = null;
    id = "#" + valor.id;
    $(id).val($(id).val().toUpperCase());
}

function verficarFormulario() {

    //digVer digito de verificación
    var digVer = calcularDigitoVerificacion($("#nit").val());    

	var direccionCliente=$("#direccionCliente").val();

    if ($("#nit").val().length <= 5) {

        $("#nit").focus();
        $("#mensajes").html('<div class="alert alert-danger">M&iacute;nimo 5 digitos en el NIT de la empresa <br> </div>');
        return false;
    } else if (parseInt($("#digitoVerificacion").val()) !== parseInt(digVer)) {
        $("#digitoVerificacion").focus();
        $("#mensajes").html('<div class="alert alert-danger">Al parecer no es un NIT real. Por favor indique el D&iacute;gito de verificaci&oacute;n de la empresa</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = 1;
    }

    if ($("#nombreCliente").val().length <= 2) {
        $("#nombreCliente").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>NOMBRE</strong> de la empresa</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#contactoCliente").val().length <= 4) {
        $("#contactoCliente").focus();
        $("#mensajes").html('<div class="alert alert-danger">Por favor digite un <strong>CONTACTO</strong> de la empresa</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#direccionCliente").val().length <= 4) {
        $("#direccionCliente").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>DIRECCI&Oacute;N</strong> de la empresa</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if(direccionCliente.indexOf("#")!==-1){
        $("#direccionCliente").val(direccionCliente.replace("#","No"));
    }

    if ($("#correoCliente").val().length <= 6) {
        $("#correoCliente").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>CORREO EL&Eacute;CTRONICO</strong> de la empresa</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#telefonoUno").val() === '0') {
        $("#telefonoUno").focus();
        $("#mensajes").html('<div class="alert alert-danger">Por favor digite al menos un <strong>TEL&Eacute;FONO</strong> de contacto</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#listaAsesores").val() === '0') {
        $("#listaAsesores").focus();
        $("#mensajes").html('<div class="alert alert-danger">Seleccione un <strong>ASESOR</strong> de la empresa</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#objeto").val() === '0') {
        $("#objeto").focus();
        $("#mensajes").html('<div class="alert alert-danger">Por favor haga una breve descripci&oacute;n del <strong>OBJETO</strong> de la empresa</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#listaMunicipios").val() === '0') {
        $("#listaMunicipios").focus();
        $("#mensajes").html('<div class="alert alert-danger">Seleccione una <strong>CIUDAD</strong> de la empresa</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#estadoCliente").val() === '0') {
        $("#estadoCliente").focus();
        $("#mensajes").html('<div class="alert alert-danger">Seleccione el <strong>ESTADO</strong> de la empresa</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }
    return completo;
}

var boton_ = null;
function colorEntra(boton) {
    boton_ = "#" + boton.id;
    $(boton_).addClass("btn-info");
}

function colorSale(boton) {
    boton_ = "#" + boton.id;
    $(boton_).removeClass("btn-info");
}

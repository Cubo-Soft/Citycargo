$(document).ready(function () {

    var placa = null;

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $("#botonConductores").click(function () {
        window.location.href = "../modulos/conductores.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    /*
     * Cambia las minusculas a mayusculas
     */
    $("#placaVehiculo").keyup(function () {
        $("#placaVehiculo").val($("#placaVehiculo").val().toUpperCase());
    });

    $("#largo").focusout(function () {
        calcularVolumenes();
    });

    /*
     * verifica si una placa se encuentra creada en la base de datos
     */
    $("#placaVehiculo").blur(function () {

        if ($("#placaVehiculo").val().match(/^[A-Z Ñ]{3}[0-9]{3}$/)) {
            $.ajax({
                url: "../trafico/vehiculo.php",
                data: {
                    'caso': '1',
                    'placa': $("#placaVehiculo").val()
                },
                type: "POST",
                success: function (respuesta) {
                    var obj = JSON.parse(respuesta);
                    if (obj.estado === '0') {
                        $("#botonModificarPlaca").attr("disabled", true);
                        $("#mensajes").html('<div class="alert alert-dismissible alert-danger">La placa <strong>no registra en la base</strong>. Por favor crearla.</div>');
                    } else {
                        $("#listaMarcas").val(obj[0].marca);
                        $("#modeloVehiculo").val(obj[0].modelo);
                        $("#listaTipoCarroceria").val(obj[0].tipocarroceria);
                        $("#capacidadCarga").val(obj[0].capacidadcarga);
                        $("#ancho").val(obj[0].ancho);
                        $("#largo").val(obj[0].largo);
                        $("#alto").val(obj[0].alto);
                        $("#listaTipoVehiculo").val(obj[0].tipovehiculo);
                        $("#reportar_novedad").val(obj[0].reportar_novedad);
                        $("#estadoVehiculo").val(obj[0].estado);
                        calcularVolumenes();
                        $('#botonCrearPlaca').attr("disabled", true);

                        retornarPropietario();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX blur #placaVehiculo");
                }
            });

        } else {
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">La placa es incorrecta</div>');
            $("#placaVehiculo").val('');
        }
    });

    $("#placaVehiculo").focus(function () {
        $("#mensajes").html('<div class="alert alert-dismissible alert-success">Para las <strong>medidas del veh&iacute;culo</strong>; por favor use el punto como separador de decimales</div>');
        limpiarFormulario();
    });

    $("#botonCrearPlaca").click(function () {

        if (validarFormulario() === 10) {
            $.ajax({
                url: "../trafico/vehiculo.php",
                data: {
                    'caso': '2',
                    'placa': $("#placaVehiculo").val(),
                    'marca': $("#listaMarcas").val(),
                    'modelo': $("#modeloVehiculo").val(),
                    'tipocarroceria': $("#listaTipoCarroceria").val(),
                    'capacidadcarga': $("#capacidadCarga").val(),
                    'ancho': $("#ancho").val(),
                    'largo': $("#largo").val(),
                    'alto': $("#alto").val(),
                    'tipovehiculo': $("#listaTipoVehiculo").val(),
                    'estado': $("#estadoVehiculo").val(),
                    'reportar_novedad': $("#reportar_novedad").val()
                },
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj === 1) {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-success"><strong>Correcto!</strong> La placa se ha creado en la base </div>');
                    } else {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> No se ha creado la placa. Error en AJAX Json.parse(data).</div>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX click #botonCrearPlaca");
                }
            });
        }
    });

    $("#listaMarcas").click(function () {
        if ($("#listaMarcas").val() === '-1') {
            $("#divListaMarcas").html('<input class="form-control" id="marcaVehiculo" name="marcaVehiculo" placeholder="Marca del vehículo" type="text" onblur="crearMarca(this)" onkeyup="mayusculas(this)" >');
        } else {

        }
    });

    $("#botonModificarPlaca").click(function () {
        if (validarFormulario() === 10) {
            $.ajax({
                url: "../trafico/vehiculo.php",
                data: {
                    'caso': '3',
                    'placa': $("#placaVehiculo").val(),
                    'marca': $("#listaMarcas").val(),
                    'modelo': $("#modeloVehiculo").val(),
                    'tipocarroceria': $("#listaTipoCarroceria").val(),
                    'capacidadcarga': $("#capacidadCarga").val(),
                    'ancho': $("#ancho").val(),
                    'largo': $("#largo").val(),
                    'alto': $("#alto").val(),
                    'tipovehiculo': $("#listaTipoVehiculo").val(),
                    'estado': $("#estadoVehiculo").val(),
                    'reportar_novedad': $("#reportar_novedad").val()
                },
                type: "POST",
                success: function (data) {
                    //console.log(data);
                    var obj = JSON.parse(data);
                    if (!obj) {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> No se ha modificado la placa. Error en AJAX Json.parse(data).</div>');
                    } else {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-success"><strong>Correcto!</strong> Los datos de la placa se ha modificado en la base </div>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX click #botonModificarPlaca");
                }
            });
        }
    });
});

function crearMarca(valores) {
    var id = valores.id;
    var valor = $("#" + id).val();
    var listaVehiculos = null;
    if (valor.length === 0) {
        $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Por favor digite una <strong>marca de veh&iacute;culo</strong> v&aacute;lida </div>');
        $("#marcaVehiculo").focus();
    } else {
        $.ajax({
            url: "../trafico/Marcasvehiculos.php",
            data: {'caso': '1', 'marca': valor},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.length > 0) {
                    listaVehiculos = "<select class='form-control' id='listaMarcas'><option value='0'>...</option>";
                    $.each(obj, function (key, value) {
                        listaVehiculos += "<option value='" + value.id + "'>" + value.marca + "</option>";
                    });
                    //listaVehiculos += "<option value='-1'>Crear marca</option>";
                    listaVehiculos += "</select>";
                    $("#divListaMarcas").html(listaVehiculos);
                } else {
                    $("#mensajes").html('<div class="alert alert-dismissible alert-dange">Ha fallado la creaci&oacute;n de la marca. Por favor presione CTRL + R e intente nuevamente</div>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX function crearMarca(valores) {...");
            }
        });
    }
}

function validarFormulario() {

    var retorno = 0;

    if ($("#placaVehiculo").val() === '') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique placa</div>");
        $("#placaVehiculo").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#listaMarcas").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique marca</div>");
        $("#listaMarcas").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#modeloVehiculo").val() !== '') {
        retorno += 1;
    } else {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique modelo</div>");
        $("#modeloVehiculo").focus();
        retorno = 0;
    }

    if ($("#listaTipoCarroceria").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique tipo carroceria</div>");
        $("#listaTipoCarroceria").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#capacidadCarga").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique capacidad de carga</div>");
        $("#capacidadCarga").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#ancho").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique el ancho del veh&iacute;culo</div>");
        $("#ancho").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#largo").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique el largo del veh&iacute;culo</div>");
        $("#largo").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#alto").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique el alto del veh&iacute;culo</div>");
        $("#alto").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#listaTipoVehiculo").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique el tipo de veh&iacute;culo</div>");
        $("#listaTipoVehiculo").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#estadoVehiculo").val() === 'ACTIVO' || $("#estadoVehiculo").val() === 'INACTIVO') {
        retorno += 1;
    } else {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique el estado del veh&iacute;culo</div>");
        $("#estadoVehiculo").focus();
        retorno = 0;
    }

    return retorno;
}

function calcularVolumenes() {
    var volVeh = null;
    volVeh = parseFloat($("#alto").val()) * parseFloat($("#ancho").val()) * parseFloat($("#largo").val());
    $("#volVehiculo").val(volVeh.toFixed(2) + ' Mts3');
}

function mayusculas(valor) {
    var val = valor.id;
    $("#" + val).val($("#" + val).val().toUpperCase());
}

function limpiarFormulario() {
    $("#placaVehiculo").val('');
    $("#listaMarcas").val('0');
    $("#modeloVehiculo").val('0');
    $("#listaTipoCarroceria").val('0');
    $("#capacidadCarga").val('0');
    $("#ancho").val('0');
    $("#largo").val('0');
    $("#alto").val('0');
    $("#listaTipoVehiculo").val('0');
    $("#estadoVehiculo").val('0');
    $("#volVehiculo").val('0');
    $("#volCarga").val('0');
    $('#botonCrearPlaca').attr("disabled", false);
    $("#botonModificarPlaca").attr("disabled", false);
    $("#reportar_novedad").val('1');
}

function retornarPropietario() {
    $.ajax({
        url: "../trafico/vehiculo.php",
        data: {
            'caso': '4',
            'placa': $("#placaVehiculo").val()
        },
        type: "POST",
        success: function (data, textStatus, jqXHR) {
            var obj = JSON.parse(data);
            if (obj[0].posicion === '0') {
                $("#mensajes").append('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> La placa no registra propietario, por favor primero crearlo.<input type="hidden" name="noPropietario" id="noPropietario" value="1" /></div>');
            } else {
                $("#mensajes").append('<div class="alert alert-dismissible alert-success">\n\
<strong>Propietario: </strong> ' + obj[0].nombres + ' identificaci&oacute;n: ' + obj[0].cond_identificacion + ' creado el: ' + obj[0].fecha + '</div>');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX listaPlacas.change");
        }
    });
}
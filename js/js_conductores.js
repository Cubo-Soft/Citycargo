var completo = null, arreglo = null, arregloPlacas = null;
$(document).ready(function () {

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $("#botonGuias").click(function () {
        window.location.href = "../modulos/procesosGuias.php";
    });

    $("#botonVehiculos").click(function () {
        $(location).attr('href', '../modulos/vehiculos.php');
    });

    /*
     * 201612071513
     * Agrega la placa a otro propietario
     */
    $("#botonAgregarPlaca").click(function () {

        if (verficarFormulario() === 11) {

            if (confirm('Se va a agregar una placa ')) {
                $.ajax({
                    url: "../trafico/Conductor.php",
                    data: {
                        'caso': '6',
                        'cond_id': $("#idConductor").val(),
                        'cedulaConductor': $("#cedulaConductor").val(),
                        'nombresConductor': $("#nombresConductor").val(),
                        'apellidosConductor': $("#apellidosConductor").val(),
                        'direccionConductor': $("#direccionConductor").val(),
                        'telefonoConductor': $("#telefonoConductor").val(),
                        'email': $("#email").val(),
                        'estadoConductor': $("#estadoConductor").val(),
                        'perfil': $("#perfil").val(),
                        'listaPlacas': $("#listaPlacas").val(),
                        'estadoRelacion': $("#estadoRelacion").val(),
                        'municipio': $("#listaMunicipios").val()
                    },
                    type: "POST",
                    success: function (data) {
                        var obj = JSON.parse(data);
                        if (obj[0].estado === '1') {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> No se puede modificar un propietario que no existe, por favor verifique. </div>');
                        } else if (obj[0].estado === '2') {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-success"><strong>Correcto!</strong> Se ha agregado la placa.</div>');
                        } else if (obj[0].estado === '4') {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong>El propietario no tiene una placa asociada, por favor vincule primero una.</div>');
                        } else if (obj[0].estado === '5') {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong>No se selecciono una placa nueva, por favor cambie la placa.</div>');
                        } else if (obj[0].estado === '6') {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong>Se intento cambiar el propietario por un conductor. No se realizaron los cambios  </div>');
                        } else {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> Ha ocurrido un error al modificar el propietario, por favor informar.</div>');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Ha ocurrido un error en AJAX funcion botonAgregarPlaca");
                    }
                });
            } else {
                $("#mensajes").html('<div class="alert alert-dismissible alert-info"><strong>Muy bien!</strong> Esperar&eacute; </div>');
            }
        }
    });


    /*
     * Modifica los datos del conductor en la base de datos
     */
    $("#botonModificarConductor").click(function () {
        if (verficarFormulario() === 11) {
            if (confirm('Se van a modificar los datos del conductor\n¿Esta seguro?')) {
                $.ajax({
                    url: "../trafico/Conductor.php",
                    data: {
                        'caso': '5',
                        'cond_id': $("#idConductor").val(),
                        'cedulaConductor': $("#cedulaConductor").val(),
                        'nombresConductor': $("#nombresConductor").val(),
                        'apellidosConductor': $("#apellidosConductor").val(),
                        'direccionConductor': $("#direccionConductor").val(),
                        'telefonoConductor': $("#telefonoConductor").val(),
                        'email': $("#email").val(),
                        'estadoConductor': $("#estadoConductor").val(),
                        'perfil': $("#perfil").val(),
                        'listaPlacas': $("#listaPlacas").val(),
                        'estadoRelacion': $("#estadoRelacion").val(),
                        'municipio': $("#listaMunicipios").val(),
                        'reportar_novedad':$("#reportar_novedad").val()
                    },
                    type: "POST",
                    success: function (data) {  
//                        console.log(data);                      
                        var obj = JSON.parse(data);
                        if (obj[0].estado === '1') {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> No se puede modificar un conductor que no existe, por favor verifique. </div>');

                        } else if (obj[0].estado === '2') {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-success"><strong>Correcto!</strong> Se ha modificado el conductor.</div>');
                        } else {
                            $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> Ha ocurrido un error al modificar el conductor, por favor informar.</div>');
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Ha ocurrido un error en AJAX funcion botonModificarConductor");
                    }
                });
            } else {
                $("#mensajes").html('<div class="alert alert-dismissible alert-info"><strong>Muy bien!</strong> Esperar&eacute; </div>');
            }
        }
    });

    /*
     * Trae el propietario del vehículo
     */
    $("#listaPlacas").on('change', function () {

        if ($("#listaPlacas").val().length !== 1) {
            $.ajax({
                url: "../trafico/vehiculo.php",
                data: {
                    'caso': '4',
                    'placa': $("#listaPlacas").val()
                },
                type: "POST",
                success: function (data, textStatus, jqXHR) {
                    console.log(data);
                    var obj = JSON.parse(data);
                    if (obj[0].posicion === '0') {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> La placa no registra propietario, por favor primero crearlo.<input type="hidden" name="noPropietario" id="noPropietario" value="1" /></div>');
                    } else {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-success">\n\
<strong>Propietario: </strong> ' + obj[0].nombres + ' identificaci&oacute;n: ' + obj[0].cond_identificacion + ' creado el: ' + obj[0].fecha + '</div>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX listaPlacas.change");
                }
            });
        } else {
            $("#mensajes").html(' ');
        }
    });

    $("#perfil").on('change', function () {
        $.ajax({
            url: "../trafico/vehiculo.php",
            data: {
                'caso': '4',
                'placa': $("#listaPlacas").val()
            },
            type: "POST",
            success: function (data, textStatus, jqXHR) {
                var obj = JSON.parse(data);
                if (obj[0].posicion !== '0' && $("#perfil").val() === '9') {
                    $("#mensajes").html('<div class="alert alert-dismissible alert-danger">\n\
<strong>Propietario: </strong> ' + obj[0].nombres + ' creado el: ' + obj[0].fecha + '. ¿Desea <strong>CAMBIAR</strong> el propietario?</div>');
                } else {
                    $("#mensajes").html('<div class="alert alert-dismissible alert-success">\n\
<strong>Propietario: </strong> ' + obj[0].nombres + ' creado el: ' + obj[0].fecha + ' . ¿Desea <strong>CREAR</strong> o <strong>MODIFICAR</strong> el conductor? </div>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX perfil.change");
            }
        });
    });

    /*
     * Ingresa el nuevo conductor     
     */
    $("#botonCrearConductor").click(function () {

        if ($("#noPropietario").val() === '1' && $("#perfil").val() === '10') {
            $("#mensajes").html('<div class="alert alert-danger"><strong> Error </strong>Debe primero crear o vincular el propietario del veh&iacute;culo<input type="hidden" name="noPropietario" id="noPropietario" value="1" /></div>');
        } else {
            if (verficarFormulario() === 11) {
                $.ajax({
                    url: "../trafico/Conductor.php",
                    data: {
                        'caso': '2',
                        'cedulaConductor': $("#cedulaConductor").val(),
                        'nombresConductor': $("#nombresConductor").val(),
                        'apellidosConductor': $("#apellidosConductor").val(),
                        'direccionConductor': $("#direccionConductor").val(),
                        'telefonoConductor': $("#telefonoConductor").val(),
                        'email': $("#email").val(),
                        'estadoConductor': $("#estadoConductor").val(),
                        'perfil': $("#perfil").val(),
                        'listaPlacas': $("#listaPlacas").val(),
                        'estadoRelacion': $("#estadoRelacion").val(),
                        'municipio': $("#listaMunicipios").val(),
                        'reportar_novedad':$("#reportar_novedad").val()
                    },
                    type: "POST",
                    success: function (data, textStatus, jqXHR) {
                        console.log(data);
                        var obj = JSON.parse(data);
                        switch (obj[0].estado) {
                            case '0':
                                $("#mensajes").html('<div class="alert alert-dismissible alert-warning"><strong>Error!</strong> EL n&uacute;mero de c&eacute;dula ya se encuentra registrada. ¿Desea modificar el conductor?</div>');
                                break;
                            case '1':
                                $("#mensajes").html('<div class="alert alert-dismissible alert-success"><strong>Correcto!</strong> Se ha creado el conductor.</div>');
                                break;
                            case '2':
                                $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> Ha ocurrido un error al crear el conductor, por favor informar.</div>');
                                break;
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Ha ocurrido un error en AJAX botonCrearConductor.click");
                    }
                });
            }
        }
    });

    /*
     * Evalua si se digita el numero de cedula del conductor para llevarlo 
     * al modulo de anticipos
     */
    $('#botonAnticipos').click(function () {
        var identCon = prompt("Digite el número de cédula del conductor");
        if (!identCon) {
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> Para retornar a los anticipos debe digitar el n&uacute;mero de c&eacute;dula.</div>');
        } else {
            window.location.href = "../modulos/anticipos.php?pj=" + identCon;
        }
    });

    /*
     * Consulta si un conductor existe o no en la base de datos
     */
    $("#cedulaConductor").blur(function () {        
        var datos=[];
        datos.nombreCampo='cedulaConductor';    
        traerDatosConductor(datos,1);
    });

    /*
     * Evalua si se digita el numero de cedula del conductor para llevarlo 
     * al modulo de cuentas de cobro
     */
    $('#botonCuentaCobro').click(function () {
        var identCon = prompt("Digite el número de cédula del conductor");
        if (!identCon) {
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Error!</strong> Para retornar a cuenta de cobro debe digitar el n&uacute;mero de c&eacute;dula.</div>');
        } else {
            window.location.href = "../modulos/cuentaCobro.php?pj=" + identCon;
        }
    });

    /*
     * Limpia el formulario
     */

    $("#botonLimpiar").click(function () {
        limpiar();
    });

});

function limpiar() {
    $("#idConductor").val('');
    $("#nombresConductor").val('');
    $("#apellidosConductor").val('');
    $("#direccionConductor").val('');
    $("#telefonoConductor").val('');
    $("#email").val('');
    $('#listaMunicipios').val('0');
    $("#estadoConductor").val('0');
    $("#perfil").val('0');
    $("#listaPlacas").val('0');
    $("#estadoRelacion").val('0');
    $('#cedulaConductor').prop('disabled', false);
    $('#cedulaConductor').val('');
    $('#cedulaConductor').focus();
    $('#mensajes').html('');
    $('#botonCrearConductor').prop('disabled', false);
}

function cambiaTamanio(valor) {
    var id = null;
    id = "#" + valor.id;
    $(id).val($(id).val().toUpperCase());
}

function verficarFormulario() {

    //expresion regular para validar un correo electronico
    var regex = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

    if ($("#cedulaConductor").val().length <= 6) {
        $("#cedulaConductor").focus();
        $("#mensajes").html('<div class="alert alert-danger">M&iacute;nimo 6 digitos en el número de cédula</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = 1;
    }

    if ($("#nombresConductor").val().length <= 4) {
        $("#nombresConductor").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>NOMBRES</strong> del conductor ó propietario</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#apellidosConductor").val().length <= 4) {
        $("#apellidosConductor").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>APELLIDOS</strong> del conductor ó propietario</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#direccionConductor").val().length <= 4) {
        $("#direccionConductor").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>DIRECCI&Oacute;N</strong> del conductor ó propietario</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#telefonoConductor").val().length <= 6) {
        $("#telefonoConductor").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>TEL&Eacute;FONO</strong> del conductor ó propietario</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }
    
    //if ($("#email").val().length <= 6) {
    if (!regex.test($("#email").val())) {
        $("#email").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>EMAIL</strong> del conductor ó propietario</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#estadoConductor").val() === '0') {
        $("#estadoConductor").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>ESTADO</strong> del conductor ó propietario</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#perfil").val() === '0') {
        $("#perfil").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>PERFIL</strong> del conductor ó propietario</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#listaPlacas").val() === '0') {
        $("#listaPlacas").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>PLACA</strong> actual del veh&iacute;culo</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#estadoRelacion").val() === '0') {
        $("#estadoRelacion").focus();
        $("#mensajes").html('<div class="alert alert-danger">Seleccione la <strong>ESTADO RELACI&Oacute;N VEH&Iacute;CULO</strong></div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#listaMunicipios").val() === '0') {
        $("#listaMunicipios").focus();
        $("#mensajes").html('<div class="alert alert-danger">Seleccione el <strong>MUNICIPIO</strong></div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }
    return completo;
}

function retornarPlacas(objeto) {
    if (objeto.length >= 1) {
        $.each(objeto, function (llave, valor) {
            if (objeto[llave].placa !== undefined) {
                arregloPlacas = arregloPlacas + ' - ' + objeto[llave].placa;
            }
        });

        if (arregloPlacas !== null) {
            arregloPlacas = arregloPlacas.substr(7, arregloPlacas.length);
            return $("#mensajes").html('<div class="alert alert-dismissible alert-info">Se registran las siguientes placas para este conductor: <strong>' + arregloPlacas + '</strong></div>');
        } else {
            return $("#mensajes").html('<div class="alert alert-dismissible alert-info">No se registran placas para este conductor, desea <strong> ¿Modificar conductor? </strong></div>');
        }
    } else {
        return $("#mensajes").html('<div class="alert alert-dismissible alert-info">No se registran placas para este conductor, desea <strong> ¿Modificar conductor? </strong></div>');
    }
}

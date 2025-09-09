var listaGuias = [], datosCalificacion = {}, calificacionGeneral = null, resultado = null;
$(document).ready(function () {

    var datos = {
        'idservicio': $("#idservicio").val(),
        'guia': $("#guia").val()
    };

    $.ajax({
        url: "../trafico/Servicios.php",
        data: { "caso": '19', "datos": datos },
        type: "POST",
        success: function (respuesta) {
            var obj = JSON.parse(respuesta);

            let ruta = window.location.pathname;
            let nombrepagina = ruta.split("/").pop();

            if (nombrepagina !== 'estudioSeguridad.php') {
                $("#ThFechaCreacion").html(obj[0]["fecha"]);
                $("#ThUsuarioCreacion").html(obj[0]["nombreEmpleado"]);
            }
        }
    });

    retornarPruebasEntrega($("#idservicio").val(), 'tdImagenesCumplidos');

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        if ($("#rol_id").val() === '7') {
            window.location.href = "../modulos/consultasAsesores.php";
        } else {
            window.location.href = "../modulos/index.php";
        }
    });

    $("#trMostrar").show();

    $("#subirArchivo").click(function () {
        var datosFormulario = new FormData($("#formSubirImagen1")[0]);
        datosFormulario.append("caso", "2");
        datosFormulario.append("idservicio", $("#idservicio").val());
        $.ajax({
            url: "../trafico/SeguimientoServicio.php",
            data: datosFormulario,
            type: "POST",
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.pdr === 1 && obj.modificacion === 1) {
                    var pdr = "<a href='" + obj.planderuta + "' target='_blank' class='btn btn-link'>Ir al archivo</a>";
                    $("#divPlanderuta").html(pdr);
                    $("#mensajes").html('<div class="alert alert-success">Archivo de plan de ruta actualizado con &eacute;xito</div>');
                } else {
                    $("#mensajes").html('<div class="alert alert-danger">Falló actualizaci&oacute;n de archivo plan de ruta. Asegurese de haber seleccionado un archivo antes de volver a intentarlo</div>');
                }
            }
        });
    });

    $('#close').on('click', function () {
        $('#popup').fadeOut('slow');
        $('.popup-overlay').fadeOut('slow');
        $('#estados').val('0');
        return false;
    });

    $("#grabarCalificacion").click(function () {

        var cedulaConductor = $("#cedulaConductor").val();
        var cedulaPropietario = $("#cedulaPropietario").val();
        var placa = $("#placa").val();
        var calificacionConductor = $("#calificacionConductor").val();
        var calificacionPropietario = $("#calificacionPropietario").val();
        var calificacionPlaca = $("#calificacionPlaca").val();
        var comentarioPropietario = $("#comentarioPropietario").val();
        var comentarioConductor = $("#comentarioConductor").val();
        var comentarioPlaca = $("#comentarioPlaca").val();
        var idservicio = $("#idservicio").val();

        if ($("#mismoPropietario").val() === '1') {
            datosCalificacion = [{
                "cedulaPropietario": cedulaPropietario,
                "placa": placa,
                "calificacionPropietario": calificacionPropietario,
                "calificacionPlaca": calificacionPlaca,
                "comentarioPlaca": comentarioPlaca,
                "comentarioPropietario": comentarioPropietario,
                "idservicio": idservicio
            }];
            calificacionGeneral = null;
            if (crearCalificacion(datosCalificacion, 1) === 2) {
                crearSeguimiento(0, 1);
            }
        } else if ($("#mismoPropietario").val() === '0') {
            datosCalificacion = [{
                "cedulaConductor": cedulaConductor,
                "cedulaPropietario": cedulaPropietario,
                "placa": placa,
                "calificacionConductor": calificacionConductor,
                "calificacionPropietario": calificacionPropietario,
                "calificacionPlaca": calificacionPlaca,
                "comentarioPlaca": comentarioPlaca,
                "comentarioConductor": comentarioConductor,
                "comentarioPropietario": comentarioPropietario,
                "idservicio": idservicio
            }];
            calificacionGeneral = null;
            if (crearCalificacion(datosCalificacion, 0) === 3) {
                crearSeguimiento(0, 1);
            }
        }

    });

    $("#agregarImagenCumplido").click(function () {

        if (validarArchivo(2) === 0) {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>El tipo de archivo a subir debe ser una <strong>imagen con extensi&oacute;n jpg o png</strong></div>");
        } else if ($("#todasLasGuias").val() === '0') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>No ha seleccionado al menos una gu&iacute;a para agregar la prueba de entrega</div>");
        } else {
            var datosFormulario = new FormData($("#formSubirImagen2")[0]);
            datosFormulario.append("caso", "1");
            datosFormulario.append("idservicio", $("#idservicio").val());
            datosFormulario.append("todasLasGuias", $("#todasLasGuias").val());

            $.ajax({
                url: "../trafico/Pruebasentrega.php",
                data: datosFormulario,
                type: "POST",
                cache: false,
                contentType: false,
                processData: false,
                success: function (response) {
                    var obj = JSON.parse(response);
                    if (obj >= 1) {
                        location.reload();
                    } else {
                        $("#mensajes").html('<div class="alert alert-danger">Falló actualizaci&oacute;n de archivo plan de ruta. Por favor presione F5 e intentelo nuevamente, si el problema persiste; por favor informe</div>');
                    }
                }
            });
        }
    });

    $("#agregarSeguimiento").click(function () {
        if ($("#estados").val() === 'Despacho finalizado con novedad') {
            if ($("#todasLasGuias").val() === '0') {
                $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>No ha seleccionado al menos una gu&iacute;a para realizar el seguimiento</div>");
            } else if (verificarCampos() >= 1) {
                $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique los siguientes campos:<br>Ubicaci&oacute;n<br>Observaci&oacute;n<br>No lleva imagen del GPS?<br>Esta dentro del Plan de Ruta?<br>Selecciono un estado del veh&iacute;culo?</div>");
            } else if (validarArchivo(1) === 0) {
                $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>El tipo de archivo a subir debe ser una <strong>imagen con extensi&oacute;n jpg o png</strong>. Por favor realize la toma de la imagen con la <strong>Herramienta de Recortes</strong> de MS-Windows</div>");
                $("#formSubirImagen2")[0].reset();
            } else {
                $("#mensajes").html('');
                $('#popup').fadeIn('slow');
                $('.popup-overlay').fadeIn('slow');
                $('.popup-overlay').height($(window).height());
            }
        } else {
            if ($("#todasLasGuias").val() === '0') {
                $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>No ha seleccionado al menos una gu&iacute;a para realizar el seguimiento</div>");
            } else if (verificarCampos() >= 1) {
                $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique los siguientes campos:<br>Ubicaci&oacute;n<br>Observaci&oacute;n<br>No lleva imagen del GPS?<br>Esta dentro del Plan de Ruta?<br>Selecciono un estado del veh&iacute;culo?</div>");
            } else if (validarArchivo(1) === 0) {
                $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>El tipo de archivo a subir debe ser una <strong>imagen con extensi&oacute;n jpg o png</strong>. Por favor realize la toma de la imagen con la <strong>Herramienta de Recortes</strong> de MS-Windows</div>");
                $("#formSubirImagen2")[0].reset();
            } else if (confirm("Despúes de 'confirmar' se grabara el seguimiento.\nConfirmar?")) {

                var datosFormulario = new FormData($("#formSubirImagen2")[0]);
                datosFormulario.append("caso", "1");
                datosFormulario.append("crearCalificacion", "1");
                datosFormulario.append("idservicio", $("#idservicio").val());
                datosFormulario.append("mismoPropietario", $("#mismoPropietario").val());
                datosFormulario.append("todasLasGuias", $("#todasLasGuias").val());
                datosFormulario.append("cedulaPropietario", $("#cedulaPropietario").val());
                datosFormulario.append("cedulaConductor", $("#cedulaConductor").val());
                datosFormulario.append("placa", $("#placa").val());
                datosFormulario.append("fechaHora", $("#fechaHora").val());
                $.ajax({
                    url: "../trafico/Seguimiento.php",
                    data: datosFormulario,
                    type: "POST",
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (data) {
                        var obj = JSON.parse(data);
                        if (obj >= 1) {
                            location.reload();
                        } else {
                            $("#mensajes").html('<div class="alert alert-danger">Falló actualizaci&oacute;n de archivo plan de ruta. Por favor presione F5 e intentelo nuevamente, si el problema persiste; por favor informe</div>');
                        }
                    }
                });
            } else {
                $("#mensajes").html('');
            }
        }
    });

    $("#todasGuias").change(function () {
        listaGuias = [];
        if ($(this).is(":checked")) {
            $("#datosGuias input[type=checkbox]").prop('checked', true);
            $("#todasLasGuias").val("1");
            $("#mensajes").html('<div class="alert alert-success">Se afectaran todas las gu&iacute;as del servicio</div>');
        } else {
            $("#datosGuias input[type=checkbox]").prop('checked', false);
            $("#todasLasGuias").val("0");
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Por favor seleccione al menos una gu&iacute;a para hacer seguimiento</div>');
        }
        $("#trMostrar").show();
    });

});


function cambiarTelefonoConductor(valor) {
    var nuevoTelefono = valor.value;
    if (nuevoTelefono.length <= 7 || isNaN(nuevoTelefono)) {
        $("#mensajes").html('<div class="alert alert-danger">El n&uacute;mero de tel&eacute;fono debe ser un celular</div>');
        $("#telefonoConductor").focus();
    } else if (confirm("Cambiar el número de teléfono del conductor")) {
        $.ajax({
            url: "../trafico/Conductor.php",
            data: {
                'caso': 8,
                'telefono': nuevoTelefono,
                'cond_id': $("#cond_id").val()
            },
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    $("#mensajes").html('<div class="alert alert-success">Cambio de n&uacute;mero de conductor exitoso</div>');
                } else {
                    $("#mensajes").html('<div class="alert alert-danger">Falló al cambiar el número del condutor. Por favor informe</div>');
                }
            }
        });
    }
}

function cambiarOperador(valor) {
    var operador = valor.value;
    var placa = $("#placa").val();
    if (operador.length <= 2) {
        $("#mensajes").html('<div class="alert alert-danger">Por favor digite un operador v&acute;lido</div>');
        $("#operador").focus();
    } else {
        $.ajax({
            url: "../trafico/UsuariosGps.php",
            data: {
                'caso': 1,
                'placa': placa,
                'operador': operador
            },
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    $("#mensajes").html('<div class="alert alert-success">Cambio o creaci&oacute;n de operador GPS de ' + placa + ' exitoso</div>');
                } else {
                    $("#mensajes").html('<div class="alert alert-danger">Falló al cambiar o crear el operador GPS de condutor. Por favor informe</div>');
                }
            }
        });
    }
}

function cambiarManifiesto(valor) {
    var manifiesto = valor.value;
    var idservicio = $("#idservicio").val();
    if (manifiesto.length <= 2) {
        $("#mensajes").html('<div class="alert alert-danger">Por favor digite un n&uacute;mero de manifiesto v&aacute;lido</div>');
        $("#manifiesto").focus();
    } else {
        $.ajax({
            url: "../trafico/SeguimientoServicio.php",
            data: {
                'caso': 1,
                'manifiesto': manifiesto,
                'idservicio': idservicio
            },
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    $("#mensajes").html('<div class="alert alert-success">Cambio o creaci&oacute;n de manifiesto de servicio ' + idservicio + ' exitoso</div>');
                } else {
                    $("#mensajes").html('<div class="alert alert-danger">Falló al cambiar o crear el manifiesto. Por favor informe</div>');
                }
            }
        });
    }
}

function cambiarUsuario(valor) {
    var usuario = valor.value;
    var placa = $("#placa").val();
    if (usuario.length <= 2) {
        $("#mensajes").html('<div class="alert alert-danger">Por favor digite un usuario v&acute;lido</div>');
        $("#usuario").focus();
    } else {
        $.ajax({
            url: "../trafico/UsuariosGps.php",
            data: {
                'caso': 3,
                'placa': placa,
                'usuario': usuario
            },
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    $("#mensajes").html('<div class="alert alert-success">Cambio o creaci&oacute;n de usuario GPS de ' + placa + ' exitoso</div>');
                } else {
                    $("#mensajes").html('<div class="alert alert-danger">Falló al cambiar o crear el usuario GPS de veh&iacute;culo. Por favor informe</div>');
                }
            }
        });
    }
}

function cambiarClave(valor) {
    var clave = valor.value;
    var placa = $("#placa").val();
    if (clave.length <= 2) {
        $("#mensajes").html('<div class="alert alert-danger">Por favor digite una clave v&aacute;lida</div>');
        $("#clave").focus();
    } else {
        $.ajax({
            url: "../trafico/UsuariosGps.php",
            data: {
                'caso': 4,
                'placa': placa,
                'clave': clave
            },
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    $("#mensajes").html('<div class="alert alert-success">Cambio o creaci&oacute;n de clave GPS de ' + placa + ' exitoso</div>');
                } else {
                    $("#mensajes").html('<div class="alert alert-danger">Falló al cambiar o crear la clave GPS de veh&iacute;culo. Por favor informe</div>');
                }
            }
        });
    }
}

function hacerSeguimientoGuia(valor) {

    var guia = valor.value;
    //vlrActual valor actual
    var vlrActual = $("#todasLasGuias").val();
    var guiasGuiones = '-';

    if ($("#guia-" + guia).is(':checked')) {
        if (jQuery.inArray(guia, listaGuias) === -1) {
            listaGuias.push(guia);
        }
    } else {
        listaGuias.splice($.inArray(guia, listaGuias), 1);
    }

    if (listaGuias.length === 0) {
        $("#mensajes").html("<div class='alert alert-dismissible alert-success'>Por favor seleccione al menos una gu&iacute;a para realizar el seguimiento</div>");
    } else {
        for (var i = 0; i < listaGuias.length; i++) {
            guiasGuiones = guiasGuiones + listaGuias[i] + "-";
        }
    }

    if (guiasGuiones === '-') {
        $("#todasLasGuias").val('0');
    } else {
        guiasGuiones = guiasGuiones.substr(1, guiasGuiones.length);
        guiasGuiones = guiasGuiones.substring(0, guiasGuiones.length - 1);
        $("#todasLasGuias").val(guiasGuiones);
        $("#mensajes").html('<div class="alert alert-success">Se va a operar sobre las siguientes gu&iacute;as: ' + guiasGuiones + '</div>');
    }


}

function verificarCampos() {
    var retorno = 0;
    if ($("#ubicacion").val() === '') {
        retorno = +1;
    }
    if ($("#observacion").val() === '') {
        retorno = +1;
    }
    if ($("#planderuta").val() === '3') {
        retorno = +1;
    }
    if ($("#estados").val() === '0') {
        retorno = +1;
    }
    return retorno;
}

function validarArchivo(opcion) {

    var fileName = null;

    if (opcion === 1) {
        fileName = document.getElementById("imagenGPS").value;

        //idxDot indice del punto
        var idxDot = fileName.lastIndexOf(".") + 1;
        //extFile extención del archivo
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile === 'jpg' || extFile === 'png' || extFile === 'jpeg') {
            return 1;
        } else if (extFile === '') {
            return 1;
        } else {
            return 0;
        }
    }

    if (opcion === 2) {
        fileName = document.getElementById("imagenCumplido").value;
        //idxDot indice del punto
        var idxDot = fileName.lastIndexOf(".") + 1;
        //extFile extención del archivo
        var extFile = fileName.substr(idxDot, fileName.length).toLowerCase();
        if (extFile === 'jpg' || extFile === 'png' || extFile === 'jpeg' || extFile === 'pdf') {
            return 1;
        } else if (extFile === '') {
            return 1;
        } else {
            return 0;
        }
    }
}

function crearCalificacion(arreglo, mismoPropietario) {

    resultado = null;

    if (validarCamposCalificacion(arreglo, mismoPropietario) === 1) {
        $.ajax({
            async: false,
            url: "../trafico/Calificaciones.php",
            data: { 'caso': 1, 'arreglo': arreglo, 'mismoPropietario': mismoPropietario },
            type: "POST",
            success: function (respuesta) {
                var obj = JSON.parse(respuesta);
                if (obj === 2 || obj === 3) {
                    resultado = obj;
                } else {
                    resultado = 0;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error $function crearCalificacion(arreglo, mismoPropietario) {...retorno desde el servidor");
            }
        });
    }
    return resultado;
}

function validarCamposCalificacion(arreglo, mismoPropietario) {

    if (mismoPropietario === 1) {

        if (arreglo[0].comentarioPlaca.length === 0 && arreglo[0].calificacionPlaca === 2) {
            $("#divTextoPlaca").html("<div class='alert alert-dismissible alert-danger'>Por favor digite un 'Detalle' v&aacute;lido para la placa</div>");
            $("#comentarioPlaca").focus();
            return 0;
        } else if (arreglo[0].comentarioPropietario.length === 0 && arreglo[0].calificacionPropietario === 2) {
            $("#divTextoPropietario").html("<div class='alert alert-dismissible alert-danger'>Por favor digite un 'Detalle' v&aacute;lido para el conductor y/o propietario</div>");
            $("#divTextoPropietario").focus();
            return 0;
        } else {
            $("#divTextoPlaca").html("");
            $("#divTextoPropietario").html("");
            return 1;
        }
    } else {

        if (arreglo[0].comentarioPlaca.length === 0 && arreglo[0].calificacionPlaca === '2') {
            $("#divTextoPlaca").html("<div class='alert alert-dismissible alert-danger'>Por favor digite un 'Detalle' v&aacute;lido para la placa</div>");
            $("#comentarioPlaca").focus();
            return 0;
        } else if (arreglo[0].comentarioPropietario.length === 0 && arreglo[0].calificacionPropietario === '2') {
            $("#divTextoPropietario").html("<div class='alert alert-dismissible alert-danger'>Por favor digite un 'Detalle' v&aacute;lido para el conductor y/o propietario</div>");
            $("#divTextoPropietario").focus();
            return 0;
        } else if (arreglo[0].comentarioConductor.length === 0 && arreglo[0].calificacionConductor === '2') {
            $("#divTextoConductor").html("<div class='alert alert-dismissible alert-danger'>Por favor digite un 'Detalle' v&aacute;lido para el conductor y/o propietario</div>");
            $("#divTextoConductor").focus();
            return 0;
        } else {
            $("#divTextoPlaca").html("");
            $("#divTextoPropietario").html("");
            $("#divTextoConductor").html("");
            return 1;
        }
    }
}

function crearSeguimiento(crearCalificacion, caso) {
    var datosFormulario = new FormData($("#formSubirImagen2")[0]);
    datosFormulario.append("caso", caso);
    datosFormulario.append("crearCalificacion", crearCalificacion);
    datosFormulario.append("mismoPropietario", $("#mismoPropietario").val());
    datosFormulario.append("idservicio", $("#idservicio").val());
    datosFormulario.append("todasLasGuias", $("#todasLasGuias").val());
    datosFormulario.append("fechaHora", $("#fechaHora").val());
    $.ajax({
        url: "../trafico/Seguimiento.php",
        data: datosFormulario,
        type: "POST",
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj >= 1) {
                location.reload();
            } else {
                $("#mensajes").html('<div class="alert alert-danger">Falló actualizaci&oacute;n de archivo plan de ruta. Por favor presione F5 e intentelo nuevamente, si el problema persiste; por favor informe</div>');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function crearSeguimiento(crearCalificacion, caso) {...retorno desde el servidor");
        }
    });
}

function cambiarEstadoPruebaEntrega(valor) {
    var vlr = valor.id;
    vlr = vlr.substr(3, vlr.length);

    $.ajax({
        url: "../trafico/Pruebasentrega.php",
        data: {
            'id': vlr,
            'caso': '3'
        },
        type: "POST",
        success: function (respuesta) {
            var obj = JSON.parse(respuesta);
            if (obj) {
                location.reload();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function cambiarEstadoPruebaEntrega(valor) {...retorno desde el servidor");
        }
    });


}

function retornarPruebasEntrega(idservicio, idHtml) {
    $.ajax({
        url: "../trafico/Pruebasentrega.php",
        data: {
            'idservicio': idservicio,
            'caso': '2'
        },
        type: "POST",
        success: function (respuesta) {
            var obj = JSON.parse(respuesta);
            if (obj["todo"].length > 0) {
                pintarTablaPruebaEntregas(obj, idHtml);
            }
        }
    });
}

function desplazarse(elemento) {
    $('a[href*=#]:not([href=#])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html,body').animate({
                    scrollTop: target.offset().top - $('#' + elemento).height()
                }, 10);
                return false;
            }
        }
    });
}
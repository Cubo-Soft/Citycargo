$(document).ready(function () {

    $("#divListaEmpleados").hide();

    $("#listaEmpleados").change(function (e) {
        if ($("#listaEmpleados").is(":checked")) {
            $("#divListaEmpleados").show();
            $("#divFormularioEmpleado").hide();
        } else {
            $("#divFormularioEmpleado").show();
            $("#divListaEmpleados").hide();
        }
    });

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });
    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });
    $("#apellidosEmpleado").blur(function () {
        /*
         * u=usuario
         */
        var u = $("#apellidosEmpleado").val().substr(0, 1);
        u += $("#nombresEmpleado").val().substr(0, 6).split(' ').join('');
        $("#usuarioEmpleado").val(u);
        $("#claveEmpleado").val('*' + $("#cedulaEmpleado").val() + '!');
    });

    $("#botonModificarEmpleado").click(function () {

        if (verficarFormulario() === 11 && confirm("Confirmar modificar empleado")) {
            $.ajax({
                url: "../trafico/Empleados.php",
                data: {
                    'opcion': '3',
                    'nombresEmpleado': $("#nombresEmpleado").val(),
                    'apellidosEmpleado': $("#apellidosEmpleado").val(),
                    'direccionEmpleado': $("#direccionEmpleado").val(),
                    'telefonoEmpleado': $("#telefonoEmpleado").val(),
                    'correoEmpleado': $("#correoEmpleado").val(),
                    'fechaIngreso': $("#fechaIngreso").val(),
                    'usuarioEmpleado': $("#usuarioEmpleado").val(),
                    'claveEmpleado': $("#claveEmpleado").val(),
                    'departamentos': $("#departamentos").val(),
                    'roles': $("#roles").val(),
                    'estadoEmpleado': $("#estadoEmpleado").val(),
                    'cedulaEmpleado': $("#cedulaEmpleado").val(),
                    'emp_id': $("#emp_id").val()
                },
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj) {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-success">Empleado modificado con éxito</div>');
                    } else {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Ha ocurrido un error al modificar al empleado, por favor intentelo nuevamente</div>');
                        limpiar();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX $('#botonCrearEmpleado').click(function () {...");
                }
            });
        } else {
            $("#mensajes").html('<div class="alert alert-dismissible alert-success"><strong>Por favor verifique</strong> el formulario. Hacen falta datos por llenar </div>');
        }
    });

    $("#botonCrearEmpleado").click(function () {
        if (verficarFormulario() === 11 && confirm("Confirmar crear empleado")) {
            $.ajax({
                url: "../trafico/Empleados.php",
                data: {
                    'opcion': '2',
                    'nombresEmpleado': $("#nombresEmpleado").val(),
                    'apellidosEmpleado': $("#apellidosEmpleado").val(),
                    'direccionEmpleado': $("#direccionEmpleado").val(),
                    'telefonoEmpleado': $("#telefonoEmpleado").val(),
                    'correoEmpleado': $("#correoEmpleado").val(),
                    'fechaIngreso': $("#fechaIngreso").val(),
                    'usuarioEmpleado': $("#usuarioEmpleado").val(),
                    'claveEmpleado': $("#claveEmpleado").val(),
                    'departamentos': $("#departamentos").val(),
                    'roles': $("#roles").val(),
                    'estadoEmpleado': $("#estadoEmpleado").val(),
                    'cedulaEmpleado': $("#cedulaEmpleado").val()
                },
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj) {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-success">Empleado creado con éxito</div>');
                    } else {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Ha ocurrido un error al crear al empleado, por favor intentelo nuevamente</div>');
                        limpiar();
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX $('#botonCrearEmpleado').click(function () {...");
                }
            });
        } else {
            $("#mensajes").html('<div class="alert alert-dismissible alert-success"><strong>Muy bien!</strong> Esperar&eacute; </div>');
        }
    });
    $("#botonLimpiar").click(function () {
        limpiar();
    });
    $("#cedulaEmpleado").blur(function () {
        if ($("#cedulaEmpleado").val() === '' || $("#cedulaEmpleado").val() === '0') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Para realizar una consulta por favor digite un <strong>N&uacute;mero de c&eacute;dula</strong> correcto</div>");
            $("#cedulaEmpleado").focus();
        } else {
            $.ajax({
                url: "../trafico/Empleados.php",
                data: {
                    'opcion': '1',
                    'cedula': $("#cedulaEmpleado").val()
                },
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj.length !== 0) {
                        $("#emp_id").val(obj[0].emp_id);
                        $("#nombresEmpleado").val(obj[0].emp_nombres);
                        $("#apellidosEmpleado").val(obj[0].emp_apellidos);
                        $("#direccionEmpleado").val(obj[0].emp_direccion);
                        $("#telefonoEmpleado").val(obj[0].emp_telefono);
                        $("#correoEmpleado").val(obj[0].emp_correo);
                        $("#fechaIngreso").val(obj[0].emp_fechaIngreso);
                        $("#usuarioEmpleado").val(obj[0].emp_usuario);
                        $("#claveEmpleado").val(obj[0].emp_clave);
                        $("#departamentos").val(obj[0].departamento_dep_id);
                        $("#roles").val(obj[0].roles_rol_id);
                        $("#estadoEmpleado").val(obj[0].estado);
                        $("#cedulaEmpleado").prop('disabled', true);
                        $("#mensajes").html('');
                        $("#botonCrearEmpleado").prop('disabled', true);
                    } else {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-success">No se ubican datos con la <strong>cédula: ' + $("#cedulaEmpleado").val() + ' </strong></div>');
                        if (!confirm("¿Desea crear un nuevo empleado?")) {
                            $("#cedulaEmpleado").val("");
                            $("#cedulaEmpleado").focus();
                        } else {
                            $("#nombresEmpleado").focus();
                            $("#mensajes").html('<div class="alert alert-dismissible alert-success">Pendiente...</div>');
                            $("#estadoEmpleado").val('A');
                        }

                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX $('#cedulaEmpleado').blur(function () {...");
                }
            });
        }
    });
});
function limpiar() {
    $("#emp_id").val('');
    $("#nombresEmpleado").val('');
    $("#apellidosEmpleado").val('');
    $("#direccionEmpleado").val('');
    $("#telefonoEmpleado").val('');
    $("#correoEmpleado").val('');
    $("#fechaIngreso").val('');
    $("#usuarioEmpleado").val('');
    $("#claveEmpleado").val('');
    $("#departamentos").val('');
    $("#roles").val('');
    $("#estadoEmpleado").val('0');
    $("#cedulaEmpleado").prop('disabled', false);
    $("#cedulaEmpleado").val('');
    $("#cedulaEmpleado").focus();
    $("#mensajes").html('');
    $("#botonCrearEmpleado").prop('disabled', false);
}

function verficarFormulario() {
    if ($("#cedulaEmpleado").val().length <= 6) {
        $("#cedulaEmpleado").focus();
        $("#mensajes").html('<div class="alert alert-danger">M&iacute;nimo 6 digitos en el número de cédula</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = 1;
    }

    if ($("#nombresEmpleado").val().length <= 4) {
        $("#nombresEmpleado").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>NOMBRES</strong> del empleado</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#apellidosEmpleado").val().length <= 4) {
        $("#apellidosEmpleado").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>APELLIDOS</strong> del empleado</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#direccionEmpleado").val().length <= 4) {
        $("#direccionEmpleado").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>DIRECCI&Oacute;N</strong> del empleado</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#telefonoEmpleado").val().length <= 6) {
        $("#telefonoEmpleado").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>TEL&Eacute;FONO</strong> del empleado</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#correoEmpleado").val().length <= 6) {
        $("#correoEmpleado").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>CORREO</strong> del empleado</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#fechaIngreso").val().length <= 6) {
        $("#fechaIngreso").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>FECHA</strong> de ingreso del empleado</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#usuarioEmpleado").val().length <= 4) {
        $("#usuarioEmpleado").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>USUARIO</strong> del empleado</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#claveEmpleado").val().length <= 4) {
        $("#claveEmpleado").focus();
        $("#mensajes").html('<div class="alert alert-danger"><strong>CLAVE</strong> del empleado</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#departamentos").val() === '0') {
        $("#departamentos").focus();
        $("#mensajes").html('<div class="alert alert-danger">Seleccione el <strong>DEPARTAMENTO</strong> al cual pertenece le empleado</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    if ($("#roles").val() === '0') {
        $("#roles").focus();
        $("#mensajes").html('<div class="alert alert-danger">Seleccione el <strong>ROL</strong> que tiene el empleado</div>');
        return false;
    } else {
        $("#mensajes").html('');
        completo = completo + 1;
    }

    return completo;
}

function cambiaTamanio(valor) {
    var id = null;
    id = "#" + valor.id;
    $(id).val($(id).val().toUpperCase());
}

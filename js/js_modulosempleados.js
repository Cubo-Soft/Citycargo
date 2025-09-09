var mostrarRoles = null;
$(document).ready(function () {
    
    
    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });
    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#listaEmpleados").change(function () {

        var cedula = $("#listaEmpleados").val();

        if ($("#listaEmpleados").val() !== '0') {
            $.ajax({
                url: "../trafico/Modulosempleados.php",
                data: {'caso': 2, 'cedula': cedula},
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj[0].length === 0) {
                        $("#botones input[type=checkbox]").prop('checked', false);
                        $("#mensajes").html("<div class='alert alert-dismissible alert-success' >No se encuentran m&oacute;dulos asignados a &eacute;sta persona</div>");
                    } else {
                        $("#mensajes").html("");
                        $("#botones input[type=checkbox]").prop('checked', false);
                        $.each(obj[0], function (llave, valor) {
                            $('#' + valor.id_boton).prop('checked', true);
                        });
                    }
                    
                    if (obj[1].length === 0) {
                        $("#chkModificarFlete").prop('checked', false);
                    } else {
                        if (obj[1][0]["estado"] === '0') {
                            $("#chkModificarFlete").prop('checked', false);
                        }
                        if (obj[1][0]["estado"] === '1') {
                            $('#chkModificarFlete').prop('checked', true);
                        }
                    }
                    if (obj[2].length === 0) {
                        $("#chkServiciosPorRevisar").prop('checked', false);
                    } else {
                        if (obj[2][0]["estado"] === '0') {
                            $("#chkServiciosPorRevisar").prop('checked', false);
                        }
                        if (obj[2][0]["estado"] === '1') {
                            $('#chkServiciosPorRevisar').prop('checked', true);
                        }
                    }
                    if (obj[3].length === 0) {
                        $("#chkServiciosPorFacturar").prop('checked', false);
                    } else {
                        if (obj[3][0]["estado"] === '0') {
                            $("#chkServiciosPorFacturar").prop('checked', false);
                        }
                        if (obj[3][0]["estado"] === '1') {
                            $('#chkServiciosPorFacturar').prop('checked', true);
                        }
                    }

                    if (obj[4].length === 0) {
                        $("#chkServiciosPorPagar").prop('checked', false);
                    } else {
                        if (obj[4][0]["estado"] === '0') {
                            $("#chkServiciosPorPagar").prop('checked', false);
                        }
                        if (obj[4][0]["estado"] === '1') {
                            $('#chkServiciosPorPagar').prop('checked', true);
                        }
                    }
                    if (obj[5].length === 0) {
                        $("#chkSeguimiento").prop('checked', false);
                    } else {
                        if (obj[5][0]["estado"] === '0') {
                            $("#chkSeguimiento").prop('checked', false);
                        }
                        if (obj[5][0]["estado"] === '1') {
                            $('#chkSeguimiento').prop('checked', true);
                        }
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error function asignarBoton(valor,option) {...retorno desde el servidor");
                }
            });
        } else {
            botonesSinChequear();
            $("#mensajes").html("");
        }
    });

});

function asignarBoton(valor, option) {
    var id = valor.id;
    var cedula = $("#listaEmpleados").val();
    var estado = $("#" + id).prop('checked');

    if (cedula === '0') {
        botonesSinChequear();
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger' >Por favor seleccione un empleado de la lista</div>");
        $("#listaEmpleados").focus();
    } else {
        $("#mensajes").html("");
        if (option === 0) {
            $.ajax({
                url: "../trafico/Modulosempleados.php",
                data: {'caso': 1, 'idboton': id, 'cedula': cedula, 'estado': estado},
                type: "POST",
                success: function (data) {
                    console.log(data);
                    var obj = JSON.parse(data);
                    if (obj === 0) {
                        $("#mensajes").html("<div class='alert alert-dismissible alert-danger' >Ha fallado la asignaci&oacute;n del m&oacute;dulo. Si la falla persiste, por favor informe</div>");
                    } else {
                        $.each(obj, function (llave, valor) {
                            $('#' + valor.id_boton).prop('checked', true);
                        });
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error function asignarBoton(valor,opcion) {...retorno desde el servidor");
                }
            });
        }

        if (option === 1) {
            $.ajax({
                url: "../trafico/Modulosempleados.php",
                data: {'caso': 3, 'cedula': cedula, 'estado': estado},
                type: "POST",
                success: function (data) {
                    console.log(data);
                    var obj = JSON.parse(data);
                    if (!obj) {
                        $("#mensajes").html("<div class='alert alert-dismissible alert-warning' >Cambio realizado con &eacute;xito</div>");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error function asignarBoton(valor,opcion=1) {...retorno desde el servidor");
                }
            });
        }

        if (option === 2) {
            $.ajax({
                url: "../trafico/Modulosempleados.php",
                data: {'caso': 4, 'cedula': cedula, 'estado': estado},
                type: "POST",
                success: function (data) {
                    console.log(data);
                    var obj = JSON.parse(data);
                    if (!obj) {
                        $("#mensajes").html("<div class='alert alert-dismissible alert-warning' >Cambio realizado con &eacute;xito</div>");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error function asignarBoton(valor,opcion=2) {...retorno desde el servidor");
                }
            });
        }

        if (option === 3) {
            $.ajax({
                url: "../trafico/Modulosempleados.php",
                data: {'caso': 5, 'cedula': cedula, 'estado': estado},
                type: "POST",
                success: function (data) {
                    console.log(data);
                    var obj = JSON.parse(data);
                    if (!obj) {
                        $("#mensajes").html("<div class='alert alert-dismissible alert-warning' >Cambio realizado con &eacute;xito</div>");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error function asignarBoton(valor,opcion=2) {...retorno desde el servidor");
                }
            });
        }

        if (option === 4) {
            $.ajax({
                url: "../trafico/Modulosempleados.php",
                data: {'caso': 6, 'cedula': cedula, 'estado': estado},
                type: "POST",
                success: function (data) {
                    console.log(data);
                    var obj = JSON.parse(data);
                    if (!obj) {
                        $("#mensajes").html("<div class='alert alert-dismissible alert-warning' >Cambio realizado con &eacute;xito</div>");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error function asignarBoton(valor,opcion=2) {...retorno desde el servidor");
                }
            });
        }

        if (option === 5) {
            $.ajax({
                url: "../trafico/Modulosempleados.php",
                data: {'caso': 7, 'cedula': cedula, 'estado': estado},
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (!obj) {
                        $("#mensajes").html("<div class='alert alert-dismissible alert-warning' >Cambio realizado con &eacute;xito</div>");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error function asignarBoton(valor,opcion=2) {...retorno desde el servidor");
                }
            });
        }
    }
}

function botonesSinChequear() {
    $("input.groupchk").prop('checked', false);
    $("input.groupchk1").prop('checked', false);
}
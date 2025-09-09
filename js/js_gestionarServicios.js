var boton_ = null, mostrar, nitEmpresa = 0, guia = null, idservicio = 0;
$(document).ready(function () {

    $("#contenedor-index3").hide();
    $("#contenedor-index2").hide();
    $("#numeroCuentaCobro").focusin(function () {
        $("#numeroCuentaCobro").val('');
    });

    $("#botonCrearServicioVarios").click(function () {
        window.location.href = "../modulos/serviciosVariosTres.php";
    });

    $("#botonCrearServicio").click(function () {
        window.location.href = "../modulos/servicios.php";
    });

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#selectOpcion").change(function () {
        if ($("#selectOpcion").val() === '0') {
            $("#contenedor-index3").hide();
            $("#contenedor-index2").hide();
        } else if ($("#selectOpcion").val() === '1') {
            $("#contenedor-index3").hide();
            $("#contenedor-index2").show();
        } else {
            $("#contenedor-index3").show();
            $("#contenedor-index2").hide();
        }
    });

    $("#numeroCuentaCobro").focusout(function () {
        $.ajax({
            url: "../trafico/retornarServicios.php",
            data: {'numeroCuentaCobro': parseInt($("#numeroCuentaCobro").val()),
                'condicion': '3'},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                mostrarTabla(obj, 2);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en el evento $('#numeroCuentaCobro').focusout(function () {");
            }
        });
    });

    $("#listaClientes").find("option[value='1']").remove();

    $("#listaClientes").change(function () {
        var fecha = null, guia = null;
        if ($("#fechaInicial").val() === '' || $("#fechaFinal").val() === '' || $("#listaClientes").val() === '0') {
            $("#mensajesGestionarServicios").html("<div class='alert alert-danger'>Por favor verifique opciones v&aacute;lidas para: </br>- Fecha de inicial </br>- Cliente </br>☺</div>");
        } else {
            $("#mensajesGestionarServicios").html("");
            $.ajax({
                url: "../trafico/retornarServicios.php",
                data: {'fechaInicial': $("#fechaInicial").val(),
                    'fechaFinal': $("#fechaFinal").val(),
                    'nitEmpresa': $("#listaClientes").val(),
                    'condicion': '1'},
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj.servicios.length > 0) {
                        var cadena = "<table class='table table-hover'><tr><th>Servicio</th><th>Fecha</th><th>Gu&iacute;a</th></tr>";
                        for (var i = 0, max = obj.servicios.length; i < max; i++) {
                            fecha = obj.servicios[i].fechaServicio;
                            fecha = fecha.slice(0, 10);
                            cadena += "<tr><td><a href='../modulos/mostrarServicio.php?idservicio=" + obj.servicios[i].idservicio + "'>" + obj.servicios[i].idservicio + "</a></td><td>" + fecha + "</td><td>" + obj.servicios[i].numeroGuia;
                            +"</td></tr>";
                        }
                        cadena += "</table>";
                        $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-success altura2'>" + cadena + "</div>");
                    } else {
                        $("#mensajesGestionarServicios").html("<div class='alert alert-danger'>No se registran servicios con los criterios seleccionados</div>");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX en el evento $('#listaClientes').change");
                }
            });
        }
    });

    $("#guia").focusin(function () {

        if ($("#guia").val() === '0') {
            $("#guia").val('');
        }
        $("#mensajesGestionarServicios").html("");
    });

    $("#guia").focusout(function () {
        var bandera = 0, fecha = null;
        $("#mensajesGestionarServicios").html('');
        guia = $("#guia").val();
        if ($("#guia").val() === '0' || $("#guia").val() === '') {
            $("#guia").val('0');
            $("#guia").focus();
            $("#mensajesGestionarServicios").html("<div class='alert alert-danger'>Para realizar la consulta por n&uacute;mero de gu&iacute;a, debe ser mayor a cero (0) </div>");
        } else {
            $("#mensajesGestionarServicios").html("");
            $.ajax({
                url: "../trafico/retornarServicios.php",
                data: {'guia': $("#guia").val(),
                    'condicion': '2'},
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj !== false) {
                        mostrarTabla(obj, 1);
                    } else {
                        alert("Ha ocurrido un error en AJAX en el evento $('#guia').focusout(function ()... return from server :( ");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX en el evento $('#guia').focusout(function ()...");
                }
            });
        }
    });

});

function mostrarTabla(obj, condicion) {

    if (condicion === 1) {
        if (obj === 0) {
            $("#mensajesGestionarServicios").html("<div class='alert alert-warning'>No se encuentra servicio con el n&uacute;muero de gu&iacute;a: " + $("#guia").val() + "</div>");
        } else if (obj[0].idservicio > 0) {
            var fecha = obj[0].fecha;
            fecha = fecha.slice(0, 10);
            var cadena = "<table class='table table-hover'>\n\
<tr><th>Servicio</th><th>Fecha</th><th>Placa</th><th>Gu&iacute;a</th></tr>";
            for (var i = 0, max = obj.length; i < max; i++) {
                cadena += "<tr><td><a class='btn btn-success btn-xs' href='../modulos/mostrarServicio.php?idservicio=" + obj[i].idservicio + "' >" + obj[i].idservicio + "</a></td><td>" + fecha + "</td><td>" + obj[i].placa + "</td><td>" + obj[i].guia + "</td></tr>";
            }
            cadena += "</table>";
            $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-success altura2'>" + cadena + "</div>");
        } else {
            $("#mensajesGestionarServicios").html("<div class='alert alert-danger'>No se encuentra servicio con el n&uacute;muero de gu&iacute;a: " + $("#guia").val() + "</div>");
        }
    }

    if (condicion === 2) {
        var fecha = new Date().toJSON().substr(0, 10);

        if (obj.length === 0) {
            $("#mensajesGestionarServiciosDos").html("<div class='alert alert-danger'>No se encuentra servicio con la cuenta de cobro: " + $("#numeroCuentaCobro").val() + "</div>");
            $("#divTextoFechaTransferencia").html("");
            $("#divBotonCrearFechaTransferencia").html("");
        } else if (obj[0].idservicio > 0) {
            
            console.log(obj[0]["fechaTransferencia"]);

            if (obj[0]["fechaTransferencia"] === null) {
                
                if($("#departamento").val()==='GERENCIA'){
                    $("#divTextoFechaTransferencia").html("<input type='date' value='" + fecha + "' class='form form-control' id='fechaTransferencia' name='fechaTransferencia' />");
                    $("#divBotonCrearFechaTransferencia").html("<input type='button' class='btn btn-success' value='Crear fecha transferencia' onclick='ingresarFechaTransferencia()' />");
                }else{
                    $("#divTextoFechaTransferencia").html("");
                    $("#divBotonCrearFechaTransferencia").html("");
                }
            } else {
                $("#divTextoFechaTransferencia").html("<input type='date' value='" + obj[0]["fechaTransferencia"] + "' class='form form-control' id='fechaTransferencia' name='fechaTransferencia' disabled='disabled' />");
                $("#divBotonCrearFechaTransferencia").html("");
            }
            var cadena = "<table class='table table-hover'>\n\
            <tr><th colspan='4'>Relación de servicios</th></tr>\n\
<tr><th>Servicio</th><th>Fecha</th><th>Placa</th><th>Gu&iacute;a</th></tr>";
            for (var i = 0, max = obj.length; i < max; i++) {
                cadena += "<tr><td><a class='btn btn-success btn-xs' href='../modulos/mostrarServicio.php?idservicio=" + obj[i].idservicio + "' >" + obj[i].idservicio + "</a></td><td>" + obj[i].fecha + "</td><td>" + obj[i].placa + "</td><td>" + obj[i].guia + "</td><td></td><td></td></tr>";
            }
            cadena += "</table>";
            $("#mensajesGestionarServiciosDos").html("<div class='alert alert-dismissible alert-success altura2'>" + cadena + "</div>");
        } else {
            $("#mensajesGestionarServiciosDos").html("<div class='alert alert-danger'>No se encuentra servicio con la cuenta de cobro: " + $("#numeroCuentaCobro").val() + "</div>");
            $("#divTextoFechaTransferencia").html("");
            $("#divBotonCrearFechaTransferencia").html("");
        }
    }
}

function mostrarNit(valor) {
    nitEmpresa = valor.value;
}

function redireccionar(valor) {
    switch (valor) {
        case 1:
            window.location.href = "../modulos/modificarServicio.php?idservicio=" + idservicio;
            break;
    }
}

function ingresarFechaTransferencia() {
    $.ajax({
        url: "../trafico/retornarServicios.php",
        data: {'numeroCuentaCobro': $("#numeroCuentaCobro").val(),
            'fechaTransferencia': $("#fechaTransferencia").val(),
            'condicion': '4'},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj) {
                $("#mensajesGestionarServiciosDos").html("<div class='alert alert-success'>La fecha de la transferencia ha sido guardada de manera éxitosa<br>Para ingresar otra cuenta de cobro, por favor digitela en el campo correspondiente</div>");
                $("#numeroCuentaCobro").val('');
                $("#numeroCuentaCobro").focus('');
            } else {
                alert("Ha ocurrido un error en AJAX function ingresarFechaTransferencia(){... return from server :( ");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en la function ingresarFechaTransferencia() {...");
        }
    });

}
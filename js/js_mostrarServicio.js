var boton_ = null, mostrar, nitEmpresa = 0, guia = null, idservicio = 0, saldoAnticipos = 0, sobreanticipo = 0, saldoSobreAnticipo = 0;
$(document).ready(function () {

    $("#valorSobreAnticipo").number(true, 0);
    $("#saldoAnticipos").number(true, 0);
    $("#saldoSobreAnticipo").number(true, 0);

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#botonConsultarGuia").click(function () {
        window.location.href = "../modulos/mostrarServicio.php";
    });

    $("#sobreAnticipo1").hide();
    $("#sobreAnticipo2").hide();
    $("#sobreAnticipo3").hide();

    $("#mostrar").click(function () {

        $("#mensajesGestionarServicios").html("");

        if ($("#sobreAnticipo1").is(":visible") && $("#sobreAnticipo2").is(":visible") && $("#sobreAnticipo3").is(":visible")) {
            $("#sobreAnticipo1").hide();
            $("#sobreAnticipo2").hide();
            $("#sobreAnticipo3").hide();
        } else {
            $("#sobreAnticipo1").show();
            $("#sobreAnticipo2").show();
            $("#sobreAnticipo3").show();
        }
    });

    $("#guia").focusin(function () {
        if ($("#guia").val() === '0') {
            $("#guia").val('');
        }
    });

    $("#idservicio").click(function () {
        var razones = prompt("Indique las razones para cancelar el servicio: " + $("#idservicio").val(), "Digite motivos de cancelación");
        if (razones.length > 0) {
            $.ajax({
                url: "../trafico/ServiciosPorCancelar.php",
                data: {'caso': '2',
                    'idservicio': $("#idservicio").val(),
                    'idempleado': $("#idempleado").val(),
                    'motivo': razones},
                type: "POST",
                success: function (data) {                    
                    var obj = JSON.parse(data);                    
                    if (obj === true) {
                        $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-success'>El servicio " + $("#idservicio").val() + " ha sido agregado a la lista de servicios por cancelar</div>");
                    } else {
                        $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-danger'>Oops! Ha ocurrido un error al agregar el servicio: " + $("#idservicio").val() + " a la lista de servicios para eliminar. Por favor informe de este evento y tenga presente el n&uacute;mero de servicio </div>");
                    }
                }
            });
        }
    });

    $("#valorSobreAnticipo").blur(function () {

        if ($("#valorSobreAnticipo").val() !== '0') {
            saldoAnticipos = $("#saldoAnticipos").val();
            saldoAnticipos = parseInt(saldoAnticipos.replace(",", ""));
            sobreanticipo = $("#valorSobreAnticipo").val();
            sobreanticipo = parseInt(sobreanticipo.replace(",", ""));            
            saldoSobreAnticipo = saldoAnticipos - sobreanticipo;
            if (saldoAnticipos === sobreanticipo) {
                $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-danger'>El valor del sobre anticipo implica el pago total del servicio</div>");
                $("#saldoSobreAnticipo").val(saldoSobreAnticipo);
                $("#crearSobreAnticipo").hide();
            } else {
                $("#saldoSobreAnticipo").val(saldoSobreAnticipo);
                $("#mensajesGestionarServicios").html("");
                $("#crearSobreAnticipo").show();
            }
        }
    });

    $("#crearSobreAnticipo").click(function () {
        var guia = $("#guia").val();
        var valorSobreAnticipo = $("#valorSobreAnticipo").val();
        var idservicio = $("#idservicio").val();
        var placa = $("#placa").val();
        valorSobreAnticipo = parseInt(valorSobreAnticipo.replace(",", ""));
        if ($("#guia").val() === '0' || $("#guia").val() === '' || $("#guia").val() === null) {
            $("#guia").val('0');
            $("#guia").focus();
            $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-danger'>Cargar el sobre anticipo a la gu&iacute;a...<br>Por favor verifique el n&uacute;mero de gu&iacute;a</div>");
        } else {
            $.ajax({
                url: "../trafico/ValoresAnticipos.php",
                data: {'caso': '1',
                    'guia': guia,
                    'valorSobreAnticipo': valorSobreAnticipo,
                    'placa': placa},
                type: "POST",
                success: function (respuesta) {                    
                   var obj = JSON.parse(respuesta);                   
                    if (obj["valoresAnticipos"] === 1) {                        
                        crearSobreAnticipo(guia, idservicio,obj);
                    } else {
                        alert("Ha fallado la creación del sobreanticipo...\nPor favor informe.\nGracias!");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("$('#crearSobreAnticipo').click(function () {...");
                }
            });
        }
    });
});

/*
 * Esta función también esta
 * en js_administrarServiciosDos.js
 * tiene cambios
 */
function cambiarNotasGuia(elemento) {
    var id = "#" + elemento.id;
    var valor = $(id).val();
    var guia = id.substring(3, id.length);
    var idservicio = $("#idservicio").val();
    //console.log(idservicio);
    $.ajax({
        url: "../trafico/Servicios.php",
        data: {'caso': '13',
            guia: guia,
            valor: valor,
            idservicio: idservicio},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj === 2) {
                location.reload();
            } else {
                alert("Ha fallado el cambio de las notas\n function cambiarNotasGuia(elemento) {...respuesta desde servidor...}else{...\nPor favor informe");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function cambiarNotasGuia(elemento){...");
        }
    });
}

function crearSobreAnticipo(guia, idservicio,obj) {    
    window.open('../trafico/generarSobreanticipo.php?guia=' + guia + '&idservicio=' + idservicio+'&numeroAnticipo='+obj["numeroAnticipo"],'_blank');
    setTimeout("document.location=document.location", 2000);
}


function cambiarPlanilla(valor) {
    var vlr = valor.id;
    var guia = vlr.substr(2, vlr.length);
    var planilla = $("#" + vlr).val();
    $("#mensajesGestionarServicios").html("");
    $.ajax({
        url: "../trafico/ServicioGuias.php",
        data: {'caso': '2',
            'planilla': planilla,
            'guia': guia},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj !== 1) {
                $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-danger'>Ha fallado el cambio de planilla. Por favor presione F5 e intentelo nuevamente\nSi la falla persiste por favor informe</div>");
            } else {
                $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-success'>Planilla cambiada con &eacute;xito</div>");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function cambiarPlanilla(valor) {...");
        }
    });
}

function cambiarRemision(valor) {
    var vlr = valor.id;
    var guia = vlr.substr(2, vlr.length);
    var remision = $("#" + vlr).val();
    $("#mensajesGestionarServicios").html("");
    $.ajax({
        url: "../trafico/ServicioGuias.php",
        data: {'caso': '3',
            'remision': remision,
            'guia': guia},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj !== 1) {
                $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-danger'>Ha fallado el cambio de remisi&oacute;. Por favor presione F5 e intentelo nuevamente\nSi la falla persiste por favor informe</div>");
            } else {
                $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-success'>Remisi&oacute;n cambiada con &eacute;xito</div>");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function cambiarRemision(valor) {...");
        }
    });
}

function cambiarFactura(valor) {
    var vlr = valor.id;
    var guia = vlr.substr(2, vlr.length);
    var factura = $("#" + vlr).val();
    $("#mensajesGestionarServicios").html("");
    $.ajax({
        url: "../trafico/ServicioGuias.php",
        data: {'caso': '4',
            'factura': factura,
            'guia': guia},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj !== 1) {
                $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-danger'>Ha fallado el cambio de factura de entrega del cliente;. Por favor presione F5 e intentelo nuevamente\nSi la falla persiste por favor informe</div>");
            } else {
                $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-success'>Factura de entrega del cliente cambiada con &eacute;xito</div>");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function cambiarFactura(valor) {...");
        }
    });
}

function cambiarOrdenCompra(valor) {

    var vlr = valor.id;
    var guia = vlr.substr(2, vlr.length);
    var ordenCompra = $("#" + vlr).val();
    $("#mensajesGestionarServicios").html("");
    $.ajax({
        url: "../trafico/ServicioGuias.php",
        data: {'caso': '5',
            'ordenCompra': ordenCompra,
            'guia': guia},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj !== 1) {
                $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-danger'>Ha fallado el cambio de orden de compra. Por favor presione F5 e intentelo nuevamente\nSi la falla persiste por favor informe</div>");
            } else {
                $("#mensajesGestionarServicios").html("<div class='alert alert-dismissible alert-success'>Orden de compra cambiada con &eacute;xito</div>");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function cambiarFactura(valor) {...");
        }
    });

}
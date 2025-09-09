$(document).ready(function () {

    $('#div-formaPago').hide();
    $('#textformaPago').text('');

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $('#formaPago').click(function () {
        if ($('#formaPago').val() === 'OTRO') {
            $('#div-formaPago').show();
        } else {
            $('#div-formaPago').hide();
        }
        ;
    });

    $('#botonGenerarPdf').click(function () {

        var accion;

        var dec = confirm('Los datos seran modificados y se generara la COTIZACIÓN en formato PDF  \n ¿Esta seguro de realizar esta acción?');
        if (dec === false) {
            return false;
            accion = 0;
        } else {

            if (/\s/.test($('#nit_cliente').val())) {
                alert('El NIT digitado tiene espacios en blanco, por favor borrelos');
                return false;
            }

            if ($('#contacto_cliente').val().length < 1) {
                $("#contacto_cliente").css("background-color", "#D9FDFF");
                $("#contacto_cliente").val("¡Contacto del cliente!");
                return false;
            }

            if ($('#nombre_cliente').val().length < 1) {
                $("#nombre_cliente").css("background-color", "#D9FDFF");
                $("#nombre_cliente").val("¡Razón social o nombre del cliente!");
                return false;
            }

            if ($('#telefono_cliente').val().length < 1) {
                $("#telefono_cliente").css("background-color", "#D9FDFF");
                $("#telefono_cliente").val("¡Teléfono del cliente!");
                return false;
            }

            if ($('#direccion_cliente').val().length < 1) {
                $("#direccion_cliente").css("background-color", "#D9FDFF");
                $("#direccion_cliente").val("¡Dirección del cliente!");
                return false;
            }

            if ($('#fax_cliente').val().length < 1) {
                $("#fax_cliente").css("background-color", "#D9FDFF");
                $("#fax_cliente").val("¡Fax del cliente!");
                return false;
            }

            if ($('#correoElectronico_cliente').val().length < 1) {
                $("#correoElectronico_cliente").css("background-color", "#D9FDFF");
                $("#correoElectronico_cliente").val("¡Correo electronico del cliente!");
                return false;
            }

            if ($('#detalle_servicio').val().length < 1) {
                $("#detalle_servicio").css("background-color", "#D9FDFF");
                $("#detalle_servicio").val("¡Por favor llenar el detalle del servicio!");
                return false;
            }

            if ($('#tipoServicio').val() === '...') {
                $('#cabezoteDescripcionServicio').css("background-color", "#D9FDFF");
                alert('Por favor seleccione un tipo de servicio');
                return false;
            }

            if ($('#listaFormaPago').val() === '...') {
                $('#divListaFormaPago').css("background-color", "#D9FDFF");
                alert('Por favor seleccione una forma de pago');
                return false;
            }

            if ($('#origen1').val() === '') {
                $('#origen1').css("background-color", "#D9FDFF");
                alert('Para generar la cotización al menos debe existir un origen');
                return false;
            }

            if ($('#destino1').val() === '') {
                $('#destino1').css("background-color", "#D9FDFF");
                alert('Para generar la cotización al menos debe existir un destino');
                return false;
            }

            if ($('#cantidad1').val() === '') {
                $('#cantidad1').css("background-color", "#D9FDFF");
                alert('No ha colocado una cantidad válida.');
                return false;
            }

            return true;
        }

    });

    $("#vaSerVlrDeclarar1").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar2").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar3").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar4").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar5").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar6").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar7").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar8").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar9").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar10").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar11").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar12").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar13").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar14").click(function () {
        $(this).val('');
    });
    $("#vaSerVlrDeclarar15").click(function () {
        $(this).val('');
    });

    $("#vaSerVlrTotal1").focus(function () {
        $("#vaSerVlrTotal1").val(parseInt($("#vaSerVlrFlete1").val()) + parseInt($("#vaSerVlrSeguro1").val()) + parseInt($("#vaOtrosCargos1").val()));
    });

    $("#vaSerVlrTotal2").focus(function () {
        $("#vaSerVlrTotal2").val(parseInt($("#vaSerVlrFlete2").val()) + parseInt($("#vaSerVlrSeguro2").val()) + parseInt($("#vaOtrosCargos2").val()));
    });

    $("#vaSerVlrTotal3").focus(function () {
        $("#vaSerVlrTotal3").val(parseInt($("#vaSerVlrFlete3").val()) + parseInt($("#vaSerVlrSeguro3").val()) + parseInt($("#vaOtrosCargos3").val()));
    });

    $("#vaSerVlrTotal4").focus(function () {
        $("#vaSerVlrTotal4").val(parseInt($("#vaSerVlrFlete4").val()) + parseInt($("#vaSerVlrSeguro4").val()) + parseInt($("#vaOtrosCargos4").val()));
    });

    $("#vaSerVlrTotal5").focus(function () {
        $("#vaSerVlrTotal5").val(parseInt($("#vaSerVlrFlete5").val()) + parseInt($("#vaSerVlrSeguro5").val()) + parseInt($("#vaOtrosCargos5").val()));
    });

    $("#vaSerVlrTotal6").focus(function () {
        $("#vaSerVlrTotal6").val(parseInt($("#vaSerVlrFlete6").val()) + parseInt($("#vaSerVlrSeguro6").val()) + parseInt($("#vaOtrosCargos6").val()));
    });

    $("#vaSerVlrTotal7").focus(function () {
        $("#vaSerVlrTotal7").val(parseInt($("#vaSerVlrFlete1").val()) + parseInt($("#vaSerVlrSeguro7").val()) + parseInt($("#vaOtrosCargos7").val()));
    });

    $("#vaSerVlrTotal8").focus(function () {
        $("#vaSerVlrTotal8").val(parseInt($("#vaSerVlrFlete8").val()) + parseInt($("#vaSerVlrSeguro8").val()) + parseInt($("#vaOtrosCargos8").val()));
    });

    $("#vaSerVlrTotal9").focus(function () {
        $("#vaSerVlrTotal9").val(parseInt($("#vaSerVlrFlete9").val()) + parseInt($("#vaSerVlrSeguro9").val()) + parseInt($("#vaOtrosCargos9").val()));
    });

    $("#vaSerVlrTotal10").focus(function () {
        $("#vaSerVlrTotal10").val(parseInt($("#vaSerVlrFlete10").val()) + parseInt($("#vaSerVlrSeguro10").val()) + parseInt($("#vaOtrosCargos10").val()));
    });

    $("#vaSerVlrTotal11").focus(function () {
        $("#vaSerVlrTotal11").val(parseInt($("#vaSerVlrFlete11").val()) + parseInt($("#vaSerVlrSeguro11").val()) + parseInt($("#vaOtrosCargos11").val()));
    });

    $("#vaSerVlrTotal12").focus(function () {
        $("#vaSerVlrTotal12").val(parseInt($("#vaSerVlrFlete12").val()) + parseInt($("#vaSerVlrSeguro12").val()) + parseInt($("#vaOtrosCargos12").val()));
    });

    $("#vaSerVlrTotal13").focus(function () {
        $("#vaSerVlrTotal13").val(parseInt($("#vaSerVlrFlete13").val()) + parseInt($("#vaSerVlrSeguro13").val()) + parseInt($("#vaOtrosCargos13").val()));
    });

    $("#vaSerVlrTotal14").focus(function () {
        $("#vaSerVlrTotal14").val(parseInt($("#vaSerVlrFlete14").val()) + parseInt($("#vaSerVlrSeguro14").val()) + parseInt($("#vaOtrosCargos14").val()));
    });

    $("#vaSerVlrTotal15").focus(function () {
        $("#vaSerVlrTotal15").val(parseInt($("#vaSerVlrFlete15").val()) + parseInt($("#vaSerVlrSeguro15").val()) + parseInt($("#vaOtrosCargos15").val()));
    });



    $("#botontotal").click(function () {

        var cajasValores = [parseInt($("#vaSerVlrTotal1").val()), parseInt($("#vaSerVlrTotal2").val()), parseInt($("#vaSerVlrTotal3").val()), parseInt($("#vaSerVlrTotal4").val()), parseInt($("#vaSerVlrTotal5").val()), parseInt($("#vaSerVlrTotal6").val()), parseInt($("#vaSerVlrTotal7").val()), parseInt($("#vaSerVlrTotal8").val()), parseInt($("#vaSerVlrTotal9").val()), parseInt($("#vaSerVlrTotal10").val()), parseInt($("#vaSerVlrTotal11").val()), parseInt($("#vaSerVlrTotal12").val()), parseInt($("#vaSerVlrTotal13").val()), parseInt($("#vaSerVlrTotal14").val()), parseInt($("#vaSerVlrTotal15").val())];
        var acumulado = 0;

        for (var i = 0; i <= cajasValores.length; i++) {

            if (cajasValores[i] > 0) {
                acumulado = acumulado + cajasValores[i];
            } else {
                i = cajasValores.length;
            }
        }

        if (acumulado === 0) {
            alert("No tiene valores válidos en: \n - VALOR FLETE ó \n - VALOR SEGURO \n ¡Por favor verifique!");
        } else {
            $("#vaSerOrigen15").val(acumulado.toString());
        }

    });

    $("#iniciarCotizacion").click(function () {
        if ($("#idCliente").val() === '0') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-warning'>Para iniciar la creaci&oacute;n de una cotizaci&oacute;n; por favor seleccione un cliente</div>");
            $("#idCliente").focus();
        } else {
            window.location.href = "../modulos/cotizacion.php?pj=" + $("#idCliente").val();
        }
    });

    $("#listarClientes").click(function () {
        window.location.href = "../modulos/cotizacion.php?pj=1";
    });

});



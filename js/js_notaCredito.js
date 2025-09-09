$(document).ready(function () {

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
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

            if ($('#cantidad1').val() === '') {
                $('#cantidad1').css("background-color", "#D9FDFF");
                alert('No ha colocado una cantidad válida.');
                return false;
            }


            return true;
        }

    });

    $("#botontotal").click(function () {

        var cajasValores = [parseInt($("#vlrUnit1").val()), parseInt($("#vlrUnit2").val()), parseInt($("#vlrUnit3").val()), parseInt($("#vlrUnit4").val()), parseInt($("#vlrUnit5").val()), parseInt($("#vlrUnit6").val()), parseInt($("#vlrUnit7").val()), parseInt($("#vlrUnit8").val()), parseInt($("#vlrUnit9").val()), parseInt($("#vlrUnit10").val()), parseInt($("#vlrUnit11").val()), parseInt($("#vlrUnit12").val()), parseInt($("#vlrUnit13").val()), parseInt($("#vlrUnit14").val()), parseInt($("#vlrUnit15").val())];
        var acumulado = 0;

        for (var i = 0; i <= cajasValores.length; i++) {

            if (cajasValores[i] > 0) {
                acumulado = acumulado + cajasValores[i];
            } else {
                i = cajasValores.length;
            }
        }

        if (acumulado === 0) {
            alert("No tiene valores válidos en: \n - V/R UNITARIO \n ¡Por favor verifique!");
        } else {
            $("#vaSerOrigen15").val(acumulado.toString());
            $("#vlrLetras").val(NumeroALetras($('#vaSerOrigen15').val()));
        }

    });
    
    $("#botonRealizarConsulta").click(function (){        
        if($("#idCliente").val()==='0'){
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Por favor seleccione un Cliente de la lista</div>');
            $("#idCliente").focus();
        }else{
            window.location.href = "../modulos/notaCredito.php?pj="+$("#idCliente").val();
        }
    });
    
    $("#listarClientes").click(function (){
        window.location.href = "../modulos/notaCredito.php";
    });
    
});

function traerDatosGuia(valor) {
    $("#mensaje").html('');
    var vlr = "#" + valor.id;
    var lon = vlr.length;
    var id = vlr.substr(5, lon);
    var guia = $(vlr).val();

    $.ajax({
        url: "../trafico/Prefactura.php",
        data: {'guia': guia,
            'opcion': '1'
        },
        type: "POST",
        success: function (data) {
            //console.log(data);
            var obj = JSON.parse(data);
            //console.log(obj);
            if (obj.length === 0) {
                $("#mensaje").html("<div class='alert alert-dismissible alert-danger'>Con el n&uacute;mero de gu&iacute;a: <strong>" + guia + " No se encuentra servicio asociado</strong></div>");
                $("#guia" + id).val('0');
                $("#guia" + id).focus();
            } else if (obj[0].factura === '0') {
                $("#mensaje").html("<div class='alert alert-dismissible alert-danger'>El n&uacute;mero de gu&iacute;a: <strong>" + guia + " No tiene factura asociada</strong></div>");
                $("#guia" + id).val('0');
                $("#guia" + id).focus();
            } else {
                $("#numFac" + id).val(obj[0].factura);
                $("#fecFac" + id).val(obj[0].fecha.substr(0, 10));
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function traerDatosGuia(valor){...");
        }
    });
}
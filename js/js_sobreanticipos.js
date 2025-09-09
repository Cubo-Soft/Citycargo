$(document).ready(function () {

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#val_valorAdelanto").focus(function () {
        $("#val_valorAdelanto").val('');
        $("#totalAdelantos").val('');
        $("#totalAPagar").val('');
        $("#mensajes").html("");
        $("#botonGenerarSobreAnticipo").attr("disabled", false);
        $('#valorLetras').val('');
    });

    $("#val_valorAdelanto").blur(function () {

        if (parseInt($("#val_valorAdelanto").val()) === 0 || parseInt($("#val_valorAdelanto").val()) < 0 || $("#val_valorAdelanto").val() === '') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Para generar un <strong>sobreanticipo</strong> el valor debe ser superior a cero. Por favor verifique </div>");
            $("#val_valorAdelanto").val('');
            $("#val_valorAdelanto").focus();
        } else {

            var valorServicio = $("#valorServicio").val();
            var valorAdelantado = $("#valorAdelantado").val();
            var nuevoAdelanto = $("#val_valorAdelanto").val();

            var sumaAdelantos = parseInt(nuevoAdelanto) + parseInt(valorAdelantado);
            var totalAPagar = parseInt(valorServicio) - parseInt(sumaAdelantos);
            var porPago = (totalAPagar * 100) / valorServicio;
            $("#totalAdelantos").val(sumaAdelantos);
            
            if (totalAPagar < 0) {
                //console.log("valor menor a cero");
                $("#totalAPagar").addClass('is-invalid').val(totalAPagar);
                $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>El valor del sobreanticipo supera el valor del servicio. Por favor verifique</div>");
                $("#botonGenerarSobreAnticipo").attr("disabled", true);
                $("#val_valorAdelanto").focus();
                $('#valorLetras').val('000000000.00');
            } else if (totalAPagar === 0) {
                //console.log("valor menor igual al del servicio");
                $("#mensajes").html("<div class='alert alert-dismissible alert-success'><strong>¡Cuidado!</strong> El valor del <strong>sobreanticipo</strong> es <strong>igual</strong> que el <strong>valor del servicio</strong>. Se pagaría el servicio por completo</div>");
                $("#totalAPagar").removeClass('is-invalid').val(totalAPagar);
                $("#totalAPagar").val(totalAPagar);
                $('#valorLetras').val(NumeroALetras($('#val_valorAdelanto').val()));
            } else {
                //console.log("todo bien");
                $("#totalAPagar").val(totalAPagar);
                $('#valorLetras').val(NumeroALetras($('#val_valorAdelanto').val()));
                $("#mensajes").html("<div class='alert alert-dismissible alert-success'>Aún queda el " + porPago.toFixed(1) + "% del valor por pagar al propietario</div>");
            }
        }

        porPago = 0;

    });
    
    $("#botonGenerarSobreAnticipo").click(function (){
        if(confirm("El anticipo se generará una única vez\nLa descarga producira un archivo tipo pdf que puede abrir desde el navegador\nPor favor tenga presente su carpeta de descargas, allí también lo encontrará ")){
            return true;
        }else{
            return false;
        }
    });

});

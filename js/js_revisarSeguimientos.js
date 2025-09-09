var tabla = '';

$(document).ready(function () {

    if ($("#idservicio").val() !== '0') {
        consultarPorIdServicio($("#idservicio").val(), 2);
    }

    if ($("#guia").val() !== '0') {
        consultarPorGuia($("#guia").val(), 2);
    }

    $("#guia").blur(function () {
        if ($("#guia").val() !== '0') {
            consultarPorGuia($("#guia").val(), 2);
        } else {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Para realizar la consulta se debe digitar un:<br>-N&uacute;mero de servicio ó<br>-N&uacute;mero de gu&iacute;a</div>");
        }
    });

    $("#idservicio").blur(function () {
        if ($("#idservicio").val() !== '0') {
            consultarPorIdServicio($("#idservicio").val(), 2);
        } else {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Para realizar la consulta se debe digitar un:<br>-N&uacute;mero de servicio ó<br>-N&uacute;mero de gu&iacute;a</div>");
        }
    });

    $("#idservicio").on("focusin", function () {
        $(this).val('');
        $("#guia").val('0');
        $("#idanticipo").val('0');
    });

    $("#guia").on("focusin", function () {
        $(this).val('');
        $("#idservicio").val('0');
        $("#idanticipo").val('0');
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });
    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

});
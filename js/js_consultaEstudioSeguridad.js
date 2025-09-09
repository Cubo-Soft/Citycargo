$(document).ready(function () {

    $("input[type='text']").prop("disabled", true);
    $("input[type='number']").prop("disabled", true);
    $("input[type='date']").prop("disabled", true);
    $("input[type='email']").prop("disabled", true);
    $("textarea").prop("disabled", true);

    $("#placa").prop("disabled", false);

    $("#botonNuevoEstudio").on("click", function () {
        borrarFormulario(1);
    });

    $("#placa").on("blur", function () {

        retornarEstudioSeguridad($(this).val());
    });

    $("#crearPdf").on("click", function () {
        window.print();
    });

});

var ingreso = "", boton_ = null, guia = null, factura = null;
$(document).ready(function () {

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $('#botonGenerarExcel').click(function () {
        $("#tablaExcel").table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: 'archivo',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    });

});
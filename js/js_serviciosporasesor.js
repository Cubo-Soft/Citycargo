var date = null, dia = null, mes = null, anio = null, fecha = null, servicios = null, fecha = null, diferencia = null, porGan = null,
        totVlrCont = null, totVlrCli = null, totPor = null, fechaActual = null, asesores = '', fechaSeleccion = null, fechaInicial = null,
        fechaFinal = null, asesor = null, serviciosVarios = [], auxiliar = null, parqueadero = null, otros = null, otrosValores = null, valoresExtras = {}, porFacturar = 0,
        clase = null, clase2 = null, totalValoresDos = null;
;
$(document).ready(function () {
    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
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

    $("#botonRegresar").click(function () {
        window.location.href = "../modulos/index.php";
    });


});

function consultarServicios(opcion) {

    date = null;
    dia = null;
    mes = null;
    anio = null;
    fecha = null;
    servicios = null;
    fecha = null;
    diferencia = null;
    porGan = null;
    totVlrCont = null;
    totVlrCli = null;
    totPor = null;
    fechaActual = null;
    asesores = '';
    fechaSeleccion = null;
    fechaInicial = null;
    fechaFinal = null;
    asesor = null;
    fechaAsesor = null;
    fechaPagado = null;
    municipios = [];
    municipioOrigen = null;
    municipioDestino = null;
    serviciosVarios = [];
    nitEmpresa = null;
    auxiliar = null;
    parqueadero = null;
    otros = null;
    otrosValores = null;

    if ($("#rol_id").val() === '2' || $("#rol_id").val() === '6' || $("#rol_id").val() === '7') {
        if (confirm("Para ver el listado de servicios general presione Aceptar/OK\nPara ver su listado de servicios presione Cancelar/Cancel")) {
            primerTraida(1);
        } else {
            segundaTraida();
        }
    } else {
        segundaTraida();
    }

}
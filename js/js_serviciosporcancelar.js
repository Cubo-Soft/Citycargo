var ingreso = "", boton_ = null, guia = null, factura = null;
$(document).ready(function () {

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

});

function enviarServicio(condicion, idservicio) {
    switch (condicion) {
        case 1:
            window.open("../modulos/administrarServiciosDos.php?idserv=" + idservicio+"",'_blank');
            break;

        case 2:
            window.open("../modulos/administrarServiciosDos.php?idserv=" + idservicio+"",'_blank');
            break;
    }

}

function borrarMensaje(idmensaje, idservicio) {

    if (confirm("Suprimir mensaje de cancelación del servicio: " + idservicio)) {
        $.ajax({
            url: "../trafico/cambiarMensajeBorrado.php",
            data: {'idservicio': idservicio},
            type: "POST",
            success: function (data) {
                //console.log(data);
                var obj = JSON.parse(data);
                if (obj !== false) {
                    location.reload();
                } else {
                    alert("Ha ocurrido un error en AJAX en function borrarMensaje(idmensaje) {...");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function function borrarMensaje(idmensaje) { ajax...");
            }
        });
    }

    return false;
}
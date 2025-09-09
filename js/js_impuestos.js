$(document).ready(function () {

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

});

function cambiarAnio(valor) {
    var id1 = valor.id;
    var vlr = $("#" + id1).val();
    $.ajax({
        url: "../trafico/Impuestos.php",
        data: {
            'caso': '1',
            'anio': vlr
        },
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj === 1) {
                location.reload();
            } else {
                $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Ha ocurrido un error al modificar el año. Por favor presione F5 e intentelo nuevamente. Si persiste por favor informe</div>');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX function cambiarAnio(valor) {...");
        }
    });
}

function cambiarValor(valor) {
    var id = valor.id;
    var vlr = $("#" + id).val();
    var id1 = id.substr(6, id.length);

    $.ajax({
        url: "../trafico/Impuestos.php",
        data: {
            'caso': '2',
            'valor': vlr,
            'id': id1
        },
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj === 1) {
                location.reload();
            } else {
                $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Ha ocurrido un error al modificar el valor solicitado. Por favor presione F5 e intentelo nuevamente. Si persiste por favor informe</div>');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX function cambiarValor(valor) {...");
        }
    });

}

function cambiarBase(valor) {
    var id = valor.id;
    var vlr = $("#" + id).val();
    var id1 = id.substr(5, id.length);

    $.ajax({
        url: "../trafico/Impuestos.php",
        data: {
            'caso': '3',
            'valor': vlr,
            'id': id1
        },
        type: "POST",
        success: function (data) {            
            var obj = JSON.parse(data);
            if (obj === 1) {
                location.reload();
            } else {
                $("#mensajes").html('<div class="alert alert-dismissible alert-danger"><strong>Ha ocurrido un error al modificar el valor solicitado. Por favor presione F5 e intentelo nuevamente. Si persiste por favor informe</div>');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX function cambiarValor(valor) {...");
        }
    });

}
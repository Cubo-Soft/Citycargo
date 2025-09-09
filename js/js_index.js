/*
 20160321 Agrego en los btn que quede el campo txt_documentocliente borrado
 */

var valor = '';

$(document).ready(function () {

    $("#usuario").focus(function () {
        $("#mensajes").html('');
    });

    $("#DivPorRevisar").hide();
    $('#DivPorFacturar').hide();
    $('#DivPorPagar').hide();
    $('#DivSeguimiento').hide();

    retornarPermisosAlertas();
});

function retornarPermisosAlertas() {
    $.ajax({
        url: "../trafico/Modulosempleados.php",
        data: {'caso': 2, 'cedula': $("#emp_cedula").val()},
        type: "POST",
        success: function (data) {
            
            var obj = JSON.parse(data);

            if (obj[2].length > 0) {
                if (parseInt(obj[2][0]["estado"]) === 1) {
                    $("#DivPorRevisar").show();
                }
            }

            if (obj[3].length > 0) {
                if (parseInt(obj[3][0]["estado"]) === 1) {
                    $('#DivPorFacturar').show();
                }
            }

            if (obj[4].length > 0) {
                if (parseInt(obj[4][0]["estado"]) === 1) {
                    $('#DivPorPagar').show();
                }
            }

            if (obj[5].length > 0) {
                if (parseInt(obj[5][0]["estado"]) === 1) {
                    $('#DivSeguimiento').show();
                }
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error retornarPermisosAlertas()...retorno desde el servidor");
        }
    });
}


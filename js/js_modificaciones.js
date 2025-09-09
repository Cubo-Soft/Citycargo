var nA = null;
$(document).ready(function () {
    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });
    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

//    $("#accionesAnticipos").change(function () {
//        switch ($("#accionesAnticipos").val()) {
//            case 'Modificar':
//                $("#mensajesModificaciones").html('<div class="alert alert-dismissible alert-success"><strong>Modificar</strong> el valor del anticipo</div>');
//                break;
//            case 'Adicionar':
//                $("#mensajesModificaciones").html('<div class="alert alert-dismissible alert-success"><strong>Adicionar</strong> al valor del servicio y sumar dicha adici&oacute;n al costo total del servicio</div>');
//                break;
//            case 'Cancelar':
//                $("#mensajesModificaciones").html('<div class="alert alert-dismissible alert-success"><strong>Cancelar</strong> anticipo. Se debe crear el servicio nuevamente</div>');
//                break;
////            case 'Otro':
////                $("#mensajesModificaciones").html('<div class="alert alert-dismissible alert-success col-xs-12"><strong>Otro</strong> Por favor digite el texto correspondiente<br>\n\
////<div class="col-xs-12" id="divInsertado"><div></div><div class="col-xs-4">Del motivo por el cual </div><div class="col-xs-8"><input type="text" name="otroMotivo" id="otroMotivo" class="form-control"  /></div>\n\
////</div>');
//                break;
//            default:
//                $("#mensajesModificaciones").html('');
//                break;
//        }
//    });

    $("#numeroAnticipo").blur(function () {
        if ($("#accionesAnticipos").val() === '0') {
            $("#mensajesModificaciones").html('<div class="alert alert-dismissible alert-danger">Seleccione un acci&oacute;n de la lista</div>');
            $("#accionesAnticipos").focus();
        } else if ($("#numeroAnticipo").val() === '' || $("#numeroAnticipo").val() < 0) {
            $("#mensajesModificaciones").html('<div class="alert alert-dismissible alert-danger">El valor del anticipo debe ser superior a cero</div>');
            $("#accionesAnticipos").focus();
        } else {
            $("#mensajesModificaciones").html("");
            switch ($("#accionesAnticipos").val()) {
                case '0':
                    $("#mensajesModificaciones").html('<div class="alert alert-dismissible alert-danger">Seleccione un acci&oacute;n de la lista</div>');
                    $("#accionesAnticipos").focus();
                    break;

                case 'Modificar':
                    $("#mensajesModificaciones").html('<div class="alert alert-dismissible alert-success">Opci&oacute;n no programada...</div>');
                    break;

                case 'Adicionar':
                    $("#mensajesModificaciones").html('<div class="alert alert-dismissible alert-success">Opci&oacute;n por programar...</div>');
                    break;

                case 'Cancelar':
                    nA=$("#numeroAnticipo").val()
                    cancelarAnticipo($("#accionesAnticipos").val(),nA);
                    break;
                default:

                    break;
            }
        }
    });

});

function cancelarAnticipo(accion, numeroAnticipo) {    
    $.ajax({
        url: "../trafico/cancelarAnticipo.php",
        data: {'accion': accion, 'numeroAnticipo': numeroAnticipo},
        type: "POST",
        success: function (data) {            
            var obj = JSON.parse(data);
            if (obj !== false) {
                $("#mensajesModificaciones").html('<div class="alert alert-dismissible alert-success"><strong>El anticipo</strong> ' + nA + ' ha sido cancelado</div>');
                $("#accionesAnticipos").val('0');
                $("#numeroAnticipo").val('');
                $("#accionesAnticipos").focus();
            } else {
                alert("Fallo en respuesta desde el servicidor, function cancelarAnticipo(accion,numeroAnticipo)...");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en $('#numeroAnticipo').blur() retorno de datos desde servidor");
        }
    });
}
$(document).ready(function () {

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });
    $('#botonRegresar').click(function () {
        if ($("#rol_id").val() === '7') {
            window.location.href = "../modulos/consultasAsesores.php";
        } else {
            window.location.href = "../modulos/index.php";
        }
    });

    $("#mensajes").hide();
    $("#mostarSeguimento").hide();

    $("#habilitarMensajes").click(function () {
        var mensajes = $("#mensajes").is(':visible');
        if ($("#mensajes").is(':visible') === false) {
            $("#mensajes").show();
            $("#mostrarSeguimiento").hide();
            $("#idservicio").hide();
        } else {
            $("#ocultar").hide();
            $("#mostrarSeguimiento").hide();
            $("#mensajes").hide();
            $("#guia").val('');
            $("#idservicio").hide();
        }
    });

    $("#guia").blur(function () {
        var tabla = null, imagen = null, enRuta = null, botonServicio = null, generarPdf = null;
        generarPdf = "<input type='submit' id='generarPdf' name='generarPdf' class='btn btn-success' onclick='generarPdf();' value='Generar PDF' />";
        if (parseInt($("#guia").val()) !== 0) {
            $("#mostrarSeguimiento").show();
            $.ajax({
                url: "../trafico/Seguimiento.php",
                data: {'caso': '2',
                    'guia': $("#guia").val()},
                type: "POST",
                success: function (respuesta) {
                    var obj = JSON.parse(respuesta);
                    if (obj.datosGuia === 0) {
                        tabla = '';
                        if (obj.seguimiento_servicio === 0) {
                            tabla = '<div class="alert alert-danger" id="ocultar">La gu&iacute;a: ' + $("#guia").val() + ' no se encuentra en ningún servicio</div>';
                        } else if (obj.seguimiento_servicio === 1) {
                            tabla = '<div class="alert alert-success" id="ocultar">La gu&iacute;a: ' + $("#guia").val() + ' existe ya en el SIG antes de m&oacute;dulo de seguimiento</div>';
                        } else {
                            tabla = '<div class="alert alert-success" id="ocultar">El servicio no tiene seguimientos asociados. Se puede realizar <button id="idservicio" name="idservicio" value="' + obj.idservicio + '" class="btn btn-success btn-xs" onclick="redireccionar(this);" >Seguimiento</button></div>';
                        }
                    } else {
                        if (obj.estadoServicio === '0') {
                            botonServicio = "<tr><td><input type='hidden' id='idservicio' value='" + obj.datosGuia[0].idservicio + "' /><button id='botonSeguimiento' name='botonSeguimiento' value='" + obj.datosGuia[0].idservicio + "' class='btn btn-success' onclick='redireccionar(this);' >Seguimiento</button></td><td>" + generarPdf + "</td><td></td><td></td><td></td><td></td><td></td></tr>";
                        } else {
                            botonServicio = "<tr><td>" + generarPdf + "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                        }
                        tabla = '<div id="divServicios"><form action="../trafico/generarPDFGuia.php" method="post">';
                        tabla += '<input type="hidden" id="guia" name="guia" value="' + $("#guia").val() + '"/><input type="hidden" id="idservicio" name="idservicio" value="' + obj.idservicio + '"/>';
                        tabla += '<table class="table table-responsive" id="ocultar"><tbody>';
                        tabla += '<tr><th>Servicio</th><th>Placa</th><th>Empresa</th><th>Origen</th><th>Desino</th><th></th><th></th><th></th></tr>';
                        tabla += '<tr><td>' + obj.idservicio + '</td><td>' + obj.placa + '</td><td>' + obj.cliente + '</td><td>' + obj.origen + '</td><td>' + obj.destino + '</td><td></td><td></td><td></td></tr>';
                        tabla += '<tr><th>Gu&iacute;a</th><th>Fecha/hora</th><th>Ubicaci&oacute;n</th><th>Observaci&oacute;n</th><th>Imagen</th><th>Usuario</th><th>Acorde con plan de ruta</th><th>Estado</th></tr>';
                        for (var i = 0; i < obj.datosGuia.length; i++) {
                            if (obj.datosGuia[i].imagen === '0') {
                                imagen = 'No hay imagen GPS';
                            } else {
                                imagen = '<img src="' + obj.datosGuia[i].imagen + '" alt="" width="300" height="150"  />';
                            }

                            if (obj.datosGuia[i].planderuta === '1') {
                                enRuta = 'SI';
                            } else {
                                enRuta = 'NO';
                            }
                            tabla += '<tr><td>' + obj.datosGuia[i].guia + '</td><td>' + obj.datosGuia[i].fechaHora + '</td><td>' + obj.datosGuia[i].ubicacion + '</td><td>' + obj.datosGuia[i].observacion + '</td><td>' + imagen + '</td><td>' + obj.datosGuia[i].nombreEmpleado + '</td><td>' + enRuta + '</td><td>' + obj.datosGuia[i].estadoseguimiento + '</td></tr>';
                        }
                        tabla += botonServicio;
                        tabla += '</tbody></form>';
                        tabla += '<table>';
                        tabla += '<tr>';
                        tabla += '<td><input type="button" value="Agregar pruebas de entrega" class="btn btn-success" onclick="redireccionar(null,2);"/></td>';
                        tabla += '<td>&#160;</td>';
                        tabla += '<td><input type="button" value="Mostrar servicios pendientes por seguimiento" class="btn btn-success" onclick="redireccionar(null,3);"/></td>';
                        tabla += '</tr>';
                        tabla += '</table>';
                        tabla += '</div>';
                    }
                    $("#mostrarSeguimento").html(tabla);

                    $("#DivConsultarSeguimiento").show();
                    $("#DivListaSeguimientos").hide();
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX en $('#guia').blur(function () {...");
                }
            });
        } else {
            $("#mostrarSeguimiento").html('');
            $("#mostrarSeguimiento").html('<div class="alert alert-danger">Por favor digite un n&uacute;mero de gu&iacute;a v&aacute;lido</div>');
        }
    });
});

function redireccionar(valor) {
    return false;
}
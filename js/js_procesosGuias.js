$(document).ready(function () {

    var mensaje = null;
    var boton = null;
    var arreglo = new Array();
    var pos = 0;
    var arregloEnvio = null;

    /**
     * 201707181454
     * Cambiar de persona una o varias guias
     */

    $("#usadas").click(function () {

        /*
         * Verifico si el arreglo tiene seleccionada alguna guia para pagar
         */
        if (arreglo.length !== 0) {
            arregloEnvio = JSON.stringify(arreglo);
            $.ajax({
                url: "../trafico/Guias.php",
                data: {'numeroGuia': arregloEnvio, 'documento': $("#documento").val(), 'caso': '1'},
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj !== false) {
                        pintarTablaPendientes(obj);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX en el evento cambiarGuias");
                }
            });
        } else {
            $("#mensajes").html('<input type="button" value="No hay guias seleccionadas para cambiar. Por favor verifique" class="btn btn-danger" />');
        }

        arreglo = new Array();
        arregloEnvio = new Array();

    });

    $("#ingresarConductor").css("display", "none");
    $("#ingresarEmpresa").css("display", "none");

    /*
     * Establece la altura del div mostrarTrasabilidad en 0
     */
    $("#mostrarTrasabilidad").height(0);

    /*
     * Trae las guias pendientes dadas a un documento
     */
    $("#consultar").click(function () {
        if (verificarConsulta() === 5) {
            $.ajax({
                url: "../trafico/Guias.php",
                data: {'documento': $("#listaEmpresas").val(),
                    'fechaInicial': $("#fechaInicial").val(),
                    'fechaFinal': $("#fechaFinal").val(),
                    'ordenarPor': $("#ordenarPor").val(),
                    'condicion': $("#condicion").val(),
                    'caso': '4'},
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if ($("#ordenarPor").val() === '1' && $("#condicion").val() === '1') {
                        pintarTablaPendientes(obj);
                    }
                    if ($("#ordenarPor").val() === '1' && $("#condicion").val() === '2') {
                        pintarTablaPendientes(obj);
                    }
                    if ($("#ordenarPor").val() === '1' && $("#condicion").val() === '3') {
                        pintarTablaPendientes(obj);
                    }
                    if ($("#ordenarPor").val() === '2' && $("#condicion").val() === '1') {
                        pintarTotales(obj);
                    }
                    if ($("#ordenarPor").val() === '2' && $("#condicion").val() === '2') {
                        pintarTotales(obj);
                    }
                    if ($("#ordenarPor").val() === '2' && $("#condicion").val() === '3') {
                        pintarTotales(obj);
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX funcion consultarGuiasPendientes");
                }
            });
        }
    });

    /*
     * Borro el contenido del div mensajes cuando el campo numeroInicial recibe el foco
     */
    $("#numeroInicial").focusin(function () {
        limpiar(1);

        $.ajax({
            url: "../trafico/Guias.php",
            data: {'caso': '7'},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj !== false) {
                    $("#numeroInicial").val(parseInt(obj.numeroGuia) + 1);
                }
            }
        });

    });

    /*
     * Evaluo si los campos documento y numeroInicial estan llenos antes de enviar el formulario
     */
    $("#ingresarGuias").click(function () {
        if ($("#listaEmpresas").val() <= 0) {
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Por favor seleccione una empresa de la lista </div>');
            $("#listaEmpresas").focus();
        } else if ($("#numeroInicial").val() <= 0 || $("#numeroInicial").val() === '') {
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">El campo N&uacute;mero inicial debe ser solo n&uacute;meros y mayor a cero. Por favor verifique </div>');
            $("#numeroInicial").focus();
        } else if ($("#cantidad").val() <= 0) {
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">El campo Cantidad debe ser solo n&uacute;meros y mayor a cero. Por favor verifique </div>');
            $("#cantidad").focus();
        } else if (confirm("Los números de guía a ingresar para " + $("#listaEmpresas option:selected").html() + "\ninician en " + $("#numeroInicial").val() + "\ny finalizan en " + $("#numeroFinal").val() + "\nCorrecto?")) {
            $.ajax({
                url: "../trafico/Guias.php",
                data: {'documento': $("#listaEmpresas").val(),
                    'nombreEmpresa': $('#listaEmpresas option:selected').html(),
                    'numeroInicial': $("#numeroInicial").val(),
                    'numeroFinal': $("#numeroFinal").val(),
                    'caso': '5'},
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj === 1) {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-success">Se han creado las gu&iacute;as de manera correcta</div>');
                        limpiar(1);
                    } else {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Ha ocurrido un error. Por favor presione F5 e intentelo de nuevo. Si la falla a persiste por favor informe</div>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX funcion ingresarGuias");
                },
                beforeSend: function () {
                    $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Creando el conjunto de gu&iacute;as, un momento por favor...</div>');
                }
            });
        } else {
            limpiar(1);
        }
    });

    /*
     *Obtengo los numeros finales de acuerdo a la cantidad de guias a guardar en la base 
     */
    $("#cantidad").focusout(function () {
        var numeroInicial = $("#numeroInicial").val();
        var cantidad = $("#cantidad").val();
        var numeroFinal = parseInt(numeroInicial) + parseInt(cantidad) - 1;
        $("#numeroF").val(numeroFinal);
        $("#numeroFinal").val(numeroFinal);
    });

    /*
     * Consulta la trasabilidad de una guia de acuerdo al número ingresado
     */
    $('#numeroInicial').blur(function () {
        var usada = '';
        $.ajax({
            url: "../trafico/Guias.php",
            data: {'numeroInicial': $("#numeroInicial").val(), 'caso': '6'},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj !== false) {
                    var clase = "'table table-striped table-hover'";
                    var mensaje = "<table class=" + clase + "><thead><tr><th>Id</th><th>N&uacute;mero gu&iacute;a</th><th>Usuario</th><th>Fecha asignaci&oacute;n</th><th>Fecha usada</th></tr></thead>";
                    mensaje = mensaje + "<tr class=''>\n\
<td>" + obj.id_guia + "</td>\n\
<td>" + obj.numeroGuia + "</td>\n\
<td>" + obj.nombre + "</td>\n\
<td>" + obj.fechaHora + "</td>";
                    if (obj.usada === '1000-01-01 00:00:00') {
                        usada = '';
                    } else {
                        usada = obj.usada;
                    }
                    mensaje += "<td>" + usada + "</td>\n\
</tr><tbody></tbody></table>";
                    $("#mostrarTrasabilidad").height(90);
                    $("#mostrarTrasabilidad").html(mensaje);
                    $("#cantidad").prop("disabled", true);
                    $("#numeroFinal").prop("disabled", true);
                } else {
                    $("#cantidad").prop("disabled", false);
                    $("#numeroFinal").prop("disabled", false);
                    $("#mostrarTrasabilidad").height(0);
                }

            }
        });
    });

    $("#cantidad").focusin(function () {
        $("#mostrarTrasabilidad").height(0);
    });

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });


    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $('#ingresarConductor').click(function () {
        window.location.href = "../modulos/conductores.php";
    });

    $('#ingresarEmpresa').click(function () {
        window.location.href = "../modulos/empresas.php";
    });
});
true

function limpiar(opcion) {
    if (opcion === 1) {
        //$("#mostrarEmpleados").val('0');
        //$("#mostrarConductores").val('0');
        //$("#mostrarEmpleados").val('0');
        $("#mostrarEmpresas").val('0');
        $("#documento").val('');
        $("#mostrarEmpleados").val('');
        $("#numeroInicial").val('');
        $("#cantidad").val('');
        $("#numeroFinal").val('');
    }
    if (opcion === 2) {
        $("#mensajes").html('');
        $("#documento").val('0');
        $("#cantidad").val('');
        $("#numeroFinal").val('');
        $("#numeroInicial").val('');
        //$("#mostrarTrasabilidad").html('');
        //$("#mostrarTrasabilidad").height(0);
        //$("#ingresarConductor").css("display", "none");
        //$("#ingresarEmpresa").css("display", "none");
    }
}

function pintarTablaPendientes(obj) {

    $("#mensajes").html('');

    var imprimir = '<table class="table table-striped table-hover ">';
    imprimir += '<thead><tr>';
    imprimir += '<th>Id</th>';
    imprimir += '<th>N&uacute;mero gu&iacute;a</th>';
    imprimir += '<th>Fecha asignaci&oacute;n</th>';
    if ($("#ordenarPor").val() === '1' && $("#condicion").val() === '1') {
        imprimir += '<th>Usada</th>';
    }
    if ($("#ordenarPor").val() === '1' && $("#condicion").val() === '3') {
        imprimir += '<th>Usada</th>';
    }
    imprimir += '<th>Identificaci&oacute;n</th>';
    imprimir += '<th>Nombres</th>';
    imprimir += '</tr></thead><tbody>';
    var clase = null;
    var b = 1;
    for (var a = 0; a < obj.length; a++) {
        if (a % 2 === 0) {
            clase = "class='active'";
        } else {
            clase = "class='default'";
        }
        imprimir += '<tr ' + clase + '">';
        imprimir += '<td>' + b + '</td>';
        imprimir += '<td>' + obj[a].numeroGuia + '</td>';
        imprimir += '<td>' + obj[a].fechaHora + '</td>';

        if ($("#ordenarPor").val() === '1' && $("#condicion").val() === '1') {
            imprimir += '<th>' + obj[a].usada + '</th>';
        }

        if ($("#ordenarPor").val() === '1' && $("#condicion").val() === '3') {

            if (obj[a].usada === '1000-01-01') {
                imprimir += '<th></th>';
            } else {
                imprimir += '<th>' + obj[a].usada + '</th>';
            }
        }
        imprimir += '<td>' + obj[a].identificacion + '</td>';
        imprimir += '<td>' + obj[a].nombre + '</td>';
        imprimir += '<tr>';
        b = b + 1;
    }
    $("#mostrarTrasabilidad").html(imprimir);
    $("#mostrarTrasabilidad").height(350);
}

function pintarTotales(obj) {

    var imprimir = '<table class="table table-striped table-hover" >';

    if ($("#ordenarPor").val() === '2' && $("#condicion").val() === '1') {
        imprimir += '<tr>';
        imprimir += '<td>Empresa</td><td>Documento</td><td>Usadas</td>';
        imprimir += '</tr>';
        imprimir += '<tr>';
        imprimir += '<td>' + $('#listaEmpresas option:selected').html() + '</td><td>' + $("#listaEmpresas").val() + '</td><td>' + obj[0].totalGuias + '</td>';
        imprimir += '</tr>';

    }

    if ($("#ordenarPor").val() === '2' && $("#condicion").val() === '2') {
        imprimir += '<tr>';
        imprimir += '<td>Empresa</td><td>Documento</td><td>No usadas</td>';
        imprimir += '</tr>';
        imprimir += '<tr>';
        imprimir += '<td>' + $('#listaEmpresas option:selected').html() + '</td><td>' + $("#listaEmpresas").val() + '</td><td>' + obj[0].totalGuias + '</td>';
        imprimir += '</tr>';
    }

    if ($("#ordenarPor").val() === '2' && $("#condicion").val() === '3') {

        var totalGuias = 0, bandera = 0, totalUsadas = 0, totalPorUsar = 0;

        if (obj[0].length > 0) {
            imprimir += '<tr>';
            imprimir += '<td></td><td><strong>Fecha asignacion</strong></td><td><strong>Usadas</strong></td><td><strong>Por usar</strong></td><td><strong>Total</strong></td>';
            imprimir += '</tr>';
            for (var i = 0, max = obj[0].length; i < max; i++) {
                imprimir += '<tr>';
                imprimir += '<td></td><td>' + obj[0][i]["fechaHora"] + '</td><td>' + obj[0][i]["usada"] + '</td><td></td><td></td>';
                imprimir += '</tr>';
                totalGuias += parseInt(obj[0][i]["usada"]);
                totalUsadas += parseInt(obj[0][i]["usada"]);
            }
        } else {
            bandera = 1;
        }

        if (obj[1].length > 0) {
            if (bandera === 1) {
                imprimir += '<tr>';
                imprimir += '<td></td><td><strong>Fecha asignacion</strong></td><td><strong>Usadas</strong></td><td><strong>Por usar</strong></td><td><strong>Total</strong></td>';
                imprimir += '</tr>';
            }
            for (var i = 0, max = obj[1].length; i < max; i++) {
                imprimir += '<tr>';
                imprimir += '<td></td><td>' + obj[1][i]["fechaHora"] + '</td><td></td><td>' + obj[1][i]["porUsar"] + '</td><td></td>';
                imprimir += '</tr>';
                totalGuias += parseInt(obj[1][i]["porUsar"]);
                totalPorUsar += parseInt(obj[1][i]["porUsar"]);
            }
        }

        imprimir += '<tr>';
        imprimir += '<td></td><td></td><td><strong>' + totalUsadas + '</strong></td><td><strong>' + totalPorUsar + '</strong></td><td><strong>' + totalGuias + '</strong></td>';
        imprimir += '</tr>';

//        imprimir += '<tr>';
//        imprimir += '<td>Empresa</td><td>Documento</td><td>Todas</td>';
//        imprimir += '</tr>';
//        imprimir += '<tr>';
//        imprimir += '<td>' + $('#listaEmpresas option:selected').html() + '</td><td>' + $("#listaEmpresas").val() + '</td><td>' + obj[0].totalGuias + '</td>';
//        imprimir += '</tr>';
    }

    imprimir += '</table>';

    $("#mostrarTrasabilidad").html(imprimir);
    $("#mostrarTrasabilidad").height(250);

}


function verificarConsulta() {

    var retorno = 0;
    if ($("#listaEmpresas").val() === '0') {
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#fechaInicial").val() === '') {
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#fechaFinal").val() === '') {
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#ordenarPor").val() === '0') {
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#condicion").val() === '0') {
        retorno = 0;
    } else {
        retorno += 1;
    }

    return retorno;

}
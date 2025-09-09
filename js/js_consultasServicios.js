var date = null, dia = null, mes = null, anio = null, fecha = null, servicios = null, fecha = null, diferencia = null, porGan = null,
        totVlrCont = null, totVlrCli = null, totPor = null, fechaActual = null, asesores = '', fechaSeleccion = null, fechaInicial = null,
        fechaFinal = null, asesor = null, fechaAsesor = null, fechaPagado = null, municipios = [],
        municipioOrigen = null, municipioDestino = null, serviciosVarios = [], nitEmpresa = null, auxiliar = null, parqueadero = null, otros = null,
        valoresExtras = {}, otrosValores = null, porFacturar = 0, guias = [], clase = null, clase2 = null, totalValoresDos = null, facturas = [];
$(document).ready(function () {

    retornarFecha();
    $("#DivConsultaPlacaServicios").hide();
    $("#botonNuevaConsulta").hide();

    $("#botonNuevaConsulta").click(function () {
        mostrarOpciones($("#listarServiciosPor").val());
    });

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#btnConsultarPorPlaca").click(function () {
        if ($("#fechaInicialP").val().length === 0) {
            $("#mensajes").show();
            $("#mensajes").html("<div>Agregue una fecha inicial</div>");
        } else if ($("#placas").val() === '0') {
            $("#mensajes").show();
            $("#mensajes").html("<div>Por favor seleccione una placa</div>");
        } else {
            $.ajax({
                url: "../trafico/listarServicios.php",
                data: {'opcion': 10,
                    'fechaInicial': $("#fechaInicialP").val(),
                    'fechaFinal': $("#fechaFinalP").val(),
                    'placa': $("#placas option:selected").text()},
                type: "POST",
                beforeSend: function () {
                    $("#mensajes").html("<div class='alert alert-dismissible alert-warning'>Realizando operaciones solicitadas, un momento por favor...</div>");
                },
                success: function (data) {
                    $("listaServiciosPor").val('-1');
                    var obj = JSON.parse(data);
                    $("#mensajes").show();
                    $("#DivConsultaPlacaServicios").hide();
                    $("#placas").val('0');
                    pintarTablaGeneral(obj, 1);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error $('#btnConsultarPorPlaca').click(function () {...retorno desde el servidor");
                }
            });
        }
    });

    $("#listarServiciosPor").change(function () {

        if ($("#listarServiciosPor").val() !== '-1') {
            $("#botonNuevaConsulta").show();
        } else {
            $("#botonNuevaConsulta").hide();
        }
        mostrarOpciones($("#listarServiciosPor").val());
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

    liberarVariables();

});

function mostrarOpciones(listarServiciosPor) {

    switch (listarServiciosPor) {
        case "fechaIniFechaFin":
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            mostrarTablaFechas('1');
            break;
        case "Asesor":
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            retornarAsesores(1, null);
            break;
        case "factura":
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            mostrarDivFactura();
            break;
        case "porEmpresa":
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            mostrarTablaFechas('2');
            break;
        case "agenda":
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            mostrarDatosAsesoresFechas();
            break;
        case "porPlaca":
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            mostrarListadoPlacas();
            break;
        case "porCedula":
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            mostrarCasillaCedula();
            break;
        case 'facturado':
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            mostrarTablaFechas('3');
            break;
        case 'servFacPag':
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            mostrarTablaFechas('4');
            break;
        case 'telefonosGeneral':
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            mostrarTelefonos();
            break;
        case 'AgrupadoPorFactura':
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            mostrarTablaFechas('5');
            break;
        case 'serviciosPorPlaca':
            $("#DivConsultaPlacaServicios").show();
            $("#mensajes").hide();
            break;
        case 'manifiestosGeneral':

            break;
        case 'seguimiento':

            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            retornarAsesores(2, null);
            break;
        case '-1':
            $("#mensajes").show();
            $("#DivConsultaPlacaServicios").hide();
            $("#mensajes").html("");
            break;
        default:
            liberarVariables();
            $("#mensajes").html("En construcci&oacute;n");
            break;
    }

}

function retornarAsesores(opcion, data) {
    
    var tipoInput=null;

    $.ajax({
        url: "../trafico/retornarAsesores.php",
        data: {'opcion': opcion},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj !== false) {

                asesores = "<select class='form-control' name='listaAsesores' id='listaAsesores' ";

                if (opcion === 1) {
                     asesores+=" onchange='consultarServicios(2);' >";
                     tipoInput="date";
                }

                if (opcion === 2) {
                    asesores += " onchange='consultarServicios(8);' >";
                    tipoInput="datetime-local";
                }


                asesores += "<option value='0'>...</option>";
                for (var i = 0, max = obj.length; i < max; i++) {
                    asesores += "<option value='" + obj[i].emp_cedula + "'>" + obj[i].nombreEmpleado + "</option>";
                }
                asesores += "</select>";

                fechaAsesor = "<div class='alert alert-dismissible alert-success' ><table>\n\
<tr><td>Fecha inicial</td><td><input type='"+tipoInput+"' name='fechaInicial' id='fechaInicial' /></td>\n\
<td>Fecha final</td><td><input type='"+tipoInput+"' name='fechaFinal' id='fechaFinal' value='" + fechaActual + "' /></td><td>Asesor</td><td>" + asesores + "</td></tr>\n\
</table></div>";
                //}


                $("#mensajes").html(fechaAsesor);

            } else {
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error $('#listarServiciosPor').change(function () {...retorno desde el servidor");
        }
    });
}

function mostrarTelefonos() {
    $.ajax({
        url: "../trafico/Conductor.php",
        data: {'caso': 9,
            'placa': 0},
        type: "POST",
        beforeSend: function () {
            $("#mensajes").html("<div class='alert alert-dismissible alert-success'>Realizando operaciones solicitadas, un momento por favor...</div>");
        },
        success: function (data) {
            var obj = JSON.parse(data);
            pintarTablaDatosConductores(obj);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function mostrarTelefonos() {...retorno desde el servidor");
        }
    });
}

function pintarTablaDatosConductores(arregloConductores) {
    var tabla = "<table class='table table-sm' id='tablaExcel' >\n\
<tr><th></th><th>Placa</th><th>Tipo veh&iacute;culo</th><th>Nombre y apellidos</th><th>Tel&eacute;fono</th><th>Perfil</th></tr>";
    $.each(arregloConductores, function (ciclo, valor) {
        tabla += "<tr><td>" + ciclo + "</td><td>" + valor.placa + "</td><td>" + valor.tipovehiculo + "</td><td>" + valor.cond_nombres + " " + valor.cond_apellidos + "</td><td>" + valor.cond_telefono + "</td><td>" + valor.perfil + "</td></tr>";
    });
    tabla += "</table>";

    $("#mensajes").html(tabla);
}

function consultarServicios(opcion) {

    liberarVariables();

    switch (opcion) {
        case 1:
            primerTraida(1);
            break;
        case 2:
            segundaTraida();
            break;
        case 3:
            terceraTraida();
            break;
        case 4:
            cuartaTraida();
            break;
        case 5:
            quintaTraida();
            break;
        case 6:
            sextaTraida();
            break;
        case 7:
            septimaTraida();
            break;
        case 8:
            octavaTraida();
            break;
        default:
            alert("Opción no programada");
            break;
    }
}

function octavaTraida(){
    liberarVariables();
    
    fechaInicial = $("#fechaInicial").val();
    fechaFinal = $("#fechaFinal").val();
    asesor = $("#listaAsesores").val();

    if (asesor === undefined) {
        asesor = $("#idasesor").val();
    }

    $.ajax({
        url: "../trafico/listarServicios.php",
        data: {'opcion': 12,
            'fechaInicial': fechaInicial,
            'fechaFinal': fechaFinal,
            'idasesor': asesor},
        type: "POST",
        beforeSend: function () {
            $("#mensajes").html("<div class='alert alert-dismissible alert-info'>Realizando operaciones solicitadas, un momento por favor...</div>");
        },
        success: function (data) {
            var obj = JSON.parse(data);
            console.log(obj);
            
             servicios = '<div>\n\
<table class="table table-sm" id="tablaExcel">\n\
<tr><th></th><th>Empleado</th><th>Fecha seguimiento</th><th>Guia</th><th>Reporte</th><th>Placa</th></tr>';
            
            for (var i = 0; i < obj.length; i++) {
                servicios+='<tr><td>'+(i+1)+'</td><td>'+obj[i]['nombreEmpleado']+'</td><td>'+obj[i]['fechaSeguimiento']+'</td><td>'+obj[i]['guia']+'</td><td>'+obj[i]['reporte']+'</td><td>'+obj[i]['placa']+'</td></tr>';
            }
            
            servicios+='</table></div>';
            
            $("#mensajes").html(servicios);
            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function octavaTraida(){...retorno desde el servidor");
        }
    });
    servicios = null;
}

function sextaTraida() {
    liberarVariables();

    if ($("#fechaInicial").val() === '') {
        alert("Por favor seleccione una fecha inicial");
        $("#fechaInicial").focus();
    } else {
        $.ajax({
            url: "../trafico/listarServicios.php",
            data: {'opcion': 8,
                'fechaInicial': $("#fechaInicial").val(),
                'fechaFinal': $("#fechaFinal").val()},
            type: "POST",
            beforeSend: function () {
                $("#mensajes").html("<div class='alert alert-dismissible alert-info'>Realizando operaciones solicitadas, un momento por favor...</div>");
            },
            success: function (data) {
                //console.log(data);
                $("listaServiciosPor").val('0');
                var obj = JSON.parse(data);
                //console.log(obj);
                pintarTablaGeneral(obj, 1);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function sextaTraida() {...retorno desde el servidor");
            }
        });
    }
    servicios = null;
}

function quintaTraida() {
    liberarVariables();

    if ($("#fechaInicial").val() === '') {
        alert("Por favor seleccione una fecha inicial");
        $("#fechaInicial").focus();
    } else {
        $.ajax({
            url: "../trafico/listarServicios.php",
            data: {'opcion': 7,
                'fechaInicial': $("#fechaInicial").val(),
                'fechaFinal': $("#fechaFinal").val()},
            type: "POST",
            beforeSend: function () {
                $("#mensajes").html("<div class='alert alert-dismissible alert-info'>Realizando operaciones solicitadas, un momento por favor...</div>");
            },
            success: function (data) {
                //console.log(data);
                $("listaServiciosPor").val('0');
                var obj = JSON.parse(data);
                //console.log(obj);
                pintarTablaGeneral(obj, 1);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function quintaTraida() {...retorno desde el servidor");
            }
        });
    }
    servicios = null;
}

function primerTraida(opcion) {

    liberarVariables();

    if ($("#fechaInicial").val() === '') {
        alert("Por favor seleccione una fecha inicial");
        $("#fechaInicial").focus();
    } else {
        $.ajax({
            url: "../trafico/listarServicios.php",
            data: {'opcion': 1,
                'fechaInicial': $("#fechaInicial").val(),
                'fechaFinal': $("#fechaFinal").val(),
                'opcion2': opcion},
            type: "POST",
            beforeSend: function () {
                $("#mensajes").html("<div class='alert alert-dismissible alert-info'>Realizando operaciones solicitadas, un momento por favor...</div>");
            },
            success: function (response) {
                $("listaServiciosPor").val('0');
                var obj = JSON.parse(response);
                if (opcion === 1) {
                    pintarTablaGeneral(obj, 1);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function primerTraida(opcion) {...retorno desde el servidor");
            }
        });
    }
    servicios = null;
}

function segundaTraida() {

    liberarVariables();

    fechaInicial = $("#fechaInicial").val();
    fechaFinal = $("#fechaFinal").val();
    asesor = $("#listaAsesores").val();

    if (asesor === undefined) {
        asesor = $("#idasesor").val();
    }

    $.ajax({
        url: "../trafico/listarServicios.php",
        data: {'opcion': 2,
            'fechaInicial': fechaInicial,
            'fechaFinal': fechaFinal,
            'idasesor': asesor},
        type: "POST",
        beforeSend: function () {
            $("#mensajes").html("<div class='alert alert-dismissible alert-info'>Realizando operaciones solicitadas, un momento por favor...</div>");
        },
        success: function (data) {
            var obj = JSON.parse(data);
            pintarTablaGeneral(obj, 1);
            porFacturar = 0;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function segundaTraida(){...retorno desde el servidor");
        }
    });
    servicios = null;
}

/*
 * Esta funcion no la estoy usando 
 * Quedo pendiente cuando usarla 201812130958
 */
function terceraTraida() {

    liberarVariables();

    fechaInicial = $("#fechaInicial").val();
    fechaFinal = $("#fechaFinal").val();
    asesor = $("#listaAsesores").val();
    var vlrGuia = null;
    $.ajax({
        url: "../trafico/listarServicios.php",
        data: {'opcion': 3,
            'fechaInicial': fechaInicial,
            'fechaFinal': fechaFinal},
        type: "POST",
        beforeSend: function () {
            $("#mensajes").html("<div class='alert alert-dismissible alert-info'>Realizando operaciones solicitadas, un momento por favor...</div>");
        },
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj !== false && obj.length > 0) {
                servicios = '<div class="alert alert-dismissible alert-success"><table class="table table-sm" id="tablaExcel"><tr><th>Servicio</th><th>Placa</th><th>Fecha</th><th>Gu&iacute;a</th><th>Cliente</th><th>Asesor</th><th>Origen</th><th>Destino</th><th>Vlr Cliente</th><th>Vlr Contratista</th><th>Diferencia</th><th>%</th></tr>';
                for (var i = 0, max = obj.length; i < max; i++) {
                    fecha = null;
                    fecha = obj[i].fecha;
                    fecha = fecha.substr(0, fecha.length - 8);
                    if (parseFloat(obj[i].vlrUnaGuia) > 0) {
                        vlrGuia = obj[i].vlrUnaGuia;
                    } else {
                        vlrGuia = obj[i].vlrVariasGuias;
                    }
                    servicios += '<tr><td>' + i + '</td><td>' + obj[i].guia + '</td><td>' + fecha + '</td><td>' + obj[i].MnpioOrigen + '</td><td>' + obj[i].MnpioDestino + '</td><td>' + obj[i].placa + '</td><td>' + obj[i].cedulapropietario + '</td><td>' + obj[i].NombrePropietario + '</td><td>' + obj[i].cedulaconductor + '</td><td>' + obj[i].NombreConductor + '</td><td>' + obj[i].idcliente + '</td><td>' + obj[i].cli_nombre + '</td><td>$' + vlrGuia + '</th></tr>';
                }
                servicios += '</table></div>';
                $("#mensajes").html(servicios);
            } else {
                $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Con las fechas seleccionadas no se encuentran servicios ingresados,por favor verifique</div>");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function segundaTraida(){...retorno desde el servidor");
        }
    });
    servicios = null;
}

function septimaTraida() {

    liberarVariables();

    if ($("#fechaInicial").val() === '') {
        alert("Por favor seleccione una fecha inicial");
        $("#fechaInicial").focus();
    } else {
        $.ajax({
            url: "../trafico/listarServicios.php",
            data: {'opcion': 9,
                'fechaInicial': $("#fechaInicial").val(),
                'fechaFinal': $("#fechaFinal").val()},
            type: "POST",
            beforeSend: function () {
                $("#mensajes").html("<div class='alert alert-dismissible alert-info'>Realizando operaciones solicitadas, un momento por favor...</div>");
            },
            success: function (data) {
                //console.log(data);
                //$("listaServiciosPor").val('0');
                var obj = JSON.parse(data);
                //console.log(obj);
                pintarTablaGeneral(obj, 2);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function primerTraida(opcion) {...retorno desde el servidor");
            }
        });
    }
    servicios = null;
}


function cuartaTraida() {

    liberarVariables();

    fechaInicial = $("#fechaInicial").val();
    fechaFinal = $("#fechaFinal").val();
    nitEmpresa = $("#nitempresa").val();
    $.ajax({
        url: "../trafico/listarServicios.php",
        data: {'opcion': 5,
            'fechaInicial': fechaInicial,
            'fechaFinal': fechaFinal,
            'nitEmpresa': nitEmpresa},
        type: "POST",
        beforeSend: function () {
            $("#mensajes").html("<div class='alert alert-dismissible alert-info'>Un momento por favor...</div>");
        },
        success: function (data) {
            var obj = JSON.parse(data);
            pintarTablaGeneral(obj, 1);
            porFacturar = 0;
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function function segundaTraida(){...retorno desde el servidor");
        }
    });
    servicios = null;
}

function pintarCelda(idservicio, placa, fecha, guia, cli_nombre, asesor, valorContratista, valorCliente, municipioOrigen, municipioDestino, ciclo, cedulaPropietario, nombrePropietario, cedulaConductor, nombreConductor, numeroFactura) {

    liberarVariables();

    var valores = 0, valoresDos = 0;

    if (jQuery.inArray(idservicio, serviciosVarios) < 0) {
        serviciosVarios[ciclo] = idservicio;
        valorContratista === valorContratista;
        valorCliente === valorCliente;
        $.ajax({
            async: false,
            url: "../trafico/listarServicios.php",
            data: {'opcion': 6,
                'idservicio': idservicio},
            type: "POST",
            success: function (data) {
                valoresExtras = JSON.parse(data);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function mostrarOtrosValores(idservicio){...retorno desde el servidor");
            }
        });
        if (typeof valoresExtras[0] === 'undefined') {
            auxiliar = 0;
        } else {
            auxiliar = valoresExtras[0].valor;
        }

        if (typeof valoresExtras[1] === 'undefined') {
            parqueadero = 0;
        } else {
            parqueadero = valoresExtras[1].valor;
        }

        if (typeof valoresExtras[2] === 'undefined') {
            otros = 0;
        } else {
            otros = valoresExtras[2].valor;
        }

        valores = parseFloat(auxiliar) + parseFloat(parqueadero) + parseFloat(otros);
        valoresDos = parseFloat(parqueadero) + parseFloat(otros);
        otrosValores += valores;
    } else {
        valorContratista = 0;
        valorCliente = 0;
        valores = 0;
    }

    diferencia = parseFloat(valorCliente) - (parseFloat(valorContratista) + parseFloat(valores));

    if (diferencia <= 0) {
        porGan = 0;
    } else {
        porGan = (diferencia * 100) / parseFloat(valorCliente);
    }

    if (isNaN(porGan)) {
        porGan = 0;
    }

    if (parseFloat(numeroFactura) === 0) {
        porFacturar += 1;
    }

    servicios += "<tr><td><a class='btn btn-success btn-xs' target='_blank' href='../modulos/administrarServicios.php?idservicio=" + idservicio + "'>" + idservicio + "</a></td><td>" + placa + "</td><td>" + cedulaPropietario + "</td><td>" + nombrePropietario + "</td><td>" + cedulaConductor + "</td><td>" + nombreConductor + "</td><td>" + fecha + "</td><td>" + guia + "</td><td>" + cli_nombre + "</td><td>" + asesor + "</td><td>" + municipioOrigen + "</td><td>" + municipioDestino + "</td><td>" + numeroFactura + "</td><td>$" + formatNumber.new(valorCliente) + "</td><td>$" + formatNumber.new(valorContratista) + "</td><td>$" + formatNumber.new(auxiliar) + "</td><td>$" + formatNumber.new(valoresDos) + "</td><td>$" + formatNumber.new(diferencia) + "</td><td>" + porGan.toFixed(1) + "</td></tr>";
    totVlrCont = totVlrCont + parseFloat(valorContratista);
    totVlrCli = totVlrCli + parseFloat(valorCliente);
    totPor = totPor + porGan;
    diferencia = null;
    porGan = null;
}

function consultarPorFactura() {

    liberarVariables();
    var serviciosFacturados = '', diferencia = 0, totalDiferencia = 0, guias = [], j = 0, otrosCostos = 0;

    var factura = $("#factura").val();
    if (factura === '0') {
        $("#mensajes2").html('<div class="alert alert-danger">Por favor digite un n&uacute;mero de factura v&aacute;lido</div>');
    } else {
        $("#mensajes").html('');
        $("#mensajes2").html('');
        $("#mensajesGenerales").html('');
        $.ajax({
            url: "../trafico/listarServicios.php",
            data: {'opcion': '11',
                'factura': factura},
            type: "POST",
            beforeSend: function () {
                $("#mensajes").html("<div class='alert alert-dismissible alert-info'>Realizando operaciones solicitadas, un momento por favor...</div>");
            },
            success: function (response) {
                $("listaServiciosPor").val('0');
                var obj = JSON.parse(response);
                pintarTablaGeneral(obj, 1);
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("error...$('#factura').blur(function () {....");
            }
        });
    }
}

function cambiarFacturaGrupo() {

    var facturaNueva = parseFloat($("#facturaNueva").val());
    var serviciosFacturados = $("#serviciosFacturados").val();
    var cantidadServicios = $("#cantidadServicios").val();

    if (isNaN(facturaNueva)) {
        $("#mensajes2").html('<div class="alert alert-dismissible alert-danger">Por favor digite un n&uacute;mero de factura v&aacute;lido. El d&iacute;gito cero, deja los servicios nuevamente para facturar</div>');
        $("#facturaNueva").focus();
    } else if (parseFloat(facturaNueva) === 0) {
        if (confirm("Confirmar dejar factura en cero")) {
            cambiarFactura(facturaNueva, serviciosFacturados, cantidadServicios);
        }
    } else {
        cambiarFactura(facturaNueva, serviciosFacturados, cantidadServicios);
    }
}

function cambiarFactura(facturaNueva, serviciosFacturados, cantidadServicios) {
    $("#mensajes2").html('');
    $.ajax({
        url: "../trafico/Prefactura.php",
        data: {'opcion': '3',
            'facturaNueva': facturaNueva,
            'servicios': serviciosFacturados},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj === parseFloat(cantidadServicios)) {
                confirm("Factura cambiada de manera correcta");
                location.reload();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("error...function cambiarFacturaGrupo() {....");
        }
    });
}

function calcularPorcentaje(valorFacturado, valorPagado, costos) {
    //porGan.toFixed(1)
    var total = 100 - (((valorPagado + costos) * 100) / valorFacturado);
    return total.toFixed(1);
}

function mostrarDivFactura() {
    return $("#mensajes").html("<div class='alert alert-dismissible alert-success'><table><tr><td>Ingrese el n&uacute;mero de factura a consultar</td><td><input type='number' name='factura' id='factura' value='0' class='form-control input-sm' /></td><td><input type='button' id='btnPorFactura' name='btnPorFactura' onclick='consultarPorFactura()' value='Consultar' /></td></tr></div>");
}

function mostrarTablaFechas(valor) {

    liberarVariables();

    var lsEmpresas = null;

    retornarFecha();

    switch (valor) {
        case '1':
            $("#mensajes").html("<div class='alert alert-dismissible alert-success' ><table>\n\
<tr><td>Fecha inicial</td><td><input type='date' name='fechaInicial' id='fechaInicial' value='" + retornarFechaInicio() + "' /></td>\n\
<td>Fecha final</td>\n\
<td><input type='date' name='fechaFinal' id='fechaFinal' value='" + retornarFechaDos() + "' /></td>\n\
<td><input type='button' name='btnConsultarServicios' id='btnConsultarServicios' value='Consultar servicios' onclick='consultarServicios(" + valor + ")' /></td>\n\
</tr>\n\
</table></div>");
            break;

        case '2':
            $.ajax({
                url: "../trafico/Clientes.php",
                data: {'caso': 4},
                type: "POST",
                success: function (data) {
                    //console.log(data);
                    var obj = JSON.parse(data);
                    lsEmpresas = "<div class='alert alert-dismissible alert-success' ><table>\n\
<tr><td>Fecha inicial</td><td><input type='date' name='fechaInicial' id='fechaInicial' /></td>\n\
<td>Fecha final</td><td><input type='date' name='fechaFinal' id='fechaFinal' value='" + fechaActual + "' /></td>\n\
<td>Empresas</td><td><select id='nitempresa' class='form-control' onchange='consultarServicios(4);'>\n\
<option value='0'>...</option>";
                    for (var i = 0, max = obj.length; i < max; i++) {
                        lsEmpresas += "<option value='" + obj[i].cli_documento + "'>" + obj[i].cli_nombre + "</option>";
                    }
                    lsEmpresas += "</select></td></tr></table></div>";
                    $("#mensajes").html(lsEmpresas);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error function listaEmpresas() {...retorno desde el servidor");
                }
            });
            break;

        case '3':
            $("#mensajes").html("<div class='alert alert-dismissible alert-success' ><table>\n\
<tr><td>Fecha inicial</td><td><input type='date' name='fechaInicial' id='fechaInicial' /></td>\n\
<td>Fecha final</td><td><input type='date' name='fechaFinal' id='fechaFinal' value='" + fechaActual + "' /></td><td><input type='button' name='btnConsultarServicios' id='btnConsultarServicios' value='Consultar servicios' onclick='consultarServicios(5)' /></td></tr>\n\
</table></div>");
            break;

        case'4':
            $("#mensajes").html("<div class='alert alert-dismissible alert-success' ><table>\n\
<tr><td>Fecha inicial</td><td><input type='date' name='fechaInicial' id='fechaInicial' /></td>\n\
<td>Fecha final</td><td><input type='date' name='fechaFinal' id='fechaFinal' value='" + fechaActual + "' /></td><td><input type='button' name='btnConsultarServicios' id='btnConsultarServicios' value='Consultar servicios' onclick='consultarServicios(6)' /></td></tr>\n\
</table></div>");
            break;
        case '5':
            $("#mensajes").html("<div class='alert alert-dismissible alert-success' ><table>\n\
<tr>\n\
<td>Fecha inicial</td><td><input type='date' name='fechaInicial' id='fechaInicial' /></td>\n\
<td>Fecha final</td><td><input type='date' name='fechaFinal' id='fechaFinal' value='" + fechaActual + "' /></td>\n\
<td><input type='button' name='btnConsultarServicios' id='btnConsultarServicios' value='Consultar servicios' onclick='consultarServicios(7)' /></td>\n\
</tr>\n\
</table></div>");
            break;
        case '6':
            //$("#DivConsultaPlacaServicios").show();
            break;
    }

}

function pintarTablaGeneral(obj, opcion) {

    liberarVariables();
    var fechaPago = null, fechaFactura = null, fechaTransferencia = null, otrosCostos = null;

    otrosValores = 0, valoresDos = 0, totalValoresDos = 0, diferencia = 0;

    if (opcion === 1) {

        if (obj !== false && obj.servicios.length > 0) {
            servicios = '<div>\n\
<table class="table table-sm" id="tablaExcel">\n\
<tr><th></th><th>Servicio</th><th>Placa</th><th>Tipo veh&iacute;culo</th><th>CC Prop.</th><th>Nomb. Prop.</th><th>Tel. Prop.</th><th>CC Cond.</th><th>Nomb. Cond.</th><th>Tel. Cond.</th>\n\
<th>Fecha servicio</th>\n\
<th>Fecha-hora creación guía</th>\n\
<th>Fecha factura</th>\n\
<th>Fecha pago</th>\n\
<th>Fecha transferencia</th>\n\
<th>Cuenta cobro</th>\n\
<th>Gu&iacute;a</th>\n\
<th>Cliente</th>\n\
<th>Asesor</th>\n\
<th>Origen</th>\n\
<th>Destino</th>\n\
<th>Factura</th>\n\
<th>Manifiesto</th>\n\
<th>Vlr Cliente</th>\n\
<th>Vlr Contratista</th>\n\
<th>Vlr. Aux.</th>\n\
<th>Otros Pag.</th>\n\
<th>Diferencia</th>\n\
<th>%</th></tr>';
            for (var i = 0, max = obj.servicios.length; i < max; i++) {
                if (obj.servicios[i].factura === '0') {
                    porFacturar += 1;
                }

                if (obj.servicios[i].otrosCostos === null) {
                    otrosCostos = 0;
                } else {
                    otrosCostos = obj.servicios[i].otrosCostos;
                }

                valoresDos = parseFloat(obj.servicios[i].parqueadero) + parseFloat(otrosCostos);
                otrosValores = valoresDos + parseFloat(obj.servicios[i].auxiliar);
                totalValoresDos += otrosValores;
                diferencia = (parseFloat(obj.servicios[i].valorCobrado) - parseFloat(obj.servicios[i].valorPagado) - otrosValores);
                porGan = 100 - (100 - (diferencia * 100) / parseFloat(obj.servicios[i].valorCobrado));
                if (porGan <= 0) {
                    porGan = 0;
                }
                if (obj.servicios[i].fechaPago === '1000-01-01') {
                    fechaPago = '';
                } else {
                    fechaPago = obj.servicios[i].fechaPago;
                }

                if (obj.servicios[i].fechaFactura === '1000-01-01') {
                    fechaFactura = '';
                } else {
                    fechaFactura = obj.servicios[i].fechaFactura;
                }

                if (obj.servicios[i].fechaTransferencia === null) {
                    fechaTransferencia = '';
                } else {
                    fechaTransferencia = obj.servicios[i].fechaTransferencia;
                }

                servicios += "<tr><td>" + (i + 1) + "</td><td><a class='btn btn-success btn-xs' target='_blank' href='../modulos/administrarServiciosDos.php?idserv=" + obj.servicios[i].idservicio + "'>" + obj.servicios[i].idservicio + "</a></td>\n\
<td>" + obj.servicios[i].placa + "</td><td>" + obj.servicios[i].tipovehiculo + "</td></td><td>" + obj.servicios[i].cedulaPropietario + "</td><td>" + obj.propietarios[i].nombrePropietario + "</td><td>" + obj.propietarios[i].telProp + "</td>\n\
<td>" + obj.servicios[i].cedulaConductor + "</td><td>" + obj.conductores[i].nombreConductor + "</td><td>" + obj.propietarios[i].telCond + "</td>\n\
<td>" + obj.servicios[i].fechaServicio + "</td>\n\
<td>" + obj.servicios[i].fechaCreacion + "</td>\n\
<td>" + fechaFactura + "</td>\n\
<td>" + fechaPago + "</td>\n\
<td>" + fechaTransferencia + "</td>\n\
<td>" + obj.servicios[i].numeroCuentaCobro + "</td>\n\
<td>" + obj.servicios[i].numeroGuia + "</td><td>" + obj.empresas[i].nombreEmpresa + "</td><td>" + obj.asesores[i].nombreAsesor + "</td>\n\
<td>" + obj.servicios[i].ciudadOrigen + "</td><td>" + obj.servicios[i].ciudadDestino + "</td><td>" + obj.servicios[i].factura + "</td>\n\
<td>" + obj.servicios[i].manifiesto + "</td>\n\
<td>$" + formatNumber.new(obj.servicios[i].valorCobrado) + "</td><td>$" + formatNumber.new(obj.servicios[i].valorPagado) + "</td>\n\
<td>$" + formatNumber.new(obj.servicios[i].auxiliar) + "</td><td>$" + formatNumber.new(valoresDos) + "</td><td>$" + formatNumber.new(diferencia) + "</td>\n\
<td>" + porGan.toFixed(1) + "</td></tr>";
                valoresDos = 0;
                totVlrCli += parseFloat(obj.servicios[i].valorCobrado);
                totVlrCont += parseFloat(obj.servicios[i].valorPagado);
                totPor += porGan;
            }

            totPor = totPor / obj.servicios.length;
            servicios += '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Cantidad servicios</td><td>' + obj.servicios.length + '</td>\n\
<td>Por facturar:</td><td>' + porFacturar + '</td><td>$' + formatNumber.new(totVlrCli) + '</td><td>$' + formatNumber.new(totVlrCont) + '</td><td></td><td>$' + formatNumber.new(totalValoresDos) + '</td><td>$' + formatNumber.new(totVlrCli - (totVlrCont + totalValoresDos)) + '</td><td>' + totPor.toFixed(1) + '</td>\n\
<td></td></tr>';
            servicios += '</table></div>';
            $("#mensajes").html(servicios);
        } else {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Con las fechas seleccionadas no se encuentran servicios ingresados,por favor verifique</div>");
        }
    }

    if (opcion === 2) {
        var totalFacturaCero = 0, fechaFactura = '';
        if (obj !== false && obj.length > 0) {
            servicios = '<div>\n\
<table class="table table-sm" id="tablaExcel">\n\
<tr><th>Fecha factura</th><th>Cliente</th><th>Factura</th><th>Vlr Cliente</th></tr>';

            for (var i = 0, max = obj.length; i < max; i++) {
                if (obj[i]["fechaFactura"] === '1000-01-01') {
                    fechaFactura = '-';
                } else {
                    fechaFactura = obj[i]["fechaFactura"];
                }
                servicios += '<tr><td>' + fechaFactura + '</td><td>' + obj[i]["nombreCliente"] + '</td><td>' + obj[i]["numeroFactura"] + '</td><td><strong>$ ' + formatNumber.new(obj[i]["valorCobrado"]) + '</strong></td></tr>';
                totalFacturaCero += parseFloat(obj[i]["valorCobrado"]);
            }
            servicios += '<tr><td></td><td></td><td><strong>Total</strong></td><td><strong>$ ' + formatNumber.new(totalFacturaCero) + '</strong></td></tr></div>';
            $("#mensajes").html(servicios);
        } else {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Con las fechas seleccionadas no se encuentran servicios ingresados,por favor verifique</div>");
        }
    }
}

function mostrarDatosAsesoresFechas() {
    var nombreEmpleado = null;
    var mostrarDatos = '';
    $.ajax({
        url: "../trafico/Empleados.php",
        data: {'opcion': 4},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            fechaActual = retornarFecha();
            mostrarDatos = "<div class='alert alert-dismissible alert-success' ><table>\n\
<tr><td>Fecha inicial</td><td><input type='date' name='fechaInicial' id='fechaInicial' /></td>\n\
<td>Fecha final</td><td><input type='date' name='fechaFinal' id='fechaFinal' value='" + fechaActual + "' /></td>\n\
<td>Asesores</td><td><select id='asesores' name='asesores' class='form-control' onchange='consultarAgendaAsesor(this);'>\n\
<option value='0'>...</option>";
            for (var i = 0, max = obj.length; i < max; i++) {
                nombreEmpleado = obj[i].emp_nombres + ' ' + obj[i].emp_apellidos;
                if (obj[i].emp_cedula !== '79725743' && obj[i].emp_cedula !== '17328718' && obj[i].emp_cedula !== '65749119') {
                    mostrarDatos += "<option value='" + obj[i].emp_cedula + "'>" + nombreEmpleado + "</option>";
                }
                nombreEmpleado = null;
            }
            mostrarDatos += "</select></td></tr></table></div>";
            $("#mensajes").html(mostrarDatos);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function mostrarDatosAsesoresFechas() {...retorno desde el servidor");
        }
    });

}

function retornarFecha() {
    date = new Date();
    dia = date.getDate();
    mes = date.getMonth() + 1;

    if (mes <= 9) {
        mes = "0" + mes;
    }

    if (dia <= 9) {
        dia = "0" + dia;
    }

    anio = date.getFullYear();
    fechaActual = anio + "-" + mes + "-" + dia;
    return fechaActual;
}



function liberarVariables() {
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
    valoresExtras = {};
    otrosValores = null;
    porFacturar = 0;
    guias = [];

}

function consultarAgendaAsesor(valor) {

    var cedula = $("#" + valor.id).val();
    var fechaInicial = $("#fechaInicial").val();
    var fechaFinal = $("#fechaFinal").val();
    var mostrarEventos = null, gestion = null, nombreAsesor = null;

    if (fechaInicial === '' || fechaInicial === null) {
        alert("Por favor seleccione una fecha inicial válida");
        $("#fechaInicial").focus();
        $("#" + valor.id).val('0');
    } else if (fechaFinal === '' || fechaFinal === null) {
        alert("Por favor seleccione una fecha final válida");
        $("#fechaFinal").focus();
        $("#" + valor.id).val('0');
    } else {
        nombreAsesor = $('select[name="asesores"] option:selected').text();
        traerDatosAgendaPorEmpleado(cedula, fechaInicial, fechaFinal, nombreAsesor);
    }
}

function mostrarListadoPlacas() {
    $("#mensajes").html('<div><table><td><tr>Por favor seleccione la placa a consultar de la siguiente lista: </tr><tr>' + $("#divPlacas").html() + '</tr></td></table></div>');
}

function mostrarCasillaCedula() {
    $("#mensajes").html('<div>Digite la c&eacute;dula a consultar:<input type="number" name="cedula" id="cedula" value="0" />&nbsp;<input type="button" id="cedula" name="cedula" onclick="consultarCedula()" value="Consultar"/></div>');
}

function consultarCedula() {
    var cedula = $("#cedula").val();
    if (cedula.length === 0) {
        $("#mensajes2").html("<div class='alert alert-dismissible alert-danger'>Por favor digite un n&uacute;mero de c&eacute;dula v&aacute;lido</div>");
        $("#cedula").val('0');
    } else if (cedula === '0') {
        $("#mensajes2").html("<div class='alert alert-dismissible alert-danger'>El n&uacute;mero de c&eacute;dula debe ser superior a cero</div>");
        $("#cedula").val('0');
    } else {
        $("#mensajes2").html("");
        $.ajax({
            url: "../trafico/Calificaciones.php",
            data: {'caso': 5, 'cedula': cedula},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.length > 0) {
                    var totCaliS = 0, totCaliNS = 0, listaServicios = "", texto = "";
                    for (var i = 0; i < obj.length; i++) {
                        if (obj[i].calificacion === '1') {
                            totCaliS += 1;
                        } else {
                            totCaliNS += 1;
                            listaServicios = listaServicios + obj[i].idservicio + ",";
                        }
                    }
                    if (totCaliNS >= 1) {
                        texto = "Lista de servicios no satisfactorios:" + listaServicios;
                    }

                    $("#mensajes").html("<div class='alert alert-dismissible alert-success'>C&eacute;dula: " + cedula + "<br>Total servicios satisfactorios:" + totCaliS + "<br> Total servicios no satisfactorios:" + totCaliNS + "<br>" + texto + "</div>");
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function function consultarCedula(){...retorno desde el servidor");
            }
        });
    }
}

function retornarFechaDos() {
    var date = new Date();
    var dia = date.getDate();
    var mes = date.getMonth() + 1;

    if (mes <= 9) {
        mes = "0" + mes;
    }

    if (dia <= 9) {
        dia = "0" + dia;
    }

    var anio = date.getFullYear();
    return anio + "-" + mes + "-" + dia;
}

function retornarFechaInicio() {
    var date = new Date();
    var dia = date.getDate();
    var mes = date.getMonth() + 1;

    if (mes <= 9) {
        mes = "0" + mes;
    }

    if (dia <= 9) {
        dia = "0" + dia;
    }

    var anio = date.getFullYear();
    return anio + "-" + mes + "-" + "01";
}
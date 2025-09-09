//vlrDec valorDeclarado
//varEmp valor empresa
var ingreso = "", boton_ = null, guia = null, factura = null, tabla = null, serv = null, motivo = null, colspan1 = 3,
        colspan2 = 6, valorCliente = 0, valorContratista = 0, ganancia = 0, diferencia = 0, anticipos = 0, saldo = 0,
        asesorActual = '', idanticipo = null, totalAnticipos = null, vlrDec = 0, sF = 0, varEmp = null, idservicio = null,
        Guias = new Array(), ValoresContratista = new Array(), ValoresEmpresa = new Array();

jQuery(function ($) {
    $("#vlrCliente").mask("999.999.999.999");
    $("#vlrContratista").mask("999.999.999.999");
});

$(document).ready(function () {

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $('#empleado').change(function () {
        if ($('#empleado').val() === '0') {
            $("#mensajes").html('<div class="alert alert-danger">Para consultar por favor seleccione un empleado de la lista</div>');
            $("#mensajesGenerales").html('');
            $("#empleado").focus();
        }
    });

    $('#idservicio').blur(function () {

        idservicio = $("#idservicio").val();

        $("#guia").val('0');
        $("#idanticipo").val('0');

        if ($('#idservicio').val() === '0') {
            $("#mensajes").html('<div class="alert alert-danger">Para consultar por favor digite un n&uacute;mero de servicio</div>');
            $("#mensajesGenerales").html('');
        } else {
            $("#mensajes").html('');
            $("#mensajesGenerales").html('');
            $.ajax({
                url: "../trafico/mostrarDatosServicio.php",
                data: {'idservicio': idservicio},
                type: "POST",
                success: function (data) {
                    //console.log(data);
                    var obj = JSON.parse(data);
                    if (obj !== false) {
                        if (obj === idservicio) {
                            if (confirm("Sera redirigido al nuevo módulo de administración")) {
                                window.location.href = "../modulos/administrarServiciosDos.php?idservicio=" + idservicio;
                            }
                        } else if (obj.decision === 0) {
                            $("#mensajes").html('');
                            $("#mensajesGenerales").html('<div class="alert alert-danger">Con el n&uacute;mero de servicio: <strong>' + $("#idservicio").val() + '</strong> no se registran datos <br />Por favor verifique</div>');
                            $("#idservicio").val(idservicio);
                        } else if (obj.decision === 1) {
                            $("#mensajes").html(botonAnularServicio('"' + idservicio + '"'));
                            $("#mensajesGenerales").html('<div class="alert alert-danger">El servicio <strong>' + $("#idservicio").val() + '</strong> se intento crear. No se termino.</div>');
                            $("#idservicio").val(idservicio);
                        } else if (obj.decision === 2) {
                            $("#mensajes").html(botonAnularServicio('"' + idservicio + '"'));
                            $("#mensajesGenerales").html('<div class="alert alert-danger">El servicio <strong>' + $("#idservicio").val() + '</strong> lo intento crear <strong>' + obj.nombresEmpleado + '</strong> <br>No se termino</div>');
                            $("#idservicio").val(idservicio);
                        } else if (obj.decision === 3) {
                            $("#mensajes").html(botonAnularServicio('"' + idservicio + '"'));
                            $("#mensajesGenerales").html('<div class="alert alert-danger">El servicio <strong>' + $("#idservicio").val() + '</strong> se intento crear. No tiene conductor asociado.<br>No se termino</div>');
                            $("#idservicio").val(idservicio);
                        } else if (obj.decision === 4) {
                            //console.log(data);
                            $("#mensajes").html(botonAnularServicio('"' + idservicio + '"'));
                            $("#mensajesGenerales").html('<div class="alert alert-danger">El servicio <strong>' + $("#idservicio").val() + '</strong> se intentó crear. No tiene destino asociado. Posible falla al crear destino <br>No se termino</div>');
                            $("#idservicio").val(idservicio);
                        } else if (obj.decision === 5) {
                            $("#mensajes").html(botonAnularServicio('"' + idservicio + '"'));
                            $("#mensajesGenerales").html('<div class="alert alert-danger">El servicio <strong>' + $("#idservicio").val() + '</strong> se intentó crear. Falla en la creación de la posible factura. <br>Error de sistema</div>');
                            $("#idservicio").val(idservicio);
                        } else {
                            mostrarDatosServicio(obj);
                        }
                    } else {
                        alert("Ha fallado la consulta de los datos del servicio\n$('#idservicio').blur(function () {...respuesta desde servidor...}else{...\nLa página será recargada, por favor presione F5 e intente nuevamente.\nGracias!");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("$('#idservicio').blur(function () {...");
                }
            });
        }
    });

    $("#guia").blur(function () {

        $("#idservicio").val('0');
        $("#idanticipo").val('0');

        var bandera = 0, fecha = null;
        $("#mensajes").html('');
        guia = $("#guia").val();
        if ($("#guia").val() === '0' || $("#guia").val() === '') {
            $("#mensajes").html("<div class='alert alert-danger'>Para realizar la consulta por n&uacute;mero de gu&iacute;a, debe ser mayor a cero (0) </div>");
            $("#mensajesGenerales").html('');
        } else {
            $("#mensajes").html("");
            $("#mensajesGenerales").html('');
            $.ajax({
                url: "../trafico/retornarServicios.php",
                data: {'guia': $("#guia").val(),
                    'condicion': '2'},
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj !== false) {
                        if (obj === 0) {
                            $("#mensajes").html("<div class='alert alert-danger'>No se encuentra servicio con el n&uacute;muero de gu&iacute;a: " + $("#guia").val() + "</div>");
                            $("#mensajesGenerales").html('');
                        } else if (obj[0].idservicio > 0) {
                            var fecha = obj[0].fecha;
                            fecha = fecha.slice(0, 10);
                            var cadena = "<table class='table table-hover'>\n\
<tr><th>Servicio</th><th>Fecha</th><th>Gu&iacute;a</th></tr>";
                            for (var i = 0, max = obj.length; i < max; i++) {
                                cadena += "<tr><td><a class='btn btn-success btn-xs' href='../modulos/mostrarServicio.php?idservicio=" + obj[i].idservicio + "' >" + obj[i].idservicio + "</a></td><td>" + fecha + "</td><td>" + obj[i].guia + "</td></tr>";
                            }
                            cadena += "</table>";
                            $("#mensajes").html("<div class='alert alert-dismissible alert-success altura2'>" + cadena + "</div>");
                            $("#mensajesGenerales").html('');
                        } else if(obj[0].numeroGuia===$("#guia").val()){
                            if (confirm("Sera redirigido al nuevo módulo de administración")) {
                                window.location.href = "../modulos/administrarServiciosDos.php?guia=" + $("#guia").val();
                            }                            
                        }else{
                            $("#mensajes").html("<div class='alert alert-danger'>No se encuentra servicio con el n&uacute;muero de gu&iacute;a: " + $("#guia").val() + "</div>");
                            $("#mensajesGenerales").html('');
                        }
                    } else {
                        alert("Ha ocurrido un error en AJAX en el evento $('#guia').focusout(function ()... return from server :( ");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX en el evento $('#guia').focusout(function ()...");
                }
            });
        }
    });

    $("#idanticipo").blur(function () {

        $("#guia").val('0');
        $("#idservicio").val('0');

        if ($('#idanticipo').val() === '0') {
            $("#mensajes").html('<div class="alert alert-danger">Para consultar por favor n&uacute;mero de anticipo, por favor digite un n&uacute;mero de v&aacute;lido</div>');
            $("#mensajesGenerales").html('');
        } else {
            $.ajax({
                url: "../trafico/retornarDatosAnticipo.php",
                data: {'numeroAnticipo': $("#idanticipo").val()},
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj !== false) {
                        if (Object.keys(obj).length === 0) {
                            $("#mensajes").html('<div class="alert alert-danger">Con el n&uacute;mero de anticipo: <strong>' + $("#idanticipo").val() + '</strong> no se registran datos <br />Por favor verifique</div>');
                            $("#mensajesGenerales").html('');
                            $("#idanticipo").val('0');
                        } else {
                            mostrarDatosAnticipo(obj);
                        }
                    } else {
//                        alert("Ha fallado la consulta de los datos del servicio\n$('#idanticipo').blur(function (){...respuesta desde servidor...}else{...\nLa página será recargada, por favor presione F5 e intente nuevamente.\nGracias!");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("$('#idservicio').blur(function () {...");
                }
            });
        }
    });
});

function mostrarDatosAnticipo(datos) {
    /*
     * tA=Total anticipos
     * vS=Valor servicio
     * s=Saldo
     */
    var totalAnticipo = 0, totalServicio = 0, tA = '', vS = '', s = '';
    idanticipo = $("#idanticipo").val();
    //console.log(datos);
    serv = "<table class='table table-hover'>";
    serv += "<tr><td>N&uacute;mero servicio</td><td><input type='button' class='btn btn-success btn-xs' value='" + datos[0].idservicio + "'/></td><td>N&uacute;mero anticipo</td><td>" + $("#idanticipo").val() + "</td><td></td><td></td></tr>";
    serv += "<tr><td colspan='6' style='text-align: center;' ><strong>DATOS PERSONA BENEFICIARIA DEL PAGO</strong></td></tr>";
    serv += "<tr><td>Nombre propietario</td><td>" + datos[0].nombresPropietario + "</td><td>Placa</td><td>" + datos[0].placa + "</td><td></td><td></td><td></td></tr>";
    serv += "<tr><td colspan='6' style='text-align: center;' ><strong>DETALLE ANTICIPOS</strong></td></tr>";
    serv += "<tr><td>Nro. Anticipo</td><td>Gu&iacute;a</td><td>Fecha</td><td>Empresa</td><td>V/R Anticipo</td><td>V/r Servicio</td></tr>";
    for (var i = 0; i <= datos.length - 1; i++) {
        serv += "<tr><td>" + datos[i].val_numeroAnticipo + "</td><td>" + datos[i].val_numeroGuia + "</td><td>" + datos[i].val_fechaAnticipo + "</td><td>" + datos[i].cli_nombre + "</td><td>$ " + $.number(datos[i].val_valorAdelanto) + "</td><td>$ " + $.number(datos[i].valorservicio) + "</td></tr>";
        totalAnticipo = totalAnticipo + parseInt(datos[i].val_valorAdelanto);
        totalServicio = totalServicio + parseInt(datos[i].valorservicio);
    }

    if (datos[0].prueba_entrega === 'P') {
        tA = 'Total anticipo pagado';
        vS = 'Valor servicio pagado';
        s = 'Saldo pagado';
    } else {
        tA = 'Total anticipo entregado';
        vS = 'Valor servicio por pagar';
        s = 'Saldo pagar';
    }

    serv += "<tr><td></td><td></td><td>" + tA + "</td><td>$ " + $.number(totalAnticipo) + "</td><td></td><td></td><td></td></tr>";
    serv += "<tr><td></td><td></td><td>" + s + "</td><td>$ " + $.number(parseInt(totalServicio - totalAnticipo)) + "</td><td></td><td></td></tr>";
    serv += "<tr id='trListaPlacas' name='trListaPlacas'></tr>";

    serv += "<tr><td>El anticipo se puede anular por uno de los siguientes motivos</td><td>\n\
<select id='motivoAnularAnticipo' name='motivoAnularAnticipo' class='form-control' >\n\
<option value='0'>...</option>\n\
<option value='Error de digitación'>Error de digitación</option>\n\
<option value='Cambio de valor'>Cambio de valor</option>\n\
<option value='Error de sistema'>Error de sistema</option>\n\
<option value='Cambio vehiculo'>Cambio vehiculo</option>\n\
<option value='Cliente cancela servicio'>Cliente cancela servicio</option>\n\
</select>";
    serv += "</td><td><button name='anularAnticipo' id='anularAnticipo' type='button' class='btn btn-success btn-ls botonPropio' value='SALIR' onmouseleave='colorSale(this);' onmouseenter='colorEntra(this);' onclick='anAnticipo(" + $("#idanticipo").val() + "," + datos[0].idservicio + ");' >ANULAR ANTICIPO <img src='../imagenes/delete_16.png'></button></td>";
    serv += "<td><button name='sobreAnticipo' id='sobreAnticipo' type='button' class='btn btn-success btn-ls botonPropio' value='CREAR SOBREANTICIPO' onmouseleave='colorSale(this);' onmouseenter='colorEntra(this);' onclick='redireccionar(1);' >CREAR NUEVO ANTICIPO <img src='../imagenes/plus_16.png'></button></td><td></td><td></td><td></td></tr>";
    serv += "</table>";
    serv += "<input type='hidden' id='documentoPropietario' name='documentoPropietario' value='" + datos[0].cond_identificacion + "'/>";

    $("#mensajes").html(serv);
    $("#mensajesGenerales").html('');
}



function anAnticipo(idanticipo, idservicio) {

    switch ($("#motivoAnularAnticipo").val()) {
        case "0":
            alert("Por favor seleccione un motivo de cancelación del anticipo");
            break;
        case "Cambio vehiculo":
            if (confirm("Esta acción anulara el anticipo: " + idanticipo + " y \nCambiara el vehiculo asignado al servicio: " + idservicio + "")) {
                var documentoPropietario = $("#documentoPropietario").val();
                $.ajax({
                    url: "../trafico/cancelarAnticipo.php",
                    data: {'numeroAnticipo': idanticipo,
                        'idservicio': idservicio,
                        'motivo': 'CAMBIO DE VEHICULO'},
                    type: "POST",
                    success: function (data) {
                        var obj = JSON.parse(data);
                        if (obj !== false) {
                            window.setTimeout($("#mensajes").html('<div class="alert alert-dismissible alert-success">Anticipo: <strong>' + idanticipo + '</strong> ha sido anulado con &eacute;xito</br>A continuaci&oacute;n por favor seleccione el nuevo veh&iacute;culo para el servicio anticipo' + idservicio + '</div>'), 120000);
                            window.location.href = "../modulos/cambioVehiculo.php?ant=" + idanticipo + "&idserv=" + idservicio;
                        } else {
                            alert("Ha fallado la anulación del anticipo, por favor presione F5 e intentelo nuevamente");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Error: function anAnticipo(idanticipo, idservicio) {");
                    }
                });
            }

            break;
        case "Cliente cancela servicio":
            alert("Ha seleccionado como motivo: Cliente cancela servicio,\nEl número de servicio asociado a este \n\
anticipo se encuentra en la casilla: Número servicio,\nPor favor presione la tecla Tab para comenzar el proceso de \n\
anulación del servicio");
            $("#idservicio").val(idservicio);
            $("#idservicio").focus();
            $("#idanticipo").val('0');
            break;
        default:
            if (confirm("Esta acción anulara toda información de este anticipo")) {
                var documentoPropietario = $("#documentoPropietario").val();
                $.ajax({
                    url: "../trafico/cancelarAnticipo.php",
                    data: {'numeroAnticipo': idanticipo,
                        'idservicio': idservicio,
                        'motivo': 'ANULAR ANTICIPO'},
                    type: "POST",
                    success: function (data) {
                        var obj = JSON.parse(data);
                        if (obj !== false) {
                            window.setTimeout($("#mensajes").html('<div class="alert alert-dismissible alert-success">Anticipo: <strong>' + idanticipo + '</strong> ha sido anulado con &eacute;xito</br>Será redireccionado al m&oacute;dulo de anticipos</div>'), 120000);
                            window.location.href = "../modulos/anticipos.php?pj=" + documentoPropietario;
                        } else {
                            alert("Ha fallado la anulación del anticipo, por favor presione F5 e intentelo nuevamente");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Error: function anAnticipo(idanticipo, idservicio) {");
                    }
                });
            }
            break;
    }
}

function mostrarDatosServicio(datos) {
    mostrarServicio(datos.servicios, datos.datosAsesor, datos.propietario, datos.conductor, datos.serviciovariasguias, datos.valoresanticipos, datos.posiblesfacturas, datos.ciudadorigen, datos.creaServicio, datos.motivo, datos.asesores, datos.valordeclarado, datos.valores, datos.comentarios, datos.seguimiento);
}

function mostrarServicio(servicio, datosAsesor, propietario, conductor, serviciovariasguias, valoresanticipos, posiblesfacturas, ciudadorigen, creaServicio, motivo, asesores, valordeclarado, valores, comentarios, seguimiento) {

    var incluirEmpleado = null;
    //fechaCS=fecha Creación del Servicio
    var fechaCS = '';
    //lista para mostrar de empleados con el objetivo de poder cambiar el asesor del servicio
    var lpm = '';
    //persona que Crea el Servicio
    var pCS = '';
    //servicio Facturado
    sF = posiblesfacturas[0].factura;
    var tamanio = valordeclarado.length;
    //comentarios
    var coment = comentarios.length;
    //imagen para mostrar del seguimiento del gps
    var imagenGPS = null;
    //si es 0 no esta en ruta,si es 1 esta en ruta
    var enRuta = null;

    if (creaServicio.length === 0) {
        fechaCS = 'No registra';
        pCS = 'No registra';
    } else {
        fechaCS = creaServicio[0].fechahora;
        pCS = creaServicio[0].nombresEmpleado;
    }

    lpm = '<select id="cambiarAsesor" name="cambiarAsesor" class="form-control" onchange="cambiarEmpleado(' + servicio[0]["idservicio"] + ');">';

    for (var i = 0, max = asesores.length; i < max; i++) {
        if (datosAsesor[0].cedulaAsesor === asesores[i].emp_cedula) {
            lpm += "<option value='" + asesores[i].emp_cedula + "' selected >" + asesores[i].nombreEmpelado + "</option>";
            asesorActual = asesores[i].nombreEmpelado;
        } else {
            lpm += "<option value='" + asesores[i].emp_cedula + "' >" + asesores[i].nombreEmpelado + "</option>";
            incluirEmpleado = 1;
        }
    }
    if (incluirEmpleado === 1) {
        lpm += "<option value='" + datosAsesor[0].cedulaAsesor + "' selected >" + datosAsesor[0].nombresAsesor + "</option>";
        asesorActual = datosAsesor[0].nombresAsesor;
    }
    lpm += "</select>";

    serv = "";
    if (motivo.length > 0) {
        serv = "<div class='alert alert-dismissible alert-warning center-block' id='mensajeCancelar'>";
        serv += "<table class='table table-striped' >";
        serv += "<tr><th></th><th></th><th>Empleado</th><th>Fecha</th><th>Motivo cancelacion</th><th></th></tr>";
        for (var i = 0, max = motivo.length; i < max; i++) {
            serv += "<tr><td></td><td></td><td>" + motivo[i]["nombreEmpleado"] + "</td><td>" + motivo[i]["fecha"] + "</td><td>" + motivo[i]["motivo"] + "</td><td><input type='button' value='Borrar mensaje' id='" + motivo[i]["id"] + "' onclick='borrarMensaje(this);' class='btn btn-warning btn-sm' /></td></tr>";
        }
        serv += "</table>";
        serv += "</div>";
    }

    idservicio = servicio[0]["idservicio"];

    serv += "<table class='table table-striped'>";
    serv += "<tr class='table-success' ><td scope='row' ><strong>NÚMERO</strong></td><td><strong>SERVICIO</strong></td><td><input type='button' class='btn btn-success btn-xs' value='" + servicio[0]["idservicio"] + "' /></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
    serv += "<tr><td><strong>Placa</strong></td><td>" + servicio[0]["placa"] + "</td><td><strong>C&eacute;dula propietario</strong></td><td>" + propietario[0]["cond_identificacion"] + "</td><td><strong>Nombre propietario</strong></td><td>" + propietario[0]["cond_nombres"] + " " + propietario[0]["cond_apellidos"] + "</td><td></td><td></td><td></td><td></td></tr>";
    serv += "<tr><td><strong>Fecha servicio</strong></td><td>" + servicio[0][2].substr(0, 10) + "</td><td><strong>C&eacute;dula conductor</strong></td><td>" + conductor[0]["cond_identificacion"] + "</td><td><strong>Nombre conductor</strong></td><td>" + conductor[0]["cond_nombres"] + " " + conductor[0]["cond_apellidos"] + "</td><td></td><td></td><td></td><td></td></tr>";
    serv += "<tr><td><strong>Direcci&oacute;n origen</strong></td><td>" + servicio[0].direccionorigen + "</td><td><strong>Tel&eacute;fono origen</strong></td><td>" + servicio[0]["telefonoorigen"] + "</td><td><strong>Ciudad origen</strong></td><td>" + ciudadorigen + "</td><td></td><td></td><td></td><td></td></tr>";
    serv += "<tr><td><strong>Cliente</strong></td><td>" + posiblesfacturas[0].empresa + "</td><td><strong>Asesor</strong></td><td>" + lpm + "</td><td><strong>Crea servicio<strong></td><td>" + pCS + "</td><td></td><td></td><td></td><td></td></tr>";
    serv += "<tr><td><strong>Fecha creaci&oacute;n</strong></td><td>" + fechaCS + "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
    serv += "<tr><td><strong>RELACIÓN</strong></td><td><strong>GUIAS</strong></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
    for (var i = 0; i <= serviciovariasguias.length - 1; i++) {
        if (serviciovariasguias[i].costoTotal === '0') {
            varEmp = 0;
            if (i === 0) {
                colspan2 = 6;
                serv += "<tr><td><strong>Guia</strong></td><td><strong>Planilla</strong></td><td><strong>Otro</strong></td><td><strong>Destino</strong></td><td><strong>Direcci&oacute;n destino</strong></td><td><strong>Tel&eacute;fono destino</strong></td><td></td><td><strong>Vlr Declarado</strong></td><td></td><td></td><td></td></tr>";
                $("#mensajesGenerales").html("");
            }
            valorDeclarado(serviciovariasguias, valordeclarado, i, 0, tamanio, serviciovariasguias[i].guia);
            serv += "<tr><td><input type='number' id='guia-" + serviciovariasguias[i].guia + "' value='" + serviciovariasguias[i].guia + "' onblur='cambiarNumeroGuia(this)' /></td><td>" + serviciovariasguias[i].planilla + "</td><td>" + serviciovariasguias[i].otro + "</td><td>" + serviciovariasguias[i].mun_nombre + "</td><td>" + serviciovariasguias[i].direccion + "</td><td>" + serviciovariasguias[i].telefono + "</td><td></td><td>" + vlrDec + "</td><td></td><td></td></tr>";
        } else {
            varEmp = 1;
            if (i === 0) {
                colspan2 = 6;
                serv += "<tr><td><strong>Empresa</strong></td><td><strong>Gu&iacute;a</strong></td><td><strong>Planilla</strong></td><td><strong>Otro</strong></td><td><strong>Dir. Destino</strong></td><td><strong>Tel&eacute;fono</strong></td><td><strong>Ciudad</strong></td><td><strong>Vlr. Contratista</strong></td><td><strong>Vlr. Facturar</strong></td><td><strong>Vlr. Declarado</strong></td></tr>";
                $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-success'>Este servicio es para varias empresas</div>");
            }
            valorDeclarado(serviciovariasguias, valordeclarado, i, 1, tamanio, serviciovariasguias[i].guia);

            Guias[i] = serviciovariasguias[i].guia;
            ValoresContratista[i] = serviciovariasguias[i].valorContratista;
            ValoresEmpresa[i] = serviciovariasguias[i].valorFacturar;

            serv += "<tr><td>" + serviciovariasguias[i].cli_nombre + "</td><td>" + serviciovariasguias[i].guia + "</td><td>" + serviciovariasguias[i].planilla + "</td><td>" + serviciovariasguias[i].auxiliar + "</td><td>" + serviciovariasguias[i].direccion + "</td><td>" + serviciovariasguias[i].telefono + "</td><td>" + serviciovariasguias[i].mun_nombre + "</td><td><input type='text' value='" + serviciovariasguias[i].valorContratista + "' size='7' maxlength='7' id='guia-" + serviciovariasguias[i].guia + "' onfocusout='cambiarVlrContratista(this);'/></td><td><input type='text' value='" + serviciovariasguias[i].valorFacturar + "' size='7' maxlength='7' id='vlrg-" + serviciovariasguias[i].guia + "' onfocusout='cambiarVlrAFacturar(this);'  /></td><td>$" + $.number(vlrDec) + "</td></tr>";

            valorCliente = valorCliente + parseInt(serviciovariasguias[i].valorFacturar);
            valorContratista = valorContratista + parseInt(serviciovariasguias[i].valorContratista);
        }
    }
    serv += "<tr class='table-success' ><td scope='row' ><strong>COSTOS</strong></td><td><strong>SERVICIO</strong></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

    if (serviciovariasguias[0].costoTotal === '0') {
        diferencia = 0;
        ganancia = parseInt(servicio[0]["valorcliente"]) - (parseInt(servicio[0]["valorapagar"]) + parseInt(valores[0]["valor"]) + parseInt(valores[1]["valor"]) + parseInt(valores[2]["valor"]));
        mostrarValoresServicio(servicio[0]["valorcliente"], servicio[0]["valorapagar"], ganancia);
        advertenciaGanancia(ganancia);
    } else {
        ganacia = 0;
        diferencia = 1;
        console.log(valores);
        ganancia = parseInt(valorCliente) - (valorContratista + parseInt(valores[0]["valor"]) + parseInt(valores[1]["valor"]) + parseInt(valores[2]["valor"]));
        mostrarValoresServicio(valorCliente, valorContratista, ganancia);
        advertenciaGanancia(ganancia);
    }

    mostrarValoresVarios(valores);

    mostrarPagoAnticipoServicio(valoresanticipos, servicio[0]["valorapagar"], varEmp);

    if (varEmp === 1) {
        serv += "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td style='text-align: right;'><strong>Saldo pagado</strong></td><td>$ " + $.number(saldo) + "</td><td></td><td></td></tr>";
    }

    saldo = 0;

    serv += "<tr class='table-success' ><td scope='row' ><strong>REGISTRO</strong></td><td><strong>FACTURAS</strong></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

    if (posiblesfacturas[0].factura === '0') {
        serv += "<tr><td colspan='10' ><strong>Servicio no facturado</strong></td></tr>";
    } else {
        serv += "<tr><td colspan='4' >Gu&iacute;a</td><td colspan='3'><strong>Factura</strong></td><td colspan='3' ><strong>Fecha</strong></td></tr>";
        for (var i = 0, max = posiblesfacturas.length; i < max; i++) {
            serv += "<tr><td colspan='4' >" + posiblesfacturas[i].numeroguia + "</td><td colspan='3'><input type='number' id='factura' name='factura' onfocusout='cambiarFactura(this," + servicio[0]["idservicio"] + ");' value='" + posiblesfacturas[i].factura + "' class='form-control input-sm' /></td><td colspan='3' >" + posiblesfacturas[i].fecha + "</td></tr>";

        }
    }

    if (coment === 0) {
        comentarios = "Servicio sin comentarios...";
    } else {
        comentarios = comentarios[0].comentario;
    }

    if (seguimiento.length === 0) {
        serv += "<tr><td colspan='10' ><strong>No se registra seguimiento para este servicio</strong></td></tr>";
    } else {
        serv += "<tr><td colspan='10'><strong>SEGUIMIENTO</strong></td></tr>\
<tr><td>Gu&iacute;a</td><td>Fecha/hora</td><td>Ubicaci&oacute;n</td><td>Observaci&oacute;n</td><td>Imagen GPS</td><td>Plan de ruta</td><td>Colaborador</td><td>Estado seguimiento</td><td></td><td></td></tr>";
        for (var i = 0; i < seguimiento.length; i++) {

            if (seguimiento[i].imagen === '0') {
                imagenGPS = 'Sin imagen GPS';
            } else {
                imagenGPS = '<img src="' + seguimiento[i].imagen + '" alt="" width="300" height="150"  />';
            }

            if (seguimiento[i].planderuta === '0') {
                enRuta = 'Si';
            } else {
                enRuta = 'No';
            }
            serv += "<tr><td>" + seguimiento[i].guia + "</td><td>" + seguimiento[i].fechaHora + "</td><td>" + seguimiento[i].ubicacion + "</td><td>" + seguimiento[i].observacion + "</td><td>" + imagenGPS + "</td><td>" + enRuta + "</td><td>" + seguimiento[i].empleado + "</td><td>" + seguimiento[i].estadoseguimiento + "</td><td></td><td></td></tr>";
        }
    }

    serv += "<tr><td colspan='3'><textarea name='comentario' id='comentario' rows='3' cols='50' onblur='modificarComentario();' >" + comentarios + "</textarea></td>\n\
<td><strong>Motivo anulación</strong></td><td>\n\
<select id='motivoAnularServicio' name='motivoAnularServicio' class='form-control' >\n\
<option value='0'>...</option>\n\
<option value='Error de digitación'>Error de digitación</option>\n\
<option value='Error de sistema'>Error de sistema</option>\n\
<option value='Cambio vehiculo'>Cambio vehiculo</option>\n\
<option value='Cliente cancela servicio'>Cliente cancela servicio</option>\n\
</select>\n\
</td>\n\
<td><button name='anularServicio' id='anularServicio' type='button' class='btn btn-success btn-ls botonPropio' value='SALIR' onmouseleave='colorSale(this);' onmouseenter='colorEntra(this);' onclick='canServicio(" + servicio[0]["idservicio"] + ");' >ANULAR SERVICIO <img src='../imagenes/delete_16.png'></button></td></tr>";
    serv += "</table>";
    $("#mensajes").html(serv);

}

//vE=varEmp
function mostrarPagoAnticipoServicio(valoresanticipos, valorConductor, vE) {

    var varios = null;
    totalAnticipos = 0;
    var vlrAnticipo = 0;
    var acumulado = 0;
    var botonHabilitar = "<input type='button' value='Habilitar cuenta de cobro' class='btn btn-success btn-sm' onclick='habilitarCtaCobro();'/>";

    serv += "<tr class='table-success' ><td scope='row' ><strong>ANTICIPOS</strong></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";

    for (var i = 0, max = valoresanticipos.length; i < max; i++) {
        //console.log(valoresanticipos[i].val_valorAdelanto+'---');
        vlrAnticipo = parseInt(vlrAnticipo) + parseInt(valoresanticipos[i].val_valorAdelanto);
        if (valoresanticipos[i].prueba_entrega === 'P' && valoresanticipos[i].val_valorAdelanto === '0') {
            //console.log("fue pagado y no tiene anticipo");
            serv += "<tr><td><strong>Servicio pagado el</strong></td><td>" + valoresanticipos[i].val_fechaAnticipo + "</td><td><strong>Servicio sin anticipo</strong></td><td></td><td></td><td style='text-align: right;'></td><td></td><td></td><td></td></tr>";
            serv += "<tr><td></td><td></td><td>" + botonHabilitar + "</td><td></td><td></td><td style='text-align: right;'></td><td></td><td></td><td></td></tr>";

        } else if (valoresanticipos[i].prueba_entrega === 'P' && valoresanticipos[i].val_valorAdelanto !== '0') {
            //console.log("fue pagado y tiene un anticipo superior a cero");
            serv += "<tr><td></td><td><strong>Servicio pagado el</strong></td><td>" + valoresanticipos[i].val_fechaAnticipo + "</td><td><strong>N&uacute;mero anticipo</strong></td><td>" + valoresanticipos[i].val_numeroAnticipo + "</td><td></td><td style='text-align: right;'><strong>Valor anticipo pagado<strong></td><td>$ " + $.number(valoresanticipos[i].val_valorAdelanto) + "</td><td></td><td></td></tr>";
            if (varEmp === 0) {
                saldo = 0;
                saldo = parseInt(valorConductor) - parseInt(valoresanticipos[i].val_valorAdelanto);
                serv += "<tr><td></td><td></td><td>" + botonHabilitar + "</td><td></td><td></td><td style='text-align: right;'><strong>Saldo pagado</strong></td><td>$ " + $.number(saldo) + "</td><td></td><td></td></tr>";
            } else {
                acumulado = parseInt(valoresanticipos[i].val_valorAdelanto) - parseInt(valorConductor);
                saldo = saldo + acumulado;
            }
        } else if (valoresanticipos[i].prueba_entrega === 'N' && valoresanticipos[i].val_valorAdelanto === '0') {
            //console.log("no ha sido pagado y no tiene anticipo");

            serv += "<tr><td></td><td><strong>Pendiente por pagar a conductor</strong></td><td><strong>Servicio sin anticipo</strong></td><td></td><td></td><td style='text-align: right;'><strong>Valor anticipo</strong></td><td>$ " + $.number(valoresanticipos[i].val_valorAdelanto) + "</td><td></td><td></td></tr>";
            saldo = 0;
            if (i === max) {
                saldo = parseInt(valorConductor) - parseInt(vlrAnticipo);
                console.log(valorConductor);
            }
            serv += "<tr><td></td><td></td><td></td><td></td><td></td><td style='text-align: right;'><strong>Saldo por pagar</strong></td><td>$ " + $.number(saldo) + "</td><td></td><td></td></tr>";

        } else if (valoresanticipos[i].prueba_entrega === 'N' && valoresanticipos[i].val_valorAdelanto !== '0') {
            //("No ha sido pagado y el anticipo es distinto de cero");

            serv += "<tr><td></td><td></td><td></td><td></td><td><strong>N&uacute;mero anticipo</strong></td><td>" + valoresanticipos[i].val_numeroAnticipo + "</td><td>$ " + $.number(valoresanticipos[i].val_valorAdelanto) + "</td><td></td><td></td><td></td></tr>";
            saldo = 0;
            totalAnticipos = totalAnticipos + parseInt(valoresanticipos[i].val_valorAdelanto);
            varios = 1;
        }
    }

    switch (varios) {
        case 1:
            saldo = parseInt(valorConductor) - parseInt(totalAnticipos);
            serv += "<tr><td></td><td></td><td></td><td></td><td></td><td style='text-align: right;'><strong>Total anticipos</strong></td><td>$ " + $.number(totalAnticipos) + "</td><td></td><td></td><td></td></tr>";
            serv += "<tr><td></td><td></td><td></td><td></td><td></td><td style='text-align: right;'><strong>Saldo por pagar</strong></td><td>$ " + $.number(saldo) + "</td><td></td><td></td><td></td></tr>";
            break;
    }

}


function canServicio(idservicio) {

    if (confirm("Esta acción anulara toda información de este servicio")) {
        $.ajax({
            url: "../trafico/borrarServicio.php",
            data: {'numeroServicio': idservicio,
                'opcion': 1,
                'motivo': $("#motivoAnularServicio").val()},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj !== false) {
                    $("#mensajes").html('<div class="alert alert-dismissible alert-success">Servicio: <strong>' + idservicio + '</strong> ha sido anulado con &eacute;xito</div>');
                    $("#mensajesGenerales").html('');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("function canServicio(idservicio) {...");
            }
        });
    }
}

function cambiarFactura(valor, idservicio) {
    var factura = $("#" + valor.id).val();
    $.ajax({
        url: "../trafico/cambiarFactura.php",
        data: {'idservicio': idservicio,
            'factura': factura},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj === true) {
                $("#mensajesGenerales").html('<div class="alert alert-dismissible alert-success">Al servicio: <strong>' + idservicio + '</strong> le ha sido asignada la factura: ' + factura + '</div>');
            } else {
                $("#mensajesGenerales").html('<div class="alert alert-dismissible alert-success">Fallo en el cambio de factura, por favor presione F5 e intentelo nuevamente</div>');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function canServicio(idservicio) {...");
        }
    });
}

function advertenciaGanancia(valor) {
    if (valor < 0) {
        alert("El valor de ganancia de este servicio es incoherente");
    }
}

function cambiarEmpleado(idservicio) {

    var idempleado = $("#cambiarAsesor").val();
    var idempleadoActual = $("#cambiarAsesor option:selected").text();

    if (confirm("¿Cambiar asesor " + asesorActual + " por " + idempleadoActual + " para el servicio " + idservicio + " ?")) {
        $.ajax({
            url: "../trafico/cambiarAsesorServicio.php",
            data: {'idservicio': idservicio,
                'idempleado': idempleado},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === true) {
                    $("#mensajesGenerales").html('<div class="alert alert-dismissible alert-success">El servicio: <strong>' + idservicio + '</strong> se ha sido asignado al asesor: <strong>' + idempleadoActual + '</stron></div>');
                } else {
                    $("#mensajesGenerales").html('<div class="alert alert-dismissible alert-success">Fallo en el cambio de asesor, por favor presione F5 e intentelo nuevamente</div>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("function function cambiarEmpleado(idservicio) { {...");
            }
        });
    } else {
        $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-success'>No hay cambios en el servicio</div>");
    }
}

function redireccionar(opcion) {
    idanticipo = $("#idanticipo").val();    
    switch (opcion) {
        case 1:
            window.location.href = "../modulos/sobreanticipos.php?idanticipo=" + idanticipo;
            break;
        default:

            break;
    }
    idanticipo = null;
}

function botonAnularServicio(idservicio) {
    return    "<table><tr><td><select id='motivoAnularServicio' name='motivoAnularServicio' class='form-control' >\n\
<option value='0'>...</option>\n\
<option value='Error de digitación'>Error de digitación</option>\n\
<option value='Error de sistema'>Error de sistema</option>\n\
<option value='Cambio vehiculo'>Cambio vehiculo</option>\n\
<option value='Cliente cancela servicio'>Cliente cancela servicio</option>\n\
</select></td>\n\
<td><button name='anularServicio' id='anularServicio' type='button' class='btn btn-success btn-ls botonPropio' value='SALIR' onmouseleave='colorSale(this);' onmouseenter='colorEntra(this);' onclick='canServicio(" + idservicio + ");' >ANULAR SERVICIO <img src='../imagenes/delete_16.png'></button></td></tr></table>";
}

function valorDeclarado(serviciovariasguias, valordeclarado, ciclo, valor, tamanioVector, guia) {
    switch (valor) {
        case 0:
            if (typeof valordeclarado[ciclo] === 'undefined') {
                vlrDec = '<input type="number" name="valorDeclarado-' + guia + '" id="valorDeclarado-' + guia + '" value="0" onblur="cambiarValorDeclarado(this)" />';
            } else if (parseInt(serviciovariasguias[ciclo].guia) === parseInt(valordeclarado[ciclo].guia)) {
                vlrDec = '<input type="number" name="valorDeclarado-' + guia + '" id="valorDeclarado-' + guia + '" value="' + valordeclarado[ciclo].valor + '" onblur="cambiarValorDeclarado(this)" />';
            } else {
                vlrDec = '<input type="number" name="valorDeclarado-' + guia + '" id="valorDeclarado-' + guia + '" value="' + valordeclarado + '" onblur="cambiarValorDeclarado(this)" />';
            }
            break;
        case 1:
            for (var j = 0; j <= tamanioVector - 1; j++) {
                if (parseInt(serviciovariasguias[ciclo].guia) === parseInt(valordeclarado[j].guia)) {
                    vlrDec = valordeclarado[j].valor;
                }
            }
            break;
    }
}

function mostrarValoresVarios(valores) {
    serv += "<tr><td><strong>OTROS</strong></td><td><strong>COSTOS</strong></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
    serv += "<tr><td><strong>Auxiliar</strong></td><td><input type='number' name='auxiliar' id='auxiliar' value='" + valores[0]["valor"] + "' onblur='modificarValor(this);' /></td>\n\
<td><strong>Parqueadero</strong></td><td><input type='number' name='parqueadero' id='parqueadero' value='" + valores[1]["valor"] + "' onblur='modificarValor(this);' /></td>\n\
<td><strong>Otros</strong></td><td><input type='number' name='otros' id='otros' value='" + valores[2]["valor"] + "' onblur='modificarValor(this);' /></td>\n\
<td></td><td></td><td></td><td></td></tr>";
}

function mostrarValoresServicio(valorCliente, valorContratista, ganancia) {

    /* ServicioNoFacturado UnaEmpresa
     * ServicioFacturado UnaEmpresa
     * ServicioFacturado VariasEmpresas
     * ServicioNoFacturado VariasEmpresas
     */

    if (varEmp === 0) {
        serv += "<tr><td style='text-align: right;' ><strong>Vlr Cliente</strong></td><td><input type='number' id='vlrCliente' value='" + valorCliente + "' onfocusout='modificarVlrCliente(this);' /></td>";
        serv += "<td style='text-align: right;' ><strong>Vlr Contratista</strong></td><td><input type='number' id='vlrContratista' value='" + valorContratista + "' onfocusout='modificarVlrContratista(this);' /></td>";
        serv += "<td style='text-align: right;' ><strong>Diferencia</strong></td><td><input type='number' id='vlrDiferencia' value='" + ganancia + "' /></td><td></td><td></td><td></td><td></td></tr>";
    } else {
        serv += "<tr><td style='text-align: right;' ><strong>Vlr Cliente</strong></td><td><input type='number' id='vlrCliente' value='" + valorCliente + "' size='8' /></td>";
        serv += "<td style='text-align: right;' ><strong>Vlr Contratista</strong></td><td><input type='number' id='vlrContratista' value='" + valorContratista + "' size='8' /></td>";
        serv += "<td style='text-align: right;' ><strong>Diferencia</strong></td><td><input type='number' id='vlrDiferencia' value='" + ganancia + "' size='8' /></td><td></td><td></td><td></td><td></td></tr>";
    }
}

function modificarVlrCliente(valor) {
    var vlr = "#" + valor.id;
    var vlrServicio = $(vlr).val();
    var idservicio = $("#idservicio").val();
    var diferencia = 0;
    if (confirm("¿Cambiar el valor del servicio " + idservicio + " para el cliente a $" + $.number(vlrServicio) + "?")) {
        $.ajax({
            url: "../trafico/cambiarPrecioServicio.php",
            data: {'opcion': '0',
                'idservicio': idservicio,
                'valor': vlrServicio
            },
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 2) {
                    $("#mensajesGenerales").html('<div class="alert alert-dismissible alert-success">Precio servicio cambiado con éxito</div>');
                    diferencia = parseInt($("#vlrCliente").val()) - parseInt($("#vlrContratista").val());
                    $("#vlrDiferencia").focus();
                    $("#vlrDiferencia").val(diferencia);
                } else {
                    alert("Fallo el cambio del precio, por favor presione F5 e intentelo nuevamente");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("fallo en ajax function borrarMensaje(valor) {...");
            }

        });
    }
    diferencia = 0;
}

function modificarVlrContratista(valor) {
    var vlr = "#" + valor.id;
    var vlrServicio = $(vlr).val();
    var idservicio = $("#idservicio").val();
    var diferencia = 0;
    if (confirm("¿Cambiar el valor pagado del servicio " + idservicio + " para el contratista a $" + $.number(vlrServicio) + "?")) {
        $.ajax({
            url: "../trafico/cambiarPrecioServicio.php",
            data: {'opcion': '1',
                'idservicio': idservicio,
                'valor': vlrServicio,
                'varEmp': varEmp},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 3) {
                    $("#mensajesGenerales").html('<div class="alert alert-dismissible alert-success">Valor a contratista cambiado con éxito</div>');
                    diferencia = parseInt($("#vlrCliente").val()) - parseInt($("#vlrContratista").val());
                    $("#vlrDiferencia").focus();
                    $("#vlrDiferencia").val(diferencia);
                } else {
                    alert("Fallo el cambio del valor a contratista, por favor presione F5 e intentelo nuevamente");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("fallo en ajax function function modificarVlrContratista(valor) {");
            }

        });
    } else {
        $("#vlrDiferencia").focus();
    }
    diferencia = 0;
}

function borrarMensaje(valor) {
    var id = valor.id;
    if (confirm("Borrar mensaje?")) {
        $.ajax({
            url: "../trafico/borrarMensaje.php",
            data: {'id': id},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    $("#mensajeCancelar").html('');
                    $("#mensajeCancelar").removeClass('alert alert-dismissible alert-warning center-block');
                } else {
                    $("#mensajesGenerales").html('<div class="alert alert-dismissible alert-success">Fallo la actualización del mensaje, por favor presione F5 e intentelo nuevamente</div>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("fallo en ajax function borrarMensaje(valor) {...");
            }
        });
    } else {
        $("#vlrDiferencia").focus();
    }
}

function cambiarVlrContratista(dato) {

    var vlr = "#" + dato.id;
    var longitud = vlr.length;
    var guia = vlr.substr(6, longitud);
    var valorACambiar = $(vlr).val();
    var posicion = null;
    var valor = 0;

    if (confirm("Este servicio es para varias empresas y lo hace un solo conductor\nCambiar valor de Guia: " + guia + " del servicio: " + idservicio + " a: $" + $.number(valorACambiar) + "? \nValor pagado al contratista")) {
        $.ajax({
            url: "../trafico/cambiarPrecioServicio.php",
            data: {'opcion': '2',
                'guia': guia,
                'valor': valorACambiar},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    $("#mensajesGenerales").html('<div class="alert alert-dismissible alert-success">Valor a contratista cambiado con éxito</div>');
                    posicion = Guias.indexOf(guia);
                    ValoresContratista[posicion] = valorACambiar;
                    for (var k = 0; k <= ValoresContratista.length - 1; k++) {
                        valor = valor + parseInt(ValoresContratista[k]);
                    }
                    $("#vlrContratista").val(valor);
                    diferencia = parseInt($("#vlrCliente").val()) - parseInt($("#vlrContratista").val());
                    $("#vlrDiferencia").focus();
                    $("#vlrDiferencia").val(diferencia);
                } else {
                    alert("Fallo el cambio del valor a contratista, por favor presione F5 e intentelo nuevamente");
                }
                ValoresContratista = [];
                diferencia = 0;
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("fallo en ajax function cambiarVlrContratista(valor){...");
            }
        });
    }
}

function cambiarVlrAFacturar(dato) {

    var vlr = "#" + dato.id;
    var longitud = vlr.length;
    var guia = vlr.substr(6, longitud);
    var valorACambiar = $(vlr).val();
    var posicion = null;
    var valor = 0;

    if (confirm("Este servicio es para varias empresas y lo hace un solo conductor\nCambiar valor de Guia: " + guia + " del servicio: " + idservicio + " a: $" + $.number(valorACambiar) + "? \nValor a facturar a empresa")) {
        $.ajax({
            url: "../trafico/cambiarPrecioServicio.php",
            data: {'opcion': '3',
                'guia': guia,
                'valor': valorACambiar},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    alert("Valor a facturar cambiado con éxito");
                    posicion = Guias.indexOf(guia);
                    ValoresEmpresa[posicion] = valorACambiar;
                    for (var k = 0; k <= ValoresEmpresa.length - 1; k++) {
                        valor = valor + parseInt(ValoresEmpresa[k]);
                    }
                    $("#vlrCliente").val(valor);
                    diferencia = parseInt($("#vlrCliente").val()) - parseInt($("#vlrContratista").val());
                    $("#vlrDiferencia").focus();
                    $("#vlrDiferencia").val(diferencia);
                } else {
                    alert("Fallo el cambio del valor a facturar a empresa, por favor presione F5 e intentelo nuevamente");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("fallo en ajax function cambiarVlrAFacturar(dato) {...");
            }
        });
    }

}

function modificarComentario() {

    var idservicio = $("#idservicio").val();
    var comentario = $("#comentario").val();

    if (confirm("Crear y/o modificar comentario de servicio " + idservicio)) {
        $.ajax({
            url: "../trafico/modificarComentarioServicio.php",
            data: {'idservicio': idservicio,
                'comentario': comentario},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj) {
                    $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-success'>Se ha creado y/o modificado el comentario del servicio " + idservicio + "</div>");
                } else {
                    $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-danger'>Fallo al crear y/o modificar el comentario del servicio " + idservicio + "</div>");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("fallo en ajax function function modificarComentario() {...");
            }
        });
    }
}

function modificarValor(valores) {

    var id = valores.id;
    var valor = $("#" + id).val();
    var idservicio = $("#idservicio").val();
    var totalDescuentos = 0;

    if (confirm("Modificar el valor de " + id + " de servicio " + idservicio + " a: " + valor)) {
        $.ajax({
            url: "../trafico/modificarVarios.php",
            data: {'idservicio': idservicio,
                'valor': valor,
                'detalle': id},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj) {
                    $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-success'>Se ha modificado el valor de " + id + " de servicio " + idservicio + "</div>");
                    totalDescuentos = parseInt($("#vlrContratista").val()) + parseInt($("#auxiliar").val()) + parseInt($("#parqueadero").val()) + parseInt($("#otros").val());
                    $("#vlrDiferencia").val(parseInt($("#vlrCliente").val()) - totalDescuentos);
                } else {
                    $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-danger'>Fallo al modificar el valor de " + id + " de servicio " + idservicio + "</div>");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("fallo en ajax function function modificarValor(valores) {...");
            }
        });
    }
}

function habilitarCtaCobro() {
    var idservicio = $("#idservicio").val();
    if (confirm("Habilitar cuenta de cobro del servicio: " + idservicio + "?")) {
        $.ajax({
            url: "../trafico/Servicios.php",
            data: {'caso': '1',
                'idservicio': idservicio},
            type: "POST",
            success: function (data) {
                console.log(data);
                var obj = JSON.parse(data);
                if (obj === 1) {
                    $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-success'>Cuenta de cobro hablitada nuevamente.Por favor realizar gestión para generar cuenta de cobro</div>");
                } else {
                    $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-danger'>Fallo al cambiar el estado de la cuenta de cobro. Por favor presione F5 e intentelo nuevamente</div>");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("fallo en ajax function habilitarCtaCobro() {...");
            }
        });
    }
}

function cambiarNumeroGuia(valor) {

    var guiaId = valor.id;
    var guiaAnterior = guiaId.substr(5, guiaId.length);
    var guiaNueva = $("#" + guiaId).val();
    var idservicio = $("#idservicio").val();
    $("#mensajesGenerales").html('');

    if (confirm("Cambiar guía : " + guiaAnterior + " por la guía: " + guiaNueva + " en el servicio: " + idservicio + "?") && (parseInt(guiaNueva) > 0)) {
        $.ajax({
            url: "../trafico/Servicios.php",
            data: {'caso': '2',
                'guiaAnterior': guiaAnterior,
                'guiaNueva': guiaNueva,
                'idservicio': idservicio},
            type: "POST",
            success: function (data) {
                console.log(data);
                var obj = JSON.parse(data);
                if (obj === 4 || obj === 5) {
                    $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-success'>Gu&iacute;a cambiada con &eacute;xito</div>");
                } else {
                    $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-danger'>Fallo al cambiar el n&uacute;mero de gu&iacute;a. Por favor presiones F5, digite el n&uacute;mero de servicio e intentelo nuevamente.</div>");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("fallo en ajax function cambiarNumeroGuia(valor) {");
            }
        });
    } else {
        $("#" + guiaId).val(guiaAnterior);
        $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-success'>No se realizó ning&uacute;n cambio de gu&iacute;a</div>");
    }
}

function cambiarValorDeclarado(valor) {
    var nombre = valor.name;
    var tamanio = nombre.leght;
    var guia = nombre.substring(15, tamanio);
    //valor nuevo
    var vlrNuevo = $("#" + valor.id).val();

    if (confirm("Cambiar el valor declarado de la guia: " + guia + " a: " + vlrNuevo + "?")) {

        $.ajax({
            url: "../trafico/ValorDeclarado.php",
            data: {'opcion': '2',
                'guia': guia,
                'nuevoValor': vlrNuevo,
                'idservicio': idservicio},
            type: "POST",
            success: function (data) {
                console.log(data);
                var obj = JSON.parse(data);
                if (obj === 1) {
                    $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-success'>Valor cambiado con éxito</div>");
                } else {
                    $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-danger'>Fallo al cambiar el valor de la guía. Por favor presione F5 y vuelva a intentarlo</div>");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("fallo en ajax function cambiarValorDeclarado(valor){...");
            }
        });

    }
}
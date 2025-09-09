var listaPlacas = null, listaDirecciones = null, listaTelefonos = null, empresa = null, asesor = null,
        serviciosGeneral = [], nits = [], vlrContratista = null, retornoAntSal = new Array(), caliSatisfactorios = null,
        caliNoSatisfactorios = null, placaA = null, cedulaPropietarioA = null, cedulaConductorA = null, otrosValores = new Array(),
        totalOtrosCostos = 0;
$(document).ready(function () {
    
    establecerFechaHoraEntrega();

    $("#listaPlacas").val('0');

    $('input[type=text]').keyup(function () {
        $(this).val($(this).val().toUpperCase());
    });

    listaPlacas = $("#listaPlacas");

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $("#valorContratista").blur(function () {
        $("#vlrTot2").val(retornarSumaValores());
    });

    $("#porManejo").blur(function () {
        var totVal1 = null;
        if ($("#porManejo").val() !== '0') {
            totVal1 = (parseFloat($("#porManejo").val()) * parseFloat($("#valorDeclarado").val())) / 100;
            $("#valorEmpresa").focus();
            $("#valorManejo").val(totVal1);
            $("#totVal1").val(totVal1);
        } else {
            $("#porGanancia").html("<div class='alert alert-danger'>La gu&iacute;a " + $("#guia").val() + " <strong>No lleva valor de manejo</strong></div>");
        }
    });

    $("#crearGuia").click(function () {
        var fechas = new Date();
        var anio = fechas.getFullYear();
        var mes = fechas.getMonth();
        var digitosPlaca = '';
        var guiaGenerada = '';
        if (parseInt(mes) <= 9) {
            mes = '0' + mes;
        }
        var dia = fechas.getDate();

        var placa = $("#listaPlacas").val();

        if (placa === '0') {
            $("#trGuiaPlanillaOtro").show();
            $("#tdMsjGuia").html("<div class='alert alert-danger'>Para generar la guía automatica debe seleccionar una PLACA</div>");
            guiaGenerada = '00000000000';
        } else {
            $("#trGuiaPlanillaOtro").hide();
            $("#tdMsjGuia2").html("");
            digitosPlaca = placa.substr(3, placa.length);
            guiaGenerada = anio + mes + dia + digitosPlaca;
        }

        $("#guia").val(guiaGenerada);
    });

    $("#valorDeclarado").number(true, 0);
    $("#valorContratista").number(true, 0);
    $("#valorEmpresa").number(true, 0);
    $("#valorAuxiliar").number(true, 0);
    $("#valorParqueadero").number(true, 0);
    $("#valorOtros").number(true, 0);
    $("#valorcontratista").number(true, 0);
    $("#auxiliarCarga").number(true, 0);
    //$("#parqueadero").number(true, 0);
    $("#otros").number(true, 0);
    $("#valortotal").number(true, 0);
    $("#valorfacturar").number(true, 0);
    $("#vlrAnticipo").number(true, 0);
    $("#vlrTot2").number(true, 0);
    $("#trEmpresaAsesor").hide();
    $("#trGuiaPlanillaOtro").hide();
    $("#trDirOrg").hide();
    $("#trDirDes").hide();
    $("#trPlacasPropietario").hide();
    $("#trMensajesPorcentajes").hide();

    $("#listaPlacas").change(function () {

        $("#mensajeEmpresa").html('');
        if ($("#listaPlacas").val() === '1') {
            $("#divListaPlacas").html('<input type="text" id="placaNueva" name="placaNueva" placeholder="AAA000" title="Digite aqu&iacute; la placa nueva" class="form-control input-sm" onblur="crearPlaca(this)"; />');
            $("#cedulaPropietario").val('');
            $("#nombresPropietario").val('');
            $("#cedulaConductor").val('');
            $("#nombresConductor").val('');
            $("#placaNueva").focus();
        } else if ($("#listaPlacas").val() === '0') {
            $("#cedulaPropietario").val('');
            $("#nombresPropietario").val('');
            $("#cedulaConductor").val('');
            $("#nombresConductor").val('');
            $("#trMensajesPorcentajes").hide();
        } else {
            if ($("#listaPlacas").val() !== '0') {
                buscarPropietarioConductoresdePlaca($('#listaPlacas option:selected').html());
            }
        }
    });

    $("#guia").focusin(function () {
        if ($("#listaClientes").val() === '0' || $("#listaAsesores").val() === '0' || $("#listaClientes").val() === '') {
            $("#trEmpresaAsesor").show();
            $("#tdMsjEmpresa").html("<div class='alert alert-dismissible alert-success'>Se esta intentanto crear un servicio sin asociar al menos una empresa. <strong>Por favor seleccione una empresa para asociar a la guía a incluir en el servicio</strong></div>");
            $("#listaClientes").focus();
        } else {
            $("#trEmpresaAsesor").hide();
            $("#tdMsjEmpresa").html("");
        }
    });
    $("#listaClientes").focusin(function () {
        if ($("#listaPlacas").val() === '0') {
            $("#trPlacasPropietario").show();
            $("#tdMsjPlacas").html("<div class='alert alert-dismissible alert-success'>Se esta intentanto crear un servicio sin asociar al menos una placa. <strong>Por favor seleccione una placa para asociar a la guía a incluir en el servicio</strong></div>");
            $("#listaPlacas").focus();
        } else {
            $("#trPlacasPropietario").hide();
            $("#tdMsjPlacas").html("");
        }
    });

    $("#direccionorigen").focusin(function () {
        $("#trGuiaPlanillaOtro").hide();
    });

    $("#botonAgregarDiv").mouseenter(function () {
        if (parseInt($("#valorContratista").val()) > 0 && parseInt($("#valorEmpresa").val()) > 0) {
            var porGan = 100 - (retornarSumaValores() * 100) / parseInt($("#valorEmpresa").val());
        } else {
            porGan = 0;
        }
        porcentajeGanancia(porGan);
    });

    $("#botonCrearServicio").click(function () {
        if (serviciosGeneral.length <= 0) {
            $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-danger'>Se ha intentado crear un servicio sin crear las guías asociadas. Por favor llene y/o verifique que el formulario se encuentre bien diligenciado o presione F5 para iniciar nuevamente</div>");
        } else {
            crearServicio(serviciosGeneral, serviciosGeneral.length);
        }
    });

    $('#chkAnticipo').on('change', function () {
        if (this.checked) {
            $("#valorAnticipo").show();
            $("#valorSaldo").show();
        } else {
            $("#valorAnticipo").hide();
            $("#valorSaldo").hide();
        }
    });

    $("#sumarOtrosValores").click(function () {
        var total, tabla, a = [];
        if ($("#nombreConcepto").val() === "" || $("#valorConcepto").val() === "0") {
            $("#mensajesOtrosValores").html("<div class='alert alert-danger'>Por favor verifique el concepto y/o valor a agregar</div>");
        } else {
            $("#mensajesOtrosValores").html("");
            a[0] = $("#nombreConcepto").val();
            a[1] = parseInt($("#valorConcepto").val());
            a[2] = parseInt($("#guia").val());
            otrosValores.push(a);
            $("#nombreConcepto").val("");
            $("#valorConcepto").val("0");
            $("#nombreConcepto").focus();
            //console.log(otrosValores);
            pintarTablaOtrosValores(otrosValores);
        }
    });

    $("#nombreConcepto").focusin(function () {
        $("#nombreConcepto").val("");
        $("#valorConcepto").val("");
        $("#mensajesOtrosValores").html("");
    });

    $("#valorConcepto").focusin(function () {
        $("#mensajesOtrosValores").html("");
    });

});

function pintarTablaOtrosValores(arreglo) {
    var tabla, total = 0, vlrOtros;
    if (arreglo.length === 0) {
        $("#totalOtrosConceptos").val("0");
        $("#divConceptos").html("");
    } else if (arreglo === null) {
        $("#totalOtrosConceptos").val("0");
        $("#divConceptos").html("");
        $("#valorConcepto").val("0");
        $("#divConceptos").html("");
    } else {
        tabla = "<table class='table table-striped'>\n\
<tr><td>Concepto</td><td>Valor</td><td></td></tr>";
        for (var i = 0, max = arreglo.length; i < max; i++) {
            if (arreglo[i][2] === parseInt($("#guia").val())) {
                tabla += "<tr><td>" + arreglo[i][0] + "</td><td>" + arreglo[i][1] + "</td><td><input type='button' value='x' id=" + [i] + " name=" + [i] + " onclick='borrarConcepto(this)'/></td></tr>";
                total = total + arreglo[i][1];
            }
        }
        tabla += "</table>";
        $("#divConceptos").html(tabla);
    }
    $("#valorOtros").val(total);
    $("#totalOtrosConceptos").val(total);
}

function borrarConcepto(valor) {
    otrosValores.splice(valor.id, 1);
    pintarTablaOtrosValores(otrosValores);
}

function buscarPropietarioConductoresdePlaca(placa) {
    placaA = placa;
    $.ajax({
        url: "../trafico/retornarPropietarioConductores.php",
        data: {'placa': placa},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj !== false) {
                if (obj.propietario[0] === undefined) {
                    $("#mensajePlaca").html("<div class='alert alert-danger'>La placa no registra propietario</div>");
                } else {
                    buscarCalificacionesPlaca(placa);
                    buscarDatosVehiculo(placa);
                    $("#cedulaPropietario").val(obj["propietario"][0].cond_identificacion);
                    buscarCalificacionesPropietario(obj["propietario"][0].cond_identificacion);
                    $("#nombresPropietario").val(obj["propietario"][0].nombresPropietario);
                    $("#trMensajesPorcentajes").show();
                    mostrarConductores(obj);
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en la función buscarPropietarioConductoresdePlaca");
        }
    });
}

function buscarDatosVehiculo(placa) {

    $.ajax({
        url: "../trafico/vehiculo.php",
        data: {'caso': '1',
            'placa': placa},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj[0].marca === '0') {
                alert("La placa " + placa + "; no registra datos actualizados en el sistema.\nPor favor haga las actualizaciones en los datos de este vehículo.");
                window.location.href = '../modulos/vehiculos.php?pl=' + placa + "";
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en la function buscarDatosVehiculo(placa){");
        }
    });

}

function buscarCalificacionesPropietario(cedula) {
    cedulaPropietarioA = cedula;
    $.ajax({
        url: "../trafico/Calificaciones.php",
        data: {'caso': 3, 'cedulaPropietario': cedula},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.length === 0) {
                $("#tdMsjPropietario").html("<div class='alert alert-dismissible alert-danger'>C&eacute;dula propietario: " + cedula + "<br>No se registran valores de calificaci&oacute;n</div>");
            } else {
                caliSatisfactorios = 0;
                caliNoSatisfactorios = 0;
                for (var i = 0; i < obj.length; i++) {
                    if (obj[i].calificacion === '1') {
                        caliSatisfactorios += 1;
                    } else {
                        caliNoSatisfactorios += 1;
                    }
                }
                $("#noSatPropietario").val("" + caliNoSatisfactorios + "");
                $("#tdMsjPropietario").html("<div class='alert alert-dismissible alert-success'>C&eacute;dula propietario: " + cedula + "<br>Servicios satisfactorios:" + caliSatisfactorios + "<br> No satisfactorios:" + caliNoSatisfactorios + "</div>");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en la function buscarCalificacionesConductor(cedula) {...");
        }
    });
}

function buscarCalificacionesPlaca(placa) {
    $.ajax({
        url: "../trafico/Calificaciones.php",
        data: {'caso': 2, 'placa': placa},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.length === 0) {
                $("#tdMsjPlaca").html("<div class='alert alert-dismissible alert-danger'>Placa: " + placa + "<br>No se registran valores de calificaci&oacute;n</div>");
            } else {
                caliSatisfactorios = 0;
                caliNoSatisfactorios = 0;
                for (var i = 0; i < obj.length; i++) {
                    if (obj[i].calificacion === '1') {
                        caliSatisfactorios += 1;
                    } else {
                        caliNoSatisfactorios += 1;
                    }
                }
                $("#noSatPlaca").val("" + caliNoSatisfactorios + "");
                $("#tdMsjPlaca").html("<div class='alert alert-dismissible alert-success'>Placa: " + placa + "<br>Servicios satisfactorios:" + caliSatisfactorios + "<br> No satisfactorios:" + caliNoSatisfactorios + "</div>");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en la function buscarCalificacionesPlaca(placa){...");
        }
    });
}

function buscarCalificacionesConductor() {
    var cedulaConductor = $("#cedulaConductor").val();
    cedulaConductorA = cedulaConductor;
    $.ajax({
        url: "../trafico/Calificaciones.php",
        data: {'caso': 4, 'cedulaConductor': cedulaConductor},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.length === 0) {
                $("#tdMsjConductor").html("<div class='alert alert-dismissible alert-danger'>C&eacute;dula conductor: " + cedulaConductor + "<br>No se registran valores de calificaci&oacute;n</div>");
            } else {
                caliSatisfactorios = 0;
                caliNoSatisfactorios = 0;
                for (var i = 0; i < obj.length; i++) {
                    if (obj[i].calificacion === '1') {
                        caliSatisfactorios += 1;
                    } else {
                        caliNoSatisfactorios += 1;
                    }
                }
                $("#noSatConductor").val("" + caliNoSatisfactorios + "");
                $("#tdMsjConductor").html("<div class='alert alert-dismissible alert-success'>C&eacute;dula conductor: " + cedulaConductor + "<br>Servicios satisfactorios:" + caliSatisfactorios + "<br> No satisfactorios:" + caliNoSatisfactorios + "</div>");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en la function buscarCalificacionesPlaca(placa){...");
        }
    });
}

function mostrarConductores(obj) {
    var listaConductores;
    if (obj['conductores'].length === 0) {
        $("#nombresConductor").val(obj["propietario"][0].nombresPropietario);
        $("#divCedulasConductores").html('<input type="number" name="cedulaConductor" id="cedulaConductor" class="form-control input-sm" title="C&eacute;dula del conductor" value="' + obj["propietario"][0].cond_identificacion + '" readonly="readonly" />');
    } else if (obj['conductores'].length === 1) {
        $("#nombresConductor").val(obj["conductores"][0].nombreConductor);
        $("#divCedulasConductores").html('<input type="number" name="cedulaConductor" id="cedulaConductor" class="form-control input-sm" title="C&eacute;dula del conductor" value="' + obj["conductores"][0].identificacion + '" readonly="readonly" />');
    } else {
        listaConductores = '<select id="cedulaConductor" name="cedulaConductor" class="form-control form-control-sm" onchange="mostrarIdentificacion();">\n\
<option value="0">...</option>';
        for (var i = 0, max = obj['conductores'].length; i < max; i++) {
            listaConductores = listaConductores + '<option value ="' + obj["conductores"][i].nombreConductor + '">' + obj["conductores"][i].identificacion + '</option>';
        }
        listaConductores = listaConductores + '</select>';
        $("#divCedulasConductores").html(listaConductores);
    }
    buscarCalificacionesConductor();
    $("#listaClientes").focus();
}

function mostrarIdentificacion() {
    $("#nombresConductor").val($("#cedulaConductor").val());
}

function mostrarNit(elemento) {

    var a = "#" + elemento.id;
    if ($(a).val() === '1' || $(a).val() === '0') {
        $("#nitEmpresa").val('');
        $("#trEmpresaAsesor").show();
        $("#listaAsesores").val('0');
        $("#tdMsjEmpresa").html("<div class='alert alert-dismissible alert-success'>Por favor seleccione una empresa para asociar a la guía a incluir en el servicio</div>");
    } else {
        $("#nitEmpresa").val($(a).val());
        mostrarEmpresaAsesor($(a).val());
        retornarDireccionesOrigen($(a).val());
        retornarDireccionesDestino($(a).val(), 1);
        $("#guia").focus();
    }
}

function mostrarEmpresaAsesor(nit) {
    $.ajax({
        url: "../trafico/retornarAsesorEmpresa.php",
        data: {'opcion': 1,
            'nit': nit},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.length === 0) {
                $("#trEmpresaAsesor").show();
                $("#tdMsjEmpresa").html("<div class='alert alert-dismissible alert-danger'>La empresa selecionada no tiene asesor vinculado, por favor seleccione un asesor de la lista <strong>ASESOR</strong></div>");
                $("#listaAsesores").val('0');
                $("#listaAsesores").focus();
            } else {
                $("#trEmpresaAsesor").hide();
                $("#listaAsesores").val(obj[0].cedula);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en la función function mostrarEmpresaAsesor(nit){...");
        }
    });
}

function cambiarAsesorEmpresa(valor) {
    var a = "#" + valor.id;
    var vlr = $(a).val();
    var nit = $("#listaClientes").val();
    asesor = $('#listaAsesores option:selected').html();
    empresa = $('#listaClientes option:selected').html();
    if (vlr === '0' || $("#listaClientes").val() === '0') {
        $("#trEmpresaAsesor").show();
        $("#tdMsjEmpresa").html("<div class='alert alert-dismissible alert-danger'>Por favor seleccione una empresa para asociar a la guía a incluir en el servicio</div>");
        $("#listaClientes").focus();
        $("#listaClientes").val('0');
        $("#nitEmpresa").val('');
        $("#listaAsesores").val('0');
    } else if (confirm("Se va a crear y/o cambiar\nEl asesor: " + vlr + " " + asesor + "\na la empresa: " + nit + " " + empresa + "\nEsta seguro/a?")) {
        $.ajax({
            url: "../trafico/retornarAsesorEmpresa.php",
            data: {'opcion': 2,
                'nit': nit,
                'cedula': vlr},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj) {
                    $("#trEmpresaAsesor").show();
                    $("#tdMsjEmpresa").html("<div class='alert alert-dismissible alert-success'>Se ha creado y/o vinculado correctamente a: " + asesor + " con: " + empresa + "</div>");
                } else {
                    $("#tdMsjEmpresa").html("<div class='alert alert-dismissible alert-danger'>Algo ha salido mal. Por favor presione F5 y vuelva a intentarlo</div>");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en la función function function cambiarAsesorEmpresa(valor) {...");
            }
        });
    } else {
        $("#listaClientes").focus();
        $("#listaClientes").val('0');
        $("#nitEmpresa").val('');
        $("#listaAsesores").val('0');
        $("#tdMsjEmpresa").html("<div class='alert alert-dismissible alert-success'>Cuidado! Toda guía debe tener una empresa y asesor vinculados. Por favor seleccione una empresa de la lista <strong>NOMBRE EMPRESA</strong></div>");
    }
}

function buscarGuia(elemento) {
    $("#trGuiaPlanillaOtro").show();
    var guia = $("#" + elemento.id).val();
    var servicios = '', longitud = null;
    var idservicio = null, fechaServicio = null;
    if (guia <= 0 || guia === '' || guia === '0') {
        $("#tdMsjGuia2").html("");
        $("#tdMsjGuia").html("<div class='alert alert-danger'>Se debe digitar un <strong>n&uacute;mero de gu&iacute;a</strong> en todo <strong>servicio</strong></div>");
        $("guia").focus();
    } else {
        $("#tdMsjGuia").html("");
        $.ajax({
            url: "../trafico/verificarGuia.php",
            data: {'guia': $("#" + elemento.id).val(),
                'idempleado': $("#emp_cedula").val(),
                'decision': '1'},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === false) {
                    $("#tdMsjGuia").html("<div class='alert alert-success'>La gu&iacute;a: <strong>" + guia + " se vinculará a este servicio</div>");
                    crearGuia($("#" + elemento.id).val());
                } else {
                    for (var i = 0, max = obj.length; i < max; i++) {
                        if (i === 0) {
                            idservicio = obj[i].idservicio;
                        }
                        servicios = servicios + obj[i].idservicio + '\n';
                    }
                    longitud = servicios.length;
                    longitud = longitud - 1;
                    servicios = servicios.substr(0, longitud);
                    $("#tdMsjGuia2").html("");
                    if (confirm("¡Advertencia!\nEl número de guía: " + guia + " \nSe encuentra registrado en los siguientes servicios:\n" + servicios + ";\nDesea vincularla también a este nuevo servicio?")) {

                        //22032024 a raíz de una guía repetida se crea la opción de evitar pasar del campo
                        $("#guia").val('0');
                        $("#guia").focus();

                        /*
                         * Se dejó de usar por varios mensajes creados para gerencia
                         * crearGuiaPorCancelar(guia, idservicio);
                         */
                    } else {
                        $("#guia").val('0');
                        $("#guia").focus();
                    }
                    idservicio = null;
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en #guia focusout (function (){...");
            }
        });
    }
}

/*
 * Se dejó de usar por demasiados mensajes constantes a gerencia cuando se 
 * detecta una guía repetida
 * Se comentarió en ../trafico/verificarGuia.php y en la clase 
 */
//function crearGuiaPorCancelar(guia, idservicio) {
//    $.ajax({
//        url: "../trafico/verificarGuia.php",
//        data: {'guia': guia,
//            'idservicio': idservicio,
//            'decision': '3'},
//        type: "POST",
//        success: function (data) {
//            var obj = JSON.parse(data);
//            if (obj) {
//                $("#tdMsjGuia").html("<div class='alert alert-danger'>Mensaje de revisi&oacute;n creado con &eacute;xito para la guia: " + guia + "</div>");
//            } else {
//                $("#tdMsjGuia").html("");
//            }
//        },
//        error: function (jqXHR, textStatus, errorThrown) {
//            alert("Ha ocurrido un error en AJAX en function crearGuiaPorCancelar(guia, idservicio) {...");
//        }
//    });
//}

function crearGuia(guia) {
    $.ajax({
        url: "../trafico/verificarGuia.php",
        data: {'guia': guia, 'decision': '2'},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj !== false) {
                if (obj.resultado === '0') {
                    $("#tdMsjGuia2").html("<div class='alert alert-success'>Se ha creado con éxito la guía " + guia + "</div>");
                } else {
                    $("#tdMsjGuia2").html("<div class='alert alert-danger'>Ha ocurrido un error durante el proceso de creaci&oacute;n de la gu&iacute;a</div>");
                }
            } else {
                alert("Ha ocurrido un error en AJAX en function crearGuia(guia) ajax...");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function crearGuia(guia,elemento,numeroElemento) ajax...");
        }
    });
}

function crearDir(tipo) {

    var direccion = null, telefono = null, ciudad = null, empresa = null;
    if (!validarDatosOrigen(tipo)) {

        if (tipo === 0) {
            $("#tdMsjDirOrg2").html("<div class='alert alert-danger'>Hace falta la dirección, el teléfono o la ciudad de origen. <strong>Por favor verifique</strong></div>");
        } else {
            $("#tdMsjDirDes2").html("<div class='alert alert-danger'>Hace falta la dirección, el teléfono o la ciudad de destino. <strong>Por favor verifique</strong></div>");
        }

    } else {

        if (tipo === 0) {
            $("#tdMsjDirOrg2").html("");
        } else {
            $("#tdMsjDirDes2").html("");
        }

        empresa = $("#listaClientes").val();
        if (tipo === 0) {
            direccion = $("#direccionorigen").val();
            telefono = $("#telefonoorigen").val();
            ciudad = $("#idciudadorigen").val();
        } else {
            direccion = $("#direcciondestino").val();
            telefono = $("#telefonodestino").val();
            ciudad = $("#idciudaddestino").val();
        }

        $.ajax({
            url: "../trafico/retornarAsesorEmpresa.php",
            data: {'tipo': tipo,
                'documento': empresa,
                'direccion': direccion,
                'telefono': telefono,
                'ciudad': ciudad,
                'opcion': '5'},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (tipo === 0) {
                    crearListaDirecciones(obj, tipo);
                    $("#trDirOrg").hide();
                    $("#telefonoorigen").val("0");
                    $("#idciudadorigen").val("0");
                } else {
                    crearListaDirecciones(obj, tipo);
                    $("#trDirDes").hide();
                    $("#telefonodestino").val("0");
                    $("#idciudaddestino").val("0");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function function function crearDir(tipo) {...");
            }
        });
    }
}

function validarDatosOrigen(tipo) {
    switch (tipo) {
        case 0:
            if ($("#direccionorigen").val() === '0' || $("#direccionorigen").val() === '' || $("#idciudadorigen").val() === '0') {
                return false;
            } else {
                return true;
            }
            break;
        case 1:
            if ($("#direcciondestino").val() === '0' || $("#direcciondestino").val() === '' || $("#idciudaddestino").val() === '0') {
                return false;
            } else {
                return true;
            }
            break;
    }
}

function mostrarBotonDireccion(tipo) {
    if (tipo === 0) {
        $("#telefonoorigen").val('0');
        $("#idciudadorigen").val('0');
        $("#trDirOrg").show();
        $("#tdMsjDirOrg").html("<td></td><td><input type='button' id='crearDireccion' value='Crear dirección origen' class='btn btn-warning btn-sm' onclick='crearDir(0);' /></td>");
        $("#divDirOrg").html("<input type='text' id='direccionorigen' name='direccionorigen' class='form-control input-sm' />");
    } else {
        $("#telefondestino").val('0');
        $("#idciudaddestino").val('0');
        $("#trDirDes").show();
        $("#tdMsjDirDes").html("<td></td><td><input type='button' id='crearDireccion' value='Crear dirección destino' class='btn btn-warning btn-sm' onclick='crearDir(1);' /></td>");
        $("#divDirDes").html("<input type='text' id='direcciondestino' name='direcciondestino' class='form-control input-sm' />");
    }
}

function crearPar() {

    if (revisarFormulario()) {

        //revisar que la guía no se encuentre repetida

        var bandera=0;

        //verificar que la posición .guia de serviciosGeneral no tenga 
        //un valor igual al que esta llenando la persona en el formulario 
        //del campo guia 
        for(var i=0;i<serviciosGeneral.length;i++){
            if(parseInt(serviciosGeneral[i].guia)===parseInt($("#guia").val())){
                bandera=1;
            }
        }

        if(bandera===1){
            $("#mensajes").html('<div class="alert alert-danger">Guía '+$("#guia").val()+' repetida en la toma del servicio. Por favor revise antes de grabar</div>');
        }else{

            $("#mensajes").html('');

        var placa = null, cedulaPropietario = null, cedulaConductor = null, fechaServicio = null,
                nit = null, asesor = null, guia = null, dirOri = null, dirDes = null, vlrDeclarado = null,
                vlrContratista = null, vlrEmpresa = null, servicio = [], nombreEmpresa = null, dirOrigen = null,
                dirDestino = null, valorAuxiliar = null, valorParqueadero = null, valorOtros = null, emp_cedula = null,
                notas = null, unidades = null, planilla = null, remision = null, factura = null, ordenCompra = null, valorManejo = null,
                porManejo = null, fechaHoraEntrega = null;

        placa = $("#listaPlacas").val();
        cedulaPropietario = $("#cedulaPropietario").val();
        cedulaConductor = $("#cedulaConductor").val();
        fechaServicio = $("#fechaServicio").val();
        nit = $("#listaClientes").val();
        asesor = $("#listaAsesores").val();
        dirOri = $("#dlDirOrg").val();
        guia = $("#guia").val();
        planilla = $("#planilla").val();
        dirDes = $("#dlDirDes").val();
        vlrDeclarado = $("#valorDeclarado").val();
        vlrContratista = $("#valorContratista").val();
        vlrEmpresa = $("#valorEmpresa").val();
        nombreEmpresa = $('#listaClientes option:selected').html();
        dirOrigen = $('#dlDirOrg option:selected').html();
        dirDestino = $('#dlDirDes option:selected').html();
        valorAuxiliar = $('#valorAuxiliar').val();
        valorParqueadero = 0;
        valorOtros = $('#valorOtros').val();
        valorManejo = $("#valorManejo").val();
        porManejo = $("#porManejo").val();
        emp_cedula = $("#emp_cedula").val();
        notas = $("#notas").val();
        unidades = $("#unidades").val();
        planilla = $("#planilla").val();
        remision = $("#remision").val();
        factura = $("#factura").val();
        ordenCompra = $("#ordenCompra").val();
        fechaHoraEntrega = $("#fechaHoraEntrega").val();

        if (isNaN(cedulaConductor)) {
            //tomar el valor del select en caso de que el vehiculo 
            //tenga mas de un conductor
            cedulaConductor = $('select[name="cedulaConductor"] option:selected').text();
        }

        //var arreglo =[];
        //var objeto={};

        //variable servicio de tipo objeto 
        servicio = {placa: placa,
            cedulaPropietario: cedulaPropietario,
            cedulaConductor: cedulaConductor,
            fechaServicio: fechaServicio,
            nit: nit,
            nombreEmpresa: nombreEmpresa,
            guia: guia,
            dirOrigen: dirOri,
            direOrigen: dirOrigen,
            direDestino: dirDestino,
            dirDestino: dirDes,
            vlrDeclarado: vlrDeclarado,
            vlrContratista: vlrContratista,
            vlrEmpresa: vlrEmpresa,
            valorAuxiliar: valorAuxiliar,
            valorParqueadero: valorParqueadero,
            valorOtros: valorOtros,
            emp_cedula: emp_cedula,
            notas: notas,
            unidades: unidades,
            planilla: planilla,
            remision: remision,
            factura: factura,
            ordenCompra: ordenCompra,
            valorManejo: valorManejo,
            porManejo: porManejo,
            fechaHoraEntrega: fechaHoraEntrega};
                    
        //se agregan valores a la variable de tipo arreglo serviciosGeneral
        serviciosGeneral.push(servicio);
        formarTablaServicios();
        $("#divConceptos").html("");
        $("#totalOtrosConceptos").val("0");
    }
    } else {
        revisarFormulario();
    }

}

function retirarServicio(valor) {

    /*
     * Borrar los otros valores de la guia del arreglo otrosValores     
     */
    var guia = parseInt(serviciosGeneral[valor.id].guia);
    var longitudInicial = otrosValores.length;

    for (var i = 0; i < longitudInicial; i++) {
        if (otrosValores[i][2] === guia) {
            otrosValores.splice(i, 1);
            i = 0;
        }
        longitudInicial = otrosValores.length;
    }

    serviciosGeneral.splice(valor.id, 1);

    $("#valorcontratista").val('0');
    $("#auxiliarCarga").val('0');
    //$("#parqueadero").val('0');
    $("#otros").val('0');
    $("#valortotal").val('0');
    $("#valorfacturar").val('0');

    if (serviciosGeneral.length === 0) {
        $("#mostrarParadas").html('');
        $("#mensajesGenerales").html('');
        $("#porGanancia").html('');
    } else {
        formarTablaServicios();
    }
}

function formarTablaServicios() {

    var valorAuxiliar = null, valorParqueadero = null, valorOtros = null, vlrEmpresa = null, costoTotalDespacho = null, porGanancia = null,
            totalCosto = null, porAnticipo = null, saldoAnticipo = null, vlrMan = null, arreglo;

    vlrContratista = 0;

    tabla = "<table class='table table-striped' ><thead>";
    tabla += "<tr><th>No.</th><th>Guía</th><th>Unidades</th><th>Planilla</th><th>Remisión</th><th>Factura</th><th>Orden de compra</th><th>Fecha Servicio</th><th>Fecha Hora Entrega</th><th>Placa</th><th>C.C. Prop.</th><th>C.C. Cond.</th>\n\
<th>EMPR.</th><th>Dir. Orig.</th><th>Dir. Dest.</th><th>Vlr. Man.</th><th>Vlr. Aux.</th><th>Vlr. Parq.</th><th>Vlr. Otros</th><th>Vlr Decl.</th><th>Vlr Cont.</th><th>Vlr Empr.</th><th>Vlr Tot.</th><th></th></tr></thead>";
    for (var i = 0, max = serviciosGeneral.length; i < max; i++) {

        //
        if ($.inArray(serviciosGeneral[i].nit, nits) === -1) {
            nits[i] = serviciosGeneral[i].nit;
        }

        totalCosto = parseInt(serviciosGeneral[i].valorAuxiliar) + parseInt(serviciosGeneral[i].valorParqueadero) + parseInt(serviciosGeneral[i].valorOtros) + parseInt(serviciosGeneral[i].vlrEmpresa) + parseInt(serviciosGeneral[i].valorManejo);

        tabla += "<tr><td>" + i + "</td><td><strong>" + serviciosGeneral[i].guia + "</strong></td>\n\
<td>" + serviciosGeneral[i].unidades + "</td><td>" + serviciosGeneral[i].planilla + "</td>\n\
<td>" + serviciosGeneral[i].remision + "</td><td>" + serviciosGeneral[i].factura + "</td>\n\
<td>" + serviciosGeneral[i].ordenCompra + "</td>\n\
<td>" + serviciosGeneral[i].fechaServicio + "</td><td>" + serviciosGeneral[i].fechaHoraEntrega + "</td>\n\
<td>" + serviciosGeneral[i].placa + "</td><td>" + serviciosGeneral[i].cedulaPropietario + "</td>\n\
<td>" + serviciosGeneral[i].cedulaConductor + "</td>\n\
<td>" + serviciosGeneral[i].nombreEmpresa + "</td><td>" + serviciosGeneral[i].direOrigen + "</td><td>" + serviciosGeneral[i].direDestino + "</td>\n\
<td>" + serviciosGeneral[i].valorManejo + "</td>\n\
<td>" + serviciosGeneral[i].valorAuxiliar + "</td><td>" + serviciosGeneral[i].valorParqueadero + "</td><td>" + serviciosGeneral[i].valorOtros + "</td>\n\
<td>" + serviciosGeneral[i].vlrDeclarado + "</td><td>" + serviciosGeneral[i].vlrContratista + "</td><td>" + serviciosGeneral[i].vlrEmpresa + "</td>\n\
<td><strong>" + totalCosto + "</strong></td>\n\
<td><input type='button' id='" + i + "' onclick='retirarServicio(this)' value='X' /></td></tr>";
        vlrContratista += parseInt(serviciosGeneral[i].vlrContratista);
        vlrEmpresa += parseInt(serviciosGeneral[i].vlrEmpresa);
        valorAuxiliar += parseInt(serviciosGeneral[i].valorAuxiliar);
        valorParqueadero += 0;
        valorOtros += parseInt(serviciosGeneral[i].valorOtros);
        vlrMan += parseInt(serviciosGeneral[i].valorManejo);
        costoTotalDespacho += totalCosto;
    }

    totalOtrosCostos = valorOtros;

    tabla += "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td><stron>" + vlrMan + "</strong></td><td><strong>" + valorAuxiliar + "</strong></td>\n\
<td><strong>" + valorParqueadero + "</strong></td><td><strong>" + valorOtros + "</strong></td><td></td><td><strong>" + vlrContratista + "</strong></td><td><strong>" + vlrEmpresa + "</strong></td><td><strong>" + costoTotalDespacho + "</strong></td></tr>";

    porAnticipo = (vlrContratista * 60) / 100;

    tabla += "<tr id='valorAnticipo' ><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>\n\
<td></td><td></td></td><td></td><td><td>Vlr. Ant.</td><td colspan='2'><input type='text' id='vlrAnticipo' name='vlrAnticipo' class='form-control input-sm' size='20' value='" + porAnticipo + "' onchange='cambiarVlrAnticipo();' /></td></tr>";

    saldoAnticipo = vlrContratista - porAnticipo;

    tabla += "<tr id='valorSaldo' ><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>\n\
<td></td><td></td><td></td><td></td><td>Saldo</td><td colspan='2'><input type='text' id='vlrSaldo' name='vlrSaldo' class='form-control input-sm' size='20' value='" + saldoAnticipo + "' /></td></tr>";

    tabla += "</table>";

    $("#mostrarParadas").html(tabla);
    $("#valorAnticipo").hide();
    $("#valorSaldo").hide();

    porGanancia = 100 - ((vlrContratista * 100) / costoTotalDespacho);
    porcentajeGanancia(porGanancia);

    $("#listaClientes").val('0');
    $("#listaAsesores").val('0');
    $("#dlDirOrg").val('0');
    $("#guia").val('0');
    $("#planilla").val('0');
    $("#unidades").val('0');
    $("#remision").val('0');
    $("#factura").val('0');
    $("#ordenCompra").val('0');
    $("#dlDirDes").val('0');
    $("#valorContratista").val('0');
    $("#valorEmpresa").val('0');
    $("#porManejo").val('0');
    $("#totVal1").val('0');
    $("#valorManejo").val('0');
    $("#telefonoorigen").val('0');
    $("#idciudadorigen").val('0');
    $("#telefonodestino").val('0');
    $("#idciudaddestino").val('0');
    $("#listaClientes").focus();
    $("#porGanancia").html("");
    $('#valorAuxiliar').val('0');
    $('#valorParqueadero').val('0');
    $('#valorOtros').val('0');
    $('#nitEmpresa').val('0');
    $("#vlrTot2").val('0');
    $("#notas").val('');
    $("#valorDeclarado").val('200000');
    //
    establecerFechaHoraEntrega();
}

function establecerFechaHoraEntrega() {
    var fechaActual = new Date();

    var mes = parseInt(fechaActual.getMonth() + 1);

    if (mes < 10) {
        mes = "0" + mes;
    }

    $("#fechaHoraEntrega").val(fechaActual.getFullYear() + "-" + mes + "-" + fechaActual.getDate() + "T23:59");
}

function porcentajeGanancia(porGanancia) {

    var clase = null;
    var mensaje = null;

    if (porGanancia <= 0) {
        clase = 'danger';
        mensaje = "El porcentaje de ganancia es: <strong>" + porGanancia.toFixed(2) + "% Esta seguro/a de que estan bien?</strong>";
    } else if (porGanancia > 0) {
        clase = 'success';
        mensaje = "El porcentaje de ganancia es: <strong>" + porGanancia.toFixed(2) + "%</strong>";
    } else {
        clase = 'danger';
        mensaje = 'Los valores a pagar a contratista (<strong>VLR. CONT.</strong>) y/o cobrar a empresa (<strong>VLR. EMP.</strong>) están errados. <strong>Por favor verifique</strong>';
        $("#valorContratista").focus();
    }
    $("#porGanancia").html("<div class='alert alert-dismissible alert-" + clase + "'>" + mensaje + "</div>");

}

function revisarFormulario(data,opcion) {

    /*
    var revision=0;
    if(opcion===1){
        if ($("#listaPlacas").val() === '0') {
            $("#trPlacasPropietario").show();
            $("#tdMsjPlacas").html("<div class='alert alert-dismissible alert-danger'>Placa inválida. <strong>Por favor seleccione una placa</strong></div>");
            $("#listaPlacas").focus();    
            revision=+1;
        }        
        if ($("#cedulaPropietario").val() === '0') {
            $("#trPlacasPropietario").show();
            $("#tdMsjPlacas").html("<div class='alert alert-dismissible alert-danger'>C&eacute;dula de propietario inválida. <strong>Por favor seleccione una placa</strong></div>");
            $("#listaPlacas").focus();
            revision=+1;
        }
    }
    return revision;
    */

    if ($("#listaPlacas").val() === '0') {
        $("#trPlacasPropietario").show();
        $("#tdMsjPlacas").html("<div class='alert alert-dismissible alert-danger'>Placa inválida. <strong>Por favor seleccione una placa</strong></div>");
        $("#listaPlacas").focus();

        return false;
    } else if ($("#cedulaPropietario").val() === '0') {
        $("#trPlacasPropietario").show();
        $("#tdMsjPlacas").html("<div class='alert alert-dismissible alert-danger'>C&eacute;dula de propietario inválida. <strong>Por favor seleccione una placa</strong></div>");
        $("#listaPlacas").focus();
        return false;
    } else if ($("#cedulaConductor").val() === '0') {
        $("#trPlacasPropietario").show();
        $("#tdMsjPlacas").html("<div class='alert alert-dismissible alert-danger'>C&eacute;dula de conductor inválida. <strong>Por favor seleccione una placa</strong></div>");
        $("#listaPlacas").focus();
        return false;
    } else if ($("#listaClientes").val() === '0') {
        $("#trEmpresaAsesor").show();
        $("#tdMsjEmpresa").html("<div class='alert alert-dismissible alert-danger'>Empresa inválida. <strong>Por favor seleccione una empresa</strong></div>");
        $("#listaClientes").focus();
        return false;
    } else if ($("#listaAsesores").val() === '0') {
        $("#trEmpresaAsesor").show();
        $("#tdMsjEmpresa").html("<div class='alert alert-dismissible alert-danger'>Asesor inválido. <strong>Por favor seleccione una empresa. Si la empresa no tiene asesor asociado, por favor realice la gestión</strong></div>");
        $("#listaClientes").focus();
        return false;
    } else if ($("#fechaHoraEntrega").val() === '0000-00-00 00:00:00') {
        $("#trEmpresaAsesor").show();
        $("#tdMsjEmpresa").html("<div class='alert alert-dismissible alert-danger'>Asesor inválido. <strong>Por favor seleccione seleccione una fecha y hora v&acute;lidos</strong></div>");
        $("#fechaHoraEntrega").focus();
        return false;
    } else if ($("#guia").val() === '0') {
        $("#trEmpresaAsesor").show();
        $("#tdMsjEmpresa").html("<div class='alert alert-dismissible alert-danger'>Asesor inválido. <strong>Por favor seleccione una empresa. Si la empresa no tiene asesor asociado, por favor haga la gestión</strong></div>");
        $("#listaClientes").focus();
        return false;
    } else if ($("#idciudadorigen").val() === '0') {
        $("#trDirOrg").show();
        $("#tdMsjDirOrg").html("<div class='alert alert-dismissible alert-danger'>Dirección de origen inválida. <strong>Por favor seleccione una empresa. Si la empresa no tiene dirección de origen creada, por favor realice la gestión</strong></div>");
        $("#idciudadorigen").focus();
        return false;
    } else if ($("#idciudaddestino").val() === '0') {
        $("#trDirDes").show();
        $("#tdMsjDirDes").html("<div class='alert alert-dismissible alert-danger'>Dirección de destino inválida. <strong>Por favor seleccione una empresa. Si la empresa no tiene dirección de origen creada, por favor realice la gestión</strong></div>");
        $("#idciudaddestino").focus();
        return false;
    } else if ($("#valorDeclarado").val() === '0') {
        $("#porGanancia").show();
        $("#porGanancia").html("<div class='alert alert-dismissible alert-danger'>Valor declarado inválido. <strong>Por favor digite un valor válido</strong></div>");
        $("#valorDeclarado").focus();
        return false;
    } else if ($("#valorContratista").val() === '0') {
        $("#porGanancia").show();
        $("#porGanancia").html("<div class='alert alert-dismissible alert-danger'>Valor contratista inválido. <strong>Por favor digite un valor válido</strong></div>");
        $("#valorContratista").focus();
        return false;
    } else if ($("#valorEmpresa").val() === '0') {
        $("#porGanancia").show();
        $("#porGanancia").html("<div class='alert alert-dismissible alert-danger'>Valor empresa inválido. <strong>Por favor digite un valor válido</strong></div>");
        $("#valorEmpresa").focus();
    } else {
        $("#trPlacasPropietario").hide();
        $("#trEmpresaAsesor").hide();
        $("#trDirOrg").hide();
        $("#trDirDes").hide();
        $("#porGanancia").html("");
        return true;
    }
}

function crearServicio(datosServicio, cantidad) {

    var mensaje = null;
    var ant = null;

    cambiarVlrAnticipo();

    if ($('#chkAnticipo').is(':checked')) {
        mensaje = "SERVICIO CON ANTICIPO?";
        ant = 1;
    } else {
        mensaje = "SERVICIO SIN ANTICIPO?";
        ant = 0;
    }

    if (confirm("Confirmar crear \n" + mensaje)) {
        $.ajax({
            url: "../trafico/Servicios.php",
            data: {'datosServicio': datosServicio,
                'otrosValores': otrosValores,
                'conAnticipo': ant,
                'ant_sal': retornoAntSal,
                'nombreEmpleado': $("#nombreEmpleado").val(),
                'caso': '4'},
            type: "POST",
            //success: function (data)
            //success: function (reponse)
            success: function (respuesta) {
                var obj = JSON.parse(respuesta);
                if (parseInt(obj.primeraParte) === 0 || parseInt(obj.segundaParte) === 0) {
                    alert("Oops. Algo salio mal. Vamos a intentar borrar los datos del servicio y volvemos a empezar\nSi este mensaje continua mostrandose, por favor informe!");
                    borrarServicio(obj.idservicio);
                } else {
                    var idempleado = $("#emp_cedula").val();
                    var motivo = '';

                    if (obj.placa[0]["reportar_novedad"] === '1') {

                        if (parseInt($("#noSatPlaca").val()) > 0) {
                            motivo = "La placa: " + placaA + " ha sido asignada al servicio: " + obj.idservicio + ". La placa tiene servicios No Satisfactorios";
                            $.ajax({
                                url: "../trafico/ServiciosPorCancelar.php",
                                data: {'motivo': motivo,
                                    'idempleado': idempleado,
                                    'idservicio': obj.idservicio,
                                    'caso': '2'},
                                type: "POST",
                                success: function (data) {
                                    //.log(data);
                                    //var obj = JSON.parse(data);
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.log("Ha ocurrido un error en AJAX en if (parseInt($('#noSatPlaca').val()) > 0) {...");
                                }
                            });
                        }
                    }

                    motivo = '';

                    if (obj.propietario[0]["reportar_novedad"] === '1') {

                        if (parseInt($("#noSatPropietario").val()) > 0) {
                            motivo = "El n&uacute;mero de c&eacute;dula : " + cedulaPropietarioA + " del propietario ha sido asignada al servicio: " + obj.idservicio + ". La c&eacute;dula tiene servicios No Satisfactorios";
                            $.ajax({
                                url: "../trafico/ServiciosPorCancelar.php",
                                data: {'motivo': motivo,
                                    'idempleado': idempleado,
                                    'idservicio': obj.idservicio,
                                    'caso': '2'},
                                type: "POST",
                                success: function (data) {
                                    //console.log(data);
                                    //var obj = JSON.parse(data);
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.log("Ha ocurrido un error en AJAX en if (parseInt($('#noSatPropietario').val()) > 0) {...");
                                }
                            });
                        }
                    }

                    motivo = '';

                    if (obj.conductor[0]["reportar_novedad"] === '1') {
                        if (parseInt($("#noSatConductor").val()) > 0) {
                            cedulaConductorA = $("#cedulaConductor").val();
                            motivo = "El n&uacute;mero de c&eacute;dula : " + cedulaConductorA + " del conductor ha sido asignada al servicio: " + obj.idservicio + ". La c&eacute;dula tiene servicios No Satisfactorios";
                            $.ajax({
                                url: "../trafico/ServiciosPorCancelar.php",
                                data: {'motivo': motivo,
                                    'idempleado': idempleado,
                                    'idservicio': obj.idservicio,
                                    'caso': '2'},
                                type: "POST",
                                success: function (data) {
                                    //console.log(data);
                                    //var obj = JSON.parse(data);
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    console.log("Ha ocurrido un error en AJAX en if (parseInt($('#noSatConductor').val()) > 0) {...");
                                }
                            });
                        }
                    }

                    crearAnticipo(obj.idservicio, ant);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function crearServicio(datosServicio) {...");
            }
        });
    }
}

function borrarServicio(idservicio) {
    $.ajax({
        url: "../trafico/Servicios.php",
        data: {'idservicio': idservicio, 'opcion': '2'},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj === 1) {
                location.reload();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function borrarServicio(idservicio) {...");
        }
    });
}

function cambiarVlrAnticipo() {

    var vlrAnticipo = parseInt($("#vlrAnticipo").val());
    var saldo = vlrContratista - vlrAnticipo;

    if (saldo <= 0) {
        $("#vlrAnticipo").val((vlrContratista * 60) / 100);
        $("#vlrSaldo").val(vlrContratista - parseInt($("#vlrAnticipo").val()));
        $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-danger'>El valor del saldo del servicio con anticipo no debe ser cero o menor. Por favor verifique</div>");
        $("#vlrAnticipo").focus();
        retornoAntSal = {saldo: 0, vlrAnticipo: 0};
    } else {
        $("#vlrSaldo").val(saldo);
        retornoAntSal = {saldo: saldo, vlrAnticipo: parseInt($("#vlrAnticipo").val())};
    }
}

function crearAnticipo(idservicio, conSinAnt) {
    if (conSinAnt === 1) {
        window.location.href = '../trafico/generaranticipotres.php?idservicio=' + idservicio;
        $("#mensajes").html("<div class='alert alert-dismissible alert-success'>El proceso de creaci&oacute;n del pdf ha sido &eacute;xitoso.<br>El pdf del anticipo se ha descargado, puede presionar el bot&oacute;n MEN&Uacute; PRINCIPAL</div>");
        $('#botonCrearServicio').hide();
    } else {
        window.location.href = '../modulos/index.php';
    }
}

function retornarSumaValores() {
    //return parseInt($("#valorAuxiliar").val()) + parseInt($("#valorParqueadero").val()) + parseInt($("#valorOtros").val()) + parseInt($("#valorContratista").val()) + parseInt($("#valorManejo").val());
    return parseInt($("#valorAuxiliar").val()) + parseInt($("#valorOtros").val()) + parseInt($("#valorContratista").val()) + parseInt($("#valorManejo").val());
}

function crearListaDirecciones(obj, tipo) {
    switch (tipo) {
        case 0:
            listaDirecciones = "<select id='dlDirOrg' class='form-control' onchange='mostrarTelCiu(this,0);' >";
            listaDirecciones += "<option value='-1'>...</option>";
            for (var i = 0, max = obj.length; i < max; i++) {
                listaDirecciones += "<option value='" + obj[i].iddireccion + "'>" + obj[i].direccion + "</option>";
            }
            listaDirecciones += "<option value='0'>Crear dirección</option>";
            listaDirecciones += "</select>";
            $("#divDirOrg").html(listaDirecciones);
            $("#trDirOrg").hide();
            break;
        case 1:
            listaDirecciones = "<select id='dlDirDes' class='form-control' onchange='mostrarTelCiu(this,1);' >";
            listaDirecciones += "<option value='-1'>...</option>";
            for (var i = 0, max = obj.length; i < max; i++) {
                listaDirecciones += "<option value='" + obj[i].iddireccion + "'>" + obj[i].direccion + "</option>";
            }
            listaDirecciones += "<option value='0'>Crear dirección</option>";
            listaDirecciones += "</select>";
            $("#divDirDes").html(listaDirecciones);
            $("#trDirDes").hide();

            break;
        case 2:
            listaDirecciones = "<select id='dlDirDes' class='form-control'>";
            listaDirecciones += "<option value='-1'>...</option>";
            for (var i = 0, max = obj.length; i < max; i++) {
                listaDirecciones += "<option value='" + obj[i].iddireccion + "'>" + obj[i].direccion + "</option>";
            }
            //listaDirecciones += "<option value='0'>Crear dirección</option>";
            listaDirecciones += "</select>";
            $("#divDirDes").html(listaDirecciones);

            break;
    }
}

function retornarDireccionesDestino(nit, opcion) {
    $.ajax({
        url: "../trafico/retornarAsesorEmpresa.php",
        data: {'nit': nit, 'opcion': '3', 'tipo': 1},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.length === 0) {
                $("#trDirDes").show();
                $("#tdMsjDirDes2").html("<div class='alert alert-danger'>No se encuentran direcciones de destino para este cliente. Por favor digitela</div>");
                if (opcion === 1) {
                    mostrarBotonDireccion(1);
                }
            } else {
                crearListaDirecciones(obj, 1);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function retornarDireccionesOrigen(nit) {...");
        }
    });
}

function retornaDirPorCiuCli(nit, idciudad) {
    $.ajax({
        url: "../trafico/Direcciones.php",
        data: {'nit': nit, 'caso': '4', 'idciudad': idciudad},
        type: "POST",
        success: function (data) {
            //console.log(data);
            var obj = JSON.parse(data);
            if (obj.length === 0) {
                $("#divDirDes").html('<input type="text" id="direcciondestino" value="" title="Digitar dirección nueva aquí" class="form-control" onblur="crearDireccionDestino()" />');
            } else {
                crearListaDirecciones(obj, 2);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function retornaDirPorCiuCli(nit, idciudad) {...");
        }
    });
}


function retornarDireccionesOrigen(nit) {

    $.ajax({
        url: "../trafico/retornarAsesorEmpresa.php",
        data: {'nit': nit, 'opcion': '3', 'tipo': 0},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.length === 0) {
                $("#trDirOrg").show();
                $("#tdMsjDirOrg2").html("<div class='alert alert-danger'>No se encuentran direcciones de origen para este cliente. Por favor digitela</div>");
                mostrarBotonDireccion(0);
            } else {
                crearListaDirecciones(obj, 0);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function retornarDireccionesOrigen(nit) {...");
        }
    });
}

function mostrarTelCiu(valor, tipo) {

    var a = "#" + valor.id;
    var iddireccion = $(a).val();
    var tp = null;
    if (tipo === 1) {
        tp = 'origen';
    } else {
        tp = 'destino';
    }

    switch (iddireccion) {
        case '-1':
            $("#trDirOrg").show();
            $("#tdMsjDirOrg").html("<div class='alert alert-danger'>Debe seleccionar una dirección de " + tp + "</div>");
            $("#dlDirOrg").focus();
            $("#telefonoorigen").val('0');
            $("#idciudadorigen").val('0');
            break;
        case '0':
            if (confirm("Crear una direccion de " + tp + " para " + $('#listaClientes option:selected').html() + "?")) {
                mostrarBotonDireccion(tipo);
            }
            break;
        default:
            $("#trGuiaPlanillaOtro").hide();
            $("#trDirOrg").hide();
            $("#tdMsjDirOrg").html("");
            $.ajax({
                url: "../trafico/retornarAsesorEmpresa.php",
                data: {'iddireccion': iddireccion, 'opcion': '4'},
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    mostrarTelefonoCiudad(obj, tipo);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX en function function mostrarTelCiu(valor, tipo) {...default");
                }
            });
            break;
    }
}

function mostrarTelefonoCiudad(obj, tipo) {
    if (tipo === 0) {
        $("#telefonoorigen").val(obj[0].telefono);
        $("#idciudadorigen").val(obj[0].ciudad);
    } else {
        $("#telefonodestino").val(obj[0].telefono);
        $("#idciudaddestino").val(obj[0].ciudad);
    }
}

function retornarEntregas(idservicio) {
    $.ajax({
        url: "../trafico/Entregas.php",
        data: {'caso': '2',
            'idservicio': idservicio},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            var total = 0;
            if (obj.length > 0) {
                $("#datosFormulario").hide();
                $("#divEntregas").hide();
                var mensaje = "<table class='table table-hover'><tr><td>Entregas relacionadas con gu&iacute;a: " + obj[0].numeroGuia + "</td></table>";
                mensaje += "<table class='table table-hover' ><thead><tr><th>Gu&iacute;a de entrega</th><th>Direcci&oacute;n destino</th><th>Unidades</th><th>Planilla</th><th>Remisi&oacute;n</th><th>Factura</th><th>Orden de compra</th><th>Valor</th></tr></thead><tbody>";
                $.each(obj, function (i, valor) {
                    mensaje += "<tr><td>" + valor.guiaEntrega + "</td><td>" + valor.direccionDestino + "-" + valor.ciudadDestino + "</td><td>" + valor.unidades + "</td><td>" + valor.planilla + "</td><td>" + valor.remision + "</td><td>" + valor.factura + "</td><td>" + valor.ordenCompra + "</td><td>" + formatNumber.new(valor.valorCobrado) + "</td><tr>";
                    total += parseInt(valor.valorCobrado);
                });
                if ($("#borrado").val() === '1' || $("#borrado").val() === '2') {
                    var borrado = $("#borrado").val();
                    mensaje += "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>" + formatNumber.new(total) + "</td><tr>";
                    mensaje += "<tr><td><input type='button' id='" + idservicio + "' value='Borrar entregas' onclick='borrarEntregas(this," + borrado + ")' class='btn btn-success' /></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                }
                mensaje += "</tbody></table>";
                $("#entregasAnteriores").html(mensaje);
                $("#botonGrabarEntregas").attr("disabled", true);
            } else {
                $("#datosFormulario").show();
                $("#divEntregas").show();
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('function retornarEntregas(idservicio) {...');
        }
    });
}

function borrarEntregas(valor, condicion) {
    var id = valor.id;
    if (confirm("Confirmar borrado de entregas para servicio: " + id)) {
        $.ajax({
            url: "../trafico/Entregas.php",
            data: {'caso': '3',
                'idservicio': id},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    switch (condicion) {
                        case 1:
                            borrarFormulario(3);
                            break;
                        case 2:
                            $("#entregasAnteriores").html("");
                            break;
                    }
                } else {
                    $("#mensajes").html('<div class="alert alert-dismissible alert-success">Ha fallado el borrado de las entregas. Por favor presione F5 e intente nuevamente.<br>Si la falla persiste por favor informe</div>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert('function borrarEntregas(valor) {...');
            }
        });
    }
}

function crearDireccion() {
    $.ajax({
        url: "../trafico/retornarAsesorEmpresa.php",
        data: {'tipo': 0,
            'documento': $("#nitEmpresa").val(),
            'direccion': $("#direcciondestino").val(),
            'telefono': 0,
            'ciudad': $("#listaMunicipios").val(),
            'opcion': '5'},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.length === 0) {
                $("#mensajes").html('<div class="alert alert-dismissible alert-danger">La creación de la dirección ha fallado. Por favor presione F5 e inicie nuevamente</div>');
            } else {
                retornarDireccionesOrigen($("nitEmpresa").val());
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function crearDireccion() {...");
        }
    });
}

function agregarSobreCosto(valor, opcion) {

    var numeroGuia = valor.id;
    numeroGuia = numeroGuia.substr(2, numeroGuia.length);
    var idservicio = $("#idservicio").val();
    var concepto = $("#c-" + numeroGuia).val();
    var valor = $("#v-" + numeroGuia).val();

    if (concepto === '' || valor === '0') {
        alert("No hay concepto válido o el valor se encuentra en 0. Por favor revise");
    } else {
        $.ajax({
            url: "../trafico/OtrosCostos.php",
            data: {'caso': '1',
                'numeroGuia': numeroGuia,
                'idservicio': idservicio,
                'concepto': concepto,
                'valor': valor},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    if (opcion === 1) {
                        consultarPorIdServicio(idservicio, 1);
                    }
                    if (opcion === 2) {
                        location.reload();
                    }
                } else {
                    alert("Ha ocurrido un error a nivel del servidor, por favor presione F5 e intentlo nuevamente. Si la falla persiste por favor informe");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function function agregarSobreCosto(valor){...");
            }
        });
    }
}

function cambiarValorCobrado(elemento, option) {
    var id = "#" + elemento.id;
    $(id).number(true);
    var valor = $(id).val();
    var guia = id.substring(3, id.length);
    var idservicio = $("#idservicio").val();

    $.ajax({
        url: "../trafico/Servicios.php",
        data: {'caso': '8',
            guia: guia,
            valor: valor,
            idservicio: idservicio},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (option === 0) {
                if (obj === 3) {
                    consultarPorIdServicio(idservicio, 1);
                } else {
                    alert("Ha fallado el cambio del valor cobrado\nfunction cambiarValorCobrado(elemento) {...respuesta desde servidor...}else{...\nPor favor informe");
                }
            }
            if (option === 1) {
                if (obj === 3) {
                    location.reload();
                } else {
                    alert("Ha fallado el cambio del valor cobrado\nfunction cambiarValorCobrado(elemento) {...respuesta desde servidor...}else{...\nPor favor informe");
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function cambiarValorCobrado(elemento) {...");
        }
    });
}

function cambiarValorPagado(elemento, opcion) {
    var id = "#" + elemento.id;
    $(id).number(true);
    var valor = $(id).val();
    var guia = id.substring(3, id.length);
    var idservicio = $("#idservicio").val();

    $.ajax({
        url: "../trafico/Servicios.php",
        data: {'caso': '9',
            guia: guia,
            valor: valor,
            idservicio: idservicio},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (opcion === 0) {
                if (obj === 4) {
                    consultarPorIdServicio(idservicio, 1);
                } else {
                    alert("Ha fallado el cambio del valor pagado\nfunction function cambiarValorPagado(elemento) {...respuesta desde servidor...}else{...\nPor favor informe");
                }
            }

            if (opcion === 1) {
                if (obj === 4) {
                    location.reload();
                } else {
                    alert("Ha fallado el cambio del valor pagado\nfunction function cambiarValorPagado(elemento) {...respuesta desde servidor...}else{...\nPor favor informe");
                }
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function cambiarValorPagado(elemento,opcion) {...");
        }
    });
}

function retornarFecha() {
    date = new Date();
    dia = date.getDate();
    mes = date.getMonth() + 1;
    hora = date.getHours();
    minuto = date.getMinutes();

    if (mes <= 9) {
        mes = "0" + mes;
    }

    if (dia <= 9) {
        dia = "0" + dia;
    }

    anio = date.getFullYear();
    fechaActual = anio + "-" + mes + "-" + dia + " " + hora + ":" + minuto;
    return fechaActual;
}

function consultarPorAnticipo(anticipo) {
    $.ajax({
        url: "../trafico/Servicios.php",
        data: {'caso': '7',
            anticipo: anticipo},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj !== false) {
                if (Object.keys(obj).length === 0) {
                    $("#mensajes").html('<div class="alert alert-danger">Con el n&uacute;mero de anticipo: <strong>' + anticipo + '</strong> no se registran datos <br />Por favor verifique</div>');
                    $("#mensajesGenerales").html('');
                } else {
                    consultarPorIdServicio(obj[0].idservicio, 1);
                }
            } else {
                alert("Ha fallado la consulta de los datos del anticipo\nfunction consultarPorAnticipo(anticipo) {...respuesta desde servidor...}else{...\nLa página será recargada, por favor presione F5 e intente nuevamente.\nGracias!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function consultarPorAnticipo(anticipo) { {...");
        }
    });
}

function mostrarOtrosCostos(numeroGuia, color) {
    var valoresCrear = '<table class="table">';
    $.ajax({
        url: "../trafico/OtrosCostos.php",
        data: {'caso': '2',
            numeroGuia: numeroGuia},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.length > 0) {
                valoresCrear += "<tr bgcolor='" + color + "'><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Detalle de otros costos</td>\n\
<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                for (var i = 0; i < obj.length; i++) {
                    valoresCrear += "<tr bgcolor='" + color + "' ><td></td><td></td><td></td><td></td><td></td>\n\
<td></td><td></td><td>" + obj[i].concepto + "</td><td>" + formatNumber.new(obj[i].valor) + "</td>\n\
<td><input type='button' id='" + obj[i].id + "' value='Borrar' onclick='borrarSobreCosto(this)'/></td><td></td>\n\
<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                }
                valoresCrear += "<tr bgcolor='" + color + "' ><td></td><td colspan='6'>Sobrecosto a gu&iacute;a \n\
" + numeroGuia + "</td><td><input size='10' type='text' placeholder='Motivo del costo' id='c-" + numeroGuia + "' /></td><td><input style='width: 7em;' type='number' id='v-" + numeroGuia + "' value='0' /></td>\n\
<td><input type='button' value='Agregar' id='b-" + numeroGuia + "' onclick='agregarSobreCosto(this,1)'/></td><td></td>\n\
<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                $("#otrosCostos-" + numeroGuia).html(valoresCrear);
            } else {
                valoresCrear += "<tr bgcolor='" + color + "' ><td></td><td colspan='6'>Sobrecosto a gu&iacute;a \n\
" + numeroGuia + "</td><td><input size='10' type='text' placeholder='Motivo del costo' id='c-" + numeroGuia + "' /></td><td><input style='width: 7em;' type='number' id='v-" + numeroGuia + "' value='0' /></td>\n\
<td><input type='button' value='Agregar' id='b-" + numeroGuia + "' onclick='agregarSobreCosto(this,1)'/></td><td></td>\n\
<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                $("#otrosCostos-" + numeroGuia).html(valoresCrear);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function mostrarOtrosCostos(numeroGuia){...");
        }
    });
}

function borrarSobreCosto(valor) {
    var id = valor.id;
    var idservicio = $("#idservicio").val();
    $.ajax({
        url: "../trafico/OtrosCostos.php",
        data: {'caso': '3',
            'id': id
        },
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj === 1) {
                consultarPorIdServicio(idservicio, 1);
            } else {
                alert("Ha ocurrido un error, por favor presione F5 e intentlo nuevamente. Si la falla persiste por favor informe");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function borrarSobreCosto(valor){...");
        }
    });
}


function pintarTabla(objeto, option) {
    //msj mensaje
    $("#idservicio").val(objeto.servicio[0].idservicio);
    var totalCobrado = 0, totalPagado = 0, totalAnticipo = 0, porcentajeGanancia = 0, diferencia = 0, estado = '', colspan = '19', 
    size = '7', listaMotivos = '',totalAuxiliares = 0, totalParqueaderos = 0, totalOtros = 0, valorTotalCostos = 0, planRuta = 0, 
    fecha = '', imagen = null,msjCaliPlaca, msjCaliPropi, msjCaliCond, reactivarCuenta, fechaFactura, fechaPago, fchFtr, 
    valorManejo = 0, totalVlrMan = 0,num = 0, colores = ["#FFE7E7", "#E7FFF7", "#FFFFE7", "#F7FFE7", "#E7FFE7", "#FFF7E7"], aleatorio, modificar = 1;
    listaMotivos = '<select class="form-control" id="motivosCancelacion">';
    listaMotivos += '<option selected="">...</option>';
    listaMotivos += '<option value="Error de digitacion">Error de digitaci&oacute;n</option>';
    listaMotivos += '<option value="Error de sistema">Error de sistema</option>';
    listaMotivos += '<option value="Cambio vehiculo">Cambio veh&iacute;culo</option>';
    listaMotivos += '<option value="Cliente cancela servicio">Cliente cancela servicio</option>';
    listaMotivos += '</select>';

    tabla = '<div class="panel-body" id="divServicio">';
    tabla += '<table id="datosEmpleado" class="table" >';

    if (objeto.mensajesCancelacion.length !== 0) {
        tabla += '<tr><th colspan="' + colspan + '">Mensajes de cancelaci&oacute;n</th></tr>';
        tabla += '<tr><td colspan="5">Fecha</td><td colspan="5" >Empleado</td><td colspan="6" >Mensaje</td></tr>';
        for (var i = 0; i < objeto.mensajesCancelacion.length; i++) {
            fecha = objeto.mensajesCancelacion[i].fecha.substr(0, objeto.mensajesCancelacion[i].fecha.length - 8);
            tabla += '<tr><td colspan="5" >' + fecha + '</td><td colspan="5" >' + objeto.mensajesCancelacion[i].nombresEmpleado + '</td><td colspan="6" >' + objeto.mensajesCancelacion[i].motivo + '</td><td><input type="button" id="' + objeto.mensajesCancelacion[i].id + '" value="Borrar mensaje" onclick="borrarMensaje()" class="btn btn-success" /></td></tr>';
        }
    }
    tabla += '</table class="table" >';
    tabla += '<table class="table" id="datosConductores">';
    tabla += '<tr><th colspan="' + colspan + '">Datos servicio</th></tr>';
    tabla += '<tr><td>Servicio</td><td>' + objeto.servicio[0].idservicio + '</td><td>Fecha</td><td colspan="2">' + objeto.servicio[0].fechaServicio + '</td><td colspan="2" >Crea servicio</td><td colspan="2" >' + objeto.empleado[0].emp_nombres + ' ' + objeto.empleado[0].emp_apellidos + ' </td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
    tabla += '<tr class="table-active"><th colspan="' + colspan + '">Datos veh&iacute;culo, propietario y conductor</th></tr>';

    //servicios satisfactorios del conductor
    var satisfCond = 0, 
    //servicios satisfactorios del propietario
    satisfProp = 0, 
    //servicios satisfactorios de la placa
    satisfPla = 0;
    //no satisfactorios del conductor
    var noSatisfCond = 0, 
    //no satisfactorios del propietario 
    noSatisfProp = 0, 
    //no satisfactorios de la placa 
    noSatisfPla = 0;
    for (var i = 0; i < objeto.calificacionesConductor.length; i++) {
        if (objeto.calificacionesConductor[i].calificacion === '1') {
            satisfCond += 1;
        } else {
            noSatisfCond += 1;
        }
    }
    msjCaliCond = "Sat.:" + satisfCond + " No Sat.:" + noSatisfCond + "";

    for (var i = 0; i < objeto.calificacionesPropietario.length; i++) {
        if (objeto.calificacionesPropietario[i].calificacion === '1') {
            satisfProp += 1;
        } else {
            noSatisfProp += 1;
        }
    }

    msjCaliPropi = "Sat.:" + satisfProp + " No Sat.:" + noSatisfProp + "";

    for (var i = 0; i < objeto.calificacionesPlaca.length; i++) {
        if (objeto.calificacionesPlaca[i].calificacion === '1') {
            satisfPla += 1;
        } else {
            noSatisfPla += 1;
        }
    }

    msjCaliPlaca = "Sat.:" + satisfPla + " No Sat.:" + noSatisfPla + "";

    var manifiesto = null;

    if (objeto.seguimientos[0] === undefined) {
        manifiesto = 0;
    } else {
        manifiesto = objeto.seguimientos[0].manifiesto;
    }

    tabla += '<tr><td>Placa</td><td>Calif. Pla.</td><td>C.C. Propietario</td><td colspan="2">Nombre propietario</td><td>Tel&eacute;fono</td><td>Calif. Prop.</td><td>C.C. Conductor</td><td colspan="2">Nombre conductor</td><td>Tel&eacute;fono</td><td>Calif. Cond.</td><td>Manifiesto</td><td></td><td></td><td></td><td></td></tr>';
    tabla += '<tr><td>' + objeto.servicio[0].placa + '</td><td>' + msjCaliPlaca + '</td><td>' + objeto.propietario[0].cond_identificacion + '</td><td colspan="2">' + objeto.propietario[0].cond_nombres + ' ' + objeto.propietario[0].cond_apellidos + '</td><td>' + objeto.propietario[0].cond_telefono + '</td><td>' + msjCaliPropi + '</td><td>' + objeto.conductor[0].cond_identificacion + '</td><td colspan="2">' + objeto.conductor[0].cond_nombres + ' ' + objeto.conductor[0].cond_apellidos + '</td><td>' + objeto.conductor[0].cond_telefono + '</td><td>' + msjCaliCond + '</td><td>' + manifiesto + '</td><td></td><td></td><td></td><td></td></tr>';
    tabla += '</table>';

    if (option === 1) {
        tabla += '<table class="table" id="tablaServicio" >';
        tabla += '<tr class="table-active"><th colspan="30">Datos gu&iacute;as</th></tr>';
        tabla += '<tr><td></td><td>Gu&iacute;a</td><td>Planilla</td><td>Cliente</td><td>Asesor</td><td>Origen</td><td>Destino</td><td>Valor flete</td><td>Auxilar</td><td><!--Parqueadero--></td><td>Otros</td><td>Valor total costos</td><td>Valor a facturar</td><td>Valor anticipo</td><td>Valor declarado</td><td>% manejo</td><td>Valor manejo</td><td>Utilidad</td><td>Estado</td><td>Fecha pago</td><td>Factura</td><td>Fecha factura</td><td>Cuenta de cobro</td><td>Fecha transferencia</td><td>Notas</td></tr>';

        for (var i = 0; i < objeto.datosGuias.length; i++) {
            num = i + 1;
            aleatorio = i % 2;
            if (objeto.anticipos[i].prueba_entrega === 'P') {
                estado = '<span class="badge badge-success" >Pagada</span>';
            } else if (objeto.anticipos[i].prueba_entrega === 'N') {
                estado = '<span class="badge badge-warning" >Por pagar</span>';
            }

            fechaFactura = objeto.facturas[i].fecha;

            //fchFtr fecha de factura
            fchFtr = fechaFactura.substring(0, fechaFactura.length);

            if (fchFtr.trim() === '00:00:00') {
                fechaFactura = '';
            } else {
                fechaFactura = fechaFactura.substring(0, fechaFactura.length - 8);
            }



            if (objeto.datosGuias[i].valorManejo > 0) {
                valorManejo = parseFloat((parseFloat(objeto.datosGuias[i].valorDeclarado) * objeto.datosGuias[i].valorManejo) / 100);
            } else {
                valorManejo = 0;
            }

            //es para cuando el perfil permite la edicion
            if ($("#borrado").val() === '2') {
                tabla += '<tr bgcolor="' + colores[aleatorio] + '"><td>' + num + '</td><td>' + objeto.datosGuias[i].numeroGuia + '</td><td>' + objeto.datosGuias[i].planilla + '</td><td>' + objeto.datosGuias[i].cli_nombre + '</td>';
                tabla += '<td>' + objeto.asesoresServicio[i].nombreAsesor + '</td><td>' + objeto.datosGuias[i].ciudadOrigen + '</td>';
                tabla += '<td>' + objeto.datosGuias[i].ciudadDestino + '</td>';
                tabla += '<td><input type="text" size="' + size + '" id="2-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].valorPagado) + '" onblur="cambiarValorPagado(this,0)" /></td>';
                tabla += '<td><input type="text" size="' + size + '" id="a-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].auxiliar) + '" onblur="cambiarValorAuxiliar(this)" /></td>';
                tabla += '<td><!--<input type="text" size="' + size + '" id="p-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].parqueadero) + '" onblur="cambiarValorParqueadero(this)" />--></td>';
                tabla += '<td><!--<input type="text" size="' + size + '" id="o-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].otros) + '" />-->';
                tabla += '</td><td></td><td><input type="text" size="' + size + '" id="1-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].valorCobrado) + '" onblur="cambiarValorCobrado(this,0)" /></td>';
                tabla += '<td><input type="text" size="' + size + '" id="ant-' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.anticipos[i].val_valorAdelanto) + '" onblur="cambiarValorAdelanto(this)" /></td><td>' + formatNumber.new(objeto.datosGuias[i].valorDeclarado) + '</td>';
                tabla += '<td><input type="text" size="2" id="man-' + objeto.datosGuias[i].numeroGuia + '" value="' + objeto.datosGuias[i].valorManejo + '" onblur="cambiarValorManejo(this)" /></td>';
                tabla += '<td><input type="text" size="' + size + '" value="' + formatNumber.new(valorManejo) + '" disabled="disabled" /></td>';
                tabla += '<td></td><td>' + estado + '</td>';
                tabla += '<td><div id="fechaPago-' + objeto.datosGuias[i].numeroGuia + '"></div></td>';
                tabla += '<td><input type="text" id="f-' + objeto.datosGuias[i].numeroGuia + '" value="' + objeto.facturas[i].factura + '" onblur="cambiarFactura(this)" size="' + size + '" /></td>';
                tabla += '<td><div  style=" background-color:"' + colores[aleatorio] + '" id="fechaFactura-' + objeto.datosGuias[i].numeroGuia + '">' + fechaFactura + '</div></td>';
                tabla += '<td>' + objeto.datosGuias[i].numeroCuentacobro + '</td>';

                if (objeto.datosGuias[i].fechaTransferencia === null) {
                    tabla += '<td></td>';
                } else {
                    tabla += '<td>' + objeto.datosGuias[i].fechaTransferencia + '</td>';
                }

                tabla += '<td><textarea id="n-' + objeto.datosGuias[i].numeroGuia + '" cols = "20" onblur="cambiarNotasGuia(this)">' + objeto.datosGuias[i].notas + '</textarea></td></tr>';
                tabla += '<tr bgcolor="' + colores[aleatorio] + '" id="trEntAnt' + i + '"><td colspan="25" ><div id="entregasAnteriores" class="col-lg-12" ></div></td></tr>';
                tabla += '<tr bgcolor="' + colores[aleatorio] + '" ><td colspan="21" id="otrosCostos-' + objeto.datosGuias[i].numeroGuia + '" ></td><td></td><td></td></tr>';
            }

            //aqui se evalua si el servicio fue o no pagado ya        
            if ($("#borrado").val() === '3') {
                if (objeto.anticipos[i].prueba_entrega === 'P' || fechaFactura !== '') {
                    tabla += '<tr bgcolor="' + colores[aleatorio] + '"><td>' + num + '</td><td>' + objeto.datosGuias[i].numeroGuia + '</td><td>' + objeto.datosGuias[i].planilla + '</td><td>' + objeto.datosGuias[i].cli_nombre + '</td>';
                    tabla += '<td>' + objeto.asesoresServicio[i].nombreAsesor + '</td><td>' + objeto.datosGuias[i].ciudadOrigen + '</td>';
                    tabla += '<td>' + objeto.datosGuias[i].ciudadDestino + '</td>';
                    tabla += '<td>' + formatNumber.new(objeto.datosGuias[i].valorPagado) + '</td>';
                    tabla += '<td>' + formatNumber.new(objeto.datosGuias[i].auxiliar) + '</td>';
                    tabla += '<td><!--<input type="text" size="' + size + '" id="p-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].parqueadero) + '" onblur="cambiarValorParqueadero(this)" />--></td>';
                    tabla += '<td><!--<input type="text" size="' + size + '" id="o-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].otros) + '" />--></td>';
                    tabla += '<td></td>';
                    tabla += '<td>' + formatNumber.new(objeto.datosGuias[i].valorCobrado) + '</td>';
                    tabla += '<td>' + formatNumber.new(objeto.anticipos[i].val_valorAdelanto) + '</td>';
                    tabla += '<td>' + formatNumber.new(objeto.datosGuias[i].valorDeclarado) + '</td>';
                    tabla += '<td>' + objeto.datosGuias[i].valorManejo + '</td>';
                    tabla += '<td>' + formatNumber.new(valorManejo) + '</td>';
                    tabla += '<td></td><td>' + estado + '</td>';
                    tabla += '<td><div id="fechaPago-' + objeto.datosGuias[i].numeroGuia + '"></div></td>';
                    tabla += '<td>' + objeto.facturas[i].factura + '</td>';
                    tabla += '<td><div  style=" background-color:"' + colores[aleatorio] + '" id="fechaFactura-' + objeto.datosGuias[i].numeroGuia + '">' + fechaFactura + '</div></td>';
                    tabla += '<td>' + objeto.datosGuias[i].numeroCuentacobro + '</td>';

                    if (objeto.datosGuias[i].fechaTransferencia === null) {
                        tabla += '<td></td>';
                    } else {
                        tabla += '<td>' + objeto.datosGuias[i].fechaTransferencia + '</td>';
                    }

                    tabla += '<td><textarea id="n-' + objeto.datosGuias[i].numeroGuia + '" cols = "20" onblur="cambiarNotasGuia(this)">' + objeto.datosGuias[i].notas + '</textarea></td>';
                    tabla += '</tr>';
                    tabla += '<tr bgcolor="' + colores[aleatorio] + '" id="trEntAnt' + i + '"><td colspan="25" ><div id="entregasAnteriores" class="col-lg-12" ></div></td></tr>';
                    tabla += '<tr bgcolor="' + colores[aleatorio] + '" ><td colspan="21" id="otrosCostos-' + objeto.datosGuias[i].numeroGuia + '" ></td><td></td><td></td></tr>';
                    modificar = 0;
                } else {

                    tabla += '<tr bgcolor="' + colores[aleatorio] + '"><td>' + num + '</td><td>' + objeto.datosGuias[i].numeroGuia + '</td><td>' + objeto.datosGuias[i].planilla + '</td><td>' + objeto.datosGuias[i].cli_nombre + '</td>';
                    tabla += '<td>' + objeto.asesoresServicio[i].nombreAsesor + '</td><td>' + objeto.datosGuias[i].ciudadOrigen + '</td>';
                    tabla += '<td>' + objeto.datosGuias[i].ciudadDestino + '</td>';
                    tabla += '<td><input type="text" size="' + size + '" id="2-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].valorPagado) + '" onblur="cambiarValorPagado(this,0)" /></td>';
                    tabla += '<td><input type="text" size="' + size + '" id="a-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].auxiliar) + '" onblur="cambiarValorAuxiliar(this)" /></td>';
                    tabla += '<td><!--<input type="text" size="' + size + '" id="p-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].parqueadero) + '" onblur="cambiarValorParqueadero(this)" />--></td>';
                    tabla += '<td><!--<input type="text" size="' + size + '" id="o-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].otros) + '" />-->';
                    tabla += '</td><td></td><td><input type="text" size="' + size + '" id="1-' + objeto.datosGuias[i].numeroGuia + '" name="' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.datosGuias[i].valorCobrado) + '" onblur="cambiarValorCobrado(this,0)" /></td>';
                    tabla += '<td><input type="text" size="' + size + '" id="ant-' + objeto.datosGuias[i].numeroGuia + '" value="' + formatNumber.new(objeto.anticipos[i].val_valorAdelanto) + '" onblur="cambiarValorAdelanto(this)" /></td><td>' + formatNumber.new(objeto.datosGuias[i].valorDeclarado) + '</td>';
                    tabla += '<td><input type="text" size="2" id="man-' + objeto.datosGuias[i].numeroGuia + '" value="' + objeto.datosGuias[i].valorManejo + '" onblur="cambiarValorManejo(this)" /></td>';
                    tabla += '<td><input type="text" size="' + size + '" value="' + formatNumber.new(valorManejo) + '" disabled="disabled" /></td>';
                    tabla += '<td></td><td>' + estado + '</td>';
                    tabla += '<td><div id="fechaPago-' + objeto.datosGuias[i].numeroGuia + '"></div></td>';
                    tabla += '<td><input type="text" id="f-' + objeto.datosGuias[i].numeroGuia + '" value="' + objeto.facturas[i].factura + '" onblur="cambiarFactura(this)" size="' + size + '" /></td>';
                    tabla += '<td><div  style=" background-color:"' + colores[aleatorio] + '" id="fechaFactura-' + objeto.datosGuias[i].numeroGuia + '">' + fechaFactura + '</div></td>';
                    tabla += '<td>' + objeto.datosGuias[i].numeroCuentacobro + '</td>';

                    if (objeto.datosGuias[i].fechaTransferencia === null) {
                        tabla += '<td></td>';
                    } else {
                        tabla += '<td>' + objeto.datosGuias[i].fechaTransferencia + '</td>';
                    }

                    tabla += '<td><textarea id="n-' + objeto.datosGuias[i].numeroGuia + '" cols = "20" onblur="cambiarNotasGuia(this)">' + objeto.datosGuias[i].notas + '</textarea></td>';
                    tabla += '</tr>';
                    tabla += '<tr bgcolor="' + colores[aleatorio] + '" id="trEntAnt' + i + '"><td colspan="25" ><div id="entregasAnteriores" class="col-lg-12" ></div></td></tr>';
                    tabla += '<tr bgcolor="' + colores[aleatorio] + '" ><td colspan="21" id="otrosCostos-' + objeto.datosGuias[i].numeroGuia + '" ></td><td></td><td></td></tr>';
                }
            }

            totalCobrado += parseFloat(objeto.datosGuias[i].valorCobrado) + parseFloat(valorManejo);
            totalPagado += parseFloat(objeto.datosGuias[i].valorPagado);
            totalAnticipo += parseFloat(objeto.anticipos[i].val_valorAdelanto);
            totalAuxiliares += parseFloat(objeto.datosGuias[i].auxiliar);
            totalParqueaderos += parseFloat(objeto.datosGuias[i].parqueadero);
            totalVlrMan += parseFloat(valorManejo);
            traerFechaPagoConductor(objeto.datosGuias[i].numeroGuia, objeto.servicio[0].idservicio);
            valorManejo = 0;
            mostrarOtrosCostos(objeto.datosGuias[i].numeroGuia, colores[aleatorio]);

        }

        if (objeto.sobreanticipos.length > 0) {

            tabla += '<tr><th colspan="' + colspan + '">Sobre anticipos</th></tr>';
            tabla += '<tr><td></td><td>Gu&iacute;a</td><td>Nro. Sobreanticipo</td><td>Fecha</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';

            if ($("#borrado").val() === '2') {
                for (var i = 0; i < objeto.sobreanticipos.length; i++) {
                    fecha = objeto.sobreanticipos[i].val_fechaAnticipo;
                    fecha = fecha.substr(0, 10);
                    tabla += '<tr id="tr-' + objeto.sobreanticipos[i].val_numeroAnticipo + '" ><td><input type="button" class="btn btn-success btn-sm" id="' + objeto.sobreanticipos[i].val_numeroAnticipo + '" value="X" onclick="borrarSobreAnticipo(this);"/></td><td>' + objeto.sobreanticipos[i].val_numeroGuia + '</td><td>' + objeto.sobreanticipos[i].val_numeroAnticipo + '</td>\n\
<td>' + fecha + '</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>\n\
<td></td><td>' + formatNumber.new(objeto.sobreanticipos[i].val_valorAdelanto) + '</td>\n\
<td></td>\n\
<td></td><td></td><td></td></tr>';
                    totalAnticipo += parseFloat(objeto.sobreanticipos[i].val_valorAdelanto);
                }
            }

            if ($("#borrado").val() === '3') {

                if (modificar === 0) {
                    for (var i = 0; i < objeto.sobreanticipos.length; i++) {
                        fecha = objeto.sobreanticipos[i].val_fechaAnticipo;
                        fecha = fecha.substr(0, 10);
                        tabla += '<tr id="tr-' + objeto.sobreanticipos[i].val_numeroAnticipo + '" ><td></td><td>' + objeto.sobreanticipos[i].val_numeroGuia + '</td><td>' + objeto.sobreanticipos[i].val_numeroAnticipo + '</td>\n\
<td>' + fecha + '</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>\n\
<td></td><td>' + formatNumber.new(objeto.sobreanticipos[i].val_valorAdelanto) + '</td>\n\
<td></td>\n\
<td></td><td></td><td></td></tr>';
                        totalAnticipo += parseFloat(objeto.sobreanticipos[i].val_valorAdelanto);
                    }


                } else {

                    for (var i = 0; i < objeto.sobreanticipos.length; i++) {
                        fecha = objeto.sobreanticipos[i].val_fechaAnticipo;
                        fecha = fecha.substr(0, 10);
                        tabla += '<tr id="tr-' + objeto.sobreanticipos[i].val_numeroAnticipo + '" ><td><input type="button" class="btn btn-success btn-sm" id="' + objeto.sobreanticipos[i].val_numeroAnticipo + '" value="X" onclick="borrarSobreAnticipo(this);"/></td><td>' + objeto.sobreanticipos[i].val_numeroGuia + '</td><td>' + objeto.sobreanticipos[i].val_numeroAnticipo + '</td>\n\
<td>' + fecha + '</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td>\n\
<td></td><td>' + formatNumber.new(objeto.sobreanticipos[i].val_valorAdelanto) + '</td>\n\
<td></td>\n\
<td></td><td></td><td></td></tr>';
                        totalAnticipo += parseFloat(objeto.sobreanticipos[i].val_valorAdelanto);
                    }

                }

            }
        }

        if (estado.indexOf('Pagada') > -1) {
            if ($("#borrado").val() === '2') {
                reactivarCuenta = '<input type="button" class="btn btn-success" id="btnReactivarCta" value="Re-activar cuenta de cobro" onclick="activarCuenta();"/>';
            }
            if ($("#borrado").val() === '3') {
                if (modificar === 0) {
                    reactivarCuenta = '';
                } else {
                    reactivarCuenta = '<input type="button" class="btn btn-success" id="btnReactivarCta" value="Re-activar cuenta de cobro" onclick="activarCuenta();"/>';
                }
            }

        } else {
            reactivarCuenta = '';
        }

        totalOtros = parseFloat(objeto.totalOtros);
        valorTotalCostos = totalAuxiliares + totalParqueaderos + totalOtros + totalPagado;
        diferencia = totalCobrado - valorTotalCostos;
        porcentajeGanancia = 100 - ((valorTotalCostos * 100) / totalCobrado);
        tabla += '<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Valor flete</td><td>Auxilar</td><td>Parqueadero</td><td>Otros</td><td>Valor total costos</td><td>Valor a facturar</td><td>Valor anticipo</td><td>Valor declarado</td><td>% manejo</td><td>Valor manejo</td><td>Utilidad</td><td></td><td></td><td></td><td></td><td></td></tr>';
        tabla += '<tr><td></td><td></td><td></td><td></td><td></td><td></td><th>Totales</th><th>' + formatNumber.new(totalPagado) + '</th><th>' + formatNumber.new(totalAuxiliares) + '</th><th>' + formatNumber.new(totalParqueaderos) + '</th><th>' + formatNumber.new(totalOtros) + '</th><th>' + formatNumber.new(valorTotalCostos) + '</th><th>' + formatNumber.new(totalCobrado) + '</th><th>' + formatNumber.new(totalAnticipo) + '</th><td><td></td></td><th>' + formatNumber.new(totalVlrMan) + '</th><th>' + formatNumber.new(diferencia) + '</th><th>Ganancia: ' + formatNumber.new(porcentajeGanancia.toFixed(2)) + '%</th><td></td></tr>';

        if ($("#borrado").val() === '2') {
            tabla += '<tr><th colspan="3">Cancelar servicio</th><td>Motivo</td><td colspan="2">' + listaMotivos + '</td><td colspan="2"><input type="button" class="btn btn-success" id="c-' + objeto.servicio[0].idservicio + '" onclick="cancelarServicio(this)" value="Cancelar servicio"/></td><td>' + reactivarCuenta + '</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        }

        if ($("#borrado").val() === '3') {
            if (modificar === 0) {
                tabla += '<tr><th colspan="3"</th><td></td><td colspan="2"></td><td colspan="2"></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
            } else {
                tabla += '<tr><th colspan="3">Cancelar servicio</th><td>Motivo</td><td colspan="2">' + listaMotivos + '</td><td colspan="2"><input type="button" class="btn btn-success" id="c-' + objeto.servicio[0].idservicio + '" onclick="cancelarServicio(this)" value="Cancelar servicio"/></td><td>' + reactivarCuenta + '</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
            }
        }

    } else {

        tabla += '<table class="table" id="tablaServicio" >';
        tabla += '<tr class="table-active"><th colspan="30">Datos gu&iacute;as</th></tr>';

    }


    if (objeto.seguimientos.length > 0) {
        if (objeto.seguimientos.planderuta === '1') {
            planRuta = 'Si';
        } else {
            planRuta = 'No';
        }
        tabla += '<tr><th colspan="' + colspan + '">Historial seguimiento</th></tr>';
        tabla += '<tr><td></td><td>Gu&iacute;a</td><td>Fecha/hora</td><td>Ubicaci&oacute;n</td><td>Observaci&oacute;n</td><td>Imagen GPS</td><td>Plan de ruta</td><td>Colaborador</td><td>Estado</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';
        for (var i = 0; i < objeto.seguimientos.length; i++) {

            if (objeto.seguimientos[i].imagen === '0') {
                imagen = 'No hay imagen de seguimiento';
            } else {
                imagen = '<img src="' + objeto.seguimientos[i].imagen + '" width="190" heigth="95" />';
            }

            tabla += '<tr><td><input type="button" value="X" title="Borra éste seguimiento" name="' + objeto.seguimientos[i].idseguimiento + '" onclick="eliminarSeguimiento(this)" class="btn btn-success btn-sm" /></td><td>' + objeto.seguimientos[i].guia + '</td><td>' + objeto.seguimientos[i].fechaHora + '</td>\n\
<td>' + objeto.seguimientos[i].ubicacion + '</td><td>' + objeto.seguimientos[i].observacion + '</td><td>' + imagen + '</td>\n\
<td>' + planRuta + '</td><td>' + objeto.seguimientos[i].nombreEmpleado + '</td><td>' + objeto.seguimientos[i].estadoseguimiento + '</td>';

            tabla += '<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>';

        }
    } else {
        tabla += '<tr><th colspan="' + colspan + '">No se registra historial de seguimiento</th></tr>';
    }

    tabla += '</table></div>';

    if (objeto.pruebasEntrega.length > 0) {
        tabla += '<div id="divPruebasEntrega"></div>';        
    } else {
        tabla += '<div id="divPruebasEntrega"><table class="table"><td><tr>No se registran pruebas de entrega</tr></td></table></div>';
    }
    
    $("#mensajes").html(tabla);
    
    retornarPruebasEntrega($("#idservicio").val(),'divPruebasEntrega');

    retornarEntregas(objeto.servicio[0].idservicio, i);
}

function datosAModificar() {

}

function borrarMensaje() {
    var idservicio = $("#idservicio").val();
    $.ajax({
        url: "../trafico/ServiciosPorCancelar.php",
        data: {'caso': '1',
            idservicio: idservicio},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj) {
                consultarPorIdServicio(idservicio, 1);
            } else {
                alert("Ha fallado la cancelación del mensaje.function borrarMensaje(elemento) {");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function borrarMensaje(elemento) {...");
        }
    });

}

function cambiarValorAuxiliar(elemento) {
    var id = "#" + elemento.id;
    $(id).number(true);
    var valor = $(id).val();
    var guia = id.substring(3, id.length);
    var idservicio = $("#idservicio").val();

    $.ajax({
        url: "../trafico/Servicios.php",
        data: {'caso': '10',
            guia: guia,
            valor: valor,
            idservicio: idservicio},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj === 1) {
                consultarPorIdServicio(idservicio, 1);
            } else {
                alert("Ha fallado el cambio del valor auxiliar\nfunction cambiarValorAuxiliar(elemento) {...respuesta desde servidor...}else{...\nPor favor informe");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function cambiarValorAuxiliar(elemento) {...");
        }
    });
}

function cambiarValorParqueadero(elemento) {
    var id = "#" + elemento.id;
    $(id).number(true);
    var valor = $(id).val();
    var guia = id.substring(3, id.length);
    var idservicio = $("#idservicio").val();

    $.ajax({
        url: "../trafico/Servicios.php",
        data: {'caso': '11',
            guia: guia,
            valor: valor,
            idservicio: idservicio},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj === 1) {
                consultarPorIdServicio(idservicio, 1);
            } else {
                alert("Ha fallado el cambio del valor auxiliar\nfunction cambiarValorParqueadero(elemento) {...respuesta desde servidor...}else{...\nPor favor informe");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function cambiarValorParqueadero(elemento) {...");
        }
    });
}

/*
 * 20190509 
 * Esta función también esta en 
 * js_gestionarServicios.js
 */
function cambiarNotasGuia(elemento) {
    var id = "#" + elemento.id;
    $(id).number(true);
    var valor = $(id).val();
    var guia = id.substring(3, id.length);
    var idservicio = $("#idservicio").val();
    $.ajax({
        url: "../trafico/Servicios.php",
        data: {'caso': '13',
            guia: guia,
            valor: valor,
            idservicio: idservicio},
        type: "POST",
        success: function (data) {
//            var obj = JSON.parse(data);
//            if (obj === 1) {
//                consultarPorIdServicio(idservicio);
//            } else {
//                alert("Ha fallado el cambio del valor del mensaje\n function cambiarNotasGuia(elemento) {...respuesta desde servidor...}else{...\nPor favor informe");
//            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function cambiarNotasGuia(elemento){...");
        }
    });
}

function cancelarServicio(elemento) {
    var id = elemento.id;
    var idservicio = id.substring(2, id.length);
    var motivo = $("#motivosCancelacion").val();
    if (confirm("Confirmar cancelar servicio " + idservicio)) {
        $.ajax({
            url: "../trafico/Servicios.php",
            data: {'caso': '14',
                idservicio: idservicio,
                motivo: motivo},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj) {
                    $("#mensajes").html("Servicio " + idservicio + " cancelado de manera correcta");
                    $("#idservicio").html("0");
                } else {
                    alert("Ha fallado la cancelación del servicio\n function cancelarServicio(elemento){...respuesta desde servidor...}else{...\nPor favor informe");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("function cancelarServicio(elemento){...");
            }
        });
    }
}

function cambiarFactura(elemento) {

    var id = elemento.id;
    var guia = id.substr(2, id.length);
    var factura = $("#" + id).val();
    var idservicio = $("#idservicio").val();

    $.ajax({
        url: "../trafico/Servicios.php",
        data: {'caso': '16',
            'idservicio': idservicio,
            'factura': factura,
            'guia': guia},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (!obj) {
                alert("Ha fallado el cambio de la factura \n function cambiarFactura(elemento) {...respuesta desde servidor...}else{...\nPor favor informe");
            } else {
                consultarPorIdServicio($("#idservicio").val(), 1);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function cambiarFactura(elemento) {...");
        }
    });
}

function activarCuenta() {
    var idservicio = $("#idservicio").val();
    $.ajax({
        url: "../trafico/Servicios.php",
        data: {'caso': '17',
            'idservicio': idservicio},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj === 1) {
                alert("Se ha re-activado la cuenta de cobro para este servicio");
                $("#btnReactivarCta").hide();
            } else {
                alert("Ha fallado la re-activación de la cuenta de cobro");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function function activarCuenta(){...");
        }
    });
}

function eliminarSeguimiento(valor) {

    if (confirm("Se va ha borrar éste seguimiento\nEsta seguro/a?")) {
        var idseguimiento = valor.name;
        var idservicio = $("#idservicio").val();
        $.ajax({
            url: "../trafico/Seguimiento.php",
            data: {'caso': '3',
                'idseguimiento': idseguimiento,
                'idservicio': idservicio},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    alert("Se ha borrado éste seguimiento");
                    consultarPorIdServicio($("#idservicio").val(), 1);
                } else {
                    alert("Ha fallado el borrado de éste seguimiento");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("function eliminarSeguimiento(valor) {...");
            }
        });
    }
}

function cambiarValorAdelanto(valor) {
    var id = valor.id;
    var vlr = $("#" + id).val();
}

function borrarSobreAnticipo(valor) {
    var numSobreAnticipo = valor.id;
    if (confirm("Borrar sobreanticipo " + numSobreAnticipo + "?")) {
        $.ajax({
            url: "../trafico/Anticipos.php",
            data: {'caso': '1',
                'numSobreAnticipo': numSobreAnticipo},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    consultarPorIdServicio($("#idservicio").val(), 1);
                } else {
                    alert("Ha fallado el borrado de éste sobreanticipo.\nPor favor presione F5 e intentelo nuevamente");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function borrarSobreAnticipo(valor) {...");
            }
        });
    }
}

function traerFechaPagoConductor(guia, idservicio) {
    var fecha = null;
    $.ajax({
        url: "../trafico/Trasabilidad.php",
        data: {'caso': '1',
            'idservicio': idservicio},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj[0].fecha !== null) {
                fecha = obj[0].fecha.substring(0, obj[0].fecha.length - 8);
                $("#fechaPago-" + guia).html(fecha);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function traerFechaPagoConductor(guia) {...");
        }
    });
}

function cambiarValorManejo(valor) {
    var id = valor.id;
    var numeroGuia = id.substring(4, id.length);
    var valorManejo = $("#" + id).val();

    if (isNaN(valorManejo) || valorManejo.indexOf(',') > -1) {
        $("#mensajesGenerales").html("<div class='alert alert-dismissible alert-danger'>El valor digitado para Valor Manejo debe ser: <br>-Un n&uacute;mero entero <br>-&Oacute; un n&uacute;mero con punto como separador<br>. Por favor verifique</div>");
        $("#man-" + numeroGuia).focus();
    } else {
        $.ajax({
            url: "../trafico/ServicioGuias.php",
            data: {'caso': '1',
                'numeroGuia': numeroGuia,
                'valorManejo': valorManejo},
            type: "POST",
            beforeSend: function (xhr) {
                $("#mensajes").html('<div class="alert alert-warning">Consultando, un momento por favor...</div>');
            },
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    consultarPorIdServicio($("#idservicio").val(), 1);
                } else {
                    alert("Ha fallado el cambio del valor manejo.\nPor favor presione F5 e intentelo nuevamente");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function cambiarValorManejo(valor) {...");
            }
        });
    }
}

function consultarPorIdServicio(idservicio, option) {
    if (parseFloat(idservicio) > 0) {
        $.ajax({
            url: "../trafico/Servicios.php",
            data: {'caso': '5',
                idservicio: idservicio},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj !== false) {
                    if (Object.keys(obj).length === 0) {
                        $("#mensajes").html('<div class="alert alert-danger">No se registran datos con el valor ingresado. Por favor verifique</div>');
                        $("#mensajesGenerales").html('');
                    } else {
                        pintarTabla(obj, option);
                    }
                } else {
                    alert("Ha fallado la consulta de los datos del servicio\nfunction consultarPorIdServicio(idservicio) {...respuesta desde servidor...}else{...\nPor favor informe.\nGracias!");
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("function consultarPorIdServicio(idservicio) {...");
            }
        });
    } else {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Para realizar la consulta se debe digitar un:<br>-N&uacute;mero de servicio ó<br>-N&uacute;mero de gu&iacute;a<br>Se ha detectado carácteres inválidos en: Número de servicio</div>");
        $("#idservicio").val('0');
        $("#idservicio").focus();
    }
}

function consultarPorGuia(guia, option) {
    var tabla = '';
    $.ajax({
        url: "../trafico/Servicios.php",
        data: {'caso': '6',
            guia: guia},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj !== false) {
                if (Object.keys(obj).length === 0) {
                    $("#mensajes").html('<div class="alert alert-danger">Con el n&uacute;mero de guia: <strong>' + guia + '</strong> no se registran datos <br />Por favor verifique</div>');
                    $("#mensajesGenerales").html('');
                } else if (obj.length > 1) {
                    tabla = '<div class="alert alert-dismissible alert-danger">La gu&iacute;a <strong>' + guia + '</strong> se ubic&oacute; en los suguientes servicios:<table class="table table-hover" ><tr>';
                    for (var i = 0; i < obj.length; i++) {
                        tabla += '<td><a href="../modulos/administrarServiciosDos.php?idservicio=' + obj[i].idservicio + '" target="_blank"><strong>' + obj[i].idservicio + '</strong></a></td>';
                    }
                    tabla += '</tr></table></div>';

                    $("#mensajes").html(tabla);
                } else {
                    consultarPorIdServicio(obj[0].idservicio, option);
                }
            } else {
                alert("Ha fallado la consulta de los datos de la gu&iacute;a\nfunction consultarPorGuia(guia) {...respuesta desde servidor...}else{...\nLa página será recargada, por favor presione F5 e intente nuevamente.\nGracias!");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("function consultarPorGuia(guia) {...");
        }
    });
}

function pintarTablaPruebaEntregas(obj,nombreObjetoHtml,) {
    var tabla = '<table class="table table-bordered">';
    tabla += '<tr>';
    tabla += '<th>Guia</th><th>Pruebas</th>';
    tabla += '</tr>';
    for (var i = 0; i < obj["guias"].length; i++) {
        tabla += '<tr>';
        tabla += '<td>' + obj["guias"][i]["guia"] + '</td>';
        for (var b = 0; b < obj["todo"].length; b++) {            
            if (obj["guias"][i]["guia"] === obj["todo"][b]["guia"]) {

                if(obtenerExtension(obj["todo"][b]["ruta"])==='pdf'){
                    tabla += '<td><center><a href="' + obj["todo"][b]["ruta"] + '" ><img src="../imagenes/pdf.png" width="200" height="200" target="_blank" /></a><br/><br/><input type="button" value="Borrar" id="br-'+obj["guias"][i]["id"]+'" onclick="cambiarEstadoPruebaEntrega(this)"/></center></td>';
                }else{
                    tabla += '<td><center><a href="' + obj["todo"][b]["ruta"] + '" ><img src="' + obj["todo"][b]["ruta"] + '" width="200" height="200" target="_blank" /></a><br/><br/><input type="button" value="Borrar" id="br-'+obj["guias"][i]["id"]+'" onclick="cambiarEstadoPruebaEntrega(this)"/></center></td>';
                }                
            }
        }
        tabla += '</tr>';
    }
    $("#"+nombreObjetoHtml).html(tabla);
}

function obtenerExtension(nombreArchivo) {
    const indiceExtension = nombreArchivo.lastIndexOf('.');
    if (indiceExtension === -1) {
      return "";
    }
    return nombreArchivo.slice(indiceExtension + 1);
  }

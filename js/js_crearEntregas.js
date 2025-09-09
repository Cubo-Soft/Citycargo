var datosEntregas = [], arregloEntregas = [], aMostrar = null, idservicio = 0, numeroFacturatura = null;

$(document).ready(function () {

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('input[type=text]').keyup(function () {
        $(this).val($(this).val().toUpperCase());
    });

    $("#botonBorrar").click(function () {
        location.reload(true);
    });

    $("#listaMunicipios").change(function () {
        var nit = $("#nitEmpresa").val();
        var idciudad = $("#listaMunicipios").val();
        retornaDirPorCiuCli(nit, idciudad);
    });

    $("#crearEntrega").click(function () {

        var valor = $("#valorEntrega").val();
        valor = valor.replace(".", "");

        if (validarFormulario() === 6) {
            datosEntregas = {
                'guia': $("#guia").val(),
                'unidades': $("#unidades").val(),
                'idservicio': $("#idservicio").val(),
                'guiaEntrega': $("#guiaEntrega").val(),
                'dlDirDes': $("#dlDirDes").val(),
                'nombreCiudad': $('#dlDirDes option:selected').html(),
                'planilla': $("#planilla").val(),
                'remision': $("#remision").val(),
                'factura': $('#factura').val(),
                'ordenCompra': $("#ordenCompra").val(),
                'valor': valor,
                'nit': $("#nitEmpresa").val(),
                'Notas': $("#notas").val()
            };
            arregloEntregas.push(datosEntregas);
            pintarTabla();
        }

    });

    $("#guia").blur(function () {

        $("#numeroFactura").val('0');

        consultarFacturaCE($("#guia").val());
        
        if (numeroFactura === '0') {
            $("#datosFormulario").show();
            if ($("#guia").val() === '0' || $("#guia").val() === '') {
                $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor ingrese un n&uacute;mero de gu&iacute;a v&aacute;lido</div>");
                $("#cantidadEntregas").focus();
            } else {
                $.ajax({
                    url: "../trafico/Servicios.php",
                    data: {'caso': '18',
                        'guia': $("#guia").val()},
                    type: "POST",
                    success: function (data) {
                        var a = 0, claseTr = '', nit = 0, idservicio = 0;
                        var obj = JSON.parse(data);
                        if (obj === 0) {
                            $("#mensajes").html("<div class='alert alert-dismissible alert-warning'>No se registra la gu&iacute;a ingresada</div>");
                            $("#datosFormulario").hide();
                        } else {
                            if (obj.datosGuias.length === 1) {
                                $("#datosGuias").show();
                                $("#entregasAnteriores").show();
                                $("#datosFormularios").show();
                                var mensaje = "<table class='table table-hover'><thead>"
                                mensaje += "<tr><td colspan='4'>Gu&iacute;a de servicio</td></tr>";
                                mensaje += "<tr><th scope='col' ></th><th scope='col' >Gu&iacute;a</th>\n\
<th scope='col' >Valor</th><th scope='col' >Empresa</th></tr></thead></tbody>";
                                $.each(obj.datosGuias, function (i, guias) {
                                    a += 1;
                                    nit = obj.facturas[i].nit;
                                    if (parseInt($("#guia").val()) === parseInt(guias.numeroGuia)) {
                                        claseTr = 'bgcolor="#CEF6CE"';
                                        $("#numeroServicio").html("<h2>Servicio: " + obj.datosGuias[i].idservicio + "</h2>");
                                        $("#idservicio").val(obj.datosGuias[i].idservicio);
                                        $("#idservicio1").val(obj.datosGuias[i].idservicio);
                                        idservicio = obj.datosGuias[i].idservicio;
                                        $("#iddireccionorigen").val(obj.datosGuias[i].iddireccionorigen);
                                        $("#valorGuia").val(guias.valorCobrado);
                                        $("#nitEmpresa").val(obj.facturas[0].nit);
                                        $("#diferencia").val(guias.valorCobrado);
                                    } else {
                                        claseTr = '';
                                    }
                                    mensaje += "<tr " + claseTr + " ><th>" + a + "</th><td>" + guias.numeroGuia + "</td><td>" + formatNumber.new(guias.valorCobrado) + "</td><td>" + guias.cli_nombre + "</td></tr>";
                                });
                                mensaje += "</tbody></table>";
                                $("#cantidadEntregas").focus();
                                $("#datosGuias").html(mensaje);
                                retornarEntregas(idservicio);
                            } else {
                                $("#mensajes").html('<div class="alert alert-dismissible alert-danger">El servicio registra mas de una gu&iacute;a. No es candidato de ingreso de más guías</div>');
                                $("#datosGuias").hide();
                                $("#entregasAnteriores").hide();
                                $("#datosFormulario").hide();
                            }
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("function consultarPorGuia(guia,option) {...");
                    }
                });
            }
        } else {
            $("#datosFormulario").hide();
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">El servicio registra factura n&uacute;mero: ' + numeroFactura + '<br>No es posible agregar entregas</div>');
        }
    });

    $("#guia").focusin(function () {
        borrarFormulario(1);
        $(this).val('');
        $("#menuIngresos").show();
        $("#datosIntroducidos").show();
        $("#datosIntroducidos").show();
        $("#datosFormulario").show();
        $("#mensajes").html('');
        $("#botonGrabarEntregas").attr("disabled", false);
        $("#entregasAnteriores").html('');
        $("#datosGuias").html('');
    });

    $("#cantidadEntregas").focusin(function () {
        borrarFormulario(0);
    });

    $("#cantidadEntregas").blur(function () {
        var vlr = 0;
        if (parseInt($("#cantidadEntregas").val()) > 0) {
            $("#mensajes").html("");
            vlr = parseInt($("#valorGuia").val()) / parseInt($("#cantidadEntregas").val());
            if (vlr % 1 === 0) {
                $("#divValor").html('<input type="text" id="valorEntrega" name="valorEntrega" value="' + formatNumber.new(vlr) + '"  class="form-control" />');
                $("#mensajes").html('<div class="alert alert-dismissible alert-warning">Se ha ajustado el valor por entrega a: ' + vlr + '</div>');
            } else {
                $("#divValor").html('<input type="text" id="valorEntrega" name="valorEntrega" class="form-control" value="0" />');
                $("#mensajes").html('<div class="alert alert-dismissible alert-success">El valor por entrega ha generado valores decimales, por favor ingrese los valores manualmente</div>');
            }
        } else {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor ingrese un valor v&aacute;lido en cantidad de entregas</div>");
            $("#cantidadEntregas").focus();
        }
    });

    $("#botonGrabarEntregas").click(function () {
        if ($("#diferencia").val() === '0') {
            $.ajax({
                url: "../trafico/Entregas.php",
                data: {'caso': '1',
                    'arregloEntregas': arregloEntregas},
                type: "POST",
                success: function (data) {
                    var obj = JSON.parse(data);
                    if (obj === 1) {
                        borrarFormulario(2);
                    } else {
                        $("#mensajes").html('<div class="alert alert-dismissible alert-success">Ha fallado la creaci&oacute;n de las entregas. Por favor presione F5 e intente nuevamente.<br>Si la falla persiste por favor informe</div>');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert('$("#botonGrabarEntregas").click(function () {...');
                }
            });
        } else {
            $("#mensajes").html('<div class="alert alert-dismissible alert-warning">El valor total de la gu&iacute;a no es igual a la cantidad de entregas realizadas</div>');
        }
    });
});

function consultarFacturaCE(guia) {
    $.ajax({
        url: "../trafico/Prefactura.php",
        data: {'opcion': '1',
            'guia': guia},
        type: "POST",
        async: false,
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.length !== 0) {
                numeroFactura = obj[0].factura;
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert('Error function consultarFactura(guia) {...');
        }
    });
}

function pintarTabla() {

    var total = 0, diferencia, a = 0, valor = 0;

    if ((arregloEntregas.length) <= parseInt($("#cantidadEntregas").val())) {
        aMostrar = "<table class='table table-hover' ><tr>";
        aMostrar += "<th>No.</th><th>Gu&iacute;a de entrega</th>\n\
<th>Unidades</th>\n\
<th>Planilla</th>\n\
<th>Remisi&oacute;n</th>\n\
<th>Factura</th>\n\
<th>Orden de compra</th>\n\
<th>Direcci&oacute;n destino</th>\n\
<th>Valor</th>\n\
<th>Diferencia</th>\n\
<th>Notas gu&iacute;a</th>\n\
<th></th></tr>";
        $.each(arregloEntregas, function (i, valor) {
            a += 1;
            aMostrar += "<tr><td>" + a + "</td><td>" + valor.guiaEntrega + "</td>\n\
<td>" + valor.unidades + "</td>\n\
<td>" + valor.planilla + "</td>\n\
<td>" + valor.remision + "</td>\n\
<td>" + valor.factura + "</td>\n\
<td>" + valor.ordenCompra + "</td>\n\
<td>" + valor.nombreCiudad + "</td>\n\
<td>" + formatNumber.new(valor.valor) + "</td>\n\
<td></td>\n\
<td>" + valor.Notas + "</td>\n\
<td></td><td><input type='button' id='" + i + "' value='X' onclick='borrarDetencion(this)' /></td></tr>";
            valor = valor.valor;
            total += parseInt(valor.replace('.', ''));
            diferencia = parseInt($("#valorGuia").val()) - total;
            $("#diferencia").val(diferencia);
        });
        aMostrar += "<tr><td></td><td></td><td></td><td></td><td></td><td></td><td></td><td>Total</td><td>" + formatNumber.new(total) + "</td><td>" + formatNumber.new(diferencia) + "</td><td></td></tr>";
        aMostrar += "</table>";
        if (total === parseInt($("#valorGuia").val())) {
            $("#crearEntrega").attr("disabled", true);
            $("#mensajes").html('<div class="alert alert-dismissible alert-warning">Se ha alcanzado el valor de la gu&iacute;a</div>');
            $("#datosFormulario").hide();
        } else {
            $("#crearEntrega").attr("disabled", false);
            $("#mensajes").html('');
            $("#datosFormulario").show();
        }
        $("#datosIntroducidos").html(aMostrar);
        borrarFormulario(0);
    } else {
        $("#crearEntrega").attr("disabled", true);
        if (parseInt($("#diferencia").val()) > 0) {
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">La cantidad de entregas se han digitado<br>La suma de los valores de las entregas no corresponden con el valor de la gu&iacute;a. Por favor revise</div>');
            $("#botonGrabarEntregas").attr("disabled", false);
        } else {
            $("#mensajes").html('<div class="alert alert-dismissible alert-warning">La cantidad de entregas se han digitado</div>');
        }

    }

}

function borrarDetencion(valor) {
    var id = valor.id;
    arregloEntregas.splice($.inArray(id, arregloEntregas), 1);
    pintarTabla();
}

function borrarFormulario(valor) {

    if (valor === 0) {
        $("#guiaEntrega").val("0");
        $("#dlDirDes").val("-1");
        $("#idciudaddestino").val("-1");
        $("#planilla").val("0");
        $("#unidades").val("0");
        $("#remision").val("0");
        $("#factura").val("0");
        $("#ordenCompra").val("0");
        $("#notas").val('');
    }

    if (valor === 1) {
        arregloEntregas = [];
        $("#datosIntroducidos").html("");
        $("#mensajes").html("<div class='alert alert-dismissible alert-warning'>Recuerde por favor ingresar un n&uacute;mero v&aacute;lido de entregas</div>");
        $("#datosGuias").html("");
        $("#divValor").html("");
        $("#guiaEntrega").val("0");
        $("#dlDirDes").val("-1");
        $("#idciudaddestino").val("-1");
        $("#tiposDocCliente").val("0");
        $("#ordenCompra").val("0");
        $("#idservicio").val('');
        $("#idservicio1").val('');
        $("#cantidadEntregas").val('0');
    }

    if (valor === 2) {
        arregloEntregas = [];
        $("#datosIntroducidos").html("");
        $("#mensajes").html("<div class='alert alert-dismissible alert-success'>Se han creado las entregas de manera correcta</div>");
        $("#datosGuias").html("");
        $("#divValor").html("");
        $("#guia").val("0");
        $("#guiaEntrega").val("0");
        $("#dlDirDes").val("-1");
        $("#idciudaddestino").val("-1");
        $("#tiposDocCliente").val("0");
        $("#ordenCompra").val("0");
        $("#idservicio").val('');
        $("#idservicio1").val('');
        $("#cantidadEntregas").val('0');
    }

    if (valor === 3) {
        $("#datosGuias").html("");
        $("#entregasAnteriores").html('<div class="alert alert-dismissible alert-success">Se han borrado las entregas</div>');
    }

}

function validarFormulario() {
    var retorno = 0;

    if ($("#unidades").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor digite un n&uacute;mero de unidades v&aacute;lido</div>");
        $("#unidades").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#guiaEntrega").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor digite un n&uacute;mero de gu&iacute;a v&aacute;lido</div>");
        $("#guiaEntrega").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#dlDirDes").val() === '-1') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor seleccione una dirección de destino</div>");
        $("#dlDirDes").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#tiposDocCliente").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor seleccione un tipo de documento de cliente v&aacute;lido</div>");
        $("#tiposDocCliente").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#planilla").val() === '0' && $("#remision").val() === '0' && $("#factura").val() === '0' && $("#ordenCompra").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor revise los valores para:</br>-Planilla</br>-Remisi&oacute;n</br>-Factura y/o </br>-Orden de compra</br></div>");
        retorno = 0;
    } else {
        retorno += 1;
    }

    if ($("#valorEntrega").val() === '0' || $("#valorEntrega").val() === '' || parseInt($("#valorEntrega").val()) < 0) {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>El valor de la gu&iacute;a no puede ser cero o un valor menor. Por favor revise</div>");
        $("#valorEntrega").focus();
        retorno = 0;
    } else {
        retorno += 1;
    }

    return retorno;
}

function crearDireccionDestino() {

    if ($("#direcciondestino").val() === '0' || $("#direcciondestino").val() === '') {
        $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Por favor ingrese una direcci&oacute;n de destino v&aacute;lida</div>');
        $("#direcciondestino").focus();
    } else {
        $.ajax({
            url: "../trafico/retornarAsesorEmpresa.php",
            data: {'tipo': 0,
                'documento': $("#nitEmpresa").val(),
                'direccion': $("#direcciondestino").val(),
                'telefono': 0,
                'ciudad': $("#listaMunicipios").val(),
                'opcion': '7'},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.length === 0) {
                    $("#mensajes").html('<div class="alert alert-dismissible alert-danger">La creación de la dirección ha fallado. Por favor presione F5 e inicie nuevamente</div>');
                } else {
                    crearListaDirecciones(obj, 2);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function function crearDireccionDestino() {...");
            }
        });
    }
}
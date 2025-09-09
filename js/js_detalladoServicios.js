var ingreso = "", boton_ = null, guia = null, factura = null, todos = [];
$(document).ready(function () {

    $("#chbPE").change(function () {
        if ($(this).is(":checked")) {
            $("#cuerpoPrefactura input[type=checkbox]").prop('checked', true);
            $("input:checkbox:checked").each(function () {
                chequearSimilares(this);
            });
        } else {
            $("#cuerpoPrefactura input[type=checkbox]").prop('checked', false);
            todos = [];
        }
    });

    $("#valorAcumuladoMostrar").number(true, 0);

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#botonGenerarExcel").click(function () {
        var todosLimpio = [];
        if (todos.length === 0) {
            $("#mensajes2").html('<div class="alert alert-dismissible alert-warning">Por favor seleccione servicios para generar detallado</div>');
        } else {
            var factura = prompt("Ingrese un número de factura");
            if ($.isNumeric(factura)) {
                /*
                 * Se limpia el arreglo de contenidos nulos o vacios
                 */
                todosLimpio = todos.filter(function (vlr) {
                    return vlr !== null;
                });
                $.ajax({
                    url: "../trafico/Prefactura.php",
                    data: {
                        'factura': factura,
                        'guias': todosLimpio,
                        'opcion': '2'
                    },
                    type: "POST",
                    success: function (data) {
                        //console.log(data);
                        var obj = JSON.parse(data);
                        if (obj === 0) {
                            alert("Ha fallado la generación del Detallado de servicios.\nSe ha intentado revertir el proceso.\nPor favor presione F5 y reinicie la gestión.\nSi la falla persiste, por favor informe");
                        } else {
                            traerTabla(factura, todos);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert('Error $("#botonGenerarExcel").click(function () {');
                    }
                });
            }
        }
    });
});

function traerTabla(factura, guias) {
    $.ajax({
        url: "../trafico/generarExcelDetalleServicios.php",
        data: {
            'factura': factura,
            'guias': guias,
            'nit': $("#nitEmpresa").val(),
            'nombreEmpresa': $("#nombreEmpresa").val(),
            'telefono': $("#telefonoEmpresa").val(),
            'direccion': $("#direccionEmpresa").val()
        },
        type: "POST",
        success: function (data) {            
            $("#cuerpoPrefactura").html(data);
            //---aqui esta no va var obj = JSON.parse(data);
            var d = new Date();
            var strDate = d.getFullYear() + "" + (d.getMonth() + 1) + "" + d.getDate();
            var filename = strDate + '_' + factura + '.xls';
            var name= strDate + '_' + factura;
            $("#tablaDetalleServicios").table2excel({
                exclude: ".no-export", // Clase para excluir columnas o filas específicas
                name: name, // Nombre del archivo de Excel
                filename: filename, // Nombre del archivo de Excel
                fileext: ".xls" // Extensión del archivo de Excel
            });
            //location.reload();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function traerTabla(factura, servicios) {");
        }
    });
}

function consultarFactura() {
    $.ajax({
        url: "../trafico/consultarFactura.php",
        data: {
            'guia': $("#guia").val()
        },
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj !== false) {
                if (obj["resultado"] !== 'null') {
                    $("#confirmacionIngreso").html("<div id='divInternoIngreso' class='alert alert-dismissible alert-success'>La gu&iacute;a n&uacute;mero: " + $("#guia").val() + " esta asociada a la factura: " + obj["resultado"] + "</strong></div>");
                } else {
                    $("#confirmacionIngreso").html("<div id='divInternoIngreso' class='alert alert-dismissible alert-success'>No se encuentra la gu&iacute;a: " + $("#guia").val() + " asociada a una factura</strong></div>");
                }
            } else {
                alert("Error en respuesta AJAX obj JSON function consultarFactura()");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function ingresarFactura()");
        }
    });
}

function chequearSimilares(valor) {
    var id = valor.id;
    var posGuion = id.indexOf('-');
    var vlr = id.substr(0, posGuion);
    if ($("#" + id).prop("checked") === true) {
        if (jQuery.inArray(vlr, todos) === -1) {
            todos.push(vlr);
        }
    } else {
        todos.splice($.inArray(vlr, todos), 1);
    }

}

function iniciarAccion() {
    mostrarServiciosFacturar(todos, 1);
}

function mostrarServiciosFacturar(arreglo, condicion) {
    var consultados = [];
    var mostrar = null;
    var valorTotal = 0;
    var salto = 1, b = 1;
    if (condicion === 1) {
        $("#valorAcumulado").val('0');
        $("#valorAcumuladoMostrar").val('0');
        mostrar = '<div class="alert alert-dismissible alert-success"><strong>Gu&iacute;as a facturar: ';
        $.each(arreglo, function (a, valor) {

            if (typeof (valor) !== 'undefined' && valor !== '') {
                if ($.inArray(consultados, valor) === -1) {
                    consultados[a] = valor;
                    if (a === salto) {
                        mostrar += valor + "<br/>";
                        salto = b * 16;
                        b += 1;
                    } else {
                        mostrar += valor + "-";
                    }
                    $.ajax({
                        url: "../trafico/ServicioGuias.php",
                        data: {
                            'guia': valor,
                            'caso': '6'
                        },
                        type: "POST",
                        success: function (data) {
                            var obj = JSON.parse(data);
                            valorTotal = parseInt($("#valorAcumulado").val());
                            valorTotal = valorTotal + parseInt(obj);
                            $("#valorAcumulado").val(valorTotal);
                            $("#valorAcumuladoMostrar").val(valorTotal);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert("Error function mostrarServiciosFacturar(arreglo, condicion) { ... ajax");
                        }
                    });
                }
            }
        });
        mostrar += "</strong></div>";
    }
    if (condicion === 2) {
        $("#valorAcumulado").val('0');
        $("#valorAcumuladoMostrar").val('0');
        mostrar = "";
    }
    $("#mensajes2").html(mostrar);
    consultados = [];
}
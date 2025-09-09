$(document).ready(function () {

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonGenerarExcel').click(function () {

        var d = new Date();
        var fecha = d.getFullYear() + "/" + (d.getMonth() + 1) + "/" + d.getDate();

        $("#tablaExcel").table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: 'PropCond' + fecha,
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    });

    $("#botonRegresar").click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#consultarVehiculo").click(function () {

        if (validarConsulta() === 1) {
            $.ajax({
                url: "../trafico/vehiculo.php",
                data: {'caso': 5,
                    'tipo': $("#listaTipoVehiculo").val(),
                    'carroceria': $("#listaTipoCarroceria").val(),
                    'capacidadInicial': $("#capacidadInicial").val(),
                    'capacidadFinal': $("#capacidadFinal").val()
                },
                type: "POST",
                success: function (data) {
                    var volveh = null;
                    var obj = JSON.parse(data);
                    if (obj.length > 0) {
                        var tabla = "<table id='tablaExcel' class='table table-hover'>\n\
<tr><th></th><th>Placa</th><th>Nombres propietario</th><th>Tel&eacute;fono propietario</th><th>Nombres conductor</th><th>Tel&eacute;fono conductor</th><th>Tipo</th><th>Carroceria</th><th>Marca</th><th>Modelo</th><th>Capacidad carga</th><th>Ancho</th><th>Largo</th><th>Alto</th><th>Vol.Veh</th></tr>";
                        $.each(obj, function (i, item) {
                            volveh = parseFloat(item.alto) * parseFloat(item.ancho) * parseFloat(item.largo);
                            tabla += "<tr><td>" + i + "</td><td>" + item.placa + "</td>\n\
<td id='np-" + item.placa + "'></td>\n\
<td id='tp-" + item.placa + "' ></td>\n\
<td id='nc-" + item.placa + "' ></td>\n\
<td id='tc-" + item.placa + "' ></td>\n\
<td>" + item.tipovehiculo + "</td><td>" + item.tipocarroceria + "</td><td>" + item.marca + "</td><td>" + item.modelo + "</td><td>" + item.capacidadcarga + " Kls</td><td>" + item.ancho + "</td><td>" + item.largo + "</td><td>" + item.alto + "</td><td>" + volveh + " Mts3</td></tr>";
                            retornarPropietariosCondcutores(item.placa);
                        });
                        tabla += "</table>";
                        $("#mensajes").html(tabla);
                    } else {
                        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>No se registran veh&iacute;culos con los datos seleccionados.</div>");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Error $('#consultarVehiculo').click(function () {...retorno desde el servidor");
                }
            });
        }
    });

});

function validarConsulta() {
    var cons = 0;
    if ($("#capacidadFinal").val() === '0') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor ingrese una capacidad final de carga. Ej. 3000.</div>");
        $("#capacidadFinal").focus();
        cons = 0;
    } else {
        cons += 1;
    }
    return cons;
}

function retornarPropietariosCondcutores(placa) {
    var nomPro = null, telPro = null, nomCon = null, telCon = null;
    $.ajax({
        url: "../trafico/retornarPropietarioConductores.php",
        data: {'placa': placa},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.propietario.length === 0) {
                nomPro = obj.conductores[0].nombreConductor;
                telPro = obj.conductores[0].telefonoConductor;
            } else {
                nomPro = obj.propietario[0].nombresPropietario;
                telPro = obj.propietario[0].telefonoPropietario;
            }

            if (obj.conductores.length !== 0) {
                nomCon = obj.conductores[0].nombreConductor;
                telCon = obj.conductores[0].telefonoConductor;
            }

            $("#tp-" + placa).html(telPro);
            $("#np-" + placa).html(nomPro);
            $("#tc-" + placa).html(telCon);
            $("#nc-" + placa).html(nomCon);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function retornarPropietariosCondcutores(placa) {...");
        }
    });
}


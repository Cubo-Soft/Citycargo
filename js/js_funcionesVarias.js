/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

function consultarCalificacion(valores) {
    var cedulaPropietario = $("#" + valores.id).val();
    var placa = $('select[name="placas"] option:selected').text();
    placa = placa.substr(0, 6);
    $.ajax({
        url: "../trafico/Calificaciones.php",
        data: {'caso': 2, 'placa': placa, 'cedulaPropietario': cedulaPropietario},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            var tabla = "<table class='table table-hover' ><thead><tr><td>No. Servicio</td><td>Motivo</td></tr></thead><tbody>";
            if (obj.length > 0) {
                var totCaliS = 0, totCaliNS = 0, listaServicios = "", texto = "";
                for (var i = 0; i < obj.length; i++) {
                    if (obj[i].calificacion === '1') {
                        totCaliS += 1;
                    } else {
                        totCaliNS += 1;
                        tabla += "<tr><td>" + obj[i].idservicio + "</td><td>" + obj[i].detalle + "</td></tr>";
                    }
                }
                if (totCaliNS >= 1) {
                    tabla += "</tbody></table>";
                    texto = tabla;
                }
                $("#mensajes").html("<div class='alert alert-dismissible alert-success col-lg-12'><div class='col-lg-6' >\n\
<table class='table table-hover' >\n\
<tr><td>Placa</td><td> " + placa + "</td></tr>\n\
<tr><td>Total servicios satisfactorios</td><td>" + totCaliS + "</td></tr>\n\
<tr><td>Total servicios no satisfactorios</td><td>" + totCaliNS + "</td></tr>\n\
</table></div><div class='col-lg-6' >" + texto + "</div></div>");
                $("#listarServiciosPor").val('0');
            } else {
                $("#mensajes").html("<div class='alert alert-dismissible alert-warning'>No se registran servicios no satisfactorios con las placas seleccionadas</div>");
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function consultarCalificacion(valores) {...retorno desde el servidor");
        }
    });
}

function traerDatosAgendaPorEmpleado(cedula, fechaInicial, fechaFinal, nombreAsesor) {

    $.ajax({
        url: "../trafico/Asesorempresa.php",
        data: {'caso': '1', 'cedula': cedula, 'fechaInicial': fechaInicial, 'fechaFinal': fechaFinal},
        type: "POST",
        success: function (data) {
            console.log(data);
            var obj = JSON.parse(data);
            if (obj.length === 0) {
                mostrarEventos = "<div class='alert alert-dismissible alert-danger'>Con los datos ingresados</div>";
                mostrarEventos += "<table class='table table-hover'>";
                mostrarEventos += "<tr><th>Asesor</th><th>Fecha inicial</th><th>Fecha final</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
                mostrarEventos += "<tr><td>" + nombreAsesor + "</td><td>" + fechaInicial + "</td><td>" + fechaFinal + "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                mostrarEventos += "</table>";
                mostrarEventos += "<div class='alert alert-dismissible alert-danger'>No se registran eventos. Desea <strong>¿ampliar el rango de b&uacute;squeda con las fechas?</strong></div>";
                $("#listarServiciosPor").val('0');
            } else {
                mostrarEventos = "<table class='table table-hover'>";
                mostrarEventos += "<tr><th>Asesor</th><th>Fecha inicial</th><th>Fecha final</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
                mostrarEventos += "<tr><td>" + nombreAsesor + "</td><td>" + fechaInicial + "</td><td>" + fechaFinal + "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                mostrarEventos += "<tr><th colspan='10'>Eventos empresariales</th></tr>";
                mostrarEventos += "<tr><th>Tipo evento</th><th>Direcci&oacute;n y/o evento</th><th>Fecha/Hora Inicio</th><th>Fecha/Hora Fin</th><th>Funcionario</th><th>Correo electr&oacute;nico</th><th>Cargo</th><th>Tel&eacute;fono</th><th>Cliente</th><th>Gesti&oacute;n</th>";

                for (var i = 0; i < obj.empresas.length; i++) {
                    switch (obj.empresas[i].estado) {
                        case '1':
                            gestion = 'Activo';
                            break;
                        case '2':
                            gestion = 'Pospuesto';
                            break;
                        case '3':
                            gestion = 'Cancelado';
                            break;
                        case '4':
                            gestion = 'Finalizado';
                            break;

                    }

                    mostrarEventos += "<tr><td>" + obj.empresas[i].evento + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].direccion + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].fechaHoraInicio + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].fechaHoraFin + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].nombre + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].correo + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].cargo + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].telefono + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].cli_nombre + "</td>";
                    mostrarEventos += "<td>" + gestion + "</td></tr>";
                }

                if (obj.personales.length >= 0) {
                    mostrarEventos += "<tr><th colspan='10'>Eventos personales</th></tr>";
                    for (var i = 0; i < obj.personales.length; i++) {
                        switch (obj.personales[i].estado) {
                            case '0':
                                gestion = 'Finalizado';
                                break;
                            case '1':
                                gestion = 'Activo';
                                break;
                            case '2':
                                gestion = 'Pospuesto';
                                break;
                            case '3':
                                gestion = 'Cancelado';
                                break;
                        }

                        mostrarEventos += "<tr><td>Personal</td>";
                        mostrarEventos += "<td>" + obj.personales[i].personal + "</td>";
                        mostrarEventos += "<td>" + obj.personales[i].fechaHoraInicio + "</td>";
                        mostrarEventos += "<td>" + obj.personales[i].fechaHoraFin + "</td>";
                        mostrarEventos += "<td></td>";
                        mostrarEventos += "<td></td>";
                        mostrarEventos += "<td></td>";
                        mostrarEventos += "<td></td>";
                        mostrarEventos += "<td></td>";
                        mostrarEventos += "<td>" + gestion + "</td></tr>";
                    }
                }

                mostrarEventos += "</table>";
                $("#listarServiciosPor").val('-2');
            }
            $("#mensajes").html(mostrarEventos);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function consultarAgendaAsesor() {...retorno desde el servidor");
        }
    });
}

function traerDatosAgendaPorEmpleadoNit(cedula, fechaInicial, fechaFinal, nombreAsesor, nitEmpresa) {
    $.ajax({
        url: "../trafico/Asesorempresa.php",
        data: {'caso': '2', 'cedula': cedula, 'fechaInicial': fechaInicial, 'fechaFinal': fechaFinal, 'nitEmpresa': nitEmpresa},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.length === 0) {
                mostrarEventos = "<div class='alert alert-dismissible alert-danger'>Con los datos ingresados</div>";
                mostrarEventos += "<table class='table table-hover'>";
                mostrarEventos += "<tr><th>Asesor</th><th>Fecha inicial</th><th>Fecha final</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
                mostrarEventos += "<tr><td>" + nombreAsesor + "</td><td>" + fechaInicial + "</td><td>" + fechaFinal + "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                mostrarEventos += "</table>";
                mostrarEventos += "<div class='alert alert-dismissible alert-danger'>No se registran eventos. Desea <strong>¿ampliar el rango de b&uacute;squeda con las fechas?</strong></div>";
                $("#listarServiciosPor").val('0');
            } else {
                mostrarEventos = "<table class='table table-hover'>";
                mostrarEventos += "<tr><th>Asesor</th><th>Fecha inicial</th><th>Fecha final</th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>";
                mostrarEventos += "<tr><td>" + nombreAsesor + "</td><td>" + fechaInicial + "</td><td>" + fechaFinal + "</td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>";
                mostrarEventos += "<tr><th colspan='10'>Eventos empresariales</th></tr>";
                mostrarEventos += "<tr><th>Tipo evento</th><th>Direcci&oacute;n y/o evento</th><th>Fecha/Hora Inicio</th><th>Fecha/Hora Fin</th><th>Funcionario</th><th>Correo electr&oacute;nico</th><th>Cargo</th><th>Tel&eacute;fono</th><th>Cliente</th><th>Gesti&oacute;n</th>";

                for (var i = 0; i < obj.empresas.length; i++) {
                    switch (obj.empresas[i].estado) {
                        case '0':
                            gestion = 'Finalizado';
                            break;
                        case '1':
                            gestion = 'Activo';
                            break;
                        case '2':
                            gestion = 'Pospuesto';
                            break;
                        case '3':
                            gestion = 'Cancelado';
                            break;
                    }

                    mostrarEventos += "<tr><td>" + obj.empresas[i].evento + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].direccion + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].fechaHoraInicio + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].fechaHoraFin + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].nombre + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].correo + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].cargo + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].telefono + "</td>";
                    mostrarEventos += "<td>" + obj.empresas[i].cli_nombre + "</td>";
                    mostrarEventos += "<td>" + gestion + "</td></tr>";
                }

                if (obj.personales.length >= 0) {
                    mostrarEventos += "<tr><th colspan='10'>Eventos personales</th></tr>";
                    for (var i = 0; i < obj.personales.length; i++) {
                        switch (obj.personales[i].estado) {
                            case '1':
                                gestion = 'Activo';
                                break;
                            case '2':
                                gestion = 'Pospuesto';
                                break;
                            case '3':
                                gestion = 'Cancelado';
                                break;
                            case '4':
                                gestion = 'Finalizado';
                                break;
                        }

                        mostrarEventos += "<tr><td>Personal</td>";
                        mostrarEventos += "<td>" + obj.personales[i].personal + "</td>";
                        mostrarEventos += "<td>" + obj.personales[i].fechaHoraInicio + "</td>";
                        mostrarEventos += "<td>" + obj.personales[i].fechaHoraFin + "</td>";
                        mostrarEventos += "<td></td>";
                        mostrarEventos += "<td></td>";
                        mostrarEventos += "<td></td>";
                        mostrarEventos += "<td></td>";
                        mostrarEventos += "<td></td>";
                        mostrarEventos += "<td>" + gestion + "</td></tr>";
                    }
                }


                mostrarEventos += "</table>";
                $("#listarServiciosPor").val('-2');
            }
            $("#mensajes").html(mostrarEventos);

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Error function consultarAgendaAsesor() {...retorno desde el servidor");
        }
    });
}

function traerDatosConductor(datos, opcion) {

    if ($("#" + datos.nombreCampo).val() > 0) {
        $.ajax({
            url: "../trafico/Conductor.php",
            data: {'caso': '1',
                'cedulaConductor': $("#" + datos.nombreCampo).val()
            },
            type: "POST",
            success: function (data, textStatus, jqXHR) {
                //console.log(data);
                var obj = JSON.parse(data);
                //console.log(obj);

                if (opcion === 1) {
                    if (obj.length > 0) {
                        $("#idConductor").val(obj[0].cond_id);
                        $("#nombresConductor").val(obj[0].cond_nombres);
                        $("#apellidosConductor").val(obj[0].cond_apellidos);
                        $("#direccionConductor").val(obj[0].cond_direccion);
                        $("#telefonoConductor").val(obj[0].cond_telefono);
                        $("#email").val(obj[0].email);
                        $("#listaMunicipios").val(obj[0].municipios_mun_id);
                        $("#estadoConductor").val(obj[0].estadoConductor);
                        $("#perfil").val(obj[0].perfil);
                        $("#listaPlacas").val(obj[0].placa);
                        $("#estadoRelacion").val(obj[0].estadoRelacion);
                        $('#cedulaConductor').prop('disabled', true);
                        $('#botonCrearConductor').prop('disabled', true);
                        $('#reportar_novedad').val(obj[0]["reportar_novedad"]);
                        arregloPlacas = null;
                        retornarPlacas(obj);
                    } else {
                        $('#cedulaConductor').prop('disabled', false);
                        $('#botonCrearConductor').prop('disabled', false);
                        $("#mensajes").html('<div class="alert alert-dismissible alert-warning">No se registran datos. ¿Desea <strong>CREAR</strong> conductor?</div>');
                    }
                }

                if (opcion === 2 ) {

                    if (obj.length > 0) {                        
                        
                        datos.arreglo.forEach(function (id) {
                            $("#" + id).on("input", function () {
                                cambiarDato(this, 1);
                            });
                        });                        
                                                                       
                        $("#"+datos.arreglo[0]).val(obj[0].cond_nombres + ' ' + obj[0].cond_apellidos).trigger("input");
                        $("#"+datos.arreglo[1]).val(obj[0].cond_direccion).trigger("input");
                        $("#"+datos.arreglo[2]).val(obj[0].cond_telefono).trigger("input");
                    }

                }
                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX funcion cedulaConductor");
            }
        });
    }

}
var fechaHoy = null, date = null, dia = null, mes = null, anio = null, hora = null, 
minuto = null, listaEmpresas = null, listaContactos = null, listaDirecciones = null;
$(document).ready(function () {

    fechaActual = retornarFecha();
    listaEmpresas = $("#divListaEmpresas").html();

    $("#divCliente").hide();
    $("#divBusqueda").hide();
    $("#listaAsunto").focus();
    $("#divListaMunicipios").hide();

    $('#REGRESAR').click(function () {
        window.location.href = "../trafico/redirigir.php";
    });

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $("#activarBusqueda").click(function () {
        if ($("#activarBusqueda").prop('checked')) {
            $("#divBusqueda").show();
            $("#divEventosCreados").hide();
            $("#divAgenda").hide();
            $("#mensajes").html("<div class='alert alert-dismissible alert-success'>Para realizar una búsqueda, por favor indique al menos fecha inicial y fecha final </div>");
        } else {
            $("#divBusqueda").hide();
            $("#divEventosCreados").show();
            $("#divAgenda").show();
            $("#mensajes").html("<div class='alert alert-dismissible alert-success'>Para crear un evento, inicie seleccionando un asunto de la lista </div>");
        }
    });
    
    $("#listaAsunto").click(function () {

        if ($("#listaAsunto").val() === '-1') {
            $("#divListaAsunto").html("<input type='text' class='form-control form-control-sm' id='asuntoNuevo' onblur='crearEvento(this)' />");
            $("#asuntoNuevo").focus();
        } else if ($("#listaAsunto").val() === '6') {
            $("#divListaEmpresas").html("");
            $("#divDirOrg").html("<input type='text' id='asuntoPersonal' class='form-control' onfocusin='borrarTextoDiv()'/>");
            $("#cambio").html("Asunto y/o direcci&oacute;n");
            $("#contactoCliente").html("");
            $("#mensajeRecordatorio").html("<div class='alert alert-dismissible alert-warning'>Se ha detectado un evento <strong>Personal</strong>.Por favor indique:<br> El asunto y/o direcci&oacute;n; <br>fecha/hora de inicio y <br>fecha/hora de finalizaci&oacute;n; luego<br>Presione el bot&oacute;n Crear evento</div>");
        } else {
            $("#cambio").html("Direcci&oacute;n");
            $("#divDirOrg").html('');
            $("#datosContacto").html('');
            $("#contactoCliente").html('');
            $("#mensajes").html('');
            $("#divListaEmpresas").html(listaEmpresas);
        }
    });


    $("#crearEvento").click(function () {

        var nit = $("#listaEmpresas").val();
        var idasunto = $("#listaAsunto").val();
        var dlDirOrg = $("#dlDirOrg").val();
        var fechaHoraInicio = $("#fechaHoraInicio").val();
        var fechaHoraFin = $("#fechaHoraFin").val();
        var cedulaEmpleado = $("#cedulaEmpleado").val();
        var listaContactos = $("#listaContactos").val();

        if (idasunto === '6') {
            var asuntoPersonal = $("#asuntoPersonal").val();
            if (validarCampos(idasunto, 0, 0, fechaHoraInicio, fechaHoraFin, 0, asuntoPersonal) === 1) {
                $.ajax({
                    url: "../trafico/AsuntoEmpleado.php",
                    data: {'caso': 2, 'idasunto': idasunto, 'fechaHoraInicio': fechaHoraInicio, 'fechaHoraFin': fechaHoraFin,
                        'asuntoPersonal': asuntoPersonal, 'cedulaEmpleado': cedulaEmpleado},
                    type: "POST",
                    success: function (data) {
                        console.log(data);
                        var obj = JSON.parse(data);
                        if (obj) {
                            location.reload(true);
                        } else {
                            $("#mensaje").html("<div class='alert alert-dismissible alert-danger'>Un error impidio crear el evento. Por favor presione F5 e intentelo nuevamente, si el error persiste por favor informe</div>");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Error $('#crearEvento').click(function () {...retorno desde el servidor");
                    }
                });
            }
        } else {
            if (validarCampos(idasunto, nit, dlDirOrg, fechaHoraInicio, fechaHoraFin, listaContactos, 0) === 1) {
                $.ajax({
                    url: "../trafico/AsuntoEmpleado.php",
                    data: {'caso': 1, 'idasunto': idasunto, 'nit': nit, 'dlDirOrg': dlDirOrg,
                        'fechaHoraInicio': fechaHoraInicio, 'fechaHoraFin': fechaHoraFin, 'listaContactos': listaContactos,
                        'cedulaEmpleado': cedulaEmpleado},
                    type: "POST",
                    success: function (data) {
                        var obj = JSON.parse(data);
                        if (obj) {
                            location.reload(true);
                        } else {
                            $("#mensaje").html("<div class='alert alert-dismissible alert-danger'>Un error impidio crear el evento. Por favor presione F5 e intentelo nuevamente, si el error persiste por favor informe</div>");
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        alert("Error $('#crearEvento').click(function () {...retorno desde el servidor");
                    }
                });
            }
        }


    });

    $("#consultarAgenda").click(function () {

        var fechaInicial = $("#fechaInicial").val();
        var fechaFinal = $("#fechaFinal").val();
        var nitEmpresa = $("#listaEmpresas2").val();
        var cedula = $("#cedulaEmpleado").val();
        var nombreAsesor = $("#nombreEmpleado").val();

        if (fechaInicial === '' || fechaInicial === null) {
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Por favor indique una fecha inicial</div>');
            $("#fechaInicial").focus();
        } else if (fechaFinal === '' || fechaFinal === null) {
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Por favor indique una fecha final</div>');
            $("#fechaFinal").focus();
        } else {
            if (nitEmpresa === '0') {
                traerDatosAgendaPorEmpleado(cedula, fechaInicial, fechaFinal, nombreAsesor);
            } else {
                traerDatosAgendaPorEmpleadoNit(cedula, fechaInicial, fechaFinal, nombreAsesor, nitEmpresa);
            }
        }
    });
    
});



function crearContacto() {

    var nombreContacto = $("#nombreContacto").val();
    var cargoContacto = $("#cargoContacto").val();
    var telefonoContacto = $("#telefonoContacto").val();
    var iddireccion = $("#dlDirOrg").val();
    var nit = $("#listaEmpresas").val();
    var correo = $("#correoContacto").val();

    if (nombreContacto.length === 0 || cargoContacto.length === 0 || telefonoContacto.length === 0 || correo.length === 0) {
        $("#mensajes2").html("<div class='alert alert-dismissible alert-danger'>Por favor verifique que el Nombre de contacto, Cargo, Tel&eacute;fono o Correo electr&oacute;nico sean v&acute;lidos</div>");
    } else {
        $.ajax({
            url: "../trafico/Empresacontactos.php",
            data: {'caso': '2', 'nit': nit, 'nombreContacto': nombreContacto, 'cargoContacto': cargoContacto, 'telefonoContacto': telefonoContacto, 'iddireccion': iddireccion, 'correo': correo},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.length > 0) {
                    listaContactos = "<select id='listaContactos' class='form-control form-control-sm' onclick='retornarDatosContacto()' >";
                    listaContactos += "<option value='0'>...</option>";
                    for (var i = 0; i < obj.length; i++) {
                        listaContactos += "<option value='" + obj[i].id + "'>" + obj[i].nombre + "</option>";

                    }
                    listaContactos += "<option value='-1'>Crear contacto</option>";
                    listaContactos += "</select>";
                    $("#contactoCliente").html(listaContactos);
                    $("#listaContactos").focus();
                    $("#mensajes").html('');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function crearContacto() {...retorno desde el servidor");
            }
        });
    }
}

function validarCampos(idasunto, nit, dlDirOrg, fechaHoraInicio, fechaHoraFin, listaContactos, asuntoPersonal) {

    if (idasunto !== '6') {
        if (idasunto === '0') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor seleccione un asunto de la <strong>lista de asuntos</strong></div>");
            $("#listaAsunto").focus();
            return 0;
        } else if (nit === '0') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor seleccione una empresa de la <strong>lista de empresas</strong></div>");
            $("#listaEmpresas").focus();
            return 0;
        } else if (dlDirOrg === '-1') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor seleccione una direcci&oacute;n de la <strong>lista de direcciones</strong></div>");
            $("#dlDirOrg").focus();
            return 0;
        } else if (listaContactos === '0') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor seleccione un contacto de la <strong>lista de contactos</strong></div>");
            $("#listaContactos").focus();
        } else if (fechaHoraInicio === '') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor seleccione una fecha y hora de inicio v&aacute;lida<br>Recuerde usar formato de 24 horas. Ejemplo la 2:20 pm es la 14:20</div>");
            $("#fechaHoraInicio").focus();
            return 0;
        } else if (fechaHoraFin === '') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor seleccione una fecha y hora de fin v&aacute;lida<br>Recuerde usar formato de 24 horas. Ejemplo la 1:30 pm es la 13:30</div>");
            $("#fechaHoraFin").focus();
            return 0;
        } else {
            return 1;
        }
    } else {
        if (asuntoPersonal.length === 0) {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor ingrese un asunto y/o direcci&oacute;n </div>");
            $("#asuntoPersonal").focus();
            return 0;
        } else if (fechaHoraInicio === '') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor seleccione una fecha y hora de inicio v&aacute;lida<br>Recuerde usar formato de 24 horas. Ejemplo la 2:20 pm es la 14:20</div>");
            $("#fechaHoraInicio").focus();
            return 0;
        } else if (fechaHoraFin === '') {
            $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Por favor seleccione una fecha y hora de fin v&aacute;lida<br>Recuerde usar formato de 24 horas. Ejemplo la 1:30 pm es la 13:30</div>");
            $("#fechaHoraFin").focus();
            return 0;
        } else {
            return 1;
        }
    }
}

function crearEvento(elemento) {
    var id = elemento.id;
    var evento = $("#" + id).val();
    if (evento.length >= 40 || evento.length <= 8) {
        $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>El texto del evento debe estar entre 10 a 40 caracteres.Si desea volver a ver la lista, por favor presione F5</div>");
        $("#asuntoNuevo").val('');
        $("#asuntoNuevo").focus();
    } else {
        $.ajax({
            url: "../trafico/Asunto.php",
            data: {'caso': 1,
                'evento': evento},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === 1) {
                    location.reload(true);
                } else {
                    alert("El asunto creado se encuentra en la lista. Por favor verifique");
                    location.reload(true);

                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function crearEvento(elemento) {...retorno desde el servidor");
            }
        });
    }

}



function listarDirecciones() {

    var nit = $("#listaEmpresas").val();
    var listaEmpresas = $("#listaEmpresas").val();

    if (listaEmpresas === '0') {
        $("#mensajes").html('<div class="alert alert-dismissible alert-success">Por favor seleccione una empresa para crear el evento</div>');
        $("#listaEmpresas").focus();
    } else if (listaEmpresas === '-1') {
        if (confirm("Esta acción le permitira crear una empresa.\nPor favor tenga presente el NIT y el Digito de verificación para su creación")) {
            window.location.href = "../modulos/clientes.php";
        } else {
            $("#listaEmpresas").val('0');
            $("#listaAsunto").focus();
            $("#listaAsunto").val('0');
            $("#mensajes").html('<div class="alert alert-dismissible alert-success">Para crear un evento debe seleccionar un asunto</div>');
        }
    } else {
        $.ajax({
            url: "../trafico/Direcciones.php",
            data: {'caso': '1', 'nit': nit, 'tipo': '0', 'estado': '1'},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.resultado.length > 0) {
                    armarListaDirecciones(obj);
                } else {
                    $("#datosContacto").html('');
                    $("#divDirOrg").html('');
                    mostrarFormularioDireccion(1);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error $('#listaEmpresas').click(function () {...retorno desde el servidor");
            }
        });

        $.ajax({
            url: "../trafico/Empresacontactos.php",
            data: {'caso': '1', 'nit': nit},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.length > 0) {
                    listaContactos = "<select id='listaContactos' class='form-control' onclick='retornarDatosContacto()'>";
                    listaContactos += "<option value='0'>...</option>";
                    for (var i = 0; i < obj.length; i++) {
                        listaContactos += "<option value='" + obj[i].id + "'>" + obj[i].nombre + "</option>";
                    }
                    listaContactos += "<option value='-1'>Crear contacto</option>";
                    listaContactos += "</select>";
                    $("#contactoCliente").html(listaContactos);
                    $("#mensajes").html('');
                } else {
                    $("#contactoCliente").html('');
                    $("#datosContacto").html('');
                    $("#mensajes2").html('');
                    var a = '<div class="alert alert-dismissible alert-warning">La empresa seleccionada no tiene contactos creados. <br>Se debe primero crear el contacto antes de realizar el agendamiento.<br> Para continuar, por favor llene los datos del <strong>Formulario de contacto</strong> luego presione el bot&oacute;n "Crear contacto"</div>';
                    a += '<div id="contactoNuevo">';
                    a += '<table class="table table-hover">';
                    a += '<tr><th colspan="5" style="">Formulario de contacto</th></tr>';
                    a += '<tr><th>Nombre</th><th>Correo electr&oacute;nico</th><th>Cargo</th><th>Tel&eacute;fono</th><th></th></tr>';
                    a += '<tr><td><input type="text" id="nombreContacto" name="nombreContacto" class="form-control" /></td>';
                    a += '<td><input type="email" id="correoContacto" name="correoContacto" class="form-control" /></td>';
                    a += '<td><input type="text" id="cargoContacto" name="cargoContacto" class="form-control" /></td>';
                    a += '<td><input type="number" id="telefonoContacto" name="telefonoContacto" class="form-control" /></td>';
                    a += '<td><input type="button" id="crearContacto" value="Crear contacto" class="form-control" onclick="crearContacto();" /></td>';
                    a += '</tr></table>';
                    a += '</div>';
                    $("#mensajes").html(a);
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error $('#listaEmpresas').click(function () {...retorno desde el servidor");
            }
        });
        $("#divDirOrg").html('');
        $("#mensajes").html('');
        $("#mensajes2").html('');
        $("#contactoCliente").html('');
    }
}

function retornarDatosContacto() {
    var id = $("#listaContactos").val();
    var a = null;
    if (id === '-1') {
        $("#datosContacto").html('');
        a = '<div class="alert alert-dismissible alert-warning">Por favor llene los datos del <strong>Formulario de contacto</strong> y luego presione el bot&oacute;n "Crear contacto"</div>';
        a += '<div id="contactoNuevo">';
        a += '<table class="table table-hover">';
        a += '<tr><th colspan="5" style="">Formulario de contacto</th></tr>';
        a += '<tr><th>Nombre</th><th>Correo electr&oacute;nico</th><th>Cargo</th><th>Tel&eacute;fono</th><th></th></tr>';
        a += '<tr><td><input type="text" id="nombreContacto" name="nombreContacto" class="form-control" /></td>';
        a += '<td><input type="email" id="correoContacto" name="correoContacto" class="form-control" /></td>';
        a += '<td><input type="text" id="cargoContacto" name="cargoContacto" class="form-control" /></td>';
        a += '<td><input type="number" id="telefonoContacto" name="telefonoContacto" class="form-control" /></td>';
        a += '<td><input type="button" id="crearContacto" value="Crear contacto" class="form-control" onclick="crearContacto();" /></td>';
        a += '</tr></table>';
        a += '</div>';
        $("#mensajes").html(a);
    } else {
        $("#mensajes").html('');
        $("#datosContacto").html('');
        $.ajax({
            url: "../trafico/Empresacontactos.php",
            data: {'caso': '3', 'id': id},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.length > 0) {
                    a = '<table class="table table-responsive">';
                    a += '<tr><td>Cargo</td><td>Correo electr&oacute;nico</td><td>Tel&eacute;fono</td></tr>';
                    a += '<tr><td>' + obj[0].cargo + '</td><td>' + obj[0].correo + '</td><td>' + obj[0].telefono + '</td></tr>';
                    a += '</table>';
                    $("#datosContacto").html(a);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error function retornarDatosContacto(){...retorno desde el servidor");
            }
        });
    }
}

function crearDireccion() {
    var empresa = $("#listaEmpresas").val();
    var direccion = $("#direccionDireccion").val();
    var telefono = $("#telefonoDireccion").val();
    var ciudad = $("#listaMunicipios").val();
    if (validarDireccion(empresa, direccion, telefono, ciudad) !== 0) {
        $.ajax({
            url: "../trafico/retornarAsesorEmpresa.php",
            data: {'tipo': 0,
                'documento': empresa,
                'direccion': direccion,
                'telefono': telefono,
                'ciudad': ciudad,
                'opcion': '5'},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj.length > 0) {
                    listarDirecciones();
                    $("#mensajes2").html('');
                } else {
                    $("#mensajes").html('<div class="alert alert-dismissible alert-info">Ha surgido un error, por favor presione F5 e inicie nuevamente el proceso. <strong>Verifique</strong> que el formulario de direcci&oacute;n se encuentre bien diligenciado</div>');
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function crearDireccion() {...");
            }
        });
    }
}

function validarDireccion(empresa, direccion, telefono, ciudad) {
    if (empresa === '0') {
        $("#mensajes").html('<div class="alert alert-dismissible alert-info">Por favor selecione un cliente de la lista de <strong>Clientes</strong></div>');
        $("#listaEmpresas").focus();
        return 0;
    } else if (direccion.length === 0) {
        $("#mensajes").html('<div class="alert alert-dismissible alert-info">Por favor digite una direcci&oacute;n v&aacute;lida</div>');
        $("#direccionDireccion").focus();
        return 0;
    } else if (telefono.length === 0) {
        $("#mensajes").html('<div class="alert alert-dismissible alert-info">Por favor digite un tel&eacute;fono v&aacute;lido</div>');
        $("#telefonoDireccion").focus();
        return 0;
    } else if (ciudad === '0') {
        $("#mensajes").html('<div class="alert alert-dismissible alert-info">Por favor selecione un cliente de la lista de <strong>Ciudad</strong></div>');
        $("#listaMunicipios").focus();
        return 0;
    } else {
        return 1;
    }
}

function cambiarEstadoEvento(valor) {
    var id = valor.id;
    var estado = $("#" + id).val();
    if (estado === '-1') {
        $("#mensajes").html("<div class='alert alert-dismissible alert-info' >Para cambiar el estado del evento, por favor seleccione una opci&oacute;n v&aacute;lida</div>");
        $("#" + id).focus();
    } else if (estado === '2') {
        alert('El evento aparecera como "Pospuesto" y saldrá de la lista. Recuerde por favor volver a crearlo según su programación');
        cambiarEstado(id, estado);
    } else {
        cambiarEstado(id, estado);
    }
}

function cambiarEstado(id, estado) {
    $("#mensajes").html('');
    $.ajax({
        url: "../trafico/Empresacontactos.php",
        data: {'caso': '4', 'id': id, 'estado': estado},
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj) {
                location.reload(true);
            } else {
                $("#mensajes").html('<div class="alert alert-dismissible alert-info">Ha surgido un error, por favor presione F5 e inicie nuevamente el proceso.</div>');
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function cambiarEstadoEvento(valor){...");
        }
    });
}

function armarListaDirecciones(obj) {
    listaDirecciones = "<select id='dlDirOrg' class='form-control' onclick='mostrarFormularioDireccion(this)'>";
    listaDirecciones += "<option value='-1'>...</option>";
    for (var i = 0; i < obj.resultado.length; i++) {
        listaDirecciones += "<option value='" + obj.resultado[i].iddireccion + "'>" + obj.resultado[i].direccion + "</option>";
    }
    listaDirecciones += "<option value='0'>Crear dirección</option>";
    listaDirecciones += "</select>";
    $("#divDirOrg").html(listaDirecciones);
}

function mostrarFormularioDireccion(valor) {
    
    if($("#dlDirOrg").val()==='0'){
        var listaMunicipios = $("#divListaMunicipios").html();
    var a = '<div class="alert alert-dismissible alert-success">Se va a crear una direcci&oacute;n para la empresa seleccionada. <br>Por favor llene el <strong>Formulario de direcci&oacute;n</strong> con los datos correspondientes.<br>Luego presione el bot&oacute;n "Crear direcci&oacute;n"</div>';
    a += '<div id="direccionNueva">';
    a += '<table class="table table-hover">';
    a += '<tr><th colspan="4" style="">Formulario de direcci&oacute;n</th></tr>';
    a += '<tr><th>Tel&eacute;fono</th><th>Direcci&oacute;n</th><th>Ciudad</th><th></th></tr>';
    a += '<tr><td><input type="number" id="telefonoDireccion" name="telefono" class="form-control" /></td>';
    a += '<td><input type="text" id="direccionDireccion" name="direccion" class="form-control" /></td>';
    a += '<td>' + listaMunicipios + '</td>';
    a += '<td><input type="button" id="crearDireccion" value="Crear direcci&oacute;n" class="form-control" onclick="crearDireccion();" /></td>';
    a += '</tr></table>';
    a += '</div>';
    $("#mensajes2").html(a);
    }
    
    if(valor===1){
        var listaMunicipios = $("#divListaMunicipios").html();
    var a = '<div class="alert alert-dismissible alert-danger">La empresa seleccionada no tiene direcciones creadas. <br>Se debe al menos crear una direcci&oacute;n antes de realizar el agendamiento.<br> Para continuar, por favor llene los datos del <strong>Formulario de direcci&oacute;n</strong> luego presione el bot&oacute;n "Crear direcci&oacute;n"</div>';
    a += '<div id="direccionNueva">';
    a += '<table class="table table-hover">';
    a += '<tr><th colspan="4" style="">Formulario de direcci&oacute;n</th></tr>';
    a += '<tr><th>Tel&eacute;fono</th><th>Direcci&oacute;n</th><th>Ciudad</th><th></th></tr>';
    a += '<tr><td><input type="number" id="telefonoDireccion" name="telefono" class="form-control" /></td>';
    a += '<td><input type="text" id="direccionDireccion" name="direccion" class="form-control" /></td>';
    a += '<td>' + listaMunicipios + '</td>';
    a += '<td><input type="button" id="crearDireccion" value="Crear direcci&oacute;n" class="form-control" onclick="crearDireccion();" /></td>';
    a += '</tr></table>';
    a += '</div>';
    $("#mensajes2").html(a);
    }
}

function borrarTextoDiv(){
    $("#mensajeRecordatorio").html('');
}
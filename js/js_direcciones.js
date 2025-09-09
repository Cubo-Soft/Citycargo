var direccionesOrigen = new Array(), direccionesDestino = new Array();
$(document).ready(function () {

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });
    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });
    $("#listaClientes").change(function () {
        $("#mostrarNit").html($("#listaClientes").val());
    });
    $("#buscarDirecciones").click(function () {

        if (parseInt($("#listaClientes").val()) === 0 || parseInt($("#tipoDireccion").val() === 3) || parseInt($("#estadoDireccion").val()) === 3) {
            $("#mensajes").html('<div class="alert alert-dismissible alert-danger">Por favor verifique <br>1.Debe seleccionar un cliente</div>');
        } else {
            $("#mensajes").html('');
            retornarListaDirecciones();
        }

    });
});
function retornarListaDirecciones() {
    var municipios = '';
    $.ajax({
        url: "../trafico/Direcciones.php",
        data: {
            'caso': '1',
            'nit': $("#listaClientes").val(),
            'tipo': $("#tipoDireccion").val(),
            'estado': $("#estadoDireccion").val()
        },
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            if (obj.resultado === 0) {
                municipios = "<select id='municipios'><option value='0' class='custom-select' >...</option>";
                for (var i = 0, max = obj.municipios.length; i < max; i++) {
                    municipios += "<option value='" + obj.municipios[i].mun_id + "'>" + obj.municipios[i].mun_nombre + "</option>";
                }
                municipios += "</select>";

                $("#mensajes").html('<div><table class="table table-hover"><tr><td>Tel&eacute;fono</td><td>Direcci&oacute;n</td><td>Ciudad</td><td></td></tr>\n\
<tr><td><input type="number" id="telefono" class="form-control form-control-sm"/></td>\n\
<td><input type="text" id="direccion" class="form-control form-control-sm"/></td>\n\
<td>' + municipios + '</td><td><input type="button" value="Crear direcci&oacute;n" onclick="crearDireccion()" /></td></tr></table></div>');
                $("#mostrarDirecciones").html('<div class="alert alert-dismissible alert-danger">No hay direcciones a visualizar con los criterios seleccionados</div>');
            } else {
                var direcciones = "<table id='tablaDirecciones' class='table'><tr><th></th><th>Tel&eacute;fono</th><th>Direcci&oacute;n</th><th>Ciudad</th><th>Gesti&oacute;n</th></tr>";
                for (var i = 0, max = obj.resultado.length; i < max; i++) {
                    direcciones += "<tr><td>" + i + "</td><td>" + obj.resultado[i].telefono + "</td><td>" + obj.resultado[i].direccion + "</td><td>" + obj.resultado[i].mun_nombre + "</td><td><input type='checkbox' id='" + obj.resultado[i].iddireccion + "' onclick='cambiarEstadoDireccion(this)' /></td></tr>";
                }
                direcciones += "</table>";
                $("#mostrarDirecciones").html(direcciones);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX funcion $('#listaClientes').change(function () {...");
        }
    });
}

function cambiarEstadoDireccion(dato) {
    var iddireccion = dato.id;
    var estado = $("#estadoDireccion").val();
    if (estado === '1') {
        estado = 0;
    } else {
        estado = 1;
    }
    $.ajax({
        url: "../trafico/Direcciones.php",
        data: {
            'caso': '2',
            'iddireccion': iddireccion,
            'estado': estado
        },
        type: "POST",
        success: function (data) {
            var obj = JSON.parse(data);
            retornarListaDirecciones();
            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX function cambiarEstadoDireccion(dato) {...");
        }
    });
}

function crearDireccion() {
    var telefono = null, direccion = null, idciudad = null,nit=null,tipo=null;
    telefono=$("#telefono").val();
    direccion=$("#direccion").val();
    idciudad=$("#municipios").val();
    alert(idciudad);
    nit=$("#listaClientes").val();
    tipo=$("#tipoDireccion").val();
    $.ajax({
        url: "../trafico/Direcciones.php",
        data: {
            'caso': '3',
            'telefono': telefono,
            'direccion': direccion,
            'idciudad':idciudad,
            'nit':nit,
            'tipo':tipo
        },
        type: "POST",
        success: function (data) {                 
            var obj = JSON.parse(data);
            retornarListaDirecciones();
            $("#mensajes").html('');
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX function crearDireccion() {{...");
        }
    });
}
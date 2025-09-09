function retornarEsSeguridadSocialEntidad(data, opcion) {

    var arreglo = [];

    if (opcion === 1) {
        $.ajax({
            url: "../trafico/CT_es_seguridad_social_entidad.php",
            data: { 'caso': '1' },
            type: "POST",
            success: function (respuesta) {
                var obj = JSON.parse(respuesta);

                var tabla = '<table class="table table-striped" id="tbl_seguridad_social_entidad">';
                tabla += '<thead>';
                tabla += '<tr>';
                tabla += '<th></th><th>Entidad seguridad social</th><th>Entidad</th><th></th>';
                tabla += '</tr>';
                tabla += '</thead>';
                tabla += '<tbody>';

                for (let index = 0; index < obj["es_seguridad_social_entidad"].length; index++) {
                    tabla += '<tr>';
                    tabla += '<td>' + (index + 1) + '</td><td>' + obj["es_seguridad_social_entidad"][index]["entidadSeguridadSocial"] + '</td><td>' + obj["es_seguridad_social_entidad"][index]["nombreEntidad"] + '</td><td><input type="button" value="X" class="btn btn-warning" id="' + obj["es_seguridad_social_entidad"][index]["id"] + '" onclick="borrarEsSeguridadSocialEntidad(this.id,1)" /></td>';
                    tabla += '</tr>';
                }

                tabla += '</tbody>';
                tabla += '</table>';

                $("#divData").html(tabla);
                $("#textoApoyo").html('<center><strong>Relación entre las entidades y los documentos </strong></center>');
                $("#divTexto").html('Esta plantilla modifica la visualización de los documentos solicitados a cualquier entidad, por ejemplo los documentos solicitados a un propietario, o los documentos solicitados a un conductor. Por favor tenga presente que cualquier modificación altera la manera como se ve la plantilla del estudio de seguridad');
                $("#tbl_seguridad_social_entidad").DataTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarEsSeguridadSocialEntidad(datos,opcion=1){...");
            }
        });
    }
}

function crearEsSeguridadSocialEntidad(data, opcion) {

    if (opcion === 1) {

        datosAEnviar.id_entidad_seguridad_social = $("#es_entidades_seguridad_social").val();
        datosAEnviar.id_entidad = $("#es_entidad").val();

        $.ajax({
            url: "../trafico/CT_es_seguridad_social_entidad.php",
            data: { 'caso': '2', 'datosAEnviar': datosAEnviar },
            type: "POST",
            success: function (respuesta) {
                var obj = JSON.parse(respuesta);
                if (obj > 0) {
                    retornarTodoEsSeguridadSocialEntidad();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function crearEsSeguridadSocialEntidad(datos,opcion=1){...");
            }
        });
    }
}

function borrarEsSeguridadSocialEntidad(data, opcion) {

    if (opcion === 1) {

        datosAEnviar.id = data;

        $.ajax({
            url: "../trafico/CT_es_seguridad_social_entidad.php",
            data: { 'caso': '3', 'datosAEnviar': datosAEnviar },
            type: "POST",
            success: function (respuesta) {
                var obj = JSON.parse(respuesta);
                if (obj === 1) {
                    retornarTodoEsSeguridadSocialEntidad();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function borrarEsSeguridadSocialEntidad(datos,opcion=1){...");
            }
        });
    }
}

function retornarTodoEsSeguridadSocialEntidad() {

    $("#divBotonesAccion").show();
    establecerValoresDiv(null, 2);

    var arreglo3 = [];
    arreglo3 = [["span1", "Entidad"], ["span2", "Entidad seguridad social"]];
    establecerValoresDiv(arreglo3, 1);

    var arreglo4 = {};
    arreglo4["nombreDiv"] = "div1";
    arreglo4["id"] = "es_entidades_seguridad_social";
    arreglo4["id_estados"] = null;
    retornarESEntidadesSeguridadSocial(arreglo4, 1);

    var arreglo5 = {};

    arreglo5["nombreDiv"] = "div2";
    arreglo5["id"] = "es_entidad";
    arreglo5["id_estados"] = null;
    arreglo5["funcion"] = null;
    retornarEsEntidad(arreglo5, 1);

    retornarEsSeguridadSocialEntidad(null, 1);

}
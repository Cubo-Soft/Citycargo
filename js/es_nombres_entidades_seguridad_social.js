function retornarESNombresEntidadesSeguridadSocial(data, opcion) {

    if (opcion === 1) {
        $.ajax({
            url: "../trafico/CT_es_nombres_entidades_seguridad_social.php",
            data: { 'caso': '1' },
            type: "POST",
            success: function (retorno) {
                var obj = JSON.parse(retorno);

                //los datos a ser enviados para la construcción de la tabla
                arreglo["idTabla"] = datos["tabla"];
                arreglo["camposCabecera"] = ["Nombre", "Tipo entidad", "Estado", ""];
                arreglo["camposCuerpo"] = ["id", "nombre", "nombreEntidad", "nombreEstado"];
                arreglo["obj"] = obj["es_nombres_entidades_seguridad_social"];
                arreglo["funcion"] = "retornarESNombresEntidadesSeguridadSocial(this.id,2)";

                $("#divData2").html(retornarTabla(arreglo, 1));

                $("#tbl_" + datos["tabla"]).DataTable();

                $("#divBotonesAccion2").show();
                $("#divBotonesAccion3").hide();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarParametricas(datos,opcion=1){...");
            }
        });
    }

    if (opcion === 2) {

        datosAEnviar = {};

        datosAEnviar["id"] = data;

        $.ajax({
            url: "../trafico/CT_es_nombres_entidades_seguridad_social.php",
            data: { 'caso': '2', 'datosAEnviar': datosAEnviar },
            type: "POST",
            success: function (retorno) {
                var obj = JSON.parse(retorno);

                $("#id_es_nombres_entidades_seguridad_social").val(obj["es_nombres_entidades_seguridad_social"][0]["id"]);
                $("#es_entidades_seguridad_social1").val(obj["es_nombres_entidades_seguridad_social"][0]["id_es_entidades_seguridad_social"]);
                $("#es_nombres_estados1").val(obj["es_nombres_entidades_seguridad_social"][0]["id_es_nombres_estados"]);
                $("#nombre2").val(obj["es_nombres_entidades_seguridad_social"][0]["nombre"]);

                $("#modificar2").show();
                $("#crear2").hide();

                $("#divBotonesAccion2").show();
                $("#divBotonesAccion3").hide();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarParametricas(datos,opcion=1){...");
            }
        });
    }
}

function modificarESNombresEntidadesSeguridadSocial(data, opcion) {

    if (opcion === 1) {

        $.ajax({
            url: "../trafico/CT_es_nombres_entidades_seguridad_social.php",
            data: { 'caso': '3', 'datosAEnviar': data },
            type: "POST",
            success: function (retorno) {
                var obj = JSON.parse(retorno);

                if (obj === 1) {
                    $("#nombre2").val('');
                    $("#es_entidades_seguridad_social1").val('-1');
                    $("#es_nombres_estados1").val('-1');
                    $("#modificar2").hide();
                    $("#crear2").show();
                    $("#id_es_nombres_entidades_seguridad_social").val('0');
                    retornarTodoEsNombresEntidadesSeguridadSocial();
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function modificarESNombresEntidadesSeguridadSocial(datos,opcion=1){...");
            }
        });
    }
}

function crearESNombresEntidadesSeguridadSocial(data, opcion) {
    if (opcion === 1) {

        $.ajax({
            url: "../trafico/CT_es_nombres_entidades_seguridad_social.php",
            data: { 'caso': '4', 'datosAEnviar': data },
            type: "POST",
            success: function (retorno) {
                var obj = JSON.parse(retorno);
                if (obj >= 0) {
                    $("#nombre2").val('');
                    $("#es_entidades_seguridad_social1").val('-1');
                    $("#es_nombres_estados1").val('-1');
                    $("#modificar2").hide();
                    $("#crear2").show();
                    retornarTodoEsNombresEntidadesSeguridadSocial();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function modificarESNombresEntidadesSeguridadSocial(datos,opcion=1){...");
            }
        });
    }
}

function retornarTodoEsNombresEntidadesSeguridadSocial() {
    var arregloLocal = {};

    arregloLocal["nombreDiv"] = "divNombresEstados1";
    arregloLocal["id"] = "es_nombres_estados1";
    arregloLocal["id_estados"] = 4;
    retornarESNombresEstados(arregloLocal, 3);

    arreglo["nombreDiv"] = null;
    arreglo["id"] = null;
    arreglo["id_estados"] = null;

    arreglo["nombreDiv"] = "divEntidadesSeguridadSocial1";
    arreglo["id"] = "es_entidades_seguridad_social1";
    arreglo["id_estados"] = null;
    retornarESEntidadesSeguridadSocial(arreglo, 1);

    datos["tabla"] = 'es_nombres_entidades_seguridad_social';
    retornarESNombresEntidadesSeguridadSocial(datos, 1);
}


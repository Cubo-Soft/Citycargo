function retornarESDocumentosEntidad(data, opcion) {

    var arreglo = [];

    if (opcion === 1) {
        $.ajax({
            url: "../trafico/CT_es_documentos_entidad.php",
            data: { 'caso': '1' },
            type: "POST",
            success: function (respuesta) {
                var obj = JSON.parse(respuesta);
                
                var tabla = '<table class="table table-striped" id="tbl_documentos_entidad">';
                tabla += '<thead>';
                tabla += '<tr>';
                tabla += '<th></th><th>Entidad</th><th>Documento</th><th>URL</th><th></th>';
                tabla += '</tr>';
                tabla += '</thead>';
                tabla += '<tbody>';

                for (let index = 0; index < obj["es_documentos_entidad"].length; index++) {
                    tabla += '<tr>';
                    tabla += '<td>' + (index + 1) + '</td><td>' + obj["es_documentos_entidad"][index]["nombreEntidad"] + '</td><td>' + obj["es_documentos_entidad"][index]["nombreDocumento"] + '</td><td><input type="text" id="url_' + obj["es_documentos_entidad"][index]["id"] + '" value="' + obj["es_documentos_entidad"][index]["url"] + '" class="form form-control" onblur="modificarEsDocumentos(this.id,1)" /></td><td><input type="button" value="X" class="btn btn-warning" id="' + obj["es_documentos_entidad"][index]["id"] + '" onclick="borrarESDocumentosEntidad(this.id,1)" /></td>';
                    tabla += '</tr>';
                }

                tabla += '</tbody>';
                tabla += '</table>';
                tabla += '</form>';

                $("#divData").html(tabla);
                $("#textoApoyo").html('<center><strong>Relación entre las entidades y los documentos </strong></center>');
                $("#divTexto").html('Esta plantilla modifica la visualización de los documentos solicitados a cualquier entidad, por ejemplo los documentos solicitados a un propietario, o los documentos solicitados a un conductor. Por favor tenga presente que cualquier modificación altera la manera como se ve la plantilla del estudio de seguridad');
                $("#tbl_documentos_entidad").DataTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarESDocumentosEntidad(data,opcion=1)...");
            }
        });
    }

    if (opcion === 2) {

        datosAEnviar.id_es_entidad = $("#es_entidad").val();

        $.ajax({
            url: "../trafico/CT_es_documentos_entidad.php",
            data: { 'caso': '4', 'datosAEnviar': datosAEnviar },
            type: "POST",
            success: function (respuesta) {
                var obj = JSON.parse(respuesta);

                if (obj.length > 0) {

                    var tabla ='<form id="documentos_entidad_1" action="#" enctype="multipart/form-data" method="post" >';
                    tabla += '<table class="table table-striped">';
                    tabla += '<thead>';
                    tabla += '<tr>';
                    tabla += '<th></th><th>Entidad</th><th>Nombre documento</th><th>Fecha vencimiento</th><th>Archivo</th><th></th>';
                    tabla += '</tr>';
                    tabla += '</thead>';
                    tabla += '<tbody>';

                    for (let index = 0; index < obj.length; index++) {
                        tabla += '<tr>';
                        tabla += '<td>'+(index+1)+'</td><td>'+obj[index]["nombreEntidad"]+'</td>';
                        //fe1 = fe de fecha 1 es el diferenciador
                        tabla += '<td>'+obj[index]["nombreDocumento"]+'</td><td><input type="date" class="form form-control" id="fe1_'+obj[index]["id"]+'" /></td>';
                        //fl1 = fl de file 1 es el diferenciador
                        tabla += '<td><input type="file" name="fl1_'+obj[index]["id"]+'" id="fl1_'+obj[index]["id"]+'" accept=".jpg,.png,.pdf,.jpeg" ></td>';
                        //bt1 = bt de botón 1 es el diferenciador
                        tabla += '<td><input type="button" value="Grabar" id="bt1_'+obj[index]["id"]+'" class="btn btn-success" onclick="grabarCuEsEstudioDocumentos(this.id,1)"/></td>';
                        tabla += '</tr>';
                    }

                    tabla += '</tbody>';
                    tabla += '</table>';
                    tabla += '</form>';

                    $("#head_es_documentos_entidad").html("<div class='alert alert-success'>De la lista de documentos a solicitar para esta entidad por favor a medida que va incluyendo </div>");
                    $("#es_documentos_entidad").html(tabla);

                } else {

                    //aqui para cuando no esta parametrizado la plantilla 
                    $("#es_documentos_entidad").html("<div class='alert alert-warning'>No se encuentran valores parametrizados para esta sección</div>");
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function crearESDocumentosEntidad(data,opcion=2)...");
            }
        });

    }
}

function crearESDocumentosEntidad(data, opcion) {

    if (opcion === 1) {

        datosAEnviar.id_es_documentos = $("#es_documentos").val();
        datosAEnviar.id_es_entidad = $("#es_entidad").val();
        datosAEnviar.url = $("#url").val();

        $.ajax({
            url: "../trafico/CT_es_documentos_entidad.php",
            data: { 'caso': '2', 'datosAEnviar': datosAEnviar },
            type: "POST",
            success: function (respuesta) {
                var obj = JSON.parse(respuesta);

                if (obj === -1) {
                    $("#divMensajes").html('<div class="alert alert-danger">Relación ya creada. Por favor verifique</div>');
                } else {
                    $("#divMensajes").html('<div class="alert alert-success">Correcto, relación creada</div>');
                    retornarTodoEsDocumentosEntidad();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function crearESDocumentosEntidad(data,opcion=1)...");
            }
        });
    }
}

function borrarESDocumentosEntidad(data, opcion) {
    if (opcion === 1) {

        datosAEnviar.id = data;

        $.ajax({
            url: "../trafico/CT_es_documentos_entidad.php",
            data: { 'caso': '3', 'datosAEnviar': datosAEnviar },
            type: "POST",
            success: function (respuesta) {
                var obj = JSON.parse(respuesta);
                if (obj === 1) {
                    retornarTodoEsDocumentosEntidad();
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function borrarESDocumentosEntidad(data,opcion=1)...");
            }
        });
    }
}

function retornarTodoEsDocumentosEntidad() {

    retornarESDocumentosEntidad(null, 1);

    var arreglo = {};

    arreglo["nombreDiv"] = "div1";
    arreglo["id"] = "es_entidad";
    arreglo["id_estados"] = null;
    retornarEsEntidad(arreglo, 1);

    var arreglo2 = {};
    arreglo2["nombreDiv"] = "div2";
    arreglo2["id"] = "es_documentos";
    arreglo2["id_estados"] = null;
    retornarEsDocumentos(arreglo2, 1);

    var arreglo3 = [];

    arreglo3 = [["span1", "Entidad"], ["span2", "Documentos"], ["span3", "Dirección para consulta"], ["div3", "<input type='text' class='form form-control' id='url'/>"]];
    establecerValoresDiv(arreglo3, 1);

    $("#divBotonesAccion").show();
}
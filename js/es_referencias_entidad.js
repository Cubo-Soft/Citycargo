function retornarEsReferenciasEntidad(data, opcion) {
    var arreglo = [];

    if (opcion === 1) {
        $.ajax({
            url: "../trafico/CT_es_referencias_entidad.php",
            data: { 'caso': '3' },
            type: "POST",
            success: function (respuesta) {
                var obj = JSON.parse(respuesta);

                var tabla = '<table class="table table-striped" id="tbl_referencias_entidad">';
                tabla += '<thead>';
                tabla += '<tr>';
                tabla += '<th></th><th>Nombre referencia</th><th>Vínculo</th><th></th>';
                tabla += '</tr>';
                tabla += '</thead>';
                tabla += '<tbody>';

                for (let index = 0; index < obj["es_referencias_entidad"].length; index++) {
                    tabla += '<tr>';
                    tabla += '<td>' + (index + 1) + '</td><td>' + obj["es_referencias_entidad"][index]["nombreReferencia"] + '</td><td>' + obj["es_referencias_entidad"][index]["nombreVinculo"] + '</td><td><input type="button" value="X" class="btn btn-warning" id="' + obj["es_referencias_entidad"][index]["id"] + '" onclick="borrarESReferenciasEntidad(this.id,1)" /></td>';
                    tabla += '</tr>';
                }

                tabla += '</tbody>';
                tabla += '</table>';

                $("#divData").html(tabla);
                $("#textoApoyo").html('<center><strong>Relación entre las referencias y los vínculo </strong></center>');
                $("#divTexto").html('Esta plantilla modifica la visualización de los documentos solicitados a cualquier entidad, por ejemplo los documentos solicitados a un propietario, o los documentos solicitados a un conductor. Por favor tenga presente que cualquier modificación altera la manera como se ve la plantilla del estudio de seguridad');
                $("#tbl_referencias_entidad").DataTable();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarEsReferenciasEntidad(data,opcion=1)...");
            }
        });
    }
}

function crearEsReferenciasEntidad(data,opcion){

    datosAEnviar.id_es_tipo_referencia=$("#es_tipo_referencia").val();
    datosAEnviar.id_es_nombre_vinculo=$("#es_nombre_vinculo").val();

    $.ajax({
        url: "../trafico/CT_es_referencias_entidad.php",
        data: { 'caso': '4','datosAEnviar':datosAEnviar},
        type: "POST",
        success: function (retorno) {               
          var obj = JSON.parse(retorno);  
            if(obj>0){
                retornarTodoEsReferenciasEntidad();
            }                                                
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function crearEsReferenciasEntidad(data,opcion=1)...");
        }
    });

}

function borrarESReferenciasEntidad(data,opcion) {
    
    datosAEnviar.id=data;    

    $.ajax({
        url: "../trafico/CT_es_referencias_entidad.php",
        data: { 'caso': '5','datosAEnviar':datosAEnviar},
        type: "POST",
        success: function (retorno) {               
          var obj = JSON.parse(retorno);                        
            if(obj===1){
                retornarTodoEsReferenciasEntidad();
            }                                                            
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function borrarESReferenciasEntidad(data,opcion=1)...");
        }
    });

}

function retornarTodoEsReferenciasEntidad() {
    
    $("#divBotonesAccion").show();

    establecerValoresDiv(null, 2);

    var arreglo3 = [];
    arreglo3 = [["span1", "Nombre referencia"], ["span2", "Vínculo"]];
    establecerValoresDiv(arreglo3, 1);

    var arreglo = {};
    arreglo["nombreDiv"] = "div1";
    arreglo["id"] = "es_tipo_referencia";
    arreglo["id_estados"] = null;
    retornarEsTipoReferencia(arreglo, 1);

    var arreglo4={};
    arreglo4["nombreDiv"] = "div2";
    arreglo4["id"] = "es_nombre_vinculo";
    arreglo4["id_estados"] = null;
    retornarEsNombreVinculo(arreglo4,1);

    retornarEsReferenciasEntidad(null,1);

}

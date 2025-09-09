function retornarEsDocumentos(data,opcion){

    var arregloLocal={};

    if(opcion===1){
        $.ajax({
            url: "../trafico/CT_es_documentos.php",
            data: { 'caso': '1' },
            type: "POST",
            success: function (respuesta) {                
                var obj = JSON.parse(respuesta);
                arregloLocal["id"]=data["id"];
                arregloLocal["arreglo"]=obj["es_documentos"];              
                arregloLocal["valor"]="id";
                arregloLocal["texto"]="nombre";
                arregloLocal["funcion"]=null;
                $("#"+data["nombreDiv"]).html(retornarSelect(arregloLocal, 1));                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarEsEntidad(data,opcion=1)...");
            }
        });
    }
}

function modificarEsDocumentos(data,opcion) {
    
    var arregloLocal={};

    datosAEnviar.id=obtenerPartePosteriorGuionBajo(data);
    datosAEnviar.url=$("#url_"+datosAEnviar.id).val();

    if(opcion===1){
        $.ajax({
            url: "../trafico/CT_es_documentos.php",
            data: { 'caso': '2','datosAEnviar':datosAEnviar },
            type: "POST",
            success: function (respuesta) {     
                var obj = JSON.parse(respuesta);
                if(obj===1){
                    retornarTodoEsDocumentosEntidad();
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function modificarEsDocumentos(data,opcion=1)...");
            }
        });
    }

}
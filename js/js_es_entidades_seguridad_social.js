function retornarESEntidadesSeguridadSocial(datos,opcion){

    if(opcion===1){

        $.ajax({
            url: "../trafico/CT_es_entidades_seguridad_social.php",
            data: { 'caso': '1'},
            type: "POST",
            success: function (retorno) {                      
              var obj = JSON.parse(retorno);            
              arreglo["id"]=datos["id"];
              arreglo["arreglo"]=obj["es_entidades_seguridad_social"];
              arreglo["valor"]="id";
              arreglo["texto"]="nombre";
              arreglo["funcion"]=null;              
              $("#"+datos["nomDiv"]).html(retornarSelect(arreglo, 1));                            
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarESEntidadesSeguridadSocial(data,opcion=1)...");
            }
        });
    }
}
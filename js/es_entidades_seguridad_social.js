function retornarESEntidadesSeguridadSocial(datos,opcion){

    var arregloLocal={};

    if(opcion===1){

        $.ajax({
            url: "../trafico/CT_es_entidades_seguridad_social.php",
            data: { 'caso': '1'},
            type: "POST",
            success: function (retorno) {               
              var obj = JSON.parse(retorno);  
              arregloLocal["arreglo"]=null;
              arregloLocal["id"]=datos["id"];
              arregloLocal["arreglo"]=obj["es_entidades_seguridad_social"];              
              arregloLocal["valor"]="id";
              arregloLocal["texto"]="nombre";
              arregloLocal["funcion"]=null;              
              $("#"+datos["nombreDiv"]).html(retornarSelect(arregloLocal, 1));                                          
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarESEntidadesSeguridadSocial(data,opcion=1)...");
            }
        });
    }
}
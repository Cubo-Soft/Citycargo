function retornarEsNombreVinculo(data,opcion){

    var arregloLocal={};

    if(opcion===1){
        $.ajax({
            url: "../trafico/CT_es_nombre_vinculo.php",
            data: { 'caso': '1' },
            type: "POST",
            success: function (respuesta) {                
                var obj = JSON.parse(respuesta);
                arregloLocal["id"]=data["id"];
                arregloLocal["arreglo"]=obj["es_nombre_vinculo"];              
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

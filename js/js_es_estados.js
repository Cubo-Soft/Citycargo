/*
retorna lo necesario en función de la opcion ingresada
data puede ser un arreglo o una variable de cualquier tipo
en función del parámetro opción */
function retornarEsEstados(data,opcion) {
    
    /*arma una lista desplegable para ser ubicada segun 
    el nombreDiv ingresado en data["nombreDiv"]
    */

    var arreglo=[];

    if(opcion===1){        
        $.ajax({
            url: "../trafico/CT_es_estados.php",
            data: { 'caso': '1'},
            type: "POST",
            success: function (retorno) {      
              var obj = JSON.parse(retorno);
            
              arreglo["id"]=data["id"];
              arreglo["arreglo"]=obj["es_estados"];
              arreglo["valor"]="id";
              arreglo["texto"]="nombre";
              arreglo["funcion"]=null;
              $("#"+data["nombreDiv"]).html(retornarSelect(arreglo, 1));

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarEsEstados(data,opcion=1)...");
            }
        });
    }
}
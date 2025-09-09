/*
retorna lo necesario en función de la opcion ingresada
data puede ser un arreglo o una variable de cualquier tipo
en función del parámetro opción */
function retornarEsEstados(data,opcion) {
    
    /*arma una lista desplegable para ser ubicada segun 
    el nombreDiv ingresado en data["nombreDiv"]
    */

    var arregloLocal={};

    if(opcion===1){        
        $.ajax({
            url: "../trafico/CT_es_estados.php",
            data: { 'caso': '1'},
            type: "POST",
            success: function (retorno) {   
              var obj = JSON.parse(retorno);   
              arregloLocal["id"]=data["id"];
              arregloLocal["arreglo"]=obj["es_estados"];              
              arregloLocal["valor"]="id";
              arregloLocal["texto"]="nombre";
              arregloLocal["funcion"]=null;
              $("#"+data["nombreDiv"]).html(retornarSelect(arregloLocal, 1));

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarEsEstados(data,opcion=1)...");
            }
        });
    }
}
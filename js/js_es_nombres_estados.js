var arreglo = [],datosAEnviar={};
function retornarESNombresEstados(datos, opcion) {

    if (opcion === 1) {
        $.ajax({
            url: "../trafico/CT_es_nombres_estados.php",
            data: { 'caso': '1' },
            type: "POST",
            success: function (retorno) {
                var obj = JSON.parse(retorno);

                //los datos a ser enviados para la construcción de la tabla
                arreglo["idTabla"]=datos["tabla"];
                arreglo["camposCabecera"]=["Id","Nombre estado","Estado"];
                arreglo["camposCuerpo"]=["id","nombreEstado","nombre"];
                arreglo["obj"]=obj["es_nombres_estados"];                
                arreglo["funcion"]="retornarESNombresEstados(this.id,2)";
                $("#divData2").html(retornarTabla(arreglo,1));                

                $("#tbl_" + datos["tabla"]).DataTable();

                $("#divBotonesAccion3").show();
                $("#divBotonesAccion2").hide();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarParametricas(datos,opcion=1){...");
            }
        });
    }

    if (opcion === 2) {

        datosAEnviar.id=datos;

        $("#id_es_nombres_estados").val('0');
        $.ajax({
            url: "../trafico/CT_es_nombres_estados.php",
            data: { 'caso': '3',"datosAEnviar":datosAEnviar },
            type: "POST",
            success: function (retorno) {
                var obj = JSON.parse(retorno);
                if(obj.length>0){                    
                    $("#es_estados1").val(obj[0].id_estados);
                    $("#nombre3").val(obj[0].nombre);
                    $("#id_es_nombres_estados").val(obj[0].id);
                    $("#crear3").hide();
                    $("#modificar3").show();
                }else{
                    datos["nombreDiv"]="divMensajes";
                    datos["mensaje"]="Podría presionar F5 e intenar nuevamente? Ha surgido un error al momento de retornar el registro";                    
                    retornarMensajes(datos,1);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarParametricas(datos,opcion=2){...");
            }
        });
    }

    if (opcion === 3) {

        datosAEnviar.id_estados=datos["id_estados"];

        $.ajax({
            url: "../trafico/CT_es_nombres_estados.php",
            data: { 'caso': '5','datosAEnviar':datosAEnviar },
            type: "POST",
            success: function (retorno) {
                var obj = JSON.parse(retorno);
                arreglo["id"]=datos["id"];
                arreglo["arreglo"]=obj["es_nombres_estados"];
                arreglo["valor"]="id";
                arreglo["texto"]="nombre";
                arreglo["funcion"]=null;                
                $("#"+datos["nombreDiv"]).html(retornarSelect(arreglo, 1));
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarParametricas(datos,opcion=1){...");
            }
        });
    }

}

function crearEsNombresEstados(data, opcion) {
    if (opcion === 1) {
        $.ajax({
            url: "../trafico/CT_es_nombres_estados.php",
            data: { 'caso': '2', 'datosAEnviar': data },
            type: "POST",
            success: function (retorno) {
                var obj = JSON.parse(retorno);
                if(obj===1){
                    $("#nombre3").val('');
                    $("#es_estados1").val('-1');
                    datos["tabla"]='es_nombres_estados';
                    retornarESNombresEstados(datos,1);
                }else{
                    datos["nombreDiv"]="divMensajes";
                    datos["mensaje"]="Podría presionar F5 e intenar nuevamente? Ha surgido un error al momento de crear el registro";                    
                    retornarMensajes(datos,1);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function crearEsNombresEstados(datos,opcion=1){...");
            }
        });
    }
}

function modificarEsNombresEstados(data,opcion){

    if(opcion===1){
        $.ajax({
            url: "../trafico/CT_es_nombres_estados.php",
            data: { 'caso': '4', 'datosAEnviar': data },
            type: "POST",
            success: function (retorno) {
                var obj = JSON.parse(retorno);
                if(obj===1){
                    $("#nombre3").val('');
                    $("#es_estados1").val('-1');
                    datos["tabla"]='es_nombres_estados';
                    retornarESNombresEstados(datos,1);
                }else{
                    datos["nombreDiv"]="divMensajes";
                    datos["mensaje"]="Podría presionar F5 e intenar nuevamente? Ha surgido un error al momento de modificar el registro";                    
                    retornarMensajes(datos,1);
                }
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function crearEsNombresEstados(datos,opcion=4){...");
            }
        });
    }
}

var datosAEnviar = {};

/** 
 * @param {*} data data["id"];data["arreglo"];data["valor"];data["textoAMostrar"];data["funcion"];
 * @param {*} opcion 
 * @returns el select solicitado
 */
function retornarSelect(data, opcion) {
    var select = '';

    if (data["funcion"] !== null) {
        select = '<select id="' + data["id"] + '" class="form form-control" onchange="' + data["funcion"] + '" >';
    } else {
        select = '<select id="' + data["id"] + '" class="form form-control" >';
    }

    select += '<option value="-1">...</option>';
    for (let a = 0; a < data["arreglo"].length; a++) {

        //evaluar si la posición "predeterminado" exite en el arreglo data 
        if("predeterminado" in data){

            if(data["predeterminado"]===data["arreglo"][a][data["valor"]]){
                select += '<option value="' + data["arreglo"][a][data["valor"]] + '" selected>' + data["arreglo"][a][data["texto"]] + '</option>';
            }
            
        }else{
            select += '<option value="' + data["arreglo"][a][data["valor"]] + '">' + data["arreglo"][a][data["texto"]] + '</option>';
        }

        
    }
    select += '</select>';

    return select;
}

function retornarMensajes(data, opcion) {
    $("#" + data["nombreDiv"]).html("<div class='alert alert-danger'>" + data["mensaje"] + "</div>");
}

function validarFormulario(opcion) {

    var retorno = 0;

    if (opcion === 1) {
        if ($("#es_estados").val() === '-1') {
            $("#divMensajes").html('<div class="alert alert-danger">Por favor seleccione un estado de la lista </div>');
            $("#es_estados").focus();
            retorno = 0;
        } else {
            retorno += 1;
        }

        if ($("#nombre3").val() === '' || $("#nombre3").val().length === 0) {
            $("#divMensajes").html('<div class="alert alert-danger">Por favor digite el nombre del estado </div>');
            $("#nombre3").focus();
            retorno = 0;
        } else {
            retorno += 1;
        }
    }

    return retorno;

}


/**
 * 
 * @param {*} data = el arreglo ["camposCuerpo"] debe tener como primer parametro el campo de tipo id de la tabla
 * @param {*} opcion 
 */

function retornarTabla(data, opcion) {

    var tabla = "<table class='display' id='tbl_" + datos["tabla"] + "'>";
    var totalCampos = 0;
    tabla += '<thead>';
    tabla += "<tr>";
    for (let a = 0; a < data["camposCabecera"].length; a++) {

        if(a===0){
            tabla += "<th></th>";
        }

        tabla += "<th>" + data["camposCabecera"][a] + "</th>";
    }
    tabla += "</tr>";
    tabla += '</thead>';
    tabla += '<tbody>';

   totalCampos = data["camposCuerpo"].length-1;

    for (let a = 0; a < data["obj"].length; a++) {

        tabla += "<tr>";

        for (let b = 0; b < data["camposCuerpo"].length; b++) {
            tabla += "<td>" + data["obj"][a][data["camposCuerpo"][b]] + "</td>";

            if (b === totalCampos) {
                tabla += "<td><input type='button' class='btn btn-success' value='S' id='" + data["obj"][a][data["camposCuerpo"][0]] + "' onclick='retornarESNombresEstados(this.id,2)' /></td>";
            }
        }

        tabla += "</tr>";
    }
    tabla += '</tbody>';
    tabla += '</table>';    

    return tabla;

}
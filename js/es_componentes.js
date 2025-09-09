$(document).ready(function () {

    // Script para cambiar el color del botón al hacer clic
    $('.botonIndex').on('click', function () {
        $("#modificar").hide();
        $("#crear").show();
        // Resetea todos los botones a 'btn-success'
        $('.botonIndex').removeClass('btn-success').addClass('btn-success');
        // Establece el botón clicado a 'btn-success'
        $(this).removeClass('btn-success').addClass('btn-success');
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

});

var datosAEnviar = {};

/** 
 * @param {*} data data["id"];data["arreglo"];data["valor"];data["textoAMostrar"];data["funcion"];
 * @param {*} opcion 
 * @returns el select solicitado
 */
function retornarSelect(data, opcion) {
    var select = '';

    if (data["funcion"] !== null) {
        select = '<select id="' + data["id"] + '" name="' + data["id"] + '" class="form form-control" onchange="' + data["funcion"] + '" >';
    } else {
        select = '<select id="' + data["id"] + '" name="' + data["id"] + '" class="form form-control" >';
    }

    select += '<option value="-1">...</option>';
    for (let a = 0; a < data["arreglo"].length; a++) {

        //evaluar si la posición "predeterminado" exite en el arreglo data 
        if ("predeterminado" in data) {

            if (data["predeterminado"] === data["arreglo"][a][data["valor"]]) {
                select += '<option value="' + data["arreglo"][a][data["valor"]] + '" selected>' + data["arreglo"][a][data["texto"]] + '</option>';
            }

        } else {
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

        if (a === 0) {
            tabla += "<th></th>";
        }

        tabla += "<th>" + data["camposCabecera"][a] + "</th>";
    }
    tabla += "</tr>";
    tabla += '</thead>';
    tabla += '<tbody>';

    totalCampos = data["camposCuerpo"].length - 1;

    for (let a = 0; a < data["obj"].length; a++) {

        tabla += "<tr>";

        for (let b = 0; b < data["camposCuerpo"].length; b++) {
            tabla += "<td>" + data["obj"][a][data["camposCuerpo"][b]] + "</td>";

            if (b === totalCampos) {
                tabla += "<td><input type='button' class='btn btn-success' value='S' id='" + data["obj"][a][data["camposCuerpo"][0]] + "' onclick='" + data["funcion"] + "' /></td>";
            }
        }

        tabla += "</tr>";
    }
    tabla += '</tbody>';
    tabla += '</table>';

    return tabla;

}

function obtenerPartePosteriorGuionBajo(cadena) {
    const posicionGuionBajo = cadena.indexOf("_");
    if (posicionGuionBajo !== -1) {
        return cadena.slice(posicionGuionBajo + 1);
    } else {
        return "";
    }
}

/**
 * 
 * @param {*} data => debe ser un arreglo de tipo [["id Div o Span","texto a mostrar"],["id Div o Span","texto a mostrar"]]
 * @param {*} opcion => 1 para establecer los valores, 2 para borrar los valores de las etiquedas que menciona var arreglo=[]
 */
function establecerValoresDiv(data, opcion) {

    if (opcion === 1) {
        for (let index = 0; index < data.length; index++) {
            $("#" + data[index][0]).html(data[index][1]);
        }
    }

    if (opcion === 2) {
        var arreglo = ["span1", "span2", "span3", "span4", "div1", "div2", "div3", "div4"];
        for (let index = 0; index < arreglo.length; index++) {
            $("#" + arreglo[index]).html("");
        }
    }
}

function validarPlaca(idInput) {  
    var inputElement = $("#" + idInput);  
    var inputValue = inputElement.val().trim();
  
    // Regular expression pattern for the desired format
    var regex = /^[A-Z]{3}[0-9]{3}$/;  
    
    if (regex.test(inputValue)) {
      return 1;
    } else {
      return 0;
    }
  }

  function obtenerTextoDeSelect(idSelect){
    return $('select[id="'+idSelect+'"] option:selected').text();
}

function establecerDocumento(){
    if($("#placa").is(":visible")){
        datosAEnviar.documento=$("#placa").val();
    }
    
    if($("#cedula").is(":visible")){
        datosAEnviar.documento=$("#cedula").val();
    }
}
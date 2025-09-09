var datos = [];
$(document).ready(function () {

    $("#divBotonesAccion").hide();
    $("#divBotonesAccion2").hide();
    $("#divBotonesAccion3").hide();
    $("#modificar3").hide();
    $("#modificar2").hide();

    $("#divParametricas").hide();
    $("#divRelacionesParametricas").hide();

    var arreglo=[];

    arreglo["nombreDiv"]="divEsEstados";
    arreglo["id"]="es_estados1";
    arreglo["id_estados"]=null;
    retornarEsEstados(arreglo,1);

    arreglo["nombreDiv"]="divEntidadesSeguridadSocial1";
    arreglo["id"]="es_entidades_seguridad_social1";
    arreglo["id_estados"]=null;
    retornarESEntidadesSeguridadSocial(arreglo,1);
    
    arreglo["nomDiv"]="divEstados1";
    arreglo["id_estados"]="4";
    datos["id"]="es_nombres_estados1";    
    retornarESNombresEstados(arreglo, 3);
        
    $("#mostrarParametricas").on('click',function(){
        $("#divParametricas").show();
        $("#divRelacionesParametricas").hide();
    });

    $("#mostrarRelacionesParametricas").on('click',function(){
        $("#divRelacionesParametricas").show();
        $("#divParametricas").hide();
    });
    
    // Script para cambiar el color del botón al hacer clic
    $('.botonIndex').on('click', function () {
        $("#modificar").hide();
        $("#crear").show();
        // Resetea todos los botones a 'btn-success'
        $('.botonIndex').removeClass('btn-success').addClass('btn-success');
        // Establece el botón clicado a 'btn-success'
        $(this).removeClass('btn-success').addClass('btn-success');
    });

    $("#es_nombres_estados").on("click",function(){
        datos["tabla"]='es_nombres_estados';
        retornarESNombresEstados(datos,1);        
    });

    $("#es_documentos").on("click", function () {
        datos["tabla"] = 'es_documentos';
        $("#tabla").val('es_documentos');
        retornarParametricas(datos, 1);
    });

    $("#es_entidad").on("click", function () {
        datos["tabla"] = 'es_entidad';
        $("#tabla").val('es_entidad');
        retornarParametricas(datos, 1);
    });

    $("#es_tipo_referencia").on("click", function () {
        datos["tabla"] = 'es_tipo_referencia';
        $("#tabla").val('es_tipo_referencia');
        retornarParametricas(datos, 1);
    });

    $("#es_nombre_vinculo").on("click", function () {
        datos["tabla"] = 'es_nombre_vinculo';
        $("#tabla").val('es_nombre_vinculo');
        retornarParametricas(datos, 1);
    });

    $("#es_estados").on("click", function () {
        datos["tabla"] = 'es_estados';
        $("#tabla").val('es_estados');
        retornarParametricas(datos, 1);
    });

    $("#es_entidades_seguridad_social").on("click", function () {
        datos["tabla"] = 'es_entidades_seguridad_social';
        $("#tabla").val('es_entidades_seguridad_social');
        retornarParametricas(datos, 1);
    });

    $("#crear").on("click", function () {
        if ($("#nombre").val().length === 0) {
            $("#divMensajes").html("<div class='alert alert-danger'>Por favor ingrese un nombre válido para el valor.Por favor sea lo más estricto posible en el uso de la ortografía</div>");
            $("#nombre").focus();
        } else if ($("#tabla").val() === '') {
            $("#divMensajes").html("<div class='alert alert-danger'>Por favor seleccione la tabla a ingresar el valor. Debe presionar un botón del lado derecho!. Una vez lo haga, por favor vuelva a presionar el botón 'Crear'</div>");
            $("#crear").focus();
        } else {
            $("#divMensajes").html("");
            datos["tabla"] = $("#tabla").val();
            datos["valorCampo"] = $("#nombre").val();
            crearParametricas(datos, 1);
        }
    });

    $("#modificar").on("click", function () {
        var datos={};    
        datos.valorCampo=$("#nombre").val();
        datos.id=$("#id").val();
        datos.tabla=$("#tabla").val();
        actualizarParametricas(datos,1);
    });

    $("#crear3").on("click",function(){
        if(validarFormulario(1)===2){
            var datos={};    
            datos.nombre=$("#nombre3").val();
            datos.id_estados=$("#es_estados1").val();
            crearEsNombresEstados(datos, 1);
        }        
    });

    $("#modificar3").on("click",function(){
        if(validarFormulario(1)===2){
            var datos={};    
            datos.nombre=$("#nombre3").val();
            datos.id_estados=$("#es_estados1").val();
            datos.id=$("#id_es_nombres_estados").val();
            modificarEsNombresEstados(datos, 1);
        }    
    });

    $("#es_nombres_entidades_seguridad_social").on("click",function(){
        datos["tabla"]='es_nombres_entidades_seguridad_social';
        retornarESNombresEntidadesSeguridadSocial(datos,1);
    });

});

function iniciarActualizacion(data, opcion) {
    $("#modificar").show();
    $("#crear").hide();
    
    var indiceEspacio = data.indexOf("_"); 
    
    $("#nombre").val(data.substring(indiceEspacio + 1));
    $("#id").val(data.substring(0, indiceEspacio));    
    $("#nombre").focus();
}
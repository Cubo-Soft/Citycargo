$(document).ready(function () {

    $("#divBotonesAccion").hide();

    $("#es_documentos_entidad").on("click", function () {
        retornarTodoEsDocumentosEntidad();
        $("#tabla").val("es_documentos_entidad");
    });

    $("#crear").on("click", function () {

        if ($("#tabla").val() === 'es_documentos_entidad') {
            crearESDocumentosEntidad(null, 1);
        }

        if ($("#tabla").val() === 'es_referencias_entidad') {

            if ($("#es_tipo_referencia").val() === '-1') {
                $("#es_tipo_referencia").focus();
                $("#divMensajes").html('<div class="alert alert-danger">Por favor seleccione un nombre de referencia</div>');
            } else if ($("#es_nombre_vinculo").val() === '-1') {
                $("#es_nombre_vinculo").focus();
                $("#divMensajes").html('<div class="alert alert-danger">Por favor seleccione un vínculo</div>');
            } else {
                $("#divMensajes").html('');
                crearEsReferenciasEntidad(null, 1);
            }
        }

        if ($("#tabla").val() === 'es_seguridad_social_entidad') {

            if ($("#es_entidades_seguridad_social").val() === '-1') {
                $("#es_entidades_seguridad_social").focus();
                $("#divMensajes").html('<div class="alert alert-danger">Por favor seleccione una entidad</div>');
            } else if ($("#es_entidad").val() === '-1') {
                $("#es_entidad").focus();
                $("#divMensajes").html('<div class="alert alert-danger">Por favor seleccione una entidad de seguridad social</div>');
            } else {
                $("#divMensajes").html('');
                crearEsSeguridadSocialEntidad(null, 1);
            }
        }

    });

    $("#es_seguridad_social_entidad").on("click", function () {
        $("#tabla").val("es_seguridad_social_entidad");

        //aqui para abajo
        retornarTodoEsSeguridadSocialEntidad();

    });

    $("#es_referencias_entidad").on("click", function () {
        retornarTodoEsReferenciasEntidad();
        $("#tabla").val("es_referencias_entidad");
    });

});
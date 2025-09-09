function grabarCuEsEstudioDocumentos(data, opcion) {

    if (opcion === 1) {

        var id = obtenerPartePosteriorGuionBajo(data);

        var expresionRegularFecha = /^\d{4}-\d{2}-\d{2}$/;
        
        if (!expresionRegularFecha.test($('#fe1_' + id).val())) {
            $("#divMensajeEntidad").html("<div class='alert alert-warning'>Por favor seleccionar una fecha válida</div>");
            $('#fe1_' + id).focus();
        } else {

            var dataFormulario = new FormData($("#documentos_entidad_1")[0]);
            dataFormulario.append("id_es_documentos_entidad", id);
            dataFormulario.append("id_estudio", $("#id_estudio").val());
            dataFormulario.append("cedula_empleado", $("#cedula_empleado").val());
            dataFormulario.append("id_es_entidad", $("#es_entidad").val());
            dataFormulario.append("fecha_vencimiento", $('#fe1_' + id).val());
            dataFormulario.append("caso", '1');

            if ($("#placa").is(":visible")) {
                dataFormulario.append("documento", $("#placa").val());
            }

            if ($("#cedula").is(":visible")) {
                dataFormulario.append("documento", $("#cedula").val());
            }

            $.ajax({
                url: "../trafico/CT_es_cu_estudio_documentos.php",
                data: dataFormulario,
                processData: false,
                contentType: false,
                type: "POST",
                success: function (respuesta) {
                    console.log(respuesta);
                    //               var obj = JSON.parse(respuesta);
                    
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX en function crearESDocumentosEntidad(data,opcion=2)...");
                }
            });
            
        }
    }

}
function retornarEsEstudioEntidad(data,opcion) {
    
    if(opcion===1){

        if($("#placa").is(":visible")){
            datosAEnviar.documento=$("#placa").val();
        }
        
        if($("#cedula").is(":visible")){
            datosAEnviar.documento=$("#cedula").val();
        }

        datosAEnviar.id_es_entidad=$("#es_entidad").val();        

        $.ajax({
            url: "../trafico/CT_es_estudio_entidad.php",
            data: { 'caso': '1','datosAEnviar':datosAEnviar },
            type: "POST",
            success: function (respuesta) {                
                var obj = JSON.parse(respuesta);
                
                if(obj["es_estudio_entidad"].lenght>0){

                    //aqui sería para traer todos los datos cuando exista un estudio de seguridad creada con esa cédula o placa

                }else{

                    //aqui cuando no exista un estudio y se inicia a crear                     
                    retornarESDocumentosEntidad(null, 2);
                    
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarEsEntidad(data,opcion=1)...");
            }
        });

    }

}
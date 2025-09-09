function cambiarDiasConfiguracion(dato, opcion) {

    var id = dato.id;
    var valor = $("#" + id).val();
    id = id.substring(2, id.length);

    $("#mensajes").html('');

    if (opcion === 1) {

        $.ajax({
            url: "../trafico/CT_dias_configuracion.php",
            data: { 'caso': '1', 'id': id, 'estado': valor },
            type: "POST",
            success: function (retorno) {
                //console.log(retorno);
                var obj = JSON.parse(retorno);

                if (obj) {
                    location.reload();
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function cambiarDiasConfiguracion(){...");
            }
        });
    }

    if (opcion === 2) {

        $.ajax({
            url: "../trafico/CT_dias_configuracion.php",
            data: { 'caso': '2', 'id': id, 'cantidad': valor },
            type: "POST",
            success: function (retorno) {
                //console.log(retorno);
                var obj = JSON.parse(retorno);

                if (obj) {
                    $("#mensajes").html('<div class="alert alert-success">Valor ajustado</div>');
                }

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function cambiarDiasConfiguracion(){...");
            }
        });
    }

}
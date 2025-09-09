function enviarACancelar(idservicio, empleado) {
    var razones = prompt("Indique las razones para cancelar el servicio: " + idservicio, "Digite motivos de cancelación" );
    if (razones.length > 0) {
        $.ajax({
            url: "../trafico/crearServicioParaCancelar.php",
            data: {'idservicio': idservicio,
                'idempleado': empleado,
                'motivo': razones},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj === true) {
                    $("#mensajes").html("<div class='alert alert-dismissible alert-success'>El servicio " + idservicio + " ha sido agregado a la lista de servicios por cancelar</div>");
                } else {
                    $("#mensajes").html("<div class='alert alert-dismissible alert-danger'>Oops! Ha ocurrido un error al agregar el servicio: " + idservicio + " a la lista de servicios para eliminar. Por favor informe de este evento y tenga presente el n&uacute;mero de servicio </div>");
                }
            }
        });
    }
}


var mostrarEventos = null, gestion = null;
function crearAccion(boton, empleado) {
    $.ajax({
        url: "../trafico/crearAccionEnPrograma.php",
        data: {'boton': boton,
            'empleado': empleado
        },
        type: "POST",
        success: function (data) {
            console.log(data);
        },
        fail: function () {
            alert("Yuca");
        }
    });
}
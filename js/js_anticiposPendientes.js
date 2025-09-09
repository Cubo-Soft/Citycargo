var ingreso = "", boton_ = null, guia = null, factura = null;
$(document).ready(function () {

    $("#btnContinuarConsultar").hide();    
    
    
    if($("#fechaInicial").val()!=='0'){
        document.title='Ini: '+$("#fechaInicial").val()+' Fin: '+$("#fechaFinal").val();
    }

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $('#botonGenerarExcel').click(function () {
        $("#tablaExcel").table2excel({
            exclude: ".noExl",
            name: "Excel Document Name",
            filename: 'archivo',
            fileext: ".xls",
            exclude_img: true,
            exclude_links: true,
            exclude_inputs: true
        });
    });

    $("#btnIniciarConsultar").click(function () {
        $.ajax({
            url: "../trafico/RolBoton.php",
            data: {'caso': '1', 'fechaInicial': $("#fechaInicial").val(), 'fechaFinal': $("#fechaFinal").val()},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                $("#mensajeUno").html("<div class='alert alert-success col-lg-6'>Por favor presione el bot&oacute;n Continuar consulta para mostrar la lista de servicios consultados</div><div class='alert alert-danger col-lg-6'>Se van a mostrar <strong>" + obj[0] + "</strong> servicios de un total de servicios por pagar de <strong>" + obj[1] + " </strong>.</div>");
                $("#btnIniciarConsultar").hide();
                $("#btnContinuarConsultar").show();
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Error en $('#btnIniciarConsultar').click(function () {...Por favor informe");
            }
        });
    });
});

function crearPruebaEntrega(valor) {
        
    var guia=valor.id;   
    
    if (confirm("Crear prueba de entrega para la guía:" + guia)) {
        $.ajax({
            url: "../trafico/crearPruebaEntrega.php",
            data: {'guia': guia},
            type: "POST",
            success: function (data) {                
                var obj = JSON.parse(data);
                if (obj[1] !== false) {
                    $("#" + guia).removeClass("btn btn-warning btn-sm");
                    $("#" + guia).addClass("btn btn-success btn-sm");
                    if (confirm("Desea ingresar valores para documento cliente del servicio: " + obj[0] + " ")) {                        
                        var url="../modulos/mostrarServicio.php?idservicio=" + obj[0];
                        window.open(url, '_blank');
                    }
                } else {
                    alert("Ha ocurrido un error en AJAX en function crearPruebaEntrega(valor) {...");
                }
            }
        });
    }
}



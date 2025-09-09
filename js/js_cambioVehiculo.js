var listo = null, valor = '';
$(document).ready(function () {

    $("#botonSalir").click(function () {
        if (listo === null) {
            $("#mensajes").html('<div class="alert alert-danger">No ha sido reasignado el servicio</div>');
        } else {
            window.location.href = "../trafico/salir.php";
        }

    });

    $('#botonRegresar').click(function () {
        if (listo === null) {
            $("#mensajes").html('<div class="alert alert-danger">No ha sido reasignado el servicio</div>');
        } else {
            window.location.href = "../modulos/index.php";
        }
    });

    $("#placas").focus();

    $("#placas").change(function () {
        valor = $("#placas").val();
        if (valor === "0") {
            location.reload();
        } else {
            $("#cedulaPropietario").val(valor);
            $("#identificacion").val(valor);
            $("#idConductor option[value=" + valor + "]").attr("selected", true);
        }
    });

    $("#idConductor").change(function () {
        $("#mensajes").html("<div class='alert alert-dismissible alert-primary'>Por favor seleccione una placa de la <strong>Lista de placas</strong> para hacer la reasignaci&oacute;n</div>");
        location.reload();
    });

    $("#reasignarServicio").click(function () {
        if ($("#listaPlacas").val() === '0') {
            $("#mensajes").html('<div class="alert alert-danger">Por favor seleccione una placa de la lista</div>');
            $("#listaPlacas").focus();
        } else if (confirm("Reasignar servicio: " + $("#idservicio").val() + " \na la placa: " + $("#placas option:selected").text() + "\ndel propietario: " + $("#cedulaPropietario").val() + " ?")) {
            var varEmp=null;
            $.ajax({
                url: "../trafico/reasignarServicio.php",
                data: {'idservicio': $("#idservicio").val(),
                    'placa': $("#placas option:selected").text(),
                    'cedulaPropietario': $("#cedulaPropietario").val()},
                type: "POST",
                success: function (data) {                    
                    var obj = JSON.parse(data);                    
                    if (obj !== false) {
                        alert("Se ha realizado el cambio de manera correcta,\n Será direccionado para realizar el anticipo del servicio");                        
                        if(obj[0].valorFacturar==='0'){
                            varEmp=0;
                        }else{
                            varEmp=1;
                        }                        
                        window.location.href = "../modulos/agregarAnticipo.php?pj="+$("#cedulaPropietario").val()+"&varEmp="+varEmp+"&idservicio="+$("#idservicio").val();
                    } else {
                        alert("Ha ocurrido un error, por favor presione F5 e intenelo nuevamente");
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    alert("Ha ocurrido un error en AJAX en $('#reasignarServicio').click(function () {...");
                }
            });
        } else {
            $("#mensajes").html('<div class="alert alert-dismissible alert-primary">Pendiente...</div>');
        }
    });

});

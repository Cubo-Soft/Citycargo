$(document).ready(function () {
    
     $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });
    
    $("#nitEmpresa").focusin(function (){
//           alert('hola');
    });
    
    $('#nitEmpresa').blur(function () {
        alert('hola');
//        $.ajax({
//            url: "../trafico/consultarEmpresa.php",
//            method: "POST",
//            data: {"nitEmpresa": $('#nitEmpresa').val()},
//            success: function (data) {
//                var obj = jQuery.parseJSON(data);                
//                console.log(obj);
//                if (obj === false) {
//                    alert('No se registran datos para la identificacion del cliente');
//                } else {          
//                    $('#idEmpresa').val(obj[0]['cli_id']);
//                    $('#nombreEmpresa').val(obj[0]['cli_nombre']);                    
//                    $('#contactoEmpresa').val(obj[0]['cli_contacto']);
//                    $('#direccionEmpresa').val(obj[0]['cli_direccion']);
//                    $('#correoEmpresa').val(obj[0]['cli_correo']);
//                    $('#telefonoEmpresa').val(obj[0]['cli_telefono']);
//                    $('#faxEmpresa').val(obj[0]['cli_fax']);
//                    $('#listaMunicipios option[value='+obj[0]['mun_id']+']').attr('selected',true);
//                    $('#nitEmpresa').prop('disabled',true);
//                }
//            },
//            error: function () {
//                $('#mensajes').html("Se esta generando un erro al consultar la empresa con AJAX, por favor informe al desarrollador del produclto");
//            }
//        });
    });
    
    
});
//$('#botonAnticipos').click(function () {
//    var identCon = prompt("Digite el número de cédula del conductor");
//    if (identCon !== "") {
//        window.location.href = "../modulos/anticipos.php?pj=" + identCon;
//    } else {
//        alert("Para retornar a anticipos debe digitar un número de cedula");
//    }
//});
//
//
//
//
//$("#botonCrearEmpresa").click(function () {
//
//    if ($("#nitEmpresa").val() === "") {
//        $("#msjNitEmpresa").html("Digite el NIT de la empresa");
//        $("#nitEmpresa").focus().css({"background-color": "#D9FDFF"});
//        return false;
//    }
//
//    if ($("#nombreEmpresa").val() === "") {
//        $("#msjNombreEmpresa").html("Digite el NIT de la empresa");
//        $("#nombreEmpresa").focus().css({"background-color": "#D9FDFF"});
//        return false;
//    }
//
//    if ($("#contactoEmpresa").val() === "") {
//        $("#msjContactoEmpresa").html("Digite el NIT de la empresa");
//        $("#contactoEmpresa").focus().css({"background-color": "#D9FDFF"});
//        return false;
//    }
//
//    if ($("#direccionEmpresa").val() === "") {
//        $("#msjDireccionEmpresa").html("Digite el NIT de la empresa");
//        $("#direccionEmpresa").focus().css({"background-color": "#D9FDFF"});
//        return false;
//    }
//
//    if ($("#correoEmpresa").val() === "") {
//        $("#msjCorreoEmpresa").html("Digite el NIT de la empresa");
//        $("#correoEmpresa").focus().css({"background-color": "#D9FDFF"});
//        return false;
//    }
//
//    if ($("#telefonoEmpresa").val() === "") {
//        $("#msjTelefonoEmpresa").html("Digite el NIT de la empresa");
//        $("#telefonoEmpresa").focus().css({"background-color": "#D9FDFF"});
//        return false;
//    }
//
//    if ($("#faxEmpresa").val() === "") {
//        $("#msjFaxEmpresa").html("Digite el NIT de la empresa");
//        $("#faxEmpresa").focus().css({"background-color": "#D9FDFF"});
//        return false;
//    }
//
//    return true;
//});
//
//$("#botonCuentaCobro").click(function () {
//    var identCon = prompt("Digite el número de cédula del conductor");
//    if (identCon !== "") {
//        window.location.href = "../modulos/cuentaCobro.php?pj=" + identCon;
//    } else {
//        alert("Para retornar a anticipos debe digitar un número de cedula");
//    }
//});
//
//$("#botonBorrar").click(function () {
//    location.reload();
//});



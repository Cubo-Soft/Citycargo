var idservicios = [], serviciosCtaCobro = [], valAntId = [], serviciosAPagar = [], guiasAPagar = [];
//$(document).ready(function () {
$(function(){
    $("#mensajes").html("<div class='alert alert-dismissible alert-warning'>Los servicios en rojo no tienen prueba de entrega registrada</div>");

    $("#botonSalir").click(function () {
        window.location.href = "../trafico/salir.php";
    });

    $('#botonRegresar').click(function () {
        window.location.href = "../modulos/index.php";
    });

    $("#botonGenerarPdf").click(function () {

        if ($("#conductor").val() === '0') {
            $("#mensajes").html('<div class="alert alert-danger">Por favor seleccionar un conductor</div>');
            $("#conductor").focus();
            return false;
        }else{
            return true;    
            location.reload();
        }
    });

    /*201902261050     
     *Chequear todos los input checbox y 
     *dejar los números de servicios en 
     *id serviciosAPagar
     */
    $("#chbPE").change(function () {

        $('#totalRteFuente').val('');
        $('#totalRteIca').val('');
        $('#totalImpuestos').val('');
        $('#totalAnticipos').val('');
        $('#totalDeducibles').val('');
        $('#totalNetoPagar').val('');
        $('#valorLetras').val('');
        $('#valorServicio').val('');

        if ($(this).is(":checked")) {
            /*
             * Chequeo todos los input checbox 
             */
            $("#cuerpoDescripcionServicio input[type=checkbox]").prop('checked', true);
        } else {
            $("#serviciosAPagar").val('');
            $("#valAntId").val('');
            $("#guiasApagar").val('');
            $("#cuerpoDescripcionServicio input[type=checkbox]").prop('checked', false);
            valAntId = [];
        }
    });

    /*
     * Calcula el valor de los anticipos y servicios y entrega un total
     */
    $("#botontotal").click(function () {

        var acumulado = 0, tot = 0, rteFte = 0, rteIca = 0, valSer = 0, totImp = 0,
                totAnt = 0, cont = 0, idserv = '', salto = 23, arr_servicios = [], idguia ='', arr_guias = [],
                mensaje = '<div class="alert alert-dismissible alert-success">Los siguientes servicios: ';
        /*
         * Reviso cuales checkbox estan seleccionados para despues comparar con los val_ant_id que hacen falta
         */
        serviciosCtaCobro = $("#serviciosCtaCobro").val().split('-');
        valAntId = $("#valAntId").val().split('-');

        $('input[type=checkbox]:checked').each(function () {
            if ($(this).prop("id") !== 'chbPE') {
                serviciosAPagar[cont] = $(this).prop("id").substr(5, $(this).prop("id").length);
                guiasAPagar[cont] = $(this).prop("title");
                cont += 1;
            }
        });

        cont = 0;

        if (serviciosAPagar.length === 0) {
            $("#mensajes").html('<div class="alert alert-success">No ha seleccionado un servicio para pagar. Por favor revise</div>');
            $('#totalRteFuente').val('');
            $('#totalRteIca').val('');
            $('#totalImpuestos').val('');
            $('#totalAnticipos').val('');
            $('#totalDeducibles').val('');
            $('#totalNetoPagar').val('');
            $('#valorLetras').val('');
            $('#valorServicio').val('');
        } else {

            $("#mensajes").html('');
            /* adiciono arreglo para evitar sumar doble el servicio (RMG) */
            for (var i = 0; i < valAntId.length; i++) {
                if (jQuery.inArray(valAntId[i], serviciosAPagar) === -1)
                {
                    //mensaje = mensaje + $("#serv-" + valAntId[i]).prop("name").substr(5, $("#serv-" + valAntId[i]).prop("name").length) + '---';
                    mensaje = mensaje + $("#serv-" + valAntId[i]).prop("name") + '---';
                }

                if (cont === salto) {
                    mensaje = mensaje + "<br>";
                    cont = 0;
                }

                cont = cont + 1;
            }
            console.log("Vienen servicios o valores:"+serviciosAPagar);
            $.each(serviciosAPagar, function (posicion, valor) {
                idserv += valor + '-';
            });

            console.log("VIENE EN idserv: "+idserv);
            
            idserv = idserv.substr(0, idserv.length - 1);
            $("#serviciosAPagar").val(idserv);

            /* adiciono igual rutina para guardar las guias (RMG-2024-05-300) */
            console.log('Vienen GUIAS:'+guiasAPagar);
            $.each(guiasAPagar, function (posi, num_guia){
                idguia += num_guia + '-';
            })
            console.log("VIENE en idguia: "+idguia);
            idguia = idguia.substr(0, idguia.length - 1);
            $("#guiasAPagar").val(idguia);

            /*
             * Reviso si mensaje tiene un ---, si lo tiene muestro el mensaje             
             */
            if (mensaje.indexOf('---') > 1) {
                /*
                 * Quito los tres ultimos guiónes de la cadena en mensaje
                 */
                mensaje = mensaje.substr(0, mensaje.length - 3);
                mensaje += ' <br><strong>No se pagaran en esta cuenta de cobro</strong><br>Por favor revise nuevamente antes de generar la cuenta de cobro</div>';
                $("#mensajes").html(mensaje);
            }

            /*
             * Realizo los cálculos necesarios de la cuenta             
             */
            for (var i = 0; i < serviciosAPagar.length; i++) {
                let id_servicio = $('#valorTotalServicio' + serviciosAPagar[i]).attr('title'); // trae la guia
                //let id_guia = $('#valorTotalServicio' + guiasAPagar[i]).attr('title');
                console.log("servicio: "+id_servicio+" nro anticipo: "+serviciosAPagar[i]);
                if(jQuery.inArray(id_servicio,arr_servicios)=== -1){
                    acumulado = acumulado + parseInt($('#valorTotalServicio' + serviciosAPagar[i]).val());
                    let long = arr_servicios.length;
                    arr_servicios[long] = id_servicio;
                    //arr_guias[long] = id_guia;
                    console.log("pasa servicio: "+id_servicio+" nro anticipo: "+serviciosAPagar[i]);
                }else{
                    console.log("SALTÓ EL inArray: "+id_servicio+" nro anticipo: "+serviciosAPagar[i]);
                }
                totAnt = totAnt + parseInt($('#valorServicio' + serviciosAPagar[i]).val());
            }

            tot = valSer + acumulado;

            $('#valorServicio').val((parseInt(tot)).toFixed(2));

            if (tot >= $('#base').val()) {
                rteFte = (tot * $('#rteFuente').val()) / 100;
                rteIca = (tot * $('#rteIca').val()) / 100;
                totImp = rteFte + rteIca;
            } else {
                rteFte = 0;
                rteIca = 0;
                totImp = rteFte + rteIca;
            }

            $('#totalRteFuente').val(rteFte.toFixed(2));
            $('#totalRteIca').val(rteIca.toFixed(2));
            $('#totalImpuestos').val(totImp.toFixed(2));
            $('#totalAnticipos').val(totAnt.toFixed(2));
            $('#totalDeducibles').val((totImp + totAnt).toFixed(2));
            $('#totalNetoPagar').val((parseInt(tot - (totImp + totAnt))).toFixed(2));
            $('#valorLetras').val(NumeroALetras($('#totalNetoPagar').val()));

            acumulado === 0;
            tot === 0;
            rteFte === 0;
            rteIca === 0;
            valSer === 0;
            totImp === 0;
            totAnt === 0;
        }

    });

    $("#idConductor").change(function () {
        valor = $("#idConductor").val();
        if (valor === "0") {
            $("#txt_documentocliente").val("");
            $("#placa option[value='0']").attr("selected", true);
            location.reload();
        } else {
            $("#txt_documentocliente").val($("#idConductor").val());
            placaConductor(valor);
            $("#placa option[value=" + valor + "]").attr("selected", true);
        }
    });

    $("#placas").change(function () {
        valor = $("#placas").val();
        if (valor === "0") {
            $("#txt_documentocliente").val("");
            $("#idConductor option[value='0']").attr("selected", true);
            location.reload();
        } else {
            $("#txt_documentocliente").val(valor);
            placaConductor(valor);
            $("#idConductor option[value=" + valor + "]").attr("selected", true);
        }
    });
    
    $("#botonRealizarConsulta").click(function (){      
        var placa=$( "#placas option:selected" ).text();
        if($("#idConductor").val()==='0'||$("#placas").val()==='0'){
            $("#mensaje").html('<div class="alert alert-dismissible alert-danger">Por favor seleccione una Placa o un Propietario de las listas</div>');
            $("#placas").focus();
        }else{
            window.location.href = "../modulos/cuentaCobro.php?pj="+$("#idConductor").val()+"&placa="+placa;
        }
    });

    $("#listarPropietariosPlacas").click(function (){
        window.location.href = "../modulos/cuentaCobro.php";
    });
    
});

function placaConductor(valor) {
    //20161115 Verificar si el conductor tiene asociada una placa 
    //activa
    $.ajax({
        url: "../trafico/retornarPlacaConductor.php",
        method: "POST",
        data: {"identificacion": valor},
        success: function (data) {
            var obj = jQuery.parseJSON(data);
            if (obj.length === 0) {
                if (confirm('No hay placa asociada al conductor\n¿Desea asociar o modificar alguna?')) {
                    window.location.href = "../modulos/conductores.php?identificacion=" + $("#txt_documentocliente").val();
                } else {
                    $("#idConductor").val('0');
                    $("#txt_placa").val('');
                    $("#txt_documentocliente").val('');
                }
            } else {
                $("#txt_placa").val(obj[0].placa);
            }
        },
        error: function () {
            $('#mensajes').html("Se esta generando un erro al consultar la placa del conductor con AJAX, por favor informe al desarrollador del produclto");
        }
    });
}


/*
 * 201902261050 
 * Mostrar el id de la lista conductor en 
 * el input text identificacion
 */
function colocarCedula(valor) {
    $("#identificacion").val(valor.value);
}

function retirarchbPE() {
    $("#chbPE").removeAttr('checked');
}
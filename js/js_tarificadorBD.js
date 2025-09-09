$(document).ready(function () {

    $('#botonCalcularTarifa').focusin(function () {
        var domicilioCarry, pequeno, mediano, grande;
        $.ajax({
            url: "./trafico/calcularTarifa.php",
            data: {'CLOR': $("#CLOR").val(),
                'CLDE': $("#CLDE").val(),
                'CLORS': $("#CLORS").val(),
                'CLDES': $("#CLDES").val(),
                'CROR': $("#CROR").val(),
                'CRDE': $("#CRDE").val(),
                'CRORE': $("#CRORE").val(),
                'CRDEE': $("#CRDEE").val()},
            type: "POST",
            success: function (data) {                
                var obj = JSON.parse(data);
                if (obj !== false) {
                    
                    pequeno = '$'+$.number(obj[0].pequeno);
                    mediano = '$'+$.number(obj[0].mediano);
                    grande = '$'+$.number(obj[0].grande);
                    domicilioCarry = '$'+$.number(obj[0].domicilioCarry);
                    $('#tarifa').text('$'+$.number(obj[0].tarifa));
                    $('#domicilioCarry').text(domicilioCarry);
                    var tarifaDomicilio='<table class="table table-responsive" ><tr><td><h3>Domicilios</h3></td><td><h3>Domicilio carry</h3></td></tr><tr><td><h3>'+obj[0].tarifa+'</h3></td><td><h3>'+domicilioCarry+'</h3></td></tr></table>';
                    var imprimir = '<table class="table table-responsive" ><tr><td><h3>Pequeño</h3></td><td><h3>Mediano</h3></td><td><h3>Grande</h3></td><tr><td><h3>' + pequeno + '</h3></td><td><h3>' + mediano + '</h3></td><td><h3>' + grande + '</h3></td></td></tr></table>';
                    $('#tarifasAdicionales').html(imprimir);
                    $('#divTarifa').html(tarifaDomicilio);
                }
            },
            error: function () {
                $('#mensajes').text('Error grave en AJAX al calcular la tarifa, por favor informe al ingeniero');
            }
        });
    });

    $('#botonBorrar').click(function () {
        $('#CLOR').val('0');
        $('#CLORS').val('0');
        $('#CROR').val('0');
        $('#CRORE').val('0');
        $('#divBarrioOrigen').text('');
        $('#CLDE').val('0');
        $('#CLDES').val('0');
        $('#CRDE').val('0');
        $('#CRDEE').val('0');
        $('#divBarrioDestino').text('');
        $('#tarifa').text('');
        $('#CLOR').focus();        
        $('#divTarifa').text('');
        $('#tarifasAdicionales').text('');
    });

    $('#CRORE').focusout(function () {
        $.ajax({
            url: "./trafico/retornarBarrio.php",
            data: {'CLOR': $("#CLOR").val(),
                'CLORS': $("#CLORS").val(),
                'CROR': $("#CROR").val(),
                'CRORE': $("#CRORE").val(),
                'origen': $("#origen").val()},
            type: "POST",
            success: function (data) {
                var obj = JSON.parse(data);
                if (obj !== false) {
                    $('#divBarrioOrigen').html('<strong>'+obj+'</strong>');
                }
            },
            error: function () {
                $('#mensajes').text('Error grave en AJAX al retornar nombre barrio origen, por favor informe al ingeniero');
            }
        });
        
    });

    $('#CRDEE').focusout(function () {
        $.ajax({
            url: "./trafico/retornarBarrio.php",
            data: {'CLDE': $("#CLDE").val(),
                'CLDES': $("#CLDES").val(),
                'CRDE': $("#CRDE").val(),
                'CRDEE': $("#CRDEE").val(),
                'destino': $("#destino").val()},
            type: "POST",
            success: function (data) {           
                var obj = JSON.parse(data);
                if (obj !== false) {
                    $('#divBarrioDestino').html('<strong>'+obj+'</strong>');
                }
            },
            error: function () {
                $('#mensajes').text('Error grave en AJAX al retornar nombre barrio origen, por favor informe al ingeniero');
            }
        });
    });


    $("#botonCalcularTarifa").focus(function () {
        $(this).css('outline-color', '#DF0101');
    });

    $("#CLOR").focus(function () {
        $(this).css('outline-color', '#DF0101');
    });

    $("#CLORS").focus(function () {
        $(this).css('outline-color', '#DF0101');
    });

    $("#CROR").focus(function () {
        $(this).css('outline-color', '#DF0101');
    });

    $("#CRORE").focus(function () {
        $(this).css('outline-color', '#DF0101');
    });

    $("#CLDE").focus(function () {
        $(this).css('outline-color', '#DF0101');
    });

    $("#CLDES").focus(function () {
        $(this).css('outline-color', '#DF0101');
    });

    $("#CRDE").focus(function () {
        $(this).css('outline-color', '#DF0101');
    });

    $("#CRDEE").focus(function () {
        $(this).css('outline-color', '#DF0101');
    });


});




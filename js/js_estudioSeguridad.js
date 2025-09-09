var objs = [];
var datos = [];

$(document).ready(function () {

    $("#trMensaje1").hide();
    $("#divCrearEps").hide();
    $("#divCrearArl").hide();
    $("#divCrearPension").hide();
    $("#divFormFotoConductor").hide();
    $("#divFormHojaVidaConductor").hide();

    $("#divFormRUTDocumento").hide();
    $("#divFormPlacaTrailer").hide();
    $("#crearPdf").hide();

    $("#divForm1").hide();
    $("#divForm2").hide();
    $("#divForm3").hide();
    $("#divForm4").hide();
    //$("#crearEstudio").hide();

    $("#placa").focus();

    $("#trMensaje1").show();
    //$("#tdTextoMensaje1").text("Para iniciar la consulta y/o la creacion de un estudio de seguridad, por favor digite la placa luego puede presionar la tecla 'TAB' o 'Tabulador'. Si hay datos en la base de la placa digitada, el sistema los mostrara; en caso contrario, puede 'Crear estudio' presionando en el boton azul. Los demas campos del formulario se iran asociando a la placa consultada y/o creada a medida que los vaya trabajando.");

    $("#placa").on("input", function () {
        if ($("#placa").val().length <= 5) {
            //$("#crearEstudio").hide();
            $("#trMensaje1").show();
            //$("#tdTextoMensaje1").text("Para iniciar la consulta y/o la creacion de un estudio de seguridad, por favor digite la placa luego puede presionar la tecla 'TAB' o 'Tabulador'. Si hay datos en la base de la placa digitada, el sistema los mostrara; en caso contrario, puede 'Crear estudio' presionando en el boton azul. Los demas campos del formulario se iran asociando a la placa consultada y/o creada a medida que los valla trabajando.");
            $("#tdTextoMensaje1").text("Para iniciar la consulta y/o la creacion de un estudio de seguridad, por favor digite la placa luego puede presionar la tecla 'TAB' o 'Tabulador'. Si hay datos en la base de la placa digitada, el sistema los mostrara; en caso contrario, el sistema lo creara. Los demas campos del formulario se iran asociando a la placa consultada una vez los digite.");
        }
    });

    $("#placa").on("blur", function () {
        if (validarPlaca($(this).val())) {
            $("#trMensaje1").hide();
            retornarEstudioSeguridad($(this).val());
        } else {
            //$(this).focus();
            location.reload();
        }
    });

    $('input[type="email"]').on('input', function () {
        const $input = $(this);
        const valor = $input.val();
        const id = $input.attr('id');
        const mensajeId = '#mensaje-' + id;

        if (valor === "") {
            //$input.removeClass('valid invalid'); 
            $input.addClass('invalid').removeClass('valid');
            $(this).focus();
        } else if (validarEmail(valor)) {
            $input.removeClass('valid invalid');
        } else {
            $input.addClass('invalid').removeClass('valid');
            $(this).focus();
        }
    });

    $("#correoconductor").on("blur", function () {
        if (validarEmail($(this).val())) {
            cambiarDato(this, 1);
        } else {
            $(this).val("");
            $(this).focus();
        }
    });

    $("#correopropietario").on("blur", function () {
        if (validarEmail($(this).val())) {
            cambiarDato(this, 1);
        } else {
            $(this).val("");
            $(this).focus();
        }
    });

    $("#botonNuevoEstudio").on("click", function () {
        borrarFormulario(1);
    });

    $("#btnMismoPropietario").on("click", function () {
        var datos=[];        
        datos.colectados=["cedulapropietario", "nombrepropietario", "direccionpropietario", "telefonopropietario", "correopropietario"];
        datos.mostrar=["cedulaconductor", "nombreconductor", "direccionconductor", "telefonoconductor", "correoconductor"];
        cambiarVariosDatos(datos,null);
    });
    
    $("#btnMismoPropietarioTrailer").on("click",function(){
        
        var datos=[];        
        datos.colectados=["identloctencom", "nomloctencom", "dirloctencom", "telloctencom"];
        datos.mostrar=["cedulapropietario", "nombrepropietario", "direccionpropietario", "telefonopropietario"];
        cambiarVariosDatos(datos,null);
        
    });

    //$("#crearEstudio").on("click", function () {
    //crearEstudio($("#placa").val());
    //});

    $("#modelo").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#numrutpropietario").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#id_tipovehiculo").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#operador").on("blur", function () {
        cambiarOperador(this);
    });

    $("#usuario").on("blur", function () {
        cambiarUsuario(this);
    });

    $("#clave").on("blur", function () {
        cambiarClave(this);
    });

    $("#licenciatransito").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fechasoat").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#revisiontecno").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#polizarespo").on("blur", function () {
        cambiarDato(this, 1);
    });

    //hay dos funciones
    //actualizarImagen
    //actualizarArchivo 
    //estoy trabajando sobre actualizarArchivo

    $('#btnImg1').click(function () {
        actualizarImagen("imagen1", "1", 1);
    });

    $('#btnImg2').click(function () {
        actualizarImagen("imagen2", "2", 1);
    });

    $('#btnImg3').click(function () {
        actualizarImagen("imagen3", "3", 1);
    });

    $('#btnImg4').click(function () {
        actualizarImagen("imagen4", "4", 1);
    });

    $("#rndcvehiculo").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fasecoldavehiculo").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#nomultempresacargue").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#conultempresacargue").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#fecultempresacargue").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#cedulaconductor").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#cedulaconductor").on("blur", function () {
        var datos = [];
        datos.nombreCampo = 'cedulaconductor';
        datos.arreglo = ["nombreconductor", "direccionconductor", "telefonoconductor"];
        traerDatosConductor(datos, 2);
    });

    $("#nombreconductor").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#direccionconductor").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#telefonoconductor").on("change", function () {
        cambiarDato(this, 1);
    });

    $('#btnFile2').click(function () {

        datos.nombreCampo = 'licenciaconductor';
        datos.caso = '5';
        datos.nombreDiv = 'divLicencia';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error al subir el archivo de licencia';
        datos.mensajeActualizacion = '1er imagen de licencia actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $('#btnFile8').click(function () {

        datos.nombreCampo = 'licenciaconductor2';
        datos.caso = '14';
        datos.nombreDiv = 'divLicencia2';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error al subir el archivo de licencia 2';
        datos.mensajeActualizacion = '2da imagen de licencia actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $("#vencimientolicencia").on("change", function () {
        cambiarDato(this, 1);
    });

    $('#btnImg5').click(function () {

        datos.nombreCampo = 'fotoconductor';
        datos.caso = '6';
        datos.nombreDiv = 'divFotoConductor';
        datos.nombreObj = 'archivo';
        datos.mensajeAlert = 'Error al subir imagen del conductor';
        datos.mensajeActualizacion = '';
        datos.tipoArchivo = 2;
        actualizarArchivo(datos, 1);

    });

    $("#estadoeps").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#estadoarl").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#estadopension").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fechadatospersonales").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#observaciones").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#telefonoemergencia").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#nombrecontacto").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#parentescocontacto").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#id_parentescocontacto").on("change", function () {
        cambiarDato(this, 1);
    });

    $('#btnFile1').click(function () {

        datos.nombreCampo = 'hojavidacond';
        datos.caso = '7';
        datos.nombreDiv = 'divHojaVida';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error al subir hoja de vida del conductor';
        datos.mensajeActualizacion = 'Hoja de vida actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $("#btnCancelarEps").on("click", function () {
        $("#divListaEps").show();
        $("#divCrearEps").hide();
        $("#id_eps").val('-1');
    });

    $("#btnCancelarArl").on("click", function () {
        $("#divListaArl").show();
        $("#divCrearArl").hide();
        $("#id_arl").val('-1');
    });

    $("#btnCancelarPension").on("click", function () {
        $("#divListaPension").show();
        $("#divCrearPension").hide();
        $("#id_pension").val('-1');
    });

    $("#btnCrearEps").on("click", function () {
        if (validarCampoTexto("nombreEps", "mensajesEps")) {
            crearEps();
        }
    });

    $("#btnCrearArl").on("click", function () {
        if (validarCampoTexto("nombreArl", "mensajesArl")) {
            crearArl();
        }
    });

    $("#btnCrearPension").on("click", function () {
        if (validarCampoTexto("nombrePension", "mensajesPension")) {
            crearPension();
        }
    });

    $("#nombrerefcond").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#id_parenrefcond").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#dirrefcond").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#telrefcond").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#verifirefcond").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#id_parenrefcondlab").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#id_parenrefcondlab").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#nombrereflab").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#direcreflab").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#telecreflab").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#vercreflab").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#fecvenciponal").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecvencipersone").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecvenciprocu").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecvencicontrola").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecvencisimit").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecvencirnmc").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecvenciinhab").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#cedulapropietario").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#cedulapropietario").on("blur", function () {
        var datos = [];
        datos.nombreCampo = 'cedulapropietario';
        datos.arreglo = ["nombrepropietario", "direccionpropietario", "telefonopropietario"];
        traerDatosConductor(datos, 2);
    });

    $("#nombrepropietario").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#direccionpropietario").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#telefonopropietario").on("blur", function () {
        cambiarDato(this, 1);
    });

    $('#btnFile3').click(function () {

        //datos.nombreCampo = 'hojavidacond';
        datos.nombreCampo = 'rutaruntprop';
        datos.caso = '8';
        datos.nombreDiv = 'divRUNTPropietario';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error al subir el RUNT del propietario';
        datos.mensajeActualizacion = 'RUNT propietario actualizado';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);

    });

    $("#fecvenciruntprop").on("change", function () {
        cambiarDato(this, 1);
    });

    $('#btnFile4').click(function () {

        datos.nombreCampo = 'rutadatperprop';
        datos.caso = '9';
        datos.nombreDiv = 'divDatPerPropietario';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error al actualizar autorizacion datos personales propietario';
        datos.mensajeActualizacion = 'Autorizacion datos personales propietario actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $("#fechadatperprop").on("change", function () {
        cambiarDato(this, 1);
    });

    $('#btnFile5').click(function () {

        datos.nombreCampo = 'rutarutprop';
        datos.caso = '10';
        datos.nombreDiv = 'divRUTPropietario';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error al actualizar RUT propietario';
        datos.mensajeActualizacion = 'RUT propietario propietario actualizado';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $('#btnFile6').click(function () {

        datos.nombreCampo = 'rutacertbanprop';
        datos.caso = '11';
        datos.nombreDiv = 'divCertBancPropietario';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error al actualizar certificacion bancaria propietario';
        datos.mensajeActualizacion = 'Certificacion bancaria propietario actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $("#fecruntproveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecpolantproveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecpersproveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecprocproveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#feccontrproveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecrnmcproveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecinhabproveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#identloctencom").on("blur", function () {
        cambiarDato(this, 1);
    });
    $("#nomloctencom").on("blur", function () {
        cambiarDato(this, 1);
    });
    $("#dirloctencom").on("blur", function () {
        cambiarDato(this, 1);
    });
    $("#telloctencom").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#id_documentosoporte").on("change", function () {
        cambiarDato(this, 1);
    });

    //aqui poner el otro rut del ltc = locatario // tenedor // comprador 

    $("#numrutltc").on("blur", function () {

        //calcularDigitoVerificador($("#numrutltc").val());

        if (parseInt($("#numrutltc").val()) === 0) {
            cambiarDato(this, 1);
        } else if (validarFormatoRUT($("#numrutltc").val())) {
            cambiarDato(this, 1);
        } else {
            alert("El RUT debe tener entre 7 y 9 dígitos, seguido de un guion y un dígito verificador (número o K). Por favor verifique el digito verificador.");
        }
    });

    $('#btnFile7').click(function () {
        datos.nombreCampo = 'rutarutltc';
        datos.caso = '10';
        datos.nombreDiv = 'divRUTDocumento';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error al RUT ';
        datos.mensajeActualizacion = 'RUT actualizado';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $('#btnLicenciaTransito1').click(function () {
        datos.nombreCampo = 'rutalicenciatransito1';
        datos.caso = '15';
        datos.nombreDiv = 'divImagenLicenciaTransito1';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar imagen Licencia de Transito 1 ';
        datos.mensajeActualizacion = '1er imagen Licencia Transito actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $('#btnLicenciaTransito2').click(function () {
        datos.nombreCampo = 'rutalicenciatransito2';
        datos.caso = '16';
        datos.nombreDiv = 'divImagenLicenciaTransito2';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar imagen Licencia de Transito 2 ';
        datos.mensajeActualizacion = '2da imagen Licencia Transito actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $('#btnrutasoat').click(function () {
        datos.nombreCampo = 'rutasoat';
        datos.caso = '17';
        datos.nombreDiv = 'divImagenrutasoat';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar el archivo del SOAT ';
        datos.mensajeActualizacion = 'Imagen del SOAT actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $('#btnrutarevtecno').click(function () {
        datos.nombreCampo = 'rutarevtecno';
        datos.caso = '18';
        datos.nombreDiv = 'divImagenrutarevtecno';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar el archivo de la revision tecnicomecanica ';
        datos.mensajeActualizacion = 'Imagen de la revision tecnicomecanica actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $('#btnrutapolizarespo').click(function () {
        datos.nombreCampo = 'rutapolizarespo';
        datos.caso = '19';
        datos.nombreDiv = 'divImagenrutapolizarespo';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar el archivo de la poliza de responsabilidad civil ';
        datos.mensajeActualizacion = 'Imagen de la poliza de responsabilidad civil actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $('#btnrutarndc').click(function () {
        datos.nombreCampo = 'rutarndc';
        datos.caso = '20';
        datos.nombreDiv = 'divImagenrutarndc';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar el archivo RNDC ';
        datos.mensajeActualizacion = 'Imagen del RNDC actualizado';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $('#btnrutaimgcedcond1').click(function () {
        datos.nombreCampo = 'rutaimgcedcond1';
        datos.caso = '21';
        datos.nombreDiv = 'divImagenrutaimgcedcond1';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar 1er imagen o archivo de la cedula del conductor ';
        datos.mensajeActualizacion = '1er imagen o archivo de la cedula del conductor actualizado';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $('#btnrutaimgcedcond2').click(function () {
        datos.nombreCampo = 'rutaimgcedcond2';
        datos.caso = '22';
        datos.nombreDiv = 'divImagenrutaimgcedcond2';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar 2da imagen o archivo de la cedula del conductor ';
        datos.mensajeActualizacion = '2da imagen o archivo de la cedula del conductor actualizado';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaplansegsoccond1
    $('#btnrutaplansegsoccond1').click(function () {
        datos.nombreCampo = 'rutaplansegsoccond1';
        datos.caso = '23';
        datos.nombreDiv = 'divImagenrutaplansegsoccond1';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar 1er imagen o archivo de la planilla de seguridad social del conductor ';
        datos.mensajeActualizacion = '1er imagen o archivo de la planilla de seguridad social del conductor actualizado';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaplansegsoccond2
    $('#btnrutaplansegsoccond2').click(function () {
        datos.nombreCampo = 'rutaplansegsoccond2';
        datos.caso = '24';
        datos.nombreDiv = 'divImagenrutaplansegsoccond2';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar 2da imagen o archivo de la planilla de seguridad social del conductor ';
        datos.mensajeActualizacion = '2da imagen o archivo de la planilla de seguridad social del conductor actualizado';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutadatospersonales
    $('#btnrutadatospersonales').click(function () {
        datos.nombreCampo = 'rutadatospersonales';
        datos.caso = '25';
        datos.nombreDiv = 'divImagenrutadatospersonales';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar autorizacion de manejo de datos personales ';
        datos.mensajeActualizacion = 'Imagen o archivo de la autorizacion de manejo de datos personales actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaantepolcond
    $('#btnrutaantepolcond').click(function () {
        datos.nombreCampo = 'rutaantepolcond';
        datos.caso = '26';
        datos.nombreDiv = 'divImagenrutaantepolcond';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Policia - Antecedentes policiales ';
        datos.mensajeActualizacion = 'Imagen o archivo de Policia - Antecedentes policiales actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaantepolcond
    $('#btnrutaantediscond').click(function () {
        datos.nombreCampo = 'rutaantediscond';
        datos.caso = '27';
        datos.nombreDiv = 'divImagenrutaantediscond';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Personeria - Antecedentes disciplinarios ';
        datos.mensajeActualizacion = 'Imagen o archivo de Personeria - Antecedentes disciplinarios actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaprocucond
    $('#btnrutaprocucond').click(function () {
        datos.nombreCampo = 'rutaprocucond';
        datos.caso = '28';
        datos.nombreDiv = 'divImagenrutaprocucond';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Procuraduria ';
        datos.mensajeActualizacion = 'Imagen o archivo de Procuraduria actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutacontrcond
    $('#btnrutacontrcond').click(function () {
        datos.nombreCampo = 'rutacontrcond';
        datos.caso = '29';
        datos.nombreDiv = 'divImagenrutacontrcond';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Contraloría ';
        datos.mensajeActualizacion = 'Imagen o archivo de Contraloría actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutasimitcond
    $('#btnrutasimitcond').click(function () {
        datos.nombreCampo = 'rutasimitcond';
        datos.caso = '30';
        datos.nombreDiv = 'divImagenrutasimitcond';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar SIMIT - Infracciones de transito ';
        datos.mensajeActualizacion = 'Imagen o archivo de SIMIT - Infracciones de transito actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutarnmccond
    $('#btnrutarnmccond').click(function () {
        datos.nombreCampo = 'rutarnmccond';
        datos.caso = '31';
        datos.nombreDiv = 'divImagenrutarnmccond';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Registro Nacional de Medidas Correctivas - RNMC';
        datos.mensajeActualizacion = 'Imagen o archivo de Registro Nacional de Medidas Correctivas - RNMC actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutarnmccond
    $('#btnrutainhabcond').click(function () {
        datos.nombreCampo = 'rutainhabcond';
        datos.caso = '32';
        datos.nombreDiv = 'divImagenrutainhabcond';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Consulta de inhabilidades';
        datos.mensajeActualizacion = 'Imagen o archivo de Consulta de inhabilidades actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutafasecolda
    $('#btnrutafasecolda').click(function () {
        datos.nombreCampo = 'rutafasecolda';
        datos.caso = '33';
        datos.nombreDiv = 'divImagenrutafasecolda';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Fasecolda';
        datos.mensajeActualizacion = 'Imagen o archivo Fasecolda actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimgcedprop1 cedula propietario imagen 1
    $('#btnrutaimgcedprop1').click(function () {
        datos.nombreCampo = 'rutaimgcedprop1';
        datos.caso = '34';
        datos.nombreDiv = 'divImagenrutaimgcedprop1';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar 1er imagen cedula propietario';
        datos.mensajeActualizacion = '1er Imagen o archivo cedula propietario actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimgcedprop2 cedula propietario imagen 2
    $('#btnrutaimgcedprop2').click(function () {
        datos.nombreCampo = 'rutaimgcedprop2';
        datos.caso = '35';
        datos.nombreDiv = 'divImagenrutaimgcedprop2';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar 2da imagen cedula propietario';
        datos.mensajeActualizacion = '2da Imagen o archivo cedula propietario actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaantepolprop 
    $('#btnrutaantepolprop').click(function () {
        datos.nombreCampo = 'rutaantepolprop';
        datos.caso = '36';
        datos.nombreDiv = 'divImagenrutaantepolprop';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Imagen o archivo Policia - Antecedentes policiales propietario';
        datos.mensajeActualizacion = 'Imagen o archivo Policia - Antecedentes policiales propietario actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaantedisprop
    $('#btnrutaantedisprop').click(function () {
        datos.nombreCampo = 'rutaantedisprop';
        datos.caso = '37';
        datos.nombreDiv = 'divImagenrutaantedisprop';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Imagen o archivo Personeria - Antecedentes disciplinarios';
        datos.mensajeActualizacion = 'Imagen o archivo Personeria - Antecedentes disciplinarios propietario actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaprocuprop
    $('#btnrutaprocuprop').click(function () {
        datos.nombreCampo = 'rutaprocuprop';
        datos.caso = '38';
        datos.nombreDiv = 'divImagenrutaprocuprop';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Procuraduria';
        datos.mensajeActualizacion = 'Imagen o archivo Procuraduria actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutacontrprop
    $('#btnrutacontrprop').click(function () {
        datos.nombreCampo = 'rutacontrprop';
        datos.caso = '39';
        datos.nombreDiv = 'divImagenrutacontrprop';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Contraloria';
        datos.mensajeActualizacion = 'Imagen o archivo Contraloria actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutarnmcprop
    $('#btnrutarnmcprop').click(function () {
        datos.nombreCampo = 'rutarnmcprop';
        datos.caso = '40';
        datos.nombreDiv = 'divImagenrutarnmcprop';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Registro Nacional de Medidas Correctivas - RNMC';
        datos.mensajeActualizacion = 'Imagen o archivo Registro Nacional de Medidas Correctivas - RNMC actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutainhabprop
    $('#btnrutainhabprop').click(function () {
        datos.nombreCampo = 'rutainhabprop';
        datos.caso = '41';
        datos.nombreDiv = 'divImagenrutainhabprop';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Consulta de inhabilidades';
        datos.mensajeActualizacion = 'Imagen o archivo Consulta de inhabilidades actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimgcedltc1
    $('#btnrutaimgcedltc1').click(function () {
        datos.nombreCampo = 'rutaimgcedltc1';
        datos.caso = '42';
        datos.nombreDiv = 'divImagenrutaimgcedltc1';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Cedula o documento LTC 1';
        datos.mensajeActualizacion = 'Imagen o archivo Cedula o documento LTC actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimgcedltc2
    $('#btnrutaimgcedltc2').click(function () {
        datos.nombreCampo = 'rutaimgcedltc1';
        datos.caso = '43';
        datos.nombreDiv = 'divImagenrutaimgcedltc2';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Cedula o documento LTC 2';
        datos.mensajeActualizacion = 'Imagen o archivo Cedula o documento LTC actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimgdocsopo
    //documento soporte = docsopo
    $('#btnrutaimgdocsopo').click(function () {
        datos.nombreCampo = 'rutaimgdocsopo';
        datos.caso = '44';
        datos.nombreDiv = 'divImagenrutaimgdocsopo';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Documento soporte';
        datos.mensajeActualizacion = 'Imagen o archivo Documento soporte actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimgruttlc
    $('#btnrutaimgruttlc').click(function () {
        datos.nombreCampo = 'rutaimgruttlc';
        datos.caso = '45';
        datos.nombreDiv = 'divImagenrutaimgruttlc';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar RUNT propiedad vehiculo';
        datos.mensajeActualizacion = 'Imagen o archivo RUNT propiedad vehiculo actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimgantjudtlc
    $('#btnrutaimgantjudtlc').click(function () {
        datos.nombreCampo = 'rutaimgantjudtlc';
        datos.caso = '46';
        datos.nombreDiv = 'divImagenrutaimgantjudtlc';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Policia - Antecedentes judiciales ';
        datos.mensajeActualizacion = 'Imagen o archivo Policia - Antecedentes judiciales actualizada';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimgperstlc
    $('#btnrutaimgperstlc').click(function () {
        datos.nombreCampo = 'rutaimgperstlc';
        datos.caso = '47';
        datos.nombreDiv = 'divImagenrutaimgperstlc';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Personeria - Antecedentes disciplinarios ';
        datos.mensajeActualizacion = 'Imagen o archivo Personeria - Antecedentes disciplinarios';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimgproctlc
    $('#btnrutaimgproctlc').click(function () {
        datos.nombreCampo = 'rutaimgproctlc';
        datos.caso = '48';
        datos.nombreDiv = 'divImagenrutaimgproctlc';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Procuraduria ';
        datos.mensajeActualizacion = 'Imagen o archivo Procuraduria';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimgconttlc
    $('#btnrutaimgconttlc').click(function () {
        datos.nombreCampo = 'rutaimgconttlc';
        datos.caso = '49';
        datos.nombreDiv = 'divImagenrutaimgconttlc';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Contraloria ';
        datos.mensajeActualizacion = 'Imagen o archivo Contraloria';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimgrnmctlc
    $('#btnrutaimgrnmctlc').click(function () {
        datos.nombreCampo = 'rutaimgrnmctlc';
        datos.caso = '50';
        datos.nombreDiv = 'divImagenrutaimgrnmctlc';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Registro Nacional de Medidas Correctivas - RNMC ';
        datos.mensajeActualizacion = 'Imagen o archivo Registro Nacional de Medidas Correctivas - RNMC';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaimginhatlc
    $('#btnrutaimginhatlc').click(function () {
        datos.nombreCampo = 'rutaimginhatlc';
        datos.caso = '51';
        datos.nombreDiv = 'divImagenrutaimginhatlc';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Consulta de inhabilidades ';
        datos.mensajeActualizacion = 'Imagen o archivo Consulta de inhabilidades';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutacercarmaniali
    $('#btnrutacercarmaniali').click(function () {
        datos.nombreCampo = 'rutacercarmaniali';
        datos.caso = '52';
        datos.nombreDiv = 'divImagenrutacercarmaniali';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Certificado y carnet de manipulacion de alimentos ';
        datos.mensajeActualizacion = 'Imagen o archivo Certificado y carnet de manipulacion de alimentos';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutacerfumig
    $('#btnrutacerfumig').click(function () {
        datos.nombreCampo = 'rutacerfumig';
        datos.caso = '53';
        datos.nombreDiv = 'divImagenrutacerfumig';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Certificado de fumigacion ';
        datos.mensajeActualizacion = 'Imagen o archivo Certificado de fumigacion';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutacerconsan
    $('#btnrutacerconsan').click(function () {
        datos.nombreCampo = 'rutacerconsan';
        datos.caso = '54';
        datos.nombreDiv = 'divImagenrutacerconsan';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Certificado concepto de sanidad ';
        datos.mensajeActualizacion = 'Imagen o archivo Certificado concepto de sanidad';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutacedpropremol1
    $('#btnrutacedpropremol1').click(function () {
        datos.nombreCampo = 'rutacedpropremol1';
        datos.caso = '55';
        datos.nombreDiv = 'divImagenrutacedpropremol1';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar 1er imagen Cedula propietario remolque ';
        datos.mensajeActualizacion = '1er Imagen o archivo Cedula propietario remolque';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutacedpropremol2
    $('#btnrutacedpropremol2').click(function () {
        datos.nombreCampo = 'rutacedpropremol2';
        datos.caso = '56';
        datos.nombreDiv = 'divImagenrutacedpropremol2';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar 2da imagen Cedula propietario remolque ';
        datos.mensajeActualizacion = '2da Imagen o archivo Cedula propietario remolque';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutatarregremol
    $('#btnrutatarregremol').click(function () {
        datos.nombreCampo = 'rutatarregremol';
        datos.caso = '57';
        datos.nombreDiv = 'divImagenrutatarregremol';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Tarjeta registro remolque ';
        datos.mensajeActualizacion = 'Tarjeta registro remolque';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaruntremol
    $('#btnrutaruntremol').click(function () {
        datos.nombreCampo = 'rutaruntremol';
        datos.caso = '58';
        datos.nombreDiv = 'divImagenrutaruntremol';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Consulta RUNT propiedad remolque ';
        datos.mensajeActualizacion = 'Consulta RUNT propiedad remolque';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutapolremol
    $('#btnrutapolremol').click(function () {
        datos.nombreCampo = 'rutapolremol';
        datos.caso = '59';
        datos.nombreDiv = 'divImagenrutapolremol';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Policía - Antecedentes judiciales ';
        datos.mensajeActualizacion = 'Policía - Antecedentes judiciales';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaproremol 
    $('#btnrutaproremol').click(function () {
        datos.nombreCampo = 'rutaproremol';
        datos.caso = '60';
        datos.nombreDiv = 'divImagenrutaproremol';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Procuraduría ';
        datos.mensajeActualizacion = 'Procuraduría';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaconremol
    $('#btnrutaconremol').click(function () {
        datos.nombreCampo = 'rutaconremol';
        datos.caso = '61';
        datos.nombreDiv = 'divImagenrutaconremol';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Contraloría ';
        datos.mensajeActualizacion = 'Contraloría';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutainharemol
    $('#btnrutainharemol').click(function () {
        datos.nombreCampo = 'rutainharemol';
        datos.caso = '62';
        datos.nombreDiv = 'divImagenrutainharemol';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Consulta de inhabilidades ';
        datos.mensajeActualizacion = 'Consulta de inhabilidades';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutarnmcremol
    $('#btnrutarnmcremol').click(function () {
        datos.nombreCampo = 'rutarnmcremol';
        datos.caso = '63';
        datos.nombreDiv = 'divImagenrutarnmcremol';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar Registro Nacional de Medidas Correctivas - RNMC';
        datos.mensajeActualizacion = 'Registro Nacional de Medidas Correctivas - RNMC';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    //rutaruntcond
    $('#btnrutaruntcond').click(function () {
        datos.nombreCampo = 'rutaruntcond';
        datos.caso = '65';
        datos.nombreDiv = 'divImagenrutaruntcond';
        datos.nombreObj = 'estudioSeguridad';
        datos.mensajeAlert = 'Error actualizar RUNT - Conductor';
        datos.mensajeActualizacion = 'RUNT - Conductor';
        datos.tipoArchivo = 1;
        actualizarArchivo(datos, 1);
    });

    $("#crearPdf").on("click", function () {
        window.print();
    });

    $("#fecruntltcveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecpolantltcveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecpersltcveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecprocltcveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#feccontrltcveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecrnmcltcveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecinhabltcveh").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#cercarmaniali").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#cerfumig").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#cerconsan").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#placaremol").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#tarregremol").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#cedpropremol").on("blur", function () {
        cambiarDato(this, 1);
    });

    $("#nompropremol").on("blur", function () {
        cambiarDato(this, 1);
    });

    $('#btnImg6').click(function () {
        actualizarImagen("imagen5", "1", 3);
    });

    $("#rnmcremol").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#runtremol").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#polremol").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#proremol").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#conremol").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#inharemol").on("change", function () {
        cambiarDato(this, 1);
    });

    $("#fecvenciruntcond").on("change", function () {
        cambiarDato(this, 1);
    });

});

function validarCampoTexto(idCampo, mensajeErrorId) {
    var valorCampo = $("#" + idCampo).val().trim();  // Obtenemos el valor del campo y eliminamos espacios en blanco

    if (valorCampo.length === 0) {
        $("#" + mensajeErrorId).html("Este campo es obligatorio.");
        return false;  // Retorna false si el campo está vacío
    } else {
        $("#" + mensajeErrorId).html("");  // Limpia cualquier mensaje de error
        return true;  // Retorna true si el campo tiene contenido
    }
}

function mostrarCrearEntidad(idEntidad, caso) {

    var id = idEntidad.id;
    var valor = $("#" + id).val();

    if (caso === 1) {
        if (valor === '0') {
            $("#divListaEps").hide();
            $("#divCrearEps").show();
        } else {
            cambiarDato(idEntidad, 1);
        }
    }

    if (caso === 2) {
        if (valor === '0') {
            $("#divListaArl").hide();
            $("#divCrearArl").show();
        } else {
            cambiarDato(idEntidad, 1);
        }
    }

    if (caso === 3) {
        if (valor === '0') {
            $("#divListaPension").hide();
            $("#divCrearPension").show();
        } else {
            cambiarDato(idEntidad, 1);
        }
    }
}

function crearEps() {
    var nombreEps = $("#nombreEps").val();
    $.ajax({
        url: "../trafico/CT_eps.php",
        data: {'caso': '1', 'nombreEps': nombreEps},
        type: "POST",
        success: function (retorno) {
            var obj = JSON.parse(retorno);
            if (parseInt(obj["retorno"]) > 0) {

                objs.id = parseInt(obj["retorno"]);
                objs.nombre = nombreEps;

                actualizarSelect(objs, "id_eps");

                $("#divListaEps").show();
                $("#divCrearEps").hide();

            } else {
                alert("Error al crear la EPS, por favor presione F5 e intentelo nuevamente. Si el error persiste por favor informe.");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function crearEps(){...");
        }
    });
}

function crearArl() {
    var nombreArl = $("#nombreArl").val();
    $.ajax({
        url: "../trafico/CT_arl.php",
        data: {'caso': '1', 'nombreArl': nombreArl},
        type: "POST",
        success: function (retorno) {
            var obj = JSON.parse(retorno);
            if (parseInt(obj["retorno"]) > 0) {

                objs.id = parseInt(obj["retorno"]);
                objs.nombre = nombreArl;

                actualizarSelect(objs, "id_arl");

                $("#divListaArl").show();
                $("#divCrearArl").hide();

            } else {
                alert("Error al crear la ARL, por favor presione F5 e intentelo nuevamente. Si el error persiste por favor informe.");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function crearArl(){...");
        }
    });
}

function crearPension() {
    var nombrePension = $("#nombrePension").val();
    $.ajax({
        url: "../trafico/CT_pension.php",
        data: {'caso': '1', 'nombrePension': nombrePension},
        type: "POST",
        success: function (retorno) {
            var obj = JSON.parse(retorno);
            if (parseInt(obj["retorno"]) > 0) {

                objs.id = parseInt(obj["retorno"]);
                objs.nombre = nombrePension;

                actualizarSelect(objs, "id_pension");

                $("#divListaPension").show();
                $("#divCrearPension").hide();

            } else {
                alert("Error al crear la ARL, por favor presione F5 e intentelo nuevamente. Si el error persiste por favor informe.");
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function crearArl(){...");
        }
    });
}

function actualizarSelect(obj, id_select) {

    $('#' + id_select).append($('<option>', {
        value: obj.id, // ID de la nueva EPS (cambiar según la respuesta del servidor)
        text: obj.nombre  // Nombre de la EPS (cambiar según la respuesta del servidor)
    }));

}

function calcularDigitoVerificador(rut) {
    // Elimina cualquier punto y guion del RUT
    const rutNumeros = rut.replace(/\./g, "").replace(/-/g, "");

    // Reversa el número del RUT
    const rutReverso = rutNumeros.split("").reverse();

    // Serie de multiplicadores
    const serie = [2, 3, 4, 5, 6, 7];
    let suma = 0;

    // Recorre los dígitos del RUT de derecha a izquierda y los multiplica según la serie
    for (let i = 0; i < rutReverso.length; i++) {
        suma += parseInt(rutReverso[i]) * serie[i % serie.length];
    }

    // Calcula el módulo 11
    const resto = suma % 11;
    const digitoVerificador = 11 - resto;

    // Determina el dígito verificador final
    if (digitoVerificador === 11) {
        return "0";
    } else if (digitoVerificador === 10) {
        return "K";
    } else {
        return digitoVerificador.toString();
    }
}

function validarFormatoRUT(rut) {
    // Expresión regular para verificar el formato de un RUT como 30686957-X
    const patron = /^\d{7,9}-[0-9Kk]$/;

    // Comprobar si el RUT coincide con el patrón
    return patron.test(rut);
    //console.log(patron.test(rut));
}

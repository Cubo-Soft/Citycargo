function retornarEstudioSeguridad(placa) {
    $.ajax({
        url: "../trafico/CT_estudio_seguridad.php",
        data: {'caso': '1', 'placa': placa},
        type: "POST",
        success: function (retorno) {
            //console.log(retorno);
            var obj = JSON.parse(retorno);
            if (obj["estudioSeguridad"].length === 0) {

                if (obj?.vehiculo?.[0]?.modelo) {
                    $("#modelo").val(obj["vehiculo"][0]["modelo"]);
                }

                $("#trMensaje1").show();
                $("#tdTextoMensaje1").css("background-color", "#b0ffb6");
                $("#tdTextoMensaje1").text("No se encontro estudio asociado a esta placa. Para realizar la creacion del estudio para esta placa, por favor presione el boton 'Crear estudio'. Cada campo a medida que valla operando el formulario se grabara con la informacion ingresada. Use la tecla 'TAB' para pasar por cada campo, la informacion presentada no es del Estudio de Seguridad. Recuerde ajustar la informacion segun lo requerido");

                borrarFormulario(2);

                crearEstudio($("#placa").val());

                //$("#crearEstudio").show();

            } else {

                $("#divFormFotoConductor").show();
                $("#divFormHojaVidaConductor").show();
                $("#divLicenciaConduccion").show();
                $("#divFormRUNTPropietario").show();
                $("#divFormDatPerPropietario").show();
                $("#divFormRUTPropietario").show();
                $("#divFormCertBancPropietario").show();
                $("#divFormRUTDocumento").show();
                $("#divFormHojaVidaConductor").show();
                $("#divFormPlacaTrailer").show();
                $("#btnFile2").show();
                $("#crearPdf").show();
                //$("#divFormFotoConductor").show();

                $("#crearEstudio").hide();

                $("#modelo").focus();

                $("#modelo").val(obj["estudioSeguridad"][0]["modelo"]);
                $("#id_tipovehiculo").val(obj["estudioSeguridad"][0]["id_tipovehiculo"]);
                $("#licenciatransito").val(obj["estudioSeguridad"][0]["licenciatransito"]);

                $("#fechasoat").val(obj["estudioSeguridad"][0]["fechasoat"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fechasoat"], "fechasoat");

                $("#revisiontecno").val(obj["estudioSeguridad"][0]["revisiontecno"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["revisiontecno"], "revisiontecno");

                $("#polizarespo").val(obj["estudioSeguridad"][0]["polizarespo"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["polizarespo"], "polizarespo");

                $("#rndcvehiculo").val(obj["estudioSeguridad"][0]["rndcvehiculo"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["rndcvehiculo"], "rndcvehiculo");

                $("#fasecoldavehiculo").val(obj["estudioSeguridad"][0]["fasecoldavehiculo"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fasecoldavehiculo"], "fasecoldavehiculo");

                mostrarImagen(obj, '1', 1);
                mostrarImagen(obj, '2', 1);
                mostrarImagen(obj, '3', 1);
                mostrarImagen(obj, '4', 1);

                $("#nomultempresacargue").val(obj["estudioSeguridad"][0]["nomultempresacargue"]);
                $("#conultempresacargue").val(obj["estudioSeguridad"][0]["conultempresacargue"]);
                $("#fecultempresacargue").val(obj["estudioSeguridad"][0]["fecultempresacargue"]);

                $("#cedulaconductor").val(obj["estudioSeguridad"][0]["cedulaconductor"]);
                $("#nombreconductor").val(obj["estudioSeguridad"][0]["nombreconductor"]);
                $("#direccionconductor").val(obj["estudioSeguridad"][0]["direccionconductor"]);
                $("#telefonoconductor").val(obj["estudioSeguridad"][0]["telefonoconductor"]);

                mostrarImagen(obj, null, 2);

                $("#vencimientolicencia").val(obj["estudioSeguridad"][0]["vencimientolicencia"]);

                //mostrar la licencia
                mostrarImagen(obj, null, 2);

                //mostrar la foto del conductor
                mostrarImagen(obj, null, 3);

                //mostrar la hoja de vida del conductor
                mostrarImagen(obj, null, 5);

                $("#estadoeps").val(obj["estudioSeguridad"][0]["estadoeps"]);
                $("#estadoarl").val(obj["estudioSeguridad"][0]["estadoarl"]);
                $("#estadopension").val(obj["estudioSeguridad"][0]["estadopension"]);

                $("#id_eps").val(obj["estudioSeguridad"][0]["id_eps"]);
                $("#id_arl").val(obj["estudioSeguridad"][0]["id_arl"]);
                $("#id_pension").val(obj["estudioSeguridad"][0]["id_pension"]);

                $("#telefonoemergencia").val(obj["estudioSeguridad"][0]["telefonoemergencia"]);
                $("#fechadatospersonales").val(obj["estudioSeguridad"][0]["fechadatospersonales"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fechadatospersonales"], "fechadatospersonales");

                $("#nombrecontacto").val(obj["estudioSeguridad"][0]["nombrecontacto"]);
                $("#id_parentescocontacto").val(obj["estudioSeguridad"][0]["id_parentescocontacto"]);

                $("#nombrerefcond").val(obj["estudioSeguridad"][0]["nombrerefcond"]);
                $("#id_parenrefcond").val(obj["estudioSeguridad"][0]["id_parenrefcond"]);
                $("#dirrefcond").val(obj["estudioSeguridad"][0]["dirrefcond"]);
                $("#telrefcond").val(obj["estudioSeguridad"][0]["telrefcond"]);
                $("#verifirefcond").val(obj["estudioSeguridad"][0]["verifirefcond"]);

                $("#nombrereflab").val(obj["estudioSeguridad"][0]["nombrereflab"]);
                $("#id_parenrefcondlab").val(obj["estudioSeguridad"][0]["id_parenrefcondlab"]);
                $("#direcreflab").val(obj["estudioSeguridad"][0]["direcreflab"]);
                $("#telecreflab").val(obj["estudioSeguridad"][0]["telecreflab"]);
                $("#vercreflab").val(obj["estudioSeguridad"][0]["vercreflab"]);

                $("#fecvenciponal").val(obj["estudioSeguridad"][0]["fecvenciponal"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecvenciponal"], "fecvenciponal");

                $("#fecvencipersone").val(obj["estudioSeguridad"][0]["fecvencipersone"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecvencipersone"], "fecvencipersone");

                $("#fecvenciprocu").val(obj["estudioSeguridad"][0]["fecvenciprocu"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecvenciprocu"], "fecvenciprocu");

                $("#fecvencicontrola").val(obj["estudioSeguridad"][0]["fecvencicontrola"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecvencicontrola"], "fecvencicontrola");

                $("#fecvencisimit").val(obj["estudioSeguridad"][0]["fecvencisimit"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecvencisimit"], "fecvencisimit");

                $("#fecvencirnmc").val(obj["estudioSeguridad"][0]["fecvencirnmc"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecvencirnmc"], "fecvencirnmc");

                $("#fecvenciinhab").val(obj["estudioSeguridad"][0]["fecvenciinhab"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecvenciinhab"], "fecvenciinhab");

                $("#cedulapropietario").val(obj["estudioSeguridad"][0]["cedulapropietario"]);
                $("#nombrepropietario").val(obj["estudioSeguridad"][0]["nombrepropietario"]);
                $("#direccionpropietario").val(obj["estudioSeguridad"][0]["direccionpropietario"]);
                $("#telefonopropietario").val(obj["estudioSeguridad"][0]["telefonopropietario"]);

                //mostrar el runt del propietario
                mostrarImagen(obj, null, 6);

                $("#fecvenciruntprop").val(obj["estudioSeguridad"][0]["fecvenciruntprop"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecvenciruntprop"], "fecvenciruntprop");

                //mostrar el runt del propietario
                mostrarImagen(obj, null, 7);

                $("#fechadatperprop").val(obj["estudioSeguridad"][0]["fechadatperprop"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fechadatperprop"], "fechadatperprop");

                //mostrar el rut del propietario
                mostrarImagen(obj, null, 8);

                //mostrar la certificacion bancaria propietario
                mostrarImagen(obj, null, 9);

                $("#fecruntproveh").val(obj["estudioSeguridad"][0]["fecruntproveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecruntproveh"], "fecruntproveh");

                $("#fecpolantproveh").val(obj["estudioSeguridad"][0]["fecpolantproveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecpolantproveh"], "fecpolantproveh");

                $("#fecpersproveh").val(obj["estudioSeguridad"][0]["fecpersproveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecpersproveh"], "fecpersproveh");

                $("#fecprocproveh").val(obj["estudioSeguridad"][0]["fecprocproveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecprocproveh"], "fecprocproveh");

                $("#feccontrproveh").val(obj["estudioSeguridad"][0]["feccontrproveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["feccontrproveh"], "feccontrproveh");

                $("#fecrnmcproveh").val(obj["estudioSeguridad"][0]["fecrnmcproveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecrnmcproveh"], "fecrnmcproveh");

                $("#fecinhabproveh").val(obj["estudioSeguridad"][0]["fecinhabproveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecinhabproveh"], "fecinhabproveh");

                $("#fecvenciruntcond").val(obj["estudioSeguridad"][0]["fecvenciruntcond"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecvenciruntcond"], "fecvenciruntcond");

                $("#identloctencom").val(obj["estudioSeguridad"][0]["identloctencom"]);
                $("#nomloctencom").val(obj["estudioSeguridad"][0]["nomloctencom"]);
                $("#dirloctencom").val(obj["estudioSeguridad"][0]["dirloctencom"]);
                $("#telloctencom").val(obj["estudioSeguridad"][0]["telloctencom"]);

                $("#id_documentosoporte").val(obj["estudioSeguridad"][0]["id_documentosoporte"]);

                //aqui para el ltc = locatario // tenedor // comprador 

                $("#numrutltc").val(obj["estudioSeguridad"][0]["numrutltc"]);

                //mostrar la hoja de vida del conductor
                mostrarImagen(obj, null, 10);

                $("#numrutpropietario").val(obj["estudioSeguridad"][0]["numrutpropietario"]);

                $("#fecruntltcveh").val(obj["estudioSeguridad"][0]["fecruntltcveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecruntltcveh"], "fecruntltcveh");

                $("#fecpolantltcveh").val(obj["estudioSeguridad"][0]["fecpolantltcveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecpolantltcveh"], "fecpolantltcveh");

                $("#fecpersltcveh").val(obj["estudioSeguridad"][0]["fecpersltcveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecpersltcveh"], "fecpersltcveh");

                $("#fecprocltcveh").val(obj["estudioSeguridad"][0]["fecprocltcveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecprocltcveh"], "fecprocltcveh");

                $("#feccontrltcveh").val(obj["estudioSeguridad"][0]["feccontrltcveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["feccontrltcveh"], "feccontrltcveh");

                $("#fecrnmcltcveh").val(obj["estudioSeguridad"][0]["fecrnmcltcveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecrnmcltcveh"], "fecrnmcltcveh");

                $("#fecinhabltcveh").val(obj["estudioSeguridad"][0]["fecinhabltcveh"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["fecinhabltcveh"], "fecinhabltcveh");

                $("#cercarmaniali").val(obj["estudioSeguridad"][0]["cercarmaniali"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["cercarmaniali"], "cercarmaniali");

                $("#cerfumig").val(obj["estudioSeguridad"][0]["cerfumig"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["cerfumig"], "cerfumig");

                $("#cerconsan").val(obj["estudioSeguridad"][0]["cerconsan"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["cerconsan"], "cerconsan");

                $("#placaremol").val(obj["estudioSeguridad"][0]["placaremol"]);
                $("#tarregremol").val(obj["estudioSeguridad"][0]["tarregremol"]);
                $("#cedpropremol").val(obj["estudioSeguridad"][0]["cedpropremol"]);
                $("#nompropremol").val(obj["estudioSeguridad"][0]["nompropremol"]);

                //mostrar el trailer
                mostrarImagen(obj, null, 11);

                $("#runtremol").val(obj["estudioSeguridad"][0]["runtremol"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["runtremol"], "runtremol");

                $("#polremol").val(obj["estudioSeguridad"][0]["polremol"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["polremol"], "polremol");

                $("#proremol").val(obj["estudioSeguridad"][0]["proremol"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["proremol"], "proremol");

                $("#conremol").val(obj["estudioSeguridad"][0]["conremol"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["conremol"], "conremol");

                $("#inharemol").val(obj["estudioSeguridad"][0]["inharemol"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["inharemol"], "inharemol");

                $("#rnmcremol").val(obj["estudioSeguridad"][0]["rnmcremol"]);
                mostrarDiferenciaFechas(obj["estudioSeguridad"][0]["rnmcremol"], "rnmcremol");

                $("#empleados").val(obj["estudioSeguridad"][0]["cedulaempleado"]);

                $("#fechaestudio").val(obj["estudioSeguridad"][0]["fechaestudio"]);

                $("#observaciones").val(obj["estudioSeguridad"][0]["observaciones"]);

                //mostrar 1er imagen Licencia Transito
                mostrarImagen(obj, null, 12);

                //mostrar 2da imagen Licencia Transito
                mostrarImagen(obj, null, 13);

                //mostrar imagen soat
                mostrarImagen(obj, null, 14);

                //mostrar imagen revision tecnicomecanica
                mostrarImagen(obj, null, 15);

                //mostrar imagen poliza de responsabilidad civil
                mostrarImagen(obj, null, 16);

                //mostrar RNDC
                mostrarImagen(obj, null, 17);

                //mostrar 1er imagen cedula conducutor
                mostrarImagen(obj, null, 18);

                //mostrar 2da imagen cedula conductor
                mostrarImagen(obj, null, 19);

                //mostrar 2da imagen cedula conductor                
                mostrarImagen(obj, null, 20);

                //mostrar 2da imagen cedula conductor
                mostrarImagen(obj, null, 21);

                mostrarImagen(obj, null, 22);

                //mostrar policia - antecedentes policiales
                mostrarImagen(obj, null, 23);

                //mostrar Personeria - Antecedentes disciplinarios
                mostrarImagen(obj, null, 24);

                //mostrar Procuraduria
                mostrarImagen(obj, null, 25);

                //mostrar Contraloría                
                mostrarImagen(obj, null, 26);

                //mostrar Contraloría
                mostrarImagen(obj, null, 27);

                //mostrar RNMC Condcutor
                mostrarImagen(obj, null, 28);

                //Consulta de inhabilidades
                mostrarImagen(obj, null, 29);

                //Fasecolda
                mostrarImagen(obj, null, 30);

                //1er imagen cedula propietario
                mostrarImagen(obj, null, 31);

                //2da imagen cedula propietario
                mostrarImagen(obj, null, 32);

                //Policia -Antecedentes judiciales propietario
                mostrarImagen(obj, null, 33);

                //Policia -Antecedentes judiciales propietario
                mostrarImagen(obj, null, 34);

                //Procuraduria                
                mostrarImagen(obj, null, 35);

                //Contraloria                                
                mostrarImagen(obj, null, 36);

                //RNMC propietario                                
                mostrarImagen(obj, null, 37);

                //inhabilidades propietario                                
                mostrarImagen(obj, null, 38);

                //1er imagen cedula LTC 
                mostrarImagen(obj, null, 39);

                //2da imagen cedula LTC
                mostrarImagen(obj, null, 40);

                //documento soporte                                
                mostrarImagen(obj, null, 41);

                //runt ltc       ok
                mostrarImagen(obj, null, 42);

                //policia antecedentes ltc ok
                mostrarImagen(obj, null, 43);

                //personeria ltc ok
                mostrarImagen(obj, null, 44);

                //produraduria ltc ok
                mostrarImagen(obj, null, 45);

                //contraloria ltc ok
                mostrarImagen(obj, null, 46);

                //rnmc ltc ok
                mostrarImagen(obj, null, 47);

                //inhabilidades ltc ok
                mostrarImagen(obj, null, 48);

                //Certificado y carnet de manipulación de alimentos
                mostrarImagen(obj, null, 49);

                //Certificado de fumigación
                mostrarImagen(obj, null, 50);

                //Certificado concepto de sanidad                
                mostrarImagen(obj, null, 51);

                //1er imagen o archivo de la cedula o documento del remolque
                mostrarImagen(obj, null, 52);

                //2da imagen o archivo de la cedula o documento del remolque
                mostrarImagen(obj, null, 53);

                //Tarjeta registro remolque
                mostrarImagen(obj, null, 54);

                //Consulta RUNT propiedad remolque 
                mostrarImagen(obj, null, 55);

                //Policía - Antecedentes judiciales
                mostrarImagen(obj, null, 56);

                //Policía - Antecedentes judiciales
                mostrarImagen(obj, null, 57);

                //Policía - Antecedentes judiciales
                mostrarImagen(obj, null, 58);

                mostrarImagen(obj, null, 59);

                mostrarImagen(obj, null, 60);

                mostrarImagen(obj, null, 61);

                //correoconductor
                $("#correoconductor").val(obj["estudioSeguridad"][0]["correoconductor"]);

                //correopropietario
                $("#correopropietario").val(obj["estudioSeguridad"][0]["correopropietario"]);

            }

            if (obj["usuariosGps"].length !== 0) {
                $("#operador").val(obj["usuariosGps"][0]["operador"]);
                $("#usuario").val(obj["usuariosGps"][0]["usuario"]);
                $("#clave").val(obj["usuariosGps"][0]["clave"]);
            } else {
                $("#operador").val('');
                $("#usuario").val('');
                $("#clave").val('');
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function retornarEstudioSeguridad(placa){...");
        }
    });
}

function retornarFechaEstudio() {
    $.ajax({
        url: "../trafico/CT_estudio_seguridad.php",
        data: {'caso': '64', 'placa': $("#placa").val()},
        type: "POST",
        success: function (retorno) {
            var obj = JSON.parse(retorno);
            $("#fechaestudio").val(obj[0]["fechaestudio"]);
        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function retornarFechaEstudio(placa){...");
        }
    });
}

function mostrarImagen(obj, idimagen, opcion) {

    if (opcion === 1) {
        if (obj["estudioSeguridad"][0]["imagen" + idimagen].length === 0) {
            $("#divVehiculo" + idimagen).hide();
            $("#divForm" + idimagen).show();
        } else {
            $("#divVehiculo" + idimagen).show();
            $("#divVehiculo" + idimagen).html('<img src="' + obj["estudioSeguridad"][0]["imagen" + idimagen] + '" width="250" height="125" />');
            $("#divForm" + idimagen).show();
        }
    }

    if (opcion === 2) {
        if (obj["estudioSeguridad"][0]["rutalicencia"].length === 0) {
            $("#divLicencia").hide();
            $("#divLicenciaConduccion").show();
        } else {
            $("#divLicencia").show();
            $("#divLicencia").html('<a href="' + obj["estudioSeguridad"][0]["rutalicencia"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver licencia del conductor" />1er imagen o archivo</a>');
            $("#divLicenciaConduccion").show();
        }

        if (obj["estudioSeguridad"][0]["rutalicencia2"].length === 0) {
            $("#divLicencia2").hide();
            $("#divLicenciaConduccion2").show();
        } else {
            $("#divLicencia2").show();
            $("#divLicencia2").html('<a href="' + obj["estudioSeguridad"][0]["rutalicencia2"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver licencia del conductor" />2da imagen o archivo</a>');
            $("#divLicenciaConduccion2").show();
        }
    }

    if (opcion === 3) {
        if (obj["estudioSeguridad"][0]["fotoconductor"].length === 0) {
            $("#divFotoConductor").hide();
            $("#divFormFotoConductor").show();
        } else {
            $("#divFotoConductor").show();
            $("#divFotoConductor").html('<img src="' + obj["estudioSeguridad"][0]["fotoconductor"] + '" width="200" height="200" />');
            $("#divFormFotoConductor").show();
        }
    }

    if (opcion === 4) {
        if (obj["estudioSeguridad"][0]["rutalicencia"].length === 0) {
            $("#divLicencia").hide();
            $("#divLicenciaConduccion").show();
        } else {
            $("#divLicencia").show();
            $("#divLicencia").html('<a href="' + obj["estudioSeguridad"][0]["rutalicencia"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver licencia del conductor" />Ver licencia del conductor</a>');
            $("#divLicenciaConduccion").show();
        }
    }

    if (opcion === 5) {
        if (obj["estudioSeguridad"][0]["hojavidacond"].length === 0) {
            $("#divFormHojaVidaConductor").show();
        } else {
            $("#divHojaVida").show();
            $("#divHojaVida").html('<a href="' + obj["estudioSeguridad"][0]["hojavidacond"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver hoja de vida de conductor" />Ver hoja de vida de conductor</a>');
            $("#divFormHojaVidaConductor").show();
        }
    }

    if (opcion === 6) {
        if (obj["estudioSeguridad"][0]["rutaruntprop"].length === 0) {
            $("#divFormRUNTPropietario").show();
        } else {
            $("#divRUNTPropietario").show();
            $("#divRUNTPropietario").html('<a href="' + obj["estudioSeguridad"][0]["rutaruntprop"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver RUNT del propietario" />Ver RUNT del propietario</a>');
            $("#divFormRUNTPropietario").show();
        }
    }

    if (opcion === 7) {
        if (obj["estudioSeguridad"][0]["rutadatperprop"].length === 0) {
            $("#divFormDatPerPropietario").show();
        } else {
            $("#divDatPerPropietario").show();
            $("#divDatPerPropietario").html('<a href="' + obj["estudioSeguridad"][0]["rutadatperprop"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver autorizacion datos personales propietario" />Ver autorizacion datos personales propietario</a>');
            $("#divFormDatPerPropietario").show();
        }
    }

    if (opcion === 8) {
        if (obj["estudioSeguridad"][0]["rutarutprop"].length === 0) {
            $("#divFormRUTPropietario").show();
        } else {
            $("#divRUTPropietario").show();
            $("#divRUTPropietario").html('<a href="' + obj["estudioSeguridad"][0]["rutarutprop"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver RUT propietario" />Ver RUT propietario</a>');

            $("#divFormRUTPropietario").show();
        }
    }

    if (opcion === 9) {

        if (obj["estudioSeguridad"][0]["rutacertbanprop"].length === 0) {
            $("#divFormCertBancPropietario").show();
        } else {
            $("#divCertBancPropietario").show();
            $("#divCertBancPropietario").html('<a href="' + obj["estudioSeguridad"][0]["rutacertbanprop"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver certificacion bancaria propietario" />Ver certificacion bancaria propietario</a>');

            $("#divFormCertBancPropietario").show();
        }
    }

    if (opcion === 10) {
        if (obj["estudioSeguridad"][0]["rutarutltc"].length === 0) {
            $("#divFormRUTDocumento").show();
        } else {
            $("#divRUTDocumento").show();
            $("#divRUTDocumento").html('<a href="' + obj["estudioSeguridad"][0]["rutarutltc"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver RUT" />Ver RUT</a>');
            $("#divFormRUTDocumento").show();
        }
    }

    if (opcion === 11) {
        if (obj["estudioSeguridad"][0]["rutafototrailer"].length === 0) {
            $("#divFormPlacaTrailer").show();
        } else {
            $("#divFotoPlacaTrailer").show();
            $("#divFotoPlacaTrailer").html('<img src="' + obj["estudioSeguridad"][0]["rutafototrailer"] + '" width="350" height="175" />');
            $("#divFormPlacaTrailer").show();
        }
    }


    if (opcion === 12) {
        if (obj["estudioSeguridad"][0]["rutalicenciatransito1"].length === 0) {
            $("#divImagenLicenciaTransito1").show();
        } else {
            $("#divImagenLicenciaTransito1").show();
            $("#divImagenLicenciaTransito1").html('<a href="' + obj["estudioSeguridad"][0]["rutalicenciatransito1"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver 1er imagen Licencia de Transito" />1er imagen Licencia de Transito</a>');
        }
    }

    if (opcion === 13) {
        if (obj["estudioSeguridad"][0]["rutalicenciatransito2"].length === 0) {
            $("#divImagenLicenciaTransito2").show();
        } else {
            $("#divImagenLicenciaTransito2").show();
            $("#divImagenLicenciaTransito2").html('<a href="' + obj["estudioSeguridad"][0]["rutalicenciatransito2"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver 2da imagen Licencia de Transito" />2da imagen Licencia de Transito</a>');
        }
    }

    if (opcion === 14) {
        if (obj["estudioSeguridad"][0]["rutasoat"].length === 0) {
            $("#divImagenrutasoat").show();
        } else {
            $("#divImagenrutasoat").show();
            $("#divImagenrutasoat").html('<a href="' + obj["estudioSeguridad"][0]["rutasoat"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver Imagen SOAT" />Imagen SOAT</a>');
        }
    }

    if (opcion === 15) {
        if (obj["estudioSeguridad"][0]["rutarevtecno"].length === 0) {
            $("#divImagenrutarevtecno").show();
        } else {
            $("#divImagenrutarevtecno").show();
            $("#divImagenrutarevtecno").html('<a href="' + obj["estudioSeguridad"][0]["rutarevtecno"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver Imagen Revision Tecnicomecanica" />Imagen Revision Tecnicomecanica</a>');
        }
    }

    if (opcion === 16) {
        if (obj["estudioSeguridad"][0]["rutapolizarespo"].length === 0) {
            $("#divImagenrutarevtecno").show();
        } else {
            $("#divImagenrutapolizarespo").show();
            $("#divImagenrutapolizarespo").html('<a href="' + obj["estudioSeguridad"][0]["rutapolizarespo"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver Poliza Responsabilidad Civil" />Poliza Responsabilidad Civil</a>');
        }
    }

    if (opcion === 17) {
        if (obj["estudioSeguridad"][0]["rutarndc"].length === 0) {
            $("#divImagenrutarndc").show();
        } else {
            $("#divImagenrutarndc").show();
            $("#divImagenrutarndc").html('<a href="' + obj["estudioSeguridad"][0]["rutarndc"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver RNDC" />RNDC</a>');
        }
    }

    if (opcion === 18) {
        if (obj["estudioSeguridad"][0]["rutaimgcedcond1"].length === 0) {
            $("#divImagenrutaimgcedcond1").show();
        } else {
            $("#divImagenrutaimgcedcond1").show();
            $("#divImagenrutaimgcedcond1").html('');
            $("#divImagenrutaimgcedcond1").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgcedcond1"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver 1er imagen cedula conductor" />Ver 1er imagen cedula conductor</a>');
        }
    }

    if (opcion === 19) {
        if (obj["estudioSeguridad"][0]["rutaimgcedcond2"].length === 0) {
            $("#divImagenrutaimgcedcond2").show();
        } else {
            $("#divImagenrutaimgcedcond2").show();
            $("#divImagenrutaimgcedcond2").html('');
            $("#divImagenrutaimgcedcond2").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgcedcond2"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver 2da imagen cedula conductor" />Ver 2da imagen cedula conductor</a>');
        }
    }

    //planilla de seguridad social del conductor 1
    if (opcion === 20) {
        if (obj["estudioSeguridad"][0]["rutaplansegsoccond1"].length === 0) {
            $("#divImagenrutaplansegsoccond1").show();
        } else {
            $("#divImagenrutaplansegsoccond1").show();
            $("#divImagenrutaplansegsoccond1").html('');
            $("#divImagenrutaplansegsoccond1").html('<a href="' + obj["estudioSeguridad"][0]["rutaplansegsoccond1"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver 1er imagen planilla seguridad social conductor" />Ver 1er imagen planilla seguridad social conductor</a>');
        }
    }

    //planilla de seguridad social del conductor 2
    if (opcion === 21) {
        if (obj["estudioSeguridad"][0]["rutaplansegsoccond2"].length === 0) {
            $("#divImagenrutaplansegsoccond2").show();
        } else {
            $("#divImagenrutaplansegsoccond2").show();
            $("#divImagenrutaplansegsoccond2").html('');
            $("#divImagenrutaplansegsoccond2").html('<a href="' + obj["estudioSeguridad"][0]["rutaplansegsoccond2"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver 2da imagen planilla seguridad social conductor" />Ver 2da imagen planilla seguridad social conductor</a>');
        }
    }

    //Imagen o archivo de la autorizacion de manejo de datos personales
    if (opcion === 22) {
        if (obj["estudioSeguridad"][0]["rutadatospersonales"].length === 0) {
            $("#divImagenrutadatospersonales").show();
        } else {
            $("#divImagenrutadatospersonales").show();
            $("#divImagenrutadatospersonales").html('');
            $("#divImagenrutadatospersonales").html('<a href="' + obj["estudioSeguridad"][0]["rutadatospersonales"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver Imagen o archivo de la autorización de manejo de datos personales" />Ver Imagen o archivo de la autorización de manejo de datos personales</a>');
        }
    }

    //Imagen o archivo de la autorizacion de manejo de datos personales
    if (opcion === 23) {
        if (obj["estudioSeguridad"][0]["rutaantepolcond"].length === 0) {
            $("#divImagenrutaantepolcond").show();
        } else {
            $("#divImagenrutaantepolcond").show();
            $("#divImagenrutaantepolcond").html('');
            $("#divImagenrutaantepolcond").html('<a href="' + obj["estudioSeguridad"][0]["rutaantepolcond"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Policia - Antecedentes policiales conductor" />Ver Policia - Antecedentes policiales</a>');
        }
    }

    //Imagen o archivo de la autorizacion de manejo de datos personales
    if (opcion === 24) {
        if (obj["estudioSeguridad"][0]["rutaantediscond"].length === 0) {
            $("#divImagenrutaantediscond").show();
        } else {
            $("#divImagenrutaantediscond").show();
            $("#divImagenrutaantediscond").html('');
            $("#divImagenrutaantediscond").html('<a href="' + obj["estudioSeguridad"][0]["rutaantediscond"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Personeria - Antecedentes disciplinarios" />Ver Personeria - Antecedentes disciplinarios</a>');
        }
    }

    //
    //Imagen o archivo Procuraduria
    if (opcion === 25) {
        if (obj["estudioSeguridad"][0]["rutaprocucond"].length === 0) {
            $("#divImagenrutaprocucond").show();
        } else {
            $("#divImagenrutaprocucond").show();
            $("#divImagenrutaprocucond").html('');
            $("#divImagenrutaprocucond").html('<a href="' + obj["estudioSeguridad"][0]["rutaprocucond"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Procuraduria" />Ver Procuraduria</a>');
        }
    }

    //Contraloría
    if (opcion === 26) {
        if (obj["estudioSeguridad"][0]["rutacontrcond"].length === 0) {
            $("#divImagenrutaprocucond").show();
        } else {
            $("#divImagenrutacontrcond").show();
            $("#divImagenrutacontrcond").html('');
            $("#divImagenrutacontrcond").html('<a href="' + obj["estudioSeguridad"][0]["rutacontrcond"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Contraloría" />Ver Contraloría</a>');
        }
    }

    //SIMIT
    if (opcion === 27) {
        if (obj["estudioSeguridad"][0]["rutasimitcond"].length === 0) {
            $("#divImagenrutasimitcond").show();
        } else {
            $("#divImagenrutasimitcond").show();
            $("#divImagenrutasimitcond").html('');
            $("#divImagenrutasimitcond").html('<a href="' + obj["estudioSeguridad"][0]["rutasimitcond"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="SIMIT - Infracciones de transito" />Ver SIMIT - Infracciones de transito</a>');
        }
    }

    //SIMIT
    if (opcion === 28) {
        if (obj["estudioSeguridad"][0]["rutarnmccond"].length === 0) {
            $("#divImagenrutarnmccond").show();
        } else {
            $("#divImagenrutarnmccond").show();
            $("#divImagenrutarnmccond").html('');
            $("#divImagenrutarnmccond").html('<a href="' + obj["estudioSeguridad"][0]["rutarnmccond"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Registro Nacional de Medidas Correctivas - RNMC" />Ver Registro Nacional de Medidas Correctivas - RNMC</a>');
        }
    }

    //Consulta de inhabilidades
    if (opcion === 29) {
        if (obj["estudioSeguridad"][0]["rutainhabcond"].length === 0) {
            $("#divImagenrutainhabcond").show();
        } else {
            $("#divImagenrutainhabcond").show();
            $("#divImagenrutainhabcond").html('');
            $("#divImagenrutainhabcond").html('<a href="' + obj["estudioSeguridad"][0]["rutainhabcond"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Consulta de inhabilidades" />Ver Consulta de inhabilidades</a>');
        }
    }

    //fasecolda
    if (opcion === 30) {
        if (obj["estudioSeguridad"][0]["rutafasecolda"].length === 0) {
            $("#divImagenrutafasecolda").show();
        } else {
            $("#divImagenrutafasecolda").show();
            $("#divImagenrutafasecolda").html('');
            $("#divImagenrutafasecolda").html('<a href="' + obj["estudioSeguridad"][0]["rutafasecolda"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Consulta Fasecolda" />Ver Fasecolda</a>');
        }
    }

    //rutaimgcedprop1
    if (opcion === 31) {
        if (obj["estudioSeguridad"][0]["rutaimgcedprop1"].length === 0) {
            $("#divImagenrutaimgcedprop1").show();
        } else {
            $("#divImagenrutaimgcedprop1").show();
            $("#divImagenrutaimgcedprop1").html('');
            $("#divImagenrutaimgcedprop1").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgcedprop1"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="1er imagen cedula propietario" />Ver 1er imagen cedula propietario</a>');
        }
    }

    //rutaimgcedprop2
    if (opcion === 32) {
        if (obj["estudioSeguridad"][0]["rutaimgcedprop2"].length === 0) {
            $("#divImagenrutaimgcedprop2").show();
        } else {
            $("#divImagenrutaimgcedprop2").show();
            $("#divImagenrutaimgcedprop2").html('');
            $("#divImagenrutaimgcedprop2").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgcedprop2"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="2da imagen cedula propietario" />Ver 2da imagen cedula propietario</a>');
        }
    }

    //rutaantepolprop
    if (opcion === 33) {
        if (obj["estudioSeguridad"][0]["rutaantepolprop"].length === 0) {
            $("#divImagenrutaantepolprop").show();
        } else {
            $("#divImagenrutaantepolprop").show();
            $("#divImagenrutaantepolprop").html('');
            $("#divImagenrutaantepolprop").html('<a href="' + obj["estudioSeguridad"][0]["rutaantepolprop"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Policia - Antecedentes judiciales" />Ver Policia - Antecedentes judiciales</a>');
        }
    }

    //rutaantedisprop
    if (opcion === 34) {
        if (obj["estudioSeguridad"][0]["rutaantedisprop"].length === 0) {
            $("#divImagenrutaantedisprop").show();
        } else {
            $("#divImagenrutaantedisprop").show();
            $("#divImagenrutaantedisprop").html('');
            $("#divImagenrutaantedisprop").html('<a href="' + obj["estudioSeguridad"][0]["rutaantedisprop"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Personeria - Antecedentes disciplinarios" />Ver Personeria - Antecedentes disciplinarios</a>');
        }
    }

    //rutaantedisprop
    if (opcion === 35) {
        if (obj["estudioSeguridad"][0]["rutaprocuprop"].length === 0) {
            $("#divImagenrutaprocuprop").show();
        } else {
            $("#divImagenrutaprocuprop").show();
            $("#divImagenrutaprocuprop").html('');
            $("#divImagenrutaprocuprop").html('<a href="' + obj["estudioSeguridad"][0]["rutaprocuprop"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Procuraduria" />Ver Procuraduria</a>');
        }
    }

    //rutacontrprop
    if (opcion === 36) {
        if (obj["estudioSeguridad"][0]["rutacontrprop"].length === 0) {
            $("#divImagenrutacontrprop").show();
        } else {
            $("#divImagenrutacontrprop").show();
            $("#divImagenrutacontrprop").html('');
            $("#divImagenrutacontrprop").html('<a href="' + obj["estudioSeguridad"][0]["rutacontrprop"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Contraloria" />Ver Contraloria</a>');
        }
    }

    //rutarnmcprop
    if (opcion === 37) {
        if (obj["estudioSeguridad"][0]["rutarnmcprop"].length === 0) {
            $("#divImagenrutarnmcprop").show();
        } else {
            $("#divImagenrutarnmcprop").show();
            $("#divImagenrutarnmcprop").html('');
            $("#divImagenrutarnmcprop").html('<a href="' + obj["estudioSeguridad"][0]["rutarnmcprop"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Registro Nacional de Medidas Correctivas - RNMC" />Ver Registro Nacional de Medidas Correctivas - RNMC</a>');
        }
    }

    //rutainhabprop
    if (opcion === 38) {
        if (obj["estudioSeguridad"][0]["rutainhabprop"].length === 0) {
            $("#divImagenrutainhabprop").show();
        } else {
            $("#divImagenrutainhabprop").show();
            $("#divImagenrutainhabprop").html('');
            $("#divImagenrutainhabprop").html('<a href="' + obj["estudioSeguridad"][0]["rutainhabprop"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Consulta de inhabilidades" />Ver Consulta de inhabilidades</a>');
        }
    }

    //rutaimgcedltc1
    if (opcion === 39) {
        if (obj["estudioSeguridad"][0]["rutaimgcedltc1"].length === 0) {
            $("#divImagenrutaimgcedltc1").show();
        } else {
            $("#divImagenrutaimgcedltc1").show();
            $("#divImagenrutaimgcedltc1").html('');
            $("#divImagenrutaimgcedltc1").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgcedltc1"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Cedula o documento del LTC" />Ver cedula o documento del LTC</a>');
        }
    }

    //rutaimgcedltc2
    if (opcion === 40) {
        if (obj["estudioSeguridad"][0]["rutaimgcedltc2"].length === 0) {
            $("#divImagenrutaimgcedltc2").show();

        } else {
            $("#divImagenrutaimgcedltc2").show();
            $("#divImagenrutaimgcedltc2").html('');
            $("#divImagenrutaimgcedltc2").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgcedltc2"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Cedula o documento del LTC" />Ver cedula o documento del LTC</a>');
        }
    }

    //rutaimgdocsopo
    if (opcion === 41) {
        if (obj["estudioSeguridad"][0]["rutaimgdocsopo"].length === 0) {
            $("#divImagenrutaimgdocsopo").show();
        } else {
            $("#divImagenrutaimgdocsopo").show();
            $("#divImagenrutaimgdocsopo").html('');
            $("#divImagenrutaimgdocsopo").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgdocsopo"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Documento soporte" />Ver Documento soporte</a>');
        }
    }

    //rutaimgruttlc
    //me quedo rut cuando en realidad es runt OK
    if (opcion === 42) {
        if (obj["estudioSeguridad"][0]["rutaimgruttlc"].length === 0) {
            $("#divImagenrutaimgruttlc").show();
        } else {
            $("#divImagenrutaimgruttlc").show();
            $("#divImagenrutaimgruttlc").html('');
            $("#divImagenrutaimgruttlc").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgruttlc"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="RUNT" />Ver RUNT</a>');
        }
    }

    //rutaimgantjudtlc
    if (opcion === 43) {
        if (obj["estudioSeguridad"][0]["rutaimgantjudtlc"].length === 0) {
            $("#divImagenrutaimgantjudtlc").show();
        } else {
            $("#divImagenrutaimgantjudtlc").show();
            $("#divImagenrutaimgantjudtlc").html('');
            $("#divImagenrutaimgantjudtlc").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgantjudtlc"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Antecedentes judiciales" />Ver Antecedentes judiciales</a>');
        }
    }

    //rutaimgperstlc
    if (opcion === 44) {
        if (obj["estudioSeguridad"][0]["rutaimgperstlc"].length === 0) {
            $("#divImagenrutaimgperstlc").show();
        } else {
            $("#divImagenrutaimgperstlc").show();
            $("#divImagenrutaimgperstlc").html('');
            $("#divImagenrutaimgperstlc").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgperstlc"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Personeria" />Ver Personeria</a>');
        }
    }

    //rutaimgproctlc
    if (opcion === 45) {
        if (obj["estudioSeguridad"][0]["rutaimgproctlc"].length === 0) {
            $("#divImagenrutaimgproctlc").show();
        } else {
            $("#divImagenrutaimgproctlc").show();
            $("#divImagenrutaimgproctlc").html('');
            $("#divImagenrutaimgproctlc").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgproctlc"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Procuraduria" />Ver Procuraduria</a>');
        }
    }

    //rutaimgconttlc
    if (opcion === 46) {
        if (obj["estudioSeguridad"][0]["rutaimgconttlc"].length === 0) {
            $("#divImagenrutaimgconttlc").show();
        } else {
            $("#divImagenrutaimgconttlc").show();
            $("#divImagenrutaimgconttlc").html('');
            $("#divImagenrutaimgconttlc").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgconttlc"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Contraloria" />Ver Contraloria</a>');
        }
    }

    //rutaimgrnmctlc
    if (opcion === 47) {
        if (obj["estudioSeguridad"][0]["rutaimgrnmctlc"].length === 0) {
            $("#divImagenrutaimgrnmctlc").show();
        } else {
            $("#divImagenrutaimgrnmctlc").show();
            $("#divImagenrutaimgrnmctlc").html('');
            $("#divImagenrutaimgrnmctlc").html('<a href="' + obj["estudioSeguridad"][0]["rutaimgrnmctlc"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="RNMC" />Ver RNMC</a>');
        }
    }

    //rutaimginhatlc
    if (opcion === 48) {
        if (obj["estudioSeguridad"][0]["rutaimginhatlc"].length === 0) {
            $("#divImagenrutaimginhatlc").show();
        } else {
            $("#divImagenrutaimginhatlc").show();
            $("#divImagenrutaimginhatlc").html('');
            $("#divImagenrutaimginhatlc").html('<a href="' + obj["estudioSeguridad"][0]["rutaimginhatlc"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Inhabilidades" />Ver Inhabilidades</a>');
        }
    }

    //rutacercarmaniali
    if (opcion === 49) {
        if (obj["estudioSeguridad"][0]["rutacercarmaniali"].length === 0) {
            $("#divImagenrutacercarmaniali").show();
        } else {
            $("#divImagenrutacercarmaniali").show();
            $("#divImagenrutacercarmaniali").html('');
            $("#divImagenrutacercarmaniali").html('<a href="' + obj["estudioSeguridad"][0]["rutacercarmaniali"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Certificado y carnet de manipulación de alimentos" />Ver Certificado y carnet de manipulación de alimentos</a>');
        }
    }

    //rutacerfumig
    if (opcion === 50) {
        if (obj["estudioSeguridad"][0]["rutacerfumig"].length === 0) {
            $("#divImagenrutacerfumig").show();
        } else {
            $("#divImagenrutacerfumig").show();
            $("#divImagenrutacerfumig").html('');
            $("#divImagenrutacerfumig").html('<a href="' + obj["estudioSeguridad"][0]["rutacerfumig"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Certificado de fumigación" />Ver Certificado de fumigación</a>');
        }
    }

    //rutacerconsan
    if (opcion === 51) {
        if (obj["estudioSeguridad"][0]["rutacerconsan"].length === 0) {
            $("#divImagenrutacerconsan").show();
        } else {
            $("#divImagenrutacerconsan").show();
            $("#divImagenrutacerconsan").html('');
            $("#divImagenrutacerconsan").html('<a href="' + obj["estudioSeguridad"][0]["rutacerconsan"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Certificado concepto de sanidad" />Ver Certificado concepto de sanidad</a>');
        }
    }

    //rutacedpropremol1
    if (opcion === 52) {
        if (obj["estudioSeguridad"][0]["rutacedpropremol1"].length === 0) {
            $("#divImagenrutacedpropremol1").show();
        } else {
            $("#divImagenrutacedpropremol1").show();
            $("#divImagenrutacedpropremol1").html('');
            $("#divImagenrutacedpropremol1").html('<a href="' + obj["estudioSeguridad"][0]["rutacedpropremol1"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="1er imagen o archivo de la cedula o documento del remolque" />Ver 1er imagen o archivo de la cedula o documento del remolque</a>');
        }
    }

    //rutacedpropremol2
    if (opcion === 53) {
        if (obj["estudioSeguridad"][0]["rutacedpropremol2"].length === 0) {
            $("#divImagenrutacedpropremol2").show();
        } else {
            $("#divImagenrutacedpropremol2").show();
            $("#divImagenrutacedpropremol2").html('');
            $("#divImagenrutacedpropremol2").html('<a href="' + obj["estudioSeguridad"][0]["rutacedpropremol2"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="2da imagen o archivo de la cedula o documento del remolque" />Ver 2da imagen o archivo de la cedula o documento del remolque</a>');
        }
    }

    //rutacedpropremol2
    if (opcion === 54) {
        if (obj["estudioSeguridad"][0]["rutatarregremol"].length === 0) {
            $("#divImagenrutatarregremol").show();
        } else {
            $("#divImagenrutatarregremol").show();
            $("#divImagenrutatarregremol").html('');
            $("#divImagenrutatarregremol").html('<a href="' + obj["estudioSeguridad"][0]["rutatarregremol"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver Tarjeta registro remolque" />Ver Tarjeta registro remolque</a>');
        }
    }

    //rutaruntremol
    if (opcion === 55) {
        if (obj["estudioSeguridad"][0]["rutaruntremol"].length === 0) {
            $("#divImagenrutaruntremol").show();
        } else {
            $("#divImagenrutaruntremol").show();
            $("#divImagenrutaruntremol").html('');
            $("#divImagenrutaruntremol").html('<a href="' + obj["estudioSeguridad"][0]["rutatarregremol"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver Consulta RUNT propiedad remolque" />Ver Consulta RUNT propiedad remolque</a>');
        }
    }

    //rutapolremol
    if (opcion === 56) {
        if (obj["estudioSeguridad"][0]["rutapolremol"].length === 0) {
            $("#divImagenrutapolremol").show();
        } else {
            $("#divImagenrutapolremol").show();
            $("#divImagenrutapolremol").html('');
            $("#divImagenrutapolremol").html('<a href="' + obj["estudioSeguridad"][0]["rutapolremol"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver Policía - Antecedentes judiciales" />Ver Policía - Antecedentes judiciales</a>');
        }
    }

    //rutaproremol
    if (opcion === 57) {
        if (obj["estudioSeguridad"][0]["rutaproremol"].length === 0) {
            $("#divImagenrutaproremol").show();
        } else {
            $("#divImagenrutaproremol").show();
            $("#divImagenrutaproremol").html('');
            $("#divImagenrutaproremol").html('<a href="' + obj["estudioSeguridad"][0]["rutaproremol"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver Procuraduría" />Ver Procuraduría</a>');
        }
    }

    //rutaconremol
    if (opcion === 58) {
        if (obj["estudioSeguridad"][0]["rutaconremol"].length === 0) {
            $("#divImagenrutaconremol").show();
        } else {
            $("#divImagenrutaconremol").show();
            $("#divImagenrutaconremol").html('');
            $("#divImagenrutaconremol").html('<a href="' + obj["estudioSeguridad"][0]["rutaconremol"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver Contraloría" />Ver Contraloría</a>');
        }
    }

    //rutainharemol
    if (opcion === 59) {
        if (obj["estudioSeguridad"][0]["rutainharemol"].length === 0) {
            $("#divImagenrutainharemol").show();
        } else {
            $("#divImagenrutainharemol").show();
            $("#divImagenrutainharemol").html('');
            $("#divImagenrutainharemol").html('<a href="' + obj["estudioSeguridad"][0]["rutainharemol"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver Consulta de inhabilidades" />Ver Consulta de inhabilidades</a>');
        }
    }

    //rutainharemol
    if (opcion === 60) {
        if (obj["estudioSeguridad"][0]["rutarnmcremol"].length === 0) {
            $("#divImagenrutarnmcremol").show();
        } else {
            $("#divImagenrutarnmcremol").show();
            $("#divImagenrutarnmcremol").html('');
            $("#divImagenrutarnmcremol").html('<a href="' + obj["estudioSeguridad"][0]["rutarnmcremol"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver Registro Nacional de Medidas Correctivas - RNMC" />Ver Registro Nacional de Medidas Correctivas - RNMC</a>');
        }
    }

    //rutaruntcond    
    if (opcion === 61) {
        if (obj["estudioSeguridad"][0]["rutaruntcond"].length === 0) {
            $("#divImagenrutaruntcond").show();
        } else {
            $("#divImagenrutaruntcond").show();
            $("#divImagenrutaruntcond").html('');
            $("#divImagenrutaruntcond").html('<a href="' + obj["estudioSeguridad"][0]["rutaruntcond"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver RUNT - Conductor" />Ver RUNT - Conductor</a>');
        }
    }

}

function crearEstudio(placa) {
    $.ajax({
        url: "../trafico/CT_estudio_seguridad.php",
        data: {'caso': '2', 'placa': placa, 'cedula': $("#cedula").val()},
        type: "POST",
        success: function (retorno) {
            var obj = JSON.parse(retorno);

            if (obj["estudioSeguridad"] === 1) {
                $("#crearEstudio").hide();
                $("#trMensaje1").show();
                $("#tdTextoMensaje1").css("background-color", "#b0ffb6");
                $("#tdTextoMensaje1").text("Estudio de seguridad creado de manera correcta. Por favor continue con el modelo del vehiculo y el tipo para luego continuar con los siguientes datos del formulario");
                $("#modelo").focus();
                $("#btnImg1").show();
                $("#btnImg2").show();
                $("#btnImg3").show();
                $("#btnImg4").show();
                $("#divFormFotoConductor").show();
                $("#btnImg5").show();
                $("#divFormHojaVidaConductor").show();
                $("#btnFile1").show();
                $("#divFormRUNTPropietario").show();
                $("#divFormDatPerPropietario").show();
                $("#divFormCertBancPropietario").show();
                $("#divFormRUTPropietario").show();
                $("#divFormRUTDocumento").show();
                $("#divFormPlacaTrailer").show();
                retornarEstudioSeguridad($("#placa").val());
            }

        },
        error: function (jqXHR, textStatus, errorThrown) {
            alert("Ha ocurrido un error en AJAX en function crearEstudio(){...");
        }
    });
}

function actualizarImagen(idimagen, numeroimagen, opcion) {

    var formData = new FormData();
    event.preventDefault();
    var file = $('#' + idimagen)[0].files[0]; // Obtener el archivo seleccionado

    formData.append('placa', $("#placa").val());

    if (file) {
        if (opcion === 1) {

            formData.append('numeroimagen', numeroimagen);
            formData.append('caso', '4'); // Añadir el archivo al FormData
            formData.append('' + idimagen, file); // Añadir el archivo al FormData

            $.ajax({
                url: "../trafico/CT_estudio_seguridad.php",
                type: 'POST',
                data: formData,
                contentType: false, // Imprescindible para que jQuery no procese el tipo de contenido
                processData: false, // Evita que jQuery procese los datos
                success: function (retorno) {
                    var obj = JSON.parse(retorno);

                    $("#divVehiculo" + numeroimagen).show();

                    if (obj["imagen"] === 1) {
                        $("#divVehiculo" + numeroimagen).html('<img src="' + obj["ruta"] + '" width="250" height="125" />');
                    } else {
                        $("#divVehiculo" + numeroimagen).html('Error al subir la imagen, por favor presione F5 e intentelo nuevamente. Si el error persiste por favor informe');
                    }
                },
                error: function () {
                    alert('Error al subir la imagen');
                }
            });
        }

        if (opcion === 2) {

            formData.append('placa', $("#placa").val());
            formData.append('caso', '5'); // Añadir el archivo al FormData
            formData.append(file); // Añadir el archivo al FormData

            $.ajax({
                url: "../trafico/CT_estudio_seguridad.php",
                type: 'POST',
                data: formData,
                contentType: false, // Imprescindible para que jQuery no procese el tipo de contenido
                processData: false, // Evita que jQuery procese los datos
                success: function (retorno) {
                    var obj = JSON.parse(retorno);

                    $("#divVehiculo" + numeroimagen).show();

                    if (obj["imagen"] === 1) {
                        $("#divVehiculo" + numeroimagen).html('<img src="' + obj["ruta"] + '" width="250" height="125" />');
                    } else {
                        $("#divVehiculo" + numeroimagen).html('Error al subir la imagen, por favor presione F5 e intentelo nuevamente. Si el error persiste por favor informe');
                    }
                },
                error: function () {
                    alert('Error al subir la imagen');
                }
            });
        }

        if (opcion === 3) {

            formData.append('placa', $("#placa").val());
            formData.append('caso', '13'); // Añadir el archivo al FormData
            formData.append('file', file); // Añadir el archivo al FormData

            $.ajax({
                url: "../trafico/CT_estudio_seguridad.php",
                type: 'POST',
                data: formData,
                contentType: false, // Imprescindible para que jQuery no procese el tipo de contenido
                processData: false, // Evita que jQuery procese los datos
                success: function (retorno) {
                    var obj = JSON.parse(retorno);

                    $("#divFotoPlacaTrailer").show();

                    if (obj["imagen"] === 1) {
                        $("#divFotoPlacaTrailer").html('<img src="' + obj["ruta"] + '" width="350" height="175" />');
                    } else {
                        $("#divFotoPlacaTrailer").html('Error al subir la imagen, por favor presione F5 e intentelo nuevamente. Si el error persiste por favor informe');
                    }

                },
                error: function () {
                    alert('Error al subir la imagen');
                }
            });
        }
    } else {
        alert('Por favor, seleccione una imagen valida');
    }
}

function actualizarArchivo(datos, opcion) {

    var formData = new FormData();
    event.preventDefault();
    var file = $('#' + datos.nombreCampo)[0].files[0]; // Obtener el archivo seleccionado

    formData.append('placa', $("#placa").val());
    formData.append('file', file); // Añadir el archivo al FormData

    if (file) {
        if (opcion === 1) {

            formData.append('caso', datos.caso); // Añadir el archivo al FormData            

            $.ajax({
                url: "../trafico/CT_estudio_seguridad.php",
                type: 'POST',
                data: formData,
                contentType: false, // Imprescindible para que jQuery no procese el tipo de contenido
                processData: false, // Evita que jQuery procese los datos
                success: function (retorno) {
                    console.log(retorno);
                    var obj = JSON.parse(retorno);
                    //console.log(obj);
                    if (obj[datos.nombreObj] === 1) {
                        $("#" + datos.nombreDiv).show();
                        $("#" + datos.nombreDiv).html('');

                        if (datos.tipoArchivo === 1) {
                            $("#" + datos.nombreDiv).html('<a href="' + obj["ruta"] + '?v=' + new Date() + '" target="_blank" ><img src="../imagenes/document_48.png" alt="Ver licencia" />' + datos.mensajeActualizacion + '</a>');
                        }

                        if (datos.tipoArchivo === 2) {
                            $("#" + datos.nombreDiv).html('<img src="' + obj["ruta"] + '?v=' + new Date() + '" width="200" height="200" />');
                        }


                    } else {
                        alert(datos.mensajeAlert);
                    }

                },
                error: function () {
                    alert(datos.mensajeAlert);
                }
            });
        }

    } else {
        alert('Por favor, seleccione una imagen valida');
    }
}

function cambiarDato(campo, opcion) {

    var id = campo.id;
    var valor = $("#" + id).val();


    if (opcion === 1) {
        $.ajax({
            url: "../trafico/CT_estudio_seguridad.php",
            data: {'caso': '3', 'campo': id, 'valor': valor, 'placa': $("#placa").val(), 'emp_cedula': $("#cedula").val()},
            type: "POST",
            success: function (retorno) {
                var obj = JSON.parse(retorno);

                if (obj["estudioSeguridad"] !== 1) {
                    alert("Algo no ha funcionado de manera correcta al realizar la actualizacion. Por favor presione F5 e intentelo nuevamente. Si el error persiste por favor informe a ingenieria");
                } else {
                    //retornarEstudioSeguridad($("#placa").val());
                }

                if (obj["ajusteFecha"] !== 1) {
                    alert("Fallo al actualizar la fecha del cambio. Por favor presione F5 e intentelo nuevamente. Si el error persiste por favor informe a ingenieria");
                } else {
                    retornarFechaEstudio();
                }


            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function cambiarDato(){...");
            }
        });
    }

}

function validarPlaca(placa) {
    // Regular expression pattern for AAA111 format
    const pattern = /^[A-Z]{3}\d{3}$/;

    // Test the code against the pattern
    return pattern.test(placa);
}


function validarEmail(email) {
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}


function mostrarDiferenciaFechas(fechaIngresada, id_campo) {

    //calcular los dias
    var d_c_id = $("#d_c_id").val();
    var d_c_cant = parseInt($("#d_c_cant").val());
    var diferencia = null;
    //fI fechaIngresada
    var fI = new Date(fechaIngresada);
    var fechaHoySinFormato = new Date();

    var yyyy = fechaHoySinFormato.getFullYear();
    var mm = String(fechaHoySinFormato.getMonth() + 1).padStart(2, '0'); // Los meses van de 0 a 11
    var dd = String(fechaHoySinFormato.getDate()).padStart(2, '0');
    var fechaHoy = `${yyyy}-${mm}-${dd}`;

    if (fechaIngresada !== '0000-00-00') {

        //calcula la diferencia en dias
        if (d_c_id === '1') {
            diferencia = fechaHoy - fI;
            diferencia = Math.floor(diferencia / (1000 * 60 * 60 * 24));
        } else

        //calcula la diferencia en meses
        if (d_c_id === '2') {
            //const aniosDiferencia = fechaHoy.getFullYear() - fI.getFullYear();
            //const mesesDiferencia = fechaHoy.getMonth() - fI.getMonth();
            //diferencia = aniosDiferencia * 12 + mesesDiferencia;
            //diferencia = Math.abs(diferencia);
            //console.log(diferencia);
            //diferencia=(fechaHoy.getTime()-fechaIngresada.getTime())/100/(3600*24*7*4);
            //diferencia=Math.abs(Math.roun(diferencia));
            //console.log(fechaHoy + ' fechaHoy');
            //console.log(fechaIngresada + ' fechaIngresada');

            var d1 = new Date(fechaHoy);
            var d2 = new Date(fechaIngresada);

            var year1 = d1.getFullYear();
            var month1 = d1.getMonth(); // 0-indexed
            var year2 = d2.getFullYear();
            var month2 = d2.getMonth(); // 0-indexed

            if (year1 >= year2) {
                diferencia = d_c_cant;
            } else {
                diferencia = Math.abs((year2 - year1) * 12 + (month2 - month1));
            }

            //console.log(diferencia);

        }

        //calcula la diferencia en anios
        // (d_c_id === '3') {
        else {
            const aniosDiferencia = fechaHoy.getFullYear() - fI.getFullYear();
            const mesesDiferencia = fechaHoy.getMonth() - fI.getMonth();

            if (mesesDiferencia < 0 || (mesesDiferencia === 0 && fechaHoy.getDate() < fI.getDate())) {
                diferencia = aniosDiferencia - 1;
            } else {
                diferencia = aniosDiferencia;
            }

            diferencia = Math.abs(diferencia);
        }

        if (diferencia <= d_c_cant) {
            $("#" + id_campo).css('border-color', 'red');
        } else {
            $("#" + id_campo).css('border-color', '');
        }
    }
}

function borrarFormulario(opcion) {

    if (opcion === 1) {
        $("html, body").animate({scrollTop: 0}, "slow");
        $("#placa").val('');
        $("#placa").focus();
    } else {
        $("#crearEstudio").focus();
    }

    $("#crearPdf").hide();
    $("#trMensaje1").hide();
    $("#divForm1").show();
    $("#divForm2").show();
    $("#divForm3").show();
    $("#divForm4").show();
    $("#divVehiculo1").hide();
    $("#divVehiculo2").hide();
    $("#divVehiculo3").hide();
    $("#divVehiculo4").hide();
    $("#btnImg1").hide();
    $("#btnImg2").hide();
    $("#btnImg3").hide();
    $("#btnImg4").hide();
    $("#divFotoConductor").hide();
    $("#divHojaVida").hide();
    $("#btnImg5").hide();
    $("#btnFile1").hide();
    $("#divFormFotoConductor").hide();
    $("#divFormHojaVidaConductor").hide();
    $("#divLicenciaConduccion").hide();
    $("#divLicenciaConduccion2").hide();
    $("#divRUNTPropietario").html('RUNT - Propietario');
    $("#divFormDatPerPropietario").html('');
    $("#divRUTPropietario").html('');
    $("#divCertBancPropietario").html('');
    $("#divFormRUTDocumento").hide();
    $("#divFormHojaVidaConductor").hide();
    $("#divLicencia").hide();
    $("#divLicencia2").hide();
    $("#btnFile2").hide();
    $("#crearEstudio").show();
    $("#divFormPlacaTrailer").hide();
    $("#divFotoPlacaTrailer").hide();
    $("#divImagenLicenciaTransito1").html('1er imagen o archivo de licencia');
    $("#divImagenLicenciaTransito2").html('2do imagen o archivo de licencia');
    $("#divImagenrutasoat").html('Imagen o archivo del SOAT');
    $("#divImagenrutarevtecno").html('Imagen o archivo de la revision tecnicomecanica');
    $("#divImagenrutapolizarespo").html('Imagen o archivo de la poliza de responsabilidad civil');
    $("#divImagenrutarndc").html('Imagen o archivo del RNDC');
    $("#divImagenrutaimgcedcond1").html('1er imagen o archivo de la cedula del conductor');
    $("#divImagenrutaimgcedcond2").html('2da imagen o archivo de la cedula del conductor');
    $("#divImagenrutaplansegsoccond1").html('1er imagen o archivo de la planilla de seguridad social del conductor');
    $("#divImagenrutaplansegsoccond2").html('2da imagen o archivo de la planilla de seguridad social del conductor');
    $("#divImagenrutadatospersonales").html('Imagen o archivo de la autorizacion de manejo de datos personales');
    $("#divImagenrutaantepolcond").html('Imagen o archivo Policia - Antecedentes policiales');
    $("#divImagenrutaantediscond").html('Imagen o archivo Personeria - Antecedentes disciplinarios');
    $("#divImagenrutaprocucond").html('Procuraduría');
    $("#divImagenrutacontrcond").html('Contraloría');
    $("#divImagenrutasimitcond").html('SIMIT - Infracciones de transito');
    $("#divImagenrutarnmccond").html('Registro Nacional de Medidas Correctivas - RNMC');
    $("#divImagenrutainhabcond").html('Consulta de inhabilidades');
    $("#divImagenrutafasecolda").html('Imagen o archivo de Fasecolda');
    $("#divImagenrutaimgcedprop1").html('1er imagen o archivo <br/>de la cedula del propietario');
    $("#divImagenrutaimgcedprop2").html('2da imagen o archivo <br/>de la cedula del propietario');
    $("#divImagenrutaantepolprop").html('Imagen o archivo Policia - Antecedentes policiales');
    $("#divImagenrutaantedisprop").html('Personeria - Antecedentes disciplinarios');
    $("#divImagenrutaprocuprop").html('Procuraduria');
    $("#divImagenrutacontrprop").html('Contraloria');
    $("#divImagenrutarnmcprop").html('Imagen o archivo Registro Nacional de Medidas Correctivas - RNMC actualizada');
    $("#divImagenrutainhabprop").html('Consulta de inhabilidades');
    $("#divImagenrutaimgdocsopo").html('Documento soporte');
    $("#divImagenrutaimgcedltc1").html('Cedula o documento LTC');
    $("#divImagenrutaimgcedltc2").html('Cedula o documento LTC');
    $("#divImagenrutaimgruttlc").html('RUNT');
    $("#divImagenrutaimgantjudtlc").html('Antecedentes judiciales');
    $("#divImagenrutaimgperstlc").html('Personeria');
    $("#divImagenrutaimgproctlc").html('Procuraduria');
    $("#divImagenrutaimgconttlc").html('Contraloria');
    $("#divImagenrutaimgrnmctlc").html('RNMC');
    $("#divImagenrutaimginhatlc").html('Inhabilidades');
    $("#divImagenrutacercarmaniali").html('Certificado y carnet de manipulación de alimentos');
    $("#divImagenrutacerfumig").html('Certificado de fumigación');
    $("#divImagenrutacerconsan").html('Certificado concepto de sanidad');
    $("#divImagenrutacedpropremol1").html('1er imagen o archivo de la cedula o documento del remolque');
    $("#divImagenrutacedpropremol2").html('2da imagen o archivo de la cedula o documento del remolque');
    $("#divImagenrutatarregremol").html('Tarjeta registro remolque');
    $("#divImagenrutaruntremol").html('Consulta RUNT propiedad remolque');
    $("#divImagenrutapolremol").html('Policía - Antecedentes judiciales');
    $("#divImagenrutaproremol").html('Procuraduría');
    $("#divImagenrutaconremol").html('Contraloría');
    $("#divImagenrutaruntcond").html('RUNT - Conductor');

    $("#modelo").val('');
    $("#id_tipovehiculo").val('-1');
    $("#licenciatransito").val('');
    $("#fechasoat").val('');
    $("#revisiontecno").val('');
    $("#polizarespo").val('');
    $("#rndcvehiculo").val('');
    $("#fasecoldavehiculo").val('');

    $("#nomultempresacargue").val();
    $("#conultempresacargue").val();
    $("#fecultempresacargue").val();
    $("#cedulaconductor").val();
    $("#nombreconductor").val();
    $("#direccionconductor").val();
    $("#telefonoconductor").val();
    $("#vencimientolicencia").val();
    $("#estadoeps").val('');
    $("#estadoarl").val('');
    $("#estadopension").val('');
    $("#id_eps").val('');
    $("#id_arl").val('');
    $("#id_pension").val('');
    $("#telefonoemergencia").val('');
    $("#fechadatospersonales").val('');
    $("#nombrecontacto").val('');
    $("#id_parentescocontacto").val('');
    $("#nombrerefcond").val('');
    $("#id_parenrefcond").val('');
    $("#dirrefcond").val('');
    $("#telrefcond").val('');
    $("#verifirefcond").val('');
    $("#nombrereflab").val('');
    $("#id_parenrefcondlab").val('');
    $("#direcreflab").val('');
    $("#telecreflab").val('');
    $("#vercreflab").val('');
    $("#fecvenciponal").val('');
    $("#fecvencipersone").val('');
    $("#fecvenciprocu").val('');
    $("#fecvencicontrola").val('');
    $("#fecvencisimit").val('');
    $("#fecvencirnmc").val('');
    $("#fecvenciinhab").val('');
    $("#cedulapropietario").val('');
    $("#nombrepropietario").val('');
    $("#direccionpropietario").val('');
    $("#telefonopropietario").val('');
    $("#fecvenciruntprop").val('');
    $("#fechadatperprop").val('');
    $("#fecruntproveh").val('');
    $("#fecpolantproveh").val('');
    $("#fecpersproveh").val('');
    $("#fecprocproveh").val('');
    $("#feccontrproveh").val('');
    $("#fecrnmcproveh").val('');
    $("#fecinhabproveh").val('');
    $("#identloctencom").val('');
    $("#nomloctencom").val('');
    $("#dirloctencom").val('');
    $("#telloctencom").val('');
    $("#id_documentosoporte").val('');
    $("#numrutpropietario").val('');
    //aqui para el ltc = locatario // tenedor // comprador 
    $("#numrutltc").val('');
    $("#fecruntltcveh").val('');
    $("#fecpolantltcveh").val('');
    $("#fecpersltcveh").val('');
    $("#fecprocltcveh").val('');
    $("#feccontrltcveh").val('');
    $("#fecrnmcltcveh").val('');
    $("#fecinhabltcveh").val('');
    $("#cercarmaniali").val('');
    $("#cerfumig").val('');
    $("#cerconsan").val('');
    $("#placaremol").val('');
    $("#tarregremol").val('');
    $("#cedpropremol").val('');
    $("#nompropremol").val('');
    $("#runtremol").val('');
    $("#polremol").val('');
    $("#proremol").val('');
    $("#conremol").val('');
    $("#inharemol").val('');
    $("#rnmcremol").val('');
    $("#empleados").val('');
    $("#fechaestudio").val('');
    $("#observaciones").val('');
    $("#operador").val('');
    $("#usuario").val('');
    $("#clave").val('');
    $("#correopropietario").val('');
    $("#correoconductor").val('');

    //$input.removeClass('valid invalid');
    //$("input[type='date']").css('border-color', '');
    $("input[type='date']").css("border-color", "#ced4da");
}

function cambiarVariosDatos(datos, opcion) {

    //var datos = ["cedulapropietario", "nombrepropietario", "direccionpropietario", "telefonopropietario", "correopropietario"];

    datos.colectados.forEach(function (id) {
        $("#" + id).on("input", function () {
            cambiarDato(this, 1);
        });
    });

    for (var i = 0; i < datos.mostrar.length; i++) {
       $("#"+datos.colectados[i]).val($("#"+datos.mostrar[i]).val()).trigger("input");
    }

    /*datos.mostrar.forEach(function(i){
        $("#"+datos.colectados[i]).val($("#"+datos.mostrar[i]).val()).trigger("input");
    });

    /*$("#cedulapropietario").val($("#cedulaconductor").val()).trigger("input");
    $("#nombrepropietario").val($("#nombreconductor").val()).trigger("input");
    $("#direccionpropietario").val($("#direccionconductor").val()).trigger("input");
    $("#telefonopropietario").val($("#telefonoconductor").val()).trigger("input");
    $("#correopropietario").val($("#correoconductor").val()).trigger("input");
     * 
     */

}
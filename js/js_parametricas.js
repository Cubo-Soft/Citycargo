var datosAEnviar = {};

function retornarParametricas(datos, opcion) {

    var valor_campo = null;

    if (opcion === 1) {

        datosAEnviar.tabla = datos["tabla"];
        $.ajax({
            url: "../trafico/CT_es_parametricas.php",
            data: { 'caso': '1', 'datosAEnviar': datosAEnviar },
            type: "POST",
            success: function (retorno) {
                var obj = JSON.parse(retorno);

                $("#divBotonesAccion").show();

                var tabla = "<table class='display' id='tbl_" + datos["tabla"] + "'>";
                tabla += '<thead>';
                tabla += "<tr>";
                tabla += "<th>Id</th><th>Nombre</th><th></th>";
                tabla += "</tr>";
                tabla += '</thead>';
                tabla += '<tbody>';

                for (let index = 0; index < obj.length; index++) {
                    tabla += "<tr>";
                    tabla += "<td>" + obj[index].id + "</td>";
                    tabla += "<td>" + obj[index].nombre + "</td><td><input type='button' class='btn btn-success' value='S' id='" + obj[index].id + "_"+obj[index].nombre+"' onclick='iniciarActualizacion(this.id,1)' /></td>";
                    tabla += "</tr>";
                }

                tabla += '</tbody>';
                tabla += '</table>';

                $("#divData").html(tabla);

                $("#tbl_" + datos["tabla"]).DataTable();

            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function retornarParametricas(datos,opcion=1){...");
            }
        });
   }
    
}

function crearParametricas(data, opcion) {
    
    datosAEnviar.tabla=data["tabla"];
    datosAEnviar.valorCampo=data["valorCampo"];

    if (opcion === 1) {       

        $.ajax({
            url: "../trafico/CT_es_parametricas.php",
            data: { 'caso': '2', 'datosAEnviar': datosAEnviar },
            type: "POST",
            success: function (retorno) {                
                var obj = JSON.parse(retorno);
                if (obj > 0) {
                    if (opcion === 1) {
                        datos["tabla"] = $("#tabla").val();
                        retornarParametricas(datos, 1);
                        $("#nombre").val('');
                    }
                } else {
                    $("#divMensajes").html('<div class="alert alert-warning">Ha ocurrido un error al crear la información. Por favor presione CTRL+SHIFT+R e intentelo nuevamente.</div>');
                }                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function crearParametricas(datos,opcion){...");
            }
        });
    }
    
}

function actualizarParametricas(data,opcion){
   
    if (opcion === 1) {       

        datosAEnviar.id=data.id;
        datosAEnviar.valorCampo=data.valorCampo;
        datosAEnviar.tabla=data.tabla;

        $.ajax({
            url: "../trafico/CT_es_parametricas.php",
            data: { 'caso': '4', 'datosAEnviar': datosAEnviar },
            type: "POST",
            success: function (retorno) {                
                var obj = JSON.parse(retorno);
                if (obj > 0) {
                    if (opcion === 1) {
                        datos["tabla"] = $("#tabla").val();
                        retornarParametricas(datos, 1);
                        $("#nombre").val('');
                    }
                } else {
                    $("#divMensajes").html('<div class="alert alert-warning">Ha ocurrido un error al modificar la información. Por favor presione CTRL+SHIFT+R e intentelo nuevamente.</div>');
                }                
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert("Ha ocurrido un error en AJAX en function crearParametricas(datos,opcion){...");
            }
        });
    }

}
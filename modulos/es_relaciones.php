<?php
session_start();

include_once '../clases/funcionesVarias.php';
include_once '../varios_php/varios.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
}
?>
<!DOCTYPE html>
<!--
    To change this license header, choose License Headers in Project Properties.
    To change this template file, choose Tools | Templates
    and open the template in the editor.
    -->
<html>

<head lang="es">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta charset="iso-8859-1">
    <title>Servicios por pagar</title>
    <link rel="icon" href="../imagenes/favicon.ico">

    <link href="../css/css2.css" rel="stylesheet" type="text/css" />
    <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
    <?= retornarRecursosBootstrap(); ?>
    <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css" />

    <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>    
    <script src="../js/js_componentes.js" type=" text/javascript"></script>
    <script src="../js/es_documentos_entidad.js" type=" text/javascript"></script>
    <script src="../js/js_relaciones.js" type=" text/javascript"></script>
    <script src="../js/accionesenprograma.js" type="text/javascript"></script>
    <script src="../js/cambioColores.js" type="text/javascript"></script>

    <script src="../js/datatable.js" type="text/javascript"></script>
    <link href="../css/datatable.css" rel="stylesheet" type="text/css" />

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
</head>

<body>
    <br />
    <input type="hidden" value="" name="tabla"/>
    <input type="hidden" value="" name="id"/>
    <div class="col-lg-12 alert alert-warning" style="text-align: center;" id="divTexto">
        <p>¡Advertencia!</p>
        <p>Tablas de relación para el "Estudio de seguridad". <br />Al modificar o agregar información a estas tablas, la visualización del "Estudio de seguridad" puede verse afectado y los resultados no ser los esperados</p>
    </div>
    <div id="divInicial">
        <div class="col-lg-12" id="divPrincipal">
            <div class="col-lg-12" id="divFormularioSecundario">
                <div class="col-lg-12" id="textoApoyo">
                    
                </div>
                <div class="row">
                    <div class="col-lg-12" id="divData">                        
                    </div>
                    <div class="col-lg-12" id="divTabla">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12" id="divMensajes">

        </div>
        <div class="col-lg-12" id="divBotones" style="text-align: center;">
            <input type="button" value="Documentos - Entidad" class="btn btn-success" id="es_documentos_entidad" />            
        </div>
    </div>
</body>

</html>
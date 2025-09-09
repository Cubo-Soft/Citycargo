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
    <title>Administración Plantilla Estudio de Seguridad</title>
    <link rel="icon" href="../imagenes/favicon.ico">

    <link href="../css/css2.css" rel="stylesheet" type="text/css" />
    <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
    <?= retornarRecursosBootstrap(); ?>
    <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css" />

    <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>
    <script src="../js/es_componentes.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_entidad.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_documentos.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_tipo_referencia.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_nombre_vinculo.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_entidades_seguridad_social.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_seguridad_social_entidad.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_referencias_entidad.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_documentos_entidad.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_plantilla_estudio_seguridad.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/accionesenprograma.js?v=<?= rand() ?>" type="text/javascript"></script>
    <script src="../js/cambioColores.js?v=<?= rand() ?>" type="text/javascript"></script>

    <script src="../js/datatable.js" type="text/javascript"></script>
    <link href="../css/datatable.css" rel="stylesheet" type="text/css" />

    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
</head>

<body>
    <br />
    <input type="hidden" value="" id="tabla" />
    <input type="hidden" value="" id="id" />
    <div class="col-lg-12 alert alert-warning" style="text-align: center;">
        <p>Plantilla para agregar o quitar valores al "Estudio de seguridad". <br />Al modificar o agregar información a estas tablas, la visualización de la plantilla del "Estudio de seguridad" puede verse afectado y los resultados no ser los esperados</p>
    </div>
    <div id="divInicial">
        <div class="col-lg-12" id="divParametricas">
            <div class="col-lg-2" id="divMenu1">
                <input type="button" value="Documentos de entidades" class="btn btn-success botonIndex" id="es_documentos_entidad" />
                <br>
                <input type="button" value="Referencias de entidades" class="btn btn-success botonIndex" id="es_referencias_entidad" />
                <br>
                <input type="button" value="Seguridad social entidad" class="btn btn-success botonIndex" id="es_seguridad_social_entidad" />
                <br>
            </div>
            <div class="col-lg-10" id="divFormularioSecundario">
                <div class="row">
                    <div class="col-lg-12" id="divData">

                    </div>
                    <div class="col-lg-12" id="divBotonesAccion">
                        <table class="table table-striped">
                            <tr>
                                <td><span id="span1"></span></td>
                                <td>
                                    <div id="div1"></div>
                                </td>
                                <td><span id="span2"></span></td>
                                <td>
                                    <div id="div2"></div>
                                </td>
                                <td><span id="span3"></span></td>
                                <td>
                                    <div id="div3"></div>
                                </td>
                                <td><span id="span4"></span></td>
                                <td>
                                    <div id="div4"></div>
                                </td>
                                <td>
                                    <input type="button" class="btn btn-warning" value="Crear" id="crear" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12" id="divMensajes"></div>
    </div>
    <div id="botones">
            <button type="button" value="REGRESAR" id="botonRegresar" name="botonRegresar" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
            <!--<button type="button" value="GRABAR SERVICIO" id="botonCrearServicio" name="boton" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GRABAR SERVICIO <img src="../imagenes/camion_16.png"></button>-->
            <button type="button" value="SALIR" id="botonSalir" name="botonSalir" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>            
        </div>
</body>

</html>
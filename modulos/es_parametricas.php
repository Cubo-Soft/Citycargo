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
    <title>Parametricas Estudio de Seguridad</title>
    <link rel="icon" href="../imagenes/favicon.ico">

    <link href="../css/css2.css" rel="stylesheet" type="text/css" />
    <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
    <?= retornarRecursosBootstrap(); ?>
    <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css" />

    <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>        
    <script src="../js/es_componentes.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_parametricas.js?v=<?= rand() ?>" type="text/javascript"></script>      
    <script src="../js/js_parametricas.js?v=<?= rand() ?>" type="text/javascript"></script>     
    <script src="../js/es_nombres_estados.js?v=<?= rand() ?>" type="text/javascript"></script>
    <script src="../js/es_estados.js?v=<?= rand() ?>" type="text/javascript"></script>    
    <script src="../js/es_nombres_entidades_seguridad_social.js?v=<?= rand() ?>" type="text/javascript"></script>   
    <script src="../js/es_entidades_seguridad_social.js?v=<?= rand() ?>" type="text/javascript"></script> 
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
        <p>¡Advertencia!</p>
        <p>Tablas parámetricas para el "Estudio de seguridad". <br />Al modificar o agregar información a estas tablas, la visualización del "Estudio de seguridad" puede verse afectado y los resultados no ser los esperados</p>
        <input type="button" value="Mostrar paramétricas" class="btn btn-success" id="mostrarParametricas" />
        <input type="button" value="Mostrar relaciones de paramétricas" class="btn btn-success" id="mostrarRelacionesParametricas" />
    </div>
    <div id="divInicial">
        <div class="col-lg-12" id="divParametricas">
            <div class="col-lg-2" id="divMenu1">
                <input type="button" value="Documentos" class="btn btn-success botonIndex" id="es_documentos" />
                <br>
                <input type="button" value="Entidades" class="btn btn-success botonIndex" id="es_entidad" />
                <br>
                <input type="button" value="Entidades de seguridad social" class="btn btn-success botonIndex" id="es_entidades_seguridad_social" />
                <br>
                <input type="button" value="Estados" class="btn btn-success botonIndex" id="es_estados" />
                <br>
                <input type="button" value="Nombres de vínculo" class="btn btn-success botonIndex" id="es_nombre_vinculo" />
                <br>
                <input type="button" value="Tipos de referencia" class="btn btn-success botonIndex" id="es_tipo_referencia" />
            </div>
            <div class="col-lg-10" id="divFormularioSecundario">
                <div class="row">
                    <div class="col-lg-12" id="divData">

                    </div>
                    <div class="col-lg-12" id="divBotonesAccion">
                        <table class="table table-striped">
                            <tr>
                                <td>Nombre</td>
                                <td><input type="text" class="form form-control" id="nombre" /></td>
                                <td>
                                    <input type="button" class="btn btn-warning" value="Crear" id="crear" />
                                    <input type="button" class="btn btn-warning" value="Modificar" id="modificar" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12" id="divRelacionesParametricas">
            <div class="col-lg-2" id="divMenu1">
                <input type="button" value="Nom. Entidades Seg. Social" class="btn btn-success botonIndex" id="es_nombres_entidades_seguridad_social" />
                <br>
                <input type="button" value="Nombres estados" class="btn btn-success botonIndex" id="es_nombres_estados" />
            </div>
            <div class="col-lg-10" id="divFormularioSecundario2">
                <div class="row">
                    <div class="col-lg-12" id="divData2">

                    </div>
                    <div class="col-lg-12" id="divBotonesAccion2">
                        <input type="hidden" id="id_es_nombres_entidades_seguridad_social" name="id_es_es_nombres_entidades_seguridad_social" value="0" />
                        <table class="table table-striped" id="tbl_es_nombres_entidades_seguridad_social">
                            <tr>
                                <td>Nombre</td>
                                <td><input type="text" class="form form-control" id="nombre2" /></td>
                                <td>Tipo entidad</td>
                                <td>
                                    <div id="divEntidadesSeguridadSocial1"></div>
                                </td>
                                <td>Estados</td>
                                <td>
                                    <div id="divNombresEstados1"></div>
                                </td>
                                <td>
                                    <input type="button" class="btn btn-warning" value="Crear" id="crear2" />
                                    <input type="button" class="btn btn-warning" value="Modificar" id="modificar2" />
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-lg-12" id="divBotonesAccion3">
                        <input type="hidden" id="id_es_nombres_estados" name="id_es_nombres_estados" value="0" />
                        <table class="table table-striped" id="tbl_es_nombres_estados">
                            <tr>
                                <td>Estado</td>
                                <td>
                                    <div id="divEsEstados"></div>
                                </td>
                                <td>Nombre</td>
                                <td><input type="text" class="form form-control" id="nombre3" /></td>
                                <td>
                                    <input type="button" class="btn btn-warning" value="Crear" id="crear3" />
                                    <input type="button" class="btn btn-warning" value="Modificar" id="modificar3" />
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
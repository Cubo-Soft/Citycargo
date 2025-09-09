<?php
session_start();

include_once '../clases/servicios.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    $servicios = new servicios();
    $placas = $servicios->retornarPlacas();
    $clientes = $servicios->retornarClientes();
    $empleados = $servicios->retornarEmpleados($_SESSION["emp_cedula"]);
}
?>
<!DOCTYPE html>
<!--
To change this license header, choose License Headers in Project Properties.
To change this template file, choose Tools | Templates
and open the template in the editor.
-->
<html>

<head>
    <meta charset="UTF-8">
    <title>Estudio de seguridad</title>
    <link rel="icon" href="../imagenes/favicon.ico">

    <link href="../css/css2.css" rel="stylesheet" type="text/css" />
    <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
    <?= retornarRecursosBootstrap(); ?>
    <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css" />

    <script src="../js/es_componentes.js?v=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_entidad.js?b=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_estudio_entidad.js?b=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_documentos_entidad.js?b=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/es_cu_estudio_documentos.js?b=<?= rand(); ?>" type="text/javascript"></script>

    <script src="../js/js_estudio_seguridad.js?b=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/js_comunes.js?b=<?= rand(); ?>" type="text/javascript"></script>
    <script src="../js/numeros_letras.js" type="text/javascript"></script>
    <script src="../js/cambioColores.js" type="text/javascript"></script>
    <script src="../js/jquery.number.js" type="text/javascript"></script>
    <script src="../js/accionesenprograma.js" type="text/javascript"></script>
    <!-- Evitar cache -->
    <meta http-equiv="Expires" content="0">
    <meta http-equiv="Last-Modified" content="0">
    <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
    <meta http-equiv="Pragma" content="no-cache">
</head>

<body>
    <div id="datosCliente" class="panel panel-success small paddinMargin">
        <div class="panel-success">
            <h3 class="panel-title">Estudio de seguridad</h3>
            <input type="hidden" id="cedula_empleado" name="cedula_empleado" value="<?= $_SESSION["emp_cedula"]; ?>" />
            <input type="hidden" id="nombreEmpleado" name="nombreEmpleado" value="<?= $_SESSION["nombre_usuario"]; ?>" />
            <input type="hidden" id="id_estudio" name="id_estudio" value="0" />
        </div>
        <div class="table table-responsive">
            <table class="table table-striped">
                <tr>
                    <td>Fecha</td>
                    <td><input type="date" class="form form-control" value="<?= date("Y-m-d") ?>" id="fecha" /></td>
                    <td>Empleado</td>
                    <td id="nombre_empleado" colspan="3"><?= $_SESSION["nombre_usuario"]; ?></td>

                </tr>
                <tr>
                    <td>Entidad</td>
                    <td id="td_es_entidad"></td>
                    <td>
                        <input type="text" class="form form-control" id="placa" placeholder="AAA000" oninput="$(this).val($(this).val().toUpperCase());" />
                        <input type="number" class="form form-control" id="cedula" placeholder="1234567890" />
                    </td>
                    <td>
                        <div id="divMensajeEntidad"></div>
                    </td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
        </div>

        <div>
            <div id="es_fotos_entidad"></div>
        </div>

        <div>
            <div id="head_es_documentos_entidad"></div>
            <div id="es_documentos_entidad"></div>
        </div>
        <div id="es_cu_estudio_documentos"></div>

        <div id="es_referencias_entidad"></div>
        <div id="es_cu_estudio_referencias"></div>

        <div id="es_seguridad_social_entidad"></div>
        <div id="es_cu_estudio_seguridad_social"></div>

        <div id="es_cu_documentos_adicionales"></div>

        <div id="mensajes"></div>

    </div>


    <div id="botones">
        <button type="button" value="REGRESAR" id="botonRegresar" name="botonRegresar" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>        
        <button type="button" value="SALIR" id="botonSalir" name="botonSalir" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);">SALIR <img src="../imagenes/salir.png"> </button>
    </div>
</body>

</html>
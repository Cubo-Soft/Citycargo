<?php
session_start();

include_once '../clases/conexion.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
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
            <title>Administraci&oacute;n cotizaciones</title>
            <link rel="icon" href="../imagenes/camion256.png">
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
        </head>
        <body>
            <div id="menuIzquierdo">
                <ul>
                    <li><a href="../trafico/salir.php">Salir</a></li>
                    <li><a href="../modulos/index.php">Retornar</a></li>
                </ul>
            </div>
            <?php
            echo 'En construccion';
            ?>
        </body>
    </html>

    <?php
}
?>

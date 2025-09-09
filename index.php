<?php

include './clases/funcionesVarias.php';

//$cadena= devolverCadena();

$cadena= randomString(6, $type = '');
date_default_timezone_set("America/Bogota");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>CITYCARGO S.A.S</title>    
    <link rel="icon" href="imagenes/camion256.png">    
    <link href="css/css.css" rel="stylesheet" type="text/css" />
    <?= retornarRecursosBootstrap(); ?>
    <script src="js/js_index_1.js" type="text/javascript"></script>    
<!--<script>
alert("Buenas tardes.\nSe ha programado una ventana de mantenimiento para hoy entre las 13.30 y 14.30 por tanto en esa franja horaria no estará disponible el sistema.\nGracias!.");
</script>-->
    <style>
        .tamanioLetra{
                font-size: 12px;
            }
        </style>
</head>
<body class="container">
    <!--<div class="col-lg-12">
        <div class="alert alert-danger col-lg-6">Buenos noches. Para el día de hoy entre las 22.20 a las 22.40 tenemos programada una ventana de mantenimiento.</div>
    
        <div class="alert alert-success col-lg-6"><br>Durante ese periodo de tiempo el sistema no estará disponible.</div>
    </div>-->
    
    
    <div class="col-md-6 col-md-offset-3">
        <legend></legend>
        <h1>S.C.T. CITYCARGO S.A.S</h1>
        <h6>Sistema de Control de Carga</h6>
        <legend></legend>
    </div>
    <div class="col-md-6 col-md-offset-3">
        <img src="imagenes/logocity.jpg" alt="" width="480" height="220"/>
    </div>
    <div class="col-md-6 col-md-offset-3">
<form class="form-horizontal" name="validarUsuario" action="trafico/validarUsuario.php" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend></legend>
                <div class="form-group">
                    <label for="inputEmail" class="col-lg-2 control-label">Usuario</label>
                    <div class="col-lg-6">
                        <input class="form-control" id="usuario" name="usuario" placeholder="Usuario" type="text">
                    </div>
                </div>
                <div class="form-group">
                    <label for="inputPassword" class="col-lg-2 control-label">Clave</label>
                    <div class="col-lg-6">
                        <input class="form-control" id="clave" name="clave" placeholder="Clave" type="password">
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-lg-6 col-lg-offset-2">
                        <button name="boton" id="boton" type="submit" class="btn btn-success">INGRESAR <img src="imagenes/key_16.png"> </button>
                    </div>
                </div>
                <legend>
                </legend>
            </fieldset>
        </form>
<!--<h1>Sistema en mantenimiento</h1>-->
        <h6>Versión S.C.T.: <a href="actualizaciones.php" target="_blank">1.20250830 </a></h6>
        <p class="tamanioLetra">Plataforma WEB Desarrollada a la medidad para CITYCARGO S.A.S.<br>
            Derechos reservados &reg; REMG-2025
        </p> </div> <div class="col-md-6 col-md-offset-3" id='mensajes'>
                        <?php
                if (@$_GET["error"] == "1") {
                    echo '<div class="alert alert-dismissible alert-success">Nombre de usuario o clave invalidos. Por favor verifique</div>';
                }
                if (@!is_null($_GET["null"])) {
                    echo '<div class="alert alert-dismissible alert-danger">Intento de sesión invalido </div>';
                }
                if (@$_GET["msj"] == "2") {
                    echo '<div class="alert alert-dismissible alert-danger">Intento de acceso no valido </div>';
                }
                if (@$_GET["msj"] == "3") {
                    echo '<div class="alert alert-dismissible alert-danger">Intento de acceso no valido </div>';
                }
                ?>
    </div>
</body>

</html>

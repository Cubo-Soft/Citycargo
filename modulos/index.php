<?php
session_start();

include_once '../clases/rol_boton.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
    $rol_boton = new rol_boton();

    $cantidadGuias = $rol_boton->retornarGuiasPorFacturar();
    $cantidadServicios = $rol_boton->retornarServiciosPorFacturar(0);
    $cantidadAnticipos = $rol_boton->retornarAnticiposPendientes(1, 1, null, null);
    $cantidadCancelar = $rol_boton->retornarServiciosPorCancelar(2);
    $cantidadConNovedad = $rol_boton->retornarServiciosConNovedad(1);
    $cantidadPorSeguimiento = $rol_boton->seguimientosPendientes();
    $cantidadPorSeguimiento = count($cantidadPorSeguimiento);

    $cancelarServicios = "";
    ?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Men&uacute;</title>
        <link rel="icon" href="../imagenes/camion256.png">
        <link href="../css/css2.css" rel="stylesheet" type="text/css" />
        <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
        <?= retornarRecursosBootstrap(); ?>
        <script src="../js/accionesenprograma.js" type="text/javascript"></script>
        <script src="../js/cambioColores.js" type="text/javascript"></script>
        <!-- Evitar cache -->
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Last-Modified" content="0">
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        <meta http-equiv="Pragma" content="no-cache">
    </head>

    <body>
        <input type="hidden" id="emp_cedula" value="<?= $_SESSION["emp_cedula"] ?>" />
        <form method="post" action="../trafico/redirigir.php" name="formuarlio_index">
            <div id="contenedor">
                <div id="contenedor-index" class="col-lg-12 row input-sm">
                    <div id="divImagenUsa" class="col-lg-4 input-sm"><img class="col-lg-12" src="../imagenes/logocity.jpg"
                            alt="CITYCARGO" id="imagen_usapostal_cotizacion" /></div>
                    <div id="texoDocumento" class="col-lg-4 input-sm">
                        <h1>Men&uacute; principal</h1>
                    </div>
                    <div id="divDatosIniciales" class="col-lg-4 input-sm">
                        <ul class="list-group">
                            <li class="list-group-item ">
                                <span class="badge"><?= $_SESSION["nombre_usuario"]; ?></span>
                                Usuario
                            </li>
                            <li class="list-group-item ">
                                <span class="badge"><?= $_SESSION["departamento"]; ?></span>
                                Departamento
                            </li>
                            <li class="list-group-item ">
                                <span class="badge"><?= date('Y-m-d'); ?></span>
                                Fecha
                            </li>
                        </ul>
                    </div>
                </div>
                <div id="contenedor-index2" class="col-lg-12 panel panel-success">
                    <div class="panel-body">
                        <div class="col-lg-12">
                            <?php
                            $botones = $rol_boton->retornarBotones($_SESSION["emp_cedula"]);
                            pintarMenu($botones, 1);
                            ?>
                            <button name="boton" id="botonSalir" type="submit" class="btn btn-secondary btn-lg botonIndex"
                                onmouseenter="colorIndexEntra(this);" onmouseleave="colorIndexSale(this);" value="SALIR">
                                SALIR </button>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <?php
                        $collg = 4;
                        if ($_SESSION["departamento"] === 'GERENCIA' || $_SESSION["departamento"] === 'SISTEMAS') {
                            if ($cantidadCancelar > 0) {
                                $collg = 3;
                            }
                        }
                        ?>
                        <div class="alert alert-success col-lg-<?= $collg ?>" id="DivPorRevisar">
                            Servicios por revisar:
                            <a class='btn btn-success btn-sm' id='porCancelar'
                                href='../modulos/serviciosporcancelar.php?novedad=2' target="_blank">
                                <strong>
                                    <?= $cantidadCancelar ?>
                                </strong>
                            </a>
                        </div>
                        <div class="alert alert-success  col-lg-<?= $collg ?>" id="DivPorFacturar">
                            Servicios por facturar:
                            <a class="btn btn-success btn-sm" id="porFacturar" href="../modulos/facturasyguias.php">
                                <strong>
                                    <?= $cantidadServicios; ?>
                                </strong>
                            </a>
                        </div>
                        <div class="alert alert-success col-lg-<?= $collg ?>" id="DivPorPagar">
                            Servicios por pagar:
                            <a class="btn btn-success btn-sm" id="porPagar" href="../modulos/anticiposPendientes.php"
                                target="_blank">
                                <strong>
                                    <?= $cantidadAnticipos ?>
                                </strong>
                            </a>
                        </div>
                        <div class="alert alert-success col-lg-<?= $collg ?>" id="DivSeguimiento">
                            Servicios por seguimiento
                            <a class="btn btn-success btn-sm" id="porSeguimiento" href="../modulos/seguimiento.php">
                                <strong>
                                    <?= $cantidadPorSeguimiento; ?>
                                </strong>
                            </a>
                        </div>

                        <?php
                        if ($_SESSION["departamento"] === 'GERENCIA' || $_SESSION["departamento"] === 'SISTEMAS') {
                            ?>
                            <div class="alert alert-success col-lg-<?= $collg ?>" id="DivConNovedad">
                                Servicios finalizados con novedad
                                <a class='btn btn-success btn-sm' id='porCancelar'
                                    href='../modulos/serviciosporcancelar.php?novedad=1' target="_blank">
                                    <strong>
                                        <?= count($cantidadConNovedad); ?>
                                    </strong>
                                </a>
                            </div>

                            <?php
                        }
                        ?>


                    </div>
                    <div id="mensajes" class="col-lg-12">
                        <?php
                        if (@$_GET["cn"] == '2') {
                            $mensaje = "'El número de identificación : " . $_GET['i'] . ", no registra anticipos pendientes'";
                            echo '<div class="alert alert-dismissible alert-danger">' . $mensaje . ');</div>';
                        }
                        if (@$_GET["pj"] == 'n') {
                            echo '<div class="alert alert-dismissible alert-danger">El conductor no existe, para agregar anticipo y crear conductor por favor use el boton ANTICIPOS</div>';
                        }
                        if (@$_GET["msj"] == 'tra') {
                            echo '<div class="alert alert-dismissible alert-danger">Estamos trabajando para modificar la cuenta de cobro. Gracias</div>';
                        }
                        if (@$_GET["msj"] == '1') {
                            echo '<div class="alert alert-dismissible alert-danger"><strong><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>Ha ocurrido un error grave en generarpdfanticipo.php y no se ha generado bien el anticipo, por favor informar inmediatamente </strong></div>';
                        }
                        if (@$_GET["msj"] == '2') {
                            echo '<div class="alert alert-dismissible alert-success"><strong>Se ha creado de manera correcta el anticipo. Gracias </strong></div>';
                        }
                        if (@$_GET["msj"] == '3') {
                            echo '<div class="alert alert-dismissible alert-danger"><strong>No se ubica la c&eacute;dula del conductor. Por favor rectifique </strong></div>';
                        }
                        if (@$_GET["msj"] == '4') {
                            echo '<div class="alert alert-dismissible alert-danger"><span class="glyphicon glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  M&oacute;dulo en construcci&oacute;n</div>';
                        }
                        if (@$_GET["msj"] == '5') {
                            echo '<div class="alert alert-dismissible alert-danger"><span class="glyphicon glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
  <span class="sr-only">Error:</span>
  Se intento realizar un anticipo a un conductor</div>';
                        }
                        if (@$_GET["msj"] == '6') {
                            echo '<div class="alert alert-dismissible alert-danger"><span class="glyphicon glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
  <span class="sr-only">Informaci&oacute;n</span>
  No se registran servicios por pagar con placa o el propietario seleccionados. Por favor verifique</div>';
                        }

                        if (@$_GET["msj"] == '7') {
                            echo '<div class="alert alert-dismissible alert-danger"><span class="glyphicon glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
  <span class="sr-only">Informaci&oacute;n</span>
  Para agregar o crear un servicio presione el bot&oacute;n SERVICIOS</div>';
                        }
                        if (@$_GET["msj"] == '8') {
                            echo '<div class="alert alert-dismissible alert-danger"><span class="glyphicon glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
  <span class="sr-only">Informaci&oacute;n</span>
  Para crear un anticipo debe crear un servicio</div>';
                        }
                        if (@$_GET["msj"] == '9') {
                            echo '<div class="alert alert-dismissible alert-danger"><span class="glyphicon glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
  <span class="sr-only">Acceso denegado</span>
  Acceso denegado</div>';
                        }

                        if (@$_GET["msj"] == '10') {
                            echo '<div class="alert alert-dismissible alert-success"><strong>El PDF del anticipo ' . $_GET["ant"] . ' ya fue generado, por favor verifique su carpeta de descargas </strong></div>';
                        }

                        if (@$_GET["msj"] == '11') {
                            echo '<div class="alert alert-dismissible alert-danger"><strong>Se intento crear un sobreanticipo como nulo.</strong></div>';
                        }
                        if (@$_GET["msj"] == '12') {
                            echo '<div class="alert alert-dismissible alert-danger"><strong>Se intento crear un anticipo sin crear primero el servicio.</strong></div>';
                        }
                        if (@$_GET["msj"] == '13') {
                            echo '<div class="alert alert-dismissible alert-danger"><strong>Se intento realizar un seguimiento sin crear primero el servicio.</strong></div>';
                        }

                        if (@$_GET["msj"] == '14') {
                            echo '<div class="alert alert-dismissible alert-danger"><strong>Se intento realizar un seguimiento alterando el nombre de la variable.</strong></div>';
                        }

                        if (@$_GET["msj"] == '15') {
                            echo '<div class="alert alert-dismissible alert-danger"><strong>Oops, este error se había corregido. la cuenta de cobro no se ha podido generar, se había cambiado los parámetros en la cuenta de cobro. Por favor informe.</strong></div>';
                        }
                        ?>
                    </div>
                </div>
            </div>
            <script src="../js/js_index.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>
        </form>
    </body>

    </html>
    <?php
}

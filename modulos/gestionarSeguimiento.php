<?php
session_start();

include_once '../clases/seguimiento.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    $conductor = null;
    $telefono = null;
    $planderuta = null;
    $operador = null;
    $usuario = null;
    $clave = null;
    $manifiesto = null;
    $propietario = null;
    $telefonoPropietario = null;
    $guias = array();
    $empresas = array();

    $idservicio = (int) $_GET["idservicio"];

    if (!is_int($idservicio)) {
        header("Location: ../modulos/index.php?msj=13");
    }

    if (!isset($idservicio)) {
        header("Location: ../modulos/index.php?msj=14");
    }

    $seguimiento = new seguimiento();
    $placa = $seguimiento->retornarPlaca($_GET["idservicio"], 0);
    if ($placa === 0) {
        $placa = $seguimiento->retornarPlaca($idservicio, 1);
        if ($placa === 0) {
            header("Location: ../modulos/index.php?msj=13");
        } else {
            $datosPlaca = $seguimiento->retornarDatosPlaca($placa);
        }
    }
    $datosConductor = $seguimiento->retornarConductor($placa);
    //var_dump($datosConductor);
    $conductor = $datosConductor[0]["nombresConductor"];
    $cedulaConductor = $datosConductor[0]["cond_identificacion"];
    $telefono = $datosConductor[0]["cond_telefono"];
    $cond_id = $datosConductor[0]["cond_id"];
    $datosPropietario = $seguimiento->retornarPropietario($placa);
    $cedulaPropietario = $datosPropietario[0]["cond_identificacion"];
    $propietario = $datosPropietario[0]["nombresConductor"];
    $telefonoPropietario = $datosPropietario[0]["cond_telefono"];

    $planDRManifiesto = $seguimiento->retornarPlandeRutaManifiesto($_GET["idservicio"]);
    $planderuta = $planDRManifiesto[0]["planderuta"];
    $manifiesto = $planDRManifiesto[0]["manifiesto"];

    if ($planderuta === '') {
        $planderuta = "<div id='divPlanderuta'><input type='file' name='planderuta' id='planderuta' class='form-control-file' /><input type='button' value='Subir plan de ruta' id='subirArchivo' /></div>";
    } else {
        $planderuta = "<div id='divPlanderuta'><a href='" . $planderuta . "' target='_blank' class='btn btn-link' >Ir al archivo</a></div>";
    }

    $manifiesto = $planDRManifiesto[0]["manifiesto"];

    if ($manifiesto === '') {
        $manifiesto = "<input type='text' name='manifiesto' id='manifiesto' class='form-control input-sm' onblur='cambiarManifiesto(this)' />";
    } else {
        $manifiesto = "<input type='text' name='manifiesto' id='manifiesto' value='" . $manifiesto . "' class='form-control input-sm' onblur='cambiarManifiesto(this)' />";
    }

    $usuarioGPS = $seguimiento->retornarUsuarioGPS($placa);

    if (count($usuarioGPS) === 0) {
        $operador = "<input type='text' name='operador' id='operador' value='' class='form-control input-sm' onblur='cambiarOperador(this)'/>";
        $usuario = "<input type='text' name='usuario' id='usuario' value='' class='form-control input-sm' onblur='cambiarUsuario(this)'/>";
        $clave = "<input type='text' name='clave' id='clave' value='' class='form-control input-sm' onblur='cambiarClave(this)'/>";
    } else {
        $operador = "<input type='text' name='operador' id='operador' value='" . $usuarioGPS[0]["operador"] . "' class='form-control input-sm' onblur='cambiarOperador(this)'/>";
        $usuario = "<input type='text' name='usuario' id='usuario' value='" . $usuarioGPS[0]["usuario"] . "' class='form-control input-sm' onblur='cambiarUsuario(this)'/>";
        $clave = "<input type='text' name='clave' id='clave' value='" . $usuarioGPS[0]["clave"] . "' class='form-control input-sm' onblur='cambiarClave(this)'/>";
    }

    $guiasMostrar = $seguimiento->retornarGuias($_GET["idservicio"]);

    //exit();

    $valoresSeguimiento = $seguimiento->retornarSeguimiento($_GET["idservicio"]);

    $mostrarCheckbox = 0;

    $cantidadDeGuias = count($guiasMostrar);
    $guiasTerminadas = 0;

    for ($index2 = 0; $index2 < count($guiasMostrar); $index2++) {
        $estadoGuia = $seguimiento->retornarEstadoGuia($guiasMostrar[$index2]["guia"], $_GET["idservicio"]);
        if (count($estadoGuia) > 0) {
            $guiasTerminadas += 1;
        } else {
            $guiasTerminadas += 0;
        }
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
        <title>Seguimiento</title>
        <link rel="icon" href="../imagenes/camion256.png">

        <link href="../css/css2.css" rel="stylesheet" type="text/css" />
        <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
        <?= retornarRecursosBootstrap(); ?>
        <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css" />

        <link href="../css/cssVentanas.css" rel="stylesheet" type="text/css" />
        <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>
        <script src="../js/accionesenprograma.js" type="text/javascript"></script>
        <script src="../js/js_gestionarSeguimiento.js?n=<?= rand(0, 100000000) ?>" type="text/javascript"></script>
        <script src="../js/fechaActual.js" type="text/javascript"></script>
        <script src="../js/cambioColores.js" type="text/javascript"></script>
        <script src="../js/js_comunes.js" type="text/javascript"></script>
        <!-- Evitar cache -->
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Last-Modified" content="0">
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        <meta http-equiv="Pragma" content="no-cache">
    </head>

    <body>
        <div>
            <div id="contenedor-index">
                <div id="contenedor-index" class="row">
                    <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion" /></div>
                    <div id="texoDocumento" class="col-lg-4">
                        <h1>Seguimiento</h1>
                        <p>
                        <h3>Servicio:<?= $_GET["idservicio"] ?></h3>
                        </p>
                    </div>
                    <div id="divDatosIniciales" class="col-lg-4">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <input type="hidden" id="rol_id" value="<?= $_SESSION["rol_id"] ?>" />
                                <span class="badge"><?php echo $_SESSION["nombre_usuario"]; ?></span>
                                Usuario
                            </li>
                            <li class="list-group-item">
                                <span class="badge"><?php echo $_SESSION["departamento"]; ?></span>
                                Departamento
                            </li>
                            <li class="list-group-item">
                                <span class="badge"><?php echo date('Y-m-d'); ?></span>
                                Fecha
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="panel panel-success" id="divExportarExcell">
                    <div class="panel-heading">
                        <h3 class="panel-title">Datos servicio</h3>
                    </div>
                    <div class="panel-body altura">
                        <div class="col-lg-12 panel">
                            <form id="formSubirImagen1" method="post" enctype="multipart/form-data">
                                <table class="table table-hover">
                                    <thead>
                                        <tr class="table-active">
                                            <th colspan="9" class="sombreado">Datos de la creación del servicio</th>
                                        </tr>
                                        <tr>
                                            <th>Fecha</th>
                                            <th colspan="2" id="ThFechaCreacion"></th>
                                            <th>Usuario</th>
                                            <th colspan="2" id="ThUsuarioCreacion"></th>
                                        </tr>
                                        <tr class="table-active">
                                            <th colspan="9" class="sombreado">Datos conductor</th>
                                        </tr>
                                        <tr>
                                            <th></th>
                                            <th>Placa</th>
                                            <th>Conductor</th>
                                            <th>Tel&eacute;fono</th>
                                            <th>Propietario</th>
                                            <th>Tel&eacute;fono</th>
                                            <th></th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tr>
                                        <td><input type="hidden" id="idservicio" value="<?= $_GET["idservicio"] ?>" /></td>
                                        <td><input type="hidden" id="placa" name="placa" value="<?= $placa ?>" /><?= $placa ?></td>
                                        <td><input type="hidden" id="cond_id" name="cond_id" value="<?= $cond_id ?>" /><?= $conductor ?></td>
                                        <td><input type="number" id="telefonoConductor" name="telefonoConductor" value="<?= $telefono ?>" onblur="cambiarTelefonoConductor(this)" class="form-control input-sm" /></td>
                                        <td><?= $propietario ?></td>
                                        <td><?= $telefonoPropietario ?></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </table>
                        </div>
                        <div class="col-lg-12 panel" id="datosVehiculo">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="9" class="sombreado">Datos plan de ruta</th>
                                    </tr>
                                    <tr>
                                        <th>Archivo</th>
                                        <th>Manifiesto</th>
                                        <th>Operador satelital</th>
                                        <th>Usuario</th>
                                        <th>Clave</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><?= $planderuta ?></td>
                                        <td><?= $manifiesto ?></td>
                                        <td><?= $operador ?></td>
                                        <td><?= $usuario ?></td>
                                        <td><?= $clave ?></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                            </form>
                        </div>
                        <div id="datosGuias">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th colspan="9" class="sombreado">Gu&iacute;as del servicio</th>
                                    </tr>
                                    <tr>
                                        <?php
                                        //if ($cantidadDeGuias !== $guiasTerminadas) {
                                        echo '<th><input type="checkbox" id="todasGuias" name="todasGuias" /></th>';
                                        //} else {
                                        //    echo '<th></th>';
                                        //}
                                        ?>
                                        <th>Gu&iacute;a</th>
                                        <th></th>
                                        <th>Notas</th>
                                        <th>Empresa</th>
                                        <th>Origen</th>
                                        <th>Destino</th>
                                        <th>Fecha y hora de entrega</th>
                                        <th>Valor declarado</th>
                                        <th>Valor flete</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $totalSeguimientos = count($valoresSeguimiento);
                                    $totalEmpresas = count($empresas);

                                    for ($index = 0; $index < count($guiasMostrar); $index++) {
                                        if (!in_array($guiasMostrar[$index]["empresa"], $empresas)) {
                                            $empresas[$index] = $guiasMostrar[$index]["empresa"];
                                        }
                                        echo '<tr>';

                                        //$estadoGuia = $seguimiento->retornarEstadoGuia($guiasMostrar[$index]["guia"], $_GET["idservicio"]);
                                        //if (count($estadoGuia) > 0) {
                                        //    echo '<td></td>';
                                        //} else {
                                        echo '<td><input type="checkbox" id="guia-' . $guiasMostrar[$index]["guia"] . '" value="' . $guiasMostrar[$index]["guia"] . '" onchange="hacerSeguimientoGuia(this)"/></td>';
                                        //}

                                        echo '<td>' . $guiasMostrar[$index]["guia"] . '</td>';
                                        echo "<td><form action='../trafico/generarPDFGuia.php' method='post'><input type='hidden' id='guia' name='guia' value='" . $guiasMostrar[$index]["guia"] . "'/><input type='hidden' id='idservicio' name='idservicio' value='" . $idservicio . "'/>"
                                            . "<input type='submit' id='generarPdf' name='generarPdf' class='btn btn-success btn-xs' value='Generar PDF' /></form></td>";
                                        $notasGuia = $seguimiento->retornarNotasGuia($guiasMostrar[$index]["guia"]);
                                        $notasGuia = $notasGuia[0]["Notas"];
                                        echo '<td style="width: 15%;" >' . $notasGuia . '</td>'
                                            . '<td>' . $guiasMostrar[$index]["empresa"] . '</td>'
                                            . '<td>' . $guiasMostrar[$index]["origen"] . '</td>'
                                            . '<td>' . $guiasMostrar[$index]["destino"] . '</td>'
                                            . '<td>' . $guiasMostrar[$index]["fechaHoraEntrega"] . '</td>'
                                            . '<td>$' . number_format($guiasMostrar[$index]["valordeclarado"]) . '</td>'
                                            . '<td>$' . number_format($guiasMostrar[$index]["valorservicio"]) . '</td>'
                                            . '</tr>';
                                    }
                                    ?>
                                <tbody>
                            </table>
                        </div>
                        <form id="formSubirImagen2" method="post" enctype="multipart/form-data">
                            <div id="divSeguimiento">
                                <input type="hidden" id="todasLasGuias" name="todasLasGuias" value="0" />
                                <input type="hidden" id="emp_cedula" name="emp_cedula" value="<?= $_SESSION["emp_cedula"] ?> " />
                                <table class="table table-responsive" id="tablaLlenarUbicacion">
                                    <thead>
                                        <tr class="sombreadoDos">
                                            <th>Gu&iacute;a</th>
                                            <th>Fecha/hora</th>
                                            <th>Ubicaci&oacute;n</th>
                                            <th>Observaci&oacute;n</th>
                                            <th>Imagen</th>
                                            <th>Usuario</th>
                                            <th>Acorde con plan de ruta</th>
                                            <th>Estado</th>
                                            <th>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $listaEstados = "";
                                        if ($cantidadDeGuias > 1) {
                                            $listaEstados = '<select class="form-control" id="estados" name="estados">
                                                                <option value="0">...</option>    
                                                                <option value="En Punto de Cargue">En Punto de Cargue</option>    
                                                                <option value="En Punto de Entrega">En Punto de Entrega</option>    
                                                                <option value="En transito">En transito</option>    
                                                                <option value="Pernoctando">Pernoctando</option>
                                                                <option value="Guia entregada">Gu&iacute;a entregada</option>
                                                                <option value="Despacho finalizado con novedad">Despacho finalizado con novedad</option>    
                                                                <option value="Despacho finalizado sin novedad">Despacho finalizado sin novedad</option>';
                                        } else {
                                            $listaEstados = '<select class="form-control" id="estados" name="estados">
                                                                <option value="0">...</option>    
                                                                <option value="En Punto de Cargue">En Punto de Cargue</option>    
                                                                <option value="En Punto de Entrega">En Punto de Entrega</option>    
                                                                <option value="En transito">En transito</option>    
                                                                <option value="Pernoctando">Pernoctando</option>                                                                
                                                                <option value="Despacho finalizado con novedad">Despacho finalizado con novedad</option>    
                                                                <option value="Despacho finalizado sin novedad">Despacho finalizado sin novedad</option>';
                                        }
                                        if (count($valoresSeguimiento) <= 0) {
                                        ?>
                                            <tr>
                                                <td></td>
                                                <td><input type="text" id="fechaHora" value="<?= date('Y-m-d H:i:s') ?>" disabled="disabled" class="form-control input-sm" /></td>
                                                <td><input type="text" id="ubicacion" name="ubicacion" class="form-control input-sm" required="required" /></td>
                                                <td><input type="text" id="observacion" name="observacion" class="form-control input-sm" required="required" /></td>
                                                <td><input type="file" accept="image/x-png,image/jpeg" id="imagenGPS" name="imagenGPS" class="form-control-file" onchange="validarArchivo();" /></td>
                                                <td><?= $_SESSION["nombre_usuario"] ?></td>
                                                <td><select class="form-control" id="planderuta" name="planderuta">
                                                        <option value="3">...</option>
                                                        <option value="1">SI</option>
                                                        <option value="0">NO</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <?= $listaEstados; ?>
                                                </td>
                                                <td>
                                                    <input type="button" value="Agregar" id="agregarSeguimiento" name="agregarSeguimiento" />
                                                </td>
                                                <td></td>
                                            </tr>
                                            <?php
                                        } else {
                                            for ($index1 = 0; $index1 < count($valoresSeguimiento); $index1++) {
                                                echo '<tr class="sombreadoDos" >'
                                                    . '<td>' . $valoresSeguimiento[$index1]["guia"] . '</td>'
                                                    . '<td>' . $valoresSeguimiento[$index1]["fechaHora"] . '</td>'
                                                    . '<td>' . $valoresSeguimiento[$index1]["ubicacion"] . '</td>'
                                                    . '<td>' . $valoresSeguimiento[$index1]["observacion"] . '</td>';
                                                if ($valoresSeguimiento[$index1]["imagen"] === '0') {
                                                    echo '<td>No hay imagen GPS</td>';
                                                } else {                                                    
                                                    echo '<td><img src="' . $valoresSeguimiento[$index1]["imagen"] . '" alt="" width="300" height="150"  /></td>';
                                                }
                                                echo '<td>' . $valoresSeguimiento[$index1]["empleado"] . '</td>';
                                                if ($valoresSeguimiento[$index1]["planderuta"] === '1') {
                                                    echo '<td>SI</td>';
                                                } else {
                                                    echo '<td>NO</td>';
                                                }
                                                echo '<td>' . $valoresSeguimiento[$index1]["estadoseguimiento"] . '</td>';
                                                echo "<td></td></tr>";
                                            }

                                            if ($cantidadDeGuias !== $guiasTerminadas) {
                                            ?>
                                                <tr id="trMostrar">
                                                    <th colspan="9" class="sombreado">Ingreso de informaci&oacute;n de seguimiento</th>
                                                </tr>
                                                <tr>
                                                    <th>Gu&iacute;a</th>
                                                    <th>Fecha/hora</th>
                                                    <th>Ubicaci&oacute;n</th>
                                                    <th>Observaci&oacute;n</th>
                                                    <th>Imagen</th>
                                                    <th>Usuario</th>
                                                    <th>Acorde con plan de ruta</th>
                                                    <th>Estado</th>
                                                    <th><input type="hidden" id="todasLasGuias" name="todasLasGuias" value="0" />
                                                        <input type="hidden" id="emp_cedula" name="emp_cedula" value="<?= $_SESSION["emp_cedula"] ?> " />
                                                    </th>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><input type="text" id="fechaHora" value="<?= date('Y-m-d H:i:s') ?>" disabled="disabled" class="form-control input-sm" /></td>
                                                    <td><input type="text" id="ubicacion" name="ubicacion" class="form-control input-sm" required="required" /></td>
                                                    <td><input type="text" id="observacion" name="observacion" class="form-control input-sm" required="required" /></td>
                                                    <td><input type="file" id="imagenGPS" name="imagenGPS" class="form-control-file" accept="image/x-png,image/jpeg" onchange="validarArchivo();" /></td>
                                                    <td><?= $_SESSION["nombre_usuario"] ?></td>
                                                    <td><select class="form-control" id="planderuta" name="planderuta">
                                                            <option value="3">...</option>
                                                            <option value="1">SI</option>
                                                            <option value="0">NO</option>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <?= $listaEstados; ?>
                                                    </td>
                                                    <td>
                                                        <input type="button" value="Agregar seguimiento" id="agregarSeguimiento" name="agregarSeguimiento" class="btn btn-success" />
                                                    </td>
                                                </tr>
                                            <?php
                                            } else {
                                            ?>
                                                <tr id="trMostrar2">
                                                    <th colspan="9" class="sombreado">Ingreso de imagenes de cumplidos</th>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td><input type="file" id="imagenCumplido" name="imagenCumplido" class="form form-control-file" accept="image/x-png,image/jpeg/pdf" onchange="validarArchivo();" /></td>
                                                    <td></td>
                                                    <td><input type="button" value="Agregar cumplido" id="agregarImagenCumplido" name="agregarImagenCumplido" class="btn btn-success" /></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                    <td></td>
                                                </tr>
                                                <tr>
                                                    <td colspan="9" id="tdImagenesCumplidos"></td>
                                                </tr>
                                        <?php
                                            }
                                        }
                                        ?>
                                    </tbody>
                                </table>
                                <div id="popup" style="display: none;">
                                    <div class="content-popup">
                                        <div class="close"><a href="#" id="close"><img src="../imagenes/close.png" /></a></div>
                                        <div>
                                            <table class="table table-responsive">
                                                <thead>
                                                    <tr class="sombreadoDos">
                                                        <th colspan="6">Indique las calificaciones de veh&iacute;culo, propietario y/o conductor</th>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="6">Calificaciones: 1=Satisfactorio 2=No Satisfactorio</td>
                                                    </tr>
                                                    <tr>
                                                        <th></th>
                                                        <th></th>
                                                        <th></th>
                                                        <th>Calificaci&oacute;n</th>
                                                        <th>Detalle</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td><strong>Placa</strong></td>
                                                        <td><?= $placa ?></td>
                                                        <td></td>
                                                        <td><select class='form-control' id="calificacionPlaca">
                                                                <option value="0">...</option>
                                                                <option value="1">1</option>
                                                                <option value="2" selected>2</option>
                                                            </select>
                                                        </td>
                                                        <td><input type="text" id="comentarioPlaca" class="form-control input-sm" /></td>
                                                        <td>
                                                            <div id="divTextoPlaca"></div>
                                                        </td>
                                                    </tr>
                                                    <?php
                                                    if (($cedulaConductor === null) || ($cedulaConductor === $cedulaPropietario)) {
                                                    ?>
                                                        <tr>
                                                            <td><strong>Conductor y/o propietario</strong></td>
                                                            <td><input type="hidden" id="cedulaPropietario" value="<?= $cedulaPropietario ?>" /><?= $conductor ?></td>
                                                            <td></td>
                                                            <td>
                                                                <select class='form-control' id="calificacionPropietario">
                                                                    <option value="0">...</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2" selected>2</option>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" id="comentarioPropietario" class="form-control input-sm" />
                                                                <input type="hidden" id="mismoPropietario" value="1" />
                                                            </td>
                                                            <td>
                                                                <div id="divTextoPropietario"></div>
                                                            </td>
                                                        </tr>
                                                    <?php
                                                    } else {
                                                    ?>
                                                        <tr>
                                                            <td><strong>Propietario</strong></td>
                                                            <td><input type="hidden" id="cedulaPropietario" value="<?= $cedulaPropietario ?>" /><?= $propietario ?></td>
                                                            <td></td>
                                                            <td>
                                                                <select class='form-control' id="calificacionPropietario">
                                                                    <option value="0">...</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2" selected>2</option>
                                                                </select>
                                                            </td>
                                                            <td><input type="text" id="comentarioPropietario" class="form-control input-sm" /></td>
                                                            <td>
                                                                <div id="divTextoPropietario"></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td><strong>Conductor</strong></td>
                                                            <td><input type="hidden" id="cedulaConductor" value="<?= $cedulaConductor ?>" /><?= $conductor ?></td>
                                                            <td></td>
                                                            <td>
                                                                <select class='form-control' id="calificacionConductor">
                                                                    <option value="0">...</option>
                                                                    <option value="1">1</option>
                                                                    <option value="2" selected>2</option>
                                                                </select>
                                                            </td>
                                                            <td>
                                                                <input type="text" id="comentarioConductor" class="form-control input-sm" />
                                                                <input type="hidden" id="mismoPropietario" value="0" />
                                                            </td>
                                                            <td>
                                                                <div id="divTextoConductor"></div>
                                                            </td>
                                                        </tr>

                                                    <?php
                                                    }
                                                    ?>
                                                    <tr>
                                                        <td colspan="4">Recuerde; una vez creada la calificación, no es posible borrarla</td>
                                                        <td><input type="button" value="Grabar calificaciones" id="grabarCalificacion" /></td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            <div style="float:left; width:100%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>
                <div id="mensajes"></div>
                <div class="panel-body">
                    <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                    <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);">SALIR <img src="../imagenes/salir.png"> </button>
                </div>
            </div>
        </div>
    </body>

    </html>
<?php
}

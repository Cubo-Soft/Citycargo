<?php
session_start();

include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    include_once '../clases/roles.php';
    include_once '../clases/CL_dias_configuracion.php';

    $OB_dias_configuracion = new CL_dias_configuracion();

    $datosDiasConfiguracion = $OB_dias_configuracion->retornarDiasConfiguracion(null, 1);

    $roles = new roles();
    $listaEmpleados = $roles->retornarEmpleados();
    $mostrarEmpleados = "<select class='form-control' id='listaEmpleados' name='listaRoles'>"
        . "<option value='0'>...</option>";
    for ($index = 0; $index < count($listaEmpleados); $index++) {
        $mostrarEmpleados .= "<option value='" . $listaEmpleados[$index]["emp_cedula"] . "'>" . $listaEmpleados[$index]["nombres"] . "</option>";
    }
    $mostrarEmpleados .= "</select>";
?>
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="UTF-8">
        <title>Empleado - M&oacute;dulos</title>
        <link rel="icon" href="../imagenes/camion256.png">

        <link href="../css/css2.css" rel="stylesheet" type="text/css" />
        <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
        <?= retornarRecursosBootstrap(); ?>
        <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css" />

        <script src="../js/js_modulosempleados.js?n=<?= rand() ?>" type="text/javascript"></script>
        <script src="../js/accionesenprograma.js" type="text/javascript"></script>
        <script src="../js/cambioColores.js" type="text/javascript"></script>
        <script src="../js/js_dias_configuracion.js" type="text/javascript"></script>
        <link href="../css/deslizador.css" rel="stylesheet" type="text/css" />
        <script src="../js/cambioColores.js" type="text/javascript"></script>
        <!-- Evitar cache -->
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Last-Modified" content="0">
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        <meta http-equiv="Pragma" content="no-cache">
    </head>

    <body>
        <div id="contenedor-index" class="row">
            <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion" /></div>
            <div id="texoDocumento" class="col-lg-4">
                <h1>Empleado - M&oacute;dulos</h1>
            </div>
            <div id="divDatosIniciales" class="col-lg-4">
                <ul class="list-group">
                    <li class="list-group-item">
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
        <div id="separador2">
        </div>
        <div id="divRoles" class="panel panel-success" class="col-lg-12">
            <div class="panel-heading">
                <h3 class="panel-title">Mantenimiento empleados - m&oacute;dulos - permisos</h3>
            </div>
            <div class="panel-body">
                <div class="col-xs-3">
                    <h4>Lista de empleados</h4>
                </div>
                <div class="col-xs-9">
                    <?= $mostrarEmpleados; ?>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="alert alert-dismissible alert-success">
                M&oacute;dulos
            </div>
            <!--</div>-->
            <div class="col-lg-12" id="botones">
                <?php
                $botones = $roles->retornarBotones();
                for ($i = 0; $i < count($botones); $i++) {
                    echo '<div class="col-lg-4" ><div class="col-lg-10" >' . $botones[$i]["valor"] . '</div><div class="col-lg-2" ><input type="checkbox" id="' . $botones[$i]["id_boton"] . '" onclick="asignarBoton(this,0)" class="groupchk" /></div></div>';
                }
                ?>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="alert alert-dismissible alert-success">
                Alertas
            </div>
            <!--</div>-->
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <div class="col-lg-10">
                        Por revisar
                    </div>
                    <div class="col-lg-2">
                        <input type="checkbox" id="chkServiciosPorRevisar" onclick="asignarBoton(this, 2)" class="groupchk1" />
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="col-lg-10">
                        Por facturar
                    </div>
                    <div class="col-lg-2">
                        <input type="checkbox" id="chkServiciosPorFacturar" onclick="asignarBoton(this, 3)" class="groupchk1" />
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="col-lg-10">
                        Por pagar
                    </div>
                    <div class="col-lg-2">
                        <input type="checkbox" id="chkServiciosPorPagar" onclick="asignarBoton(this, 4)" class="groupchk1" />
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="col-lg-10">
                        Seguimiento
                    </div>
                    <div class="col-lg-2">
                        <input type="checkbox" id="chkSeguimiento" onclick="asignarBoton(this, 5)" class="groupchk1" />
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="alert alert-dismissible alert-success">
                Permisos especiales
            </div>
            <div class="col-lg-12">
                <div class="col-lg-3">
                    <div class="col-lg-10">
                        Cambio de valor a pagar a conductor en m&oacute;dulo "Mostrar servicios"
                    </div>
                    <div class="col-lg-2">
                        <input type="checkbox" id="chkModificarFlete" onclick="asignarBoton(this, 1)" class="groupchk" />
                    </div>
                </div>
                <div class="col-lg-3">

                </div>
                <div class="col-lg-3">

                </div>
                <div class="col-lg-3">

                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="alert alert-dismissible alert-success">
                Configuracion del tiempo a validar documentos en el modulo del estudio de seguridad. Por favor recuerde que solo debe haber activo una de las tres opciones.
            </div>
            <div class="col-lg-12">
                <table class="table table-striped">
                    <tr>
                        <th>Id</th>
                        <th>Tiempo</th>
                        <th>Cantidad</th>
                        <th>Estado</th>
                    </tr>
                    <?php

                    for ($i = 0; $i < count($datosDiasConfiguracion); $i++) {
                        echo '<tr>
                        <td>' . $datosDiasConfiguracion[$i]["id"] . '</td>
                            <td>' . $datosDiasConfiguracion[$i]["tiempo"] . '</td>
                            <td><input type="number" class="form form-control" id="c_' . $datosDiasConfiguracion[$i]["id"] . '" name="c_' . $datosDiasConfiguracion[$i]["id"] . '" value="' . $datosDiasConfiguracion[$i]["cantidad"] . '" onchange="cambiarDiasConfiguracion(this,2)" /></td>
                            <td>';

                        if ($datosDiasConfiguracion[$i]["estado"] === 1) {
                            echo '<select class="form form-control" id="e_' . $datosDiasConfiguracion[$i]["id"] . '" onchange="cambiarDiasConfiguracion(this,1)">
                                <option value="1" selected>Activo</option>
                                <option value="0">Inactivo</option>
                                </select>';
                        }

                        if ($datosDiasConfiguracion[$i]["estado"] === 0) {
                            echo '<select class="form form-control" id="e_' . $datosDiasConfiguracion[$i]["id"] . '" onchange="cambiarDiasConfiguracion(this,1)">
                                <option value="0" selected>Inactivo</option>
                                <option value="1">Activo</option>
                                </select>';
                        }

                        echo '</td>
                        </tr>';
                    }

                    ?>
                </table>
            </div>
        </div>
        <div id="mensajes" class="col-lg-12">

        </div>
        <div class="col-lg-12">
            <button type="button" value="REGRESAR" id="botonRegresar" name="botonRegresar" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
            <button type="button" value="SALIR" id="botonSalir" name="botonSalir" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);">SALIR <img src="../imagenes/salir.png"> </button>
        </div>
    </body>

    </html>
<?php
}
?>
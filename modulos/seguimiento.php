<?php
session_start();

include_once '../clases/seguimiento.php';
include_once '../clases/funcionesVarias.php';

$arregloGuias = array();
$arregloIdservicios = array();

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
    $valoresAMostrar = array();
    $seguimiento = new seguimiento();
    $serviciosPorSeguimientoDos = $seguimiento->retornarServiciosPendientes(1);
    $cantidadDos = count($serviciosPorSeguimientoDos);
    ?>
    <!DOCTYPE html>
    <!--
    To change this license header, choose License Headers in Project Properties.
    To change this template file, choose Tools | Templates
    and open the template in the editor.
    -->
    <html>
        <head>            
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta charset="iso-8859-1">
            <title>Por Seguimiento Carga</title>
            <link rel="icon" href="../imagenes/camion256.png">

            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        

            <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>            

            <script src="../js/accionesenprograma.js" type="text/javascript"></script>             
            <script src="../js/js_seguimiento.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
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
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1>Pendientes por seguimientos</h1>
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
                    <div class="panel panel-success" id="DivConsultarSeguimiento">
                        <div class="panel-heading">
                            <h3 class="panel-title">Consultar gu&iacute;a <input type="checkbox" id="habilitarMensajes" name="habilitarMensajes" /></h3>
                        </div>
                        <div id="mensajes">
                            Ingrese n&uacute;mero de gu&iacute;a a buscar: <input type="number" id="guia" name="guia" class="" placeholder="Gu&iacute;a n&uacute;mero" />                            
                        </div>
                        <div id="mostrarSeguimento"></div>
                    </div>
                    <div class="panel panel-success" id="DivListaSeguimientos">
                        <div class="panel-heading">
                            <h3 class="panel-title">Listado de servicios pendientes por seguimiento</h3>                            
                        </div>
                        <div class="panel-body altura">
                            <div class="col-lg-12 panel" id="divFacturas" >
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <td colspan="3">Colores de seguimiento</td>
                                            <td bgcolor="#75ff64" colspan="3">de 0 a 3 horas</td>
                                            <td bgcolor="#fff664" colspan="2">de 3 a 6 horas</td>
                                            <td bgcolor="#ffbcba" colspan="3">> 6 horas</td>                                                                                        
                                        </tr>

                                        <?php
                                        if ($_SESSION["departamento"] === 'GERENCIA' || $_SESSION["departamento"] === 'SISTEMAS') {
                                            echo '<tr style="background-color: #BEDAFF;" >';
                                        } else {
                                            echo '<tr>';
                                        }
                                        ?>                                        
                                    <th></th>
                                    <th>Servicio</th>
                                    <th>Fecha servicio</th>
                                    <th>Fecha entrega</th>
                                    <th>Placa</th>
                                    <th>Guias</th>                                                
                                    <th>Ultimo seguimiento</th>   
                                    <th>Cliente</th>
                                    <th>Origen</th>    
                                    <th>Destino</th>    
                                    <th></th>                                                                                     
                                    </tr>                                        
                                    </thead>
                                    <tbody>
                                        <?php
                                        $contadorDos = 0;
                                        /*
                                         * Aqui empieza la busqueda para el $serviciosPorSeguimientoDos
                                         */
                                        if ($cantidadDos > 0) {

                                            //var_dump($_SESSION);

                                            $date2 = new DateTime("now");

                                            for ($index1 = 0; $index1 < $cantidadDos; $index1++) {

                                                if (in_array($serviciosPorSeguimientoDos[$index1]["guias"], $arregloIdservicios) === false) {
                                                    $arregloIdservicios[$index1] = $serviciosPorSeguimientoDos[$index1]["idservicio"];

                                                    if ($_SESSION["departamento"] === 'GERENCIA' || $_SESSION["departamento"] === 'SISTEMAS') {
                                                        echo '<tr style="background-color: #BEEFFF;" >';
                                                    } else {
                                                        echo '<tr>';
                                                    }
                                                    ?>


                                                <td><?= $contadorDos += 1 ?></td>
                                                <td><?= $serviciosPorSeguimientoDos[$index1]["idservicio"] ?></td>
                                                <td><?= substr($serviciosPorSeguimientoDos[$index1]["fechaServicio"], 0, 10) ?></td>
                                                <td><?= $serviciosPorSeguimientoDos[$index1]["fechaHoraEntrega"] ?></td>
                                                <td><?= $serviciosPorSeguimientoDos[$index1]["placa"] ?></td>
                                                <td>
                                                    <table>
                <?php
                echo '<tr><td><strong>' . $serviciosPorSeguimientoDos[$index1]["guias"] . '</strong><td></tr>';
                ?>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table>
                <?php
                $fechaUltimoSeguimiento = $seguimiento->retornarFechaServicio($serviciosPorSeguimientoDos[$index1]["guias"]);
                $date1 = new DateTime($fechaUltimoSeguimiento);
                $diff = $date1->diff($date2);
                $horas = $diff->h;
                $dias = $diff->d;
                $texto = '';

                $openStrong = '<strong>';
                $closeStrong = '</strong>';

//                                                                echo '<tr>'
//                                                                . '<td>horas '.$horas.'</td><td>'.date("Y-m-d H:m:s").'</td>'
//                                                                . '</tr>';

                if ($dias >= 1) {
                    //color rojo para mas de un día sin hacer seguimiento desde el último que se hizo                                                                    
                    $color = "ffbcba";
                    $texto = ">1 día";
                } else {
                    if ($horas >= 0 && $horas <= 3) {
                        //color verde para entre 0 a 3 horas desde el ultimo seguimiento
                        $color = "75ff64";
                        $texto = "0 a 3 hrs ult. seg.";
                    } else if ($horas >= 3 && $horas <= 6) {
                        //amarillo para entre tres a seis horas desde el ultimo seguimiento
                        $color = "fff664";
                        $texto = "3 a 6 hrs ult. seg.";
                    } else {
                        //si son mas de seis horas vuelve el color rojo
                        //$color = "ff6964";
                        $color = "ffbcba";
                        $texto = "> 6 hrs ult. seg.";
                    }
                }

                if ($fechaUltimoSeguimiento === null) {
                    echo '<tr><td>Sin seguimiento<td></tr>';
                } else {
                    echo '<tr><td bgcolor="#' . $color . '">' . $openStrong . '' . $fechaUltimoSeguimiento . '' . $closeStrong . '<td></tr>';
                }
                ?>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table>
                <?php
                $cliente = $seguimiento->retornarClienteGuia($serviciosPorSeguimientoDos[$index1]["guias"], 1);

                if ($cliente === null) {
                    echo '<tr><td>-<td></tr>';
                } else {
                    echo '<tr><td>' . $cliente . '<td></tr>';
                }
                ?>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table>
                <?php
                $origen = $seguimiento->retornarOrigenServicio($serviciosPorSeguimientoDos[$index1]["guias"], 1);
                echo '<tr><td>' . $origen[0]["mun_nombre"] . '<td></tr>';
                ?>
                                                    </table>
                                                </td>
                                                <td>
                                                    <table>
                <?php
                $destino = $seguimiento->retornarDestinoGuia($serviciosPorSeguimientoDos[$index1]["guias"], 1);
                echo '<tr><td>' . $destino[0]["mun_nombre"] . '<td></tr>';
                ?>
                                                    </table>
                                                </td>
                                                <td><button id="idservicio1" name="idservicio1" value="<?= $serviciosPorSeguimientoDos[$index1]["idservicio"] ?>" class='btn btn-success btn-xs' onclick="redireccionar(this, 1);" >Seguimiento</button></td>                                                
                                                </tr>
                <?php
                if ($_SESSION["departamento"] === 'GERENCIA' || $_SESSION["departamento"] === 'SISTEMAS') {

                    $fechasSeguimientosEstados = $seguimiento->retornarSeguimientosPorGuia($serviciosPorSeguimientoDos[$index1]["guias"]);

                    if (count($fechasSeguimientosEstados) > 0) {
                        echo '<tr style="background-color: #DEF7FF;"><td colspan="11">Seguimientos guía <strong>' . $serviciosPorSeguimientoDos[$index1]["guias"] . '</strong></td></tr>';
                        for ($index = 0; $index < count($fechasSeguimientosEstados); $index++) {
                            echo '<tr><td colspan="5"></td>'
                            . '<td>' . ($index + 1) . '</td>';
                            echo '<td>' . $fechasSeguimientosEstados[$index]["fechaHora"] . '</td>';
                            echo '<td>' . $fechasSeguimientosEstados[$index]["estadoseguimiento"] . '</td>
                            <td></td>
                            <td></td>
                            <td></td>'
                            . '</tr>';
                        }
                        //echo '</tr>';
                    }
                }
            }
        }
    }
    ?>
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </div>                        
                    <div class="panel-body">      
                        <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                        
                        <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>               
                    </div>                    
                </div>
            </div>             
        </body>
        <script type="text/javascript">
            function redireccionar(valor, opcion) {
                if (opcion === 1) {
                    var idservicio = valor.value;
                    window.location.href = "../modulos/gestionarSeguimiento.php?idservicio=" + idservicio;
                }

                if (opcion === 2) {
                    window.location.href = "../modulos/gestionarSeguimiento.php?idservicio=" + $("#idservicio").val();
                }

                if (opcion === 3) {
                    location.reload();
                }
            }
        </script>
    </html>
    <?php
}
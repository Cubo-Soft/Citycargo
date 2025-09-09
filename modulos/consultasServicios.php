<?php
session_start();

include_once '../clases/rol_boton.php';
include_once '../clases/funcionesVarias.php';
//include_once '../clases/usuarios.php';


$listado = null;

$departamentos = array('SISTEMAS', 'GERENCIA');

if (is_null($_SESSION["rol_id"])) {
    finalizarSesion();
} else {

    if (@$_SESSION["departamento"] === 'SISTEMAS' || @$_SESSION["departamento"] === 'GERENCIA') {
        $listado = retornarLista(1);
    } else {
        $listado = retornarLista(2);
    }

    $rol_boton = new rol_boton();
    $serviciosFacturar = $rol_boton->retornarAnticiposPendientes(1, 0, null, null);
    $empleados = $rol_boton->retornarEmpleados();
    $placas = $rol_boton->retornarPlacas();
    $placa = retornarListaPlacas($placas, 0);
    $placa2 = retornarListaPlacas($placas, 1);

    $empleado = "<select id='empleado' name='empleado' class='form-control' >"
            . "<option value='0'>...</option>";
    for ($index = 0; $index < count($empleados); $index++) {
        $empleado .= "<option value='" . $empleados[$index]["cedula"] . "'>" . $empleados[$index]["nombre"] . "</option>";
    }
    $empleado .= "</select>";
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
            <title>Consultas</title>
            <link rel="icon" href="../imagenes/favicon.ico">

            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        

            <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>            
            <script src="../js/jquery.number.min.js" type="text/javascript"></script>
            <script src="../js/js_consultasServicios.js?n=<?= time() ?>" type="text/javascript"></script> 
            <script src="../js/js_funcionesVarias.js?n=<?= time() ?>" type="text/javascript"></script>                        
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <script src="../js/terceraMascara.js" type="text/javascript"></script>
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
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1>Consultas</h1>
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
                    <form method="post" action="#" name="formuarlio_index" >                        
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Realizar consultas por:</strong></h3>                                
                            </div>
                            <div class="panel-body">                                
                                <div class="col-lg-10">
                                    <?= $listado ?>
                                </div>
                                <div class="col-lg-2">
                                    
                                </div>
                            </div>
                            <div id="mensajes"></div>
                            <div id="DivConsultaPlacaServicios" class='alert alert-dismissible alert-success'>
                                <table>
                                    <tr>                                      
                                        <td>Fecha inicial</td>
                                        <td><input type="date" id="fechaInicialP" class="form form-control" /></td>
                                        <td>Fecha final</td>
                                        <td><input type="date" id="fechaFinalP" value="<?= date("Y-m-d") ?>" class="form form-control" /></td>                                        
                                        <td>Placas</td>
                                        <td><?= $placa2 ?></td>
                                        <td><input type="button" value="Consultar servicios" id="btnConsultarPorPlaca" /></td>
                                    </tr>
                                </table>                                
                            </div>
                            <div id="mensajes2"></div>
                        </div>
                        <div class="panel-body">                        
                            <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                            <button name="boton" id="botonGenerarExcel" type="button" class="btn btn-success btn-ls botonPropio" value="GENERAREXCEL" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GENERAR EXCEL <img src="../imagenes/excel.ico"></button>
                            <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>
                            <button name="boton" id="botonNuevaConsulta" type="button" value="Mostrar opciones de consulta" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >Mostrar opciones de consulta <img src="../imagenes/bubble_16.png"> </button>
                        </div>
                    </form>  
                </div>
            </div> 
            <div style="display:none" id="divPlacas">
                <?= $placa; ?>
            </div>            
        </body>
    </html>
    <?php
}

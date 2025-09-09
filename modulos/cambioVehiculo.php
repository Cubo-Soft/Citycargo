<?php
session_start();

include_once '../clases/rol_boton.php';
include_once '../clases/funcionesVarias.php';

$departamentos = array('SISTEMAS', 'GERENCIA');

if (is_null($_SESSION["rol_id"])) {
    finalizarSesion();
} else if (in_array($_SESSION["departamento"], $departamentos) === false) {
    finalizarSesion();
} else {
    $rol_boton = new rol_boton();
    $conductores = $rol_boton->retornarPropietarios();
    $propietarios = '<select name="idConductor" id="idConductor" class="form-control col-lg-8">'
            . '<option value="0">...</option>';
    for ($index = 0; $index < count($conductores); $index++) {
        $propietarios .= "<option value=" . $conductores[$index]["cond_identificacion"] . ">" . $conductores[$index]["cond_nombres"] . " " . $conductores[$index]["cond_apellidos"] . "</option>";
    }
    $propietarios .= "</select>";

    $placas = $rol_boton->retornarPlacas();
    $placa = '<select name="placas" id="placas" class="form-control">'
            . '<option value="0">...</option>';
    for ($index = 0; $index < count($placas); $index++) {
        $placa .= "<option value='" . $placas[$index]["cond_identificacion"] . "'>" . $placas[$index]["placa"] . "</option>";
    }
    $placa .= "</select>";
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
            <title>Cambio de veh&iacute;culo</title>
            <link rel="icon" href="../imagenes/favicon.ico">
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
            <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>
            <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">            
            <script src="../js/js_cambioVehiculo.js?n=<?= rand(0,3)?>" type="text/javascript"></script>               
            <script src="../js/bootstrap.min.js" type="text/javascript"></script>  
            <script src="../js/jquery.number.min.js" type="text/javascript"></script>
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>            
            <div id="contenedor" class="container">
                <div id="contenedor-index">   
                    <div id="contenedor-index" class="row">                       
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1>Cambio de veh&iacute;culo</h1>
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
                                <h3 class="panel-title"><strong>Reasignar servicio</strong></h3>
                            </div>
                            <div class="panel-body">
                                <table class="table table-hover">
                                    <tr>
                                        <td>Servicio</td>
                                        <td><input type="text" value="<?= $_GET["idserv"]; ?>" class="form-control input-sm" disabled="disabled" />
                                            <input type="hidden" name="idservicio" id="idservicio" value="<?= $_GET["idserv"] ?>" /></td>
                                    </tr>
                                    <tr>
                                        <td>Lista de placas</td>
                                        <td><?= $placa; ?></td>                                        
                                    </tr>
                                    <tr>
                                        <td>Propietario</td>                                        
                                        <td><?= $propietarios; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Identificaci&oacute;n</td>
                                        <td><input type="number" id="identificacion" name="identificacion" disabled="disabled" class="form-control" />
                                            <input type="hidden" name="cedulaPropietario" id="cedulaPropietario" value=""  /></td>
                                    </tr>
                                    <tr>
                                        <td><button name="reasignarServicio" id="reasignarServicio" type="button" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > REASIGNAR <img src="../imagenes/save_16.png"></button></td>
                                        <td></td>
                                    </tr>
                                </table>                                 
                            </div>
                            <div id="mensajes">
                                <div class="alert alert-dismissible alert-success">Por favor seleccione una placa de la <strong>Lista de placas</strong> para hacer la reasignaci&oacute;n</div>
                            </div>
                        </div>
                        <div class="panel-body">                        
                            <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                            <!--<button onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" id="botonConsultar" name="boton" type="submit" class="btn btn-success btn-ls botonPropio" value="CONSULTAR">CONSULTAR <img src="../imagenes/clipboard_16.png"> </button>-->
                            <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>               
                        </div>
                    </form>  
                </div>
            </div>             
        </body>
    </html>
    <?php
}
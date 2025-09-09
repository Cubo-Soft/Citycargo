<?php
session_start();

include '../clases/usuarios.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    $dia = date("d");
    
    $empleado=new usuarios();
    $empleados=$empleado->retornarNombresEmpleados();     
    
    $mostrarEmpleados = "<select id='mostrarEmpleados' name='mostrarEmpleados' class='form-control'>";
    $mostrarEmpleados.="<option value='0' selected>...</option>";
    for ($i = 0; $i < count($empleados); $i++) {           
        $mostrarEmpleados .= "<option value=" . $empleados[$i]["emp_cedula"] . ">" . $empleados[$i]["emp_nombres"] . " ".$empleados[$i]["emp_apellidos"]."</option>";        
    }
    $mostrarEmpleados .= "</select>";
    
    $conductores=$empleado->retornarConductoresPropietarios();
    
    $mostrarConductores="<select id='mostrarConductores' name='mostrarConductores' class='form-control'>";
    $mostrarConductores.="<option value='0' selected>...</option>";
    for($i=0;$i<count($conductores);$i++){
        $mostrarConductores.="<option value=" . $conductores[$i]["cond_identificacion"] . ">" . $conductores[$i]["cond_nombres"] . " ".$conductores[$i]["cond_apellidos"]."</option>";
    }
    $mostrarConductores.="</select>";
    
    $empresas=$empleado->retornarEmpresas();
    
    $mostrarEmpresas="<select id='listaEmpresas' name='listaEmpresas' class='form-control'>";
    $mostrarEmpresas.="<option value='0' selected>...</option>";
    for($i=0;$i<count($empresas);$i++){
        $mostrarEmpresas.="<option value=" . $empresas[$i]["cli_documento"] . ">" . $empresas[$i]["cli_nombre"] . "</option>";
    }
    $mostrarEmpresas.="</select>";
    
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
            <title>Procesos gu&iacute;as</title>
            <link rel="icon" href="../imagenes/favicon.ico">
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../js/js_procesosGuias.js?n=<?= rand(0,10)?>" type="text/javascript"></script>      
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>
            <div>   
                 <div id="contenedor-index" class="row">                       
                    <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGI" id="imagen_usapostal_cotizacion"/></div>
                    <div id="texoDocumento" class="col-lg-4">
                        <h1>Procesos gu&iacute;as</h1>
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
                <form action="#" method="POST" >                    
                    <div class="panel panel-success">                          
                        <div class="panel-heading ">                                        
                            <h3 class="panel-title">Secci&oacute;n para ingreso de gu&iacute;as</h3>
                        </div>                                                
                        <table class="table table-striped">
                            <tr>
                                <td>Empresas</td>
                                <td><?= $mostrarEmpresas; ?></td>
                                <td>Fecha inicial</td>
                                <td><input type="date" name="fechaInicial" id="fechaInicial" class="form-control" /></td>                                
                                <td>Fecha final</td>
                                <td><input type="date" value="<?= date("Y-m-d"); ?>" name="fechaFinal" id="fechaFinal" class="form-control" /></td>                                
                                <td>Mostrar</td>
                                <td>
                                    <select class="form-control" id="ordenarPor" name="ordenarPor">
                                        <option value="0">...</option>
                                        <option value="1">Detallado</option>
                                        <option value="2">Total</option>
                                    </select>                                    
                                </td>  
                                <td>
                                    Estado
                                </td>
                                <td>
                                    <select class="form-control" id="condicion" name="condicion">
                                        <option value="0">...</option>
                                        <option value="1">Usadas</option>
                                        <option value="2">No usadas</option>
                                        <option value="3">Todas</option>
                                    </select>                                                                        
                                </td>                                
                            </tr>                           
                            <tr>
                                <td>N&uacute;mero inicial</td>
                                <td><input type="number" name="numeroInicial" id="numeroInicial" class="form-control" /></td>
                                <td>Cantidad</td>
                                <td><input type="number" name="cantidad" id="cantidad" class="form-control" /></td>
                                <td>N&uacute;mero final</td>
                                <td>                                        
                                    <input type="number" name="numeroFinal" id="numeroFinal" class="form-control" />
                                </td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                            </tr>                            
                        </table>                        
                    </div>                    
                    <div>
                        
                    </div>
                    <div id="mostrarTrasabilidad"></div>
                    <div id="mensajes"></div>                    
                    <div id="botones">                                                        
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" id="botonRegresar" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="INGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" name="ingresarGuias" id="ingresarGuias" >INGRESAR <img src="../imagenes/save_16.png"></button>                            
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="CONSULTAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" name="consultar" id="consultar">CONSULTAR <img src="../imagenes/warning_16.png"></button>                                                                                                
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" name="boton" id="botonSalir" >SALIR <img src="../imagenes/salir.png"> </button>
                    </div>
                </form>                    
            </div>                                
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>                                
            <script src="../js/bootstrap.min.js" type="text/javascript"></script>            
        </body>
    </html>
    <?php
    if (@$_GET["ms"] == 1) {
        echo '<script>alert("Se han creado las guías para el documento: ' . @$_GET["pj"] . '");</script>';
    }
}

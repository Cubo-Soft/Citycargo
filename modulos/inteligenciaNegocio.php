<?php
session_start();

include_once '../clases/funcionesVarias.php';

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
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <title>Inteligencia Negocio</title>
            <link rel="icon" href="../imagenes/camion256.png">
            
             <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>   
            
            <script src="../js/js_inteligenciaNegocio.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>  
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <div>   
            <div id="contenedor-index" class="row">                       
                <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                <div id="texoDocumento" class="col-lg-4">
                    <h1>Inteligencia de Negocio</h1><br>                    
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
                        <h3 class="panel-title"><strong>Visualizaci&oacute;n de gr&aacute;ficos </strong></h3>
                    </div>
                    <div class="panel-body">
                        <div id="grafico" class="col-lg-12 panel panel-success">
                            <img style="margin-left:3em" id="grafico" src="../trafico/graficos/ventasUltimosSeisMeses.php" alt="Grafico de ventas de los ultimos seis meses" border="0"/>
                        </div>
                    </div>
                    <div class="panel-body">                        
                        <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                            
                        <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>               
                    </div>
                </div>
            </form>
        </div>        
    </body>
    </html>
    <?php
}
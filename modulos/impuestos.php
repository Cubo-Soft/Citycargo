<?php
session_start();

include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
    include_once '../clases/impuestos.php';
    $impuestos = new impuestos();
    $valoresImpuestos = $impuestos->retornarImpuestos();
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
            <title>Impuestos</title>
            <link rel="icon" href="../imagenes/camion256.png">            
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../js/js_impuestos.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>            
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/digitoVerificacion.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>            
            <div id="contenedor">
                <div id="contenedor-index" class="row">                       
                    <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                    <div id="texoDocumento" class="col-lg-4">
                        <h1>Gesti&oacute;n de impuestos</h1>
                    </div>                                        
                    <div id="divDatosIniciales" class="col-lg-4">                           
                        <ul class="list-group">
                            <li class="list-group-item">
                                <span class="badge"><?php echo $_SESSION["nombre_usuario"]; ?></span>
                                Usuario
                            </li>
                            <li class="list-group-item">
                                <span class="badge" id="departamento"><?php echo $_SESSION["departamento"]; ?></span>
                                Departamento
                            </li>                                        
                            <li class="list-group-item">
                                <span class="badge"><?php echo date('Y-m-d'); ?></span>
                                Fecha
                            </li>
                        </ul>
                    </div>
                </div>                    
                <div id="datosCliente" class="col-lg-12">
                    <table class="table table-hover">
                        <thead>
                            <tr>                                
                                <th scope="col"></th>
                                <th scope="col">Nombre</th>
                                <th scope="col">Año</th>
                                <th scope="col">Valor</th>
                                <th scope="col">Base</th>
                            </tr>
                        </thead>
                        <tbody>                            
                            <?php
                            for ($index = 0; $index < count($valoresImpuestos); $index++) {
                                ?>                            
                                <tr class="table-active">                                
                                    <td><?= $index + 1; ?></td>
                                    <td><?= $valoresImpuestos[$index]["imp_nombre"]; ?></td>
                                    <td><input type="number" id="anio-<?= $valoresImpuestos[$index]["valimp_id"]; ?>" value="<?= $valoresImpuestos[$index]["valimp_ano"]; ?>" class="form-control form-control-sm" onblur="cambiarAnio(this)"/></td>
                                    <td><input type="text" id="valor-<?= $valoresImpuestos[$index]["valimp_id"]; ?>" value="<?= $valoresImpuestos[$index]["valimp_valor"]; ?>" class="form-control form-control-sm" onblur="cambiarValor(this)" /></td>
                                    <?php
                                    if ($valoresImpuestos[$index]["base"] === '0') {
                                        ?>
                                        <td></td>
                                        <?php
                                    } else {
                                        ?>
                                        <td><input type="text" id="base-<?= $valoresImpuestos[$index]["valimp_id"]; ?>" value="<?= $valoresImpuestos[$index]["base"]; ?>" class="form-control form-control-sm" onblur="cambiarBase(this)" /></td>
                                        <?php
                                    }
                                    ?>
                                </tr>                            
                                <?php
                            }
                            ?>                            
                        </tbody>
                    </table>
                </div>
                <div id="mensajes" class="col-lg-12" ><div class="alert alert-dismissible alert-warning">Para realizar un cambio en cualquiera de los valores, solo de click en la casilla a cambiar; luego digite el valor y finalmente de click por fuera de la casilla.</div></div>
                <div id="botones" class="col-lg-12" >   
                    <form action="../trafico/redirigir.php" method="POST">
                        <button type="submit" class="btn btn-success btn-ls botonPropio" value="REGRESAR" name="boton" id="botonRegresar" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                        
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" name="botonSalir" id="botonSalir" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > SALIR <img src="../imagenes/salir.png"></button>                            
                    </form>                    
                </div>
            </div>                             
        </body>
    </html>
    <?php
}
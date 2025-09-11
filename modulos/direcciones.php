<?php
session_start();
$identificacion = null;
include_once '../clases/cliente.php';
include_once '../clases/funcionesVarias.php';
if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
    $cliente = new cliente();
    $clientes = $cliente->retornarClientes();
    $listaClientes = "<select class='form-control' name='listaClientes' id='listaClientes' >"
            . "<option value='0'>...</option>";
    for ($index = 0; $index < count($clientes); $index++) {
        $listaClientes.="<option value='" . $clientes[$index]["cli_documento"] . "'>" . $clientes[$index]["cli_nombre"] . "</option>";
    }
    $listaClientes.="</select>";
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Direcciones</title>
            <link rel="icon" href="../imagenes/camion256.png">        
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../js/js_direcciones.js?n=<?= rand(0,3)?>" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <script src="../js/bootstrap.min.js" type="text/javascript"></script>      
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>
            <form action="#" method="POST" id="formularioConductor">
                <div id="contenedor">
                    <div id="contenedor-index" class="row">                       
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1>Gesti&oacute;n direcciones</h1>
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
                    <div id="datosCliente" class="col-lg-12">
                        <fieldset>    
                            <legend></legend>
                            <div class="form-group col-lg-3">
                                <label for="lblnitEmpresa" class="col-lg-3 control-label">CLIENTE</label>
                                <div class="col-lg-6">                                                                        
                                    <?= $listaClientes ?>
                                </div>    
                                <div id="mostrarNit" class="col-lg-3">
                                </div>
                            </div>
                            <div class="form-group col-lg-3" >
                                <label for="lblnitEmpresa" class="col-lg-3 control-label">TIPO</label>
                                <div class="col-lg-9">                                                                        
                                    <select class="form-control" id="tipoDireccion" name="tipoDireccion" >
                                        <option value="3">...</option>
                                        <option value="0">ORIGEN</option>
                                        <option value="1">DESTINO</option>
                                    </select>
                                </div>                                
                            </div>
                            <div class="form-group col-lg-3" >
                                <label for="lblnitEmpresa" class="col-lg-3 control-label">ESTADO</label>
                                <div class="col-lg-9">                                                                        
                                    <select class="form-control" id="estadoDireccion" name="estadoDireccion" >
                                        <option value="3">...</option>
                                        <option value="1">ACTIVAS</option>
                                        <option value="0">INACTIVAS</option>
                                    </select>
                                </div>                                
                            </div>
                            <div class="form-group col-lg-3">
                                <input type="button" value="Buscar" id="buscarDirecciones" name="buscarDirecciones" class="btn btn-success btn-ls" />
                            </div>
                        </fieldset>
                    </div>
                    <div id="mostrarDirecciones" class="col-lg-12"></div>
                    <div id="mensajes" class="col-lg-12" ></div>
                    <div id="botones" class="col-lg-12" >   
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" name="boton" id="botonRegresar" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                        
                        <button type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" name="botonSalir" id="botonSalir" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > SALIR <img src="../imagenes/salir.png"></button>          
                    </div>
                </div>   
            </form>                   
        </body>
    </html>
    <?php
}
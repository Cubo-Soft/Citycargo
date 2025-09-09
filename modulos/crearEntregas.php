<?php
session_start();

include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    $borrado = '';

    if ($_SESSION["rol_id"] === '6' || $_SESSION["rol_id"] === '2') {
        $borrado = "<input type='hidden' id='borrado' value='1' />";
    } else {
        $borrado = "<input type='hidden' id='borrado' value='0' />";
    }

    include_once '../clases/municipios.php';
    $municipio = new municipios();
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Entregas</title>
            <link rel="icon" href="../imagenes/favicon.ico">            
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../js/js_crearEntregas.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>            
            <script src="../js/js_comunes.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>  
            <script src="../js/terceraMascara.js" type="text/javascript"></script>            
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>            
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>
            <form action="#" method="POST">                
                <div id="contenedor">
                    <div id="contenedor-index" class="row">                       
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1>Entregas</h1>
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
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Consultar por:</strong></h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-lg-4">
                                    <div class="col-lg-3">
                                        Gu&iacute;a a consultar
                                    </div>
                                    <div class="col-lg-9">
                                        <input type="number" name="guia" id="guia" value="0" class="form-control" />
                                    </div>                                     
                                </div>
                                <div class="col-lg-4">
                                    <div class="col-lg-3">
                                        Servicio
                                    </div>
                                    <div class="col-lg-9">
                                        <?= $borrado ?>                                    
                                        <input type="hidden" name="idservicio" id="idservicio" />
                                        <input type="number" name="idservicio1" id="idservicio1" class="form-control" disabled="disabled" />
                                        <input type="hidden" id="valorGuia" value="" /> 
                                        <input type="hidden" id="diferencia" value="" />   
                                        <input type="hidden" id="iddireccionorigen" value=""/>
                                        <input type="hidden" id="nitEmpresa" value=""/> 
                                        <input type="hidden" id="numeroFactura" value=""/>
                                    </div>
                                </div>
                                <div class="col-lg-4" id="divEntregas" >
                                    <div class="col-lg-3">
                                        Cantidad de entregas
                                    </div>
                                    <div class="col-lg-9">
                                        <input type="number" name="cantidadEntregas" id="cantidadEntregas" value="0" class="form-control" />
                                    </div>    
                                </div>                                                               
                            </div>
                            <div class="panel-body">
                                <div class="col-lg-4" id="datosGuias"></div>
                                <div class="col-lg-8" id="entregasAnteriores"></div>
                            </div>                            
                            <div id="datosIntroducidos" class="panel-body"></div>
                            <div id="mensajes" class="panel-body"></div>
                            <div id="datosFormulario" class="panel-body">
                                <table class="table table-hover" >
                                    <tr>
                                        <td>Gu&iacute;a</td>
                                        <td>Unidades</td>
                                        <td>Planilla</td>
                                        <td>Remisi&oacute;n</td>
                                        <td>Factura de entrega de cliente</td>
                                        <td>Orden de compra</td>
                                        <td>Ciudad</td>
                                        <td>Direcciones destino</td>                                        
                                        <td>Valor</td>
                                        <td>Notas gu&iacute;a</td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td><input type="number" id="guiaEntrega" name="guiaEntrega" class="form-control" value="0" /></td>
                                        <td><input type="number" id="unidades" name="unidades" class="form-control" value="0"/></td>
                                        <td><input type="text" id="planilla" name="planilla" class="form-control" value="0" /></td>
                                        <td><input type="text" id="remision" name="remision" class="form-control" value="0" /></td>
                                        <td><input type="text" id="factura" name="factura" class="form-control" value="0" /></td>
                                        <td><input type="text" id="ordenCompra" name="ordenCompra" class="form-control" value="0" /></td>
                                        <td><?= $municipio->retornarMunicipios(); ?></td>
                                        <td><div id="divDirDes"></div></td>
                                        <td><div id="divValor"></div></td>
                                        <td><textarea id="notas" name="notas" class="form-control" rows="3" ></textarea></td>
                                        <td><input type="button" id="crearEntrega" class="form-control" value="Crear entrega"/></td>
                                    </tr>
                                </table>                                
                            </div>                           
                        </div>
                        <div id="botones">
                            <button type="button" value="REGRESAR" id="botonRegresar" name="botonRegresar" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                            <button type="button" value="GRABAR ENTREGAS" id="botonGrabarEntregas" name="boton" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GRABAR ENTREGAS <img src="../imagenes/camion_16.png"></button>
                            <button type="button" value="BORRAR" id="botonBorrar" name="boton" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > BORRAR FORMULARIO </button>
                            <button type="button" value="SALIR" id="botonSalir" name="botonSalir" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>            
                        </div>
                    </div>
                </div>
            </form>
        </body>
    </html>
    <?php
}
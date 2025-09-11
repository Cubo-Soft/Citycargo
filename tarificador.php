<?php

function listaCalles($nombreLista) {
    return "<select name='" . $nombreLista . "' id='" . $nombreLista . "' class='form-control input-sm' >"
            . "<option value='0' default >-</option>"
            . "<option value='1'>S</option>"
            . "</select>";
}

function listaCarreras($nombreLista) {
    return "<select name='" . $nombreLista . "' id='" . $nombreLista . "' class='form-control input-sm' >"
            . "<option value='0' default >-</option>"
            . "<option value='1'>E</option>"
            . "</select>";
}
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Tarificador</title>
        <link rel="icon" href="./imagenes/camion256.png">
        <link href="./css/css2.css" rel="stylesheet" type="text/css"/>        
        <link href="./css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
        <script src="./js/jquery-1.11.2.js" type="text/javascript"></script>
        <link href="./bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">         
        <script src="./js/js_tarificadorBD.js" type="text/javascript"></script>        
        <script src="./js/jquery.number.js" type="text/javascript"></script>
        <script src="./js/jquery.number.min.js" type="text/javascript"></script>
        <script src="./js/bootstrap.min.js" type="text/javascript"></script>
    </head>
    <body class="container">
        <div id="contenedor-index" class="row">                       
            <div id="divImagenUsa" class="col-lg-6"><img src="./imagenes/logocity.jpg" alt="logocity" id="imagen_usapostal_cotizacion"/></div>
            <div id="texoDocumento" class="col-lg-6">
                <h1>TARIFICADOR</h1>        
            </div>                                                                
        </div>  
        <div class="col-lg-5 alert alert-dismissible alert-success">
            <form method="POST" id="formularioTarifas" action="trafico/calcularTarifa.php">
                <table class="table table-responsive">
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td colspan="4"><label>Coordenadas origen</label></td> 
                                    <td></td>
                                    <td></td>
                                    <td><label>Barrio</label></td>
                                </tr>
                                <tr>
                                    <td><label><span>CL</span></label></td>
                                    <td>
                                        <input type="text" name="CLOR" id="CLOR" value="0" size="3" class="form-control input-sm" />                                           
                                    </td>
                                    <td>
                                        <?= listaCalles('CLORS') ?>
                                    </td>
                                    <td>
                                        <label><span>CR</span></label>
                                    </td>
                                    <td>
                                        <input type="text" name="CROR" id="CROR" value="0" size="3" class="form-control input-sm" />
                                    </td>
                                    <td>
                                        <input type="hidden" id="origen" name="origen" value="1"/>
                                        <?= listaCarreras('CRORE') ?>
                                    </td>
                                    <td>
                                        <div id="divBarrioOrigen"></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table>
                                <tr>
                                    <td colspan="4"><label>Coordenadas destino</label></td>
                                </tr>
                                <tr>
                                    <td>
                                        <label><span>CL</span></label>
                                    </td>
                                    <td>
                                        <input type="text" name="CLDE" id="CLDE" value="0" size="3" class="form-control input-sm" />
                                    </td>
                                    <td>
                                        <?= listaCalles('CLDES') ?> 
                                    </td>                                            
                                    <td>
                                        <label><span>CR</span></label>
                                    </td>
                                    <td>
                                        <input type="text" name="CRDE" id="CRDE" value="0" size="3" class="form-control input-sm" />
                                    </td>
                                    <td>
                                        <input type="hidden" id="destino" name="destino" value="1"/>
                                        <?= listaCarreras('CRDEE') ?>  
                                    </td>
                                    <td>
                                        <div id="divBarrioDestino"></div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>                                                            
                </table>                    
            </form>
        </div>
        <div id="divTarifa" class="col-lg-3 alert-dismissible alert-success">            
        </div>        
        <div id="divPrecios" class="col-lg-4 alert-dismissible alert-success">
            <div id="conservarDirecciones">
                <div id="coordenadasOrigen"></div>
                <div id="coordenadasDestino"></div>
            </div>        
            <div id="tarifasAdicionales" class="col-lg-4 alert-dismissible alert-success"></div> 
        </div>
        <div id="mensajes"></div>
        <div id="botones" class="col-lg-12">
            <button type="button" name="botonCalcularTarifa" id="botonCalcularTarifa" class="btn btn-success btn-ls botonPropio" > <img src="./imagenes/tick_16.png" />  Calcular</button>                    
            <button type="button" name="botonBorrar" id="botonBorrar" class="btn btn-success btn-ls botonPropio" > <img src="./imagenes/trash_16.png" />  Borrar</button>                    
        </div>                


    </body>
</html>



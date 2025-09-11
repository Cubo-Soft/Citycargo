<?php
session_start();

include_once '../clases/servicios.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    $servicios = new servicios();
    $placas = $servicios->retornarPlacas();
    $clientes = $servicios->retornarClientes();
    $empleados = $servicios->retornarEmpleados($_SESSION["emp_cedula"]);

    /*
     * onchange='cambiarEmpleadoServicio(this," . $numeroServicio . ");'
     */

    $listaEmpleados = "<select class='form-control form-control-sm' id='listaAsesores' name='listaAsesores' onchange='cambiarAsesorEmpresa(this);'>";
    $listaEmpleados .= "<option value='0'>...</option>";
    for ($index = 0; $index < count($empleados); $index++) {
        if ($_SESSION["emp_cedula"] === $empleados[$index]["cedula"]) {
            $listaEmpleados .= "<option value='" . $empleados[$index]["cedula"] . "' selected>" . $empleados[$index]["nombre"] . "</option>";
        } else {
            $listaEmpleados .= "<option value='" . $empleados[$index]["cedula"] . "'>" . $empleados[$index]["nombre"] . "</option>";
        }
    }
    $listaEmpleados .= "</select>";
}
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
        <title>Crear servicio</title>
        <link rel="icon" href="../imagenes/camion256.png">

        <link href="../css/css2.css" rel="stylesheet" type="text/css" />
        <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
        <?= retornarRecursosBootstrap(); ?>
        <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        

        <script src="../js/js_serviciosVariosTres.js?b=<?= time() ?>" type="text/javascript"></script>  
        <script src="../js/js_comunes.js?b=<?= time() ?>" type="text/javascript"></script>  
        <script src="../js/numeros_letras.js" type="text/javascript"></script>        
        <script src="../js/cambioColores.js" type="text/javascript"></script>
        <script src="../js/jquery.number.js" type="text/javascript"></script>        
        <style>

            .oculto{
                display: none;
            }

            .selected{
                cursor: pointer;
            }
            .selected:hover{
                background-color: #0585C0;
                color: white;
            }
            #nombreEmpresa{
                text-transform:uppercase;
            }

        </style>
        <script src="../js/accionesenprograma.js" type="text/javascript"></script>
        <!-- Evitar cache -->
        <meta http-equiv="Expires" content="0">
        <meta http-equiv="Last-Modified" content="0">
        <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
        <meta http-equiv="Pragma" content="no-cache">
    </head>
    <body>
        <form action="#" method="post" id="formServicios" name="formServicios" />
        <div id="datosCliente" class="panel panel-success small paddinMargin">
            <div class="panel-heading">                
                <h3 class="panel-title">Crear servicio</h3>                
                <input type="hidden" id="emp_cedula" name="emp_cedula" value="<?= $_SESSION["emp_cedula"]; ?>" />
                <input type="hidden" id="nombreEmpleado" name="nombreEmpleado" value="<?= $_SESSION["nombre_usuario"]; ?>" />
            </div>
            <div class="table table-responsive">
                <table class="table table-striped">                    
                    <tr>
                        <td>Placa</td>
                        <td><div id="divListaPlacas"><?= $placas; ?></div></td>
                        <td>C&eacute;dula del propietario</td>
                        <td><input type="number" name="cedulaPropietario" id="cedulaPropietario" class="form-control input-sm" title="C&eacute;dula del propietario" placeholder="0000000000" onblur="verificarCedulaPropietario(this)" readonly="readonly" /></td>
                        <td>Nombres del propietario</td>
                        <td><input type="text" name="nombresPropietario" id="nombresPropietario" class="form-control input-sm" title="Nombres y apellidos del propietario" placeholder="NOMBRES APELLIDOS" /></td>
                    </tr>                    
                    <tr>
                        <td>Fecha del servicio</td>
                        <td><input type="date" name="fechaServicio" id="fechaServicio" class="form-control" value="<?= date("Y-m-d"); ?>" required="required"/></td>
                        <td>C&eacute;dula del conductor</td>
                        <td><div id="divCedulasConductores"><input type="number" name="cedulaConductor" id="cedulaConductor" class="form-control input-sm" title="C&eacute;dula del conductor" placeholder="0000000000" onblur="verificarCedulaConductor(this)" readonly="readonly" /></div></td>
                        <td>Nombres del conductor</td>
                        <td><input type="text" name="nombresConductor" id="nombresConductor" class="form-control input-sm" title="Nombres y apelliso del conductor" placeholder="NOMBRES APELLIDOS" /></td>
                    </tr>
                    <tr id="trMensajesPorcentajes"> 
                        <td colspan="2"><div id="tdMsjPlaca"></div><input type="hidden" id="noSatPlaca" value="0"/></td>
                        <td colspan="2"><div id="tdMsjPropietario"></div><input type="hidden" id="noSatPropietario" value="0" /></td>
                        <td colspan="2"><div id="tdMsjConductor"></div><input type="hidden" id="noSatConductor" value="0" /></td>
                    </tr>
                    <tr id="trPlacasPropietario">
                        <td colspan="6"><div id="tdMsjPlacas" ></div></td>                            
                    </tr>   
                    <tr>
                        <td>Nombre del cliente</td>
                        <td><div id="divListaClientes"><?= $clientes; ?></div></td>                            
                        <td>NIT</td>
                        <td><input type="number" id="nitEmpresa" name="nitEmpresa" placeholder="NIT de la empresa" 
                                   title="Al crear una empresa nueva, por favor digitar el NIT sin digito de verificaci&oacute;n" 
                                   class="form-control input-sm" />
                        </td>
                        <td>
                            Asesor
                        </td>
                        <td>
                            <?= $listaEmpleados; ?>
                        </td>
                    </tr>
                    <tr id="trEmpresaAsesor">
                        <td colspan="6"><div id="tdMsjEmpresa" ></div></td>                            
                    </tr>
                    <tr>                        
                        <td>Gu&iacute;a</td>
                        <td>
                            <table>
                                <tr>
                                    <td>
                                        <input type="number" placeholder="12345678" title="Guía de la empresa asignada al servicio" 
                                       required="required" minlength="9" maxlength="15" name="guia" 
                                       id="guia" class="form-control input-sm" onfocusout="buscarGuia(this);" value="0"  />
                                <input type="hidden" id="guiasRepetidas" name="guiasRepetidas" value="0" />
                                    </td>
                                    <td>
                                        <input type="button" value="Crear gu&iacute;a" id="crearGuia" name="crearGuia" class="btn btn-default btn-sm" alt="Crea un n&uacute;mero aleatorio de gu&iacute;a" />
                                    </td>
                                    <td>
                                        Fecha de entrega
                                    </td>
                                    <td>
                                        <input type="datetime-local" id="fechaHoraEntrega" name="fechaHoraEntrega" class="form-control input-sm"  />
                                    </td>
                                </tr>
                            </table>
                            <div class="col-lg-8">
                                
                            </div>
                            <div class="col-lg-4" >
                                
                            </div>
                        </td>
                        <td>Unidades</td>
                        <td><input type="number" placeholder="00" title="Unidades" 
                                   name="unidades" id="unidades" class="form-control input-sm" value="0"/></td>
                        <td>Planilla</td>
                        <td><input type="text" placeholder="NN-12345678XX" title="Planilla asignada al servicio" 
                                   name="planilla" id="planilla" class="form-control input-sm" value="0"/></td>
                    </tr>
                    <tr>                        
                        <td>Remisi&oacute;n</td>
                        <td><input type="text" placeholder="BB-32144FF" title="Remsisión del cliente" name="remision" id="remision" class="form-control input-sm" value="0" /></td>
                        <td>Factura de entrega del cliente</td>
                        <td><input type="text" placeholder="A-00000BB" title="Factura cliente"  name="factura" id="factura" class="form-control input-sm" value="0"/></td>  
                        <td>Orden de compra</td>
                        <td><input type="text" placeholder="A-0000000" title="Orden de compra"  name="ordenCompra" id="ordenCompra" class="form-control input-sm"value="0"/></td>  
                    </tr>
                    <tr id="trGuiaPlanillaOtro">
                        <td colspan="3"><div id="tdMsjGuia" ></div></td>
                        <td colspan="3"><div id="tdMsjGuia2" ></div></td>
                    </tr>                                     
                    <tr>
                        <td>Direcci&oacute;n de origen</td>
                        <td><div id="divDirOrg"></div></td>
                        <td>Tel&eacute;fono de origen</td>
                        <td><input type="number" id="telefonoorigen" name="telefonoorigen" placeholder="Tel&eacute;fono de origen" 
                                   title="Tel&eacute;fono donde se inicia el servicio" required="required" 
                                   title="" class="form-control input-sm" value="0" />
                        </td>
                        <td>Ciudad de origen</td>
                        <td><div id="idCiudadOrigen"><?= $servicios->retornarMunicipios("idciudadorigen"); ?></div></td>
                    </tr>  
                    <tr id="trDirOrg">
                        <td colspan="3"><div id="tdMsjDirOrg" ></div></td>
                        <td colspan="3"><div id="tdMsjDirOrg2"></div></td>
                    </tr>
                    <tr>                        
                        <td>Direcci&oacute;n de destino</td>
                        <td><div id="divDirDes"></td>
                        <td>Tel&eacute;fono de destino</td>
                        <td><input type="number" id="telefonodestino" name="telefonodestino" placeholder="Tel&eacute;fono de destino"
                                   title="Tel&eacute;fono de destino" required="required" 
                                   class="form-control input-sm" value="0" /></td>
                        <td>Ciudad de destino</td>
                        <td><div id="idCiudadDestino"><?= $servicios->retornarMunicipios("idciudaddestino"); ?></div></td>
                    </tr>
                    <tr id="trDirDes">
                        <td colspan="3"><div id="tdMsjDirDes" ></div></td>
                        <td colspan="3"><div id="tdMsjDirDes2"></div></td>
                    </tr>                        
                    <tr>
                        <td>Valor declarado</td>
                        <td><input type="text" id="valorDeclarado" name="valorDeclarado" placeholder="Valor declarado" 
                                   title="Valor declarado de la mercancía" class="form-control input-sm" value="200000"  /></td>
                        <td>% de manejo</td>
                        <td><input type="number" id="porManejo" name="porManejo" placeholder="Valor manejo" 
                                   title="Valor manejo" class="form-control input-sm" value="0" step="0.0"  /></td>
                        <td></td>
                        <td><input type="text" id="totVal1" name="totVal1" class="form-control input-sm" value="0" disabled="disabled" />
                            <input type="hidden" id="valorManejo" name="valorManejo" class="form-control input-sm" value="0" />
                        </td>
                    </tr>   
                    <tr>  
                        <td>Valor a facturar</td>
                        <td><input type="text" id="valorEmpresa" name="valorEmpresa" class="form-control input-sm" value="0" /></td> 

                        <td>Valor del auxiliar</td>
                        <td><input type="text" id="valorAuxiliar" name="valorAuxiliar" placeholder="Valor auxiliar" 
                                   title="Valor auxiliar" class="form-control input-sm" value="0"  /></td>

                        <td>Valor otros</td>
                        <td>                            
                            <div>
                                <input type="hidden" id="valorOtros" name="valorOtros" class="form-control input-sm" value="0" />                                
                                <div id="divConceptos"></div>
                                <table class='table table-striped'>
                                    <tr><td><input type="text" title="Concepto" placeholder="Concepto" name="nombreConcepto" id="nombreConcepto" class="form-control input-sm" value=""></td><td><input type="number" title="000000" placeholder="000000" id="valorConcepto" name="valorConcepto" class="form-control input-sm" /></td><td><input type="button" value="Crear valor" id="sumarOtrosValores"/></td></tr>
                                    <tr><td>Total</td><td><input type="number" id="totalOtrosConceptos" value="0" class="form-control input-sm" disabled="disabled"/></td></tr>
                                </table>                                
                            </div>
                            <div id="mensajesOtrosValores"></div>
                        </td>
                    </tr>    
                    <tr>                        
                        <td>Valor contratista</td>
                        <td><input type="text" id="valorContratista" name="valorContratista" class="form-control input-sm" value="0" /></td>
                        <td>Valor total</td>
                        <td><input type="text" id="vlrTot2" name="vlrTot2" class="form-control input-sm" value="0" disabled="disabled" /></td>
                        <td>Notas Gu&iacute;a</td>
                        <td><textarea id="notas" name="notas" class="form-control" rows="3"></textarea></td>
                    </tr>                    
                    <tr>
                        <td><!--VALOR PARQUEADERO--></td>
                       <td><!--<input type="text" id="valorParqueadero" name="valorParqueadero" class="form-control input-sm" value="0" />--></td>                        
                        <td id="tdMsjNotGui"></td>

                        <td><input type="button" id="botonAgregarDiv" name="botonAgregarDiv" value="Crear entrega" onclick="crearPar();" class="btn btn-success"/></td>
                        <td colspan="2"><div id="porGanancia"></div></td>                        
                    </tr>                    
                </table>                     
            </div>
        </div>
        <div id="mostrarParadas"></div>
        <div id="valorAnticipo"></div>
        <div id="tablaValores"></div>
        <div id="mensajesGenerales"></div>
        <div class="col-md-12" >
            <table class="table table-hover" >
                <thead>                  
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name="chkAnticipo" id="chkAnticipo" title="Valor a facturar, solo n&uacute;meros" class="custom-control-input" />&nbsp;&nbsp;CON ANTICIPO</td>                        
                        <td></td>
                        <td></td>                        
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>                
            </table>
        </div>
        <div id="mensajes">

        </div>
        <div id="botones">
            <button type="button" value="REGRESAR" id="botonRegresar" name="botonRegresar" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
            <button type="button" value="GRABAR SERVICIO" id="botonCrearServicio" name="boton" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GRABAR SERVICIO <img src="../imagenes/camion_16.png"></button>
            <button type="button" value="SALIR" id="botonSalir" name="botonSalir" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>            
        </div>
    </div>
</form> 
</body>
</html>
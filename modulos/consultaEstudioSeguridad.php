<?php
session_start();

include_once '../clases/rol_boton.php';
include_once '../clases/funcionesVarias.php';
include_once '../clases/CL_tipovehiculo.php';
include_once '../clases/CL_eps.php';
include_once '../clases/CL_arl.php';
include_once '../clases/CL_documentosoporte.php';
include_once '../clases/CL_pension.php';
include_once '../clases/CL_estados_tablas.php';
include_once '../clases/CL_parentescos.php';
include_once '../clases/CL_dias_configuracion.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
}

$OB_tipovehiculo = new CL_tipovehiculo();
$OB_eps = new CL_eps();
$OB_arl = new CL_arl();
$OB_pension = new CL_pension();
$OB_estados_tablas = new CL_estados_tablas();
$OB_parentescos = new CL_parentescos();
$OB_documentosoporte = new CL_documentosoporte();
$OB_dias_configuracion = new CL_dias_configuracion();

$tipovehiculos = $OB_tipovehiculo->retornarTiposVehiculos(null, null);
$listaEps = $OB_eps->retornarEps(null, null);
$listaArl = $OB_arl->retornarArl(null, null);
$listaPension = $OB_pension->retornarPension(null, null);
$listaEstadosTablas = $OB_estados_tablas->retornarEstadosTablas(null, null);
$listaParentescos = $OB_parentescos->retornarParentescos(null, null);
$listaDocumentoSoporte = $OB_documentosoporte->retornarDocumentosoporte(null, null);
$listaEmpleados = $OB_tipovehiculo->retornarEmpleados();
$diasConfiguracion = $OB_dias_configuracion->retornarDiasConfiguracion(null, 2);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Thu, 01 Jan 1970 00:00:00 GMT");
?>

<!DOCTYPE html>

<head>
    <title>Consulta Estudio de seguridad</title>
    <link rel="icon" href="../imagenes/camion256.png">
    <link href="../css/css2.css" rel="stylesheet" type="text/css" />
    <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
    <script src="../js/js_estudio_seguridad.js?n=<?= rand() ?>" type="text/javascript"></script>
    <script src="../js/js_consultaEstudioSeguridad.js?n=<?= rand() ?>" type="text/javascript"></script>
    <script src="../js/js_funcionesVarias.js?n=<?= rand() ?>" type="text/javascript"></script>     
    <script src="../js/js_gestionarSeguimiento.js" type="text/javascript"></script>
    <script src="../js/cambioColores.js" type="text/javascript"></script>
    <?= retornarRecursosBootstrap(); ?>
    <script>
        function prepararParaImprimir() {
            // Selecciona todos los enlaces que contienen imágenes
            document.querySelectorAll('a').forEach(enlace => {
                const imagen = enlace.querySelector('img'); // Busca la imagen dentro del enlace
                if (imagen) { // Verifica si el enlace contiene una imagen
                    const texto = enlace.textContent.trim(); // Obtiene el texto del enlace
                    const nuevoContenedor = document.createElement('div'); // Crea un nuevo contenedor

                    // Clona la imagen y la agrega al nuevo contenedor
                    const clonImagen = imagen.cloneNode(true);
                    nuevoContenedor.appendChild(clonImagen);

                    // Si hay texto, lo agrega al nuevo contenedor
                    if (texto) {
                        const textoElemento = document.createElement('span');
                        textoElemento.textContent = texto;
                        nuevoContenedor.appendChild(textoElemento);
                    }

                    // Reemplaza el enlace con el nuevo contenedor
                    enlace.parentNode.replaceChild(nuevoContenedor, enlace);
                }
            });
        }

        window.onbeforeprint = prepararParaImprimir;
    </script>
</head>

<body>
    <div>
        <input type="hidden" id="id" value="0" />
        <input type="hidden" id="cedula" value="<?= $_SESSION["emp_cedula"] ?>" />
        <!-- d_c_id=dias_cantidad id -->
        <input type="hidden" id="d_c_id" value="<?= $diasConfiguracion[0]["id"] ?>" />
        <!-- d_c_id=dias_cantidad cantidad -->
        <input type="hidden" id="d_c_cant" value="<?= $diasConfiguracion[0]["cantidad"] ?>" />
        <table class="table table-borderless table-striped">
            <tr>
                <td colspan="4" style="text-align: center;"><img src="../imagenes/logo_solo_citycargo.png" alt="CITYCARGO" style="width:30em;" /></td>
            </tr>
            <tr>
                <td colspan="2"><strong>PLAN ESTRATEGICO DE SEGURIDAD VIAL</strong></td>
                <td colspan="2"><strong>CONSULTA FORMATO ESTUDIO DE SEGURIDAD VEHÍCULO</strong></td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>ESTUDIO DE SEGURIDAD CITYCARGO S.A.S</strong></td>
            </tr>
            <tr style="background-color: #e2efd9;" id="trArriba1">
                <td><strong>Placa</strong></td>                
                <td><strong>Modelo</strong></td>
                <td><strong>Tipo vehículo</strong></td>
                <td></td>
            </tr>
            <tr id="trArriba2">
                <td><input type="text" id="placa" name="placa" class="form-control form-control-sm" placeholder="ABC123" oninput="this.value = this.value.toUpperCase()" /></td>                
                <td><input type="number" id="modelo" name="modelo" class="form-control form-control-sm" placeholder="2024" onblur="cambiarDato(this)" /></td>
                <td>
                    <div id="divTipoVehiculo">
                        <?php
                        $nombresPosiciones = array("id" => "id", "nombre" => "nombre");
                        echo crearSelect($tipovehiculos, "id_tipovehiculo", $nombresPosiciones, null, false);
                        ?>
                    </div>
                </td>
                <td>
<!--<input type="button" class="btn btn-success" value="Crear estudio" id="crearEstudio" /> --></td>
            </tr>
            <tr style="background-color:#9dc3e6;" id="trMensaje1">
                <td colspan="4" id="tdTextoMensaje1"></td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>SATELITAL</strong></td>
            </tr>
            <tr style="background-color: #e2efd9;">
                <td><strong>Empresa / Operador satelital</strong></td>
                <td><strong>Usuario</strong></td>
                <td><strong>Clave</strong></td>
                <td></td>
            </tr>
            <tr>
                <td><input type="text" id="operador" name="operador" class="form form-control" /></td>
                <td><input type="text" id="usuario" name="usuario" class="form-control form-control-sm" placeholder="Usuario" /></td>
                <td><input type="text" id="clave" name="clave" class="form-control form-control-sm" placeholder="Clave" /></td>
                <td></td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>VEHÍCULO</strong></td>
            </tr>
            <tr>
                <td><strong>Licencia de transito</strong></td>
                <td><input type="text" id="licenciatransito" name="licenciatransito" class="form-control form-control-sm" placeholder="Licencia de transito" oninput="this.value = this.value.toUpperCase()" /></td>
                <td>

<?php
$arreglo["idDiv"] = 'LicenciaTransito1';
$arreglo["nombreCampo"] = 'rutalicenciatransito1';
$arreglo["texto"] = '1er imagen o archivo de licencia';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
                <td>

<?php
$arreglo["idDiv"] = 'LicenciaTransito2';
$arreglo["nombreCampo"] = 'rutalicenciatransito2';
$arreglo["texto"] = '2do imagen o archivo de licencia';
retornarDivsParaImagenes($arreglo,0);
?>

                </td>
            </tr>
            <tr>
                <td><strong>SOAT</strong></td>
                <td>Vencimiento</td>
                <td><input type="date" class="form-control form-control-sm" id="fechasoat" name="fechasoat" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutasoat';
$arreglo["nombreCampo"] = 'rutasoat';
$arreglo["texto"] = 'Imagen o archivo del SOAT';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Revisión tecnicomecanica</strong></td>
                <td>Vencimiento</td>
                <td><input type="date" class="form-control form-control-sm" id="revisiontecno" name="revisiontecno" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutarevtecno';
$arreglo["nombreCampo"] = 'rutarevtecno';
$arreglo["texto"] = 'Imagen o archivo de la revision tecnicomecanica';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Póliza de responsabilidad civil</strong></td>
                <td>Vencimiento</td>
                <td><input type="date" class="form-control form-control-sm" id="polizarespo" name="polizarespo" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutapolizarespo';
$arreglo["nombreCampo"] = 'rutapolizarespo';
$arreglo["texto"] = 'Imagen o archivo de la poliza de responsabilidad civil';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr style="background-color: #22f160">
                <td colspan="4" class="text-center align-middle"><strong>FOTOS VEHÍCULO</strong></td>
            </tr>
            <tr>
                <td>
                    <div>
                        <div id="divVehiculo1"></div>
                        <div id="divForm1">
                        </div>
                    </div>
                </td>
                <td>
                    <div>
                        <div id="divVehiculo2"></div>
                        <div id="divForm2">                        
                        </div>
                    </div>
                </td>
                <td>
                    <div>
                        <div id="divVehiculo3"></div>
                        <div id="divForm3">

                        </div>
                    </div>
                </td>
                <td>
                    <div>
                        <div id="divVehiculo4"></div>
                        <div id="divForm4">
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Consulta RNDC <br />(Ultimo manifiesto)</strong></td>
                <td>Fecha consulta</td>
                <td><input type="date" class="form-control form-control-sm" id="rndcvehiculo" name="rndcvehiculo" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutarndc';
$arreglo["nombreCampo"] = 'rutarndc';
$arreglo["texto"] = 'Imagen o archivo del RNDC';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Consulta Fasecolda</strong></td>
                <td>Fecha consulta</td>
                <td><input type="date" class="form-control form-control-sm" id="fasecoldavehiculo" name="fasecoldavehiculo" /></td>
                <td> <?php
                    $arreglo["idDiv"] = 'rutafasecolda';
                    $arreglo["nombreCampo"] = 'rutafasecolda';
                    $arreglo["texto"] = 'Imagen o archivo de Fasecolda';
                    retornarDivsParaImagenes($arreglo,0);
?></td>
            </tr>
            <tr style="background-color: #22f160">
                <td colspan="4" class="text-center align-middle"><strong>Verificación ultima empresa de cargue</strong></td>
            </tr>
            <tr>
                <td><strong>Empresa</strong></td>
                <td colspan="4"><input type="text" class="form-control form-control-sm" id="nomultempresacargue" name="nomultempresacargue" oninput="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>
                <td><strong>Contacto</strong></td>
                <td><input type="text" class="form-control form-control-sm" id="conultempresacargue" name="conultempresacargue" oninput="this.value = this.value.toUpperCase()" /></td>
                <td><strong>Fecha cargue</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecultempresacargue" name="fecultempresacargue" /></td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>CONDUCTOR</strong></td>
            </tr>
            <tr>
                <td><strong>Numero de cédula </strong></td>
                <td><input type="number" class="form-control form-control-sm" id="cedulaconductor" name="cedulaconductor" /></td>
                <td><strong>Nombre</strong></td>
                <td><input type="text" class="form-control form-control-sm" id="nombreconductor" name="nombreconductor" oninput="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>
                <td></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaimgcedcond1';
$arreglo["nombreCampo"] = 'rutaimgcedcond1';
$arreglo["texto"] = '1er imagen o archivo <br/>de la cedula del conductor';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
                <td><strong></strong></td>
                <td>

<?php
$arreglo["idDiv"] = 'rutaimgcedcond2';
$arreglo["nombreCampo"] = 'rutaimgcedcond2';
$arreglo["texto"] = '2da imagen o archivo <br/>de la cedula del conductor';
retornarDivsParaImagenes($arreglo,0);
?>

                </td>
            </tr>
            <tr>
                <td><strong>Dirección</strong></td>
                <td><input type="text" class="form-control form-control-sm" id="direccionconductor" name="direccionconductor" oninput="this.value = this.value.toUpperCase()" /></td>
                <td><strong>Teléfono</strong></td>
                <td><input type="number" class="form-control form-control-sm" id="telefonoconductor" name="telefonoconductor" /></td>
            </tr>
            <tr>
                <td><strong>Vencimiento licencia</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="vencimientolicencia" name="vencimientolicencia" /></td>
                <td><strong>Correo electronico</strong></td>
                <td><input type="email" class="form-control form-control-sm" id="correoconductor" name="correoconductor" /></td>
            </tr>            
            <tr>
                <td><strong>1er imagen o archivo <br />de licencia</strong></td>
                <td>
                    <div id="divLicencia"></div>
                    <div id="divLicenciaConduccion">                        
                    </div>
                </td>
                <td><strong>2da imagen o archivo <br />de licencia</strong></td>
                <td>
                    <div id="divLicencia2"></div>
                    <div id="divLicenciaConduccion2">                        
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Foto actual del conductor</strong></td>
                <td>
                    <div>
                        <div id="divFotoConductor"></div>
                        <div id="divFormFotoConductor">
                        </div>
                    </div>
                </td>
                <td><strong>Hoja de vida</strong></td>
                <td>
                    <div id="divHojaVida"></div>
                    <div id="divFormHojaVidaConductor">                        
                    </div>
                </td>
            </tr>            
            <!--<tr>                
                <td></td>
                <td><input type="button" value="Mismo propietario" class="btn btn-success" id="btnMismoPropietario" /></td>
                <td></td>
                <td></td>
            </tr>
            -->
            <tr style="background-color: #22f160;">
                <td class="text-center align-middle"><strong>Verificación</strong></td>
                <td class="text-center align-middle"><strong>EPS</strong></td>
                <td class="text-center align-middle"><strong>ARL</strong></td>
                <td class="text-center align-middle"><strong>PENSION</strong></td>
            </tr>
            <tr>
                <td><strong>Nombre de la entidad</strong></td>
                <td>
                    <!--<div id="divCrearEps">
                        <table class="table">
                            <tr>
                                <td><input type="text" id="nombreEps" name="nombreEps" class="form form-control" oninput="this.value = this.value.toUpperCase()" /></td>
                                <td>
                                    <input type="button" value="Crear EPS" id="btnCrearEps" class="btn btn-success" />
                                </td>
                                <td>
                                    <input type="button" value="X" id="btnCancelarEps" class="btn btn-danger" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" id="mensajesEps"></td>
                            </tr>
                        </table>
                    </div>-->
                    <div id="divListaEps">
<?php
$nombresPosiciones = array("id" => "id", "nombre" => "nombre");
echo crearSelect($listaEps, "id_eps", $nombresPosiciones, "mostrarCrearEntidad(this,1)", true);
?>

                    </div>
                </td>
                <td>
                    <!--<div id="divCrearArl">
                        <table class="table">
                            <tr>
                                <td><input type="text" id="nombreArl" name="nombreArl" class="form form-control" oninput="this.value = this.value.toUpperCase()" /></td>
                                <td>
                                    <input type="button" value="Crear ARL" id="btnCrearArl" class="btn btn-success" />
                                </td>
                                <td>
                                    <input type="button" value="X" id="btnCancelarArl" class="btn btn-danger" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" id="mensajesArl"></td>
                            </tr>
                        </table>
                    </div>-->
                    <div id="divListaArl">
<?php
$nombresPosiciones = array("id" => "id", "nombre" => "nombre");
echo crearSelect($listaArl, "id_arl", $nombresPosiciones, "mostrarCrearEntidad(this,2)", true);
?>
                    </div>
                </td>
                <td>
                    <!--<div id="divCrearPension">
                        <table class="table">
                            <tr>
                                <td><input type="text" id="nombrePension" name="nombrePension" class="form form-control" oninput="this.value = this.value.toUpperCase()" /></td>
                                <td>
                                    <input type="button" value="Crear Pension" id="btnCrearPension" class="btn btn-success" />
                                </td>
                                <td>
                                    <input type="button" value="X" id="btnCancelarPension" class="btn btn-danger" />
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" id="mensajesArl"></td>
                            </tr>
                        </table>
                    </div>-->
                    <div id="divListaPension">
<?php
$nombresPosiciones = array("id" => "id", "nombre" => "nombre");
echo crearSelect($listaPension, "id_pension", $nombresPosiciones, "mostrarCrearEntidad(this,3)", true);
?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Estado</strong></td>
                <td>
                    <div id="divEstadoEps">

                        <?php
                        $nombresPosiciones = array("id" => "id", "nombre" => "nombre_estado");
                        echo crearSelect($listaEstadosTablas, "estadoeps", $nombresPosiciones, null, false);
                        ?>
                    </div>
                </td>
                <td>
                    <div id="divEstadoArl">

                        <?php
                        $nombresPosiciones = array("id" => "id", "nombre" => "nombre_estado");
                        echo crearSelect($listaEstadosTablas, "estadoarl", $nombresPosiciones, null, false);
                        ?>

                    </div>
                </td>
                <td>
                    <div id="divEstadoPension">

                        <?php
                        $nombresPosiciones = array("id" => "id", "nombre" => "nombre_estado");
                        echo crearSelect($listaEstadosTablas, "estadopension", $nombresPosiciones, null, false);
                        ?>

                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Planilla de seguridad <br />social del conductor</strong></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaplansegsoccond1';
$arreglo["nombreCampo"] = 'rutaplansegsoccond1';
$arreglo["texto"] = '1er imagen o archivo de la planilla de seguridad social del conductor';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaplansegsoccond2';
$arreglo["nombreCampo"] = 'rutaplansegsoccond2';
$arreglo["texto"] = '2da imagen o archivo de la planilla de seguridad social del conductor';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
                <td></td>
            </tr>
            <tr>
                <td style="background-color: #9de6d4ff;"><strong>Autorización de manejo <br />de datos personales</strong></td>
                <td><strong>Fecha de autorización</strong></td>
                <td><input type="date" id="fechadatospersonales" name="fechadatospersonales" class="form form-control" /></td>
                <td>
                    <?php
                    $arreglo["idDiv"] = 'rutadatospersonales';
                    $arreglo["nombreCampo"] = 'rutadatospersonales';
                    $arreglo["texto"] = 'Imagen o archivo de la autorizacion de manejo de datos personales';
                    retornarDivsParaImagenes($arreglo,0);
                    ?>
                </td>
            </tr>
            <tr>
                <td style="background-color: #FFB9B9;"><strong>Contacto emergencia</strong></td>
                <td><strong>Teléfono</strong></td>
                <td><input type="number" id="telefonoemergencia" name="telefonoemergencia" class="form form-control" /></td>
                <td></td>
            </tr>
            <tr>
                <td><strong>Nombre</strong></td>
                <td><input type="text" class="form-control form-control-sm" id="nombrecontacto" name="nombrecontacto" oninput="this.value = this.value.toUpperCase()" /></td>
                <td><strong>Parentesco</strong></td>
                <td>
                    <div>

<?php
$nombresPosiciones = array("id" => "id", "nombre" => "nombre");
echo crearSelect($listaParentescos, "id_parentescocontacto", $nombresPosiciones, null, false);
?>

                    </div>
                </td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>REFERENCIAS CONDUCTOR</strong></td>
            </tr>
            <tr style="background-color: #e2efd9;">
                <td colspan="4" class="text-center align-middle"><strong>REFERENCIA FAMILIAR</strong></td>
            </tr>
            <tr>
                <td><strong>Nombre</strong></td>
                <td><input type="text" class="form-control form-control-sm" id="nombrerefcond" name="nombrerefcond" oninput="this.value = this.value.toUpperCase()" /></td>
                <td><strong>Parentesco</strong></td>
                <td>
                    <div>
                        <?php
                        $nombresPosiciones = array("id" => "id", "nombre" => "nombre");
                        echo crearSelect($listaParentescos, "id_parenrefcond", $nombresPosiciones, null, false);
                        ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><strong>Dirección</strong></td>
                <td><input type="text" class="form-control form-control-sm" id="dirrefcond" name="dirrefcond" oninput="this.value = this.value.toUpperCase()" /></td>
                <td><strong>Teléfono</strong></td>
                <td><input type="number" class="form-control form-control-sm" id="telrefcond" name="telrefcond" oninput="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>
                <td><strong>Verificación referencia</strong></td>
                <td colspan="3"><textarea id="verifirefcond" name="verifirefcond" class="form form-control" oninput="this.value = this.value.toUpperCase()"></textarea></td>                
            </tr>            
            <tr style="background-color: #e2efd9;">
                <td colspan="4" class="text-center align-middle"><strong>REFERENCIA LABORAL </strong></td>
            </tr>
            <tr>
                <td><strong>Nombre de la empresa</strong></td>
                <td colspan="3"><input type="text" class="form-control form-control-sm" id="nombrereflab" name="nombrereflab" oninput="this.value = this.value.toUpperCase()" /></td>

<!--<td><strong>Parentesco</strong></td>
<td>
    <div>
<?php
//        $nombresPosiciones = array("id" => "id", "nombre" => "nombre");
//        echo crearSelect($listaParentescos, "id_parenrefcondlab", $nombresPosiciones, null, false);
?>
    </div>
</td>-->
            </tr>
            <tr>
                <td><strong>Dirección</strong></td>
                <td><input type="text" class="form-control form-control-sm" id="direcreflab" name="direcreflab" oninput="this.value = this.value.toUpperCase()" /></td>
                <td><strong>Teléfono</strong></td>
                <td><input type="number" class="form-control form-control-sm" id="telecreflab" name="telecreflab" oninput="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>
                <td><strong>Verificación referencia</strong></td>
                <td colspan="3"><textarea id="vercreflab" class="form form-control" oninput="this.value = this.value.toUpperCase()"></textarea></td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>CONSULTA ANTECEDENTES CONDUCTOR</strong></td>
            </tr>
            <tr>
                <td><strong>RUNT</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecvenciruntcond" name="fecvenciruntcond" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaruntcond';
$arreglo["nombreCampo"] = 'rutaruntcond';
$arreglo["texto"] = 'RUNT - Conductor';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Policia - <br />Antecedentes policiales</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecvenciponal" name="fecvenciponal" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaantepolcond';
$arreglo["nombreCampo"] = 'rutaantepolcond';
$arreglo["texto"] = 'Imagen o archivo Policia - Antecedentes policiales';
retornarDivsParaImagenes($arreglo,0);
?> 
                </td>               
            </tr>
            <tr>
                <td><strong>Procuraduría</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecvenciprocu" name="fecvenciprocu" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaprocucond';
$arreglo["nombreCampo"] = 'rutaprocucond';
$arreglo["texto"] = 'Procuraduría';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Contraloría</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecvencicontrola" name="fecvencicontrola" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutacontrcond';
$arreglo["nombreCampo"] = 'rutacontrcond';
$arreglo["texto"] = 'Contraloria';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>SIMIT - <br />Infracciones de transito</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecvencisimit" name="fecvencisimit" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutasimitcond';
$arreglo["nombreCampo"] = 'rutasimitcond';
$arreglo["texto"] = 'SIMIT - Infracciones de transito';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Registro Nacional de <br />Medidas Correctivas - RNMC</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecvencirnmc" name="fecvencirnmc" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutarnmccond';
$arreglo["nombreCampo"] = 'rutarnmccond';
$arreglo["texto"] = 'Registro Nacional de Medidas Correctivas - RNMC';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
                </td>
            </tr>
            <tr>
                <td><strong>Consulta de inhabilidades</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecvenciinhab" name="fecvenciinhab" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutainhabcond';
$arreglo["nombreCampo"] = 'rutainhabcond';
$arreglo["texto"] = 'Consulta de inhabilidades';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>PROPIETARIO</strong></td>
            </tr>
            <tr>
                <td><strong>Cedula o NIT</strong></td>
                <td><input type="number" class="form-control form-control-sm" id="cedulapropietario" name="cedulapropietario" /></td>
                <td><strong>Nombre</strong></td>
                <td><input type="text" class="form-control form-control-sm" id="nombrepropietario" name="nombrepropietario" oninput="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>
                <td><strong></strong></td>
                <td>
                    <?php
                    $arreglo["idDiv"] = 'rutaimgcedprop1';
                    $arreglo["nombreCampo"] = 'rutaimgcedprop1';
                    $arreglo["texto"] = '1er imagen o archivo <br/>de la cedula del propietario';
                    retornarDivsParaImagenes($arreglo,0);
                    ?>
                </td>
                <td><strong></strong></td>
                <td>

<?php
$arreglo["idDiv"] = 'rutaimgcedprop2';
$arreglo["nombreCampo"] = 'rutaimgcedprop2';
$arreglo["texto"] = '2da imagen o archivo <br/>de la cedula del propietario';
retornarDivsParaImagenes($arreglo,0);
?>

                </td>
            </tr>
            <tr>
                <td><strong>Dirección</strong></td>
                <td><input type="text" class="form-control form-control-sm" id="direccionpropietario" name="direccionpropietario" /></td>
                <td><strong>Teléfono</strong></td>
                <td><input type="number" class="form-control form-control-sm" id="telefonopropietario" name="telefonopropietario" oninput="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>                
                <td><strong>Correo electronico</strong></td>
                <td colspan="3"><input type="email" class="form-control form-control-sm" id="correopropietario" name="correopropietario" /></td>                
            </tr>
            <tr>
                <td><strong>Autorización de <br>manejo de datos personales</strong></td>
                <td><strong>Fecha autorización</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fechadatperprop" name="fechadatperprop" /></td>
                <td>
                    <div id="divDatPerPropietario"></div>
                    <div id="divFormDatPerPropietario">
                        
                    </div>
                </td>

            </tr>
            <tr>
                <td><strong>Certificación bancaria</strong></td>
                <td></td>
                <td></td>
                <td>
                    <div id="divCertBancPropietario"></div>
                    <div id="divFormCertBancPropietario">
                        
                    </div>
                </td>

            </tr>
            <tr>
                <td><strong>RUT Número</strong></td>
                <td><input type="text" class="form-control form-control-sm" id="numrutpropietario" name="numrutpropietario" pattern="^\d{9}-[0-9Kk]$"
                           title="El RUT debe tener entre 7 y 10 dígitos, seguido de un guion y un dígito verificador (número o K)" oninput="this.value = this.value.toUpperCase()" /></td>
                <td><strong>RUT Documento</strong></td>
                <td>
                    <div id="divRUTPropietario"></div>
                    <div id="divFormRUTPropietario">
                        
                    </div>
                </td>
            </tr>
            <!--<tr>                
                <td></td>
                <td><input type="button" value="Mismo propietario trailer" class="btn btn-success" id="btnMismoPropietarioTrailer"/></td>
                <td></td>
                <td></td>
            </tr>
            -->
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>CONSULTA ANTECEDENTES PROPIETARIO</strong></td>
            </tr>
            <tr>
                <td><strong>RUNT</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecvenciruntprop" name="fecvenciruntprop" /></td>
                <td>
                    <div id="divRUNTPropietario">RUNT - Propietario</div>
                    <div id="divFormRUNTPropietario">                        
                    </div>
                </td>

            </tr>
            <!--<tr>
                <td><strong>Consulta RUNT <br />propiedad vehículo</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecruntproveh" name="fecruntproveh" /></td>
                <td></td>
            </tr>-->
            <tr>
                <td><strong>Policia - <br />Antecedentes judiciales</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecpolantproveh" name="fecpolantproveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaantepolprop';
$arreglo["nombreCampo"] = 'rutaantepolprop';
$arreglo["texto"] = 'Imagen o archivo Policia - Antecedentes policiales';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Personería - <br />Antecedentes disciplinarios</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecpersproveh" name="fecpersproveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaantedisprop';
$arreglo["nombreCampo"] = 'rutaantedisprop';
$arreglo["texto"] = 'Personeria - Antecedentes disciplinarios';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Procuraduria</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecprocproveh" name="fecprocproveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaprocuprop';
$arreglo["nombreCampo"] = 'rutaprocuprop';
$arreglo["texto"] = 'Procuraduria';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Contraloría</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="feccontrproveh" name="feccontrproveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutacontrprop';
$arreglo["nombreCampo"] = 'rutacontrprop';
$arreglo["texto"] = 'Contraloria';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Registro Nacional de <br />Medidas Correctivas - RNMC</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecrnmcproveh" name="fecrnmcproveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutarnmcprop';
$arreglo["nombreCampo"] = 'rutarnmcprop';
$arreglo["texto"] = 'Registro Nacional de Medidas Correctivas - RNMC';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Consulta de inhabilidades</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecinhabproveh" name="fecinhabproveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutainhabprop';
$arreglo["nombreCampo"] = 'rutainhabprop';
$arreglo["texto"] = 'Consulta de inhabilidades';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>LOCATARIO / TENEDOR / COMPRADOR (LTC)</strong></td>
            </tr>
            <tr>
                <td><strong>Cedula o NIT</strong></td>
                <td><input type="text" class="form form-control" id="identloctencom" name="identloctencom" oninput="this.value = this.value.toUpperCase()" /></td>
                <td><strong>Nombre</strong></td>
                <td><input type="text" class="form form-control" id="nomloctencom" name="nomloctencom" oninput="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php
                    $arreglo["idDiv"] = 'rutaimgcedltc1';
                    $arreglo["nombreCampo"] = 'rutaimgcedltc1';
                    $arreglo["texto"] = '1er imagen o archivo <br/>de la cedula o documento del LTC';
                    retornarDivsParaImagenes($arreglo,0);
                    ?>
                </td>
                <td><strong></strong></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaimgcedltc2';
$arreglo["nombreCampo"] = 'rutaimgcedltc2';
$arreglo["texto"] = '2da imagen o archivo <br/>de la cedula del LTC';
retornarDivsParaImagenes($arreglo,0);
?>

                </td>
            </tr>
            <tr>
                <td><strong>Dirección</strong></td>
                <td><input type="text" class="form form-control" id="dirloctencom" name="dirloctencom" oninput="this.value = this.value.toUpperCase()" /></td>
                <td><strong>Teléfono</strong></td>
                <td><input type="number" class="form form-control" id="telloctencom" name="telloctencom" /></td>
            </tr>
            <tr>
                <td><strong>Documento soporte</strong></td>
                <td>
                    <div id="divDocumentoSoporte">
                    <?php
                    $nombresPosiciones = array("id" => "id", "nombre" => "nombre");
                    echo crearSelect($listaDocumentoSoporte, "id_documentosoporte", $nombresPosiciones, null, false);
                    ?>
                    </div>
                </td>
                <td></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaimgdocsopo';
$arreglo["nombreCampo"] = 'rutaimgdocsopo';
$arreglo["texto"] = 'Documento soporte';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>RUT Número</strong></td>
                <td><input type="text" class="form form-control" id="numrutltc" name="numrutltc" pattern="^\d{9}-[0-9Kk]$"
                           title="El RUT debe tener entre 7 y 10 dígitos, seguido de un guion y un dígito verificador (número o K)" oninput="this.value = this.value.toUpperCase()" /></td>
                <td><strong>RUT Documento</strong></td>
                <td>
                    <div id="divRUTDocumento"></div>
                    <div id="divFormRUTDocumento">
                        
                    </div>
                </td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>CONSULTA ANTECEDENTES</strong></td>
            </tr>
            <tr>
                <td><strong>Consulta RUNT <br />propiedad vehículo</strong></td>
                <td><strong>Fecha vencimiento</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecruntltcveh" name="fecruntltcveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaimgruttlc';
$arreglo["nombreCampo"] = 'rutaimgruttlc';
$arreglo["texto"] = 'Actualizar RUNT';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Policía - <br />Antecedentes judiciales</strong></td>
                <td><strong>Fecha vencimiento</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecpolantltcveh" name="fecpolantltcveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaimgantjudtlc';
$arreglo["nombreCampo"] = 'rutaimgantjudtlc';
$arreglo["texto"] = 'Actualizar Policia - Antecedentes judiciales ';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Personería - <br />Antecedentes disciplinarios</strong></td>
                <td><strong>Fecha vencimiento</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecpersltcveh" name="fecpersltcveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaimgperstlc';
$arreglo["nombreCampo"] = 'rutaimgperstlc';
$arreglo["texto"] = 'Actualizar Personeria - Antecedentes disciplinarios ';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Procuraduría</strong></td>
                <td><strong>Fecha vencimiento</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecprocltcveh" name="fecprocltcveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaimgproctlc';
$arreglo["nombreCampo"] = 'rutaimgproctlc';
$arreglo["texto"] = 'Actualizar Procuraduria ';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Contraloría</strong></td>
                <td><strong>Fecha vencimiento</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="feccontrltcveh" name="feccontrltcveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaimgconttlc';
$arreglo["nombreCampo"] = 'rutaimgconttlc';
$arreglo["texto"] = 'Actualizar Contraloria ';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Registro Nacional de <br />Medidas Correctivas - RNMC</strong></td>
                <td><strong>Fecha vencimiento</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecrnmcltcveh" name="fecrnmcltcveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaimgrnmctlc';
$arreglo["nombreCampo"] = 'rutaimgrnmctlc';
$arreglo["texto"] = 'Actualizar RNMC ';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Consulta de inhabilidades</strong></td>
                <td><strong>Fecha vencimiento</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fecinhabltcveh" name="fecinhabltcveh" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaimginhatlc';
$arreglo["nombreCampo"] = 'rutaimginhatlc';
$arreglo["texto"] = 'Consulta de inhabilidades ';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>SOLO PARA TRANSPORTE DE ALIMENTOS</strong></td>
            </tr>
            <tr>
                <td><strong>Certificado y carnet <br />de manipulación de alimentos</strong></td>
                <td><strong>Fecha vencimiento</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="cercarmaniali" name="cercarmaniali" /></td>
                <td>
                    <?php
                    $arreglo["idDiv"] = 'rutacercarmaniali';
                    $arreglo["nombreCampo"] = 'rutacercarmaniali';
                    $arreglo["texto"] = 'Certificado y carnet de manipulacion de alimentos ';
                    retornarDivsParaImagenes($arreglo,0);
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Certificado de fumigación</strong></td>
                <td><strong>Fecha vencimiento</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="cerfumig" name="cerfumig" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutacerfumig';
$arreglo["nombreCampo"] = 'rutacerfumig';
$arreglo["texto"] = 'Certificado de fumigacion ';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Certificado concepto de sanidad</strong></td>
                <td><strong>Fecha vencimiento</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="cerconsan" name="cerconsan" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutacerconsan';
$arreglo["nombreCampo"] = 'rutacerconsan';
$arreglo["texto"] = 'Certificado concepto de sanidad ';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>SOLO PARA REMOLQUE</strong></td>
            </tr>
            <tr>
                <td><strong>Cedula propietario</strong></td>
                <td><input type="number" class="form form-control" id="cedpropremol" name="cedpropremol" /></td>
                <td><strong>Nombre</strong></td>
                <td><input type="text" class="form form-control" id="nompropremol" name="nompropremol" oninput="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>
                <td></td>
                <td>
                    <?php
                    $arreglo["idDiv"] = 'rutacedpropremol1';
                    $arreglo["nombreCampo"] = 'rutacedpropremol1';
                    $arreglo["texto"] = '1er imagen o archivo <br/>de la cedula o documento del remolque';
                    retornarDivsParaImagenes($arreglo,0);
                    ?>
                </td>
                <td><strong></strong></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutacedpropremol2';
$arreglo["nombreCampo"] = 'rutacedpropremol2';
$arreglo["texto"] = '2da imagen o archivo <br/>de la cedula o documento del remolque';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Placa remolque</strong></td>
                <td><input type="text" class="form form-control" id="placaremol" name="placaremol" oninput="this.value = this.value.toUpperCase()" /></td>
                <td><strong>Numero tarjeta registro remolque</strong></td>
                <td><input type="text" class="form form-control" id="tarregremol" name="tarregremol" oninput="this.value = this.value.toUpperCase()" /></td>
            </tr>
            <tr>
                <td><strong>Foto con placa del trailer</strong></td>
                <td>
                    <div>
                        <div id="divFotoPlacaTrailer"></div>
                        <div id="divFormPlacaTrailer">
                            
                        </div>
                    </div>
                </td>
                <td>
                    <strong>Tarjeta registro remolque</strong> 
                </td>
                <td>
<?php
$arreglo["idDiv"] = 'rutatarregremol';
$arreglo["nombreCampo"] = 'rutatarregremol';
$arreglo["texto"] = 'Imagen o archivo de la Tarjeta registro remolque';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>CONSULTA ANTECEDENTES PROPIETARIO TRAILER</strong></td>
            </tr>
            <tr>
                <td><strong>Consulta RUNT <br />propiedad remolque</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="runtremol" name="runtremol" /></td>
                <td>
                    <?php
                    $arreglo["idDiv"] = 'rutaruntremol';
                    $arreglo["nombreCampo"] = 'rutaruntremol';
                    $arreglo["texto"] = 'Consulta RUNT propiedad remolque';
                    retornarDivsParaImagenes($arreglo,0);
                    ?>
                </td>
            </tr>
            <tr>
                <td><strong>Policía - <br />Antecedentes judiciales</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="polremol" name="polremol" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutapolremol';
$arreglo["nombreCampo"] = 'rutapolremol';
$arreglo["texto"] = 'Policía - Antecedentes judiciales';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Procuraduría</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="proremol" name="proremol" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaproremol';
$arreglo["nombreCampo"] = 'rutaproremol';
$arreglo["texto"] = 'Procuraduría';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Contraloría</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="conremol" name="conremol" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutaconremol';
$arreglo["nombreCampo"] = 'rutaconremol';
$arreglo["texto"] = 'Contraloría';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Consulta de inhabilidades</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="inharemol" name="inharemol" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutainharemol';
$arreglo["nombreCampo"] = 'rutainharemol';
$arreglo["texto"] = 'Consulta de inhabilidades';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr>
                <td><strong>Registro Nacional de <br />Medidas Correctivas - RNMC</strong></td>
                <td><strong>Fecha consulta</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="rnmcremol" name="rnmcremol" /></td>
                <td>
<?php
$arreglo["idDiv"] = 'rutarnmcremol';
$arreglo["nombreCampo"] = 'rutarnmcremol';
$arreglo["texto"] = 'Registro Nacional de Medidas Correctivas - RNMC';
retornarDivsParaImagenes($arreglo,0);
?>
                </td>
            </tr>
            <tr style="background-color: #22f160;">
                <td colspan="4" class="text-center align-middle"><strong>DATOS FINALES</strong></td>
            </tr>
            <tr>
                <td><strong>Estudio elaborado por:</strong></td>
                <td>
                    <div id="divNombresEmpleados">
<?php
$nombresPosiciones = array("id" => "emp_cedula", "nombre" => "nombreCompleto");
echo crearSelect($listaEmpleados, "empleados", $nombresPosiciones, null, false);
?>
                    </div>
                </td>
                <td><strong>Fecha elaboración</strong></td>
                <td><input type="date" class="form-control form-control-sm" id="fechaestudio" name="fechaestudio" /></td>
            </tr>
            <tr>
                <td><strong>Observaciones</strong></td>
                <td colspan="3"><textarea class="form form-control" id="observaciones" oninput="this.value = this.value.toUpperCase()"></textarea></td>
            </tr>
        </table>
    </div>
    <div class="panel-body">
        <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);"> MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
        <button name="boton" id="crearPdf" type="button" class="btn btn-success btn-ls botonPropio" value="CREAR PDF" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);"> CREAR PDF <img src="../imagenes/document_16.png"></button>
        <button name="boton" id="botonNuevoEstudio" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);"> NUEVO ESTUDIO <img src="../imagenes/document_16.png"></button>
        <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);">SALIR <img src="../imagenes/salir.png"> </button>
    </div>
</body>
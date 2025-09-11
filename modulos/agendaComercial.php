<?php
session_start();

include_once '../clases/asesor_empresa.php';
include_once '../clases/funcionesVarias.php';

$asesor = new asesor_empresa();
$empresas = $asesor->retornarEmpresasAsesor($_SESSION["emp_cedula"]);
$asunto = $asesor->retornarAsunto();

$eventos = $asesor->retornarDatosAgenda($_SESSION["emp_cedula"], 1, 0, 0,0);
$municipio = $asesor->retornarMunicipios();
$mostrarEventos = null;
$listaEmpresas = null;

$listaEmpresas2 = null;

if ($_SESSION["departamento"] === 'COMERCIAL') {
    $listaEmpresas = "<select class='form-control form-control-sm' id='listaEmpresas' onclick='listarDirecciones()'>";
    $listaEmpresas .= "<option value='0'>...</option>";
    $listaEmpresas .= "<option value='-1'>::::CREAR EMPRESA::::</option>";
    $listaEmpresas2 = "<select class='form-control form-control-sm' id='listaEmpresas2'>";
    $listaEmpresas2 .= "<option value='0'>...</option>";
    for ($index = 0; $index < count($empresas); $index++) {
        $listaEmpresas .= "<option value='" . $empresas[$index]["cli_documento"] . "'>" . $empresas[$index]["cli_nombre"] . "</option>";
        $listaEmpresas2 .= "<option value='" . $empresas[$index]["cli_documento"] . "'>" . $empresas[$index]["cli_nombre"] . "</option>";
    }
    $listaEmpresas .= "</select>";
    $listaEmpresas2.="</select>";
} else {
    $listaEmpresas = "";
}

$listaAsunto = "<div id='divListaAsunto'><select class='form-control form-control-sm' id='listaAsunto'>";
$listaAsunto .= "<option value='0'>...</option>";
for ($index1 = 0; $index1 < count($asunto); $index1++) {
    $listaAsunto .= "<option value='" . $asunto[$index1]["id"] . "'>" . $asunto[$index1]["evento"] . "</option>";
}
//$listaAsunto .= "<option value='-1'>Crear evento</option>";
$listaAsunto .= "</select></div>";

$listaMunicipios = "<select class='form-control form-control-sm' id='listaMunicipios'>";
$listaMunicipios .= "<option value='0'>...</option>";
for ($index3 = 0; $index3 < count($municipio); $index3++) {
    $listaMunicipios .= "<option value='" . $municipio[$index3]["mun_id"] . "' >" . $municipio[$index3]["mun_nombre"] . "</option>";
}
$listaMunicipios .= "</select>";

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
            <meta charset="UTF-8">
            <title>Agenda comercial</title>
            <link rel="icon" href="../imagenes/camion256.png">
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />                        
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
            
            <script src="../js/js_agendaComercial.js?n=<?= rand(0,3)?>" type="text/javascript"></script>
            <script src="../js/numeros_letras.js" type="text/javascript"></script>
            <script src="../js/bootstrap.min.js" type="text/javascript"></script>  
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">        
        </head>
        <body>
            <div id="contenedor-index" class="row paddinMargin">
                <div id="divImagenUsa" class="col-xs-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                <div id="texoDocumento" class="col-xs-4">
                    <h1>Agenda comercial</h1>
                </div> 
                <div id="divDatosIniciales" class="col-sm-4">
                    <ul class="list-group">
                        <li class="list-group-item ">
                            <span class="badge"><?= $_SESSION["nombre_usuario"]; ?></span>
                            Usuario
                        </li>
                        <li class="list-group-item ">
                            <span class="badge"><?= $_SESSION["departamento"]; ?></span>
                            Departamento
                        </li>                                        
                        <li class="list-group-item ">
                            <span class="badge"><?= date('Y-m-d'); ?></span>
                            Fecha
                        </li>
                    </ul>
                </div>
            </div> 

            <div id="divDatosCliente" class="panel panel-success small paddinMargin">
                <input type="hidden" id="cedulaEmpleado" value="<?= $_SESSION["emp_cedula"] ?>" />      
                <input type="hidden" id="nombreEmpleado" value="<?= $_SESSION["nombre_usuario"]; ?>" />
                <div class="panel-heading">                                        
                    <h3 class="panel-title">Datos de la agenda</h3>
                </div>                
                <input type="checkbox" id="activarBusqueda" alt="Activa las condiciones de búsqueda de agendamiento en con fechas y empresas" />Realizar b&uacute;squeda
                <div class="table table-responsive" id="divAgenda">
                    <table class="table">
                        <tr>
                            <th>Asunto</th>
                            <th>Clientes</th>
                            <th id="cambio">Direcci&oacute;n</th>
                            <th>Contacto</th> 
                        </tr>
                        <tr>
                            <td><?= $listaAsunto; ?></td>
                            <td><div id="divListaEmpresas"><?= $listaEmpresas; ?></div></td>
                            <td><div id="divDirOrg"></div></td>
                            <td><div id="contactoCliente"></div></td>                            
                        </tr>
                        <tr>
                            <td colspan="5">
                                <div id="datosContacto" >

                                </div>
                            </td>                        
                        </tr>
                        <tr>
                            <th>Fecha Inicio</th>
                            <th>Fecha fin</th>
                            <th></th>
                            <th></th>
                        </tr>                        
                        <tr>
                            <td>
                                <input type="datetime-local" name="fechaHoraInicio" id="fechaHoraInicio" class="form-control"/>                                
                            </td>
                            <td>
                                <input type="datetime-local" name="fechaHoraFin" id="fechaHoraFin" class="form-control" />
                            </td>
                            <td><input type="button" value="Crear evento" class='form-control' id="crearEvento" /></td>
                            <td></td>
                        </tr>                        
                    </table>
                    <div id="divEventosCreados">
                        <div class="alert alert-dismissible alert-success" id="mensajeRecordatorio">
                            Señor(a) asesor(a), por favor recuerde hacer gestión de sus eventos con la lista de selección ubicada al final de cada uno de ellos. <br>
                            Si desea crear un evento para una empresa de la cual no se conoce el NIT, puede utilizar la opción Personal de la lista de Asuntos, en la casilla Asunto y/o dirección incluya todo el texto que necesite.
                        </div>
                        <?php                                                
                        $mostrarEventos = "<table class='table table-hover'>";

                        if (count($eventos["empresas"]) > 0 || count($eventos["personales"] > 0)) {
                            $mostrarEventos .= "<tr><th>Evento</th><th>Direcci&oacute;n y/o evento</th><th>Fecha/Hora Inicio</th><th>Fecha/Hora Fin</th><th>Funcionario</th><th>Correo electr&oacute;nico</th><th>Cargo</th><th>Tel&eacute;fono</th><th>Cliente</th><th>Gesti&oacute;n</th>";
                        }

                        if (count($eventos["empresas"])) {
                            $mostrarEventos .= "<tr><th colspan='10'>Eventos empresariales</th></tr>";

                            for ($index2 = 0; $index2 < count($eventos["empresas"]); $index2++) {

                                $listaGestion = "<select class='form-control' onchange='cambiarEstadoEvento(this)' id='" . $eventos["empresas"][$index2]["id"] . "'>";
                                $listaGestion .= "<option value='-1' >...</option>";
                                $listaGestion .= "<option value='1' selected >Activo</option>";
                                $listaGestion .= "<option value='2' >Pospuesto</option>";
                                $listaGestion .= "<option value='3' >Cancelado</option>";
                                $listaGestion .= "<option value='0' >Finalizado</option>";
                                
                                if($eventos["empresas"][$index2]["estado"]==='0'){
                                    $listaGestion="Finalizado";
                                }

                                $mostrarEventos .= "<tr><td>" . $eventos[$index2]["evento"] . "</td>"
                                        . "<td>" . $eventos["empresas"][$index2]["direccion"] . "</td>"
                                        . "<td>" . $eventos["empresas"][$index2]["fechaHoraInicio"] . "</td>"
                                        . "<td>" . $eventos["empresas"][$index2]["fechaHoraFin"] . "</td>"
                                        . "<td>" . $eventos["empresas"][$index2]["nombre"] . "</td>"
                                        . "<td>" . $eventos["empresas"][$index2]["correo"] . "</td>"
                                        . "<td>" . $eventos["empresas"][$index2]["cargo"] . "</td>"
                                        . "<td>" . $eventos["empresas"][$index2]["telefono"] . "</td>"
                                        . "<td>" . $eventos["empresas"][$index2]["cli_nombre"] . "</td>"
                                        . "<td>" . $listaGestion . "</td></tr>";
                            }
                        }

                        if (count($eventos["personales"]) > 0) {

                            $mostrarEventos .= "<tr><th colspan='10'>Eventos personales</th></tr>";

                            for ($index2 = 0; $index2 < count($eventos["personales"]); $index2++) {
                                                                
                                $listaGestion = "<select class='form-control' onchange='cambiarEstadoEvento(this)' id='" . $eventos["personales"][$index2]["id"] . "'>";
                                $listaGestion .= "<option value='-1' >...</option>";
                                $listaGestion .= "<option value='1' selected >Activo</option>";
                                $listaGestion .= "<option value='2' >Pospuesto</option>";
                                $listaGestion .= "<option value='3' >Cancelado</option>";
                                $listaGestion .= "<option value='0' >Finalizado</option>";
                                
                                if($eventos["personales"][$index2]["estado"]==='0'){
                                    $listaGestion="Finalizado";
                                }

                                $mostrarEventos .= "<tr><td>Personal</td>"
                                        . "<td>" . $eventos["personales"][$index2]["personal"] . "</td>"
                                        . "<td>" . $eventos["personales"][$index2]["fechaHoraInicio"] . "</td>"
                                        . "<td>" . $eventos["personales"][$index2]["fechaHoraFin"] . "</td>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "<td>" . $listaGestion . "</td></tr>";
                            }
                        }



                        $mostrarEventos .= "</table>";
                        echo $mostrarEventos;
                        ?>
                    </div>
                </div>                
            </div>            
            <div id="divBusqueda">
                <table class='table table-hover' >
                    <tr><th>Fecha inicial</th><th>Fecha final</th><th>Lista empresas</th><th></th></tr>
                    <tr><td><input type="date" id="fechaInicial"/></td><td><input type="date" id="fechaFinal" value="<?= date("Y-m-d") ?>" /></td><td><?= $listaEmpresas2; ?></td><td><input type="button" value="Buscar" id="consultarAgenda" /></td></tr>
                </table>
            </div>
            <div id="mensajes"></div>
            <div id="mensajes2"></div>

            <div id="divListaMunicipios">
                <?= $listaMunicipios; ?>
            </div>
            <form method="post" action="../trafico/redirigir.php" name="formuarlio_index" >
                <div class="col-lg-12">
                    <button name="boton" id="REGRESAR" type="submit" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                    <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" onmouseenter="colorEntra(this);" onmouseleave="colorSale(this);" value="SALIR"> SALIR <img src="../imagenes/salir.png"></button>                                                               
                </div>
            </form>            
        </body>
    </html>

    <?php
}


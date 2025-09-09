<?php
session_start();

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {
        /* el @ (de Wilmer) evita presentar cuando hay error en variable (RMG-2024-05-20) */
    if (@$_GET["pj"] === null || $_GET["pj"] === '1' || $_GET["pj"] === '3' || $_GET["pj"] === '6') {

        include_once '../clases/rol_boton.php';
        include_once '../clases/funcionesVarias.php';

        $rol_boton = new rol_boton();
        $placas = $rol_boton->retornarPlacas(1);

        $conductores = $rol_boton->retornarPropietarios();
        $propietarios = '<select name="idConductor" id="idConductor" class="form-control col-lg-8">'
                . '<option value="0">...</option>';
        for ($index = 0; $index < count($conductores); $index++) {
            $propietarios .= "<option value=" . $conductores[$index]["cond_identificacion"] . ">" . $conductores[$index]["cond_nombres"] . " " . $conductores[$index]["cond_apellidos"] . "</option>";
        }
        $propietarios .= "</select>";

        $placa = retornarListaPlacas($placas, 1);
        ?>

        <!DOCTYPE html>
        <!--
        To change this license header, choose License Headers in Project Properties.
        To change this template file, choose Tools | Templates
        and open the template in the editor.
        -->
        <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Cuenta de cobro</title>
                <link rel="icon" href="../imagenes/favicon.ico">

                <link href="../css/css2.css" rel="stylesheet" type="text/css" />
                <?= retornarRecursosBootstrap(); ?>
                <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
                <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            

                <script src="../js/js_cuentaCobro.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>
                <script src="../js/cancelarServicio.js" type="text/javascript"></script>
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
                <form method="POST" action="../trafico/cuentaCobro.php" id="formularioCotizacion" > 
                    <input type="hidden" id="serviciosAPagar" name="serviciosAPagar" value="0" />      
                    <div id="contenedor-index" class="row paddinMargin">
                        <div id="divImagenUsa" class="col-xs-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-xs-4">
                            <h1>Cuenta de cobro</h1>
                        </div> 
                        <div id="divDatosIniciales" class="col-sm-4">
                            <ul class="list-group">                                
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $_SESSION["nombre_usuario"]; ?></span>
                                    Usuario
                                </li>                               
                                <li class="list-group-item">
                                    <span class="badge"><?php echo date('Y-m-d'); ?></span>
                                    Fecha
                                    <?= '<input type="hidden" name="fechaApertura" value="' . date('Y-m-d') . '" />'; ?>
                                </li>
                                <li class="list-group-item">
                                    <span class="badge">Pendiente</span>
                                    <input type='hidden' name='numeroAnticipo' id='numeroAnticipo' value='0' >
                                    Consecutivo
                                </li>
                            </ul>
                        </div>
                        <div class="col-sm-12">
                            <div class="col-lg-3 panel">
                                <div class="col-lg-3 text-center">
                                    <h4>Placas</h4>
                                </div>
                                <div class="col-lg-9" id="placa">
                                    <?= $placa; ?>                                    
                                </div>                                
                            </div>                            
                            <div class="col-lg-5 panel">
                                <div class="col-lg-3 text-center">
                                    <h4>Propietarios</h4>
                                </div>
                                <div class="col-lg-9">
                                    <?= $propietarios; ?>     
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="col-lg-12">
                                    <button type="button" value="REALIZAR CONSULTA" id="botonRealizarConsulta" name="botonRealizarConsulta" class="form-control" > CONSULTAR </button>
                                </div>
                            </div>
                        </div>
                        <div id="mensaje" class="col-lg-12">
                            <?php
                            if (@$_GET["pj"] === '3') {
                                echo '<div class="alert alert-dismissible alert-warning">La c&eacute;dula: ' . $_GET["id"] . ' no registra servicios por pagar</div>';
                            }
                            if (@$_GET["pj"] === '6') {
                                echo '<div class="alert alert-dismissible alert-danger"><span class="glyphicon glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
  <span class="sr-only">Informaci&oacute;n</span>
  No se registran servicios por pagar con placa o el propietario seleccionados. Por favor verifique</div>';
                            }
                            ?>
                        </div>
                        <div id="botones">
                            <button type="button" value="REGRESAR" id="botonRegresar" name="botonRegresar" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                            
                            <button type="button" value="SALIR" id="botonSalir" name="botonSalir" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>
                        </div>
                    </div>
                </form>
            </body>
        </html>

        <?php
    } else {

        include_once '../clases/anticipos.php';
        include_once '../clases/funcionesVarias.php';

        $anticipos = new anticipos();

        $tamanio = 15;
        /* retorna (Wilmer) el máximo numero de cuenta de cobro a usar (RMG) */
        $numeroCtaCobro = $anticipos->retornarNumeroCtaCobro();  
        /* extrae (Wilmer) del arreglo y lo deja directo en la variable (RMG) */
        $numeroCtaCobro = $numeroCtaCobro["numeroCtaCobro"];
        //busco los datos del conductor
        //var_dump($_GET["pj"]);

        $arreglo = $anticipos->datosConductor($_GET["pj"], $_GET["placa"]);

        $placa = $_GET["placa"];

        if (count($arreglo) === 0) {
            header("Location: ../modulos/cuentaCobro.php?id=" . $_GET["pj"] . "&pj=3");
        } else {

            $cond_id = $arreglo["cond_id"];
            $conductorNo = 0;
            $cli_id = $arreglo["cond_id"];
            $cond_nombres = $arreglo["cond_nombres"];
            $cond_apellidos = $arreglo["cond_apellidos"];
            $cond_direccion = $arreglo["cond_direccion"];
            $cond_telefono = $arreglo["cond_telefono"];
            $cond_idMun = $arreglo["municipios_mun_id"];
            $cond_placa = $arreglo["placa"];
            $cond_cedula = $arreglo["cond_identificacion"];
            $estadoRelacion = $arreglo["estadoRelacion"];

            if ($estadoRelacion === 'INACTIVO') {
                header("Location: ../modulos/conductores.php?pj=" . $_GET["pj"]);
            }
            
            if (intval($arreglo["perfil"]) !== 9) {
                header("Location: ../modulos/index.php?msj=5");                
            }
        }

        //var_dump($arreglo); exit();
        //traigo la lista de ciudades
        $arreglo = $anticipos->retonarCiudades();

        $mostrarCiudad = "<select id='idCiudadConductor' name='idCiudadConductor' class='form-control input-sm'>";
        for ($i = 0; $i < count($arreglo); $i++) {
            $idMunicipio = $arreglo[$i]["mun_id"];
            if ($idMunicipio === $cond_idMun) {
                $mostrarCiudad .= "<option value=" . $arreglo[$i]["mun_id"] . " selected>" . $arreglo[$i]["mun_nombre"] . "</option>";
            } else {
                $mostrarCiudad .= "<option value=" . $arreglo[$i]["mun_id"] . ">" . $arreglo[$i]["mun_nombre"] . "</option>";
            }
        }
        $mostrarCiudad .= "</select>";

        //traigo el propietario del vehiculo
        $arreglo = $anticipos->retornarPropietario($cond_placa);
        $propietario = $arreglo[0]["cond_nombres"] . " " . $arreglo[0]["cond_apellidos"];

        //traigo la lista de clientes
        $arreglo = $anticipos->retornarClientes();
        $lista = '<select name="idCliente" id="idCliente" class="form-control input-sm" >'
                . '<option value="0">...</option>';
        for ($index = 0; $index < count($arreglo); $index++) {
            $lista .= "<option value=" . $arreglo[$index]["cli_documento"] . ">" . $arreglo[$index]["cli_nombre"] . "</option>";
        }
        $lista .= "</select>";

        $lista = null;

        //echo ':)'; exit();

        $resultado = $anticipos->mostrarAnticipos($cond_cedula, 2, $placa);

        //echo "<pre>";var_dump($resultado);echo "</pre>"; exit();

        if (empty($resultado)) {
            header("Location: ../modulos/cuentaCobro.php?pj=6");
        }
        
        //traigo los conductores asociados a un vehiculo, de acuerdo a su estado y la 
        //placa
        $arreglo = $anticipos->retornarConductores($cond_placa);
        $conductores = '<select name="conductor" id="conductor" onchange="colocarCedula(this);" class="form-control input-sm" >'
                . '<option value="0">...</option>';
        if (empty($arreglo)) {
            $conductores .= "<option value=" . $_GET["pj"] . ">" . $cond_nombres . " " . $cond_apellidos . "</option>";
        } else {
            for ($index = 0; $index < count($arreglo); $index++) {
                $conductores .= "<option value=" . $arreglo[$index]["cond_identificacion"] . ">" . $arreglo[$index]["cond_nombres"] . " " . $arreglo[$index]["cond_apellidos"] . "</option>";
            }
            $conductores .= "</select>";
        }
        if( $_SESSION['iva'] == '' || $_SESSION['retefuente'] == '' || $_SESSION['reteica'] == ''  ){
            echo "<script>alert('OJO !!! Faltan parámetros de Impuestos del año.');</script> ";
        }
        //else{  print_r($_SESSION);}
        ?>
        <!DOCTYPE html>
        <!--
        To change this license header, choose License Headers in Project Properties.
        To change this template file, choose Tools | Templates
        and open the template in the editor.
        -->
        <html lang="es">
            <head>
                <meta charset="UTF-8">
                <title>Cuenta de cobro</title>
                <link rel="icon" href="../imagenes/favicon.ico">

                <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <?= retornarRecursosBootstrap(); ?>
                <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
                <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            

                <!-- Evitar cache -->
                <meta http-equiv="Expires" content="0">
                <meta http-equiv="Last-Modified" content="0">
                <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
                <meta http-equiv="Pragma" content="no-cache">
            </head>
            <body>            
                <form method="POST" action="../trafico/generarpdfctacobro.php" id="formularioCotizacion" > 
                    <div id="contenedor-index" class="row paddinMargin">
                        <div id="divImagenUsa" class="col-xs-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-xs-4">
                            <h1>CUENTA DE COBRO</h1>
                        </div> 
                        <div id="divDatosIniciales" class="col-sm-4">
                            <ul class="list-group">                                
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $_SESSION["nombre_usuario"]; ?></span>
                                    Usuario
                                </li>                               
                                <li class="list-group-item">
                                    <span class="badge"><?php echo date('Y-m-d'); ?></span>
                                    Fecha
                                    <?= '<input type="hidden" name="fechaApertura" value="' . date('Y-m-d') . '" />'; ?>
                                </li>
                                <li class="list-group-item">
                                    <span class="badge"><?php echo $numeroCtaCobro; ?></span>
                                    <input type='hidden' name='numeroCtaCobro' id='numeroCtaCobro' value='<?php echo $numeroCtaCobro; ?>' >
                                    Consecutivo
                                </li>
                            </ul>
                        </div>                    
                    </div>
                    <div id="datosCliente" class="panel panel-success small paddinMargin">
                        <div class="panel-heading">                                        
                            <h3 class="panel-title">Datos del propietario</h3>
                        </div>
                        <div class="table table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td>IDENTIFICACI&Oacute;N</td>
                                    <td><input type="text" disabled value="<?= $_GET["pj"]; ?>" class="form-control input-sm" />
                                        <input type="hidden" name="identificacion_conductor" id="identificacion_conductor"  value="<?php echo $_GET["pj"]; ?>"/>                                        
                                    <td>NOMBRES</td>
                                    <td><input type="text" value="<?= $cond_nombres; ?>" disabled="disabled" class="form-control input-sm" />
                                        <input type="hidden" name="nombre_conductor" id="nombre_conductor" value="<?php echo $cond_nombres; ?>" /></td>
                                    <td>APELLIDOS</td>
                                    <td><input type="text" value="<?= $cond_apellidos; ?>" disabled="disabled" class="form-control input-sm" />
                                        <input type="hidden" name="apellido_conductor" id="apellido_conductor" value="<?php echo $cond_apellidos; ?>" /></td>                                                                    
                                </tr>
                                <tr><td>DIRECCI&Oacute;N</td>
                                    <td><input type="text" value="<?= $cond_direccion; ?>" disabled="disabled" class="form-control input-sm" />
                                        <input type="hidden" name="direccion_conductor" id="direccion_conductor" value="<?php echo $cond_direccion; ?>" /></td>
                                    <td>TEL&Eacute;FONO</td>       
                                    <td><input type="text" value="<?= $cond_telefono; ?>" disabled="disabled" class="form-control input-sm" />
                                        <input type="hidden" name="telefono_conductor" id="telefono_conductor" value="<?php echo $cond_telefono; ?>" /></td>
                                    <td>PLACA</td>
                                    <td><input type="text" value="<?= $cond_placa; ?>" disabled="disabled" class="form-control input-sm" />
                                        <input type="hidden" name="placa" id="placa" value="<?php echo $cond_placa; ?>" /></td>
                                </tr>
                                <tr>
                                    <td>CIUDAD</td>
                                    <td><?= $mostrarCiudad; ?> </td>
                                    <td>CONDUCTORES</td>
                                    <td><?= $conductores; ?></td>
                                    <td>IDENTIFICACI&Oacute;N</td>
                                    <td><input type="text" value="" name="identificacion" id="identificacion" disabled="disabled" class="form-control input-sm" /></td>
                                </tr>
                            </table>
                            <input type="hidden" name="id_conductor" id="id_conductor" value="<?php echo $cli_id; ?>" />
                        </div> 
                    </div>   
                    <div id="mensajes"></div>
                    <div id="descripcionServicio" class="panel panel-success small">
                        <div class="panel-heading">                                        
                            <h3 class="panel-title">Descripci&oacute;n de los servicios</h3>
                        </div>                        
                        <div id="cuerpoDescripcionServicio" class="bs-component">
                            <table class="table">
                                <tr>
                                    <td><span class="color">No.</span></td>                                    
                                    <td><span class="color">NIT</span></td>                                
                                    <td class="tamanioTr"><span class="color" >EMPRESA</span></td> 
                                    <td><span class="color">No. GUIA</span></td>                                                                      
                                    <td class="tamanioTr"><span class="color">FECHA SERVICIO</span></td>                                        
                                    <td><span class="color">No. ANTICIPO</span></td>     
                                    <td><span class="color">FECHA ANTICIPO</span></td>     
                                    <td><span class="color">V/R ANTICIPO</span></td>     
                                    <td><span class="color">V/R SERVICIO</span></td>                                
                                    <td><span class="color">P.E.</span><input type="checkbox" name="chbPE" id="chbPE" /></td>
                                    <td><span class="color"></span></td>
                                </tr>

                                <?php
                                $guias = array();
                                $vlrTotAnt = 0;
                                $id_total_anticipo = @$resultado[0]['totalesanticipos_val_id'];
                                $id_total_anticipo_ = $id_total_anticipo;
                                $cicloFinal = 0;
                                $fpe = null;
                                $clase = null;
                                $guiasRepetidas = array();
                                $guiasMostrar = null;
                                $mensaje = 0;
                                $fechaServicio = null;
                                $idservicios = null;
                                $valAntId = null;

                                for ($i = 1; $i <= count($resultado); $i++) {
                                    $b = $i - 1;
                                    if (@$resultado[$b]["nitEmpresa"] <> "") {
                                        $vlrTotServ = $resultado[0]["val_total"];
                                        $vlrTotAnt = $vlrTotAnt + @intval($resultado[$b]["val_valorAdelanto"]);
                                        $vts = $resultado[$b]["val_total"];
                                        /* 201809061642
                                         * Verificar si hay guias repetidas
                                         */

                                         // adiciono el numeroAnticipo a la guia - RMG 2024-05-17
                                        if (!in_array($resultado[$b]["val_numeroGuia"]."_".$resultado[$b]["val_numeroAnticipo"], $guiasRepetidas)) {
                                            $guiasRepetidas[$i] = $resultado[$b]["val_numeroGuia"]."_".$resultado[$b]["val_numeroAnticipo"];
                                        } else {
                                            $guiasMostrar = $guiasMostrar . ',' . $resultado[$b]["val_numeroGuia"]."_".$resultado[$b]["val_numeroAnticipo"];
                                            $mensaje = 1;
                                        }

                                        /* 201809061543 Es para la prueba de entrega
                                         * $fpe=Fecha Prueba Entrega
                                         */
                                        $cantidadGuiones = mb_substr_count($resultado[$b]["val_numeroGuia"], '-');
                                        if ($cantidadGuiones >= 1) {
                                            $guias = explode('-', $resultado[$b]["val_numeroGuia"]);
                                            for ($index1 = 0; $index1 < count($guias); $index1++) {
                                                $fpe = $anticipos->retornarFechaPruebaEntrega($guias[$index1]);
                                            }
                                        } else {
                                            $fpe = $anticipos->retornarFechaPruebaEntrega($resultado[$b]["val_numeroGuia"]);
                                        }

                                        if ($fpe === 0) {
                                            $clase = "#F6CECE";
                                        } else {
                                            $clase = "#CEF6CE";
                                        }

                                        $idservicios .= $resultado[$b]["idservicio"] . '-';
                                        $valAntId .= $resultado[$b]["val_ant_id"] . '-';

                                        /*
                                         * 201809111145 Traer la fecha del servicio
                                         * Antes se mostraba la fecha del anticipo, no cambio los valores de las cajas para 
                                         * hacer más rápido el cambio.
                                         */

                                        $fechaServicio = $anticipos->retornarFechaServicio($resultado[$b]["idservicio"]);
                                        $fechaServicio = $fechaServicio[0]["fecha"];
                                        $numeroManifiesto = $anticipos->retornarNumeroManifiesto($resultado[$b]["idservicio"]);
                                        $numManifiesto = $numeroManifiesto["manifiesto"];
                                        /* comerciales (RMG) */
                                        //echo " Servicio:".$idservicios." vlrTotServ:".$vlrTotServ." vts:".$vts;

                                        if ($numManifiesto === '0') {
                                            ?>
                                            <tr bgcolor="<?= $clase; ?>">
                                                <td><input type="button" value="<?= $resultado[$b]["idservicio"]; ?>" size="1" class='btn btn-success btn-xs' onclick="enviarACancelar(<?= $resultado[$b]["idservicio"]; ?>,<?= $_SESSION["emp_cedula"] ?>);" /></td>                                            
                                                <td colspan="8"><p class="mb-0" >El servicio no registra n&uacute;mero de manifiesto. Por favor presione <a href="../modulos/gestionarSeguimiento.php?idservicio=<?= $resultado[$b]["idservicio"]; ?>"><strong>aqu&iacute;</strong></a> para ingresarlo</p></td>
                                            </tr>     
                                            <?php
                                        } else {
                                            ?>                                    
                                            <tr bgcolor="<?= $clase; ?>">
                                                <td><input type="button" value="<?= $resultado[$b]["idservicio"]; ?>" size="1" class='btn btn-success btn-xs' onclick="enviarACancelar(<?= $resultado[$b]['idservicio']; ?>,<?= $_SESSION['emp_cedula'] ?>);" /></td>                                            
                                                <td><input type="text" value="<?= $resultado[$b]["nitEmpresa"]; ?>" disabled size="<?= $tamanio; ?>"/></td>                                
                                                <td><input type="text" value="<?= $resultado[$b]["nombreEmpresa"]; ?>" class="input1TablaDescripcionServicio" disabled /></td>                                
                                                <td><input type="text" value="<?= $resultado[$b]["val_numeroGuia"]; ?>" disabled size="<?= $tamanio; ?>"/></td>
                                                <td><input type="text"  value="<?= $fechaServicio; ?>" class="input1TablaDescripcionServicio"  disabled /></td>
                                                <td><input type="text" value="<?= $resultado[$b]["val_numeroAnticipo"]; ?>" size="<?= $tamanio; ?>" disabled /></td>
                                                  <!-- ADICION DE LA FECHA ANTICIPO - RMG 2024-05-17 -->
                                                <td><input type="text" value="<?= $resultado[$b]["val_fechaAnticipo"]; ?>" size="<?= $tamanio; ?>" disabled /></td>
                                                <td><input type="text" value="<?= $resultado[$b]["val_valorAdelanto"]; ?>" size="<?= $tamanio; ?>" disabled />
                                                    <input type="hidden" name="valorServicio<?= $resultado[$b]["val_ant_id"]; ?>" id="valorServicio<?= $resultado[$b]["val_ant_id"]; ?>" value="<?= $resultado[$b]["val_valorAdelanto"]; ?>" />
                                                </td>
                                                <td>                                                   
                                                    <input type="text" value="<?= $vts; ?>" size="<?= $tamanio; ?>" disabled size="20"/>  
                                                    <!-- le quito el hidden para ver su contenido, reverso, agrego atributo title con el idservicio -->                                                      
                                                    <input type="hidden" name="valorTotalServicio<?= $resultado[$b]["val_ant_id"]; ?>" id="valorTotalServicio<?= $resultado[$b]["val_ant_id"]; ?>" value="<?= $vts; ?>" onblur="cambiarPorcentaje(this);"  title="<?= $resultado[$b]["val_numeroGuia"]; ?>" />
                                                </td>
                                                    <!-- le cambio el title de idservicio a val_numeroGuia en el checkbox (RMG-2024-05-30) -->                                              
                                                <td><input type="checkbox" name="serv-<?= $resultado[$b]["idservicio"]; ?>" id="serv-<?= $resultado[$b]["val_ant_id"]; ?>" onchange="retirarchbPE()" title="<?= $resultado[$b]["val_numeroGuia"]; ?>"></td>
                                                <td>
                                                    <?php
                                                        $arregloTrasabilidad=$anticipos->retornarPosibleFechaPago($resultado[$b]["val_numeroGuia"],$resultado[$b]["idservicio"]);
                                                        $guia=$resultado[$b]["val_numeroGuia"];
                                                        $idservicio=$resultado[$b]["idservicio"];
                                                        if(count($arregloTrasabilidad)>0){
                                                            echo '<span style="color:red;">';
                                                            $anticipos->crearAdvertencia($guia,$idservicio);
                                                            echo 'Advertencia. Se encontró un registro de pago de ésta guía ya registrado. Por favor revise en contabilidad y evite realizar el pago de esta guía hasta estar 100% segur@!</span>';
                                                        }else{
                                                            echo '<span></span>';
                                                        }
                                                    ?>
                                                </td>
                                            </tr>                                        
                                            <?php
                                        }
                                    }
                                }

                                $idservicios = substr($idservicios, 0, -1);
                                $valAntId = substr($valAntId, 0, -1);
                                ?>      
                                
                                <tr>
                                    <td>&nbsp;</td>                                    
                                    <td>&nbsp;</td>
                                    <td class="tamanioTr">&nbsp;</td>
                                    <td class="tamanioTr">&nbsp;</td>
                                    <td class="tamanioTr">&nbsp;</td>
                                    <td width="100px">&nbsp;</td>
                                    <td width="70px" >BASE</td>                                        
                                    <td width="150px">
                                        <input type="text" name="valorServicio" id="valorServicio" class="input2TablaDescripcionServicio" value=""  size="<?= $tamanio; ?>"/>
                                        <input type="hidden" name="base" id="base" value="<?= $_SESSION['base'] ?>" />
                                    </td>   
                                    <td></td>                                 
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><label class="eti_imp">RTE FUENTE <?= $_SESSION["retefuente"]; ?>%</label></td>
                                    <td><input type="text" name="totalRteFuente" id="totalRteFuente" class="input2TablaDescripcionServicio" value="" size="<?= $tamanio; ?>" />
                                        <input type="hidden" name="rteFuente" id="rteFuente" value="<?= $_SESSION['retefuente'] ?>" />
                                    </td>
                                    <td></td>                                 
                                </tr>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>                                    
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><label class="eti_imp">RTE ICA <?php echo $_SESSION["reteica"]; ?>%</label></td>                                        
                                    <td><input type="text" name="totalRteIca" id="totalRteIca" class="input2TablaDescripcionServicio" value=""  size="<?= $tamanio; ?>"/>
                                        <input type="hidden" name="rteIca" id="rteIca" value="<?= $_SESSION['reteica'] ?>" />
                                    </td>    
                                    <td></td>                                                                 
                                </tr> 
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><label class="eti_imp">T. IMPUESTOS</label></td>                                        
                                    <td><input type="text" name="totalImpuestos" id="totalImpuestos" class="input2TablaDescripcionServicio" value="" size="<?= $tamanio; ?>" /></td>                                    
                                    <td></td>
                                </tr> 
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><label class="eti_imp">T. ANTICIPOS</label></td>                                        
                                    <td><input type="text" name="totalAnticipos" id="totalAnticipos" class="input2TablaDescripcionServicio" value="" size="<?= $tamanio; ?>" /></td>                                    
                                    <td></td>
                                </tr>

                                <tr>
                                <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>T. DEDUCIBLES</td>                                        
                                    <td><input type="text" name="totalDeducibles" id="totalDeducibles" class="input2TablaDescripcionServicio" value="" size="<?= $tamanio; ?>" /></td>                                    
                                    <td></td>
                                </tr>
                                <tr>
                                    <td></td>
                                    <td>&nbsp;</td>                                                                                                                        
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>NETO A PAGAR</td>                                        
                                    <td><input type="text" name="totalNetoPagar" id="totalNetoPagar" class="input2TablaDescripcionServicio" value="" size="<?= $tamanio; ?>" /></td>                                    
                                    <td></td>
                                </tr>  
                                <tr>
                                    <td></td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td><input type="button" name="botontotal" id="botontotal" class="botonTotal" value="TOTAL" /></td>
                                    <td>&nbsp;</td>                                    
                                    <td></td>
                                </tr>
                                <tr>
                                    <td><span class="color">VALOR EN LETRAS</span></td>
                                    <td colspan="15"><input type="text" id="valorLetras" name="valorLetras" class="form form-control" /></td>
                                </tr>
                            </table>                        
                        </div>
                        <!-- cambio de hidden a text para visualizar los datos. Reverso. (RMG) -->
                        <input type="text" id="serviciosCtaCobro" name="serviciosCtaCobro" value="<?= $idservicios ?>" readonly />
                        <input type="hidden" id="valAntId" name="valAntId" value="<?= $valAntId; ?>" /> 

                        <!-- visualizo hidden a text (RMG) cambiando la ubicación para mejor visualización --> 
                         <input type="text" id="serviciosAPagar" name="serviciosAPagar" value="0" placeholder="linea 562" readonly />      

                         <!-- adiciono campo text para contener las guias (RMG-2024-05-30) --> 
                         <input type="text" id="guiasAPagar" name="guiasAPagar" value="0" placeholder="linea 565" size="80" readonly />
                    </div>                  
                    <div id="botones">
                        <button type="button" value="REGRESAR" id="botonRegresar" name="botonRegresar" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>
                        <button type="submit" value="GENERAR PDF" id="botonGenerarPdf" name="botonGenerarPdf" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > GENERAR CTA COBRO <img src="../imagenes/pdf_16.png"></button>
                        <button type="button" value="LISTAR PROPIETARIOS/PLACAS" id="listarPropietariosPlacas" name="boton" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > PROPIETARIOS/PLACAS <img src="../imagenes/empleados_16.jpg"></button>
                        <button type="button" value="SALIR" id="botonSalir" name="botonSalir" class="btn btn-success btn-ls botonPropio" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>
                    </div>

                </form>    
    
                <script src="../js/js_cuentaCobro.js?n=<?= rand(0, 3) ?>" type="text/javascript"></script>
                <script src="../js/cancelarServicio.js" type="text/javascript"></script>
                <script src="../js/numeros_letras.js" type="text/javascript"></script>
                <script src="../js/bootstrap.min.js" type="text/javascript"></script>
                <script src="../js/accionesenprograma.js" type="text/javascript"></script>
                <script src="../js/cambioColores.js" type="text/javascript"></script>

            </body>
        </html>
        <?php
        if ($conductorNo == 1) {
            echo '<script>alert("EL CONDUCTOR NO EXISTE, POR FAVOR CREARLO");</script>';
        }
        if (@$_GET["d"] == "1") {
            echo '<script>alert("No se enviaron adelantos para actualizar en este conductor");</script>';
        }

        if ($mensaje === 1) {
            $guiasMostrar = substr($guiasMostrar, 1);
            echo '<script>alert("!!!      Cuidado      !!! \n Se han encontrado las siguientes guías repetidas: \n ' . $guiasMostrar . ' \n Por favor realizar gestión de eliminación de servicios y/o \n No seleccionar la casilla de dichas guías para que no se sumen en la cuenta de cobro. \n Gracias");</script>';
        }
    }
}

$conexion = null;


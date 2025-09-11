<?php
session_start();

include_once '../clases/funcionesVarias.php';
include '../clases/anticipos.php';
include '../clases/CifrasEnLetras.php';

$departamentos = array('SISTEMAS', 'GERENCIA');

if (is_null($_SESSION["rol_id"])) {
    finalizarSesion();
} else if (in_array($_SESSION["departamento"], $departamentos) === false) {
    finalizarSesion();
} else {
    $anticipos = new anticipos();
    $anticipo = $anticipos->retornarDatosAnticipo($_GET["idanticipo"]);
    $numeroAnticipo = $anticipos->retornarNumeroAnticipo();
    $totalAnticipo = 0;
    $valorServicio = 0;
    $valorAReducir = 0;
    $totalDeducible = 0;
    
    $cifraEnLetra=new CifrasEnLetras();    

    if (!isset($_GET["idanticipo"]) || $_GET["idanticipo"] === '') {
        header("Location: ../modulos/index.php?msj=11");
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
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
            <meta charset="iso-8859-1">
            <title>Sobreanticipos</title>
            <link rel="icon" href="../imagenes/camion256.png">
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>
            <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>
            <link href="../bootstrap-3.3.7-dist/css/bootstrap.min.css" rel="stylesheet">            
            <script src="../js/js_sobreanticipos.js?n=<?= rand(0,3)?>" type="text/javascript"></script> 
            <script src="../js/numeros_letras.js" type="text/javascript"></script>
            <script src="../js/bootstrap.min.js" type="text/javascript"></script>  
            <script src="../js/jquery.number.min.js" type="text/javascript"></script>
            <script src="../js/accionesenprograma.js" type="text/javascript"></script>
            <script src="../js/cambioColores.js" type="text/javascript"></script>
            <!-- Evitar cache -->
            <meta http-equiv="Expires" content="0">
            <meta http-equiv="Last-Modified" content="0">
            <meta http-equiv="Cache-Control" content="no-cache, mustrevalidate">
            <meta http-equiv="Pragma" content="no-cache">
        </head>
        <body>            
            <div id="contenedor">
                <div id="contenedor-index">   
                    <div id="contenedor-index" class="row">                       
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGO" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1>Agregar anticipo</h1>
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
                    <form method="post" action="../trafico/generarSobreanticipo.php" name="formuarlio_index" >                        
                        <div class="panel panel-success">
                            <div class="panel-heading">
                                <h3 class="panel-title"><strong>Agregar Anticipo</strong></h3>
                            </div>
                            <div class="panel-body">
                                <table class='table table-striped'>
                                    <tr><td>N&uacute;mero servicio</td><td><input type='button' class='btn btn-success btn-xs' id="idservicio" name="idservicio" value='<?= $anticipo[0]["idservicio"] ?>'/>
                                            <input type='hidden' id="idservicio" name="idservicio" value='<?= $anticipo[0]["idservicio"] ?>'/>
                                        <input type="hidden" id="conductor" name="conductor" value="<?= $anticipo[0]["cond_identificacion"] ?>"/>
                                        <input type="hidden" id="val_id_empresa" name="val_id_empresa" value="<?= $anticipo[0]["val_id_empresa"] ?>" />
                                        </td><td>N&uacute;mero sobre-anticipo</td><td><input type="hidden" name="numeroSobreanticipo" id="numeroSobreanticipo" value="<?= $numeroAnticipo["numeroAnticipo"]+1 ?>" /><?= $numeroAnticipo["numeroAnticipo"]+1 ?></td>
                                        <td>Guías</td>
                                        <td><?= $anticipo[0]["val_numeroGuia"] ?>
                                            <input type="hidden" id="val_numeroGuia" name="val_numeroGuia" value="<?=$anticipo[0]["val_numeroGuia"] ?>" />
                                        </td></tr>
                                    <tr><td colspan='7' style='text-align: center;' ><strong>DATOS PERSONA BENEFICIARIA DEL PAGO</strong></td></tr>
                                    <tr><td>Nombre propietario</td><td><?= $anticipo[0]["nombresPropietario"] ?>
                                            <input type="hidden" id="nombresPropietario" name="nombresPropietario" value="<?= $anticipo[0]["nombresPropietario"] ?>" />
                                        </td><td>Placa</td><td><?= $anticipo[0]["placa"] ?>
                                            <input type="hidden" id="placa" name="placa" value="<?= $anticipo[0]["placa"] ?>" />
                                        </td><td>Empresa</td><td> <?= $anticipo[0]["cli_nombre"] ?>
                                            <input type="hidden" id="nombreEmpresa" name="nombreEmpresa" value="<?= $anticipo[0]["cli_nombre"] ?>" />
                                        </td></tr>
                                    <tr><td colspan='7' style='text-align: center;' ><strong>DETALLE ANTICIPOS</strong></td></tr>
                                    <tr><td>Anticipo</td><td>Fecha</td><td>V/r Anticipo</td><td></td><td>V/r Servicio</td><td></td><td></td></tr>
                                    <?php
                                    $valorServicio = $anticipo[0]["valorservicio"];

                                    for ($index = 0; $index < count($anticipo); $index++) {
                                        echo "<tr>"
                                        . "<td>" . $anticipo[$index]["val_numeroAnticipo"] . "</td>"
                                        . "<td>" . $anticipo[$index]["val_fechaAnticipo"] . "</td>"
                                        . "<td>" . $anticipo[$index]["val_valorAdelanto"] . "</td>"
                                        . "<td></td>"
                                        . "<td>" . $anticipo[$index]["valorservicio"] . "</td>"
                                        . "<td></td>"
                                        . "<td></td>"
                                        . "</tr>";
                                        $totalAnticipo = $totalAnticipo + $anticipo[$index]["val_valorAdelanto"];
                                    }

                                    $totalDeducible = ($valorServicio - $totalAnticipo) * 0.50;
                                    $totalAPagar = $valorServicio - ($totalAnticipo + $totalDeducible);
                                    $totalAnticipos = $totalDeducible + $totalAnticipo;
                                    $porPago=($totalAPagar*100)/$valorServicio;
                                    ?>
                                    <tr><td colspan='7' style='text-align: center;' ><strong>VALORES NUEVOS ANTICIPOS</strong></td></tr>                                    
                                    <tr><td></td>                                        
                                        <td>V/r sobreanticipo</td>
                                        <td><input type="number" id="val_valorAdelanto" name="val_valorAdelanto" class="form-control form-control-sm"  value="<?= $totalDeducible ?>"/></td>
                                        <td>Valor en letras</td>
                                        <td colspan="2"><input type="text" id="valorLetras" name="valorLetras" class="form-control form-group-sm" value="<?= strtoupper($cifraEnLetra->convertirCifrasEnLetras($totalDeducible)); ?> PESOS M/TE" /></td>
                                        <td></td>
                                    </tr>                                    
                                    <tr>
                                        <td><input type="hidden" id="valorServicio" name="valorServicio" value="<?= $anticipo[0]["valorservicio"] ?>" /></td>
                                        <td><strong>Total anticipos</strong><input type="hidden" id="valorAdelantado" name="valorAdelantado" value="<?= $totalAnticipo ?>" /></td>                                        
                                        <td><input type="number" id="totalAdelantos" name="totalAdelantos" class="form-control form-control-sm" value="<?= $totalAnticipos ?>" /></td>
                                        <td><strong>Saldo</strong></td>
                                        <td><input type="number" id="totalAPagar" name="totalAPagar" class="form-control form-control-sm" value="<?= $valorServicio - $totalAnticipos ?>" /></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        
                                    </tr>
                                </table>                                
                            </div>
                            <div id="mensajesGenerales"></div>                            
                            <?php
                            
                            $disabled='';
                            
                            if((int)$totalAnticipo===(int)$valorServicio){
                                echo '<div class="alert alert-dismissible alert-success"><strong>¡Cuidado!</strong> El valor del <strong>sobreanticipo</strong> es <strong>igual</strong> que el <strong>valor del servicio</strong>. Se pagaría el servicio por completo</div>';
                                $disabled='disabled="disabled"';
                            }else{
                                echo '<div id="mensajes"><div class="alert alert-dismissible alert-success">Aún queda el '.substr($porPago, 0,4).'% del valor por pagar al propietario</div></div>';                                
                                $disabled='';
                            }                            
                            ?>
                        </div>
                        <div class="panel-body">                        
                            <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                            
                            <button name="boton" id="botonGenerarSobreAnticipo" type="submit" class="btn btn-success btn-ls botonPropio" value="GENERAR SOBREANTICIPO" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" <?= $disabled ?> > AGREGAR ANTICIPO <img src="../imagenes/pdf_16.png"></button>
                            <button name="boton" id="botonSalir" type="button" class="btn btn-success btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>               
                        </div>
                    </form>  
                </div>
            </div>             
        </body>
    </html>
    <?php
}
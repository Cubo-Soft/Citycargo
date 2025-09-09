<?php
session_start();

include_once '../clases/serviciosporcancelar.php';
include_once '../clases/funcionesVarias.php';

if (is_null($_SESSION["rol_id"])) {
    header("Location: ../index.php?null=null");
} else {

    $servporcancelar = new serviciosporcancelar();
    $tituloPagina='';
    $h1='';
        
    if(isset($_GET["novedad"])){
        if($_GET["novedad"]==='1'){
             $serviciosCancelar = $servporcancelar->retornarServiciosPorCancelar(1);
             $tituloPagina='Con novedad';
             $h1='Servicios con novedad';
        }else if($_GET["novedad"]==='2'){
            $serviciosCancelar = $servporcancelar->retornarServiciosPorCancelar(2);
            $tituloPagina='Por cancelar';
             $h1='Servicios por cancelar';
        }else{
            header("Location: index.php");
        }
    }else{
        header("Location: index.php");
    }
    //var_dump($serviciosFacturar);
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
            <title><?= $tituloPagina ?></title>
            <link rel="icon" href="../imagenes/favicon.ico">
            
            <link href="../css/css2.css" rel="stylesheet" type="text/css" />
            <script src="../js/jquery-1.11.2.js" type="text/javascript"></script>            
            <?= retornarRecursosBootstrap(); ?>
            <link href="../css/jquery-ui-1.7.2.custom.css" rel="stylesheet" type="text/css"/>        
            
            <script src="../exportarexcel/src/jquery.table2excel.js" type="text/javascript"></script>            
            <script src="../js/js_serviciosporcancelar.js?n=<?= rand(0,3)?>" type="text/javascript"></script>             
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
                        <div id="divImagenUsa" class="col-lg-4"><img src="../imagenes/logocity.jpg" alt="CITYCARGI" id="imagen_usapostal_cotizacion"/></div>
                        <div id="texoDocumento" class="col-lg-4">
                            <h1><?= $h1 ?></h1>
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
                    <div id="mensajes">

                    </div>                    
                    <div class="panel panel-success">
                        <div class="panel-heading">
                            <h3 class="panel-title">Listado</h3>
                        </div>
                        <div class="panel-body altura">
                            <div class="col-lg-12 panel" id="divFacturas" >
                                <table class="table table-condensed table-hover ">
                                    <thead>
                                        <tr>
											<th></th>
                                            <th>Servicio</th>
                                            <th>Empleado</th>
                                            <th>Fecha solicitud.</th>
                                            <th>Motivo</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $cantidad = count($serviciosCancelar);
                                        $idservicio=null;
                                        for ($index1 = 0; $index1 < $cantidad; $index1++) {
                                            $idservicio=$serviciosCancelar[$index1]["idservicio"];
                                            ?>
                                            <tr>
												<td><?= $index1+1 ?></td>
                                                <td><button id="idservicio" name="idservicio" value="<?= $idservicio ?>" class='btn btn-success btn-xs' onclick="enviarServicio(1,<?= $serviciosCancelar[$index1]["idservicio"] ?>);" ><?= $serviciosCancelar[$index1]["idservicio"] ?></button></td>
                                                <td><?= $serviciosCancelar[$index1]["nombreEmpleado"] ?></td>
                                                <td><?= $serviciosCancelar[$index1]["fecha"] ?></td>
                                                <td><?= $serviciosCancelar[$index1]["motivo"] ?></td>
                                                <td><button id="botonCancelarMensaje" name="<?= $serviciosCancelar[$index1]["id"] ?>" onclick="borrarMensaje('<?= $serviciosCancelar[$index1]["id"] ?>','<?= $serviciosCancelar[$index1]["idservicio"] ?>');" class="btn btn-warning btn-sm" style="height: 20px;"  >X</button></td>
                                            </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div> 
                        </div>
                    </div>                        
                    <div class="panel-body">                        
                        <button name="boton" id="botonRegresar" type="button" class="btn btn-success btn-ls botonPropio" value="REGRESAR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" > MEN&Uacute; PRINCIPAL <img src="../imagenes/left_16.png"></button>                        
                        <button name="boton" id="botonSalir" type="button" class="btn btn-succes btn-ls botonPropio" value="SALIR" onmouseleave="colorSale(this);" onmouseenter="colorEntra(this);" >SALIR <img src="../imagenes/salir.png"> </button>               
                    </div>                    
                </div>
            </div>             
        </body>
    </html>
    <?php
}
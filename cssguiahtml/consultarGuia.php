<?php

//asegurarse de que la consulta proviene del servidor
/*if($_SERVER['REMOTE_ADDR']!=='186.180.13.188'){
    header("Location: https://citycargo.com.co/");
    exit();
}
*/

//importar la clase de conexion
include_once 'CL_conexion2.php';

$headPagina = ' <html>
<head>
    <title>Seguimientos</title>
    <link rel="icon" href="../imagenes/camion256.png">
    <link rel="stylesheet" href="consulta.css">
    <script src="bootstrap.js">
    </script>';
//$headPagina .= retornarRecursosBootstrap();
$headPagina .='<script>
function abrirRecurso(recurso){
    window.open(recurso,"_blank");
}
</script>';
$headPagina .= '</head>
<body>
<div style="text-align: center;">
    <img src="logo.png" alt="Logo CITYCARGO" width="330" height="230"">
</div>
<div style="text-align: center; font-size: 30">
    <strong>Estado Actual de la Guia</strong>
</div>';

$footerPagina = '</body>
</html>';

$formulario = '<form action="consultarGuia.php" method="post">

<center>
<table cellspacing="4">
    <tr>
        <td>NIT Empresa</td>
        <td width="180" >
            <input type="number" class="form form-control" name="nit" id="nit">
        </td>
    </tr>
    <tr>
        <td>Guía a consultar</td>
        <td>
            <input type="number" class="form form-control" name="guia" id="guia">
        </td>
    </tr>
    <tr>
        <td align="center" colspan="2"  >
            <input type="submit" class="btn btn-success" value="Consultar" />
        </td>
    </tr>
</table>
</center>
</form>';

//valida si esta seteada la variable guia que llega por post
if (isset($_POST['guia']) && is_numeric($_POST['guia'])) {

    //abre la conexion a la base de datos
    $OB_conexion = new CL_conexion2();

    //consulta a realizar

    $consulta = "SELECT guia,fechaHora,estadoseguimiento FROM seguimiento, servicio_guias "
               ."WHERE seguimiento.guia=" . $_POST['guia'] . " AND seguimiento.guia=servicio_guias.numeroGuia "
               ."AND servicio_guias.nit=" . $_POST['nit'] . " ORDER BY servicio_guias.fechaPruebaEntrega desc LIMIT 1";
    //$retorno tiene los valores de retorno de la funcion retornar de la clase CL_conexion2()
    $retorno = $OB_conexion->retornar($consulta);

    //$pruebasEntrega tiene los valores de retorno de la funcion retornar de la clase CL_conexion2()
    $consulta="SELECT ruta FROM pruebasentrega WHERE guia=".$_POST["guia"].";";
    $pruebasEntrega=$OB_conexion->retornar($consulta);
    
    //destruye la conexion a la base de datos
    $OB_conexion = null;
    //destruye el contenido de la variable $_POST["guia"] 
    $_POST["guia"] = null;

    if (count($retorno) > 0) {
        //inicia construcción de la página
        echo $headPagina;
        $tabla = '<center><form action="consultarGuia.php" method="post">';
        $tabla .= '<h3>Transacción</h3>';
        $tabla .= '<table border="2">';
        $tabla .= '<thead>';
        $tabla .= '<tr bgcolor="#337AB7">
			<th width="30" style="color: white;size: 120%;text-align: center">#</th>
			<th width="70" style="color: white;size: 120%;text-align: center">Guía</th>
			<th width="170" style="color: white;size: 120%;text-align: center">Fecha del seguimiento</th>
			<th width="300" style="color: white;size: 120%;text-align: center">Estado</th></tr>';
        $tabla .= '</thead>';
        $tabla .= '<tbody>';
        for ($i = 0; $i < count($retorno); $i++) {
            $tabla .= '<tr>
			<td style="text-align: center">' . ($i + 1) . '</td>
			<td style="text-align: center">' . $retorno[$i]["guia"] . '</td>
			<td style="text-align: center">' . $retorno[$i]["fechaHora"] . '</td>
			<td style="text-align: center">' . $retorno[$i]["estadoseguimiento"] . '</td>
			</tr>';
        }
        
        $tabla .= '</tbody>';
        $tabla .= '</table>';

        $tabla .='<h3>Pruebas de entrega</h3>';

        //$tabla .= '<table class="table table-sm table-hover table-bordered">';
        $tabla .= '<table ">';
        $tabla .= '<thead>';

        $tabla .='<tr>';
        for ($i=0; $i < count($pruebasEntrega); $i++) { 
            $tabla .='<th>'.($i+1).'</th>';
        }
        $tabla .='</tr>';
        
        $tabla .= '</thead>';
        $tabla .= '<tbody>';

        $tabla .='<tr>';
        for ($i=0; $i < count($pruebasEntrega); $i++) { 
            $tabla .='<td>';
            $tabla .= '<img src="'.substr($pruebasEntrega[$i]["ruta"],1).' " width="230" height="150" onclick="abrirRecurso(this.src)"/>';
            $tabla .='</td>';
        }
        $tabla .='</tr>';

        $tabla .= '</tbody>';
        $tabla .= '</table>';
        $tabla .= '<tr><td><input type="submit" class="btn btn-success" value="Consultar otra guía"></td></tr>';
        $tabla .= '</form>';
        echo $tabla;

        echo $footerPagina;
        //finaliza construcción de la página
    } else {
        echo $headPagina;
        echo $formulario;
        echo $footerPagina;
    }
} else {
    echo $headPagina;
    echo $formulario;
    echo $footerPagina;    
}

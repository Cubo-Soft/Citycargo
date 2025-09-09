<?php 

include_once '../clases/CL_dias_configuracion.php';

$OB_dias_configuracion=new CL_dias_configuracion();

if($_POST["caso"]==='1'){

    $OB_dias_configuracion->actualizarDiasConfiguracion(null, 1);
    
    $parametro1["estado"]=$_POST["estado"];
    $parametro1["id"]=$_POST["id"];

    echo json_encode($OB_dias_configuracion->actualizarDiasConfiguracion($parametro1, 3));

}

if($_POST["caso"]==='2'){

    $parametro1["cantidad"]=$_POST["cantidad"];
    $parametro1["id"]=$_POST["id"];

    echo json_encode($OB_dias_configuracion->actualizarDiasConfiguracion($parametro1, 4));

}
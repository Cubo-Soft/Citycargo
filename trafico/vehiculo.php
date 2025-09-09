<?php

include '../clases/vehiculo.php';

$vehiculo = new vehiculos();

$caso=(int)$_POST["caso"];

if ($caso === 1) {    
    $vehiculo->retornarEstadoPlaca($_POST["placa"]);
}

if($caso===2){    
    echo json_encode($vehiculo->crearPlaca($_POST["placa"],$_POST["estado"],$_POST["marca"],$_POST["modelo"],$_POST["tipocarroceria"],$_POST["capacidadcarga"],$_POST["ancho"],$_POST["largo"],$_POST["alto"],$_POST["tipovehiculo"],$_POST["reportar_novedad"]));
}

if($caso===3){
    $vehiculo->modificarPlaca($_POST["placa"],$_POST["estado"],$_POST["marca"],$_POST["modelo"],$_POST["tipocarroceria"],$_POST["capacidadcarga"],$_POST["ancho"],$_POST["largo"],$_POST["alto"],$_POST["tipovehiculo"],$_POST["reportar_novedad"]);
}

if($caso===4){    
    $vehiculo->retornarPropietario($_POST["placa"]);
}

if($caso===5){
    $vehiculo->retornarConductoresTipoVehiculo($_POST["tipo"], $_POST["carroceria"], $_POST["capacidadInicial"], $_POST["capacidadFinal"]);
}


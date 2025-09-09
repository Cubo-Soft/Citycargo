<?php

include '../clases/conductores.php';
$conductores = new conductores();

$caso = (int) $_POST["caso"];

if ($caso === 1) {
    $conductores->consultarConductor($_POST["cedulaConductor"]);
}

if ($caso === 2) {
    $conductores->crearConductor($_POST["email"],$_POST["cedulaConductor"], $_POST["nombresConductor"], $_POST["apellidosConductor"], $_POST["direccionConductor"], $_POST["telefonoConductor"], $_POST["municipio"], $_POST["perfil"], $_POST["listaPlacas"],$_POST["reportar_novedad"]);
}

if ($caso === 5) {
    echo $conductores->modificarConductor($_POST["email"],$_POST["cond_id"], $_POST["cedulaConductor"], $_POST["nombresConductor"], $_POST["apellidosConductor"], $_POST["direccionConductor"], $_POST["telefonoConductor"], $_POST["municipio"], $_POST["perfil"], $_POST["listaPlacas"], $_POST["estadoConductor"], $_POST["estadoRelacion"],$_POST["reportar_novedad"]);
}

if ($caso === 6) {
    echo $conductores->agregarPlacaPropietario($_POST["email"],$_POST["cond_id"], $_POST["cedulaConductor"], $_POST["nombresConductor"], $_POST["apellidosConductor"], $_POST["direccionConductor"], $_POST["telefonoConductor"], $_POST["municipio"], $_POST["perfil"], $_POST["listaPlacas"], $_POST["estadoConductor"], $_POST["estadoRelacion"]);
}

if($caso===8){
    echo $conductores->cambiarTelefonoConductor($_POST["telefono"], $_POST["cond_id"]);
}

if($caso===9){
    echo json_encode($conductores->retornarDatosPropietariosConductores($_POST["placa"]));
}
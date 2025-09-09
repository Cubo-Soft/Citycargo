<?php

include '../clases/conexion.php';

$con = new Conexion();

//CLOR Calle Origen
$CLOR = $_POST['CLOR'];
//CLDE Calle Destino
$CLDE = $_POST['CLDE'];
//Calle Origen Sur
$CLORS = $_POST['CLORS'];
//Calle Destino Sur
$CLDES = $_POST['CLDES'];

//Tamaño Cuadra
$TAMCUA = 100;

//Calculo el total de metros para las calles
if ($CLORS === $CLDES) {
    $TOTCL = ($CLOR - $CLDE) * $TAMCUA;
} else {
    $TOTCL = ($CLOR + $CLDE) * $TAMCUA;
}

//llamo la función negativo para que evalue si el valor de la operación anterior 
//da como resultado un número negativo, si es así; que lo convierta en positivo

$TOTCL=negativo($TOTCL);

//CROR Carrera Origen
$CROR = $_POST['CROR'];
//CRDE Carrera Destino
$CRDES = $_POST['CRDE'];
//CRORE Carrera Origen Este
$CRORE = $_POST['CRORE'];
//CRDEE Carrera Destino Este
$CRDEE = $_POST['CRDEE'];

//Calculo el total de metros para las carreras
if ($CRORE === $CRDEE) {
    $TOTCR = ($CROR - $CRDES) * $TAMCUA;
} else {
    $TOTCR = ($CROR + $CRDES) * $TAMCUA;
}

//llamo la función negativo para que evalue si el valor de la operación anterior 
//da como resultado un número negativo, si es así; que lo convierta en positivo

$TOTCR=negativo($TOTCR);

$metros = $TOTCL + $TOTCR;

//aumenta la cantidad de cuadras teniendo en cuenta que hay entre las cuadras
//las letras a,b,c,d,e... etc.
//$metros=$metros*1.10;

if($metros<=2500){
    $consulta="select tarifa,domicilioCarry,pequeno,mediano,grande from tarifas where metrosFinal=2500;";
}else if($metros>=18001){
    $consulta="select tarifa,domicilioCarry,pequeno,mediano,grande from tarifas where metrosInicial=18001;";
}else{
    $consulta="SELECT max(tarifa)as tarifa,domicilioCarry,pequeno,mediano,grande FROM `tarifas` WHERE metrosFinal >= ".$metros." and metrosInicial <= ".$metros.";";
}

$prepare = $con->prepare($consulta);
$prepare->execute();
$arreglo = $prepare->fetchAll(PDO::FETCH_ASSOC);

$con = null;

function negativo($val) {
    if ($val < 0) {
        $val = $val * -1;
    }
    return $val;
}
echo json_encode($arreglo);
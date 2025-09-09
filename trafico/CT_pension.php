<?php 

include_once "../clases/CL_pension.php";

$OB_pension = new CL_pension();

if($_POST["caso"]==="1"){

    $retorno["retorno"] = $OB_pension->crearPension($_POST["nombrePension"],null);

    echo json_encode($retorno);

}
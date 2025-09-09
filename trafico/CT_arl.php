<?php 

include_once "../clases/CL_arl.php";

$OB_arl = new CL_arl();

if($_POST["caso"]==="1"){

    $retorno["retorno"] = $OB_arl->crearEps($_POST["nombreArl"],null);

    echo json_encode($retorno);

}
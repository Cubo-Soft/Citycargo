<?php 

include_once "../clases/CL_eps.php";

$OB_eps = new CL_eps();

if($_POST["caso"]==="1"){

    $retorno["retorno"] = $OB_eps->crearEps($_POST["nombreEps"],null);

    echo json_encode($retorno);

}
<?php

include '../clases/anticipos.php';

$anticipos=new anticipos();
echo json_encode($anticipos->crearValorCuentaCobro($_POST["valortotal"],$_POST["valorapagar"],$_POST["guia"],$_POST["nitEmpresa"],$_POST["cedulaConductor"],$_POST["placa"]));
//var_dump($_POST);
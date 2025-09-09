<?php

include '../clases/anticipos.php';

$anticipos=new anticipos();
echo json_encode($anticipos->cancelarAnticipo($_POST["numeroAnticipo"],$_POST["idservicio"],$_POST["motivo"]));
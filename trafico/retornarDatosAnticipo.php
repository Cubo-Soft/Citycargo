<?php

include '../clases/anticipos.php';

$anticipos=new anticipos();
echo json_encode($anticipos->retornarDatosAnticipo($_POST["numeroAnticipo"]));
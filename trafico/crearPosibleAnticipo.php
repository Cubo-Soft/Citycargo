<?php

include '../clases/anticipos.php';

$anticipos=new anticipos();
//echo $_POST["placa"];
json_encode($anticipos->crearPosibleAnticipo($_POST["numeroServicio"], $_POST["variasEmpresas"],$_POST["placa"]));

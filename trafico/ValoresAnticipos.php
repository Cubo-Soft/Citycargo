<?php

include '../clases/valoresanticipos.php';

$valorAnticipo=new valoresanticipos();

if($_POST["caso"]==='1'){
    echo json_encode($valorAnticipo->crearSobreAnticipo($_POST["guia"], $_POST["valorSobreAnticipo"],$_POST["placa"]));
}
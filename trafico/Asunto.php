<?php

include_once '../clases/asunto.php';

$asunto=new asunto();

if($_POST["caso"]==='1'){
    echo json_encode($asunto->crearAsunto($_POST["evento"]));
}


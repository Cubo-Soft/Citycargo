<?php

include_once '../clases/anticipos.php';

$anticipo = new anticipos();

if ($_POST["caso"] === '1') {    
    echo json_encode($anticipo->borrarSobreAnticipo($_POST["numSobreAnticipo"]));    
}


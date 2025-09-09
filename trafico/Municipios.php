<?php

include_once '../clases/municipios.php';

$municipio=new municipios();

if($_POST["caso"]==='1'){
    echo json_encode($municipio->retornarMunicipios());    
}

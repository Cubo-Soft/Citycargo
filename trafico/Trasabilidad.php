<?php

include_once '../clases/trasabilidad.php';

$trasabilidad=new trasabilidad();

if($_POST["caso"]==='1'){
    echo json_encode($trasabilidad->retornarFechaPago($_POST["idservicio"]));
}
        


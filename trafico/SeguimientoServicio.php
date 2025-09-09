<?php

include '../clases/seguimientoServicio.php';

$seguimientoServicio=new seguimientoServicio();

if($_POST["caso"]==='1'){
    echo json_encode($seguimientoServicio->cambiarManifiesto($_POST["idservicio"], $_POST["manifiesto"]));
}

if($_POST["caso"]==='2'){
   echo json_encode($seguimientoServicio->crearPlanDeRuta($_POST, $_FILES));
}

if($_POST["caso"]==='3'){
    echo json_encode($seguimientoServicio->retornarDatosSeguimiento($_POST["idservicio"]));
}

if($_POST["caso"]==='4'){
    echo json_encode($seguimientoServicio->retornarDatosSeguimientoPorGuia($_POST["guia"], $_POST["idservicio"]));
}
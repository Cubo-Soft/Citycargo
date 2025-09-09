<?php

include '../clases/empresacontactos.php';

$empresacontactos=new empresacontactos();

if($_POST["caso"]==='1'){
    echo json_encode($empresacontactos->retornarContactos($_POST["nit"]));
}

if($_POST["caso"]==='2'){
    echo json_encode($empresacontactos->crearContacto($_POST["nit"], $_POST["nombreContacto"], $_POST["cargoContacto"], $_POST["telefonoContacto"],$_POST["correo"]));
}

if($_POST["caso"]==='3'){
    echo json_encode($empresacontactos->retornarDatosContacto($_POST["id"]));
}

if($_POST["caso"]==='4'){
    echo json_encode($empresacontactos->cambiarEstado($_POST["id"],$_POST["estado"]));
}
<?php

include '../clases/valorDeclarado.php';

$vlrDeclarado=new valorDeclarado();

if($_POST["opcion"]==='1'){    
    echo json_encode($vlrDeclarado->verificarValorDeclarado($_POST["guia"]));
}

if($_POST["opcion"]==='2'){
    echo json_encode($vlrDeclarado->cambiarValorDeclarado($_POST["guia"], $_POST["nuevoValor"],$_POST["idservicio"]));
}


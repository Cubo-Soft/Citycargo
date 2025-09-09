 <?php

include '../clases/usuarios.php';

$asesores = new usuarios();

if($_POST["opcion"]==="1"){
    echo json_encode($asesores->retornarAsesores(1));
}

if($_POST["opcion"]==="2"){
    echo json_encode($asesores->retornarAsesores(2));
}


<?php

session_start();

include '../clases/usuarios.php';

$usu=new usuarios();

$usu->registrarSalida($_SESSION["id_ingreso"], date("Y-m-d H:i:s"));

$_SESSION["nombre_usuario"] = null;
$_SESSION["departamento"] = null;
$_SESSION["emp_id"] = null;
$_SESSION["rol_id"] = null;

session_destroy();

header("Location: ../index.php?msj=".$_GET["msj"]);


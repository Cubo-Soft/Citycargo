<?php

session_start();

include '../clases/usuarios.php';

$usu = new usuarios();

$usuario=$_POST["usuario"];
$clave=$_POST["clave"];

$caracteres=array('"','#','$','%','&','/','(',')','=','?','¡','¨',' ',':',';','.','_','<','>','SELECT');
$usuario=str_replace($caracteres, "", $usuario);
$clave= str_replace($caracteres, "", $clave);
$resultado = $usu->validarUsuario($usuario, $clave);

if ($resultado == "error") {
    header("Location: ../index.php?error=1");
} else if ($resultado[0]["estado"] === 'I') {
    header("Location: ../modulos/index.php");
} else {

//Inicio las variables de sesión correspondientes.
    $_SESSION["id_ingreso"] = $usu->registrarIngreso($resultado[0]["emp_cedula"], date("Y-m-d H:i:s"));

    $_SESSION["nombre_usuario"] = $resultado[0]["emp_nombres"] . " " . $resultado[0]["emp_apellidos"];
    $_SESSION["departamento"] = $resultado[0]["dep_nombre"];
    $_SESSION["emp_id"] = $resultado[0]["emp_id"];
    $_SESSION["rol_id"] = $resultado[0]["rol_id"];
    $_SESSION["emp_cedula"] = $resultado[0]["emp_cedula"];

    $impuestos = $usu->retornarImpuestos(date('Y'));

    $_SESSION["iva"] = $impuestos[0]['valimp_valor'];
    $_SESSION["retefuente"] = $impuestos[1]['valimp_valor'];
    $_SESSION["reteica"] = $impuestos[2]['valimp_valor'];
    $_SESSION["base"] = $impuestos[1]['base'];

    header("Location: ../modulos/index.php");
}



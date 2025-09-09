<?php

include '../clases/empleados.php';

$empleado = new empleados();
$datos = array();

if ($_POST["opcion"] === '1') {
    echo json_encode($empleado->retornarDatosEmpleado($_POST["cedula"]));
}

if ($_POST["opcion"] === '2') {    
    
    $datos["cedulaEmpleado"] = $_POST["cedulaEmpleado"];
    $datos["nombresEmpleado"] = $_POST["nombresEmpleado"];
    $datos["apellidosEmpleado"] = $_POST["apellidosEmpleado"];
    $datos["fechaIngreso"] = $_POST["fechaIngreso"];
    $datos["telefonoEmpleado"] = $_POST["telefonoEmpleado"];
    $datos["correoEmpleado"] = $_POST["correoEmpleado"];
    $datos["direccionEmpleado"] = $_POST["direccionEmpleado"];
    $datos["estadoEmpleado"] = $_POST["estadoEmpleado"];
    $datos["departamentos"] = $_POST["departamentos"];
    $datos["roles"] = $_POST["roles"];
    $datos["usuarioEmpleado"] = $_POST["usuarioEmpleado"];
    $datos["claveEmpleado"] = $_POST["claveEmpleado"];
    echo json_encode($empleado->crearEmpleado($datos));
}

if ($_POST["opcion"] === '3') {    
    
    $datos["cedulaEmpleado"] = $_POST["cedulaEmpleado"];
    $datos["nombresEmpleado"] = $_POST["nombresEmpleado"];
    $datos["apellidosEmpleado"] = $_POST["apellidosEmpleado"];
    $datos["fechaIngreso"] = $_POST["fechaIngreso"];
    $datos["telefonoEmpleado"] = $_POST["telefonoEmpleado"];
    $datos["correoEmpleado"] = $_POST["correoEmpleado"];
    $datos["direccionEmpleado"] = $_POST["direccionEmpleado"];
    $datos["estadoEmpleado"] = $_POST["estadoEmpleado"];
    $datos["departamentos"] = $_POST["departamentos"];
    $datos["roles"] = $_POST["roles"];
    $datos["usuarioEmpleado"] = $_POST["usuarioEmpleado"];
    $datos["claveEmpleado"] = $_POST["claveEmpleado"];
    $datos["emp_id"]=$_POST["emp_id"];

    echo json_encode($empleado->modificarEmpleado($datos));
}

if($_POST["opcion"]==='4'){    
    echo json_encode($empleado->retornarEmpleados());
}
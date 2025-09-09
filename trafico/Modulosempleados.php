<?php

include_once '../clases/modulos_empleados.php';
$modEmp = new modulos_empleados();

$arregloRetorno = array();

if ($_POST["caso"] === '1') {
    if ($_POST["estado"] === "true") {
        echo json_encode($modEmp->crearBoton($_POST["idboton"], $_POST["cedula"]));
    } else {
        echo json_encode($modEmp->borrarBoton($_POST["idboton"], $_POST["cedula"]));
    }
}

if ($_POST["caso"] === '2') {
    $arregloRetorno[0] = $modEmp->retornarBotones($_POST["cedula"]);
    $arregloRetorno[1] = $modEmp->retornarPermiso($_POST["cedula"], 'actualizarvalorflete');
    $arregloRetorno[2] = $modEmp->retornarPermiso($_POST["cedula"], 'serviciosporrevisar');
    $arregloRetorno[3] = $modEmp->retornarPermiso($_POST["cedula"], 'serviciosporfacturar');
    $arregloRetorno[4] = $modEmp->retornarPermiso($_POST["cedula"], 'serviciosporpagar');    
    $arregloRetorno[5] = $modEmp->retornarPermiso($_POST["cedula"], 'seguimiento');    
    echo json_encode($arregloRetorno);
}

if ($_POST["caso"] === '3') {
    $estado = $_POST["estado"];

    if ($estado === 'true') {
        $estado = 1;
    } else if ($estado === 'false') {
        $estado = 0;
    }
    $arreglo = $modEmp->retornarPermiso($_POST["cedula"], 'actualizarvalorflete');
    if (count($arreglo) > 0) {
        echo json_encode($modEmp->cambiarValorPermiso($arreglo[0]["id"], $estado));
    } else {
        echo json_encode($modEmp->createPermiso($_POST["cedula"], 'mostrarServicio', 'actualizarvalorflete', 'actualiza el valor del flete en el modulo de mostrarServicio por parte del funcionario con el numero de cedula aqui mostrado'));
    }
}

if ($_POST["caso"] === '4') {
    $estado = $_POST["estado"];
    if ($estado === 'true') {
        $estado = 1;
    } else if ($estado === 'false') {
        $estado = 0;
    }
    $arreglo = $modEmp->retornarPermiso($_POST["cedula"], 'serviciosporrevisar');
    
    //echo $arreglo; exit();
        
    if (count($arreglo) > 0) {
        echo json_encode($modEmp->cambiarValorPermiso($arreglo[0]["id"], $estado));
    } else {
        echo json_encode($modEmp->createPermiso($_POST["cedula"], 'index', 'serviciosporrevisar', 'Visualiza la cantidad de servicios por revisar por parte de gerencia'));
    }
}

if ($_POST["caso"] === '5') {
    $estado = $_POST["estado"];
    if ($estado === 'true') {
        $estado = 1;
    } else if ($estado === 'false') {
        $estado = 0;
    }
    $arreglo = $modEmp->retornarPermiso($_POST["cedula"], 'serviciosporfacturar');
    //var_dump($arreglo);
    if (count($arreglo) > 0) {
        echo json_encode($modEmp->cambiarValorPermiso($arreglo[0]["id"], $estado));
    } else {
        echo json_encode($modEmp->createPermiso($_POST["cedula"], 'index', 'serviciosporfacturar', 'Visualiza la cantidad de servicios por facturar'));
    }
}

if ($_POST["caso"] === '6') {
    $estado = $_POST["estado"];
    if ($estado === 'true') {
        $estado = 1;
    } else if ($estado === 'false') {
        $estado = 0;
    }
    $arreglo = $modEmp->retornarPermiso($_POST["cedula"], 'serviciosporpagar');
    //var_dump($arreglo);
    if (count($arreglo) > 0) {
        echo json_encode($modEmp->cambiarValorPermiso($arreglo[0]["id"], $estado));
    } else {
        echo json_encode($modEmp->createPermiso($_POST["cedula"], 'index', 'serviciosporpagar', 'Visualiza la cantidad de servicios por pagar'));
    }
}

if ($_POST["caso"] === '7') {
    $estado = $_POST["estado"];
    if ($estado === 'true') {
        $estado = 1;
    } else if ($estado === 'false') {
        $estado = 0;
    }
    $arreglo = $modEmp->retornarPermiso($_POST["cedula"], 'seguimiento');    
    if (count($arreglo) > 0) {
        echo json_encode($modEmp->cambiarValorPermiso($arreglo[0]["id"], $estado));
    } else {
        echo json_encode($modEmp->createPermiso($_POST["cedula"], 'index', 'seguimiento', 'Visualiza el boton con la cantidad de servicios por hacer seguimiento. Una vez se da clic en dicho boton se puede hacer seguimiento de todos los servicios'));
    }
}
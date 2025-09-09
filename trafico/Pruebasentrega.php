<?php

session_start();

include_once '../clases/pruebasentrega.php';
include_once '../clases/servicioguias.php';

$O_pruebasentrega = new pruebasentrega();
$O_servicioguias = new servicioguias();

if ($_POST["caso"] === '1') {

    $arregloRetorno = array();
    $nombreArchivoInicial = null;
    $idservicio = $_POST["idservicio"];
    $cantidadDeGuias = 0;

    if ($_POST["todasLasGuias"] === '1') {

        $datosServicioGuias = $O_servicioguias->retornarServicioGuias($idservicio);

        for ($index = 0; $index < count($datosServicioGuias); $index++) {

            $cantidadDeGuias += 1;

            $rutaCarpeta = "../imagenes/pruebasEntregas/";
            $nombreArchivo = $datosServicioGuias[$index]["numeroGuia"] . "_" . date("Ymdhms") . "_" . $index . "." . pathinfo($_FILES["imagenCumplido"]["name"], PATHINFO_EXTENSION);
            $rutaGuardarArchivo = $rutaCarpeta . $nombreArchivo;

            if ($index === 0) {
                $nombreArchivoInicial = $rutaGuardarArchivo;
            }

            $archivoTemporal = $_FILES["imagenCumplido"]["tmp_name"];

            //por alguna razon no puedo cargar los archivos mientras el el ciclo esta funcionando
            //asi que el primer archivo lo creo y el segundo lo copio 
            //y así de manera consecutiva

            if ($index === 0) {
                if (move_uploaded_file($archivoTemporal, $rutaGuardarArchivo)) {
                    $arregloRetorno["creacionArchivo"] = $index + 1;
                } else {
                    $arregloRetorno["creacionArchivo"] = 0;
                }
            } else {
                if (copy($nombreArchivoInicial, $rutaGuardarArchivo)) {
                    $arregloRetorno["creacionArchivo"] = $index + 1;
                } else {
                    $arregloRetorno["creacionArchivo"] = 0;
                }
            }


            $datos["cedula"] = $_SESSION["emp_cedula"];
            $datos["idservicio"] = $idservicio;
            $datos["guia"] = $datosServicioGuias[$index]["numeroGuia"];
            $datos["ruta"] = $rutaGuardarArchivo;

            $arregloRetorno["rutaGuardata"][$index] = $O_pruebasentrega->crearPruebasEntrega($datos, 1);
        }
    } else {

        $todasLasGuias = explode('-', $_POST["todasLasGuias"]);

        for ($index = 0; $index < count($todasLasGuias); $index++) {

            $cantidadDeGuias += 1;

            $rutaCarpeta = "../imagenes/pruebasEntregas/";
            $nombreArchivo = $todasLasGuias[$index] . "_" . date("Ymdhms") . "_" . $index . "." . pathinfo($_FILES["imagenCumplido"]["name"], PATHINFO_EXTENSION);
            $rutaGuardarArchivo = $rutaCarpeta . $nombreArchivo;

            if ($index === 0) {
                $nombreArchivoInicial = $rutaGuardarArchivo;
            }

            $archivoTemporal = $_FILES["imagenCumplido"]["tmp_name"];

            //por alguna razon no puedo cargar los archivos mientras el el ciclo esta funcionando
            //asi que el primer archivo lo creo y el segundo lo copio 
            //y así de manera consecutiva
            if (move_uploaded_file($archivoTemporal, $rutaGuardarArchivo)) {
                $arregloRetorno["creacionArchivo"] = $index + 1;
            } else {

                if (copy($nombreArchivoInicial, $rutaGuardarArchivo)) {
                    $arregloRetorno["creacionArchivo"] = $index + 1;
                } else {
                    $arregloRetorno["creacionArchivo"] = 0;
                }
            }

            $datos["cedula"] = $_SESSION["emp_cedula"];
            $datos["idservicio"] = $idservicio;
            $datos["guia"] = $todasLasGuias[$index];
            $datos["ruta"] = $rutaGuardarArchivo;

            $arregloRetorno["rutaGuardata"][$index] = $O_pruebasentrega->crearPruebasEntrega($datos, 1);
        }
    }

    if ($cantidadDeGuias === count($arregloRetorno["rutaGuardata"])) {
        echo json_encode(1);
    } else {
        echo json_encode(0);
    }
}

if ($_POST["caso"] === '2') {
    $datos["idservicio"] = $_POST["idservicio"];
    $arregloRetorno["todo"] = $O_pruebasentrega->retornarPruebasEntrega($datos, 1);
    $arregloRetorno["guias"] = $O_pruebasentrega->retornarPruebasEntrega($datos, 2);
    echo json_encode($arregloRetorno);
}

if ($_POST["caso"] === '3') {
    $datos["id"] = $_POST["id"];
    echo json_encode($O_pruebasentrega->actualizarPruebasEntrega($datos, 1));
}

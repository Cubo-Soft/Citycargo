<?php

include_once '../clases/CL_estudio_seguridad.php';
include_once '../clases/CL_hist_estudio_seguridad.php';
include_once '../clases/CL_usuariosgps.php';
include_once '../clases/vehiculo.php';

$OB_estudio_seguridad = new CL_estudio_seguridad();
$OB_hist_estudio_seguridad = new CL_hist_estudio_seguridad();
$OB_usuariosgps = new CL_usuariosgps();
$OB_vehiculo = new vehiculos();

$retorno = array();

if ($_POST["caso"] === '1') {
    $retorno["estudioSeguridad"] = $OB_estudio_seguridad->retornarEstudioSeguridad($_POST["placa"], null);
    $retorno["usuariosGps"] = $OB_usuariosgps->retornarUsuariosGps($_POST["placa"], null);
    $retorno["vehiculo"] = $OB_vehiculo->retornarVehiculo($_POST["placa"]);
    echo json_encode($retorno);
}

if ($_POST["caso"] === '2') {    
    $retorno["estudioSeguridad"] = $OB_estudio_seguridad->crearEstudioSeguridad($_POST, null);
    echo json_encode($retorno);
}

if ($_POST["caso"] === '3') {
    $datos["campo"] = $_POST["campo"];
    $datos["valor"] = $_POST["valor"];
    $datos["cedulaempcambio"] = $_POST["emp_cedula"];
    $datos["placa"] = $_POST["placa"];
    $datos["fechaestudio"] = date("Y-m-d h:m:s");

    $data = $OB_estudio_seguridad->retornarEstudioSeguridad($_POST["placa"], null); 

    $OB_hist_estudio_seguridad->crearHistEstudioSeguridad($data, $datos);

    //echo $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos); exit();
    
    $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d h:m:s");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
    
    echo json_encode($retorno);
}

if ($_POST["caso"] === '4') {
    $numeroimagen = $_POST["numeroimagen"];

    if (isset($_FILES['imagen' . $numeroimagen])) {

        $imagen = $_FILES['imagen' . $numeroimagen];
        $uploadDir = '../imagenes/vehiculos/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($imagen['name']);

        // Validar que el archivo sea una imagen JPG o PNG
        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
            //echo "Solo se permiten archivos JPG, JPEG y PNG.";
            $retorno["imagen"] = 0;
            exit;
        }

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        $newFileName = $_POST["placa"] . '_' . $numeroimagen . '.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($imagen['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["imagen"] = 1;

            $datos["campo"] = "imagen" . $numeroimagen;
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["imagen"] = 0;
        }
    } else {
        $retorno["imagen"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

if ($_POST["caso"] === '5') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        $pattern = "../imagenes/licenciasConduccion/" . $placa . "_LC.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/licenciasConduccion/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //LC Licencia Conductor
        $newFileName = $_POST["placa"] . '_LC.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutalicencia";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }

    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
    
    echo json_encode($retorno);
}

if ($_POST["caso"] === '6') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        //FC Foto Conductor
        $pattern = "../imagenes/conductores/" . $placa . "_FoCo.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/conductores/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //FoCo Foto Conductor
        $newFileName = $_POST["placa"] . '_FoCo.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "fotoconductor";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

if ($_POST["caso"] === '7') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        //FC Foto Conductor
        $pattern = "../imagenes/hojasVida/" . $placa . "_HoVi.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/hojasVida/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //FoCo Foto Conductor
        $newFileName = $_POST["placa"] . '_HoVi.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "hojavidacond";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }

    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
    
    echo json_encode($retorno);
}

if ($_POST["caso"] === '8') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        //FC Foto Conductor
        $pattern = "../imagenes/runtPropietarios/" . $placa . "_runtprop.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/runtPropietarios/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //FoCo Foto Conductor
        $newFileName = $_POST["placa"] . '_runtprop.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutaruntprop";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

if ($_POST["caso"] === '9') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        //FC Foto Conductor
        $pattern = "../imagenes/autorizacionesPropietario/" . $placa . "_autdatperpro.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/autorizacionesPropietario/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //FoCo Foto Conductor
        $newFileName = $_POST["placa"] . '_autdatperpro.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutadatperprop";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

if ($_POST["caso"] === '10') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        //FC Foto Conductor
        $pattern = "../imagenes/rutPropietarios/" . $placa . "_rutpro.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/autorizacionesPropietario/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //FoCo Foto Conductor
        $newFileName = $_POST["placa"] . '_rutpro.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutarutprop";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

if ($_POST["caso"] === '11') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        //FC Foto Conductor
        $pattern = "../imagenes/certibancPropietario/" . $placa . "_cerbanpro.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/certibancPropietario/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //FoCo Foto Conductor
        $newFileName = $_POST["placa"] . '_cerbanpro.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutacertbanprop";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

if ($_POST["caso"] === '12') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        //FC Foto Conductor
        $pattern = "../imagenes/urtLtc/" . $placa . "_rutltc.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/urtLtc/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //FoCo Foto Conductor
        $newFileName = $_POST["placa"] . '_rutltc.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutarutltc";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

if ($_POST["caso"] === '13') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        $pattern = "../imagenes/trailer/" . $placa . "_trailer.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/trailer/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //LC Licencia Conductor
        $newFileName = $_POST["placa"] . '_trailer.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["imagen"] = 1;

            $datos["campo"] = "rutafototrailer";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }

    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
    
    echo json_encode($retorno);
}

if ($_POST["caso"] === '14') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        $pattern = "../imagenes/licenciasConduccion/" . $placa . "_LC2.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/licenciasConduccion/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //LC Licencia Conductor
        $newFileName = $_POST["placa"] . '_LC2.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutalicencia2";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

if ($_POST["caso"] === '15') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        $pattern = "../imagenes/licenciasTransito/" . $placa . "_LT1.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/licenciasTransito/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //LC Licencia Conductor
        $newFileName = $_POST["placa"] . '_LT1.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutalicenciatransito1";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

if ($_POST["caso"] === '16') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        $pattern = "../imagenes/licenciasTransito/" . $placa . "_LT2.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/licenciasTransito/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //LC Licencia Conductor
        $newFileName = $_POST["placa"] . '_LT2.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutalicenciatransito2";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

if ($_POST["caso"] === '17') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        $pattern = "../imagenes/soat/" . $placa . "_SOAT.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/soat/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //LC Licencia Conductor
        $newFileName = $_POST["placa"] . '_SOAT.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutasoat";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

//revisionTecnicomecanica
if ($_POST["caso"] === '18') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        $pattern = "../imagenes/revisionTecnicomecanica/" . $placa . "_rT.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/revisionTecnicomecanica/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //LC Licencia Conductor
        $newFileName = $_POST["placa"] . '_rT.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutarevtecno";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

//poliza de responsabilida civil
//prc
if ($_POST["caso"] === '19') {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
        $pattern = "../imagenes/polizas/" . $placa . "_prc.*";

        // Usar glob() para encontrar todos los archivos que coincidan con el patrón
        $files = glob($pattern);

        // Recorrer y eliminar los archivos encontrados
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file); // Eliminar el archivo
            }
        }

        $archivo = $_FILES['file'];
        $uploadDir = '../imagenes/polizas/'; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen (puedes cambiar este método si lo deseas)
        //LC Licencia Conductor
        $newFileName = $_POST["placa"] . '_prc.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = "rutapolizarespo";
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }
    
    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d hh:mm:ss");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);

    echo json_encode($retorno);
}

$actualizarArchivo = 0;
$carpeta = null;
$campo = null;
$abreviatura = null;

switch ($_POST["caso"]) {
    case '20':
        //rndc
        $actualizarArchivo = 1;
        $carpeta = 'rndc';
        $campo = "rutarndc";
        $abreviatura = 'rndc';
        break;

    case '21':
        //imagen cedula conductor 1 = icc1
        $actualizarArchivo = 1;
        $carpeta = 'cedulas';
        $campo = "rutaimgcedcond1";
        $abreviatura = 'icc1';
        break;

    case '22':
        //imagen cedula conductor 2 = icc2
        $actualizarArchivo = 1;
        $carpeta = 'cedulas';
        $campo = "rutaimgcedcond2";
        $abreviatura = 'icc2';
        break;

    case '23':
        //planilla de seguridad social del conductor 1 = pssc1
        $actualizarArchivo = 1;
        $carpeta = 'planillas';
        $campo = "rutaplansegsoccond1";
        $abreviatura = 'pssc1';
        break;

    case '24':
        //planilla de seguridad social del conductor 2 = pssc2
        $actualizarArchivo = 1;
        $carpeta = 'planillas';
        $campo = "rutaplansegsoccond2";
        $abreviatura = 'pssc2';
        break;

    case '25':
        //autorizacion manejo datos personales conductor = amdpc
        $actualizarArchivo = 1;
        $carpeta = 'autorizacionesConductor';
        $campo = "rutadatospersonales";
        $abreviatura = 'amdpc';
        break;

    case '26':
        //Policia - Antecedentes policiales conductor= papc
        $actualizarArchivo = 1;
        $carpeta = 'conductores';
        $campo = "rutaantepolcond";
        $abreviatura = 'papc';
        break;

    case '27':
        //Personeria - Antecedentes disciplinarios= padc
        $actualizarArchivo = 1;
        $carpeta = 'conductores';
        $campo = "rutaantediscond";
        $abreviatura = 'padc';
        break;

    case '28':
        //Procuraduria conductor = pC
        $actualizarArchivo = 1;
        $carpeta = 'conductores';
        $campo = "rutaprocucond";
        $abreviatura = 'pC';
        break;

    case '29':
        //Contraloria conductor = cC
        $actualizarArchivo = 1;
        $carpeta = 'conductores';
        $campo = "rutacontrcond";
        $abreviatura = 'cC';
        break;

    case '30':
        //SIMIT - Infracciones de transito = simCond
        $actualizarArchivo = 1;
        $carpeta = 'conductores';
        $campo = "rutasimitcond";
        $abreviatura = 'simCond';
        break;

    case '31':
        //Registro Nacional de Medidas Correctivas - RNMC
        $actualizarArchivo = 1;
        $carpeta = 'conductores';
        $campo = "rutarnmccond";
        $abreviatura = 'rnmcCond';
        break;

    case '32':
        //Consulta de inhabilidades
        $actualizarArchivo = 1;
        $carpeta = 'conductores';
        $campo = "rutainhabcond";
        $abreviatura = 'ciCond';
        break;

    case '33':
        //vehiculo Fasecolda = veFasec 
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutafasecolda";
        $abreviatura = 'veFasec';
        break;

    case '34':
        //imagen cedula propietario 1 = icp1
        $actualizarArchivo = 1;
        $carpeta = 'cedulas';
        $campo = "rutaimgcedprop1";
        $abreviatura = 'icp1';
        break;

    case '35':
        //imagen cedula propietario 2 = icp2
        $actualizarArchivo = 1;
        $carpeta = 'cedulas';
        $campo = "rutaimgcedprop2";
        $abreviatura = 'icp2';
        break;

    case '36':
        //Policia - Antecedentes policiales propietario= papp
        $actualizarArchivo = 1;
        $carpeta = 'propietarios';
        $campo = "rutaantepolprop";
        $abreviatura = 'papp';
        break;

    case '37':
        //Personería - Antecedentes disciplinarios propietario = padp
        $actualizarArchivo = 1;
        $carpeta = 'propietarios';
        $campo = "rutaantedisprop";
        $abreviatura = 'padp';
        break;

    case '38':
        //Procuraduria Propietario = pP
        $actualizarArchivo = 1;
        $carpeta = 'propietarios';
        $campo = "rutaprocuprop";
        $abreviatura = 'pP';
        break;

    //rutacontrprop
    case '39':
        //Contraloria Propietario = cP
        $actualizarArchivo = 1;
        $carpeta = 'propietarios';
        $campo = "rutacontrprop";
        $abreviatura = 'cP';
        break;

    case '40':
        //Registro Nacional de Medidas Correctivas - RNMC propietario
        $actualizarArchivo = 1;
        $carpeta = 'propietarios';
        $campo = "rutarnmcprop";
        $abreviatura = 'rnmcProp';
        break;

    //rutainhabprop
    case '41':
        $actualizarArchivo = 1;
        $carpeta = 'propietarios';
        $campo = "rutainhabprop";
        $abreviatura = 'ciProp';
        break;

    //rutaimgcedltc1
    case '42':
        $actualizarArchivo = 1;
        $carpeta = 'ltc';
        $campo = "rutaimgcedltc1";
        $abreviatura = 'cedltc1';
        break;

    //rutaimgcedltc2
    case '43':
        $actualizarArchivo = 1;
        $carpeta = 'ltc';
        $campo = "rutaimgcedltc2";
        $abreviatura = 'cedltc2';
        break;

    //rutaimgdocsopo
    //documento soporte = ds 
    //ltc = locatario / tenedor / comprador 
    case '44':
        $actualizarArchivo = 1;
        $carpeta = 'ltc';
        $campo = "rutaimgdocsopo";
        $abreviatura = 'dsltc';
        break;

    case '45':
        $actualizarArchivo = 1;
        $carpeta = 'ltc';
        $campo = "rutaimgruttlc";
        $abreviatura = 'runtltc';
        break;

    case '46':
        $actualizarArchivo = 1;
        $carpeta = 'ltc';
        $campo = "rutaimgantjudtlc";
        $abreviatura = 'antjudltc';
        break;

    case '47':
        $actualizarArchivo = 1;
        $carpeta = 'ltc';
        $campo = "rutaimgperstlc";
        $abreviatura = 'persltc';
        break;

    case '48':
        $actualizarArchivo = 1;
        $carpeta = 'ltc';
        $campo = "rutaimgproctlc";
        $abreviatura = 'procltc';
        break;

    case '49':
        $actualizarArchivo = 1;
        $carpeta = 'ltc';
        $campo = "rutaimgconttlc";
        $abreviatura = 'contltc';
        break;

    case '50':
        $actualizarArchivo = 1;
        $carpeta = 'ltc';
        $campo = "rutaimgrnmctlc";
        $abreviatura = 'rnmcltc';
        break;

    case '51':
        $actualizarArchivo = 1;
        $carpeta = 'ltc';
        $campo = "rutaimginhatlc";
        $abreviatura = 'inhaltc';
        break;

    case '52':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutacercarmaniali";
        $abreviatura = 'ccma';
        break;

    case '53':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutacerfumig";
        $abreviatura = 'cefu';
        break;

    case '54':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutacerconsan";
        $abreviatura = 'ccs_';
        break;

    //cedula propietario remolque cpr                
    case '55':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutacedpropremol1";
        $abreviatura = 'cpr1';
        break;

    //cedula propietario remolque cpr                
    case '56':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutacedpropremol2";
        $abreviatura = 'cpr2';
        break;

    //Tarjeta registro remolque                
    case '57':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutatarregremol";
        $abreviatura = 'trr';
        break;

    //RUNT propiedad remolque                
    case '58':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutaruntremol";
        $abreviatura = 'rrunrem';
        break;

    //Policía - Antecedentes judiciales           
    case '59':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutapolremol";
        $abreviatura = 'rpolrem';
        break;

    //rutaproremol
    case '60':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutaproremol";
        $abreviatura = 'rprorem';
        break;

    //rutaconremol
    case '61':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutaconremol";
        $abreviatura = 'rconrem';
        break;

    //rutainharemol
    case '62':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutainharemol";
        $abreviatura = 'rinharem';
        break;

    //rutarnmcremol
    case '63':
        $actualizarArchivo = 1;
        $carpeta = 'vehiculos';
        $campo = "rutarnmcremol";
        $abreviatura = 'rrnmcrem';
        break;
    
    //rutaruntcond
    case '65':
        $actualizarArchivo = 1;
        $carpeta = 'runtConductor';
        $campo = "rutaruntcond";
        $abreviatura = 'rC'; 
        break;

    default:
        $actualizarArchivo = 0;
        break;
}

if ($actualizarArchivo === 1) {

    if (isset($_FILES['file'])) {

        // borrar los archivos de
        $placa = $_POST["placa"]; // Aquí puedes poner la variable que contiene la "placa"
      
        $archivo = $_FILES['file'];
        $uploadDir = "../imagenes/" . $carpeta . "/"; // Directorio donde se guardarán las imágenes
        $uploadFile = $uploadDir . basename($archivo['name']);

        $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));

        // Generar un nombre único para la imagen 
        $newFileName = $_POST["placa"] . '_' . $abreviatura . '.' . $imageFileType;

        // Ruta completa para guardar la imagen con el nuevo nombre
        $uploadFile = $uploadDir . $newFileName;

        //unlink($uploadFile); // Eliminar el archivo
        // Mover el archivo al servidor
        if (move_uploaded_file($archivo['tmp_name'], $uploadFile)) {
            //echo "La imagen ha sido subida exitosamente.";
            $retorno["archivo"] = 1;

            $datos["campo"] = $campo;
            $datos["valor"] = $uploadFile;
            $retorno["ruta"] = $uploadFile;

            $retorno["estudioSeguridad"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);
        } else {
            //echo "Hubo un error al subir la imagen.";
            $retorno["archivo"] = 0;
        }
    } else {
        $retorno["archivo"] = 0;
        //echo "No se envió ninguna imagen.";
    }

    $datos["campo"] = "fechaestudio";
    $datos["valor"] = date("Y-m-d H:m:s");
    $retorno["ajusteFecha"] = $OB_estudio_seguridad->cambiarEstudioSeguridad($_POST["placa"], $datos);    
    
    echo json_encode($retorno);
}


if ($_POST["caso"] === '64') {    
    echo json_encode($OB_estudio_seguridad->retornarEstudioSeguridad($_POST["placa"], null));
}

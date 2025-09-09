<?php

function retornarEstructuraTabla($datos,$opcion){

    $tabla=null;

    if($opcion===1){

        $tabla ='<table class="table table-striped" >';
        $tabla .='<tr>';
        for ($i=0; $i < count($datos["cabeceraTabla"]) ; $i++) { 
            $tabla .= '<th>'. $datos["cabeceraTabla"][$i].'</th>';
        }
        $tabla .='</tr>';

        for ($i=0; $i < count($datos["cuerpoTabla"]); $i++) { 
            $tabla .='<tr>';
            for ($a=0; $a < count($datos["datosCuerpoTabla"]) ; $a++) { 
                $tabla .= '<th>'. $datos["cuerpoTabla"][$i]["datosCuerpoTabla"][$a].' <input type="text" class="form form-control" /></th>';
            }
        $tabla .='</tr>';
        }

        return $tabla;

    }

}

function validarExtensionArchivo($nombreArchivo) {
    // Obtener la extensión del archivo
    $extension = pathinfo($nombreArchivo, PATHINFO_EXTENSION);

    // Array de extensiones permitidas
    $extensionesPermitidas = array('jpg', 'jpeg', 'png', 'pdf');

    // Convertir la extensión a minúsculas para comparar
    $extension = strtolower($extension);

    // Verificar si la extensión está en la lista de extensiones permitidas
    if (in_array($extension, $extensionesPermitidas)) {
        return true; // La extensión del archivo es válida
    } else {
        return false; // La extensión del archivo no es válida
    }
}

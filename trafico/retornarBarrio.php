<?php

//cargo el archivo zonasdata.txt
$archivo = fopen("./ZONASDATA.TXT", "r") or die("problemas al leer archivo");

//arreglo en el cual va a estar el resultado
$barrio = array();

//verifico si es origen 
if (isset($_POST['origen'])) {

    $CL = $_POST['CLOR'];
    $CLS = $_POST['CLORS'];
    $CR = $_POST['CROR'];
    $CRE = $_POST['CRORE'];
}

//verifico si es destino
if (isset($_POST['destino'])) {

    $CL = $_POST['CLDE'];
    $CLS = $_POST['CLDES'];
    $CR = $_POST['CRDE'];
    $CRE = $_POST['CRDEE'];
}

//mientras el archivo tenga datos
while (!feof($archivo)) {
    //obtener datos del archivo
    $traer = fgets($archivo);
    //buscar los saltos de linea
    $saltodelinea = nl2br($traer);
    //convertir en arreglo teniendo en cuenta el | como signo para 
    $arreglo = explode("|", $saltodelinea);

    if (( $CL >= intval($arreglo[3]) && $CL <= intval($arreglo[5]) ) && ($CR >= intval($arreglo[7]) && $CR <= intval($arreglo[9])) && (intval($arreglo[4] == $CLS) && intval($arreglo[6]) == $CLS && intval($arreglo[8]) == $CRE && intval($arreglo[10] == $CRE)) && ($arreglo[2] > 0)) {

        //quito caracteres que trae desde el archivo que no son necesarios en la vista
        $barrio[0] = substr($arreglo[11], 0, -8);
        //cierro el archivo
        fclose($archivo);
        //detengo el bucle
        break;
    }
}

//mando información a la vista por json_encode
echo json_encode($barrio);

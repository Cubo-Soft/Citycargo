<?php

function limpiarVariable($variable) {
    $variable = str_replace('.', '', $variable);
    $variable = str_replace(',', '', $variable);
    $variable = str_replace(' ', '', $variable);
    return $variable;
}

function limpiarVariable2($variable) {
    $variable = str_replace('.', ' ', $variable);
    $variable = str_replace(',', ' ', $variable);
    return $variable;
}

function devolverCadena() {
    $primerLetra = chr(rand(ord("A"), ord("Z")));
    $primerNumero = rand(1, 9);
    $segundaLetra = chr(rand(ord("a"), ord("z")));
    $segundoNumero = rand(1, 9);
    $tercerLetra = chr(rand(ord("a"), ord("z")));
    $tercerNumero = rand(1, 9);
    return $primerLetra . '' . $primerNumero . '' . $segundaLetra . '' . $segundoNumero . '' . $tercerLetra . '' . $tercerNumero;
}

function finalizarSesion() {
    header("Location: ../trafico/salir.php?msj=2");
}

//declaramos la funcion : randomString
function randomString($length, $type = '') {
// Seleccionamos el tipo de caracteres que deseas que devuelva el string
    switch ($type) {
        case 'num':
// Solo cuando deseas que devuelva numeros.
            $salt = '1234567890';
            break;
        case 'lower':
// Solo cuando deseas que devuelva letras en minusculas.
            $salt = 'abcdefghijklmnopqrstuvwxyz';
            break;
        case 'upper':
// Solo cuando deseas que devuelva letras en mayusculas.
            $salt = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            break;
        default:
// Para cuando deseas que la cadena este compuesta por letras y numeros
            $salt = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
            break;
    }
    $rand = '';
    $i = 0;
    while ($i < $length) {
//Loop hasta que el string aleatorio contenga la longitud ingresada.
        $num = rand() % strlen($salt);
        $tmp = substr($salt, $num, 1);
        $rand = $rand . $tmp;
        $i++;
    }
//Retorno del string aleatorio.
    return $rand;
}

//Para llamar a la función.
//Para este ejemplo mostraremos un string de longitud de 10 caracteres, entre letras y números
//echo randomString(10, $type = '');

/*
 * Se crea la función lista que retornar una lista de acuerdo a una condicion establecida
 */

function retornarLista($condicion) {
    $listado = null;
    switch ($condicion) {
        case 1:
            $listado = "<select  class='form-control' id='listarServiciosPor' name='listarServiciosPor'>"
                    . "<option value='-1'>Listar servicios por </option>"
                    . "<option value='Asesor'>......Asesor</option>"
                    . "<option value='AgrupadoPorFactura'>......Agrupado por número de factura</option>"
                    . "<option value='porEmpresa'>......Empresa</option>"
                    . "<option value='fechaIniFechaFin'>......Fecha inicial a fecha final</option>"
                    . "<option value='facturado'>......Facturado</option>"
                    . "<option value='factura'>......N&uacute;mero de factura</option>"
                    . "<option value='serviciosPorPlaca'>......Por placa</option>"
                    . "<option value='servFacPag'>......Servicios por facturar que ya fueron pagados</option>"
                    . "<option value='-2'>Agenda</option>"
                    . "<option value='agenda'>......Agenda comercial</option>"
                    . "<option value='-3'>Calificaciones</option>"
                    . "<option value='porPlaca'>......Por placa</option>"
                    . "<option value='porCedula'>......Por cédula</option>"
                    . "<option value='-4'>Tel&eacute;fonos</option>"
                    . "<option value='telefonosPorPlaca'>......Por placa</option>"
                    . "<option value='telefonosGeneral'>......General</option>"
                    . "<option value='seguimiento'>Seguimiento</option>"
                    . "</select>";
            break;
        case 2:
            $listado = "<select class='form-control' id='listarServiciosPor' name='listarServiciosPor'>"
                    . "<option value='0'>...</option>"
                    . "<option value='fechaIniFechaFin'>......Fecha inicial a fecha final</option>"
                    . "<option value='factura'>......N&uacute;mero de factura</option>"
                    . "<option value='serviciosPorPlaca'>......Por placa</option>"
                    //. "<option value='seguimiento'>Seguimiento</option>"
                    //. "<option value='UIAF'>Reporte UIAF</option>"
                    . "</select>";
            break;
        case 3:
            $listado = "<table>"
                    . "<tr>"
                    . "<td>Fecha inicial </td><td> <input type='date' name='fechaInicial' id='fechaInicial' class='form form-control' /></td>"
                    . "<td>Fecha final </td><td> <input type='date' name='fechaFinal' id='fechaFinal' value='" . date("Y-m-d") . "' class='form form-control' /></td>"
                    . "<td><input type='button' name='btnConsultarServicios' id='btnConsultarServicios' value='CONSULTAR SERVICIOS' onclick='consultarServicios(1)' class='btn btn-success' /></td>"
                    . "</tr>"
                    . "</table>";
            break;
        case 4:
            $listado = "<table>"
                    . "<tr>"
                    . "<td>Fecha inicial </td><td> <input type='date' name='fechaInicial' id='fechaInicial' class='form form-control' /></td>"
                    . "<td>Fecha final </td><td> <input type='date' name='fechaFinal' id='fechaFinal' value='" . date("Y-m-d") . "' class='form form-control' /></td>"
                    . "<td><input type='button' name='btnConsultarServicios' id='btnConsultarServicios' value='CONSULTAR SERVICIOS' onclick='consultarServicios(2)' class='btn btn-success' /></td>"
                    . "</tr>"
                    . "</table>";
            break;
    }

    return $listado;
}

function randomColor() {

    $letras = array("B", "D", "E", "F", "A", "C");
    $color = null;

    for ($index = 0; $index < 3; $index++) {
        $letra = rand(0, 5);
        $color .= $letras[$letra];
        $numero = rand(0, 9);
        $color .= $numero;
    }
    return $color;
}

function retornarListaPlacas($placas, $conFuncion) {
    $retorno = null;
    $arregloPlacas = array();

    if ($conFuncion === 1) {
        $retorno = '<select name="placas" id="placas" class="form-control">';
    } else {
        $retorno = '<select name="placas" id="placas" class="form-control" onchange="consultarCalificacion(this)">';
    }
    $retorno .= '<option value="0">...</option>';
    for ($index = 0; $index < count($placas); $index++) {
        //if (!in_array($placas[$index]["placa"], $arregloPlacas)) {
        //$arregloPlacas[$index] = $placas[$index]["placa"];
        $retorno .= "<option value='" . $placas[$index]["cond_identificacion"] . "'>" . $placas[$index]["placa"] . "</option>";
        //}
    }
    $retorno .= "</select>";
    return $retorno;
}

function pintarMenu($botones, $opcion) {

    if ($opcion === 1) {
        for ($i = 0; $i < count($botones); $i++) {
            echo '<input type="submit" name="boton" onmouseleave="colorIndexSale(this);" onmouseenter="colorIndexEntra(this);" '
            . ' class="' . $botones[$i]["clsboostrap"] . ' botonIndex" value="' . $botones[$i]["valor"] . '" id="' . $botones[$i]["id"] . '" '
            //. ' class="' . $botones[$i]["clsboostrap"] . '" value="' . $botones[$i]["valor"] . '" id="' . $botones[$i]["id"] . '" '
            . ' title="' . $botones[$i]["title"] . '" > ';
        }
    }

    if ($opcion === 2) {

//        for ($i = 0; $i < count($botones); $i++) {
//            echo '<input type="submit" name="boton" onmouseleave="colorIndexSale(this);" onmouseenter="colorIndexEntra(this);" '
//            . ' class="' . $botones[$i]["clsboostrap"] . ' botonIndex" value="' . $botones[$i]["valor"] . '" id="' . $botones[$i]["id"] . '" '
//            . ' title="' . $botones[$i]["title"] . '" >';
//        }
    }
}

function retornarListaCarrocerias() {
    echo '<select class="form-control" id="listaTipoCarroceria"> 
                                        <option value="0" >...</option>
                                        <option value="FURGON" >FURGON</option>
                                        <option value="ESTACAS" >ESTACAS</option>
                                        <option value="PLANCHA" >PLANCHA</option>
                                        <option value="REMOLQUE" >REMOLQUE</option>
                                        <option value="CAMABAJA" >CAMABAJA</option>
                                    </select>';
}

function retornarListaVehiculos() {
    echo '<select name="listaTipoVehiculo" id="listaTipoVehiculo" class="form-control col-lg-8">
                                        <option value="0">...</option>  
                                        <option value="SENCILLO">SENCILLO (hasta 10Ton)</option>  
                                        <option value="NPR">NPR (hasta 5Ton)</option>  
                                        <option value="NHR">NHR (hasta 2Ton)</option>  
                                        <option value="NKR">NKR (hasta 4Ton)</option>  
                                        <option value="PATINETA">PATINETA (hasta 18Ton)</option>  
                                        <option value="MULA">MULA (de 32 Ton a 34 Ton)</option>  
                                        <option value="DOBLETROQUE">DOBLETROQUE (hasta 14Ton)</option>  
                                    </select>';
}

function retornarListaClientes($listaClientes) {
    $lista = '<select name="idCliente" id="idCliente" class="form-control">'
            . '<option value="0">...</option>';
    for ($index = 0; $index < count($listaClientes); $index++) {
        $lista .= "<option value=" . $listaClientes[$index]["cli_documento"] . ">" . $listaClientes[$index]["cli_nombre"] . "</option>";
    }
    $lista .= "</select>";
    return $lista;
}

function nombreMes($mes) {
    setlocale(LC_TIME, 'spanish');
    $nombre = strftime("%B", mktime(0, 0, 0, $mes, 1, 2000));
    $nombre_mes = ucfirst($nombre);
    return $nombre_mes;
}

function retornarMilesMillones($valor) {

    $retorno = 0;

    if (strlen($valor) === 9) {
        $retorno = substr($valor, 0, 3);
    }

    if (strlen($valor) === 10) {
        $retorno = substr($valor, 0, 4);
    }

    return $retorno;
}

function retornarRecursosBootstrap() {
    echo "<!-- Latest compiled and minified CSS -->
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' integrity='sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u' crossorigin='anonymous'>

    <!-- Optional theme -->
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css' integrity='sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp' crossorigin='anonymous'>

    <!-- Latest compiled and minified JavaScript -->
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js' integrity='sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa' crossorigin='anonymous'></script>";
}

//echo 'hola';
//echo retornarMilesMillones('5246893123');

function crearSelect($arreglo, $selectName, $nombresPosiciones, $funcion, $crear) {

    if ($funcion === null) {
        $html = "<select name='{$selectName}' id='{$selectName}' class='form form-control'>";
    } else {
        $html = "<select name='{$selectName}' id='{$selectName}' class='form form-control' onchange='" . $funcion . "'>";
    }

    $html .= "<option value='-1'>...</option>";

    foreach ($arreglo as $partes) {
        $html .= "<option value=" . $partes[$nombresPosiciones["id"]] . ">" . $partes[$nombresPosiciones["nombre"]] . "</option>";
    }

    if ($crear) {
        $html .= "<option value='0'>Crear</option>";
    }

    $html .= "</select>";

    return $html;
}

function retornarDivsParaImagenes($arreglo, $opcion) {
    echo '<div id="divImagen' . $arreglo["idDiv"] . '">' . $arreglo["texto"] . '</div>
                    <div id="div' . $arreglo["idDiv"] . '">';

    if ($opcion === 1) {
        echo '      <form action="" method="post" enctype="multipart/form-data">
                            <input type="file" name="' . $arreglo["nombreCampo"] . '" id="' . $arreglo["nombreCampo"] . '" accept=".jpg, .jpeg, .png, .pdf, .doc, .docx">
                            <input type="submit" value="Actualizar" id="btn' . $arreglo["idDiv"] . '" class="btn btn-success">
                        </form>';
    }

    echo '</div>';
}

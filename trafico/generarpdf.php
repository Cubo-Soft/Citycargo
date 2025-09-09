<?php

session_start();

include_once '../clases/conexion.php';
require_once '../fpdf17/fpdf.php';

$conexion = new Conexion();

//---------------------------------------------------------------------------------------
//fin de la insercion en la base de datos
//inicio creacion pdf
$numeroCotizacion = $_POST["numeroCotizacion"];

$pdf = new FPDF();
$pdf->SetFont('Courier', '', 10);
$pdf->AddPage();
//{creo la primera estructura de la cabecera
//marco externo
$pdf->Rect(9, 9, 191, 22);
//lineas verticales
$pdf->Line(85, 9, 85, 31);
$pdf->Line(130, 9, 130, 31);
$pdf->Line(165, 9, 165, 31);
//lineas horizontales de numero de cotización y consecutivo
$pdf->Line(130, 16.5, 200, 16.5);
$pdf->Line(130, 24, 200, 24);

//}fin de la estructura de la cabecera
$pdf->Image('../imagenes/USAPOSTAL.jpg', 10, 10, 70, 19);
$pdf->SetY(15);
$pdf->SetX(80);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(55, 10, 'COTIZACION', 0, 0, 'C');
$pdf->SetFont('Courier', '', 10);
$pdf->SetY(10);
$pdf->SetX(130);

$pdf->SetY(10);
$pdf->SetX(129);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(38, 5, 'FECHA', 0, 0, 'C');
$pdf->SetFont('Courier', '', 10);
$pdf->SetY(10);
$pdf->SetX(165);
$pdf->Cell(25, 5, date('d/m/Y'), 0, 0, 'L');

$pdf->SetY(17.5);
$pdf->SetX(130);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(38, 5, utf8_decode('N° CONSECUTIVO'), 0, 0, 'L');
$pdf->SetFont('Courier', '', 10);
$pdf->SetY(17.5);
$pdf->SetX(165);
$pdf->Cell(25, 5, $_POST["numeroCotizacion"], 0, 0, 'L');

$pdf->SetY(25);
$pdf->SetX(136);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(38, 5, utf8_decode('COMERCIAL'), 0, 0, 'L');
$pdf->SetFont('Courier', '', 10);
$pdf->SetY(25);
$pdf->SetX(165);
$pdf->Cell(25, 5, $_SESSION["nombre_usuario"], 0, 0, 'L');



//{inicio de la estructura para el cliente
$pdf->SetFillColor(166, 166, 166);
$pdf->Rect(9, 31.2, 191, 5, 'F');
$pdf->Line(9, 36, 200, 36);
$pdf->Line(9, 36, 200, 36);
$pdf->Line(9, 30, 9, 36);
$pdf->Line(200, 31, 200, 36);
$pdf->SetY(31);
$pdf->SetX(10);
$pdf->SetFont('Courier', 'B', 10.5);
$pdf->Cell(193, 5, 'DATOS DEL CLIENTE', 0, 0, 'C');
$pdf->SetFont('Courier', '', 10);
//}fin de la estructura para el cliente
//{Aqui metos los datos del cliente en la estructura creada
//Primero meto el contacto del cliente pues es este el que me indica donde debo
//dejar al eje y en la siguiente linea
//obtengo la posicion actual de y
$posActY = $pdf->GetY();
$posIniY = $pdf->GetY();
$pdf->SetY(36);
$pdf->SetX(155);
$pdf->MultiCell(45, 5, utf8_decode($_POST["contacto_cliente"]), 0, 'L', false);
//posicion de y despues de crear esta multicelda
$posDesp = $pdf->GetY();
//pongo la linea 
$pdf->Line(9, $posDesp, 200, $posDesp);
//hago la operacion para obtener el centro donde voy a poner el numero del nit
$restaPos = $posDesp - $posActY;
$porAumento = $restaPos / 2;
$posActY = $porAumento + $posActY;

$pdf->SetY($posActY);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(45, 5, 'NIT', 0, 0, 'L');
$pdf->SetFont('Courier', '', 10);
$pdf->SetX(55);
$pdf->Cell(50, 5, utf8_decode($_POST["nit_cliente"]), 0, 0, 'L');
$pdf->SetX(105);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(45, 5, 'CONTACTO', 0, 0, 'L');
$pdf->SetFont('Courier', '', 10);

//calculo la cantidad de caracteres de el telefono de cliente o nombre de cliente
//para asi saber cual imprimo primero y manejar a y
$pdf->SetY($posDesp);
$posActY1 = $pdf->GetY();

if (strlen($_POST['nombre_cliente']) > strlen($_POST['telefono_cliente'])) {

    $pdf->SetX(55);
    $pdf->MultiCell(50, 5, utf8_decode($_POST['nombre_cliente']), 0, 'L', false);
    $posDesp = $pdf->GetY();
    $pdf->Line(9, $posDesp, 200, $posDesp);
    if (($posActY1 + 5) == $posDesp) {
        $posActY = $posActY1;
    } else {
        $posActY = (($posActY1 + $posDesp) / 2) - 2.5;
    }
    $pdf->SetY($posActY);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(50, 5, 'RAZON SOCIAL', 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);
    $pdf->SetX(105);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(45, 5, utf8_decode('TELÉFONO'), 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);
    $pdf->SetX(155);
    $pdf->Cell(50, 5, $_POST["telefono_cliente"], 0, 0, 'L');

    $posActY = $pdf->SetY($posDesp);
} else if (strlen($_POST['nombre_cliente']) < strlen($_POST['telefono_cliente'])) {
    $pdf->SetX(155);
    $pdf->MultiCell(50, 5, utf8_decode($_POST['telefono_cliente']), 0, 'L', false);
    $posDesp = $pdf->GetY();
    $pdf->Line(9, $posDesp, 200, $posDesp);
    if (($posActY1 + 5) == $posDesp) {
        $posActY = $posActY1;
    } else {
        $posActY = (($posActY1 + $posDesp) / 2) - 2.5;
    }
    $pdf->SetY($posActY);
    $pdf->SetX(105);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(45, 5, utf8_decode('TELÉFONO'), 0, 0, 'L');
    $pdf->SetX(55);
    $pdf->SetFont('Courier', '', 10);
    $pdf->Cell(50, 5, $_POST['nombre_cliente'], 0, 0, 'L');
    $pdf->SetX(10);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(50, 5, 'RAZON SOCIAL', 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);

    $posActY = $pdf->SetY($posDesp);
} else {
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->SetX(10);
    $pdf->Cell(45, 5, 'RAZON SOCIAL', 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);
    $pdf->SetX(55);
    $pdf->Cell(50, 5, $_POST["nombre_cliente"], 0, 0, 'L');
    $pdf->SetX(105);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(45, 5, 'TELEFONO', 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);
    $pdf->SetX(155);
    $pdf->Cell(50, 5, $_POST["telefono_cliente"], 0, 0, 'L');
    $posDesp = $pdf->GetY() + 5;
    $pdf->Line(9, $posDesp, 200, $posDesp);
    $posActY = $pdf->SetY($posDesp);
}

//aqui va la tercera parte que es dirección y fax

if(!isset($_POST['fax_cliente'])){
    $_POST['fax_cliente']='';
}

$posActY1 = $pdf->GetY();
if (strlen($_POST['direccion_cliente']) > strlen($_POST['fax_cliente'])) {

    $pdf->SetX(55);
    $pdf->MultiCell(50, 5, utf8_decode($_POST['direccion_cliente']), 0, 'L', false);
    $posDesp = $pdf->GetY();
    $pdf->Line(9, $posDesp, 200, $posDesp);
    if (($posActY1 + 5) == $posDesp) {
        $posActY = $posActY1;
    } else {
        $posActY = (($posActY1 + $posDesp) / 2) - 2.5;
    }
    $pdf->SetY($posActY);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(50, 5, utf8_decode('DIRECCIÓN'), 0, 0, 'L');
    $pdf->SetX(105);
    $pdf->Cell(45, 5, utf8_decode('FAX'), 0, 0, 'L');
    $pdf->SetX(155);
    $pdf->SetFont('Courier', '', 10);
    $pdf->Cell(50, 5, $_POST["fax_cliente"], 0, 0, 'L');

    $posActY = $pdf->SetY($posDesp);
} else if (strlen($_POST['direccion_cliente']) < strlen($_POST['fax_cliente'])) {
    $pdf->SetX(155);
    $pdf->MultiCell(50, 5, utf8_decode($_POST['fax_cliente']), 0, 'L', false);
    $posDesp = $pdf->GetY();
    $pdf->Line(9, $posDesp, 200, $posDesp);
    if (($posActY1 + 5) == $posDesp) {
        $posActY = $posActY1;
    } else {
        $posActY = (($posActY1 + $posDesp) / 2) - 2.5;
    }
    $pdf->SetY($posActY);
    $pdf->SetX(105);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(45, 5, utf8_decode('FAX'), 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);
    $pdf->SetX(55);
    $pdf->Cell(50, 5, $_POST['direccion_cliente'], 0, 0, 'L');
    $pdf->SetX(10);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(50, 5, utf8_decode('DIRECCIÓN'), 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);

    $posActY = $pdf->SetY($posDesp);
} else {
    $pdf->SetX(10);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(45, 5, utf8_decode('DIRECCIÓN'), 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);
    $pdf->SetX(55);
    $pdf->Cell(50, 5, $_POST["direccion_cliente"], 0, 0, 'L');
    $pdf->SetX(105);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(45, 5, 'FAX', 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);
    $pdf->SetX(155);
    $pdf->Cell(50, 5, $_POST["fax_cliente"], 0, 0, 'L');
    $posDesp = $pdf->GetY() + 5;
    $pdf->Line(9, $posDesp, 200, $posDesp);
    $posActY = $pdf->SetY($posDesp);
}

//aqui va la cuarta parte que es ciudad y correo electronico
//
$consulta = "select mun_nombre from municipios where mun_id=" . $_POST["idCiudadCliente"] . ";";
$prepare = $conexion->prepare($consulta);
$prepare->execute();
$resultado = $prepare->fetchAll();
$nombreCiudad = $resultado[0]["mun_nombre"];

$posActY1 = $pdf->GetY();
if (strlen($nombreCiudad) > strlen($_POST['correoElectronico_cliente'])) {

    $pdf->SetX(55);
    $pdf->MultiCell(50, 5, utf8_decode($nombreCiudad), 0, 'L', false);
    $posDesp = $pdf->GetY();
    $pdf->Line(9, $posDesp, 200, $posDesp);
    if (($posActY1 + 5) == $posDesp) {
        $posActY = $posActY1;
    } else {
        $posActY = (($posActY1 + $posDesp) / 2) - 2.5;
    }
    $pdf->SetY($posActY);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(50, 5, utf8_decode('CIUDAD'), 0, 0, 'L');
    $pdf->SetX(105);
    $pdf->Cell(45, 5, utf8_decode('CORREO ELECTRONICO'), 0, 0, 'L');
    $pdf->SetX(155);
    $pdf->SetFont('Courier', '', 10);
    $pdf->Cell(50, 5, $_POST["correoElectronico_cliente"], 0, 0, 'L');

    $posActY = $pdf->SetY($posDesp);
} else if (strlen($nombreCiudad) < strlen($_POST['correoElectronico_cliente'])) {
    $pdf->SetX(155);
    $pdf->MultiCell(45, 5, utf8_decode($_POST['correoElectronico_cliente']), 0, 'L', false);
    $posDesp = $pdf->GetY();
    $pdf->Line(9, $posDesp, 200, $posDesp);
    if (($posActY1 + 5) == $posDesp) {
        $posActY = $posActY1;
    } else {
        $posActY = (($posActY1 + $posDesp) / 2) - 2.5;
    }
    $pdf->SetY($posActY);
    $pdf->SetX(105);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(45, 5, utf8_decode('CORREO ELECTRONICO'), 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);
    $pdf->SetX(55);
    $pdf->Cell(50, 5, $nombreCiudad, 0, 0, 'L');
    $pdf->SetX(10);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(50, 5, utf8_decode('CIUDAD'), 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);
    $posActY = $pdf->SetY($posDesp);
} else {
    $pdf->SetX(10);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(45, 5, utf8_decode('CIUDAD'), 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);
    $pdf->SetX(55);
    $pdf->Cell(50, 5, utf8_decode($nombreCiudad), 0, 0, 'L');
    $pdf->SetX(105);
    $pdf->SetFont('Courier', 'B', 10);
    $pdf->Cell(45, 5, 'CORREO ELECTRONICO', 0, 0, 'L');
    $pdf->SetFont('Courier', '', 10);
    $pdf->SetX(155);
    $pdf->Cell(50, 5, utf8_decode($_POST["correoElectronico_cliente"]), 0, 0, 'L');
    $posDesp = $pdf->GetY() + 5;
    $pdf->Line(9, $posDesp, 200, $posDesp);
    $posActY = $pdf->SetY($posDesp);
}

$posFinY = $pdf->GetY();

//lineas verticales
$pdf->Line(9, 36, 9, $posFinY);
$pdf->Line(55, 36, 55, $posFinY);
$pdf->Line(105, 36, 105, $posFinY);
$pdf->Line(155, 36, 155, $posFinY);
$pdf->Line(200, 36, 200, $posFinY);

//{Inicio de estructura para TIPO DE SERVICIO
$pdf->Rect(9, $posFinY + 0.2, 191, 5, 'F');
$pdf->Line(9, $posFinY, 9, $posFinY + 5);
$pdf->Line(200, $posFinY, 200, $posFinY + 5);
$pdf->Line(9, $posFinY + 5, 200, $posFinY + 5);
//}Fin de estructura para TIPO DE SERVICIO
//
$pdf->SetY($posFinY);
$pdf->SetX(9);
$pdf->SetFont('Courier', 'B', 10.5);
$pdf->Cell(193, 5, 'TIPO DE SERVICIO', 0, 0, 'C');

//{creo el area donde va a estar el tipo de servicio
$x=$pdf->GetY();
$y=$posFinY+5;
$y1 = $y + 22;
$pdf->Rect(9, $y, 191, 22);
$pdf->Line(42, $y, 42, $y1);
$pdf->Line(74, $y, 74, $y1);
$pdf->Line(105, $y, 105, $y1);
$pdf->Line(137, $y, 137, $y1);
$pdf->Line(169, $y, 169, $y1);
$y1 = $y1 - 5;
$pdf->Line(9, $y1, 200, $y1);
//{fin area tipo de servicio

$y1=$pdf->GetY();
$pdf->SetFont('Courier', 'B', 10);
$y1 = $y1 + 5;
$pdf->SetY($y1);
$pdf->Cell(31, 5, 'MENSAJEROS', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(31, 5, 'INMEDIATOS Y', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(31, 5, 'CARGA', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(31, 5, 'INMEDIATA', 0, 0, 'C');

$y1 = $y1 - 12;
$pdf->SetY($y1);
$pdf->Cell(95, 5, 'CARGA', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(95, 5, 'MASIVA,', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(95, 5, 'SEMIVASIVA', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(95, 5, 'Y PAQUETEO', 0, 0, 'C');

$y1 = $y1 - 8;
$pdf->SetY($y1);
$pdf->Cell(160, 5, 'MENSAJERIA', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(160, 5, 'EXPRESA', 0, 0, 'C');

$y1 = $y1 - 4;
$pdf->SetY($y1);
$pdf->Cell(222, 5, 'MUDANZAS', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(222, 5, 'LOCALES Y', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(222, 5, 'NACIONALES', 0, 0, 'C');

$y1 = $y1 - 8;
$pdf->SetY($y1);
$pdf->Cell(287, 5, 'PROYECTOS', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(287, 5, 'ESPECIALES EN', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(287, 5, 'LOGISTICA', 0, 0, 'C');

$y1 = $y1 - 8;
$pdf->SetY($y1);
$pdf->Cell(350, 5, 'CASILLEROS', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(350, 5, 'INTER', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(350, 5, 'NACIONALES', 0, 0, 'C');
$y1 = $y1 + 5;
$pdf->SetY($y1);
//busco donde ubicar la x del tipo de servicio
$pdf->SetFont('Courier', 'B', 10);
switch ($_POST["tipoServicio"]) {
    case "M_I_C_I":
        $pdf->Cell(31, 5, 'X', 0, 0, 'C');
        break;
    case "C_M_S_P":
        $pdf->Cell(95, 5, 'X', 0, 0, 'C');
        break;
    case "M_E":
        $pdf->Cell(160, 5, 'X', 0, 0, 'C');
        break;
    case "M_L_N":
        $pdf->Cell(222, 5, 'X', 0, 0, 'C');
        break;
    case "P_E_L":
        $pdf->Cell(287, 5, 'X', 0, 0, 'C');
        break;
    case "C_I":
        $pdf->Cell(350, 5, 'X', 0, 0, 'C');
        break;
}

//{Inicio de estructura para DESCRIPCION DEL SERVICIO
$posFinY=$pdf->GetY()+5;
$pdf->Rect(9, $posFinY + 0.2, 191, 5, 'F');
$pdf->Line(9, $posFinY, 9, $posFinY + 5);
$pdf->Line(200, $posFinY, 200, $posFinY + 5);
$pdf->Line(9, $posFinY + 5, 200, $posFinY + 5);
//}Fin de estructura para DESCRIPCION DEL SERVICIO
//
$pdf->SetY($posFinY);
$pdf->SetX(9);
$pdf->SetFont('Courier', 'B', 10.5);
$pdf->Cell(193, 5, utf8_decode('DESCRIPCIÓN DEL SERVICIO'), 0, 0, 'C');

$posFinY=$pdf->GetY();
$pdf->SetFont('Courier', '', 9);
$pdf->SetXY(9, $posFinY + 5);
$pdf->MultiCell(191, 4, utf8_decode($_POST["detalle_servicio"]), 1, 'L', false);
$y1=$pdf->GetY();

//creo el area para el texto VALOR SERVICIO
$x=9;
$pdf->SetX(9);
$pdf->SetY($y1);
$pdf->Rect($x, $y1, 191, 5, 'F');
$pdf->Rect($x, $y1, 191, 5);
$pdf->SetFont('Courier', 'B', 10.5);
$pdf->Cell(193, 5, 'VALOR DEL SERVICIO', 0, 0, 'C');
//creo el marco de ORIGEN,DESTINO,CANTIDAD,VALOR MINIMO DECLARADO,V/R FLETE,V/R SEGURO,TOTAL
$y1 = $y1 + 5;
$x1=9;
$pdf->Rect($x1, $y1, 191, 13);
$y = $y1 + 13;
$pdf->Line(32, $y, 32, $y1);
$pdf->Line(55, $y, 55, $y1);
$pdf->Line(79, $y, 79, $y1);
$pdf->Line(103, $y, 103, $y1);
$pdf->Line(128, $y, 128, $y1);
$pdf->Line(153, $y, 153, $y1);
$pdf->Line(178, $y, 178, $y1);
////coloco los textos ORIGEN,DESTINO,CANTIDAD,VALOR MINIMO DECLARADO,V/R FLETE,V/R SEGURO,TOTAL
//$pdf->SetFont('Courier', 'B', 10);
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(20, 5, 'ORIGEN', 0, 0, 'C');
$pdf->Cell(28, 5, 'DESTINO', 0, 0, 'C');
$pdf->Cell(18, 5, 'CANTIDAD', 0, 0, 'C');
$y1 = $y1 - 4;
$pdf->SetY($y1);
$pdf->Cell(162, 5, 'VALOR', 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(162, 5, utf8_decode('MÍNIMO'), 0, 0, 'C');
$y1 = $y1 + 4;
$pdf->SetY($y1);
$pdf->Cell(162, 5, 'DECLARADO', 0, 0, 'C');
$y1 = $y1 - 4;
$pdf->SetY($y1);
$pdf->Cell(211, 5, 'V/R FLETE', 0, 0, 'C');
$pdf->SetY($y1);
$pdf->Cell(262, 5, 'V/R SEGURO', 0, 0, 'C');
$pdf->SetY($y1);
$pdf->Cell(312, 5, 'OT. CARGOS', 0, 0, 'C');
$pdf->SetY($y1);
$pdf->Cell(358, 5, 'TOTAL', 0, 0, 'C');

//armo la estructura para los servicios
//este for es para las lineas horizontales
$pdf->SetFont('Courier', '', 10);

$y2 = $y1;
$y1 = $y1 + 9;
//----
$pdf->SetXY(9, $y1);

$origen = "origen";
$destino = "destino";
$cantidad = "cantidad";
$vaSerVlrDeclarar = "vaSerVlrDeclarar";
$vaSerVlrFlete = "vaSerVlrFlete";
$vaSerVlrSeguro = "vaSerVlrSeguro";
$vaOtrosCargos = "vaOtrosCargos";
$vaSerVlrTotal = "vaSerVlrTotal";

$posYantesCiclo = $pdf->GetY();

for ($i = 1; $i < 16; $i++) {

    //obtengo la posicion actual de y
    $posActY = $pdf->GetY();

    //redifino el tamaño de la fuente
    $pdf->SetFontSize(8);

    $origen .=$i;
    $destino.=$i;
    $cantidad.=$i;
    $vaSerVlrDeclarar .= $i;
    $vaSerVlrFlete .= $i;
    $vaSerVlrSeguro .= $i;
    $vaOtrosCargos .= $i;
    $vaSerVlrTotal .= $i;

    if ($_POST[$origen] != null) {

        $pdf->MultiCell(22.5, 5, utf8_decode($_POST[$origen]), 0, 'L', false);

        //obtener posicion de y despues de imprimir el texto
        $posTempY1 = $pdf->GetY();

        //devuelvo la posicion de y donde estaba antes de imprimir origen1
        $pdf->SetY($posActY);
        $pdf->SetX(32);
        $pdf->MultiCell(25, 5, utf8_decode($_POST[$destino]), 0, 'L', false);

        //obtengo otra vez la posicion de y despues de imprimir origen1
        $posTempY2 = $pdf->GetY();

        if ($posTempY1 > $posTempY2) {
            $pdf->Line(9, $posTempY1, 200, $posTempY1);
            //hago la operación para saber donde voy a poner y
            $resulResta = $posTempY1 - $posActY;
            $porcAument = $resulResta / 3;
            $posMitadY = $porcAument + $posActY;
            $posNuevaY = $posTempY1;
        } else if ($posTempY1 === $posTempY2) {
            $pdf->Line(9, $posTempY2, 200, $posTempY2);
            //hago la operación para saber donde voy a poner y            
            $posMitadY = $posActY;
            $posNuevaY = $posTempY1;
        } else {
            $pdf->Line(9, $posTempY2, 200, $posTempY2);
            //hago la operación para saber donde voy a poner y
            $resulResta = $posTempY2 - $posActY;
            $porcAument = $resulResta / 3;
            $posMitadY = $porcAument + $posActY;
            $posNuevaY = $posTempY2;
        }       
        $pdf->SetY($posMitadY);
        $pdf->SetX(54);
        //coloco la cantidad
        $pdf->Cell(36, 5, utf8_decode($_POST[$cantidad]), 0, 0, 'L');
        $pdf->SetX(78);
         $pdf->SetFontSize(9);
        //coloco el valor del servicio a declarar        
        $pdf->Cell(35, 5, '$' . str_replace(",", ".", number_format($_POST[$vaSerVlrDeclarar])), 0, 0, 'L');
        $pdf->SetX(103);
        //coloco el valor del flete        
        $pdf->Cell(35, 5, '$' . str_replace(",", ".", number_format($_POST[$vaSerVlrFlete])), 0, 0, 'L');
        $pdf->SetX(128);
        //coloco el valor del seguro
        $pdf->Cell(35, 5, '$' . str_replace(",", ".", number_format($_POST[$vaSerVlrSeguro])), 0, 0, 'L');
        //coloco el valor de otros cargos
        $pdf->SetX(153);
        $pdf->Cell(35, 5, '$' . str_replace(",", ".", number_format($_POST[$vaOtrosCargos])), 0, 0, 'L');
        //coloco el valor de total
        $pdf->SetX(178);
        $pdf->Cell(35, 5, '$' . str_replace(",", ".", number_format($_POST[$vaSerVlrTotal])), 0, 0, 'L');

        $pdf->Line(32, $posActY, 32, $posNuevaY);
        $pdf->Line(55, $posActY, 55, $posNuevaY);
        $pdf->Line(79, $posActY, 79, $posNuevaY);
        $pdf->Line(103, $posActY, 103, $posNuevaY);
        $pdf->Line(128, $posActY, 128, $posNuevaY);
        $pdf->Line(153, $posActY, 153, $posNuevaY);
        $pdf->Line(178, $posActY, 178, $posNuevaY);

        $pdf->SetY($posNuevaY);
    } else {
        $i = 16;
        $posYDespuesCiclo = $posNuevaY;
    }

    $origen = "origen";
    $destino = "destino";
    $cantidad = "cantidad";
    $vaSerVlrDeclarar = "vaSerVlrDeclarar";
    $vaSerVlrFlete = "vaSerVlrFlete";
    $vaSerVlrSeguro = "vaSerVlrSeguro";
    $vaOtrosCargos = "vaOtrosCargos";
    $vaSerVlrTotal = "vaSerVlrTotal";
}
$pdf->SetX(9);
$y1=$posYantesCiclo;
$y2=$posYDespuesCiclo;
//lineas laterales
$pdf->Line(9, $y2, 9, $y1);
$pdf->Line(200, $y2, 200, $y1);

//normalizo el tamaño inicial de la fuente
$pdf->SetFontSize(10);
//
//creo el area donde va a estar el letrero de TOTAL A FACTURAR
$pdf->Rect($x, $y2, 191, 5,'F');
$y1 = $pdf->GetY();
$y = $y1 + 5;
$pdf->Line(172, $y, 172, $y1);
$pdf->SetY($y1);
$pdf->SetX(100);
$pdf->SetFont('Courier', 'B', 10.5);
//$pdf->Rect(9, $y1, 191, 5, 'F');
$pdf->Cell(30, 5, 'TOTAL A FACTURAR', 0, 0, 'C');
$pdf->SetX(170);
$pdf->Cell(30, 5, '$' . str_replace(",", ".", number_format($_POST['vaSerOrigen15'])), 0, 0, 'R');
$pdf->SetFont('Courier', '', 9);

//creo el area donde va el texto de las CONDICIONES DEL SERVICIO
$y1 = $pdf->GetY()+5;
$pdf->SetXY(90, $y1);
$pdf->MultiCell(110, 4, utf8_decode($_POST["observaciones_forma_pago"]), 1, 'L', false);
//creo el area donde va a ir las CONDICIONES DEL SERVICIO
$y3 = $pdf->GetY();
$y2 = $y3 - $y1;
$y5 = $y3;
$pdf->Rect(9, $y1, 81, $y2);
//busco donde ubicar el texto OBSERVACIONES
$y3 = $y1 + $y2;
$y2 = $y2 / 2;
$y4 = $y2 + $y1 - 3;
$pdf->SetXY(9, $y4);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(90, 4, 'OBSERVACIONES', 0, 0, 'C');
$pdf->SetFont('Courier', '', 10);
////creo la estructura para FORMA DE PAGO
$pdf->SetXY(9, $y5);
$y1 = $pdf->GetY();
$pdf->Rect(9, $y1, 191, 5);
$pdf->Rect(9, $y1, 45, 5, 'F');
$pdf->Rect(9, $y1, 45, 5);
$y2 = $y1 + 5;
//$pdf->Line(55, $y1, 55, $y2);
$pdf->SetX(15);
$pdf->SetFont('Courier', 'B', 10.5);
$pdf->Cell(30, 5, 'FORMA DE PAGO', 0, 0, 'L');
$pdf->SetFont('Courier', '', 10);
//Coloco los letreritos de EFECTIVO,CREDITO y OTRO con sus respectivas lineas
$pdf->Line(90, $y1, 90, $y2);
$pdf->SetX(55);
$pdf->Cell(30, 5, 'CONTADO:', 0, 0, 'L');
$pdf->Line(125, $y1, 125, $y2);
$pdf->SetX(90);
$pdf->Cell(30, 5, 'CREDITO:', 0, 0, 'L');
$pdf->SetX(125);
$pdf->Cell(30, 5, 'OTRO:', 0, 0, 'L');
$pdf->SetX(141);
$pdf->Cell(30, 5, 'Cual:', 0, 0, 'L');
//pongo una x si es EFECTIVO o CREDITO 
$pdf->SetFont('Courier', 'B', 10);
switch ($_POST["formaPago"]) {
    case "CONTADO":
        $pdf->SetX(65);
        $pdf->Cell(30, 5, 'X', 0, 0, 'C');
        break;
    case "CREDITO":
        $pdf->SetX(100);
        $pdf->Cell(30, 5, 'X', 0, 0, 'C');
        break;
    case "OTRO":
        $pdf->SetX(125);
        $pdf->Cell(30, 5, 'X', 0, 0, 'C');
        $pdf->SetX(150);
        $pdf->Cell(30, 5, utf8_decode($_POST["textformaPago"]), 0, 0, 'L');
        break;
}
//coloco el texto del cumplimiento ese...jejeje
$pdf->SetFont('Courier', '', 8);
$pdf->SetXY(9,$y2);
$pdf->MultiCell(191, 5,utf8_decode('En cumplimiento con nuestro sistema de gestión de calidad para la prestación de nuestros servicios, USA POSTAL requiere que nos envíe su aprobación por escrito de las tarifas'), 1, 'C', false);
$pdf->SetX(9);
$pdf->SetFontSize(10);
$pdf->MultiCell(191, 5,utf8_decode('       USA POSTAL S.A. - NIT: 830.112.988-3 - CARRERA 28BIS No. 53A-62 - BOGOTÁ D.C.                    Resolución MinTransportes: 000878 de 2007 - Decreto 173 de 2001                                    Resolución 1062 Mensajeria Expresa MINTIC                                www.usapostal.com.co '), 1, 'C', false);
$pdf->Rect(9, 9, 191, $pdf->GetY()-9);
$numeroCotizacion = "COTIZACION_NUMERO_" . $_POST["numeroCotizacion"] . ".pdf";
$pdf->Output($numeroCotizacion, "D");

//---------------------------------------------------------------------------------------------------
if ($_POST["id_cliente"] <> "") {
           
    $cli_id = $_POST["id_cliente"];
    
} else {
    $consulta = "insert into cliente values (null,'" . $_POST["nombre_cliente"] . "',"
            . "'" . $_POST["contacto_cliente"] . "','" . $_POST["direccion_cliente"] . "',"
            . "'" . $_POST["correoElectronico_cliente"] . "','" . trim($_POST["nit_cliente"]) . "',"
            . "'" . $_POST["telefono_cliente"] . "','" . $_POST["fax_cliente"] . "','ACTIVO');";    
    
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
   
    //inserto en la tabla tabla asesor_empresa los valores correspondientes al empleado y el cliente
    $consulta="insert into asesor_empresa (id,cedula,nit)"
            . " values (null,".$_SESSION["emp_cedula"].",".$_POST["nit_cliente"].");";
    $prepare=$conexion->prepare($consulta);
    $prepare->execute();
}



//lleno la información de la tabla cotizacion

if($_POST["formaPago"]=='OTRO'){
    $formaPago=$_POST["textformaPago"];
}else{
    $formaPago=$_POST["formaPago"];
}

$consulta = "insert into cotizacion (cot_id,cot_numero,cot_fecha,cot_detalleServicio,"
        . "cot_observaciones,cot_fomaPago,cot_tipoServicio,cot_estado,"
        . "cliente_cli_id,empleados_emp_id) values (null,"
        . "'" . $_POST["numeroCotizacion"] . "','" . date("Y-m-d") . "','" . $_POST["detalle_servicio"] . "',"
        . "'" . $_POST["observaciones_forma_pago"] . "','" . $formaPago . "',"
        . "'" . $_POST["tipoServicio"] . "','A'," . $cli_id . "," . $_SESSION["emp_id"] . ")";

$prepare = $conexion->prepare($consulta);
$prepare->execute();

$consulta = "select max(cot_id) as cot_id from cotizacion;";

$prepare = $conexion->prepare($consulta);
$prepare->execute();
$arreglo = $prepare->fetchAll();

$cot_id = $arreglo[0]["cot_id"];

//lleno de la tabla valorServicio
$origen = "origen";
$destino = "destino";
$cantidad = "cantidad";
$vaSerVlrDeclarar = "vaSerVlrDeclarar";
$vaSerVlrFlete = "vaSerVlrFlete";
$vaSerVlrSeguro = "vaSerVlrSeguro";
$vaOtrosCargos = "vaOtrosCargos";
$vaSerVlrTotal = "vaSerVlrTotal";

$valoresInsertar2 = '';

for ($i = 1; $i < 16; $i++) {

    $origen.=$i;
    $destino.=$i;
    $cantidad.=$i;
    $vaSerVlrDeclarar .= $i;
    $vaSerVlrFlete .= $i;
    $vaSerVlrSeguro .= $i;
    $vaOtrosCargos.= $i;
    $vaSerVlrTotal .= $i;
    
    if ($_POST[$origen] === "") {
        $i = 16;
    } else {
        $valoresInsertar2 .= '(null,"' . $_POST[$cantidad] . '","' . $_POST[$vaSerVlrDeclarar] . '",'
                . '"' . $_POST[$vaSerVlrFlete] . '","' . $_POST[$vaSerVlrSeguro] . '","' . $_POST[$vaOtrosCargos] .'","' . $_POST[$origen] . '",'
                . '"' . $_POST[$destino] . '",' . $cot_id . '),';
    }

    $origen = "origen";
    $destino = "destino";
    $cantidad = "cantidad";
    $vaSerVlrDeclarar = "vaSerVlrDeclarar";
    $vaSerVlrFlete = "vaSerVlrFlete";
    $vaSerVlrSeguro = "vaSerVlrSeguro";
    $vaOtrosCargos="vaOtrosCargos";
    $vaSerVlrTotal = "vaSerVlrTotal";
}

$tamaño = strlen($valoresInsertar2);
$tamaño = $tamaño - 1;
$valoresInsertar2 = substr($valoresInsertar2, 0, $tamaño);

$consulta = "insert into valorservicio values " . $valoresInsertar2;

$prepare = $conexion->prepare($consulta);
$prepare->execute();

$numeroCotizacion = $_POST["numeroCotizacion"] + 1;

$consulta = "insert into numeroscotizacion values (null,'" . $numeroCotizacion . "');";

$prepare = $conexion->prepare($consulta);
$prepare->execute();

$conexion = null;


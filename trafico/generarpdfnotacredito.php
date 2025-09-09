<?php

session_start();

include_once '../clases/conexion.php';
include_once '../fpdf17/fpdf.php';

$conexion = new Conexion();

$numero = $_POST['numeroCotizacion'] + 1;

$consulta = "insert into numeroscuentas values (null," . $numero . ");";
$prepare = $conexion->prepare($consulta);
$prepare->execute();

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
$pdf->Image('../imagenes/logocity.jpg', 10, 10, 70, 19);
$pdf->SetY(15);
$pdf->SetX(80);
$pdf->SetFont('Courier', 'B', 10);
$pdf->Cell(55, 10, utf8_decode('NOTA CREDITO'), 0, 0, 'C');
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

//{Inicio de estructura para DESCRIPCIÓN DE LA OPERACION
$pdf->Rect(9, $posFinY + 0.2, 191, 5, 'F');
$pdf->Line(9, $posFinY, 9, $posFinY + 5);
$pdf->Line(200, $posFinY, 200, $posFinY + 5);
$pdf->Line(9, $posFinY + 5, 200, $posFinY + 5);
//}Fin de estructura para DESCRIPCIÓN DE LA OPERACIÓN
//
$pdf->SetY($posFinY);
$pdf->SetX(9);
$pdf->SetFont('Courier', 'B', 10.5);
$pdf->Cell(193, 5, utf8_decode('DESCRIPCIÓN DE LA OPERACIÓN'), 0, 0, 'C');
$pdf->SetFont('Courier', '', 10.5);

$guia = "guia";
$numFac = "numFac";
$fecFac = "fecFac";
$vlrUnit = "vlrUnit";

if ($_POST["guia1"] > 0) {

    $posActY = $pdf->GetY();
    $posFinY = $posActY + 5;

    $pdf->Line(9, $posFinY, 9, $posFinY + 5);
    $pdf->Line(200, $posFinY, 200, $posFinY + 5);
    $pdf->Line(9, $posFinY + 5, 200, $posFinY + 5);

    $pdf->Line(55, $posActY + 5, 55, $posFinY + 5);
    $pdf->Line(105, $posActY + 5, 105, $posFinY + 5);
    $pdf->Line(155, $posActY + 5, 155, $posFinY + 5);

    $pdf->SetFont('Courier', 'B', 10.5);

    $pdf->SetY($posActY + 5);
    $pdf->SetX(20);
    $pdf->Cell(50, 5, utf8_decode('NÚMERO GUIA'), 0, 0, 'L');
    $posActX = @$posActX + 63;
    $pdf->SetX($posActX);
    $pdf->Cell(50, 5, utf8_decode('NÚMERO FACTURA'), 0, 0, 'L');
    $posActX = $posActX + 52;
    $pdf->SetX($posActX);
    $pdf->Cell(50, 5, utf8_decode('FECHA FACTURA'), 0, 0, 'L');
    $posActX = $posActX + 48;
    $pdf->SetX($posActX);
    $pdf->Cell(50, 5, utf8_decode('V/R UNITARIO'), 0, 0, 'L');

    $pdf->SetFont('Courier', '', 10.5);

    $posActY = $posFinY + 5;
    $pdf->SetY($posActY);
    $posAntY = $pdf->GetY();

    $posActX = 10;

    for ($a = 1; $a < 17; $a++) {

        $guia .= $a;
        $numFac .= $a;
        $fecFac .= $a;
        $vlrUnit .= $a;

        if (@$_POST[$guia] > 0) {

            $consulta = "select Notas from servicio_guias where numeroGuia=" . $_POST[$guia] . ";";
            $prepare = $conexion->prepare($consulta);
            $prepare->execute();
            $resultado = $prepare->fetchAll();
            $mensaje = $resultado[0]["Notas"];
            
            $mensaje.=$mensaje."Se ha creado una nota credito a esta guía. Usuario:".$_SESSION["nombre_usuario"]." Fecha:".date("Y-m-d")."";
            
            $consulta="update servicio_guias set Notas='".$mensaje."' where numeroGuia=".$_POST[$guia].";";            
            $prepare = $conexion->prepare($consulta);
            $prepare->execute();

            $pdf->SetX($posActX);
            $pdf->Cell(50, 5, utf8_decode($_POST[$guia]), 0, 0, 'L');
            $posActX = $posActX + 45;
            $pdf->SetX($posActX);
            $pdf->Cell(50, 5, utf8_decode($_POST[$numFac]), 0, 0, 'L');
            $posActX = $posActX + 50;
            $pdf->SetX($posActX);
            $pdf->Cell(50, 5, utf8_decode($_POST[$fecFac]), 0, 0, 'L');
            $posActX = $posActX + 50;
            $pdf->SetX($posActX);
            $ultimoX = $posActX;
            $pdf->Cell(35, 5, utf8_decode($_POST[$vlrUnit]), 0, 0, 'L');

            $posActY = $pdf->GetY();
            $pdf->Line(9, $posActY + 5, 200, $posActY + 5);

            $posActY = $posActY + 5;
            $pdf->SetY($posActY);
            $pdf->SetX(0);

            $posActX = 10;
            $posFinY = $posFinY + 5;
        } else {
            $a = 17;
        }

        $guia = "guia";
        $numFac = "numFac";
        $fecFac = "fecFac";
        $vlrUnit = "vlrUnit";
    }

    $posYantes = $posFinY + 5;
    $posFinY = $posFinY + 5;

    $pdf->SetY($posFinY);

    $pdf->Line(9, $posAntY, 9, $posFinY);
    $pdf->Line(55, $posAntY, 55, $posFinY);
    $pdf->Line(105, $posAntY, 105, $posFinY);
    $pdf->Line(155, $posAntY, 155, $posFinY);
    $pdf->Line(200, $posAntY, 200, $posFinY);

    $posActX = $pdf->GetX();
    $pdf->SetX(140);
    $pdf->SetFont('Courier', 'B', 11);
    $pdf->Cell(30, 5, utf8_decode('TOTAL'), 0, 'L', FALSE);
    $pdf->SetX($ultimoX);
    $pdf->Cell(35, 5, utf8_decode($_POST['vaSerOrigen15']), 0, 'C', FALSE);
    $pdf->SetX(10);
    $pdf->SetFont('Courier', '', 10.5);
    $pdf->MultiCell(90, 5, '' . utf8_decode($_POST["vlrLetras"]), 0, 'L', FALSE);

    $posFinY = $pdf->GetY();

    $pdf->Line(9, $posAntY, 9, $posFinY);
    $pdf->Line(200, $posAntY, 200, $posFinY);
    $pdf->Line(140, $posYantes, 140, $posFinY);
    $pdf->Line(155, $posAntY, 155, $posFinY);
    $pdf->Line(9, $posFinY, 200, $posFinY);

    $pdf->SetY($posFinY);
    $pdf->Rect(9, $posFinY, 191, 5, 'F');
    $pdf->setX(9);
    $pdf->SetFont('Courier', 'B', 10.5);
    $pdf->MultiCell(191, 5, utf8_decode('DESCRIPCIÓN'), 1, 'C', FALSE);
    $pdf->SetFont('Courier', '', 10.5);
    $pdf->setX(9);
    $pdf->MultiCell(191, 5, utf8_decode($_POST["detalle_servicio"]), 1, 'C', FALSE);
    $posActY = $pdf->GetY();
    $posActY = $posActY + 30;
    $pdf->SetY($posActY);
    $pdf->Line(30, $posActY, 80, $posActY);
    $pdf->Line(130, $posActY, 180, $posActY);
    $pdf->SetX(45);
    $pdf->Cell(35, 5, utf8_decode('ELABORA'), 0, 'C', FALSE);
    $pdf->SetX(150);
    $pdf->Cell(35, 5, utf8_decode('RECIBE'), 0, 'C', FALSE);
} else {

    $posActY = $pdf->GetY();
    $posFinY = $posActY + 5;

    $pdf->Line(9, $posFinY, 9, $posFinY + 5);
    $pdf->Line(200, $posFinY, 200, $posFinY + 5);
    $pdf->Line(9, $posFinY + 5, 200, $posFinY + 5);

    $pdf->Line(65, $posActY + 5, 65, $posFinY + 5);
    $pdf->Line(130, $posActY + 5, 130, $posFinY + 5);

    $pdf->SetFont('Courier', 'B', 10.5);
    $pdf->SetY($posFinY);
    $posActX = 20;
    $pdf->SetX($posActX);
    $pdf->Cell(50, 5, utf8_decode('NÚMERO FACTURA'), 0, 0, 'L');
    $pdf->SetX($posActX + 63);
    $pdf->Cell(50, 5, utf8_decode('FECHA FACTURA'), 0, 0, 'L');
    $pdf->SetX($posActX + 130);
    $pdf->Cell(50, 5, utf8_decode('V/R UNITARIO'), 0, 0, 'L');

    $pdf->SetFont('Courier', '', 10.5);
    $posFinY = $pdf->GetY();

    $posActY = $posFinY + 5;
    $pdf->SetY($posActY);
    $posAntY = $pdf->GetY();

    $posActX = 10;

    for ($a = 1; $a < 17; $a++) {

        $numFac .= $a;
        $fecFac .= $a;
        $vlrUnit .= $a;

        if (@$_POST[$numFac] > 0) {

            $pdf->SetX($posActX);
            $pdf->Cell(50, 5, utf8_decode($_POST[$numFac]), 0, 0, 'L');
            $posActX = $posActX + 55;
            $pdf->SetX($posActX);
            $pdf->Cell(50, 5, utf8_decode($_POST[$fecFac]), 0, 0, 'L');
            $posActX = $posActX + 65;
            $pdf->SetX($posActX);
            $ultimoX = $posActX;
            $pdf->Cell(35, 5, utf8_decode($_POST[$vlrUnit]), 0, 0, 'L');

            $posActY = $pdf->GetY();
            $pdf->Line(9, $posActY + 5, 200, $posActY + 5);

            $posActY = $posActY + 5;
            $pdf->SetY($posActY);
            $pdf->SetX(0);

            $posActX = 10;
            $posFinY = $posFinY + 5;
        } else {
            $a = 17;
        }

        $numFac = "numFac";
        $fecFac = "fecFac";
        $vlrUnit = "vlrUnit";
    }

    $posYantes = $posFinY + 5;
    $posFinY = $posFinY + 5;

    $pdf->SetY($posFinY);

    $pdf->Line(9, $posAntY, 9, $posFinY);
    $pdf->Line(65, $posAntY, 65, $posFinY);
    $pdf->Line(130, $posAntY, 130, $posFinY);
    $pdf->Line(200, $posAntY, 200, $posFinY);

    $posActX = $pdf->GetX();
    $pdf->SetX(110);
    $pdf->SetFont('Courier', 'B', 11);
    $pdf->Cell(30, 5, utf8_decode('TOTAL'), 0, 'L', FALSE);
    $pdf->SetX($ultimoX);
    $pdf->Cell(35, 5, utf8_decode($_POST['vaSerOrigen15']), 0, 'C', FALSE);
    $pdf->SetX(10);
    $pdf->SetFont('Courier', '', 10.5);
    $pdf->MultiCell(90, 5, '' . utf8_decode($_POST["vlrLetras"]), 0, 'L', FALSE);

    $posFinY = $pdf->GetY();

    $pdf->Line(9, $posAntY, 9, $posFinY);
    $pdf->Line(200, $posAntY, 200, $posFinY);
    $pdf->Line(130, $posAntY, 130, $posFinY);
    $pdf->Line(9, $posFinY, 200, $posFinY);

    $pdf->SetY($posFinY);
    $pdf->Rect(9, $posFinY, 191, 5, 'F');
    $pdf->setX(9);
    $pdf->SetFont('Courier', 'B', 10.5);
    $pdf->MultiCell(191, 5, utf8_decode('DESCRIPCIÓN'), 1, 'C', FALSE);
    $pdf->SetFont('Courier', '', 10.5);
    $pdf->setX(9);
    $pdf->MultiCell(191, 5, utf8_decode($_POST["detalle_servicio"]), 1, 'C', FALSE);
    $posActY = $pdf->GetY();
    $posActY = $posActY + 30;
    $pdf->SetY($posActY);
    $pdf->Line(30, $posActY, 80, $posActY);
    $pdf->Line(130, $posActY, 180, $posActY);
    $pdf->SetX(45);
    $pdf->Cell(35, 5, utf8_decode('ELABORA'), 0, 'C', FALSE);
    $pdf->SetX(150);
    $pdf->Cell(35, 5, utf8_decode('RECIBE'), 0, 'C', FALSE);
}

$numeroCotizacion = "NOTA_CREDITO_" . $_POST["numeroCotizacion"] . ".pdf";
$pdf->Output($numeroCotizacion, "D");


$conexion = null;

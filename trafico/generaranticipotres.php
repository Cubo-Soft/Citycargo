<?php

session_start();

include_once '../clases/conexion.php';
include_once '../fpdf17/fpdf.php';
include_once '../clases/numerosALetras.php';

$conexion = new Conexion();

$idservicio = $_GET["idservicio"];

$cifraEnLetra = new CifrasEnLetras();

$pdf = new FPDF();
$pdf->SetFont('Courier', '', 9);
$pdf->AddPage();
$pdf->SetDrawColor(0, 0, 0);
$pdf->SetLineWidth(0.1);
$pdf->Line(9.5, 9.5, 200, 9.5);
$pdf->Image('../imagenes/logocity.jpg', 10, 10, 70, 20);
$pdf->SetY(15);
$pdf->SetX(80);
$pdf->Cell(40, 20, 'ANTICIPO', 0, 0, 'C');
$pdf->SetY(10);
$pdf->SetX(130);
$pdf->Cell(38, 5, 'CONSECUTIVO:', 0, 0, 'L');
$pdf->SetY(10);
$pdf->SetX(168);

$consulta = "select * "
        . "from valoresanticipos "
        . "where idservicio=" . $idservicio . ";";
$prepare = $conexion->prepare($consulta);
$prepare->execute();
$valoresAnticipos = $prepare->fetchAll();
$numeroAnticipo = $valoresAnticipos[0]["val_numeroAnticipo"];

$pdf->Cell(10, 5, $numeroAnticipo, 0, 0, 'L');
$pdf->SetY(15);
$pdf->SetX(130);
$pdf->Cell(38, 5, 'DEPARTAMENTO:', 0, 0, 'L');
$pdf->SetY(15);
$pdf->SetX(168);
$pdf->Cell(20, 5, $_SESSION["departamento"], 0, 0, 'L');
$pdf->SetY(20);
$pdf->SetX(130);
$pdf->Cell(38, 5, 'HOJA:', 0, 0, 'L');
$pdf->SetY(20);
$pdf->SetX(168);
$pdf->Cell(25, 5, '1', 0, 0, 'L');
$pdf->SetY(25);
$pdf->SetX(130);
$pdf->Cell(38, 5, 'FECHA:', 0, 0, 'L');
$pdf->SetY(25);
$pdf->SetX(168);
$pdf->Cell(25, 5, date("Y-m-d"), 0, 0, 'L');
$pdf->Line(9, 30, 200, 30);
$pdf->SetY(30);
$pdf->SetX(10);
$pdf->Cell(193, 5, 'DATOS DE LA PERSONA NATURAL O BENEFICIARIA DEL PAGO', 0, 0, 'C');
$pdf->Line(9, 35, 200, 35);

$manejoY = $pdf->GetY();
$manejoY = $manejoY + 5;

$pdf->SetY($manejoY);
$pdf->SetX(10);
$pdf->Cell(42, 5, 'PROPIETARIO:', 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(33);

$consulta = "select concat(c.cond_nombres,' ',c.cond_apellidos) as nombrePropietario,s.cedulaPropietario,s.placa,"
        . "c.cond_direccion,c.cond_telefono,c.municipios_mun_id "
        . "from conductores as c,servicio as s "
        . "where s.cedulaPropietario=c.cond_identificacion "
        . "and s.idservicio=" . $idservicio . " "
        . "and c.estado='ACTIVO';";
//echo $consulta;
$prepare = $conexion->prepare($consulta);
$prepare->execute();
$propietario = $prepare->fetchAll();
$nombrePropietario = $propietario[0]["nombrePropietario"];
$cedulaPropietario = $propietario[0]["cedulaPropietario"];
$placa = $propietario[0]["placa"];
$direccionPropietario = $propietario[0]["cond_direccion"];
$telefonoPropietario = $propietario[0]["cond_telefono"];
$mun_id = $propietario[0]["municipios_mun_id"];

$pdf->Cell(50, 5, utf8_decode($nombrePropietario), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(95);
$pdf->Cell(60, 5, utf8_decode("C.C. PROP.:"), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(118);
$pdf->Cell(50, 5, $cedulaPropietario, 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(155);
$pdf->Cell(60, 5, "PLACA:", 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(175);
$pdf->Cell(50, 5, $placa, 0, 0, 'L');

$manejoY = $manejoY + 5;

$pdf->SetY($manejoY);
$pdf->SetX(10);
$pdf->Cell(45, 5, utf8_decode('DIRECCIÓN:'), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(30);
$pdf->Cell(50, 5, $direccionPropietario, 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(95);
$pdf->Cell(45, 5, utf8_decode('TELÉFONO:'), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(118);
$pdf->Cell(50, 5, $telefonoPropietario, 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(155);
$pdf->Cell(60, 5, "CIUDAD:", 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(175);
$consulta = "select municipios.mun_nombre "
        . "from municipios "
        . "where municipios.mun_id=" . $mun_id . ";";
//echo $consulta;
$prepare = $conexion->prepare($consulta);
$prepare->execute();
$resultado = $prepare->fetchAll();
$nombreCiudad = $resultado[0]["mun_nombre"];

$manejoY = $manejoY + 5;

$pdf->Cell(50, 5, utf8_decode($nombreCiudad), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(10);
$pdf->Cell(45, 5, utf8_decode('CONDUCTOR:'), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(33);

$consulta = "select concat(c.cond_nombres,' ',c.cond_apellidos) as nombreConductor,s.cedulaConductor,"
        . "c.cond_telefono "
        . "from conductores as c,servicio as s "
        . "where s.cedulaConductor=c.cond_identificacion "
        . "and s.idservicio=" . $idservicio . " "
        . "and c.estado='ACTIVO';";
//echo $consulta;
$prepare = $conexion->prepare($consulta);
$prepare->execute();
$conductor = $prepare->fetchAll();
$nombreConductor = $conductor[0]["nombreConductor"];
$cedulaConductor = $conductor[0]["cedulaConductor"];

$pdf->Cell(50, 5, utf8_decode($nombreConductor), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(95);
$pdf->Cell(45, 5, utf8_decode('C.C. COND.:'), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(118);
$pdf->Cell(50, 5, utf8_decode($cedulaConductor), 0, 0, 'L');
$manejoY = $manejoY + 5.5;
$pdf->Line(9, $manejoY, 200, $manejoY);

$pdf->setY($manejoY);
$pdf->SetX(20);
$pdf->Cell(170, 5, 'DETALLE ANTICIPOS', 0, 0, 'C');
$manejoY = $manejoY + 5.5;
$pdf->Line(9, $manejoY, 200, $manejoY);
$manejoY = $manejoY + 0.1;

$manejoX = 10;

$pdf->SetY($manejoY);
$pdf->SetX($manejoX);
$pdf->Cell(45, 5, 'No. GUIA', 0, 0, 'L');
$manejoX += 17;
$pdf->SetX($manejoX);
$pdf->Cell(35, 5, 'FECHA SVR', 0, 0, 'L');
$manejoX += 20;
$pdf->SetX($manejoX);
$manejoX += 20;
$pdf->Cell(35, 5, 'ORIGEN', 0, 0, 'L');
$pdf->SetX($manejoX);
$manejoX += 20;
$pdf->Cell(35, 5, 'DESTINO', 0, 0, 'L');
$pdf->SetX($manejoX);
$pdf->Cell(35, 5, 'EMPRESA', 0, 0, 'L');
$manejoX += 30;
$pdf->SetX($manejoX);
$pdf->SetFont('Courier', 'B', 9);
$pdf->Cell(50, 5, 'V/R ANTICIPO', 0, 0, 'L');
$pdf->SetFont('Courier', '', 9);
$manejoX += 25;
$pdf->SetX($manejoX);
$pdf->Cell(50, 5, 'V/R SERVICIO', 0, 0, 'L');
$manejoX += 25;
$pdf->SetX($manejoX);
$pdf->Cell(50, 5, 'V/R DECLARADO', 0, 0, 'L');

$manejoY = $manejoY + 5;
$manejoX = 10;

$consulta = "select sg.numeroGuia,sg.iddireccionorigen,sg.iddirecciondestino,sg.valorDeclarado,"
        . "sg.valorPagado,s.fechaServicio,sg.nit "
        . "from servicio_guias as sg,servicio as s "
        . "where sg.idservicio=s.idservicio "
        . "and sg.idservicio=" . $idservicio . ";";
//echo $consulta;
$prepare = $conexion->prepare($consulta);
$prepare->execute();
$servicio = $prepare->fetchAll();

$iddireccionorigen = null;
$iddirecciondestino = null;
$totalAnticipos = null;
$totalValorServicio = null;

for ($index = 0; $index < count($servicio); $index++)
{

    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, $servicio[$index]["numeroGuia"], 0, 0, 'L');

    $manejoX = $manejoX + 17;

    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, $servicio[0]["fechaServicio"], 0, 0, 'L');

    $manejoX = $manejoX + 20;

    $consulta = "select mun_nombre "
            . "from municipios as m,direcciones as d "
            . "where d.ciudad=m.mun_id "
            . "and d.iddireccion=" . $servicio[$index]["iddireccionorigen"] . ";";

    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $resultado = $prepare->fetchAll();
    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, $resultado[0]["mun_nombre"], 0, 0, 'L');

    $manejoX = $manejoX + 20;

    $consulta = "select mun_nombre "
            . "from municipios as m,direcciones as d "
            . "where d.ciudad=m.mun_id "
            . "and d.iddireccion=" . $servicio[$index]["iddirecciondestino"] . ";";
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $resultado = $prepare->fetchAll();

    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, $resultado[0]["mun_nombre"], 0, 0, 'L');

    $manejoX = $manejoX + 20;

    $consulta = "select cli_nombre "
            . "from cliente "
            . "where cli_documento= " . $servicio[$index]["nit"] . ";";
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $resultado = $prepare->fetchAll();

    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $nombreEmpresa = substr($resultado[0]["cli_nombre"], 0, 15);
    $pdf->Cell(28, 5, $nombreEmpresa, 0, 0, 'L');

    $consulta = "insert into trasabilidad "
            . "values (" . $idservicio . ",'" . date("Y-m-d h:m:s") . "'," . $servicio[$index]["numeroGuia"] . ",'ANTICIPO " . $_SESSION["emp_cedula"] . "')";
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();

    $manejoX = $manejoX + 30;

    $consulta = "select val_valorAdelanto,valorservicio "
            . "from valoresanticipos "
            . "where val_numeroGuia= " . $servicio[$index]["numeroGuia"] . ";";
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $resultado = $prepare->fetchAll();

    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, '$' . number_format($resultado[0]["val_valorAdelanto"]), 0, 0, 'L');
    $totalAnticipos += (int) $resultado[0]["val_valorAdelanto"];

    $manejoX = $manejoX + 25;
    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, '$' . number_format($resultado[0]["valorservicio"]), 0, 0, 'L');
    $totalValorServicio += (int) $resultado[0]["valorservicio"];

    $manejoX += 25;
    $pdf->SetX($manejoX);
    $pdf->Cell(50, 5, '$' . number_format($servicio[$index]["valorDeclarado"]), 0, 0, 'L');

    $manejoX = 10;
    $manejoY = $manejoY + 5;
}

$pdf->Line(9, $manejoY + 1, 200, $manejoY + 1);

$manejoX = $manejoX + 4;
$manejoY = $manejoY + 1;
$pdf->SetY($manejoY);
$pdf->SetX($manejoX);
$pdf->SetFont('Courier', 'B', 9);
$pdf->Cell(193, 5, "TOTALES", 0, 0, 'C');
$pdf->SetFont('Courier', '', 9);
$pdf->SetY($manejoY);
$manejoX = $pdf->GetX();
$manejoX = $manejoX + 107;
$pdf->SetX($manejoX);
$pdf->SetFont('Courier', 'B', 9);
$pdf->Cell(28, 5, '$' . number_format($totalAnticipos), 0, 0, 'L');
$pdf->SetFont('Courier', '', 9);
$manejoY = $manejoY + 5;
$pdf->Line(9, $manejoY, 200, $manejoY);
$manejoX = $manejoX + 25;
$pdf->SetX($manejoX);
$pdf->Cell(28, 5, '$' . number_format($totalValorServicio), 0, 0, 'L');
$pdf->SetY($manejoY);
$manejoX = $manejoX - 37;
$pdf->SetX($manejoX);
$pdf->Cell(28, 5, 'SALDO ', 0, 0, 'L');
$pdf->SetY($manejoY);
$manejoX = $manejoX + 37;
$pdf->SetX($manejoX);
$saldo = $totalValorServicio - $totalAnticipos;
$pdf->Cell(28, 5, '$' . number_format($saldo), 0, 0, 'L');

$pdf->SetX(15);
$manejoY = $manejoY + 9;
$pdf->Line(15, $manejoY, 60, $manejoY);

$pdf->SetFont('Courier', 'B', 9);
$pdf->SetY($manejoY + 1);
$pdf->MultiCell(180, 5, 'SON: ' . strtoupper($cifraEnLetra::convertirCifrasEnLetras($totalAnticipos)) . ' PESOS M/TE', 0, 'L', false);
$manejoY = $pdf->GetY();
$pdf->SetFont('Courier', '', 9);
$manejoY = $manejoY + 5;
$pdf->SetY($manejoY);
$pdf->SetX(9);
$pdf->Cell(28, 5, "- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - ", 0, 0, 'L');

//---> hasta aqui llega la primera parte del anticipo <---
$manejoY = $manejoY + 5;
$manejoX = $manejoX + 10;

$pdf->Line(9, $manejoY, 200, $manejoY);
$pdf->Image('../imagenes/logocity.jpg', 10, $manejoY + 5, 70, 20);
$manejoY = $manejoY + 5;
$pdf->SetY($manejoY);
$pdf->SetX(80);
$pdf->Cell(40, 20, 'ANTICIPO', 0, 0, 'C');
$pdf->SetY($manejoY);
$pdf->SetX(130);
$pdf->Cell(38, 5, 'CONSECUTIVO:', 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(168);
$pdf->Cell(10, 5, $numeroAnticipo, 0, 0, 'L');

$manejoY = $manejoY + 5;
$pdf->SetY($manejoY);
$pdf->SetX(130);
$pdf->Cell(38, 5, 'DEPARTAMENTO:', 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(168);
$pdf->Cell(20, 5, $_SESSION["departamento"], 0, 0, 'L');
$manejoY = $manejoY + 5;
$pdf->SetY($manejoY);
$pdf->SetX(130);
$pdf->Cell(38, 5, 'HOJA:', 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(168);
$pdf->Cell(25, 5, '2', 0, 0, 'L');
$manejoY = $manejoY + 5;
$pdf->SetY($manejoY);
$pdf->SetX(130);
$pdf->Cell(38, 5, 'FECHA:', 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(168);
$pdf->Cell(25, 5, date("Y-m-d"), 0, 0, 'L');
$manejoY = $manejoY + 5;
$pdf->Line(9, $manejoY, 200, $manejoY);
//$manejoY = $manejoY + 0.4;
$pdf->SetY($manejoY);
$pdf->SetX(10);
$pdf->Cell(193, 5, 'DATOS DE LA PERSONA NATURAL O BENEFICIARIA DEL PAGO', 0, 0, 'C');
$manejoY = $manejoY + 5;
$pdf->Line(9, $manejoY, 200, $manejoY);

$manejoY = $manejoY + 1;

$consulta = "select concat(c.cond_nombres,' ',c.cond_apellidos) as nombrePropietario,s.cedulaPropietario,s.placa,"
        . "c.cond_direccion,c.cond_telefono,c.municipios_mun_id "
        . "from conductores as c,servicio as s "
        . "where s.cedulaPropietario=c.cond_identificacion "
        . "and s.idservicio=" . $idservicio . " "
        . "and c.estado='ACTIVO';";
//echo $consulta;
$prepare = $conexion->prepare($consulta);
$prepare->execute();
$propietario = $prepare->fetchAll();
$nombrePropietario = $propietario[0]["nombrePropietario"];
$cedulaPropietario = $propietario[0]["cedulaPropietario"];
$placa = $propietario[0]["placa"];
$direccionPropietario = $propietario[0]["cond_direccion"];
$telefonoPropietario = $propietario[0]["cond_telefono"];
$mun_id = $propietario[0]["municipios_mun_id"];

$pdf->SetY($manejoY);
$pdf->SetX(10);
$pdf->Cell(42, 5, 'PROPIETARIO:', 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(33);
$pdf->Cell(50, 5, utf8_decode($nombrePropietario), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(95);
$pdf->Cell(60, 5, "C.C. PROP.:", 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(118);
$pdf->Cell(50, 5, utf8_decode($cedulaPropietario), 0, 0, 'L');
$pdf->SetX(155);
$pdf->Cell(60, 5, "PLACA:", 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(175);
$pdf->Cell(50, 5, utf8_decode($placa), 0, 0, 'L');

$consulta = "select municipios.mun_nombre "
        . "from municipios "
        . "where municipios.mun_id=" . $mun_id . ";";
$prepare = $conexion->prepare($consulta);
$prepare->execute();
$resultado = $prepare->fetchAll();
$nombreCiudad = $resultado[0]["mun_nombre"];

$manejoY = $manejoY + 5;

$pdf->SetY($manejoY);
$pdf->SetX(10);
$pdf->Cell(45, 5, utf8_decode('DIRECCIÓN:'), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(30);
$pdf->Cell(50, 5, utf8_decode($direccionPropietario), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(95);
$pdf->Cell(45, 5, utf8_decode('TELÉFONO:'), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(118);
$pdf->Cell(50, 5, utf8_decode($telefonoPropietario), 0, 0, 'L');
$pdf->SetX(155);
$pdf->Cell(45, 5, utf8_decode('CIUDAD:'), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(175);
$pdf->Cell(50, 5, utf8_decode($nombreCiudad), 0, 0, 'L');

$manejoY = $manejoY + 5;

$consulta = "select concat(c.cond_nombres,' ',c.cond_apellidos) as nombreConductor,s.cedulaConductor,"
        . "c.cond_telefono "
        . "from conductores as c,servicio as s "
        . "where s.cedulaConductor=c.cond_identificacion "
        . "and s.idservicio=" . $idservicio . " "
        . "and c.estado='ACTIVO';";
$prepare = $conexion->prepare($consulta);
$prepare->execute();
$conductor = $prepare->fetchAll();
$nombreConductor = $conductor[0]["nombreConductor"];
$cedulaConductor = $conductor[0]["cedulaConductor"];

$pdf->SetY($manejoY);
$pdf->SetX(10);
$pdf->Cell(45, 5, utf8_decode('CONDUCTOR:'), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(33);
$pdf->Cell(50, 5, utf8_decode($nombreConductor), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(95);
$pdf->Cell(45, 5, utf8_decode('C.C. COND.:'), 0, 0, 'L');
$pdf->SetY($manejoY);
$pdf->SetX(118);
$pdf->Cell(50, 5, utf8_decode($cedulaConductor), 0, 0, 'L');

$manejoY = $manejoY + 5;

$pdf->Line(9, $manejoY, 200, $manejoY);
$pdf->setY($manejoY);
$pdf->SetX(20);
$pdf->Cell(170, 5, 'DETALLE ANTICIPOS', 0, 0, 'C');
$manejoY = $manejoY + 5;
$pdf->Line(9, $manejoY, 200, $manejoY);

$manejoX = 10;

$pdf->SetY($manejoY);
$pdf->SetX($manejoX);
$pdf->Cell(45, 5, 'No. GUIA', 0, 0, 'L');
$manejoX += 17;
$pdf->SetX($manejoX);
$pdf->Cell(35, 5, 'FECHA SVR', 0, 0, 'L');
$manejoX += 20;
$pdf->SetX($manejoX);
$manejoX += 20;
$pdf->Cell(35, 5, 'ORIGEN', 0, 0, 'L');
$pdf->SetX($manejoX);
$manejoX += 20;
$pdf->Cell(35, 5, 'DESTINO', 0, 0, 'L');
$pdf->SetX($manejoX);
$pdf->Cell(35, 5, 'EMPRESA', 0, 0, 'L');
$manejoX += 30;
$pdf->SetX($manejoX);
$pdf->SetFont('Courier', 'B', 9);
$pdf->Cell(50, 5, 'V/R ANTICIPO', 0, 0, 'L');
$pdf->SetFont('Courier', '', 9);
$manejoX += 25;
$pdf->SetX($manejoX);
$pdf->Cell(50, 5, 'V/R SERVICIO', 0, 0, 'L');
$manejoX += 25;
$pdf->SetX($manejoX);
$pdf->Cell(50, 5, 'V/R DECLARADO', 0, 0, 'L');

$manejoY = $manejoY + 5;
$manejoX = 10;

$iddireccionorigen = null;
$iddirecciondestino = null;
$totalAnticipos = null;
$totalValorServicio = null;

for ($index = 0; $index < count($servicio); $index++)
{

    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, $servicio[$index]["numeroGuia"], 0, 0, 'L');

    $manejoX = $manejoX + 17;

    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, $servicio[0]["fechaServicio"], 0, 0, 'L');

    $manejoX = $manejoX + 20;

    $consulta = "select mun_nombre "
            . "from municipios as m,direcciones as d "
            . "where d.ciudad=m.mun_id "
            . "and d.iddireccion=" . $servicio[$index]["iddireccionorigen"] . ";";

    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $resultado = $prepare->fetchAll();
    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, $resultado[0]["mun_nombre"], 0, 0, 'L');

    $manejoX = $manejoX + 20;

    $consulta = "select mun_nombre "
            . "from municipios as m,direcciones as d "
            . "where d.ciudad=m.mun_id "
            . "and d.iddireccion=" . $servicio[$index]["iddirecciondestino"] . ";";
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $resultado = $prepare->fetchAll();

    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, $resultado[0]["mun_nombre"], 0, 0, 'L');

    $manejoX = $manejoX + 20;

    $consulta = "select cli_nombre "
            . "from cliente "
            . "where cli_documento= " . $servicio[$index]["nit"] . ";";
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $resultado = $prepare->fetchAll();

    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $nombreEmpresa = substr($resultado[0]["cli_nombre"], 0, 15);
    $pdf->Cell(28, 5, $nombreEmpresa, 0, 0, 'L');

    $manejoX = $manejoX + 30;

    $consulta = "select val_valorAdelanto,valorservicio "
            . "from valoresanticipos "
            . "where val_numeroGuia= " . $servicio[$index]["numeroGuia"] . ";";
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $resultado = $prepare->fetchAll();

    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, '$' . number_format($resultado[0]["val_valorAdelanto"]), 0, 0, 'L');
    $totalAnticipos += (int) $resultado[0]["val_valorAdelanto"];

    $manejoX = $manejoX + 25;
    $pdf->SetY($manejoY);
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, '$' . number_format($resultado[0]["valorservicio"]), 0, 0, 'L');
    $totalValorServicio += (int) $resultado[0]["valorservicio"];

    $manejoX += 25;
    $pdf->SetX($manejoX);
    $pdf->Cell(50, 5, '$' . number_format($servicio[$index]["valorDeclarado"]), 0, 0, 'L');

    $manejoX = 10;
    $manejoY = $manejoY + 5;
}

$pdf->Line(9, $manejoY + 1, 200, $manejoY + 1);

$manejoX = $manejoX + 4;
$manejoY = $manejoY + 1;
$pdf->SetY($manejoY);
$pdf->SetX($manejoX);
$pdf->SetFont('Courier', 'B', 9);
$pdf->Cell(193, 5, "TOTALES", 0, 0, 'C');
$pdf->SetFont('Courier', '', 9);
$pdf->SetY($manejoY);
$manejoX = $pdf->GetX();
$manejoX = $manejoX + 107;
$pdf->SetX($manejoX);
$pdf->SetFont('Courier', 'B', 9);
$pdf->Cell(28, 5, '$' . number_format($totalAnticipos), 0, 0, 'L');
$pdf->SetFont('Courier', '', 9);
$manejoY = $manejoY + 5;
$pdf->Line(9, $manejoY, 200, $manejoY);
$manejoX = $manejoX + 25;
$pdf->SetX($manejoX);
$pdf->Cell(28, 5, '$' . number_format($totalValorServicio), 0, 0, 'L');
$pdf->SetY($manejoY);
$manejoX = $manejoX - 37;
$pdf->SetX($manejoX);
$pdf->Cell(28, 5, 'SALDO ', 0, 0, 'L');
$pdf->SetY($manejoY);
$manejoX = $manejoX + 37;
$pdf->SetX($manejoX);
$pdf->Cell(28, 5, '$' . number_format($saldo), 0, 0, 'L');

$pdf->SetX(15);
$manejoY = $manejoY + 9;
$pdf->Line(15, $manejoY, 60, $manejoY);

$pdf->SetFont('Courier', 'B', 9);
$pdf->SetY($manejoY + 1);
$pdf->MultiCell(180, 5, 'SON: ' . strtoupper($cifraEnLetra::convertirCifrasEnLetras($totalAnticipos)) . ' PESOS M/TE', 0, 'L', false);

$numeroCotizacion = "ANTICIPO_NUMERO_" . $numeroAnticipo . ".pdf";
$pdf->Output($numeroCotizacion, "D");

$conexion = null;


<?php
session_start();

include_once '../clases/conexion.php';
include_once '../fpdf17/fpdf.php';

$conexion = new Conexion();

$serviciosAPagar = explode("-", $_POST["serviciosAPagar"]);
//var_dump($serviciosAPagar);exit;
/*
 * 201904082004
 * Cuando no llega la variable $_POST["serviciosAPagar"] genero un mensaje 
 * al index.php 
 */

if (count($serviciosAPagar) === 0) {
    header("Location ../modulos/index.php?msj=15");
}

/* 201612071029
 * Averiguo el id del conductor que va a estar relacionado en el anticipo
 */

$cedulaConductor = $_POST["conductor"];
$consulta = "select cond_id,cond_nombres,cond_apellidos from conductores where cond_identificacion=" . $cedulaConductor . " and estado='ACTIVO';";

$prepare = $conexion->prepare($consulta);
$prepare->execute();
$arreglo = $prepare->fetchAll();

$idCondRel = $arreglo[0]["cond_id"];
$nombresConductor = $arreglo[0]["cond_nombres"] . ' ' . $arreglo[0]["cond_apellidos"];

/*   Inicio transaccion */
$prepare = $conexion->prepare(" BEGIN ");
$prepare->execute();
try{
        //busco el ultimo numero de la tabla numeros numeros_cta_cobro
        $consulta = "select numero as numeroCtaCobro from numeros_cta_cobro;";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();
        $numeroAnticipo = intval($resultado[0]['numeroCtaCobro']) + 1;

        $consulta = "update numeros_cta_cobro set numero=" . $numeroAnticipo . ";";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();

        for ($a = 0; $a < count($serviciosAPagar); $a++) {

        $consulta = "select idservicio "
                . "from valoresanticipos "
                . "where val_ant_id=" . $serviciosAPagar[$a] . ";";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();
        $idservicio = $resultado[0]["idservicio"];

        /*
        * Averiguar las guias del servicio e insertar 
        * en la tabla trasabilidad CUENTA DE COBRO A CONDUCTOR     
        */

        $consulta = "select val_numeroGuia "
                . "from valoresanticipos "
                . "where idservicio=" . $idservicio . ";";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();
        $guiasServicio = $resultado[0]["val_numeroGuia"];
        $guiasServicio = explode('-', $guiasServicio);

        for ($index = 0; $index < count($guiasServicio); $index++) {
                $consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                        . "values (" . $idservicio . ",'" . date("Y-m-d h:m:s") . "','" . $guiasServicio[$index] . "','CUENTA DE COBRO A CONDUCTOR ".$numeroAnticipo."');";
                $prepare = $conexion->prepare($consulta);
                $prepare->execute();

                $consulta = "update servicio_guias "
                        . "set fechaPago='" . date('Y-m-d') . "' "
                        . "where numeroGuia=" . $guiasServicio[$index] . ";";
                $prepare = $conexion->prepare($consulta);
                $prepare->execute();
        }

        /*
        * Modificar el estado de cada idservicio en la tabla valoresanticipos a P y
        * Modificar el estado de cada idservicio en la tabla posiblesanticipos a CUENTA DE COBRO
        */

        $consulta = "update valoresanticipos set prueba_entrega='P' where val_ant_id=" . $serviciosAPagar[$a] . ";";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();

        $consulta = "update posiblesanticipos set estado='CUENTA DE COBRO' where idservicio=" . $idservicio . ";";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();

        $consulta = "update servicio_guias "
        //        . "set numeroCuentaCobro=".$_POST["numeroCtaCobro"]." "
                . "set numeroCuentaCobro=".$numeroAnticipo." "
                . "where idservicio=" . $idservicio . ";";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        }

        $cant = 0;

        $consulta = "select cond_id from conductores where cond_identificacion=" . $_POST["identificacion_conductor"] . ";";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();
        $cond_id = $resultado[0]["cond_id"];

        //exit();
        //-------------------------------------------------------genero el pdf
        //$pdf->SetFont('Courier', '', 10); le adiciono 'B' en segundo parametro.
        $pdf = new FPDF();
        $pdf->SetFont('Courier', '', 10);
        $pdf->AddPage();
        $pdf->SetDrawColor(0, 0, 0);
        $pdf->SetLineWidth(0.1);
        $pdf->Line(9.5, 9.5, 190, 9.5);
        //$pdf->Image('../imagenes/USAPOSTAL.jpg', 10, 10, 70, 20);
        $pdf->Image('../imagenes/USAPOSTAL.jpg', 10, 10, 65, 20);
        $pdf->SetY(15);
        $pdf->SetX(80);
        $pdf->SetFont('Courier', 'B', 15);
        $pdf->Cell(40, 20, 'CUENTA DE COBRO', 0, 0, 'C');
        $pdf->SetFont('Courier', 'B', 10);
        $pdf->SetY(10);
        $pdf->SetX(130);
        $pdf->Cell(38, 5, 'CONSECUTIVO:', 0, 0, 'L');
        $pdf->SetY(10);
        $pdf->SetX(168);
        //$pdf->Cell(10, 5, $_POST["numeroCtaCobro"], 0, 0, 'L');
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
        $pdf->Cell(25, 5, $pdf->PageNo(), 0, 0, 'L');
        $pdf->SetY(25);
        $pdf->SetX(130);
        $pdf->Cell(38, 5, 'FECHA:', 0, 0, 'L');
        $pdf->SetY(25);
        $pdf->SetX(168);
        $pdf->Cell(25, 5, date("Y-m-d"), 0, 0, 'L');
        $pdf->Line(9, 30, 190, 30);
        $pdf->SetY(30.5);
        $pdf->SetX(10);
        //$pdf->SetFont('Courier', 'B', 10);
        $pdf->Cell(193, 5, 'DATOS DE LA PERSONA NATURAL O BENEFICIARIA DEL PAGO', 0, 0, 'C');
        $pdf->SetFont('Courier', 'B', 9);
        $pdf->Line(9, 35, 190, 35);

        $nombresPropietario = $_POST["nombre_conductor"] . ' ' . $_POST["apellido_conductor"] .
                $pdf->SetY(35.5);
        $pdf->SetX(10);
        $pdf->Cell(42, 5, 'PROPIETARIO:', 0, 0, 'L');
        $pdf->SetX(33);
        $pdf->Cell(50, 5, substr($nombresPropietario, 0, 31), 0, 0, 'L');
        $pdf->SetX(95);
        $pdf->Cell(60, 5, utf8_decode("C.C. COND.:"), 0, 0, 'L');
        $pdf->SetX(118);
        $pdf->Cell(50, 5, $_POST["identificacion_conductor"], 0, 0, 'L');
        $pdf->SetX(155);
        $pdf->Cell(60, 5, "PLACA:", 0, 0, 'L');
        $pdf->SetX(175);
        $pdf->Cell(50, 5, $_POST["placa"], 0, 0, 'L');

        $posActY = $pdf->GetY();
        $posActY = $posActY + 4;

        $pdf->SetY($posActY);
        $pdf->SetX(10);
        $pdf->Cell(45, 5, utf8_decode('DIRECCIÓN:'), 0, 0, 'L');
        $pdf->SetX(33);
        $pdf->Cell(55, 5, $_POST["direccion_conductor"], 0, 0, 'L');
        $pdf->SetX(95);
        $pdf->Cell(45, 5, utf8_decode('TELÉFONO:'), 0, 0, 'L');
        $pdf->SetX(118);
        $pdf->Cell(50, 5, $_POST["telefono_conductor"], 0, 0, 'L');
        $pdf->SetX(155);
        $pdf->Cell(60, 5, "CIUDAD:", 0, 0, 'L');
        $pdf->SetX(175);
        $consulta = "select municipios.mun_nombre from municipios where municipios.mun_id=" . $_POST["idCiudadConductor"] . ";";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();
        $nombreCiudad = $resultado[0]["mun_nombre"];

        $pdf->Cell(50, 5, utf8_decode($nombreCiudad), 0, 0, 'L');
        $posActY = $pdf->GetY();
        $posActY = $posActY + 4;
        $pdf->SetY($posActY);
        $pdf->SetX(10);
        $pdf->Cell(45, 5, utf8_decode('CONDUCTOR:'), 0, 0, 'L');
        $pdf->SetX(33);
        $pdf->Cell(50, 5, substr(utf8_decode($nombresConductor), 0, 31), 0, 0, 'L');
        $pdf->SetX(95);
        $pdf->Cell(45, 5, utf8_decode('C.C. COND.:'), 0, 0, 'L');
        $pdf->SetX(118);
        $pdf->Cell(50, 5, utf8_decode($cedulaConductor), 0, 0, 'L');

        $posActY = $pdf->GetY();
        $posActY = $posActY + 5;

        $pdf->Line(9, $posActY, 190, $posActY);

        $posActY = $pdf->GetY();
        $posActY = $posActY + 5;

        $pdf->setY($posActY);
        $pdf->SetX(20);
        $pdf->SetFont('Courier', 'B', 10);
        $pdf->Cell(170, 5, 'DETALLE CUENTA DE COBRO', 0, 0, 'C');
        //$pdf->SetFont('Courier', '', 10);

        $posActY = $pdf->GetY();
        $posActY = $posActY + 5;

        $pdf->Line(9, $posActY, 190, $posActY);

        $posActY = $pdf->GetY();
        $posActY = $posActY + 5;

        $pdf->SetY($posActY);
        $pdf->SetX(8);
        $pdf->Cell(45, 5, 'GUIA', 0, 0, 'L');
        $pdf->SetX(30);
        $pdf->Cell(50, 5, 'MANIF', 0, 0, 'L');
        $pdf->SetX(50);
        $pdf->Cell(50, 5, 'FECHA', 0, 0, 'L');
        $pdf->SetX(70);
        $pdf->Cell(30, 5, 'ORI', 0, 0, 'L');
        $pdf->SetX(80);
        $pdf->Cell(30, 5, 'DES', 0, 0, 'L');
        $pdf->SetX(95);

        $pdf->Cell(45, 5, 'EMPRESA', 0, 0, 'L');
        $pdf->SetX(135);
        $pdf->Cell(50, 5, 'V/R ANTICIPO', 0, 0, 'L');
        $pdf->SetX(163);
        $pdf->Cell(50, 5, 'V/R SERVICIO', 0, 0, 'L');

        $manejoY = $pdf->GetY();
        $manejoY = $manejoY + 5;
        $pdf->SetX(8);
        $manejoX = $pdf->GetX();

        $pdf->SetFont('Courier', 'B', 8);

        $contador = 60;
        $multiplicador = 1;

        for ($a = 0; $a < count($serviciosAPagar); $a++) {
        
        $consulta = "select va.idservicio,va.val_numeroGuia,va.val_valorAdelanto,va.valorservicio,c.cli_nombre "
                . "from valoresanticipos as va,cliente as c "
                . "where va.val_id_empresa=c.cli_id "
                . "and va.val_ant_id=" . $serviciosAPagar[$a] . ";";

        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();
        $idservicio = $resultado[0]["idservicio"];
        $val_numeroGuia = $resultado[0]["val_numeroGuia"];
        $nombreEmpresa = $resultado[0]["cli_nombre"];
        $valorAdelanto = $resultado[0]["val_valorAdelanto"];
        $valorServicio = $resultado[0]["valorservicio"];

        /* se añade una nueva hoja en caso de que la cantidad de servicios sea 
        * igual a 60     
        */
        if ($a === $contador) {
                $manejoY = 9.5;
                $multiplicador = +1;
                $contador = $contador * $multiplicador;
                $pdf->AddPage();
        }

        $pdf->SetY($manejoY);
        $pdf->SetX($manejoX);
        $pdf->Cell(28, 5, $val_numeroGuia, 0, 0, 'L');

        $consulta = "select manifiesto from seguimiento_servicio "
                . "where idservicio=" . $idservicio . ";";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();

        $manejoX = $manejoX + 22;
        $pdf->SetX($manejoX);
        $fecha = substr($resultado[0]["manifiesto"], 0, 10);
        $pdf->Cell(28, 5, $fecha, 0, 0, 'L');

        $consulta = "select fechaServicio as fecha from servicio "
                . "where idservicio=" . $idservicio . ";";
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();

        $manejoX = $manejoX + 20;
        $pdf->SetX($manejoX);
        $fecha = substr($resultado[0]["fecha"], 0, 10);
        $pdf->Cell(28, 5, $fecha, 0, 0, 'L');

        $consulta = "select (select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddirecciondestino)) as ciudadDestino,"
                . "(select mun_nombre from municipios where mun_id=(select d.ciudad from direcciones as d where d.iddireccion=sg.iddireccionorigen)) as ciudadOrigen "
                . "from servicio_guias as sg where sg.numeroGuia=" . $val_numeroGuia . ";";  
        //echo $consulta.'<br>'; 
        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultadoCiudades = $prepare->fetchAll();     
        $ciudadOrigen = substr($resultadoCiudades[0]["ciudadOrigen"], 0, 3);    
        $ciudadDestino = substr($resultadoCiudades[0]["ciudadDestino"], 0, 3);
        $manejoX = $manejoX + 20;
        $pdf->SetX($manejoX);
        $pdf->Cell(28, 5, $ciudadOrigen, 0, 0, 'L');

        $manejoX = $manejoX + 10;
        $pdf->SetX($manejoX);
        $pdf->Cell(28, 5, $ciudadDestino, 0, 0, 'L');

        $manejoX = $manejoX + 15;
        $pdf->SetX($manejoX);
        $nombreEmpresa = substr($nombreEmpresa, 0, 20);
        $pdf->Cell(28, 5, $nombreEmpresa, 0, 0, 'L');

        $manejoX = $manejoX + 40;

        $pdf->SetX($manejoX);
        $pdf->Cell(28, 5, number_format($valorAdelanto), 0, 0, 'L');

        $manejoX = $manejoX + 28;

        $pdf->SetX($manejoX);
        $pdf->Cell(28, 5, number_format($valorServicio), 0, 0, 'L');

        $manejoY = $manejoY + 3.5;
        $manejoX = 8;
        }

        $manejoY = $manejoY + 1;
        $pdf->Line(9, $manejoY, 190, $manejoY);
        $pdf->SetX(9);

        $manejoY = $manejoY + 1;

        $pdf->SetFont('Courier', '', 10);

        $pdf->SetY($manejoY);
        $pdf->SetX(148.4);
        $pdf->Cell(28, 5, "BASE:", 0, 0, 'L');
        $pdf->SetX(160);
        $pdf->Cell(28, 5, '$' . number_format($_POST["valorServicio"]), 0, 0, 'L');
        $pdf->SetY($manejoY + 3);
        $pdf->SetX(135);
        $pdf->SetFontSize(8);
        $pdf->Cell(28, 5, 'RTE FUENTE ' . $_SESSION["retefuente"] . '%:', 0, 0, 'L');
        $pdf->SetX(160);
        $pdf->Cell(28, 5, '$' . number_format($_POST["totalRteFuente"]), 0, 0, 'L');
        $manejoY = $manejoY + 6;
        $pdf->SetY($manejoY);
        //$pdf->SetX(140);
        $pdf->SetX(133);
        $pdf->Cell(28, 5, 'RTE ICA ' . $_SESSION["reteica"] . '%:', 0, 0, 'L');
        $pdf->SetX(160);
        $pdf->Cell(28, 5, '$' . number_format($_POST["totalRteIca"]), 0, 0, 'L');
        $manejoY = $manejoY + 3;
        $pdf->SetY($manejoY);
        $pdf->SetX(136.5);
        $pdf->Cell(28, 5, 'T. IMPUESTOS:', 0, 0, 'L');
        $pdf->SetX(160);
        $pdf->Cell(28, 5, '$' . number_format($_POST["totalImpuestos"]), 0, 0, 'L');
        $manejoY = $manejoY + 3;
        $pdf->SetY($manejoY);
        $pdf->SetX(136.5);
        $pdf->Cell(28, 5, 'T. ANTICIPOS:', 0, 0, 'L');
        $pdf->SetX(160);
        $pdf->Cell(28, 5, '$' . number_format($_POST["totalAnticipos"]), 0, 0, 'L');
        $pdf->SetFontSize(10);
        $manejoY = $manejoY + 3;
        $pdf->SetY($manejoY);
        $pdf->SetX(129);
        $pdf->Cell(28, 5, 'T. DEDUCIBLES:', 0, 0, 'L');
        $pdf->SetX(160);
        $pdf->Cell(28, 5, '$' . number_format($_POST["totalDeducibles"]), 0, 0, 'L');
        $manejoY = $manejoY + 3;
        $pdf->SetY($manejoY);
        $pdf->SetX(131);
        $pdf->SetFont('Courier', 'B', 10);
        $pdf->Cell(28, 5, 'NETO A PAGAR:', 0, 0, 'L');
        $pdf->SetX(160);
        $pdf->Cell(28, 5, '$' . number_format($_POST["totalNetoPagar"]), 0, 0, 'L');
        $manejoY = $pdf->GetY();
        $pdf->SetFont('Courier', 'B', 9);
        $manejoY = $pdf->GetY();
        $manejoY = $manejoY + 5;
        $pdf->SetX(10);
        $pdf->SetY($manejoY + 1);
        $pdf->MultiCell(190, 5, utf8_decode($_POST["valorLetras"]), 0, 'L', false);
        //$pdf->SetFont('Courier', '', 9);
        $manejoY = $pdf->GetY();
        $manejoY = $manejoY + 1;
        $pdf->Line(9, $manejoY, 190, $manejoY);
        $pdf->SetY($manejoY + 15);
        $pdf->SetX(20);
        $pdf->Cell(28, 5, 'x', 0, 0, 'L');
        $pdf->Line(20, $manejoY + 16, 70, $manejoY + 16);
        $pdf->SetX(9);

        //$numeroCotizacion = "CUENTA_DE_COBRO_NUMERO_" . $_POST["numeroCtaCobro"] . ".pdf";
        $numeroCotizacion = "CUENTA_DE_COBRO_NUMERO_" . $numeroAnticipo . ".pdf";
        $pdf->Output($numeroCotizacion, "D");
        $prepare = $conexion->prepare("COMMIT");
        $prepare->execute();

}
catch(PDOException $e){
        $prepare = $conexion->prepare("ROLLBACK");
        $prepare->execute();
        $e->getMessage();
}
$conexion = null;


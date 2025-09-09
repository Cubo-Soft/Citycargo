<?php

session_start();

include_once '../clases/conexion.php';
include_once '../fpdf17/fpdf.php';

$conexion = new Conexion();

$ciudadOrigen = array();
$ciudadDestino = array();
$guias = array();
$guia = null;
$varEmp = null;

/*
 * 201807271504
 * Cuando desde la vista agregarAnticipo.php se pica varias veces en el botón 
 * GENERAR PDF, cada vez que esta acción se realiza, inserta en la tabla 
 * valoresanticipos un nuevo registro. Se debe primero consultar si ya existe el 
 * número de anticipo, si no existe hacerlo. El pdf si se podría generar de manera
 * normal
 */

$consulta = "select val_numeroAnticipo from valoresanticipos where idservicio=" . $_POST["idservicio"] . ";";
$prepare = $conexion->prepare($consulta);
$prepare->execute();
$arreglo = $prepare->fetchAll();

$cantidadVeces = count($arreglo);

if ($cantidadVeces <= 0) {

    /* 201808242116
     * Verifico a través de la variable $_POST["varEmp"] si llega desde la 
     * creación de servicio
     */

    if ($_POST["varEmp"] === '0') {
        $varEmp = '0';
        $guia = $_POST["guiaNumero1"];
    } else if ($_POST["varEmp"] === '1') {
        $varEmp = '1';
        $n = 1;
        $guiaNumero = "guiaNumero" . $n;
        while ($_POST[$guiaNumero] !== "") {
            $guia = $guia . '-' . $_POST[$guiaNumero];
            $n += 1;
            $guiaNumero = "guiaNumero" . $n;
        }
        $guia = substr($guia, 1);
    } else {
        header("Location: ../modulos/index.php?msj=12");
        exit();
    }

    /* 201612071029
     * Averiguo el id del conductor que va a estar relacionado en el anticipo
     */
    $cedulaConductor = $_POST["conductor"];
    $consulta = "select cond_id,cond_nombres,cond_apellidos from conductores where cond_identificacion=" . $cedulaConductor . ";";
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $arreglo = $prepare->fetchAll();
    $idCondRel = $arreglo[0]["cond_id"];
    $nombresConductor = $arreglo[0]["cond_nombres"] . ' ' . $arreglo[0]["cond_apellidos"];

    $valoresInsertar = "";
    $control = null;

//el campo val_ant_id viene con un numero cuando el conductor se le han hecho anticipos
//si es asi, esos anticipos no se tienen en cuenta para ser insertados a la base.
//averiguo si este campo viene con ese numero

    $val_ant_id = "val_ant_id";
    $valorTotalServicio = "valorTotalServicio";

//esto es cuando el conductor no ha tenido anticipos
//inserto en la tabla totalesanticipos el valor total del servicio

    $consulta = "select cond_id from conductores where cond_identificacion=" . $_POST["identificacion_conductor"] . ";";
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $arreglo = $prepare->fetchAll();
    $id_conductor = $arreglo[0]["cond_id"];

//selecciono el ultimo id de la insercion creada
    $consulta = "select max(val_id) as val_id from totalesanticipos;";
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $arreglo = $prepare->fetchAll();
    $val_id = $arreglo[0]["val_id"];

//inserto en la tabla valoresAnticipos cada uno de los anticipos que vienen aqui
    $fechaAnticipo = "fechaAnticipo";
    $valorAnticipo = "valorAnticipo";
    $nitEmp = "nitEmp";
    $guiaNumero = "guiaNumero";
    $valorTotalServicio = "valorTotalServicio";
    $idservicio = "idservicio";

    $cantidadComas = mb_substr_count($_POST["idservicio"], ',');

    if ($cantidadComas > 0) {
        $idser = strstr($_POST["idservicio"], ",", true);
    } else {
        $idser = $_POST["idservicio"];
    }

    for ($index = 1; $index < 16; $index++) {

        $fechaAnticipo .= $index;
        $valorAnticipo .= $index;
        $nitEmp .= $index;
        $guiaNumero .= $index;
        $valorTotalServicio .= $index;
        $idservicio .= $index;


        /*
         * 201808161715
         * Buscar ciudad de origen de un servicio. 
         * En caso de varias guías se selecciona el destino de la ultima guía
         */

        if (intval(@$_POST[$nitEmp]) > 0 && $index === 1) {
            $consulta = "select m.mun_nombre "
                    . "from servicios as s, municipios as m "
                    . "where s.idciudadorigen=m.mun_id "
                    . "and s.idservicio=" . $idser . ";";
            $prepare = $conexion->prepare($consulta);
            $prepare->execute();
            $arreglo = $prepare->fetchAll();
            $ciudadOrigen = $arreglo[0]["mun_nombre"];
        }


        if ((int) $_POST[$nitEmp] <> 0) {

            $consulta = "select cli_id from cliente where cli_documento=" . $_POST[$nitEmp] . ";";
            $prepare = $conexion->prepare($consulta);
            $prepare->execute();
            $arreglo = $prepare->fetchAll();
            $id_empresa = @$arreglo[0]["cli_id"];

            $valoresInsertar .= "(null,'" . $_POST[$fechaAnticipo] . "'," . "'" . $_POST[$valorAnticipo] . "'," . $_POST[$valorTotalServicio] . ","
                    . "" . $id_empresa . ",'" . $_POST[$guiaNumero] . "'," . $id_conductor . "," . $val_id . ",'" . $_POST["numeroAnticipo"] . "','N'," . $_POST[$idservicio] . "),";
        } else {
            $index = 16;

            $cantidadGuiones = mb_substr_count($guia, '-');

            if ($cantidadGuiones > 0) {
                $guias = explode('-', $guia);

                for ($index3 = 0; $index3 < count($guias); $index3++) {
                    $consulta = "select m.mun_nombre "
                            . "from serviciovariasguias as svg, municipios as m "
                            . "where svg.idciudaddestino=m.mun_id "
                            . "and svg.guia=" . $guias[$index3] . ";";

                    $prepare = $conexion->prepare($consulta);
                    $prepare->execute();
                    $arreglo = $prepare->fetchAll();
                    $ciudadDestino[$index3] = $arreglo[0]["mun_nombre"];
                }
            } else {
                $consulta = "select m.mun_nombre "
                        . "from serviciovariasguias as svg, municipios as m "
                        . "where svg.idciudaddestino=m.mun_id "
                        . "and svg.guia=" . $guia . ";";

                $prepare = $conexion->prepare($consulta);
                $prepare->execute();
                $arreglo = $prepare->fetchAll();
                $ciudadDestino[0] = $arreglo[0]["mun_nombre"];
                $guias[0] = $guia;
            }
        }

        $fechaAnticipo = "fechaAnticipo";
        $valorAnticipo = "valorAnticipo";
        $nitEmp = "nitEmp";
        $guiaNumero = "guiaNumero";
        $valorTotalServicio = "valorTotalServicio";
        $idservicio = "idservicio";
    }

//encontrar tamaño de string 
    $tamaño = strlen($valoresInsertar);
    $tamaño = $tamaño - 1;
    $valoresInsertar = substr($valoresInsertar, 0, $tamaño);

//guardo los nuevos adelantos
    $consulta = "insert into valoresanticipos values " . $valoresInsertar . ";";
    $prepare = $conexion->prepare($consulta);

    if ($prepare->execute()) {
        $control = 1;
    }

    $numeroAnticipo = $_POST["numeroAnticipo"] + 1;
    $consulta = "insert into numerosanticipos values (null,'" . $numeroAnticipo . "');";

    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
}

//-------------------------------------------------------genero el pdf

if ($control == 1) {

    /*
     * 201801171142
     * Si trae anticipos que esten creados en la tabla posiblesanticipos cambio 
     * el estado de esos a ANTICIPO. Estaba en CREACION
     */

    if (isset($_POST["idservicios"])) {
        $idservicios = trim($_POST["idservicios"], ',');
        $idservicios = explode(',', $idservicios);
        $a = null;
        $consulta = null;

        for ($index1 = 0; $index1 < count($idservicios); $index1++) {
            $a = $index1 + 1;
            $consulta = "update posiblesanticipos set estado='ANTICIPO' where idservicio=" . $idservicios[$index1] . ";";
            $prepare = $conexion->prepare($consulta);
            $prepare->execute();

            $cantGuiones = substr_count($_POST["guiaNumero" . $a], '-');

            if ($cantGuiones > 0) {
                $numGuias = explode('-', $_POST["guiaNumero" . $a]);
                for ($index2 = 0; $index2 < count($numGuias); $index2++) {
                    $consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                            . "values (" . $idservicios[$index1] . ",'" . date('Y-m-d H:m:s') . "'," . $numGuias[$index2] . ",'ANTICIPO');";
                    $prepare = $conexion->prepare($consulta);
                    $prepare->execute();
                }
            } else {
                $consulta = "insert into trasabilidad (idservicio,fecha,referencia,evento) "
                        . "values (" . $idservicios[$index1] . ",'" . date('Y-m-d H:m:s') . "'," . $_POST["guiaNumero" . $a] . ",'ANTICIPO');";
                $prepare = $conexion->prepare($consulta);
                $prepare->execute();
            }
        }
    }



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
    $pdf->Cell(10, 5, $_POST["numeroAnticipo"], 0, 0, 'L');
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
    $pdf->Cell(50, 5, $_POST["nombre_conductor"] . ' ' . $_POST["apellido_conductor"], 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(95);
    $pdf->Cell(60, 5, utf8_decode("C.C. PROP.:"), 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(118);
    $pdf->Cell(50, 5, $_POST["identificacion_conductor"], 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(155);
    $pdf->Cell(60, 5, "PLACA:", 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(175);
    $pdf->Cell(50, 5, $_POST["placa"], 0, 0, 'L');

    $manejoY = $manejoY + 5;

    $pdf->SetY($manejoY);
    $pdf->SetX(10);
    $pdf->Cell(45, 5, utf8_decode('DIRECCIÓN:'), 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(30);
    $pdf->Cell(50, 5, $_POST["direccion_conductor"], 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(95);
    $pdf->Cell(45, 5, utf8_decode('TELÉFONO:'), 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(118);
    $pdf->Cell(50, 5, $_POST["telefono_conductor"], 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(155);
    $pdf->Cell(60, 5, "CIUDAD:", 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(175);
    $consulta = "select municipios.mun_nombre from municipios where municipios.mun_id=" . $_POST["idCiudadConductor"] . ";";
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
    $pdf->Cell(50, 5, utf8_decode($nombresConductor), 0, 0, 'L');
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

    $cantidadGuiones = $cantidadGuiones + 1;

    $consulta = "select fecha "
            . "from servicios "
            . "where idservicio=" . $idser . ";";
    $prepare = $conexion->prepare($consulta);
    $prepare->execute();
    $resultado = $prepare->fetchAll();
    $fechaServicio = $resultado[0]["fecha"];

    for ($index = 0; $index < $cantidadGuiones; $index++) {

        $consulta = "select svg.guia,m.mun_nombre,c.cli_nombre "
                . "from serviciovariasguias as svg,municipios as m,cliente as c "
                . "where svg.idciudaddestino=m.mun_id "
                . "and svg.idcliente = c.cli_documento "
                . "and svg.guia=" . $guias[$index] . ";";

        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();

        $pdf->SetY($manejoY);
        $pdf->SetX($manejoX);
        $pdf->Cell(28, 5, $resultado[0]["guia"], 0, 0, 'L');

        $manejoX = $manejoX + 17;

        $pdf->SetY($manejoY);
        $pdf->SetX($manejoX);
        $fecha = substr($fechaServicio, 0, 10);
        $pdf->Cell(28, 5, $fecha, 0, 0, 'L');

        $manejoX = $manejoX + 20;

        $pdf->SetY($manejoY);
        $pdf->SetX($manejoX);
        $pdf->Cell(28, 5, $ciudadOrigen, 0, 0, 'L');

        $manejoX = $manejoX + 20;

        $pdf->SetY($manejoY);
        $pdf->SetX($manejoX);
        $pdf->Cell(28, 5, $resultado[0]["mun_nombre"], 0, 0, 'L');

        $manejoX = $manejoX + 20;

        $pdf->SetY($manejoY);
        $pdf->SetX($manejoX);
        $nombreEmpresa = substr($resultado[0]["cli_nombre"], 0, 15);
        $pdf->Cell(28, 5, $nombreEmpresa, 0, 0, 'L');

        $manejoX = $manejoX + 30;

        if ($varEmp === '0') {
            $pdf->SetY($manejoY);
            $pdf->SetX($manejoX);
            $pdf->Cell(28, 5, '$' . number_format('0'), 0, 0, 'L');

            $manejoX = $manejoX + 25;
            $pdf->SetY($manejoY);
            $pdf->SetX($manejoX);
            $pdf->Cell(28, 5, '$' . number_format('0'), 0, 0, 'L');
        } else {
            $consulta = "select val_valorAdelanto,valorservicio "
                    . "from valoresanticipos "
                    . "where val_numeroGuia=" . $guias[$index] . ";";

            $prepare = $conexion->prepare($consulta);
            $prepare->execute();
            $resultado = $prepare->fetchAll();

            $pdf->SetY($manejoY);
            $pdf->SetX($manejoX);
            $pdf->Cell(28, 5, '$' . number_format($resultado[0]["val_valorAdelanto"]), 0, 0, 'L');

            $manejoX = $manejoX + 25;
            $pdf->SetY($manejoY);
            $pdf->SetX($manejoX);
            $pdf->Cell(28, 5, '$' . number_format($resultado[0]["valorservicio"]), 0, 0, 'L');
        }

        /*
         * 201810251008 
         * Se agrega el valor declarado al anticipo
         */
        $consulta = "select valor "
                . "from valordeclarado "
                . "where guia=" . $guias[$index] . ";";

        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();

        $manejoX += 25;
        $pdf->SetX($manejoX);
        $pdf->Cell(50, 5, '$' . number_format($resultado[0]["valor"]), 0, 0, 'L');

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
    $pdf->Cell(28, 5, '$' . number_format($_POST["totalAnticipos"]), 0, 0, 'L');
    $pdf->SetFont('Courier', '', 9);
    $manejoY = $manejoY + 5;
    $pdf->Line(9, $manejoY, 200, $manejoY);
    $manejoX = $manejoX + 25;
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, '$' . number_format($_POST["valorServicioMostrar"]), 0, 0, 'L');
    $pdf->SetY($manejoY);
    $manejoX = $manejoX - 37;
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, 'SALDO ', 0, 0, 'L');
    $pdf->SetY($manejoY);
    $manejoX = $manejoX + 37;
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, '$' . number_format($_POST["saldo"]), 0, 0, 'L');

    $pdf->SetX(15);
    $manejoY = $manejoY + 9;
    $pdf->Line(15, $manejoY, 60, $manejoY);

    $pdf->SetFont('Courier', 'B', 9);
    $pdf->SetY($manejoY + 1);
    $pdf->MultiCell(180, 5, 'SON: ' . utf8_decode($_POST["valorLetras"]), 0, 'L', false);
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
    $pdf->Cell(10, 5, $_POST["numeroAnticipo"], 0, 0, 'L');

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

    $pdf->SetY($manejoY);
    $pdf->SetX(10);
    $pdf->Cell(42, 5, 'PROPIETARIO:', 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(33);
    $pdf->Cell(50, 5, $_POST["nombre_conductor"] . ' ' . $_POST["apellido_conductor"], 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(95);
    $pdf->Cell(60, 5, "C.C. PROP.:", 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(118);
    $pdf->Cell(50, 5, $_POST["identificacion_conductor"], 0, 0, 'L');
    $pdf->SetX(155);
    $pdf->Cell(60, 5, "PLACA:", 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(175);
    $pdf->Cell(50, 5, $_POST["placa"], 0, 0, 'L');

    $manejoY = $manejoY + 5;

    $pdf->SetY($manejoY);
    $pdf->SetX(10);
    $pdf->Cell(45, 5, utf8_decode('DIRECCIÓN:'), 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(30);
    $pdf->Cell(50, 5, $_POST["direccion_conductor"], 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(95);
    $pdf->Cell(45, 5, utf8_decode('TELÉFONO:'), 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(118);
    $pdf->Cell(50, 5, $_POST["telefono_conductor"], 0, 0, 'L');
    $pdf->SetX(155);
    $pdf->Cell(45, 5, utf8_decode('CIUDAD:'), 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(175);
    $pdf->Cell(50, 5, utf8_decode($nombreCiudad), 0, 0, 'L');

    $manejoY = $manejoY + 5;

    $pdf->SetY($manejoY);
    $pdf->SetX(10);
    $pdf->Cell(45, 5, utf8_decode('CONDUCTOR:'), 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(33);
    $pdf->Cell(50, 5, utf8_decode($nombresConductor), 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(95);
    $pdf->Cell(45, 5, utf8_decode('C.C. COND.:'), 0, 0, 'L');
    $pdf->SetY($manejoY);
    $pdf->SetX(118);
    $pdf->Cell(50, 5, $cedulaConductor, 0, 0, 'L');

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

    for ($index = 0; $index < $cantidadGuiones; $index++) {

        $consulta = "select svg.guia,m.mun_nombre,c.cli_nombre "
                . "from serviciovariasguias as svg,municipios as m,cliente as c "
                . "where svg.idciudaddestino=m.mun_id "
                . "and svg.idcliente = c.cli_documento "
                . "and svg.guia=" . $guias[$index] . ";";

        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();

        $pdf->SetY($manejoY);
        $pdf->SetX($manejoX);
        $pdf->Cell(28, 5, $resultado[0]["guia"], 0, 0, 'L');

        $manejoX = $manejoX + 17;

        $pdf->SetY($manejoY);
        $pdf->SetX($manejoX);
        $fecha = substr($fechaServicio, 0, 10);
        $pdf->Cell(28, 5, $fecha, 0, 0, 'L');

        $manejoX = $manejoX + 20;

        $pdf->SetY($manejoY);
        $pdf->SetX($manejoX);
        $pdf->Cell(28, 5, $ciudadOrigen, 0, 0, 'L');

        $manejoX = $manejoX + 20;

        $pdf->SetY($manejoY);
        $pdf->SetX($manejoX);
        $pdf->Cell(28, 5, $resultado[0]["mun_nombre"], 0, 0, 'L');

        $manejoX = $manejoX + 20;

        $pdf->SetY($manejoY);
        $pdf->SetX($manejoX);
        $nombreEmpresa = substr($resultado[0]["cli_nombre"], 0, 15);
        $pdf->Cell(28, 5, $nombreEmpresa, 0, 0, 'L');

        $manejoX = $manejoX + 30;

        if ($varEmp === '0') {
            $pdf->SetY($manejoY);
            $pdf->SetX($manejoX);
            $pdf->Cell(28, 5, '$' . number_format('0'), 0, 0, 'L');

            $manejoX = $manejoX + 25;
            $pdf->SetY($manejoY);
            $pdf->SetX($manejoX);
            $pdf->Cell(28, 5, '$' . number_format('0'), 0, 0, 'L');
        } else {
            $consulta = "select val_valorAdelanto,valorservicio "
                    . "from valoresanticipos "
                    . "where val_numeroGuia=" . $guias[$index] . ";";

            $prepare = $conexion->prepare($consulta);
            $prepare->execute();
            $resultado = $prepare->fetchAll();

            $pdf->SetY($manejoY);
            $pdf->SetX($manejoX);
            $pdf->Cell(28, 5, '$' . number_format($resultado[0]["val_valorAdelanto"]), 0, 0, 'L');

            $manejoX = $manejoX + 25;
            $pdf->SetY($manejoY);
            $pdf->SetX($manejoX);
            $pdf->Cell(28, 5, '$' . number_format($resultado[0]["valorservicio"]), 0, 0, 'L');
        }

        $consulta = "select valor "
                . "from valordeclarado "
                . "where guia=" . $guias[$index] . ";";

        $prepare = $conexion->prepare($consulta);
        $prepare->execute();
        $resultado = $prepare->fetchAll();

        $manejoX += 25;
        $pdf->SetX($manejoX);
        $pdf->Cell(50, 5, '$' . number_format($resultado[0]["valor"]), 0, 0, 'L');

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
    $pdf->Cell(28, 5, '$' . number_format($_POST["totalAnticipos"]), 0, 0, 'L');
    $pdf->SetFont('Courier', '', 9);
    $manejoY = $manejoY + 5;
    $pdf->Line(9, $manejoY, 200, $manejoY);
    $manejoX = $manejoX + 25;
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, '$' . number_format($_POST["valorServicioMostrar"]), 0, 0, 'L');
    $pdf->SetY($manejoY);
    $manejoX = $manejoX - 37;
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, 'SALDO ', 0, 0, 'L');
    $pdf->SetY($manejoY);
    $manejoX = $manejoX + 37;
    $pdf->SetX($manejoX);
    $pdf->Cell(28, 5, '$' . number_format($_POST["saldo"]), 0, 0, 'L');

    $pdf->SetX(15);
    $manejoY = $manejoY + 9;
    $pdf->Line(15, $manejoY, 60, $manejoY);

    $pdf->SetFont('Courier', 'B', 9);
    $pdf->SetY($manejoY + 1);
    $pdf->MultiCell(180, 5, 'SON: ' . utf8_decode($_POST["valorLetras"]), 0, 'L', false);

    $numeroCotizacion = "ANTICIPO_NUMERO_" . $_POST["numeroAnticipo"] . ".pdf";
    $pdf->Output($numeroCotizacion, "D");
    header("Location: ../modulos/index.php?msj=2");
} else if ($cantidadVeces >= 1) {
    header("Location: ../modulos/index.php?msj=10&ant=" . $_POST["numeroAnticipo"] . "");
} else {
    header("Location: ../modulos/index.php?msj=1");
}

$conexion = null;

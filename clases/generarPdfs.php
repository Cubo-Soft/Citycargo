<?php

include_once '../clases/conexion.php';
include_once '../fpdf17/fpdf.php';

class generarPdfs extends FPDF
{

    private $con, $resultado, $arreglo, $consulta, $guia, $idservicio, $prepare;
    public $condition1;

    function getGuia()
    {
        return $this->guia;
    }

    function getIdservicio()
    {
        return $this->idservicio;
    }

    function setGuia($guia)
    {
        $this->guia = $guia;
    }

    function setIdservicio($idservicio)
    {
        $this->idservicio = $idservicio;
    }

    public function iniciarGuiaIdservicio($guia, $idservicio)
    {
        $this->guia = $guia;
        $this->idservicio = $idservicio;
    }

    public function Header()
    {

        $this->Line(8, 9.5, 290, 9.5);
        $this->Image('../imagenes/logocity.jpg', 8, 10, 70, 20);
        $this->SetFont('Arial', 'B', 12);
        $this->con = new Conexion();
        $this->consulta = "select s.fechaServicio as fecha,s.placa "
            . "from servicio as s "
            . "where s.idservicio=" . $this->idservicio . ";";
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();
        $fechaServicio = $this->arreglo[0]["fecha"];
        $placa = $this->arreglo[0]["placa"];

        $this->consulta = "select mun_nombre as origen "
            . "from municipios "
            . "where mun_id=(select ciudad from direcciones where iddireccion=(select iddireccionorigen from servicio_guias where idservicio=" . $this->idservicio . " and numeroGuia=" . $this->guia . "));";
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();
        $origen = $this->arreglo[0]["origen"];

        $this->consulta = "select mun_nombre "
            . "from municipios "
            . "where mun_id=(select ciudad from direcciones where iddireccion=(select iddirecciondestino from servicio_guias where idservicio=" . $this->idservicio . " and numeroGuia=" . $this->guia . "));";
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();
        $destino = $this->arreglo[0]["mun_nombre"];

        $this->consulta = "select c.cli_nombre "
            . "from servicio_guias as sg,cliente as c "
            . "where sg.nit=c.cli_documento "
            . "and sg.idservicio=" . $this->idservicio . " "
            . "and sg.numeroGuia=" . $this->guia . ";";
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();
        $cliente = $this->arreglo[0]["cli_nombre"];

        $this->consulta = "select concat(cond_nombres,' ',cond_apellidos) as nombreConductor "
            . "from servicio as s,conductores as c "
            . "where s.cedulaConductor=c.cond_identificacion "
            . "and s.idservicio=" . $this->idservicio . ";";
        $this->prepare = $this->con->prepare($this->consulta);
        $this->prepare->execute();
        $this->arreglo = $this->prepare->fetchAll();
        $nombreConductor = $this->arreglo[0]["nombreConductor"];

        //vertical linea antes de imagen         
        $this->Line(8, 10, 8, 42);
        //vertical final cabecera
        $this->Line(290, 10, 290, 42);
        //vertical linea despues de imagen 
        $this->Line(80, 10, 80, 30);
        //linea desde imagen hacia margen derecha divisoria
        $this->Line(80, 20, 290, 20);
        //establecer altura de las siguiente linea

        $this->setY(10);
        $this->SetX(80);
        $this->Cell(0, 4, utf8_decode('Guía'), 0, 0, 'L');
        //establecer altura de las siguiente linea
        $this->setY(11.5);
        $this->SetX(80);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, utf8_decode($this->guia), 0, 0, 'L');

        //vertical linea despues de guia 
        $this->Line(105, 10, 105, 30);

        $this->setY(15);
        $this->SetX(80);
        $this->SetFont('Arial', 'B', 12);
        $this->Cell(0, 14, utf8_decode('Fecha serv'), 0, 0, 'L');
        $this->setY(22);
        $this->Cell(70);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, utf8_decode($fechaServicio), 0, 0, 'L');
        //        //$this->Cell(0, 10, utf8_decode(substr($destino, 0, 11)), 0, 0, 'L');

        $this->SetFont('Arial', 'B', 12);
        //moverse a la derecha        
        $this->setY(7);
        $this->SetX(105);
        //texto en la celda que se movío
        $this->Cell(0, 10, utf8_decode('Origen'), 0, 0, 'L');
        $this->Line(140, 10, 140, 30);

        $this->setY(12);
        $this->SetX(105);
        $this->SetFont('Arial', '', 10);
        $this->Cell(0, 10, utf8_decode(substr($origen, 0, 11)), 0, 0, 'L');
        //texto en la celda que se movío
        //$this->Cell(0, 10, utf8_decode($this->guia), 0, 0, 'L');

        $this->SetFont('Arial', 'B', 12);
        $this->setY(18);
        $this->SetX(105);
        //texto en la celda que se movío
        $this->Cell(0, 10, utf8_decode('Destino'), 0, 0, 'L');

        $this->SetFont('Arial', '', 10);
        $this->setY(22);
        $this->SetX(105);
        $this->Cell(0, 10, utf8_decode(substr($destino, 0, 11)), 0, 0, 'L');
        //$this->Cell(0, 10, utf8_decode($placa), 0, 0, 'L');

        $this->Line(210, 10, 210, 20);
        $this->SetFont('Arial', 'B', 12);
        $this->setY(7.5);
        $this->Cell(130);
        $this->Cell(0, 10, utf8_decode('Placa'), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->setY(12.5);
        $this->Cell(130);
        $this->Cell(0, 10, utf8_decode($placa), 0, 0, 'L');

        $this->SetFont('Arial', 'B', 12);
        $this->setY(18);
        $this->Cell(130);
        $this->Cell(0, 10, utf8_decode('Empresa'), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->setY(22);
        $this->Cell(130);
        $this->Cell(0, 10, utf8_decode($cliente), 0, 0, 'L');

        $this->SetFont('Arial', 'B', 12);
        $this->setY(7.5);
        $this->Cell(200);
        $this->Cell(0, 10, utf8_decode('Conductor'), 0, 0, 'L');
        $this->SetFont('Arial', '', 10);
        $this->setY(12.5);
        $this->Cell(200);
        $this->Cell(0, 10, utf8_decode($nombreConductor), 0, 0, 'L');

        $this->Line(8, 30, 290, 30);

        if ($this->condition1 === 1) {

            //establecer altura de las siguiente linea
            $this->setY(28);
            $this->SetX(120);
            $this->SetFont('Arial', 'B', 12);
            //texto en la celda que se movío
            $this->Cell(0, 10, 'DATOS SEGUIMIENTO', 0, 0, 'L');
            $this->Line(8, 36, 290, 36);

            //de aqui para abajo es lo que se va a ver en el cuerpo del pdf
            $this->SetX(9);
            $this->setY(34);
            $this->Cell(0, 10, 'No', 0, 0, 'L');
            $this->Line(17, 36, 17, 42);
            $this->setX(20);
            $this->Cell(0, 10, 'Fecha y hora', 0, 0, 'L');
            $this->Line(55, 36, 55, 42);
            $this->setX(56);
            $this->Cell(0, 10, utf8_decode('Ubicación'), 0, 0, 'L');
            $this->Line(100, 36, 100, 42);
            $this->setX(100);
            $this->Cell(0, 10, utf8_decode('Observación'), 0, 0, 'L');
            $this->Line(180, 36, 180, 42);
            $this->setX(180);
            $this->Cell(0, 10, utf8_decode('Estado'), 0, 0, 'L');
            $this->Line(210, 36, 210, 42);
            $this->setX(210);
            $this->Cell(0, 10, utf8_decode('Imagen GPS'), 0, 0, 'L');


            //cierre del cabezote
            $this->Line(8, 42, 290, 42);

            $this->con = null;
        }

        if ($this->condition1 === 2) {

            //establecer altura de las siguiente linea
            $this->setY(28);
            $this->SetX(120);
            $this->SetFont('Arial', 'B', 12);
            //texto en la celda que se movío
            $this->Cell(0, 10, 'CUMPLIDOS', 0, 0, 'L');
            $this->Line(8, 36, 290, 36);

            //cierre del cabezote
            //$this->Line(8, 42, 290, 42);

            $this->con = null;
        }
    }

    public function inicioSeguimientos($pruebasEntregas)
    {

        for ($index = 0; $index < count($pruebasEntregas); $index++) {

            $this->AddPage();
            
            $pathinfo = pathinfo($pruebasEntregas[$index]["ruta"]);
            $extension = $pathinfo['extension'];

            if ($extension === 'pdf') {
                $this->Image('../imagenes/pdf_16.png', 50, 38, 10, 10);
            } else {
                $this->Image($pruebasEntregas[$index]["ruta"], 50, 38, 180, 160);
            }

            
        }
    }

    public function Footer()
    {

        // Posición: a 1,5 cm del final
        $this->SetY(-15);
        // Arial italic 8
        $this->SetFont('Arial', 'I', 8);
        // Número de página
        $this->Cell(0, 10, utf8_decode('Tecnología CITYCARGO - ' . date('Y') . ' - Página ') . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }

    public function retornarSeguimientos()
    {
        try {
            $this->con = new Conexion();
            $this->consulta = "select fechaHora,ubicacion,observacion,imagen,estadoseguimiento "
                . "from seguimiento "
                . "where guia=" . $this->guia . " "
                    . "order by fechaHora asc;";
            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->resultado = $this->prepare->fetchAll();
            $this->con = null;
            return $this->resultado;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarPruebasEntrega($guia)
    {
        try {
            $this->con = new Conexion();

            $this->consulta = "select * from pruebasentrega "
                . "where guia=" . $guia . ";";

            $this->prepare = $this->con->prepare($this->consulta);
            $this->prepare->execute();
            $this->resultado = $this->prepare->fetchAll();
            $this->con = null;
            return $this->resultado;
        } catch (PDOException $exc) {
            echo $exc->getTraceAsString();
        }
    }

    public function retornarAltura($texto)
    {
        $inicio = 0;
        $vlrIncremento = 1;
        $finCiclo = 1000;

        for ($index = $vlrIncremento; $index < $finCiclo; $index = $index + $vlrIncremento) {

            if (strlen($texto) <= $index) {
                $inicio = $inicio + $vlrIncremento;
                $index = $finCiclo;
            } else {
                $inicio = $inicio + $vlrIncremento;
            }
        }
        return $inicio / 8;
    }
}

<?php

session_start();

require '../clases/generarPdfs.php';

if (isset($_POST["botonSeguimiento"])) {

    header("Location: ../modulos/gestionarSeguimiento.php?idservicio=" . $_POST["idservicio"] . ";");
} else {

    $valorMayor = 0;

    $nvoPdf = new generarPdfs('L', 'mm', 'A4');
    $nvoPdf->condition1 = 1;
    //$nvoPdf->iniciarGuiaIdservicio($_POST["guia"], $_POST["idservicio"]);
    $nvoPdf->iniciarGuiaIdservicio($_POST["guia"], $_POST["idservicio"]);
    $nvoPdf->AliasNbPages();
    $nvoPdf->AddPage();
    $nvoPdf->SetFont('Times', '', 10);

    $seguimientos = $nvoPdf->retornarSeguimientos();

    $linea = null;

    $cantidad = count($seguimientos);

    $inicio = 25;
    $ultimaPosicion = 0;
    $adicion = 0;
    $posicionAntes = 0;

    $posicionY = $nvoPdf->GetY();

    for ($i = 0; $i < $cantidad; $i++) {

        if ($i > 0) {
            $posicionY += 5;
        }

        $nvoPdf->SetX(9);
        $nvoPdf->setY($posicionY);
        $posicionYInicial = $nvoPdf->GetY();
        $nvoPdf->Cell(0, 25, $i + 1, 0, 0, 'L');
        $nvoPdf->setX(18);
        $nvoPdf->Cell(0, 25, $seguimientos[$i]["fechaHora"], 0, 0, 'L');
        $nuevaPosicionY = $nvoPdf->GetY();
        $nuevaPosicionY = $nuevaPosicionY + 9;
        $nvoPdf->SetY($nuevaPosicionY);
        $nvoPdf->setX(56);
        $nvoPdf->MultiCell(60, 4.5, utf8_decode($seguimientos[$i]["ubicacion"]), 0, 'L', false);
        $nvoPdf->SetY($nuevaPosicionY);
        $nvoPdf->setX(100);
        $nvoPdf->MultiCell(60, 4.5, utf8_decode($seguimientos[$i]["observacion"]), 0, 'L', false);
        $nvoPdf->setX(179);
        $nvoPdf->MultiCell(20, 4.5, utf8_decode($seguimientos[$i]["estadoseguimiento"]), 0, 'L', false);
        $posicionDespuesY = $nvoPdf->GetY();
        
        if ($seguimientos[$i]["imagen"] === '0') {            
            $posicionY = $posicionDespuesY - 8;            
        } else {
            $posicionY = $posicionDespuesY + 8;
        }
                
        $nvoPdf->setX(210);
        if ($seguimientos[$i]["imagen"] === '0') {
            //$nvoPdf->SetY($posicionY-2);
            $nvoPdf->setX(210);
            $nvoPdf->Cell(25, 5, utf8_decode('Sin imagen GPS'), 0, 0, 'L');
            $nvoPdf->SetY($posicionY);

            //echo $nvoPdf->GetY().' sin imagen <br/>';
        } else {
            $nvoPdf->SetY($posicionY);

            if (file_exists($seguimientos[$i]["imagen"])) {
                $nvoPdf->Image($seguimientos[$i]["imagen"], 210, $posicionY - 10, 79, 60);
                //echo $nvoPdf->GetY().' con imagen <br/>';
            } else {
                $nvoPdf->Image("../imagenes/seguimientoGPS/noborrar.jpg", 210, $posicionY - 10, 79, 60);
            }

            //echo 'posicion con imagen '.$nvoPdf->GetY().'<br>';
            $posicionY = $nvoPdf->GetY();
            $nvoPdf->SetY($posicionY + 43);
        }

        //echo $nvoPdf->GetY().' despues de imagen<br/>'; 


        $posicionY = $nvoPdf->GetY();
        $nvoPdf->Line(8, $posicionY + 9, 290, $posicionY + 9);
        $posicionY = $nvoPdf->GetY();
    }

    $pruebasEntregas = $nvoPdf->retornarPruebasEntrega($_POST["guia"]);

    $nvoPdf->condition1 = 2;

    $nvoPdf->inicioSeguimientos($pruebasEntregas);

    $nvoPdf->Output("Seguimiento_Guia_" . $_POST["guia"] . '.pdf', "D");
}
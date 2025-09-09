<?php

class hora {

    public function retornarHora() {
        try {
            $today = getdate();

            $fechaHora = null;
            $anio = $today['year'];
            $mes = $today['mon'];
            $dia = $today['wday'];
            $hora = $today['hours'];
            $minutos = $today['minutes'];
            $segundos = $today['seconds'];

            $fechaHora = $anio . '-';

            if (strlen($mes) === 1) {
                $fechaHora .= '0' . $mes;
            } else {
                $fechaHora .= $mes;
            }

            $fechaHora .= '-';

            if (strlen($dia) === 1) {
                $fechaHora .= '0' . $dia;
            } else {
                $fechaHora .= $dia;
            }

            $fechaHora .= ' ';

            if (strlen($hora) === 1) {
                $fechaHora .= '0' . $hora;
            } else {
                $fechaHora .= $hora;
            }

            $fechaHora .= '-';

            if (strlen($minutos) === 1) {
                $fechaHora .= '0' . $minutos;
            } else {
                $fechaHora .= $minutos;
            }

            $fechaHora .= '-';

            if (strlen($segundos) === 1) {
                $fechaHora .= '0' . $segundos;
            } else {
                $fechaHora .= $segundos;
            }

            return $fechaHora;
        } catch (Exception $exc) {
            echo $exc->getTraceAsString();
        }
    }

}

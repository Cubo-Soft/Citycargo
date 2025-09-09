<?php

class fechas {

    public static function retornarFecha() {
        if (date("m") === '01') {
            $anioAnterior = date("Y") - 1;           
            $fechaInicial = $anioAnterior . '-12-' . date("d");
        } else {
            $mesAnterior = date("m") - 1;
            $fechaInicial = date("Y") . '-' . $mesAnterior . '-' . date("d");
        }
        
        return $fechaInicial;
    }

}

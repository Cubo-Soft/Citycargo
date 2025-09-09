<?php

/*
 * 20171201
 * Este archivo retorna el propietario y conductores de una placa 
 * ingresada en el input text placa de la vista servicios.php
 */

include '../clases/servicios.php';
include '../clases/funcionesVarias.php';

$servicios=new servicios();

$placa= limpiarVariable($_POST["placa"]);
return $servicios->retornarDatosDePlaca($placa);




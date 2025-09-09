<?php

session_start();

include_once '../clases/conexion.php';

$conexion = new Conexion();

$sentencia = "ALTER TABLE `valoresanticipos` ADD `placa` VARCHAR(6) NOT NULL COMMENT 'La placa del vehiculo que presta el servicio' AFTER `conductores_cond_id`;";
$preparar = $conexion->prepare($sentencia);
$preparar->execute();

$sentencia = "select idservicio,placa "
        . "from servicio;";
$preparar = $conexion->prepare($sentencia);
$preparar->execute();
$arreglo = $preparar->fetchAll();

for ($index = 0; $index < count($arreglo); $index++) {
    $sentencia = "update valoresanticipos "
            . "set placa='" . $arreglo[$index]["placa"] . "' "
            . "where idservicio=" . $arreglo[$index]["idservicio"] . ";";
    $preparar = $conexion->prepare($sentencia);
    $preparar->execute();
}

$conexion = null;

echo 'Fin, hora de revisar!';

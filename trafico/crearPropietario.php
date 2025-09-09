<?php

include '../clases/conductores.php';

$conductor=new conductores();
$conductor->agregarPlacaPropietario($cond_id, $cedulaConductor, $nombres, $apellidos, $direccion, $telefono, $municipio, $perfil, $placa, $estado, $estadoRelacion);
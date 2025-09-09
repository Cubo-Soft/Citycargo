<?php

include_once '../clases/serviciosporcancelar.php';

$serviciosCancelar=new serviciosporcancelar();

echo json_decode($serviciosCancelar->cambiarEstado($_POST["id"]));
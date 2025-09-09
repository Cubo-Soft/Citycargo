<?php

require_once '../clases/serviciosporcancelar.php';

$servicioPorCancelar = new serviciosporcancelar();

if ($_POST["caso"] === '1') {
    echo json_encode($servicioPorCancelar->cambiarEstadoCancelado($_POST["idservicio"]));
}

if ($_POST["caso"] === '2') {
    echo json_encode($servicioPorCancelar->crearServicio($_POST["idservicio"], $_POST["idempleado"], date("Y-m-d h:m:s"), $_POST["motivo"]));
}

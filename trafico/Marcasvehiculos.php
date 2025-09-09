<?php

include_once '../clases/marcas.php';

$marca = new marcas();

if ($_POST["caso"] === '1') {
    echo json_encode($marca->crearMarca($_POST["marca"]));
}

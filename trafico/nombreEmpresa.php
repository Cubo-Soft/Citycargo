<?php

include_once '../clases/cliente.php';

$cli=new cliente();
$cli->retornarNombreEmpresa($_POST["nit_cliente"]);

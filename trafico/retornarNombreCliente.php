<?php

include '../clases/cliente.php';

$cli=new cliente();
$cli->retornarNombreEmpresa($_POST["nit"]);
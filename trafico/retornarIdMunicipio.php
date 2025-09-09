<?php

include '../clases/municipios.php';

$mun=new municipios();
$mun->retornarNombreMunicipios($_POST["ciudad_cliente"]);
      


<?php

include '../clases/procesosGuias.php';

$procesosGuias=new procesosGuias();
echo json_encode($procesosGuias->cambiarMensajeBorrado($_POST["idservicio"]));
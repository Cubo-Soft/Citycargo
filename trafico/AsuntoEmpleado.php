<?php

include_once '../clases/asunto_empleado.php';

$asunto_empleado = new asunto_empleado();

if ($_POST["caso"] === '1') {
    echo json_encode($asunto_empleado->crearAsuntoEmpleado($_POST["idasunto"],$_POST["nit"],$_POST["dlDirOrg"],$_POST["fechaHoraInicio"],$_POST["fechaHoraFin"],$_POST["listaContactos"],$_POST["cedulaEmpleado"],0));
}

if ($_POST["caso"] === '2') {        
    echo json_encode($asunto_empleado->crearAsuntoEmpleado($_POST["idasunto"],0,0,$_POST["fechaHoraInicio"],$_POST["fechaHoraFin"],0,$_POST["cedulaEmpleado"],$_POST["asuntoPersonal"]));
}

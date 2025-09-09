<?php

include '../clases/cliente.php';

$cliente = new cliente();

switch ($_POST["caso"]) {
    case '1':
        echo json_encode($cliente->retornarDatosCliente($_POST["nit"]));
        break;
    case '2':       
        echo json_encode($cliente->crearEmpresa($_POST["nit"], $_POST["nombreCliente"], $_POST["contacto"], $_POST["direccion"], $_POST["correo"],$_POST["telefonoUno"], $_POST["objeto"], $_POST["cedulaAsesor"], $_POST["municipio"]));
        break;
    case '3':
        echo json_encode($cliente->modificarDatosCliente($_POST["cli_id"], $_POST["municipio"], $_POST["nit"], $_POST["nombreCliente"], $_POST["contacto"], $_POST["direccion"], $_POST["correo"], $_POST["telefonoUno"], $_POST["objeto"], $_POST["cedulaAsesor"], $_POST["estadoCliente"]));
        break;
    case '4':        
        echo json_encode($cliente->retornarClientes());
        break;
}

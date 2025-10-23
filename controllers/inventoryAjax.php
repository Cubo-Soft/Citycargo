<?php
session_start();

if (!isset($_SESSION["rol_id"])) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit;
}

require_once '../models/inventoryModel.php';
$model = new inventoryModel();

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

switch ($action) {

    // ✅ OBTENER DATOS COMPLETOS DEL VEHÍCULO
    case 'obtenerDatosVehiculo':
        $placa = trim($_POST['placa'] ?? '');
        if (empty($placa)) {
            echo json_encode(['success' => false, 'message' => 'Placa no proporcionada']);
            exit;
        }

        $datos = $model->obtenerDatosVehiculoCompleto($placa);

        if ($datos) {
            echo json_encode(['success' => true, 'data' => $datos]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Vehículo no encontrado o sin propietario asignado.']);
        }
        break;

    // ✅ CREAR NUEVO VEHÍCULO
    case 'crearVehiculo':
        $placa = trim($_POST['placa'] ?? '');
        $id_marca = (int) ($_POST['id_marca'] ?? 0);
        $tipovehiculo = trim($_POST['tipovehiculo'] ?? '');
        $tipocarroceria = trim($_POST['tipocarroceria'] ?? '');
        $modelo = (int) ($_POST['modelo'] ?? 0);
        $capacidadcarga = (int) ($_POST['capacidadcarga'] ?? 0);

        if (
            empty($placa) || !$id_marca || empty($tipovehiculo) ||
            empty($tipocarroceria) || !$modelo || !$capacidadcarga
        ) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            break;
        }

        if ($model->existePlaca($placa)) {
            echo json_encode(['success' => false, 'message' => 'La placa ya existe.']);
            break;
        }

        $data = compact('placa', 'id_marca', 'tipovehiculo', 'tipocarroceria', 'modelo', 'capacidadcarga');

        if ($model->crearVehiculo($data)) {
            echo json_encode(['success' => true, 'placa' => $placa]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el vehículo.']);
        }
        break;











    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
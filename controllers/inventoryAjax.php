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
            echo json_encode(['success' => false, 'message' => 'Vehículo no encontrado.']);
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

        if (empty($placa) || !$id_marca || empty($tipovehiculo) || empty($tipocarroceria) || !$modelo || !$capacidadcarga) {
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

    // ✅ GUARDAR INVENTARIO COMPLETO
    case 'guardarInventario':
        try {
            // ✅ Usar emp_id directamente de la sesión
            if (!isset($_SESSION['emp_id'])) {
                throw new Exception("Usuario no autenticado correctamente.");
            }
            $idGrabador = (int)$_SESSION['emp_id'];

            if ($idGrabador <= 0) {
                throw new Exception("ID de usuario inválido.");
            }
error_log("POST recibido: " . print_r($_POST, true));
            $datosEncabezado = [
                'placa' => $_POST['placa'] ?? '',
                'nombre_propietario' => $_POST['nombre_propietario'] ?? '',
                'identificacion' => $_POST['identificacion'] ?? '',
                'tipo_vehiculo' => $_POST['tipo_vehiculo'] ?? '',
                'marca' => $_POST['marca'] ?? '',
                'tipo_carroceria' => $_POST['tipo_carroceria'] ?? '',
                'kilometraje' => !empty($_POST['kilometraje']) ? (int)$_POST['kilometraje'] : 0,
                'fecha' => $_POST['fecha'] ?? date('Y-m-d'),
                'observaciones_generales' => $_POST['observaciones_generales'] ?? ''
            ];

            if (empty($datosEncabezado['placa'])) {
                throw new Exception("La placa es obligatoria.");
            }

            $detalleInventario = $_POST['detalle'] ?? [];
            $model->guardarInventarioCompleto($datosEncabezado, $detalleInventario, $idGrabador);

            echo json_encode(['success' => true, 'message' => 'Inventario guardado correctamente.']);
        } catch (Exception $e) {
            error_log("Error en guardarInventario: " . $e->getMessage());
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
<?php
session_start();

if (!isset($_SESSION["rol_id"])) {
    header("HTTP/1.1 403 Forbidden");
    echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
    exit;
}

require_once '../models/maintenanceModel.php';
$model = new maintenanceModel();

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

switch ($action) {
    case 'getDetail':
        $id = $_POST['id'] ?? 0;
        $mant = $model->obtenerDetalleMantenimiento($id);
        if ($mant) {
            echo json_encode([
                'success' => true,
                'data' => [
                    'factura' => $mant['factura'],
                    'placa' => $mant['placa'],
                    'empresa' => $mant['empresa'],
                    'servicio' => $mant['servicio'],
                    'valor' => number_format($mant['valor'], 0, ',', '.'),
                    'fecha' => date('d/m/Y', strtotime($mant['fecha'])),
                    'kilometraje' => $mant['kilometraje'] ?? 'N/A',
                    'observaciones' => $mant['observaciones'] ?? 'Ninguna',
                    'id_prestador' => $mant['id_prestador'],
                    'id_tipo_manteni' => $mant['id_tipo_manteni'],
                ]
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Mantenimiento no encontrado']);
        }
        break;

    case 'save':
        $placa = $_POST['placa'] ?? '';
        $id_prestador = $_POST['id_prestador'] ?? '';
        $id_tipo_manteni = $_POST['id_tipo_manteni'] ?? '';
        $factura = $_POST['num_factura'] ?? '';
        $valor = str_replace(['$', ',', '.'], '', $_POST['costo']);
        $fecha = $_POST['fecha'] ?? date('Y-m-d');
        $kilometraje = $_POST['kilometraje'] ?? null;
        $obs = $_POST['observaciones'] ?? '';

        if (empty($placa) || empty($id_prestador) || empty($id_tipo_manteni) || empty($factura) || empty($valor)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            break;
        }

        if ($model->guardarMantenimiento($factura, $placa, $id_prestador, $id_tipo_manteni, $valor, $fecha, $kilometraje, $obs)) {
            echo json_encode(['success' => true, 'message' => 'Mantenimiento registrado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar.']);
        }
        break;

    case 'update': // ✅ NUEVA ACCIÓN PARA EDITAR
        $id = $_POST['id'] ?? 0;
        $placa = $_POST['placa'];
        $id_prestador = $_POST['id_prestador'];
        $id_tipo_manteni = $_POST['id_tipo_manteni'];
        $num_factura = $_POST['num_factura'];
        $costo = str_replace(['$', ',', '.'], '', $_POST['costo'] ?? '');
        $fecha = $_POST['fecha'];
        $kilometraje = $_POST['kilometraje'] ?? null;
        $observaciones = $_POST['observaciones'] ?? '';

        // ✅ VALIDAR QUE EL ID EXISTA
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID no válido']);
            exit;
        }

        // ✅ VALIDAR CAMPOS OBLIGATORIOS (los mismos que usa el modelo)
        if (empty($placa) || empty($id_prestador) || empty($id_tipo_manteni) || empty($num_factura) || empty($costo) || empty($fecha)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            exit;
        }

        if ($model->actualizarMantenimiento(
        $id,                     
        $num_factura,            
        $placa,                  
        $id_prestador,           
        $id_tipo_manteni,        
        $costo,                  
        $fecha,                  
        $kilometraje,         
        $observaciones          
    )) {
        echo json_encode(['success' => true, 'message' => 'Actualizado correctamente']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No se pudo actualizar. Verifique los datos.']);
    }
    break;

    case 'quickSave':
        $placa = $_POST['placa'] ?? '';
        $id_prestador = $_POST['id_prestador'] ?? '';
        $id_tipo_manteni = $_POST['id_tipo_manteni'] ?? '';
        $factura = $_POST['num_factura'] ?? '';
        $valor = str_replace(['$', ',', '.'], '', $_POST['costo']);
        $fecha = $_POST['fecha'] ?? date('Y-m-d');

        if (empty($placa) || empty($id_prestador) || empty($id_tipo_manteni) || empty($factura) || empty($valor)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            break;
        }

        if ($model->guardarMantenimiento($factura, $placa, $id_prestador, $id_tipo_manteni, $valor, $fecha, null, '')) {
            echo json_encode(['success' => true, 'message' => 'Registro rápido creado']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar.']);
        }
        break;

    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
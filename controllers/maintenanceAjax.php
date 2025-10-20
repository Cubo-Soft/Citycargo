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
    // ✅  PARA EDITAR MANTENIMIENTO
    case 'update':
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

        if (
            $model->actualizarMantenimiento(
                $id,
                $num_factura,
                $placa,
                $id_prestador,
                $id_tipo_manteni,
                $costo,
                $fecha,
                $kilometraje,
                $observaciones
            )
        ) {
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

    // ✅ GUARDAR PROXIMA REVISION
    case 'saveRev':
        $placa = $_POST['placa'] ?? '';
        $fecha_programada = $_POST['fecha_programada'] ?? date('Y-m-d');
        $kilometraje = $_POST['kilometraje_programado'] ?? null;
        $id_tipo_manteni = $_POST['id_tipo_manteni'] ?? '';

        if (empty($placa) || empty($fecha_programada) || !is_numeric($kilometraje) || $kilometraje < 0 || empty($id_tipo_manteni)) {
            echo json_encode(['success' => false, 'message' => 'Todos los campos son obligatorios.']);
            break;
        }


        if ($model->guardarProximaRevision($placa, $fecha_programada, $id_tipo_manteni, $kilometraje)) {
            echo json_encode(['success' => true, 'message' => 'Próxima revisión creada']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar la revisión.']);
        }
        break;

    // ✅ OBTENER PROXIMA REVISIÓN POR ID (para edición)
    case 'getRevisionById':
        $id = (int) ($_POST['id'] ?? 0);
        if ($id <= 0) {
            echo json_encode(['success' => false, 'message' => 'ID inválido']);
            break;
        }

        $revision = $model->obtenerProximaRevisionPorId($id);
        if ($revision) {
            echo json_encode([
                'success' => true,
                'data' => $revision
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Revisión no encontrada']);
        }
        break;


    // ✅ ACTUALIZAR PROXIMA REVISIÓN
    case 'updateRevision':
        $id = (int) ($_POST['id'] ?? 0);
        $placa = $_POST['placa'] ?? '';
        $id_tipo_manteni = (int) ($_POST['id_tipo_manteni'] ?? 0);
        $fecha = $_POST['fecha_programada'] ?? '';
        $km = (int) ($_POST['kilometraje_programado'] ?? -1);

        if ($id <= 0 || empty($placa) || $id_tipo_manteni <= 0 || empty($fecha) || $km < 0) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos.']);
            break;
        }

        if ($model->actualizarProximaRevision($id, $id_tipo_manteni, $fecha, $km)) {
            echo json_encode(['success' => true, 'message' => 'Revisión actualizada']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al actualizar.']);
        }
        break;

    // ✅ CREAR NUEVO PRESTADOR (EMPRESA)
    case 'crearPrestador':
        $nombre = trim($_POST['nombre'] ?? '');
        $nit = trim($_POST['nit'] ?? '');
        $direccion = trim($_POST['direccion'] ?? '');
        $contacto = trim($_POST['contacto'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($nombre) || empty($nit)) {
            echo json_encode(['success' => false, 'message' => 'Nombre y NIT son obligatorios.']);
            break;
        }

        // Verificar si ya existe usando el modelo
        if ($model->prestadorExiste($nombre, $nit)) {
            echo json_encode(['success' => false, 'message' => 'La empresa o NIT ya existe.']);
            break;
        }

        $idNuevo = $model->crearPrestador($nombre, $nit, $direccion, $contacto, $email);
        if ($idNuevo) {
            echo json_encode([
                'success' => true,
                'id' => $idNuevo,
                'message' => 'Empresa creada correctamente.'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar la empresa.']);
        }
        break;

    // ✅ CREAR NUEVO TIPO SERVICIO (TIPO MANTENIMIENTO))
    case 'crearServicio':
        $nombre = trim($_POST['nombre'] ?? '');
        $descripcion = trim($_POST['descripcion'] ?? '');

        if (empty($nombre) || empty($descripcion)) {
            echo json_encode(['success' => false, 'message' => 'Nombre y Descripción son obligatorios.']);
            break;
        }

        // Verificar si ya existe usando el modelo
        if ($model->servicioExiste($nombre)) {
            echo json_encode(['success' => false, 'message' => 'El nombre o Descripción ya existe.']);
            break;
        }

        $idNuevo = $model->crearServicio($nombre, $descripcion);
        if ($idNuevo) {
            echo json_encode([
                'success' => true,
                'id' => $idNuevo,
                'message' => 'Servicio creado correctamente.'
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el Servicio.']);
        }
        break;


    // ✅ CREAR NUEVO VEHÍCULO
    case 'crearVehiculo':
        $placa = trim($_POST['placa'] ?? '');
        $tipo_vehiculo = trim($_POST['tipo_vehiculo'] ?? '');
        $marca = trim($_POST['marca'] ?? '');
        $tipo_combustible = trim($_POST['tipo_combustible'] ?? '');
        $nombre_propietario = trim($_POST['nombre_propietario'] ?? null);
        $identificacion = trim($_POST['identificacion'] ?? null);

        if (empty($placa) || empty($tipo_vehiculo) || empty($marca) || empty($tipo_combustible)) {
            echo json_encode(['success' => false, 'message' => 'Placa, tipo, marca y combustible son obligatorios.']);
            break;
        }

        // Verificar si ya existe
        if ($model->existePlaca($placa)) {
            echo json_encode(['success' => false, 'message' => 'La placa ya está registrada.']);
            break;
        }

        $idNuevo = $model->crearVehiculo([
            'placa' => $placa,
            'tipo_vehiculo' => $tipo_vehiculo,
            'marca' => $marca,
            'tipo_combustible' => $tipo_combustible,
            'nombre_propietario' => $nombre_propietario,
            'identificacion' => $identificacion,
            'id_grabador' => $_SESSION['user_id'] ?? 1
        ]);

        if ($idNuevo) {
            echo json_encode(['success' => true, 'message' => 'Vehículo creado correctamente.']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Error al guardar el vehículo.']);
        }
        break;





    default:
        echo json_encode(['success' => false, 'message' => 'Acción no válida']);
}
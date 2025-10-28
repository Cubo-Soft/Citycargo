<?php
session_start();
if (!isset($_SESSION["rol_id"])) {
    header("Location: ../index.php");
    exit;
}

require_once '../models/inventoryModel.php';
    $model = new inventoryModel();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $datosEncabezado = [
        'placa' => $_POST['placa'] ?? '',
        'Nombre' => $_POST['Nombre'] ?? '',
        'Cédula' => $_POST['Cédula'] ?? '',
        'tipo_vehiculo' => $_POST['tipo_vehiculo'] ?? '',
        'marca' => $_POST['marca'] ?? '',
        'tipo_combustible' => $_POST['tipo_combustible'] ?? '',
        'kilometraje' => $_POST['kilometraje'] ?? '',
        'fecha' => $_POST['fecha'] ?? date('Y-m-d'),
        'observaciones_generales' => $_POST['observaciones_generales'] ?? ''
    ];

    $detalleInventario = $_POST['detalle'] ?? [];

    if (empty($datosEncabezado['placa'])) {
        $error = "La placa es obligatoria.";
    } else {
        try {
            $model->guardarInventarioCompleto($datosEncabezado, $detalleInventario, $idGrabador);
            header("Location: " . $_SERVER['PHP_SELF'] . "?msg=success");
            exit;
        } catch (Exception $e) {
            $error = "Error al guardar: " . $e->getMessage();
        }
    }
}

$elementos = $model->obtenerElementosInventario();
$placas = $model->obtenerPlacas(); 
$inventarios = $model->obtenerInventariosRegistrados();
$marcas = $model->obtenerMarcas();
$lineas = $model->obtenerLineas();
$tiposVehiculo = $model->obtenerTiposVehiculo();
$carrocerias = $model->obtenerCarrocerias();
$combustibles = $model->obtenerCombustibles();
$colores = $model->obtenerColores();

// Layout principal
include '../views/components/layout/head.php';
?>

<body class=" bg-image-curved">
    <?php include '../views/components/sidebar.php'; ?>

    <main class="main-content border-radius-lg">
        <?php include '../views/components/navbar.php'; ?>

        <!-- Contenido del módulo -->
        <div class="container-fluid py-4">
            <?php include '../views/inventoryView/index.php'; ?>
        </div>

        <?php include '../views/components/footer.php'; ?>
    </main>

    <?php include '../views/components/layout/scripts.php'; ?>
</body>

</html>
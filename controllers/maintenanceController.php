<?php
session_start();
if (!isset($_SESSION["rol_id"])) {
    header("Location: ../index.php");
    exit;
}

require_once '../models/maintenanceModel.php';
$model = new maintenanceModel();

// OBTENER DATOS
$mantenimientos = $model->obtenerMantenimientos();
$totalMantenimientos = $model->contarMantenimientosMensuales();
$valorTotal = $model->valorTotalPagadoMensual();
$ultimosServicios = $model->obtenerProximasRevisiones();
$prestadores = $model->obtenerPrestadores();
$tiposMantenimiento = $model->obtenerTiposMantenimiento();

// Layout principal
include '../views/components/layout/head.php';
?>

<body class="g-sidenav-show bg-gray-200 bg-image-curved">
    <?php include '../views/components/sidebar.php'; ?>

    <main class="main-content border-radius-lg">
        <?php include '../views/components/navbar.php'; ?>

        <!-- Contenido del módulo de mantenimiento -->
        <div class="container-fluid py-4">
            <?php include '../views/maintenanceView/index.php'; ?>
        </div>

        <?php include '../views/components/footer.php'; ?>
    </main>

    <?php include '../views/components/layout/scripts.php'; ?>
</body>

</html>
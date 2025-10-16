<?php
session_start();
if (!isset($_SESSION["rol_id"])) {
    header("Location: ../index.php");
    exit;
}

require_once '../models/inventoryModel.php';
    $model = new inventoryModel();

    
// Definir los elementos del inventario
// $elementos = [ 
//     'cabina_interna' => [
//         'KILOMETRAJE', 'GUANTERA', 'SILLAS', 'VIDRIOS', 'RADIO', 'CINTURON DE SEGURIDAD',
//         'ENCENDEDOR', 'COJINERIA', 'TAPETES', 'ESPEJO RETROVISOR', 'SISTEMA DE VIDRIOS',
//         'TECHO', 'PITO', 'COMANDO DE CONTROL (AIRE ACONDICIONADO)', 'FORRO BARRA CAMBIOS',
//         'COMANDOS DE CONTROL (TABLEROS)'
//     ],
//     'cabina_externa' => [
//         'FAROLAS', 'LUCES EXPLORADORAS', 'ANTENA', 'PERSIANA', 'PARABRISAS',
//         'MARCA-EMBLEMA VEHICULO', 'PLACA', 'ESPEJO DERECHO', 'ESPEJO IZQUIERDO',
//         'PUERTA DERECHA', 'GUARDABARRO DERECHO', 'PUERTA IZQUIERDA', 'GUARDABARRO IZQUIERDO'
//     ],
//     'furgon' => [
//         'LATERAL IZQUIERDO', 'LUCES LATERAL IZQUIERDO', 'PARAL TRASERO IZQUIERDO',
//         'LATERAL DERECHO', 'LUCES LATERAL DERECHO', 'PARAL TRASERO DERECHO',
//         'PUERTA FRONTAL', 'PARTE INTERNA FURGON', 'PUERTAS', 'CANDADO'
//     ],
//     'kit_carretera' => [
//         'GATO', 'EXTINTOR', 'CONOS', 'BARRAS COPA DE RUEDAS', 'BARRAS GATO',
//         'CASCO', 'GUANTES', 'BOTIQUIN'
//     ],
//     'otros' => [
//         'KIT DE HERRAMIENTA', 'TAPA DE COMBUSTIBLE', 'LLANTA DE REPUESTO'
//     ]
// ];

$elementos = $model->obtenerElementosInventario();
$placas = $model->obtenerPlacas(); 
$inventarios = $model->obtenerInventariosRegistrados();

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
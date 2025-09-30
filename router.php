<?php
// citycargo/router.php - PUNTO DE ENTRADA DEL NUEVO SISTEMA MVC

session_start();

// Validar sesión: si no hay rol, redirigir al login
if (!isset($_SESSION["rol_id"])) {
    header("Location: trafico/index.php");
    exit;
}

// Rutas base
define('BASE_PATH', __DIR__);
define('CONTROLLERS_PATH', BASE_PATH . '/controllers');
define('VIEWS_PATH', BASE_PATH . '/views');

// Obtener módulo desde URL (ej: ?module=maintenance)
$module = $_GET['module'] ?? '';

// Sanitizar
$module = preg_replace('/[^a-zA-Z0-9_-]/', '', $module);

// Ruta del controlador
$controllerFile = CONTROLLERS_PATH . "/{$module}Controller.php";

// Verificar si existe
if (file_exists($controllerFile)) {
    require_once $controllerFile;
} else {
    // Si no existe, redirigir a dashboard o error
    header("Location: trafico/index.php?msj=4");
    exit;
}
<?php
// index.php (en la raíz de CLIENTE_API)
session_start();

// Mostrar errores
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de controladores
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

// Mapeo de controladores - CORREGIDO para controller (singular)
$controllers = [
    'auth' => 'AuthController',
    'tokenapi' => 'TokenApiController'
];

// Verificar si el controlador existe
$controllerName = $controllers[$controller] ?? $controllers['auth'];
$controllerFile = __DIR__ . '/src/controller/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    die("Controlador no encontrado: $controllerName - Archivo: $controllerFile");
}

require_once $controllerFile;

if (!class_exists($controllerName)) {
    die("Clase $controllerName no encontrada");
}

$controllerInstance = new $controllerName();

if (!method_exists($controllerInstance, $action)) {
    die("Acción $action no encontrada en $controllerName");
}

$controllerInstance->$action();
?>
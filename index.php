<?php
// index.php
session_start();

// Mostrar errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración de controladores y acciones por defecto
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

// Mapeo de controladores
$controllers = [
    'auth' => 'AuthController',
    'tokenapi' => 'TokenApiController'
];

// Verificar si el controlador existe
$controllerName = $controllers[$controller] ?? $controllers['auth'];
$controllerFile = __DIR__ . '/controllers/' . $controllerName . '.php';

if (!file_exists($controllerFile)) {
    die("Controlador no encontrado: $controllerName - Archivo: $controllerFile");
}

// Incluir y ejecutar el controlador
require_once $controllerFile;

if (!class_exists($controllerName)) {
    die("Clase $controllerName no encontrada en el archivo");
}

$controllerInstance = new $controllerName();

if (!method_exists($controllerInstance, $action)) {
    die("Acción $action no encontrada en $controllerName");
}

// Ejecutar la acción
$controllerInstance->$action();
?>
<?php
session_start();

// Mostrar errores para debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Configuración específica para MAMP
define('BASE_URL', 'http://localhost:8888/cliente_api/');
define('BASE_PATH', __DIR__ . '/');

// Incluir archivos necesarios
require_once BASE_PATH . 'src/config/database.php';
require_once BASE_PATH . 'src/Model/User.php';
require_once BASE_PATH . 'src/Model/TokenApi.php';
require_once BASE_PATH . 'src/Model/ClienteApi.php';
require_once BASE_PATH . 'src/Controller/AuthController.php';
require_once BASE_PATH . 'src/Controller/TokenApiController.php';
require_once BASE_PATH . 'src/Controller/AdminController.php';

// Obtener la URL
$url = isset($_GET['url']) ? $_GET['url'] : 'auth/login';
$url = rtrim($url, '/');
$url_parts = explode('/', $url);

// Controlador por defecto
$controller_name = !empty($url_parts[0]) ? $url_parts[0] : 'auth';
$action = !empty($url_parts[1]) ? $url_parts[1] : 'login';

// Instanciar controlador
switch($controller_name) {
    case 'auth':
        $controller = new AuthController();
        break;
    case 'token':
        $controller = new TokenApiController();
        break;
    case 'admin':
        $controller = new AdminController();
        break;
    default:
        // Si no existe, redirigir al login
        header("Location: " . BASE_URL . "auth/login");
        exit;
}

// Ejecutar acción
if(method_exists($controller, $action)) {
    $controller->$action();
} else {
    // Si la acción no existe, mostrar error
    http_response_code(404);
    echo "Error 404: Página no encontrada";
    exit;
}
?>
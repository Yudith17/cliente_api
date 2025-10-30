<?php
// Iniciar sesión al principio del archivo
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ==================== CONFIGURACIÓN INICIAL ====================
define('BASE_URL', 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
define('BASE_PATH', __DIR__);

// ==================== RUTAS PÚBLICAS (no requieren autenticación) ====================
$publicRoutes = [
    'api/client/search',
    'api/client/view',
    'cliente_api',
    'auth/login'
];

// Verificar si es una ruta pública
$isPublicRoute = false;
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    foreach ($publicRoutes as $route) {
        if (strpos($url, $route) === 0) {
            $isPublicRoute = true;
            break;
        }
    }
}

// Verificar parámetros actuales para determinar si es login
$currentController = $_GET['controller'] ?? '';
$currentAction = $_GET['action'] ?? '';
$isLoginPage = ($currentController === 'auth' && $currentAction === 'login');

// ==================== VERIFICACIÓN DE AUTENTICACIÓN ====================
$isAuthenticated = isset($_SESSION['user']) || isset($_SESSION['usuario']) || isset($_SESSION['admin_id']);

// LÓGICA DE REDIRECCIÓN SIMPLIFICADA Y SEGURA
if (!$isAuthenticated && !$isPublicRoute && !$isLoginPage) {
    // No autenticado, no es ruta pública y no está en login → ir al login
    header("Location: index.php?controller=auth&action=login");
    exit;
}

if ($isAuthenticated && $isLoginPage) {
    // Autenticado y tratando de acceder al login → ir al dashboard
    header("Location: index.php?controller=clientapi&action=index");
    exit;
}

// ==================== ENRUTAMIENTO MVC ====================
// Procesar rutas de API
if (isset($_GET['url'])) {
    $url = $_GET['url'];
    
    if ($url === 'api/client/search') {
        $_GET['controller'] = 'clientapi';
        $_GET['action'] = 'apiSearch';
    } 
    elseif (preg_match('#^api/client/view/(\d+)$#', $url, $matches)) {
        $_GET['controller'] = 'clientapi';
        $_GET['action'] = 'apiView';
        $_GET['id'] = $matches[1];
    }
    elseif ($url === 'cliente_api') {
        $_GET['controller'] = 'clientapi';
        $_GET['action'] = 'cliente_api';
    }
}

// **IMPORTANTE**: Usar controladores que SÍ existen
$controller = $_GET['controller'] ?? 'auth';    // Por defecto: auth (que existe)
$action = $_GET['action'] ?? 'login';           // Por defecto: login (que existe)

// ==================== CARGAR CONTROLADOR ====================
$controllerClass = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/controller/' . $controllerClass . '.php';

// Verificar si el archivo del controlador existe
if (!file_exists($controllerFile)) {
    // Si el controlador no existe, redirigir al login de forma segura
    http_response_code(404);
    header("Location: index.php?controller=auth&action=login");
    exit;
}

require_once $controllerFile;

if (!class_exists($controllerClass)) {
    http_response_code(500);
    die("Error: La clase $controllerClass no existe en el archivo.");
}

$ctrl = new $controllerClass();

if (!method_exists($ctrl, $action)) {
    http_response_code(404);
    die("Error: El método $action no existe en $controllerClass.");
}

// Ejecutar la acción del controlador
$ctrl->$action();
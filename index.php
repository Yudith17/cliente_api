<?php
// index.php (en la raíz del proyecto)

// Configuración básica
session_start();

// Autocargar controladores
function autoloadControllers($className) {
    $file = __DIR__ . '/src/controller/' . $className . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
}

spl_autoload_register('autoloadControllers');

// Obtener controlador y acción
$controller = $_GET['controller'] ?? 'auth';
$action = $_GET['action'] ?? 'login';

// Validar y ejecutar
$controllerClass = ucfirst($controller) . 'Controller';
$controllerFile = __DIR__ . '/src/controller/' . $controllerClass . '.php';

if (file_exists($controllerFile)) {
    require_once $controllerFile;
    
    if (class_exists($controllerClass)) {
        $app = new $controllerClass();
        
        if (method_exists($app, $action)) {
            $app->$action();
        } else {
            die("Error: La acción '$action' no existe en el controlador $controllerClass");
        }
    } else {
        die("Error: La clase '$controllerClass' no existe");
    }
} else {
    die("Error: El controlador '$controller' no existe");
}
?>
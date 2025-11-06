<?php
// src/controller/AuthController.php

require_once __DIR__ . '/../Model/User.php';

class AuthController {
    public function login() {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Si ya está autenticado, redirigir al controlador de tokens
        if (isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=tokenapi&action=index");
            exit;
        }

        $error = ''; // Inicializar variable de error

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validar que no estén vacíos
            if (empty($username) || empty($password)) {
                $error = "Por favor, completa todos los campos.";
            } else {
                $userModel = new User();
                $user = $userModel->login($username, $password);

                if ($user) {
                    // Establecer variables de sesión
                    $_SESSION['user'] = $user;
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['username'] = $user['username'];
                    
                    // Redirigir al index de tokens
                    header("Location: index.php?controller=tokenapi&action=index");
                    exit;
                } else {
                    $error = "Usuario o contraseña incorrectos.";
                }
            }
        }

        // RUTA CORREGIDA - usa views (no ../views)
        $viewPath = __DIR__ . '/../views/auth/login.php';
        
        if (!file_exists($viewPath)) {
            die("Error: No se encuentra la vista de login. Ruta: $viewPath");
        }
        
        require $viewPath;
    }

    public function logout() {
        // Iniciar sesión si no está iniciada
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Limpiar sesión completamente
        $_SESSION = array();
        
        // Destruir cookie de sesión
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        // Redirigir al login
        header("Location: index.php?controller=auth&action=login");
        exit;
    }
}
?>
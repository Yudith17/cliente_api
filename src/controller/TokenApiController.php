<?php
// src/controller/TokenApiController.php

require_once __DIR__ . '/../Model/TokenApi.php';

class TokenApiController {
    private $tokenModel;

    public function __construct() {
        $this->tokenModel = new TokenApi();
    }

    public function index() {
        // Verificar sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $tokens = $this->tokenModel->getByUserId($_SESSION['user_id']);
        
        // RUTA CORREGIDA
        $viewPath =  dirname(__DIR__, 2) . '/views/token_api/index.php';
        
        if (!file_exists($viewPath)) {
            die("Vista no encontrada: $viewPath");
        }
        
        require $viewPath;
    }

    public function create() {
        // Verificar sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'] ?? '';
            $expires_days = $_POST['expires_days'] ?? 30;

            $token = $this->tokenModel->create($_SESSION['user_id'], $name, $expires_days);
            
            if ($token) {
                $_SESSION['success'] = 'Token creado exitosamente';
                $_SESSION['new_token'] = $token;
                header("Location: index.php?controller=tokenapi&action=index");
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear el token';
            }
        }

        $viewPath = dirname(__DIR__, 2) .'/views/token_api/create.php';
        if (!file_exists($viewPath)) {
            die("Vista no encontrada: $viewPath");
        }
        require $viewPath;
    }

    public function view() {
        // Verificar sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $id = $_GET['id'] ?? 0;
        $token = $this->tokenModel->getById($id, $_SESSION['user_id']);

        if (!$token) {
            $_SESSION['error'] = 'Token no encontrado';
            header("Location: index.php?controller=tokenapi&action=index");
            exit;
        }

        $viewPath = dirname(__DIR__, 2) . '/views/token_api/view.php';
        if (!file_exists($viewPath)) {
            die("Vista no encontrada: $viewPath");
        }
        require $viewPath;
    }

    public function deactivate() {
        // Verificar sesión
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id'])) {
            header("HTTP/1.1 401 Unauthorized");
            exit;
        }

        $id = $_GET['id'] ?? 0;
        if ($this->tokenModel->deactivate($id, $_SESSION['user_id'])) {
            $_SESSION['success'] = 'Token desactivado exitosamente';
        } else {
            $_SESSION['error'] = 'Error al desactivar el token';
        }

        header("Location: index.php?controller=tokenapi&action=index");
        exit;
    }
}
?>
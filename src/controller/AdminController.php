<?php
// app/controllers/AdminController.php
require_once __DIR__ . '/../models/Admin.php';

class AdminController {
    private $model;
    public function __construct() {
        $this->model = new Admin();
    }

    public function loginForm() {
        require __DIR__ . '/../views/admin/login.php';
    }

    public function login($post) {
        session_start();
        $username = $post['username'] ?? '';
        $password = $post['password'] ?? '';
        $admin = $this->model->findByUsername($username);
        if ($admin && password_verify($password, $admin['password_hash'])) {
            $_SESSION['admin_id'] = $admin['id'];
            $_SESSION['admin_name'] = $admin['name'];
            header('Location: /?route=hotel:index');
            exit;
        } else {
            $_SESSION['flash'] = 'Usuario o contraseña inválidos';
            header('Location: /?route=admin:login');
            exit;
        }
    }

    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        header('Location: /?route=hotel:index');
    }
}

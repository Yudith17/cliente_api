<?php
class AuthController {
    private $db;
    private $user;

    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        require_once __DIR__ . '/../Model/User.php';
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->user = new User($this->db);
    }

    public function logout() {
        session_destroy();
        // Redirigir al login después del logout
        header("Location: ../../views/auth/login.php");
        exit;
    }
}

// Procesar logout si se accede directamente
if(isset($_GET['action']) && $_GET['action'] == 'logout') {
    $authController = new AuthController();
    $authController->logout();
}
?>
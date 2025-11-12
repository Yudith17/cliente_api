<?php
class AdminController {
    private $db;

    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $database = new Database();
        $this->db = $database->getConnection();
    }

    public function dashboard() {
        // Verificar si está logueado
        if(!isset($_SESSION['user_id'])) {
            header("Location: ../auth/login.php");
            exit;
        }
        
        // Aquí puedes agregar la funcionalidad del dashboard de admin
        echo "Dashboard de Administrador";
    }
}
?>
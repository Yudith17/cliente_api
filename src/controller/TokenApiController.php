<?php
class TokenApiController {
    private $db;
    private $tokenApi;

    public function __construct() {
        // Incluir las clases necesarias con rutas correctas
        require_once __DIR__ . '/../config/database.php';
        require_once __DIR__ . '/../Model/TokenApi.php';
        
        $database = new Database();
        $this->db = $database->getConnection();
        $this->tokenApi = new TokenApi($this->db);
    }

    public function create() {
        if($_POST) {
            $this->tokenApi->user_id = $_SESSION['user_id'];
            $token = $this->tokenApi->create();
            
            if($token) {
                $_SESSION['success'] = "Token creado exitosamente: " . $token;
                header("Location: index.php");
                exit;
            } else {
                $_SESSION['error'] = "Error al crear el token";
            }
        }
    }
}
?>
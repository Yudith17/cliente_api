<?php
class TokenApi {
    private $conn;
    private $table_name = "tokens_api";

    public $id;
    public $token;
    public $user_id;
    public $name;
    public $created_at;
    public $expires_at;
    public $is_active;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        $query = "INSERT INTO " . $this->table_name . " 
                 SET token=:token, user_id=:user_id, name=:name, 
                     created_at=NOW(), expires_at=:expires_at, is_active=1";
        
        $stmt = $this->conn->prepare($query);

        // Generar token único
        $this->token = bin2hex(random_bytes(32));
        $this->expires_at = date('Y-m-d H:i:s', strtotime('+1 year'));
        $this->name = $_POST['name'] ?? 'Token ' . date('Y-m-d H:i:s');

        $stmt->bindParam(":token", $this->token);
        $stmt->bindParam(":user_id", $this->user_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":expires_at", $this->expires_at);

        if($stmt->execute()) {
            return $this->token;
        }
        return false;
    }

    public function getAllByUser($user_id) {
        $query = "SELECT * FROM " . $this->table_name . " 
                 WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $user_id);
        $stmt->execute();
        return $stmt;
    }
}
?>
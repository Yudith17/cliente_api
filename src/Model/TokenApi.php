<?php
// src/Model/TokenApi.php

class TokenApi {
    private $db;
    private $conn;

    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Todos los métodos deben usar $this->conn en lugar de $this->db
    
    public function getByUserId($user_id) {
        $stmt = $this->conn->prepare("SELECT * FROM tokens_api WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM tokens_api WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($user_id, $token, $name, $expires_at) {
        $stmt = $this->conn->prepare("INSERT INTO tokens_api (user_id, token, name, created_at, expires_at) VALUES (?, ?, ?, NOW(), ?)");
        return $stmt->execute([$user_id, $token, $name, $expires_at]);
    }

    public function update($id, $name, $expires_at) {
        $stmt = $this->conn->prepare("UPDATE tokens_api SET name = ?, expires_at = ? WHERE id = ?");
        return $stmt->execute([$name, $expires_at, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM tokens_api WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function deactivate($id) {
        $stmt = $this->conn->prepare("UPDATE tokens_api SET is_active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>
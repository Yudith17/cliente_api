<?php
// src/Model/TokenApi.php

require_once __DIR__ . '/../config/database.php';

class TokenApi {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($user_id, $name, $expires_days = 30) {
        $token = bin2hex(random_bytes(32));
        $created_at = date('Y-m-d H:i:s');
        $expires_at = date('Y-m-d H:i:s', strtotime("+$expires_days days"));
        
        $query = "INSERT INTO tokens_api (user_id, token, name, created_at, expires_at, is_active) 
                  VALUES (?, ?, ?, ?, ?, 1)";
        
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([$user_id, $token, $name, $created_at, $expires_at]);
        
        return $result ? $token : false;
    }

    public function getByUserId($user_id) {
        $query = "SELECT * FROM tokens_api WHERE user_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function getByToken($token) {
        $query = "SELECT * FROM tokens_api WHERE token = ? AND is_active = 1 AND expires_at > NOW()";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$token]);
        return $stmt->fetch();
    }

    public function deactivate($token_id, $user_id) {
        $query = "UPDATE tokens_api SET is_active = 0 WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$token_id, $user_id]);
    }

    public function getById($token_id, $user_id) {
        $query = "SELECT * FROM tokens_api WHERE id = ? AND user_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$token_id, $user_id]);
        return $stmt->fetch();
    }
}
?>
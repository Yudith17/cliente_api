<?php
// src/Model/User.php

class User {
    private $db;
    private $conn;

    public function __construct() {
        require_once __DIR__ . '/../config/database.php';
        $this->db = new Database();
        $this->conn = $this->db->getConnection(); // Usar getConnection()
    }

    public function login($username, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT id, username, role FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
}
?>
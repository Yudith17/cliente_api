<?php
// src/Model/User.php

require_once __DIR__ . '/../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function login($username, $password) {
        try {
            $query = "SELECT id, username, email, password FROM users WHERE username = ? AND is_active = 1";
            $stmt = $this->db->prepare($query);
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Remover la contraseña del array antes de devolver
                unset($user['password']);
                return $user;
            }

            return false;
        } catch (Exception $e) {
            error_log("Error en login: " . $e->getMessage());
            return false;
        }
    }

    public function getUserById($id) {
        $query = "SELECT id, username, email, created_at FROM users WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($username, $email, $password) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        
        $query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($query);
        
        return $stmt->execute([$username, $email, $hashedPassword]);
    }

    public function userExists($username) {
        $query = "SELECT COUNT(*) FROM users WHERE username = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$username]);
        return $stmt->fetchColumn() > 0;
    }
}
?>
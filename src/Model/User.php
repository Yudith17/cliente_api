<?php
require_once __DIR__ . '/../../config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance(); // obtiene PDO
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // ⚠️ Comparación con sha256
        if ($user && hash('sha256', $password) === $user['password']) {
            return $user;
        }
        return false;
    }
}

<?php
// src/config/database.php

class Database {
    private static $instance = null;
    private $pdo;

    private function __construct() {
        try {
            $this->pdo = new PDO(
                "mysql:host=localhost;dbname=cliente_api;charset=utf8mb4", 
                "root", 
                "root", // Cambia si tu password es diferente
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false
                ]
            );
        } catch (PDOException $e) {
            error_log("Error de conexión: " . $e->getMessage());
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance->pdo;
    }

    public static function getConnection() {
        return self::getInstance();
    }

    private function __clone() { }
    public function __wakeup() { }
}

// Crear las tablas necesarias si no existen
function createTablesIfNotExist() {
    $pdo = Database::getInstance();
    
    // Primero crear la tabla de usuarios
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) UNIQUE NOT NULL,
            email VARCHAR(100) UNIQUE NOT NULL,
            password VARCHAR(255) NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            is_active TINYINT DEFAULT 1
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // Luego crear la tabla de tokens_api SIN la foreign key primero
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS tokens_api (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            token VARCHAR(64) UNIQUE NOT NULL,
            name VARCHAR(255) NOT NULL,
            created_at DATETIME NOT NULL,
            expires_at DATETIME NOT NULL,
            is_active TINYINT DEFAULT 1,
            INDEX idx_user_id (user_id),
            INDEX idx_token (token),
            INDEX idx_expires (expires_at)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // Ahora agregar la foreign key si no existe
    try {
        $pdo->exec("
            ALTER TABLE tokens_api 
            ADD CONSTRAINT fk_tokens_api_user_id 
            FOREIGN KEY (user_id) REFERENCES users(id) 
            ON DELETE CASCADE
        ");
    } catch (PDOException $e) {
        // La foreign key probablemente ya existe, podemos ignorar el error
        error_log("Foreign key posiblemente ya existe: " . $e->getMessage());
    }

    // Insertar usuario demo si no existe
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = 'admin'");
    $stmt->execute();
    if ($stmt->fetchColumn() == 0) {
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)")
            ->execute(['admin', 'admin@example.com', $hashedPassword]);
    }
}

// Ejecutar la creación de tablas
createTablesIfNotExist();
?>
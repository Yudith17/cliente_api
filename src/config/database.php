<?php
class Database {
    // Configuración para MAMP
    private $host = "localhost";
    private $db_name = "cliente_api";
    private $username = "root";
    private $password = "root";  // Contraseña por defecto de MAMP
    private $port = "8889";      // Puerto MySQL por defecto de MAMP
    public $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            // Para MAMP, necesitas especificar el puerto en el DSN
            $dsn = "mysql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name . ";charset=utf8mb4";
            
            $this->conn = new PDO($dsn, $this->username, $this->password);
            
            // Configurar PDO para que lance excepciones en errores
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch(PDOException $exception) {
            // Mostrar error detallado
            echo "Error de conexión a la base de datos:<br>";
            echo "Mensaje: " . $exception->getMessage() . "<br>";
            echo "Código: " . $exception->getCode() . "<br>";
            echo "Archivo: " . $exception->getFile() . "<br>";
            echo "Línea: " . $exception->getLine() . "<br>";
            
            // También puedes loggear el error en un archivo
            error_log("Error de conexión BD: " . $exception->getMessage());
        }
        return $this->conn;
    }

    // Método para verificar la conexión
    public function testConnection() {
        if ($this->getConnection()) {
            echo "✅ Conexión a la base de datos exitosa<br>";
            echo "📊 Base de datos: " . $this->db_name . "<br>";
            echo "🔌 Host: " . $this->host . ":" . $this->port . "<br>";
            echo "👤 Usuario: " . $this->username . "<br>";
            return true;
        } else {
            echo "❌ No se pudo conectar a la base de datos<br>";
            return false;
        }
    }
}
?>
<?php
class TokenApi {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
    }

    public function create($data) {
        $sql = "INSERT INTO Token (Id_cliente_Api, Token, Estado) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$data['Id_cliente_Api'], $data['Token'], $data['Estado']]);
        return $this->db->lastInsertId();
    }

    public function getActiveToken($token) {
        $sql = "SELECT t.*, c.razon_social, c.estado as cliente_estado 
                FROM Token t 
                JOIN Cliente_Api c ON t.Id_cliente_Api = c.id 
                WHERE t.Token = ? AND t.Estado = 1 AND c.estado = 'activo'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function logRequest($tokenId, $tipo) {
        $sql = "INSERT INTO Count_Request (Id_Token, Tipo, fecha) VALUES (?, ?, NOW())";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$tokenId, $tipo]);
    }
}
?>
<?php
class TokenApiController {
    private $db;
    private $clienteApiModel;
    private $tokenApiModel;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->clienteApiModel = new ClienteApi();
        $this->tokenApiModel = new TokenApi();
    }

    // Generar nuevo token
    public function generateToken($clienteApiId) {
        // Verificar si el cliente existe y está activo
        $cliente = $this->clienteApiModel->getById($clienteApiId);
        
        if (!$cliente || $cliente['estado'] != 'activo') {
            return ['success' => false, 'message' => 'Cliente no válido o inactivo'];
        }

        // Generar token único
        $token = $this->generateUniqueToken();
        
        // Guardar token en la base de datos
        $tokenId = $this->tokenApiModel->create([
            'Id_cliente_Api' => $clienteApiId,
            'Token' => $token,
            'Estado' => 1
        ]);

        if ($tokenId) {
            return [
                'success' => true,
                'token' => $token,
                'message' => 'Token generado exitosamente'
            ];
        }

        return ['success' => false, 'message' => 'Error al generar token'];
    }

    // Generar token único
    private function generateUniqueToken() {
        return uniqid() . '_' . bin2hex(random_bytes(16));
    }

    // Validar token
    public function validateToken($token) {
        return $this->tokenApiModel->getActiveToken($token);
    }

    // Registrar uso del token
    public function logRequest($tokenId, $tipo) {
        $this->tokenApiModel->logRequest($tokenId, $tipo);
    }
}
?>
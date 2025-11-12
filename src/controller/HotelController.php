<?php
require_once __DIR__ . '/../model/Hotel.php';

class HotelController {
    private $db;
    private $tokenController;
    private $hotelModel;

    public function __construct() {
        $this->db = Database::getConnection();
        $this->tokenController = new TokenApiController();
        $this->hotelModel = new Hotel();
    }

    public function getHotels($token, $filters = []) {
        error_log("Validando token: " . $token);
        
        // Validar token
        $tokenData = $this->tokenController->validateToken($token);
        
        if (!$tokenData) {
            error_log("Token inválido: " . $token);
            return [
                'success' => false,
                'message' => 'Token inválido o expirado',
                'data' => []
            ];
        }

        error_log("Token válido para cliente: " . $tokenData['razon_social']);

        // Registrar la solicitud
        $this->tokenController->logRequest($tokenData['id'], 'GET /hotels');

        try {
            $hotels = $this->hotelModel->getAll($filters);
            
            error_log("Hoteles encontrados: " . count($hotels));

            return [
                'success' => true,
                'message' => 'Hoteles obtenidos exitosamente',
                'data' => $hotels,
                'count' => count($hotels)
            ];

        } catch (PDOException $e) {
            error_log("Error en getHotels: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error al obtener hoteles: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function getHotelById($token, $hotelId) {
        // Validar token
        $tokenData = $this->tokenController->validateToken($token);
        
        if (!$tokenData) {
            return [
                'success' => false,
                'message' => 'Token inválido o expirado'
            ];
        }

        // Registrar la solicitud
        $this->tokenController->logRequest($tokenData['id'], 'GET /hotels/' . $hotelId);

        try {
            $hotel = $this->hotelModel->getById($hotelId);

            if ($hotel) {
                return [
                    'success' => true,
                    'message' => 'Hotel encontrado',
                    'data' => $hotel
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Hotel no encontrado'
                ];
            }

        } catch (PDOException $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener hotel: ' . $e->getMessage()
            ];
        }
    }
}
?>
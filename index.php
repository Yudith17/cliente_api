<?php
require_once '/../src/config/database.php';
require_once '../src/controller/TokenApiController.php';
require_once '../src/controller/HotelController.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Obtener método de la solicitud
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api/', '', $path);

// Procesar solicitud
switch ($method) {
    case 'POST':
        if ($path == 'generate-token') {
            // Generar nuevo token
            $input = json_decode(file_get_contents('php://input'), true);
            $clienteId = $input['cliente_id'] ?? null;
            
            $tokenController = new TokenApiController();
            $result = $tokenController->generateToken($clienteId);
            
            echo json_encode($result);
        }
        break;

    case 'GET':
        if (strpos($path, 'hotels') !== false) {
            // Obtener token del header
            $headers = getallheaders();
            $token = isset($headers['Authorization']) ? 
                     str_replace('Bearer ', '', $headers['Authorization']) : null;

            if (!$token) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Token requerido']);
                exit;
            }

            $hotelController = new HotelController();
            
            if ($path == 'hotels') {
                // Lista de hoteles con filtros opcionales
                $filters = [
                    'category' => $_GET['category'] ?? null,
                    'district' => $_GET['district'] ?? null,
                    'department' => $_GET['department'] ?? null
                ];
                
                $result = $hotelController->getHotels($token, $filters);
                echo json_encode($result);
                
            } else if (preg_match('/hotels\/(\d+)/', $path, $matches)) {
                // Hotel específico por ID
                $hotelId = $matches[1];
                $result = $hotelController->getHotelById($token, $hotelId);
                echo json_encode($result);
            }
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Método no permitido']);
}
?>
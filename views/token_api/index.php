<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// VERIFICAR SI ESTÁ LOGUEADO - Si no, redirigir al login
if(!isset($_SESSION['user_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

// Incluir modelos con rutas correctas
require_once __DIR__ . '/../../src/config/database.php';
require_once __DIR__ . '/../../src/Model/TokenApi.php';

$database = new Database();
$db = $database->getConnection();
$tokenApi = new TokenApi($db);

// Obtener tokens del usuario
$tokens = $tokenApi->getAllByUser($_SESSION['user_id']);

// Procesar creación de token
if(isset($_POST['crear_token'])) {
    $tokenApi->user_id = $_SESSION['user_id'];
    $nuevo_token = $tokenApi->create();
    
    if($nuevo_token) {
        $mensaje_exito = "Token creado exitosamente: " . $nuevo_token;
        // Recargar tokens
        $tokens = $tokenApi->getAllByUser($_SESSION['user_id']);
    } else {
        $mensaje_error = "Error al crear el token";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tokens API - Sistema Hoteles</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <span class="navbar-brand">🏨 Sistema Hoteles Huanta</span>
            <div class="navbar-nav ms-auto">
                <span class="navbar-text me-3">Hola, <?php echo $_SESSION['username']; ?></span>
                <a class="nav-link" href="../../src/Controller/AuthController.php?action=logout">Cerrar Sesión</a>
            </div>
        </div>
    </nav>

    <div class="container mt-4">
        <!-- Botones de navegación rápida -->
        <div class="row mb-4">
            <div class="col-md-4 mb-2">
                <a href="../clientes/index.php" class="btn btn-outline-primary w-100">
                    <i class="fas fa-users me-2"></i>
                    👥 Clientes Registrados
                </a>
            </div>
            <div class="col-md-4 mb-2">
                <a href="../hoteles/index.php" class="btn btn-outline-success w-100">
                    <i class="fas fa-hotel me-2"></i>
                    🏨 Hoteles Registrados
                </a>
            </div>
            <div class="col-md-4 mb-2">
                <a href="../reservas/index.php" class="btn btn-outline-info w-100">
                    <i class="fas fa-calendar-check me-2"></i>
                    📅 Reservas
                </a>
            </div>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2><i class="fas fa-key me-2"></i>🔑 Mis Tokens API</h2>
            <div>
                <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#modalToken">
                    <i class="fas fa-plus-circle me-1"></i>
                    ➕ Generar Nuevo Token
                </button>
            </div>
        </div>

        <?php if(isset($mensaje_exito)): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle me-2"></i>
                <?php echo $mensaje_exito; ?>
            </div>
        <?php endif; ?>

        <?php if(isset($mensaje_error)): ?>
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                <?php echo $mensaje_error; ?>
            </div>
        <?php endif; ?>

        <div class="card">
            <div class="card-header bg-light">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>
                    Lista de Tokens Generados
                </h5>
            </div>
            <div class="card-body">
                <?php if($tokens->rowCount() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Token</th>
                                    <th>Creado</th>
                                    <th>Expira</th>
                                    <th>Estado</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $tokens->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td>
                                        <i class="fas fa-tag me-1 text-muted"></i>
                                        <?php echo htmlspecialchars($row['name']); ?>
                                    </td>
                                    <td>
                                        <code class="bg-light p-1 rounded" style="font-size: 0.8em;">
                                            <?php echo substr($row['token'], 0, 20) . '...'; ?>
                                        </code>
                                        <button class="btn btn-sm btn-outline-secondary ms-1" 
                                                onclick="copiarToken('<?php echo $row['token']; ?>')"
                                                title="Copiar token completo">
                                            <i class="fas fa-copy"></i>
                                        </button>
                                    </td>
                                    <td>
                                        <i class="fas fa-calendar-plus me-1 text-muted"></i>
                                        <?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?>
                                    </td>
                                    <td>
                                        <i class="fas fa-clock me-1 text-muted"></i>
                                        <?php echo date('d/m/Y H:i', strtotime($row['expires_at'])); ?>
                                    </td>
                                    <td>
                                        <?php if($row['is_active'] == 1): ?>
                                            <span class="badge bg-success">
                                                <i class="fas fa-check me-1"></i>Activo
                                            </span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">
                                                <i class="fas fa-times me-1"></i>Inactivo
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <button class="btn btn-outline-primary" 
                                                    onclick="verToken('<?php echo $row['token']; ?>', '<?php echo htmlspecialchars($row['name']); ?>')"
                                                    title="Ver token completo">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                            <button class="btn btn-outline-danger" 
                                                    onclick="confirmarEliminacion(<?php echo $row['id']; ?>)"
                                                    title="Eliminar token">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-key fa-3x text-muted mb-3"></i>
                        <h5>No hay tokens generados</h5>
                        <p class="text-muted">Genera tu primer token para empezar a usar la API</p>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalToken">
                            <i class="fas fa-plus-circle me-1"></i>
                            Generar Primer Token
                        </button>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Modal para crear token -->
    <div class="modal fade" id="modalToken" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-key me-2"></i>
                        Generar Nuevo Token
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST">
                    <div class="modal-body">
                        <input type="hidden" name="crear_token" value="1">
                        <div class="mb-3">
                            <label class="form-label">
                                <i class="fas fa-tag me-1"></i>
                                Nombre del Token
                            </label>
                            <input type="text" name="name" class="form-control" 
                                   placeholder="Ej: Token para API de Hoteles" required>
                            <div class="form-text">Asigna un nombre descriptivo para identificar este token.</div>
                        </div>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <small>El token expirará en 1 año y será único. Guárdalo en un lugar seguro.</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="fas fa-times me-1"></i>
                            Cancelar
                        </button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-key me-1"></i>
                            Generar Token
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal para ver token completo -->
    <div class="modal fade" id="modalVerToken" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Token: <span id="tokenNombre"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Token completo:</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="tokenCompleto" readonly>
                            <button class="btn btn-outline-secondary" type="button" onclick="copiarTokenCompleto()">
                                <i class="fas fa-copy"></i>
                            </button>
                        </div>
                    </div>
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <small>Este token proporciona acceso a la API. No lo compartas con nadie.</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function copiarToken(token) {
            navigator.clipboard.writeText(token).then(function() {
                alert('Token copiado al portapapeles');
            }).catch(function(err) {
                console.error('Error al copiar: ', err);
            });
        }

        function verToken(token, nombre) {
            document.getElementById('tokenNombre').textContent = nombre;
            document.getElementById('tokenCompleto').value = token;
            var modal = new bootstrap.Modal(document.getElementById('modalVerToken'));
            modal.show();
        }

        function copiarTokenCompleto() {
            var tokenInput = document.getElementById('tokenCompleto');
            tokenInput.select();
            document.execCommand('copy');
            alert('Token copiado al portapapeles');
        }

        function confirmarEliminacion(tokenId) {
            if(confirm('¿Estás seguro de que deseas eliminar este token?')) {
                // Aquí puedes agregar la lógica para eliminar el token
                alert('Funcionalidad de eliminación pendiente para el token ID: ' + tokenId);
            }
        }

        // Tooltips de Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    </script>
</body>
</html>
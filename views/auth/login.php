<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Si ya está logueado, redirigir directamente al index principal
if(isset($_SESSION['user_id'])) {
    header("Location: ../../index.php");
    exit;
}

// Procesar login si se envió el formulario
$error = '';
if($_POST) {
    require_once __DIR__ . '/../../src/config/database.php';
    require_once __DIR__ . '/../../src/Model/User.php';
    
    $database = new Database();
    $db = $database->getConnection();
    $user = new User($db);
    
    $user->username = $_POST['username'];
    $user->password = $_POST['password'];
    
    if($user->login()) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['role'] = $user->role;
        
        // Redirigir al INDEX.PRINCIPAL después del login exitoso
        header("Location: ../../index.php");
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistema Hoteles Huanta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            width: 100%;
            max-width: 400px;
        }
        .login-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(10px);
        }
        .card-header {
            background: linear-gradient(to right, #2c3e50, #3498db);
            color: white;
            text-align: center;
            padding: 25px 20px;
            border-bottom: none;
            border-radius: 15px 15px 0 0 !important;
        }
        .hotel-icon {
            font-size: 48px;
            text-align: center;
            display: block;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-card">
            <div class="card">
                <div class="card-header">
                    <div class="hotel-icon">🏨</div>
                    <h4 class="mb-0">Sistema Hoteles Huanta</h4>
                    <small>Iniciar Sesión</small>
                </div>
                <div class="card-body p-4">
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Usuario</label>
                            <input type="text" name="username" class="form-control" value="admin" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control" value="admin123" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <strong>Ingresar al Sistema</strong>
                        </button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <small class="text-muted">
                            Credenciales: admin / admin123
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generar Nuevo Token</title>
    <style>
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input, select { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 5px; }
        .btn { padding: 10px 20px; background: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Generar Nuevo Token API</h1>
        
        <form method="POST">
            <div class="form-group">
                <label for="name">Nombre del Token:</label>
                <input type="text" id="name" name="name" required 
                       placeholder="Ej: Mi Aplicación Móvil">
            </div>
            
            <div class="form-group">
                <label for="expires_days">Días de validez:</label>
                <select id="expires_days" name="expires_days">
                    <option value="7">7 días</option>
                    <option value="30" selected>30 días</option>
                    <option value="90">90 días</option>
                    <option value="365">1 año</option>
                </select>
            </div>
            
            <button type="submit" class="btn">Generar Token</button>
            <a href="/token_api" style="margin-left: 10px;">Cancelar</a>
        </form>
    </div>
</body>
</html>
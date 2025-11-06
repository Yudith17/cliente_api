<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php?controller=auth&action=login");
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
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block; }
        .btn-back { background: #6c757d; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #333; }
        input, select { width: 100%; padding: 10px; border: 2px solid #ddd; border-radius: 5px; font-size: 1rem; }
        input:focus, select:focus { outline: none; border-color: #007bff; }
        .btn-submit { background: #28a745; color: white; border: none; padding: 12px 24px; border-radius: 5px; cursor: pointer; font-size: 1rem; }
        .btn-submit:hover { background: #218838; }
        .form-actions { display: flex; gap: 10px; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Generar Nuevo Token API</h1>
            <a href="index.php?controller=tokenapi&action=index" class="btn btn-back">Volver al Listado</a>
        </div>
        
        <form method="POST" action="index.php?controller=tokenapi&action=create">
            <div class="form-group">
                <label for="name">Nombre del Token:</label>
                <input type="text" id="name" name="name" required 
                       placeholder="Ej: Mi Aplicación Móvil, API de Producción, etc.">
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
            
            <div class="form-actions">
                <button type="submit" class="btn-submit">Generar Token</button>
                <a href="index.php?controller=tokenapi&action=index" class="btn btn-back">Cancelar</a>
            </div>
        </form>
    </div>
</body>
</html>
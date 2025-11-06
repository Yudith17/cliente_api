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
    <title>Detalles del Token</title>
    <style>
        .container { max-width: 800px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; display: inline-block; }
        .btn-back { background: #6c757d; }
        .token-info { background: #f8f9fa; border: 1px solid #e9ecef; border-radius: 5px; padding: 20px; }
        .info-row { display: flex; margin-bottom: 15px; padding-bottom: 15px; border-bottom: 1px solid #dee2e6; }
        .info-label { font-weight: bold; width: 150px; color: #495057; }
        .info-value { flex: 1; }
        .token-value { font-family: monospace; background: white; padding: 10px; border-radius: 3px; word-break: break-all; }
        .status-active { color: green; font-weight: bold; }
        .status-inactive { color: red; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Detalles del Token</h1>
            <a href="index.php?controller=tokenapi&action=index" class="btn btn-back">Volver al Listado</a>
        </div>

        <?php if (isset($token) && $token): ?>
        <div class="token-info">
            <div class="info-row">
                <div class="info-label">Nombre:</div>
                <div class="info-value"><?= htmlspecialchars($token['name']) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Token:</div>
                <div class="info-value token-value" title="<?= $token['token'] ?>">
                    <?= substr($token['token'], 0, 15) ?>...<?= substr($token['token'], -15) ?>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Creación:</div>
                <div class="info-value"><?= date('d/m/Y H:i:s', strtotime($token['created_at'])) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Fecha de Expiración:</div>
                <div class="info-value"><?= date('d/m/Y H:i:s', strtotime($token['expires_at'])) ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Estado:</div>
                <div class="info-value">
                    <span class="status-<?= $token['is_active'] ? 'active' : 'inactive' ?>">
                        <?= $token['is_active'] ? 'Activo' : 'Inactivo' ?>
                    </span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-label">ID:</div>
                <div class="info-value"><?= $token['id'] ?></div>
            </div>
        </div>

        <?php if ($token['is_active']): ?>
        <div style="margin-top: 20px;">
            <a href="index.php?controller=tokenapi&action=deactivate&id=<?= $token['id'] ?>" 
               class="btn" style="background: #dc3545;"
               onclick="return confirm('¿Estás seguro de desactivar este token?')">
                Desactivar Token
            </a>
        </div>
        <?php endif; ?>

        <?php else: ?>
            <p>Token no encontrado.</p>
        <?php endif; ?>
    </div>
</body>
</html>
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
    <title>Mis Tokens API</title>
    <style>
        .container { max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .btn { padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .token-table { width: 100%; border-collapse: collapse; }
        .token-table th, .token-table td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        .token-table th { background: #f8f9fa; }
        .token { font-family: monospace; background: #f8f9fa; padding: 5px 10px; border-radius: 3px; }
        .status-active { color: green; }
        .status-inactive { color: red; }
        .alert { padding: 10px; margin-bottom: 20px; border-radius: 5px; }
        .alert-success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .alert-error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .new-token { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; margin: 20px 0; border-radius: 5px; }
        .full-token { font-family: monospace; font-size: 14px; word-break: break-all; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Mis Tokens API</h1>
            <a href="/token_api/create" class="btn">Generar Nuevo Token</a>
        </div>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['new_token'])): ?>
            <div class="new-token">
                <strong>¡Nuevo Token Generado!</strong>
                <p>Guarda este token en un lugar seguro. No podrás verlo completo nuevamente:</p>
                <div class="full-token"><?= $_SESSION['new_token'] ?></div>
            </div>
            <?php unset($_SESSION['new_token']); ?>
        <?php endif; ?>

        <?php if (empty($tokens)): ?>
            <p>No tienes tokens generados. <a href="/token_api/create">Genera tu primer token</a></p>
        <?php else: ?>
            <table class="token-table">
                <thead>
                    <tr>
                        <th>Nombre</th>
                        <th>Token</th>
                        <th>Fecha Creación</th>
                        <th>Expira</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tokens as $token): ?>
                    <tr>
                        <td><?= htmlspecialchars($token['name']) ?></td>
                        <td>
                            <span class="token" title="Token completo solo visible al crearlo">
                                <?= substr($token['token'], 0, 10) ?>...<?= substr($token['token'], -10) ?>
                            </span>
                        </td>
                        <td><?= date('d/m/Y H:i', strtotime($token['created_at'])) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($token['expires_at'])) ?></td>
                        <td>
                            <span class="status-<?= $token['is_active'] ? 'active' : 'inactive' ?>">
                                <?= $token['is_active'] ? 'Activo' : 'Inactivo' ?>
                            </span>
                        </td>
                        <td>
                            <a href="/token_api/view/<?= $token['id'] ?>">Ver</a>
                            <?php if ($token['is_active']): ?>
                                | <a href="/token_api/deactivate/<?= $token['id'] ?>" onclick="return confirm('¿Desactivar este token?')">Desactivar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html>
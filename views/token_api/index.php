
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
    <title>Api Hospedaje - Gestión de Tokens</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            color: #333;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 30px 20px;
        }

        .dashboard-card {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            padding: 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
        }

        .user-welcome {
            font-size: 16px;
            opacity: 0.9;
        }

        .btn {
            padding: 12px 25px;
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: #28a745;
            border-color: #28a745;
        }

        .btn-primary:hover {
            background: #218838;
            border-color: #1e7e34;
        }

        .btn-danger {
            background: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background: #c82333;
            border-color: #bd2130;
        }

        .content {
            padding: 40px;
        }

        .alert {
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 10px;
            border-left: 5px solid;
            font-weight: 500;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left-color: #28a745;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left-color: #dc3545;
        }

        .new-token {
            background: #fff3cd;
            border: 2px solid #ffeaa7;
            padding: 25px;
            margin: 25px 0;
            border-radius: 10px;
            border-left: 5px solid #ffc107;
        }

        .full-token {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            word-break: break-all;
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 2px dashed #dee2e6;
            margin-top: 15px;
            font-weight: 600;
            color: #495057;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #6c757d;
        }

        .empty-state h3 {
            font-size: 24px;
            margin-bottom: 15px;
            color: #495057;
        }

        .token-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        }

        .token-table th {
            background: #f8f9fa;
            padding: 18px 15px;
            text-align: left;
            font-weight: 600;
            color: #495057;
            border-bottom: 2px solid #e9ecef;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .token-table td {
            padding: 18px 15px;
            border-bottom: 1px solid #e9ecef;
            vertical-align: middle;
        }

        .token-table tr:hover {
            background: #f8f9fa;
        }

        .token-table tr:last-child td {
            border-bottom: none;
        }

        .token {
            font-family: 'Courier New', monospace;
            background: #f8f9fa;
            padding: 8px 12px;
            border-radius: 6px;
            border: 1px solid #e9ecef;
            font-size: 13px;
            color: #495057;
        }

        .status {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: #d4edda;
            color: #155724;
        }

        .status-inactive {
            background: #f8d7da;
            color: #721c24;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .action-btn {
            padding: 8px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .action-view {
            background: #007bff;
            color: white;
        }

        .action-view:hover {
            background: #0056b3;
            transform: translateY(-1px);
        }

        .action-update {
            background: #17a2b8;
            color: white;
        }

        .action-update:hover {
            background: #138496;
            transform: translateY(-1px);
        }

        .btn-group {
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .date-cell {
            font-size: 14px;
            color: #6c757d;
        }

        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .btn-group {
                flex-direction: column;
                width: 100%;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .token-table {
                display: block;
                overflow-x: auto;
            }

            .content {
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="dashboard-card">
            <!-- Header -->
            <div class="header">
                <div>
                    <h1>Api Hospedaje</h1>
                    <div class="user-welcome">Bienvenido, <?= htmlspecialchars($_SESSION['username'] ?? 'Usuario') ?></div>
                </div>
                <div class="btn-group">
                    <a href="index.php?controller=tokenapi&action=create" class="btn btn-primary">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                        </svg>
                        Generar Nuevo Token
                    </a>
                    <a href="index.php?controller=auth&action=logout" class="btn btn-danger">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                            <path d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                        </svg>
                        Cerrar Sesión
                    </a>
                </div>
            </div>

            <!-- Content -->
            <div class="content">
                <!-- Alert Messages -->
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success">
                        <?= $_SESSION['success'] ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-error">
                        <?= $_SESSION['error'] ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <!-- New Token Display -->
                <?php if (isset($_SESSION['new_token'])): ?>
                    <div class="new-token">
                        <strong>¡Nuevo Token Generado!</strong>
                        <p>Guarda este token en un lugar seguro. No podrás verlo completo nuevamente:</p>
                        <div class="full-token"><?= $_SESSION['new_token'] ?></div>
                    </div>
                    <?php unset($_SESSION['new_token']); ?>
                <?php endif; ?>

                <!-- Tokens List -->
                <?php if (empty($tokens)): ?>
                    <div class="empty-state">
                        <h3>No tienes tokens generados</h3>
                        <p>Comienza generando tu primer token API para integrar con tus aplicaciones.</p>
                        <a href="index.php?controller=tokenapi&action=create" class="btn btn-primary" style="margin-top: 20px;">
                            Generar Primer Token
                        </a>
                    </div>
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
                                <td>
                                    <strong><?= htmlspecialchars($token['name']) ?></strong>
                                </td>
                                <td>
                                    <span class="token" title="Token completo solo visible al crearlo">
                                        <?= substr($token['token'], 0, 10) ?>...<?= substr($token['token'], -10) ?>
                                    </span>
                                </td>
                                <td class="date-cell">
                                    <?= date('d/m/Y H:i', strtotime($token['created_at'])) ?>
                                </td>
                                <td class="date-cell">
                                    <?= date('d/m/Y H:i', strtotime($token['expires_at'])) ?>
                                </td>
                                <td>
                                    <span class="status status-<?= $token['is_active'] ? 'active' : 'inactive' ?>">
                                        <?= $token['is_active'] ? 'Activo' : 'Inactivo' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="actions">
                                        <a href="index.php?controller=tokenapi&action=view&id=<?= $token['id'] ?>" 
                                           class="action-btn action-view">
                                            Ver
                                        </a>
                                        <a href="index.php?controller=tokenapi&action=deactivate&id=<?= $token['id'] ?>" 
                                           class="action-btn action-update"
                                           onclick="return confirm('¿Estás seguro de que deseas actualizar este token?')">
                                            Actualizar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Efectos de hover mejorados
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.btn, .action-btn');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });
    </script>
</body>
</html>

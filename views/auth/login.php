# Verificar que el login.php existe
ls -la /Applications/MAMP/htdocs/cliente_api/src/views/auth/login.php

# Si no existe, créalo con este comando:
cat > /Applications/MAMP/htdocs/cliente_api/src/views/auth/login.php << 'EOF'
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Api Hospedaje</title>
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
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            background: white;
            padding: 3rem;
            border-radius: 15px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
        }

        .logo {
            text-align: center;
            margin-bottom: 2rem;
        }

        .logo h1 {
            color: #007bff;
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .logo p {
            color: #6c757d;
            font-size: 1.1rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #495057;
            font-weight: 600;
        }

        input {
            width: 100%;
            padding: 1rem;
            border: 2px solid #e9ecef;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        input:focus {
            outline: none;
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        button {
            width: 100%;
            padding: 1rem;
            background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 0.5rem;
        }

        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(0, 123, 255, 0.3);
        }

        .error {
            background: #f8d7da;
            color: #721c24;
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 1.5rem;
            text-align: center;
            border: 1px solid #f5c6cb;
        }

        .demo-info {
            background: #d1ecf1;
            color: #0c5460;
            padding: 1rem;
            border-radius: 8px;
            margin-top: 2rem;
            border: 1px solid #bee5eb;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🔐 Api Hospedaje</h1>
            <p>Gestión de Tokens API</p>
        </div>
        
        <?php if (!empty($error)): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="index.php?controller=auth&action=login">
            <div class="form-group">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="username" required 
                       value="<?= htmlspecialchars($_POST['username'] ?? '') ?>"
                       placeholder="Ingresa tu usuario">
            </div>
            
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required 
                       placeholder="Ingresa tu contraseña">
            </div>
            
            <button type="submit">Ingresar al Sistema</button>
        </form>

        <div class="demo-info">
            <strong>Credenciales de Demo</strong><br>
            Usuario: <strong>admin</strong><br>
            Contraseña: <strong>admin123</strong>
        </div>
    </div>

    <script>
        document.getElementById('username').focus();
    </script>
</body>
</html>
EOF
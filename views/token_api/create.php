<?php include_once 'views/layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="text-center">Generar Nuevo Token API</h4>
            </div>
            <div class="card-body">
                <p>Este token te permitirá acceder a la API de hoteles. El token expirará en 1 año.</p>
                <form method="POST">
                    <div class="mb-3">
                        <label for="name" class="form-label">Nombre del Token</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               placeholder="Ej: Token para Hotel Valencia" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Generar Token</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include_once 'views/layouts/footer.php'; ?>
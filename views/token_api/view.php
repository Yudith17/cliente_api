<?php include_once 'views/layouts/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Lista de Hoteles</h2>
    <a href="index" class="btn btn-secondary">Volver a Tokens</a>
</div>

<?php if(isset($hoteles['error'])): ?>
    <div class="alert alert-danger"><?php echo $hoteles['error']; ?></div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Categoría</th>
                            <th>Descripción</th>
                            <th>Ubicación</th>
                            <th>Contacto</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($hoteles as $hotel): ?>
                        <tr>
                            <td><?php echo $hotel['nombre'] . ' ID: ' . $hotel['id']; ?></td>
                            <td><?php echo $hotel['categoria']; ?></td>
                            <td><?php echo substr($hotel['descripcion'], 0, 100) . '...'; ?></td>
                            <td><?php echo $hotel['ubicacion']; ?></td>
                            <td>
                                <?php if(!empty($hotel['telefono'])): ?>
                                    📞 <?php echo $hotel['telefono']; ?><br>
                                <?php endif; ?>
                                <?php if(!empty($hotel['email'])): ?>
                                    📧 <?php echo $hotel['email']; ?>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-info btn-sm" onclick="verHotel(<?php echo $hotel['id']; ?>)">💬 Ver</button>
                                <button class="btn btn-warning btn-sm">💬 Estar</button>
                                <button class="btn btn-success btn-sm">💬 Entrar</button>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal para ver detalles del hotel -->
    <div class="modal fade" id="hotelModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detalles del Hotel</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="hotelDetails">
                    Cargando...
                </div>
            </div>
        </div>
    </div>

    <script>
    function verHotel(hotelId) {
        fetch('getHotel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'token=<?php echo $tokenData['token']; ?>&hotel_id=' + hotelId
        })
        .then(response => response.json())
        .then(data => {
            if(data.error) {
                document.getElementById('hotelDetails').innerHTML = '<div class="alert alert-danger">' + data.error + '</div>';
            } else {
                document.getElementById('hotelDetails').innerHTML = `
                    <h4>${data.nombre}</h4>
                    <p><strong>Categoría:</strong> ${data.categoria}</p>
                    <p><strong>Descripción:</strong> ${data.descripcion}</p>
                    <p><strong>Ubicación:</strong> ${data.ubicacion}</p>
                    <p><strong>Contacto:</strong> ${data.telefono} | ${data.email}</p>
                `;
            }
            new bootstrap.Modal(document.getElementById('hotelModal')).show();
        });
    }
    </script>
<?php endif; ?>

<?php include_once 'views/layouts/footer.php'; ?>
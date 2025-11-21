<!-- Modal Registrar Administrador -->
<div class="modal fade" id="modalRegistrarAdmin" tabindex="-1" aria-labelledby="modalRegistrarAdminLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="modalRegistrarAdminLabel">Registrar Nuevo Administrador</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formRegistrarAdmin">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="adminNombre" class="form-label">Nombre Completo</label>
                        <input type="text" class="form-control" id="adminNombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="adminCorreo" class="form-label">Correo Electrónico</label>
                        <input type="email" class="form-control" id="adminCorreo" name="correo" required>
                    </div>
                    <div class="mb-3">
                        <label for="adminTelefono" class="form-label">Teléfono (opcional)</label>
                        <input type="tel" class="form-control" id="adminTelefono" name="telefono">
                    </div>
                    <div class="mb-3">
                        <label for="adminPassword" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="adminPassword" name="contraseña" required minlength="8">
                    </div>
                    <div class="alert alert-danger d-none" id="adminError" role="alert"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-user-plus me-2"></i>Registrar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


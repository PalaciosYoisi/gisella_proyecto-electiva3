<!-- Modal Gestionar Usuario -->
<div class="modal fade" id="modalGestionarUsuario" tabindex="-1" aria-labelledby="modalGestionarUsuarioLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalGestionarUsuarioLabel">Gestionar Usuario</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="modalGestionarUsuarioBody">
                <form id="formGestionarUsuario">
                    <div class="mb-3">
                        <label class="form-label">Acción</label>
                        <select class="form-select" id="accionUsuario" name="accion" required>
                            <option value="">Seleccione una acción</option>
                            <option value="activar">Activar Usuario</option>
                            <option value="desactivar">Desactivar Usuario</option>
                            <option value="cambiar_tipo">Cambiar Tipo de Usuario</option>
                            <option value="eliminar">Eliminar Usuario</option>
                        </select>
                    </div>
                    <div class="mb-3" id="divTipoUsuario" style="display: none;">
                        <label class="form-label">Nuevo Tipo de Usuario</label>
                        <select class="form-select" id="tipoUsuario" name="tipo_usuario">
                            <option value="turista">Turista</option>
                            <option value="prestador">Prestador</option>
                            <option value="administrador">Administrador</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Comentario (opcional)</label>
                        <textarea class="form-control" id="comentarioUsuario" name="comentario" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarGestionUsuario">
                    <i class="fas fa-check me-2"></i>Confirmar
                </button>
            </div>
        </div>
    </div>
</div>


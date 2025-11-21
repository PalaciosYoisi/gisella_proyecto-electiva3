<!-- Modal de Reserva (Reutilizable) -->
<div class="modal fade" id="reservaModal" tabindex="-1" aria-labelledby="reservaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="reservaModalLabel">
                    <i class="fas fa-calendar-check me-2"></i>Reservar Experiencia
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Mensaje si no está autenticado -->
                <div id="reservaNoAuthMessage" class="text-center py-5" style="display: none;">
                    <i class="fas fa-lock fa-3x text-muted mb-3"></i>
                    <h5 class="mb-3">Inicia sesión para reservar</h5>
                    <p class="text-muted mb-4">Necesitas tener una cuenta para realizar reservas en nuestras experiencias.</p>
                    <button type="button" class="btn btn-primary me-2" data-bs-target="#loginModal" data-bs-toggle="modal" data-bs-dismiss="modal">
                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                    </button>
                    <button type="button" class="btn btn-outline-primary" data-bs-target="#registerModal" data-bs-toggle="modal" data-bs-dismiss="modal">
                        <i class="fas fa-user-plus me-2"></i>Registrarse
                    </button>
                </div>

                <!-- Formulario de reserva (solo visible si está autenticado) -->
                <form id="formReserva" style="display: none;">
                    <input type="hidden" id="reservaExperienciaId" name="id_experiencia">
                    
                    <!-- Información de la experiencia -->
                    <div class="reserva-experiencia-info mb-4 p-3 bg-light rounded">
                        <div class="d-flex align-items-center">
                            <img id="reservaExperienciaImagen" src="" alt="" class="me-3 rounded" style="width: 80px; height: 80px; object-fit: cover;">
                            <div>
                                <h6 class="mb-1 fw-bold" id="reservaExperienciaTitulo">Experiencia</h6>
                                <p class="mb-1 text-muted small" id="reservaExperienciaUbicacion"></p>
                                <p class="mb-0">
                                    <span class="fw-bold text-primary" id="reservaExperienciaPrecio">$0</span>
                                    <span class="text-muted small">por persona</span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Fecha del evento -->
                    <div class="mb-3">
                        <label for="reservaFecha" class="form-label fw-semibold">
                            <i class="fas fa-calendar-alt me-2"></i>Fecha de la experiencia <span class="text-danger">*</span>
                        </label>
                        <input type="date" class="form-control" id="reservaFecha" name="fecha_evento" required>
                        <small class="text-muted">Selecciona la fecha en que deseas realizar la experiencia</small>
                    </div>

                    <!-- Número de participantes -->
                    <div class="mb-3">
                        <label class="form-label fw-semibold">
                            <i class="fas fa-users me-2"></i>Número de personas <span class="text-danger">*</span>
                        </label>
                        <div class="d-flex align-items-center gap-2">
                            <button type="button" class="btn btn-outline-secondary btn-count" data-action="decrease">
                                <i class="fas fa-minus"></i>
                            </button>
                            <input type="number" class="form-control text-center" id="reservaPersonas" name="cantidad_personas" value="1" min="1" max="50" required style="max-width: 100px;">
                            <button type="button" class="btn btn-outline-secondary btn-count" data-action="increase">
                                <i class="fas fa-plus"></i>
                            </button>
                            <small class="text-muted ms-2" id="reservaCapacidadInfo"></small>
                        </div>
                    </div>

                    <!-- Contacto de emergencia -->
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label for="reservaContactoEmergencia" class="form-label">
                                <i class="fas fa-user-friends me-2"></i>Contacto de emergencia
                            </label>
                            <input type="text" class="form-control" id="reservaContactoEmergencia" name="contacto_emergencia" placeholder="Nombre completo">
                        </div>
                        <div class="col-md-6">
                            <label for="reservaTelefonoEmergencia" class="form-label">
                                <i class="fas fa-phone me-2"></i>Teléfono de emergencia
                            </label>
                            <input type="tel" class="form-control" id="reservaTelefonoEmergencia" name="telefono_emergencia" placeholder="+57 300 123 4567">
                        </div>
                    </div>

                    <!-- Detalles adicionales -->
                    <div class="mb-3">
                        <label for="reservaDetalles" class="form-label">
                            <i class="fas fa-comment-alt me-2"></i>Detalles adicionales (opcional)
                        </label>
                        <textarea class="form-control" id="reservaDetalles" name="detalles" rows="3" placeholder="Información adicional que quieras compartir con el prestador..."></textarea>
                    </div>

                    <!-- Resumen de la reserva -->
                    <div class="reserva-resumen p-3 bg-light rounded mb-3">
                        <h6 class="fw-bold mb-3">Resumen de la reserva</h6>
                        <div class="d-flex justify-content-between mb-2">
                            <span id="reservaResumenPersonas">1 persona x $0</span>
                            <span id="reservaSubtotal">$0</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between">
                            <strong>Total:</strong>
                            <strong class="text-primary fs-5" id="reservaTotal">$0</strong>
                        </div>
                    </div>

                    <!-- Alerta de disponibilidad -->
                    <div id="reservaDisponibilidadAlert" class="alert alert-info d-none" role="alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <span id="reservaDisponibilidadTexto"></span>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" id="btnConfirmarReserva" style="display: none;">
                    <i class="fas fa-calendar-check me-2"></i>Confirmar Reserva
                </button>
            </div>
        </div>
    </div>
</div>


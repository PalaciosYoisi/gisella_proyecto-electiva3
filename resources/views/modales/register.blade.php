<!-- Modal Register -->
<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content register-modal-content">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Lado izquierdo - Formulario -->
                    <div class="col-md-7">
                        <div class="register-form-wrapper p-4 p-md-5">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                            
                            <div class="text-center mb-4">
                                <h4 class="fw-bold text-dark mb-2">Crear Cuenta</h4>
                                <p class="text-muted small">Únete a ExploraQuibdó y comienza tu aventura</p>
                            </div>

                            <form id="registerForm" action="{{ route('registro') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                
                                <!-- Selector de Rol -->
                                <div class="mb-4">
                                    <label class="form-label fw-semibold">¿Cómo deseas registrarte?</label>
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="tipo_usuario" id="tipoTurista" value="turista" checked required>
                                            <label class="btn btn-outline-primary w-100 role-selector" for="tipoTurista">
                                                <i class="fas fa-user-tie d-block mb-2" style="font-size: 2rem;"></i>
                                                <span class="fw-semibold">Turista</span>
                                                <small class="d-block text-muted mt-1">Explora y reserva</small>
                                            </label>
                                        </div>
                                        <div class="col-6">
                                            <input type="radio" class="btn-check" name="tipo_usuario" id="tipoPrestador" value="prestador" required>
                                            <label class="btn btn-outline-primary w-100 role-selector" for="tipoPrestador">
                                                <i class="fas fa-briefcase d-block mb-2" style="font-size: 2rem;"></i>
                                                <span class="fw-semibold">Prestador</span>
                                                <small class="d-block text-muted mt-1">Ofrece servicios</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="registerName" class="form-label fw-semibold">Nombre completo</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-user text-primary"></i>
                                        </span>
                                        <input type="text" class="form-control border-start-0" id="registerName" name="nombre" placeholder="Tu nombre completo" required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Por favor, ingresa tu nombre completo.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="registerEmail" class="form-label fw-semibold">Correo electrónico</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-envelope text-primary"></i>
                                        </span>
                                        <input type="email" class="form-control border-start-0" id="registerEmail" name="correo" placeholder="tu@email.com" required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Por favor, ingresa un correo electrónico válido.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="registerPhone" class="form-label fw-semibold">Teléfono <span class="text-muted">(opcional)</span></label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-phone text-primary"></i>
                                        </span>
                                        <input type="tel" class="form-control border-start-0" id="registerPhone" name="telefono" placeholder="+57 300 000 0000">
                                    </div>
                                </div>

                                <!-- Campos adicionales para prestadores -->
                                <div id="prestadorFields" style="display: none;">
                                    <hr class="my-4">
                                    <h6 class="fw-bold mb-3 text-primary">
                                        <i class="fas fa-briefcase me-2"></i>Información del Servicio
                                    </h6>

                                    <div class="mb-3">
                                        <label for="nombreServicio" class="form-label fw-semibold">Nombre del Servicio <span class="text-danger">*</span></label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-store text-primary"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0" id="nombreServicio" name="nombre_servicio" placeholder="Ej: Tours por el Río Atrato">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="descripcionServicio" class="form-label fw-semibold">Descripción del Servicio <span class="text-danger">*</span></label>
                                        <textarea class="form-control" id="descripcionServicio" name="descripcion_servicio" rows="3" placeholder="Describe detalladamente el servicio que ofreces (mínimo 50 caracteres)"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label for="categoriaServicio" class="form-label fw-semibold">Categoría del Servicio <span class="text-danger">*</span></label>
                                        <select class="form-select form-select-lg" id="categoriaServicio" name="categoria_servicio">
                                            <option value="">Selecciona una categoría</option>
                                            <option value="naturaleza">Naturaleza</option>
                                            <option value="cultura">Cultura</option>
                                            <option value="gastronomia">Gastronomía</option>
                                            <option value="aventura">Aventura</option>
                                            <option value="relax">Relax</option>
                                            <option value="eventos">Eventos</option>
                                        </select>
                                    </div>

                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label for="direccion" class="form-label fw-semibold">Dirección <span class="text-danger">*</span></label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-map-marker-alt text-primary"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0" id="direccion" name="direccion" placeholder="Dirección completa">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="ciudad" class="form-label fw-semibold">Ciudad <span class="text-danger">*</span></label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-city text-primary"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0" id="ciudad" name="ciudad" value="Quibdó" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <hr class="my-4">
                                    <h6 class="fw-bold mb-3 text-primary">
                                        <i class="fas fa-id-card me-2"></i>Información Legal
                                    </h6>

                                    <div class="row g-3">
                                        <div class="col-md-4">
                                            <label for="tipoDocumento" class="form-label fw-semibold">Tipo de Documento <span class="text-danger">*</span></label>
                                            <select class="form-select form-select-lg" id="tipoDocumento" name="tipo_documento">
                                                <option value="">Selecciona</option>
                                                <option value="cc">Cédula de Ciudadanía</option>
                                                <option value="ce">Cédula de Extranjería</option>
                                                <option value="passport">Pasaporte</option>
                                                <option value="nit">NIT</option>
                                            </select>
                                        </div>
                                        <div class="col-md-8">
                                            <label for="documentoIdentidad" class="form-label fw-semibold">Número de Documento <span class="text-danger">*</span></label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-id-badge text-primary"></i>
                                                </span>
                                                <input type="text" class="form-control border-start-0" id="documentoIdentidad" name="documento_identidad" placeholder="Número de documento">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row g-3 mt-2">
                                        <div class="col-md-6">
                                            <label for="experienciaAnos" class="form-label fw-semibold">Años de Experiencia</label>
                                            <div class="input-group input-group-lg">
                                                <span class="input-group-text bg-light border-end-0">
                                                    <i class="fas fa-calendar-alt text-primary"></i>
                                                </span>
                                                <input type="number" class="form-control border-start-0" id="experienciaAnos" name="experiencia_anos" min="0" max="50" placeholder="0">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="banco" class="form-label fw-semibold">Banco <span class="text-muted">(opcional)</span></label>
                                            <input type="text" class="form-control form-control-lg" id="banco" name="banco" placeholder="Nombre del banco">
                                        </div>
                                    </div>

                                    <div class="mb-3 mt-2">
                                        <label for="numeroCuenta" class="form-label fw-semibold">Número de Cuenta <span class="text-muted">(opcional)</span></label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light border-end-0">
                                                <i class="fas fa-university text-primary"></i>
                                            </span>
                                            <input type="text" class="form-control border-start-0" id="numeroCuenta" name="numero_cuenta" placeholder="Número de cuenta bancaria">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="registerPassword" class="form-label fw-semibold">Contraseña</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <input type="password" class="form-control border-start-0" id="registerPassword" name="contraseña" placeholder="Mínimo 8 caracteres" required minlength="8">
                                        <button class="btn btn-outline-secondary border-start-0" type="button" id="toggleRegisterPassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">
                                        La contraseña debe tener al menos 8 caracteres.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="confirmPassword" class="form-label fw-semibold">Confirmar contraseña</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <input type="password" class="form-control border-start-0" id="confirmPassword" name="confirmar" placeholder="Repite tu contraseña" required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Las contraseñas no coinciden.
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="acceptTerms" required>
                                        <label class="form-check-label small" for="acceptTerms">
                                            Acepto los <a href="#" class="text-primary">términos y condiciones</a> y la <a href="#" class="text-primary">política de privacidad</a>
                                        </label>
                                        <div class="invalid-feedback">
                                            Debes aceptar los términos y condiciones para continuar.
                                        </div>
                                    </div>
                                </div>

                                <div class="alert alert-danger d-none" id="registerError" role="alert"></div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                        <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                                    </button>
                                </div>
                            </form>

                            <div class="text-center">
                                <p class="text-muted mb-0">¿Ya tienes una cuenta?
                                    <a href="#" class="text-primary fw-semibold text-decoration-none" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
                                        Inicia sesión
                                    </a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Lado derecho - Imagen/Decoración -->
                    <div class="col-md-5 register-right-side d-none d-md-flex">
                        <div class="register-right-content">
                            <div class="register-logo mb-4">
                                <h2 class="text-white mb-0">
                                    <span class="fw-bold">Explora</span><span class="fw-light">Quibdó</span>
                                </h2>
                            </div>
                            <div class="register-welcome-text">
                                <h3 class="text-white fw-bold mb-3">¡Únete a nuestra comunidad!</h3>
                                <p class="text-white-50 mb-4">Forma parte de la plataforma que promueve el turismo en Quibdó</p>
                                <div class="register-benefits">
                                    <div class="benefit-item mb-3">
                                        <i class="fas fa-check-circle text-white me-2"></i>
                                        <span class="text-white">Acceso a experiencias exclusivas</span>
                                    </div>
                                    <div class="benefit-item mb-3">
                                        <i class="fas fa-check-circle text-white me-2"></i>
                                        <span class="text-white">Reserva fácil y rápida</span>
                                    </div>
                                    <div class="benefit-item mb-3">
                                        <i class="fas fa-check-circle text-white me-2"></i>
                                        <span class="text-white">Ofertas y descuentos especiales</span>
                                    </div>
                                    <div class="benefit-item">
                                        <i class="fas fa-check-circle text-white me-2"></i>
                                        <span class="text-white">Soporte 24/7</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

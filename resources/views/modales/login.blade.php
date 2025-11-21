<!-- Modal Login -->
<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content login-modal-content">
            <div class="modal-body p-0">
                <div class="row g-0">
                    <!-- Lado izquierdo - Imagen/Decoración -->
                    <div class="col-md-5 login-left-side d-none d-md-flex">
                        <div class="login-left-content">
                            <div class="login-logo mb-4">
                                <h2 class="text-white mb-0">
                                    <span class="fw-bold">Explora</span><span class="fw-light">Quibdó</span>
                                </h2>
                            </div>
                            <div class="login-welcome-text">
                                <h3 class="text-white fw-bold mb-3">¡Bienvenido de nuevo!</h3>
                                <p class="text-white-50 mb-4">Descubre la magia del Chocó y vive experiencias únicas en Quibdó</p>
                                <div class="login-features">
                                    <div class="feature-item mb-3">
                                        <i class="fas fa-map-marked-alt text-white me-2"></i>
                                        <span class="text-white">Explora destinos únicos</span>
                                    </div>
                                    <div class="feature-item mb-3">
                                        <i class="fas fa-star text-white me-2"></i>
                                        <span class="text-white">Vive experiencias inolvidables</span>
                                    </div>
                                    <div class="feature-item">
                                        <i class="fas fa-heart text-white me-2"></i>
                                        <span class="text-white">Conecta con la cultura local</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Lado derecho - Formulario -->
                    <div class="col-md-7">
                        <div class="login-form-wrapper p-4 p-md-5">
                            <button type="button" class="btn-close position-absolute top-0 end-0 m-3" data-bs-dismiss="modal" aria-label="Close"></button>
                            
                            <div class="text-center mb-4">
                                <h4 class="fw-bold text-dark mb-2">Iniciar Sesión</h4>
                                <p class="text-muted small">Ingresa tus credenciales para continuar</p>
                            </div>

                            <form id="loginForm" action="{{ route('login') }}" method="POST" class="needs-validation" novalidate>
                                @csrf
                                
                                <div class="mb-3">
                                    <label for="loginEmail" class="form-label fw-semibold">Correo electrónico</label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-envelope text-primary"></i>
                                        </span>
                                        <input type="email" class="form-control border-start-0" id="loginEmail" name="email" placeholder="tu@email.com" required>
                                    </div>
                                    <div class="invalid-feedback">
                                        Por favor, ingresa un correo electrónico válido.
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <label for="loginPassword" class="form-label fw-semibold mb-0">Contraseña</label>
                                        <a href="#" class="text-primary text-decoration-none small">¿Olvidaste tu contraseña?</a>
                                    </div>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light border-end-0">
                                            <i class="fas fa-lock text-primary"></i>
                                        </span>
                                        <input type="password" class="form-control border-start-0" id="loginPassword" name="password" placeholder="Ingresa tu contraseña" required minlength="6">
                                        <button class="btn btn-outline-secondary border-start-0" type="button" id="togglePassword">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="invalid-feedback">
                                        La contraseña debe tener al menos 6 caracteres.
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
                                        <label class="form-check-label" for="rememberMe">Mantener sesión iniciada</label>
                                    </div>
                                </div>

                                <div class="alert d-none" id="loginError" role="alert"></div>

                                <div class="d-grid mb-3">
                                    <button type="submit" class="btn btn-primary btn-lg fw-semibold">
                                        <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                                    </button>
                                </div>
                            </form>

                            <div class="text-center">
                                <p class="text-muted mb-0">¿No tienes una cuenta?
                                    <a href="#" class="text-primary fw-semibold text-decoration-none" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">
                                        Regístrate aquí
                                    </a>
                                </p>
                            </div>

                            <div class="divider my-4">
                                <span class="text-muted small">o continúa con</span>
                            </div>

                            <div class="social-login">
                                <button class="btn btn-outline-secondary w-100 mb-2">
                                    <i class="fab fa-google me-2"></i>Continuar con Google
                                </button>
                                <button class="btn btn-outline-secondary w-100">
                                    <i class="fab fa-facebook me-2"></i>Continuar con Facebook
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

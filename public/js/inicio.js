/**
 * ExploraQuibdó - JavaScript Principal
 * Funcionalidad dinámica para la página de inicio
 */

(function() {
    'use strict';

    // ============================================
    // INICIALIZACIÓN
    // ============================================
    document.addEventListener('DOMContentLoaded', function() {
        initNavbar();
        initCounters();
        initSmoothScroll();
        initContactForm();
        initLoginForm();
        initRegisterForm();
        initImageErrorHandling();
        initAnimations();
        cargarPublicaciones(); // Cargar publicaciones dinámicamente
        initReservas(); // Inicializar sistema de reservas
    });

    // ============================================
    // NAVBAR - Scroll Effect
    // ============================================
    function initNavbar() {
        const navbar = document.getElementById('mainNavbar');
        if (!navbar) return;

        let lastScroll = 0;
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
            
            lastScroll = currentScroll;
        });

        // Activar enlace activo según scroll
        const sections = document.querySelectorAll('section[id]');
        const navLinks = document.querySelectorAll('.navbar-nav .nav-link');

        window.addEventListener('scroll', function() {
            let current = '';
            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;
                if (pageYOffset >= sectionTop - 200) {
                    current = section.getAttribute('id');
                }
            });

            navLinks.forEach(link => {
                link.classList.remove('active');
                if (link.getAttribute('href') === '#' + current) {
                    link.classList.add('active');
                }
            });
        });
    }

    // ============================================
    // CONTADORES ANIMADOS
    // ============================================
    function initCounters() {
        const counters = document.querySelectorAll('.counter');
        const speed = 200;

        const animateCounter = (counter) => {
            const target = parseInt(counter.getAttribute('data-target')) || 0;
            const count = parseInt(counter.innerText) || 0;
            const increment = target / speed;

            if (count < target) {
                counter.innerText = Math.ceil(count + increment);
                setTimeout(() => animateCounter(counter), 1);
            } else {
                counter.innerText = target;
            }
        };

        // Intersection Observer para activar contadores cuando son visibles
        const observerOptions = {
            threshold: 0.5,
            rootMargin: '0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    if (!counter.classList.contains('counted')) {
                        counter.classList.add('counted');
                        animateCounter(counter);
                    }
                }
            });
        }, observerOptions);

        counters.forEach(counter => {
            observer.observe(counter);
        });
    }

    // ============================================
    // SMOOTH SCROLL
    // ============================================
    function initSmoothScroll() {
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                const href = this.getAttribute('href');
                if (href === '#' || href === '#!') return;
                
                e.preventDefault();
                const target = document.querySelector(href);
                
                if (target) {
                    const offsetTop = target.offsetTop - 80; // Altura del navbar
                    window.scrollTo({
                        top: offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    }

    // ============================================
    // FORMULARIO DE CONTACTO
    // ============================================
    function initContactForm() {
        const form = document.getElementById('contactForm');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const submitButton = form.querySelector('button[type="submit"]');
            const successAlert = document.getElementById('contactSuccess');
            
            // Deshabilitar botón
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando...';

            // Simular envío (aquí iría la llamada AJAX real)
            setTimeout(() => {
                // Mostrar mensaje de éxito
                if (successAlert) {
                    successAlert.classList.remove('d-none');
                    form.reset();
                    
                    // Ocultar mensaje después de 5 segundos
                    setTimeout(() => {
                        successAlert.classList.add('d-none');
                    }, 5000);
                }

                // Restaurar botón
                submitButton.disabled = false;
                submitButton.innerHTML = '<i class="fas fa-paper-plane me-2"></i>Enviar Mensaje';
            }, 1500);
        });
    }

    // ============================================
    // FORMULARIO DE LOGIN
    // ============================================
    function initLoginForm() {
        const form = document.getElementById('loginForm');
        if (!form) return;

        // Toggle password visibility
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('loginPassword');
        const errorAlert = document.getElementById('loginError');

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }

        // Manejo del envío del formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Deshabilitar botón y mostrar loading
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Iniciando sesión...';
            
            // Ocultar errores previos
            if (errorAlert) {
                errorAlert.classList.add('d-none');
                errorAlert.classList.remove('alert-danger', 'alert-success');
            }

            // Obtener datos del formulario
            const formData = new FormData(form);
            const data = {
                email: formData.get('email'),
                password: formData.get('password'),
                remember_me: formData.get('remember_me') === 'on'
            };

            // Enviar petición AJAX
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    if (errorAlert) {
                        errorAlert.textContent = data.message || 'Inicio de sesión exitoso';
                        errorAlert.classList.remove('d-none', 'alert-danger');
                        errorAlert.classList.add('alert-success');
                    }
                    
                    // Actualizar navbar antes de redirigir
                    actualizarNavbarUsuario(data.usuario);
                    
                    // Redirigir después de un breve delay
                    setTimeout(() => {
                        window.location.href = data.redirect || '/panel/turista';
                    }, 1000);
                } else {
                    // Mostrar errores
                    let errorMessage = data.message || 'Error al iniciar sesión';
                    
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat();
                        errorMessage = errorList.join('<br>');
                    }
                    
                    if (errorAlert) {
                        errorAlert.innerHTML = errorMessage;
                        errorAlert.classList.remove('d-none', 'alert-success');
                        errorAlert.classList.add('alert-danger');
                    }
                    
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (errorAlert) {
                    errorAlert.textContent = 'Error de conexión. Por favor, intenta nuevamente.';
                    errorAlert.classList.remove('d-none', 'alert-success');
                    errorAlert.classList.add('alert-danger');
                }
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
    }

    // ============================================
    // FORMULARIO DE REGISTRO
    // ============================================
    function initRegisterForm() {
        const form = document.getElementById('registerForm');
        if (!form) return;

        // Toggle password visibility
        const togglePassword = document.getElementById('toggleRegisterPassword');
        const passwordInput = document.getElementById('registerPassword');
        const confirmPasswordInput = document.getElementById('confirmPassword');
        const errorAlert = document.getElementById('registerError');
        const prestadorFields = document.getElementById('prestadorFields');
        const tipoTurista = document.getElementById('tipoTurista');
        const tipoPrestador = document.getElementById('tipoPrestador');

        // Mostrar/ocultar campos de prestador
        function togglePrestadorFields() {
            if (prestadorFields) {
                const isPrestador = tipoPrestador && tipoPrestador.checked;
                prestadorFields.style.display = isPrestador ? 'block' : 'none';
                
                // Marcar campos como requeridos o no
                const prestadorInputs = prestadorFields.querySelectorAll('input[required], select[required], textarea[required]');
                prestadorInputs.forEach(input => {
                    if (isPrestador) {
                        input.setAttribute('required', 'required');
                    } else {
                        input.removeAttribute('required');
                    }
                });
            }
        }

        if (tipoTurista && tipoPrestador) {
            tipoTurista.addEventListener('change', togglePrestadorFields);
            tipoPrestador.addEventListener('change', togglePrestadorFields);
            togglePrestadorFields(); // Inicializar estado
        }

        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.querySelector('i').classList.toggle('fa-eye');
                this.querySelector('i').classList.toggle('fa-eye-slash');
            });
        }

        // Validar que las contraseñas coincidan
        if (confirmPasswordInput && passwordInput) {
            confirmPasswordInput.addEventListener('input', function() {
                if (this.value !== passwordInput.value) {
                    this.setCustomValidity('Las contraseñas no coinciden');
                } else {
                    this.setCustomValidity('');
                }
            });
        }

        // Manejo del envío del formulario
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            // Validar que las contraseñas coincidan
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Las contraseñas no coinciden');
                confirmPasswordInput.reportValidity();
                return;
            }

            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.innerHTML;
            
            // Deshabilitar botón y mostrar loading
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Creando cuenta...';
            
            // Ocultar errores previos
            if (errorAlert) {
                errorAlert.classList.add('d-none');
            }

            // Obtener datos del formulario
            const formData = new FormData(form);
            const tipoUsuario = formData.get('tipo_usuario') || 'turista';
            
            const data = {
                nombre: formData.get('nombre'),
                correo: formData.get('correo'),
                telefono: formData.get('telefono') || null,
                contraseña: formData.get('contraseña'),
                confirmar: formData.get('confirmar'),
                tipo_usuario: tipoUsuario
            };

            // Agregar campos adicionales si es prestador
            if (tipoUsuario === 'prestador') {
                data.nombre_servicio = formData.get('nombre_servicio');
                data.descripcion_servicio = formData.get('descripcion_servicio');
                data.categoria_servicio = formData.get('categoria_servicio');
                data.direccion = formData.get('direccion');
                data.ciudad = formData.get('ciudad') || 'Quibdó';
                data.documento_identidad = formData.get('documento_identidad');
                data.tipo_documento = formData.get('tipo_documento');
                data.experiencia_anos = formData.get('experiencia_anos') || 0;
                data.numero_cuenta = formData.get('numero_cuenta') || null;
                data.banco = formData.get('banco') || null;
            }

            // Enviar petición AJAX
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token')
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Mostrar mensaje de éxito
                    if (errorAlert) {
                        errorAlert.textContent = data.message || 'Registro exitoso';
                        errorAlert.classList.remove('d-none', 'alert-danger');
                        errorAlert.classList.add('alert-success');
                    }
                    
                    // Cerrar modal y redirigir después de un breve delay
                    const modal = bootstrap.Modal.getInstance(document.getElementById('registerModal'));
                    if (modal) {
                        modal.hide();
                    }
                    
                    // Actualizar navbar antes de redirigir
                    actualizarNavbarUsuario(data.usuario);
                    
                    setTimeout(() => {
                        window.location.href = data.redirect || '/panel/turista';
                    }, 1500);
                } else {
                    // Mostrar errores
                    let errorMessage = data.message || 'Error al registrar usuario';
                    
                    if (data.errors) {
                        const errorList = Object.values(data.errors).flat();
                        errorMessage = errorList.join('<br>');
                    }
                    
                    if (errorAlert) {
                        errorAlert.innerHTML = errorMessage;
                        errorAlert.classList.remove('d-none');
                        errorAlert.classList.add('alert-danger');
                    }
                    
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                if (errorAlert) {
                    errorAlert.textContent = 'Error de conexión. Por favor, intenta nuevamente.';
                    errorAlert.classList.remove('d-none');
                    errorAlert.classList.add('alert-danger');
                }
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            });
        });
    }

    // ============================================
    // MANEJO DE ERRORES DE IMÁGENES
    // ============================================
    function initImageErrorHandling() {
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            img.addEventListener('error', function() {
                // Si la imagen falla, ya tiene un onerror en el HTML que muestra un placeholder
                console.warn('Imagen no encontrada:', this.src);
            });
        });
    }

    // ============================================
    // ANIMACIONES AL SCROLL
    // ============================================
    function initAnimations() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Aplicar animación a las cards
        const cards = document.querySelectorAll('.destination-card, .experience-card, .info-card');
        cards.forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(30px)';
            card.style.transition = 'opacity 0.6s ease, transform 0.6s ease';
            observer.observe(card);
        });
    }

    // ============================================
    // BOTÓN VER MÁS/VER MENOS EXPERIENCIAS
    // ============================================
    const btnVerMas = document.getElementById('btnVerMasExperiencias');
    const btnVerMenos = document.getElementById('btnVerMenosExperiencias');
    const fila2 = document.getElementById('experienciasFila2');
    const btnVerMasContainer = document.getElementById('btnVerMasContainer');
    const btnVerMenosContainer = document.getElementById('btnVerMenosContainer');

    if (btnVerMas && fila2) {
        btnVerMas.addEventListener('click', function() {
            // Mostrar segunda fila
            fila2.style.display = 'flex';
            fila2.classList.add('show', 'fade-in');
            
            // Ocultar botón "Ver más" y mostrar "Ver menos"
            if (btnVerMasContainer) btnVerMasContainer.style.display = 'none';
            if (btnVerMenosContainer) btnVerMenosContainer.style.display = 'block';
            
            // Scroll suave a la segunda fila
            setTimeout(() => {
                fila2.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }, 100);
            
            // Iniciar videos cuando se muestren (autoplay en loop)
            const videos = fila2.querySelectorAll('video');
            videos.forEach(video => {
                video.play().catch(e => {
                    // Silenciar errores de autoplay (algunos navegadores lo bloquean)
                    console.log('Video autoplay bloqueado, se reproducirá al hacer hover');
                });
            });
        });
    }

    if (btnVerMenos && fila2) {
        btnVerMenos.addEventListener('click', function() {
            // Ocultar segunda fila
            fila2.style.display = 'none';
            
            // Ocultar botón "Ver menos" y mostrar "Ver más"
            if (btnVerMenosContainer) btnVerMenosContainer.style.display = 'none';
            if (btnVerMasContainer) btnVerMasContainer.style.display = 'block';
            
            // Pausar videos
            const videos = fila2.querySelectorAll('video');
            videos.forEach(video => {
                video.pause();
            });
            
            // Scroll suave al botón "Ver más"
            setTimeout(() => {
                if (btnVerMasContainer) {
                    btnVerMasContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
                }
            }, 100);
        });
    }
    
    // Auto-play videos en hover (solo cuando están visibles)
    document.querySelectorAll('.experience-card video').forEach(video => {
        const card = video.closest('.experience-card');
        if (card) {
            card.addEventListener('mouseenter', function() {
                if (card.offsetParent !== null) { // Verificar que esté visible
                    video.play().catch(e => console.log('Error al reproducir video:', e));
                }
            });
            card.addEventListener('mouseleave', function() {
                video.pause();
            });
        }
    });

    // ============================================
    // ACTUALIZAR NAVBAR CON USUARIO
    // ============================================
    function actualizarNavbarUsuario(usuario) {
        if (!usuario || !usuario.nombre) return;
        
        const navbarNav = document.querySelector('#navbarNav .navbar-nav');
        if (!navbarNav) return;
        
        // Buscar el botón de login o el dropdown existente
        const loginButton = navbarNav.querySelector('.btn-login, .btn-primary');
        const existingDropdown = navbarNav.querySelector('.user-menu');
        
        if (existingDropdown) {
            // Actualizar el nombre en el dropdown existente
            const userNameNav = existingDropdown.querySelector('.user-name-nav');
            const dropdownHeader = existingDropdown.closest('.dropdown').querySelector('.dropdown-header');
            if (userNameNav) userNameNav.textContent = usuario.nombre;
            if (dropdownHeader) dropdownHeader.innerHTML = `<i class="fas fa-user me-2"></i>${usuario.nombre}`;
            return;
        }
        
        if (!loginButton) return;
        
        // Crear el dropdown del usuario
        const userDropdown = document.createElement('li');
        userDropdown.className = 'nav-item dropdown ms-lg-3';
        userDropdown.innerHTML = `
            <a class="nav-link dropdown-toggle user-menu" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <div class="user-avatar-nav">
                    <i class="fas fa-user-circle"></i>
                </div>
                <span class="user-name-nav">${usuario.nombre}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li>
                    <h6 class="dropdown-header">
                        <i class="fas fa-user me-2"></i>${usuario.nombre}
                    </h6>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item" href="/panel/${usuario.tipo || 'turista'}">
                        <i class="fas fa-tachometer-alt me-2"></i>Mi Panel
                    </a>
                </li>
                <li>
                    <a class="dropdown-item" href="/perfil">
                        <i class="fas fa-user-edit me-2"></i>Mi Perfil
                    </a>
                </li>
                <li><hr class="dropdown-divider"></li>
                <li>
                    <a class="dropdown-item text-danger" href="/logout">
                        <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
                    </a>
                </li>
            </ul>
        `;
        
        // Reemplazar el botón de login con el dropdown
        loginButton.closest('li').replaceWith(userDropdown);
        
        // Actualizar meta tag de autenticación
        let metaAuth = document.querySelector('meta[name="usuario-autenticado"]');
        if (!metaAuth) {
            metaAuth = document.createElement('meta');
            metaAuth.name = 'usuario-autenticado';
            document.head.appendChild(metaAuth);
        }
        metaAuth.content = 'true';
        
        // Actualizar sessionStorage
        if (typeof sessionStorage !== 'undefined') {
            sessionStorage.setItem('autenticado', 'true');
        }
    }

    // ============================================
    // UTILIDADES
    // ============================================
    
    // Función para compartir en redes sociales
    window.shareContent = function(platform, url, text) {
        const shareUrls = {
            facebook: `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`,
            twitter: `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(text)}`,
            whatsapp: `https://wa.me/?text=${encodeURIComponent(text + ' ' + url)}`
        };

        if (shareUrls[platform]) {
            window.open(shareUrls[platform], '_blank', 'width=600,height=400');
        }
    };

    // Función para cerrar modales al hacer clic fuera
    document.querySelectorAll('.modal').forEach(modal => {
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                const bsModal = bootstrap.Modal.getInstance(this);
                if (bsModal) {
                    bsModal.hide();
                }
            }
        });
    });

    // ============================================
    // CARGAR PUBLICACIONES DINÁMICAMENTE
    // ============================================
    function cargarPublicaciones() {
        const container = document.getElementById('experienciasContainer');
        if (!container) return;

        fetch('/api/publicaciones?limit=8')
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data && data.data.length > 0) {
                    mostrarPublicacionesEnInicio(data.data, container);
                }
                // Si no hay publicaciones, se mantienen las experiencias estáticas
            })
            .catch(error => {
                console.error('Error al cargar publicaciones:', error);
                // Si hay error, se mantienen las experiencias estáticas
            });
    }

    // ============================================
    // RESERVAS - Funcionalidad completa
    // ============================================
    let reservaActual = null;

    // Inicializar sistema de reservas
    function initReservas() {
        // Conectar todos los botones de reserva en modales
        conectarBotonesReserva();
        
        // Inicializar formulario de reserva
        initFormularioReserva();
        
        // Verificar autenticación al abrir modal
        const reservaModal = document.getElementById('reservaModal');
        if (reservaModal) {
            reservaModal.addEventListener('show.bs.modal', function() {
                verificarAutenticacionReserva();
            });
        }
    }

    // Conectar botones de reserva en todos los modales
    function conectarBotonesReserva() {
        // Usar delegación de eventos para capturar clics en botones de reserva
        document.addEventListener('click', function(e) {
            const btn = e.target.closest('button');
            if (!btn) return;

            const texto = btn.textContent.trim();
            const esReserva = texto.includes('Reservar Experiencia') || 
                             texto.includes('Reservar Tour') || 
                             texto.includes('Reservar cupo');

            if (esReserva && btn.classList.contains('btn-primary')) {
                e.preventDefault();
                e.stopPropagation();

                const modal = btn.closest('.modal');
                const titulo = modal?.querySelector('.modal-title')?.textContent.trim() || 'Experiencia';
                const experienciaId = modal?.getAttribute('data-experiencia-id');
                
                // Cerrar el modal actual
                if (modal) {
                    const bsModal = bootstrap.Modal.getInstance(modal);
                    if (bsModal) {
                        bsModal.hide();
                    }
                }

                // Abrir modal de reserva
                setTimeout(() => {
                    abrirModalReserva(titulo, experienciaId);
                }, 300);
            }
        });

        // También conectar botones que puedan tener eventos ya asignados
        setTimeout(() => {
            document.querySelectorAll('.modal button.btn-primary').forEach(btn => {
                const texto = btn.textContent.trim();
                if (texto.includes('Reservar Experiencia') || texto.includes('Reservar Tour') || texto.includes('Reservar cupo')) {
                    if (!btn.hasAttribute('data-reserva-conectado')) {
                        btn.setAttribute('data-reserva-conectado', 'true');
                        btn.addEventListener('click', function(e) {
                            if (this.type === 'button' && !this.getAttribute('data-bs-dismiss')) {
                                e.preventDefault();
                                e.stopPropagation();
                                
                                const modal = this.closest('.modal');
                                const titulo = modal?.querySelector('.modal-title')?.textContent.trim() || 'Experiencia';
                                const experienciaId = modal?.getAttribute('data-experiencia-id');
                                
                                // Cerrar el modal actual
                                if (modal) {
                                    const bsModal = bootstrap.Modal.getInstance(modal);
                                    if (bsModal) {
                                        bsModal.hide();
                                    }
                                }

                                // Abrir modal de reserva
                                setTimeout(() => {
                                    abrirModalReserva(titulo, experienciaId);
                                }, 300);
                            }
                        });
                    }
                }
            });
        }, 500);
    }

    // Abrir modal de reserva con información de la experiencia
    function abrirModalReserva(titulo, experienciaId) {
        reservaActual = {
            titulo: titulo,
            id: experienciaId
        };

        // Si hay ID, cargar datos completos de la experiencia
        if (experienciaId) {
            cargarDatosExperiencia(experienciaId);
        } else {
            // Usar datos básicos
            document.getElementById('reservaExperienciaTitulo').textContent = titulo;
            document.getElementById('reservaExperienciaPrecio').textContent = 'Precio a consultar';
        }

        // Mostrar modal
        const reservaModal = new bootstrap.Modal(document.getElementById('reservaModal'));
        reservaModal.show();
    }

    // Cargar datos completos de la experiencia desde la API
    function cargarDatosExperiencia(experienciaId) {
        fetch(`/api/publicaciones/${experienciaId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data) {
                    const exp = data.data;
                    reservaActual = {
                        ...reservaActual,
                        ...exp
                    };
                    actualizarFormularioReserva(exp);
                }
            })
            .catch(error => {
                console.error('Error al cargar experiencia:', error);
            });
    }

    // Actualizar formulario con datos de la experiencia
    function actualizarFormularioReserva(experiencia) {
        if (!experiencia) return;

        const idInput = document.getElementById('reservaExperienciaId');
        const tituloEl = document.getElementById('reservaExperienciaTitulo');
        const ubicacionEl = document.getElementById('reservaExperienciaUbicacion');
        const precioEl = document.getElementById('reservaExperienciaPrecio');
        const imagen = document.getElementById('reservaExperienciaImagen');

        if (idInput) {
            idInput.value = experiencia.id_publicacion || experiencia.id || '';
        }
        
        if (tituloEl) {
            tituloEl.textContent = experiencia.titulo_publicacion || experiencia.titulo || 'Experiencia';
        }
        
        if (ubicacionEl) {
            ubicacionEl.textContent = experiencia.ubicacion || 'Quibdó, Chocó';
        }
        
        if (precioEl) {
            precioEl.textContent = `$${Number(experiencia.precio_aproximado || 0).toLocaleString()}`;
        }
        
        if (imagen) {
            if (experiencia.imagen_url) {
                imagen.src = experiencia.imagen_url;
                imagen.alt = experiencia.titulo_publicacion || experiencia.titulo || 'Experiencia';
            } else {
                imagen.src = 'https://via.placeholder.com/80x80?text=Imagen';
            }
        }

        // Establecer fecha mínima del evento si existe
        const fechaInput = document.getElementById('reservaFecha');
        if (fechaInput) {
            const today = new Date().toISOString().split('T')[0];
            fechaInput.min = today;
            if (experiencia.fecha_evento) {
                fechaInput.value = experiencia.fecha_evento.split('T')[0];
            }
        }

        // Actualizar capacidad máxima
        const capacidadInfo = document.getElementById('reservaCapacidadInfo');
        const personasInput = document.getElementById('reservaPersonas');
        if (experiencia.capacidad_maxima) {
            if (capacidadInfo) capacidadInfo.textContent = `Máximo ${experiencia.capacidad_maxima} personas`;
            if (personasInput) personasInput.max = experiencia.capacidad_maxima;
        } else {
            if (capacidadInfo) capacidadInfo.textContent = '';
            if (personasInput) personasInput.max = 50;
        }

        // Actualizar precio base
        reservaActual.precioBase = Number(experiencia.precio_aproximado || 0);
        actualizarTotalReserva();
    }

    // Verificar autenticación antes de mostrar formulario
    function verificarAutenticacionReserva() {
        const autenticado = document.querySelector('meta[name="usuario-autenticado"]')?.content === 'true' || 
                           sessionStorage.getItem('autenticado') === 'true' ||
                           document.querySelector('.user-menu') !== null;

        const noAuthMsg = document.getElementById('reservaNoAuthMessage');
        const formReserva = document.getElementById('formReserva');
        const btnConfirmar = document.getElementById('btnConfirmarReserva');

        if (!autenticado) {
            if (noAuthMsg) noAuthMsg.style.display = 'block';
            if (formReserva) formReserva.style.display = 'none';
            if (btnConfirmar) btnConfirmar.style.display = 'none';
        } else {
            if (noAuthMsg) noAuthMsg.style.display = 'none';
            if (formReserva) formReserva.style.display = 'block';
            if (btnConfirmar) btnConfirmar.style.display = 'block';
        }
    }

    // Inicializar formulario de reserva
    function initFormularioReserva() {
        const form = document.getElementById('formReserva');
        const btnConfirmar = document.getElementById('btnConfirmarReserva');
        const fechaInput = document.getElementById('reservaFecha');
        const personasInput = document.getElementById('reservaPersonas');
        const countButtons = document.querySelectorAll('.btn-count');

        // Establecer fecha mínima
        if (fechaInput) {
            const today = new Date().toISOString().split('T')[0];
            fechaInput.min = today;
        }

        // Botones de incremento/decremento
        countButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                const current = parseInt(personasInput.value) || 1;
                const max = parseInt(personasInput.max) || 50;

                if (action === 'increase' && current < max) {
                    personasInput.value = current + 1;
                    actualizarTotalReserva();
                } else if (action === 'decrease' && current > 1) {
                    personasInput.value = current - 1;
                    actualizarTotalReserva();
                }
            });
        });

        // Actualizar total cuando cambian los valores
        if (personasInput) {
            personasInput.addEventListener('change', function() {
                const max = parseInt(this.max) || 50;
                if (parseInt(this.value) > max) {
                    this.value = max;
                } else if (parseInt(this.value) < 1) {
                    this.value = 1;
                }
                actualizarTotalReserva();
            });
        }

        if (fechaInput) {
            fechaInput.addEventListener('change', function() {
                verificarDisponibilidad();
            });
        }

        // Enviar reserva
        if (btnConfirmar) {
            btnConfirmar.addEventListener('click', function() {
                enviarReserva();
            });
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                enviarReserva();
            });
        }
    }

    // Actualizar total de la reserva
    function actualizarTotalReserva() {
        const personas = parseInt(document.getElementById('reservaPersonas')?.value) || 1;
        const precioBase = reservaActual?.precioBase || 0;
        const subtotal = precioBase * personas;

        document.getElementById('reservaResumenPersonas').textContent = `${personas} ${personas === 1 ? 'persona' : 'personas'} x $${precioBase.toLocaleString()}`;
        document.getElementById('reservaSubtotal').textContent = `$${subtotal.toLocaleString()}`;
        document.getElementById('reservaTotal').textContent = `$${subtotal.toLocaleString()}`;
    }

    // Verificar disponibilidad
    function verificarDisponibilidad() {
        const experienciaId = document.getElementById('reservaExperienciaId')?.value;
        const fecha = document.getElementById('reservaFecha')?.value;
        const personas = parseInt(document.getElementById('reservaPersonas')?.value) || 1;

        if (!experienciaId || !fecha) return;

        // Aquí se podría hacer una verificación con la API
        // Por ahora solo validamos que la fecha sea válida
        const fechaSeleccionada = new Date(fecha);
        const hoy = new Date();
        hoy.setHours(0, 0, 0, 0);

        if (fechaSeleccionada < hoy) {
            mostrarAlertaReserva('danger', 'La fecha debe ser hoy o en el futuro.');
        }
    }

    // Enviar reserva
    function enviarReserva() {
        const form = document.getElementById('formReserva');
        if (!form) return;

        const experienciaId = document.getElementById('reservaExperienciaId')?.value;
        const fecha = document.getElementById('reservaFecha')?.value;
        const personas = document.getElementById('reservaPersonas')?.value;
        const contacto = document.getElementById('reservaContactoEmergencia')?.value;
        const telefono = document.getElementById('reservaTelefonoEmergencia')?.value;
        const detalles = document.getElementById('reservaDetalles')?.value;

        // Validaciones
        if (!experienciaId) {
            mostrarAlertaReserva('danger', 'No se pudo identificar la experiencia. Por favor, inténtalo de nuevo.');
            return;
        }

        if (!fecha) {
            mostrarAlertaReserva('danger', 'Por favor, selecciona una fecha para la experiencia.');
            return;
        }

        if (!personas || parseInt(personas) < 1) {
            mostrarAlertaReserva('danger', 'El número de personas debe ser al menos 1.');
            return;
        }

        // Verificar autenticación mediante el navbar
        const userMenu = document.querySelector('.user-menu') || document.querySelector('.navbar-nav .user-menu');
        const autenticado = userMenu !== null;
        
        if (!autenticado) {
            mostrarAlertaReserva('warning', 'Debes iniciar sesión para realizar una reserva.');
            setTimeout(() => {
                bootstrap.Modal.getInstance(document.getElementById('reservaModal')).hide();
                bootstrap.Modal.getInstance(document.getElementById('loginModal')).show();
            }, 2000);
            return;
        }

        const btnConfirmar = document.getElementById('btnConfirmarReserva');
        const originalText = btnConfirmar.innerHTML;
        btnConfirmar.disabled = true;
        btnConfirmar.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Procesando...';

        const datosReserva = {
            id_experiencia: experienciaId,
            fecha_evento: fecha,
            cantidad_personas: parseInt(personas),
            contacto_emergencia: contacto || null,
            telefono_emergencia: telefono || null,
            detalles: detalles || null
        };

        fetch('/api/reservas', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(datosReserva)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlertaReserva('success', data.message || '¡Reserva realizada exitosamente!');
                
                // Limpiar formulario
                form.reset();
                document.getElementById('reservaPersonas').value = 1;
                actualizarTotalReserva();

                // Cerrar modal después de 2 segundos
                setTimeout(() => {
                    bootstrap.Modal.getInstance(document.getElementById('reservaModal')).hide();
                    mostrarAlertaGlobal('success', 'Tu reserva ha sido registrada. El prestador la revisará pronto.');
                }, 2000);
            } else {
                mostrarAlertaReserva('danger', data.message || 'Error al realizar la reserva. Por favor, inténtalo de nuevo.');
                btnConfirmar.disabled = false;
                btnConfirmar.innerHTML = originalText;
            }
        })
        .catch(error => {
            console.error('Error al enviar reserva:', error);
            mostrarAlertaReserva('danger', 'Error de conexión. Por favor, verifica tu conexión a internet e inténtalo de nuevo.');
            btnConfirmar.disabled = false;
            btnConfirmar.innerHTML = originalText;
        });
    }

    // Mostrar alerta en el modal de reserva
    function mostrarAlertaReserva(tipo, mensaje) {
        const alertContainer = document.getElementById('reservaDisponibilidadAlert');
        if (alertContainer) {
            alertContainer.className = `alert alert-${tipo}`;
            alertContainer.querySelector('#reservaDisponibilidadTexto').textContent = mensaje;
            alertContainer.classList.remove('d-none');
            
            // Scroll al alerta
            alertContainer.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
    }

    // Mostrar alerta global
    function mostrarAlertaGlobal(tipo, mensaje) {
        // Crear contenedor de alertas si no existe
        let alertContainer = document.getElementById('alertContainer');
        if (!alertContainer) {
            alertContainer = document.createElement('div');
            alertContainer.id = 'alertContainer';
            alertContainer.className = 'position-fixed top-0 end-0 p-3';
            alertContainer.style.zIndex = '9999';
            document.body.appendChild(alertContainer);
        }

        const alertId = 'alert-' + Date.now();
        const alertHtml = `
            <div id="${alertId}" class="alert alert-${tipo} alert-dismissible fade show" role="alert" style="min-width: 300px;">
                ${mensaje}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
        alertContainer.insertAdjacentHTML('beforeend', alertHtml);

        // Auto-ocultar después de 5 segundos
        setTimeout(() => {
            const alert = document.getElementById(alertId);
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 5000);
    }

    // Hacer funciones globales
    window.abrirModalReserva = abrirModalReserva;

    function mostrarPublicacionesEnInicio(publicaciones, container) {
        // Limpiar solo las experiencias dinámicas, mantener las estáticas si no hay suficientes
        let html = '';
        
        publicaciones.slice(0, 8).forEach((publicacion, index) => {
            const categoriaClass = {
                'naturaleza': 'badge-nature',
                'cultura': 'badge-culture',
                'gastronomia': 'badge-gastronomy',
                'aventura': 'badge-adventure',
                'relax': 'badge-relax',
                'eventos': 'badge-events'
            }[publicacion.categoria] || 'badge-nature';

            const categoriaIcon = {
                'naturaleza': 'fa-tree',
                'cultura': 'fa-book',
                'gastronomia': 'fa-utensils',
                'aventura': 'fa-mountain',
                'relax': 'fa-spa',
                'eventos': 'fa-calendar'
            }[publicacion.categoria] || 'fa-star';

            html += `
                <div class="col-lg-3 col-md-6">
                    <div class="experience-card">
                        <div class="card-image-wrapper">
                            ${publicacion.url_multimedia ? 
                                `<video class="card-image" loop playsinline muted>
                                    <source src="${publicacion.url_multimedia}" type="video/mp4">
                                </video>` :
                                `<img src="${publicacion.imagen_url || 'https://via.placeholder.com/400x250?text=Sin+imagen'}" 
                                     class="card-image" 
                                     alt="${publicacion.titulo_publicacion}" 
                                     onerror="this.src='https://via.placeholder.com/400x250?text=Sin+imagen'">`
                            }
                            <div class="card-overlay">
                                <a href="/experiencia/${publicacion.id_publicacion}" class="btn btn-sm btn-light me-1">
                                    <i class="fas fa-eye me-1"></i>Ver Experiencia
                                </a>
                                <button class="btn btn-sm btn-primary" onclick="abrirModalReserva('${publicacion.titulo_publicacion}', '${publicacion.id_publicacion}')">
                                    <i class="fas fa-calendar-check me-1"></i>Reservar
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">${publicacion.titulo_publicacion}</h5>
                            <p class="card-text">${(publicacion.descripcion || '').substring(0, 80)}${(publicacion.descripcion || '').length > 80 ? '...' : ''}</p>
                            <div class="card-footer-experiencia">
                                <span class="badge ${categoriaClass}">
                                    <i class="fas ${categoriaIcon} me-1"></i>${publicacion.categoria ? publicacion.categoria.charAt(0).toUpperCase() + publicacion.categoria.slice(1) : 'General'}
                                </span>
                                <div class="card-rating">
                                    <span class="rating-value">${publicacion.calificacion_promedio || 0}</span>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });

        // Insertar las publicaciones al inicio del contenedor
        if (html) {
            container.insertAdjacentHTML('afterbegin', html);
        }
    }

})();


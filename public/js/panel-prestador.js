/**
 * Panel de Prestador - JavaScript Completo
 * ExploraQuibdó
 */

(function() {
    'use strict';

    // Variables globales
    let charts = {};
    let currentPublicacionId = null;
    let currentReservaId = null;

    document.addEventListener('DOMContentLoaded', function() {
        initNavigation();
        initSidebarToggle();
        initCharts();
        initDashboard();
        initPublicaciones();
        initNuevaPublicacion();
        initReservas();
        initMetricas();
        initComentarios();
        initPerfil();
        initLogout();
        loadInitialData();
    });

    // ============================================
    // NAVEGACIÓN
    // ============================================
    function initNavigation() {
        const navItems = document.querySelectorAll('.sidebar-nav .nav-item[data-section]');
        const sections = document.querySelectorAll('.content-section');
        const pageTitle = document.getElementById('pageTitle');

        const sectionTitles = {
            'dashboard': 'Dashboard',
            'publicaciones': 'Mis Publicaciones',
            'nueva-publicacion': 'Nueva Publicación',
            'reservas': 'Reservas Recibidas',
            'metricas': 'Métricas',
            'comentarios': 'Comentarios',
            'perfil': 'Mi Perfil'
        };

        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                const targetSection = this.getAttribute('data-section');
                
                // Actualizar navegación
                navItems.forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                
                // Mostrar sección
                sections.forEach(section => section.classList.remove('active'));
                const targetElement = document.getElementById(`section-${targetSection}`);
                if (targetElement) {
                    targetElement.classList.add('active');
                    
                    // Cargar datos de la sección
                    loadSectionData(targetSection);
                }
                
                // Actualizar título
                if (pageTitle && sectionTitles[targetSection]) {
                    pageTitle.textContent = sectionTitles[targetSection];
                }
            });
        });

        // Botón nueva publicación
        const btnNuevaPublicacion = document.getElementById('btnNuevaPublicacion');
        if (btnNuevaPublicacion) {
            btnNuevaPublicacion.addEventListener('click', function() {
                showSection('nueva-publicacion');
            });
        }
    }

    // ============================================
    // TOGGLE SIDEBAR (Responsive)
    // ============================================
    function initSidebarToggle() {
        const btnToggle = document.getElementById('btnToggleSidebar');
        const sidebar = document.getElementById('sidebarPrestador');
        
        if (btnToggle && sidebar) {
            btnToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }

        // Mostrar botón en móviles
        window.addEventListener('resize', function() {
            if (window.innerWidth <= 991) {
                if (btnToggle) btnToggle.style.display = 'block';
            } else {
                if (btnToggle) btnToggle.style.display = 'none';
            }
        });

        if (window.innerWidth <= 991) {
            if (btnToggle) btnToggle.style.display = 'block';
        }
    }

    // ============================================
    // DASHBOARD
    // ============================================
    function initDashboard() {
        // Se carga automáticamente al entrar
    }

    function cargarDashboard() {
        fetch('/api/prestador/estadisticas')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    actualizarEstadisticas(data.data);
                    cargarReservasRecientes();
                    cargarActividadReciente();
                }
            })
            .catch(error => {
                console.error('Error al cargar dashboard:', error);
            });
    }

    function actualizarEstadisticas(stats) {
        const statVisualizaciones = document.getElementById('statVisualizaciones');
        const statReservasActivas = document.getElementById('statReservasActivas');
        const statCalificacion = document.getElementById('statCalificacion');
        const statIngresos = document.getElementById('statIngresos');

        if (statVisualizaciones) {
            statVisualizaciones.textContent = stats.total_visitas || 0;
        }
        if (statReservasActivas) {
            statReservasActivas.textContent = stats.reservas_confirmadas || 0;
        }
        if (statCalificacion) {
            statCalificacion.textContent = stats.calificacion_promedio || '0.0';
        }
        if (statIngresos) {
            const ingresos = stats.ingresos_estimados || 0;
            statIngresos.textContent = formatearMoneda(ingresos);
        }
    }

    function cargarReservasRecientes() {
        fetch('/api/reservas?estado=confirmada&limit=5')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarReservasRecientes(data.data.slice(0, 5));
                }
            })
            .catch(error => {
                console.error('Error al cargar reservas recientes:', error);
            });
    }

    function mostrarReservasRecientes(reservas) {
        const tbody = document.getElementById('reservasRecientesTable');
        if (!tbody) return;

        if (reservas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">No hay reservas recientes</td></tr>';
            return;
        }

        let html = '';
        reservas.forEach(reserva => {
            const estadoClass = reserva.estado_reserva === 'confirmada' ? 'success' : 
                              reserva.estado_reserva === 'pendiente' ? 'warning' : 'secondary';
            const estadoText = reserva.estado_reserva === 'confirmada' ? 'Confirmada' : 
                             reserva.estado_reserva === 'pendiente' ? 'Pendiente' : reserva.estado_reserva;

            html += `
                <tr>
                    <td>${reserva.usuario?.nombre || 'N/A'}</td>
                    <td>${reserva.experiencia?.titulo || 'N/A'}</td>
                    <td>${formatearFecha(reserva.fecha_evento)}</td>
                    <td>${reserva.cantidad_personas || 0}</td>
                    <td><span class="badge bg-${estadoClass}">${estadoText}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="verReserva('${reserva.id_reserva}')">
                            Ver
                        </button>
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    function cargarActividadReciente() {
        // Simular actividad reciente (luego se conectará con API real)
        const timeline = document.getElementById('activityTimeline');
        if (timeline) {
            timeline.innerHTML = `
                <div class="timeline-item">
                    <div class="timeline-marker bg-success"></div>
                    <div class="timeline-content">
                        <h6>Nueva reserva recibida</h6>
                        <p class="text-muted mb-0">Una nueva reserva ha sido confirmada</p>
                        <small class="text-muted">Hace 2 horas</small>
                    </div>
                </div>
                <div class="timeline-item">
                    <div class="timeline-marker bg-warning"></div>
                    <div class="timeline-content">
                        <h6>Nueva reseña publicada</h6>
                        <p class="text-muted mb-0">Un cliente ha dejado una reseña en tu experiencia</p>
                        <small class="text-muted">Hace 5 horas</small>
                    </div>
                </div>
            `;
        }
    }

    // ============================================
    // PUBLICACIONES
    // ============================================
    function initPublicaciones() {
        const searchInput = document.getElementById('searchPublicaciones');
        const filterSelect = document.getElementById('filterEstadoPublicacion');

        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    cargarPublicaciones();
                }, 500);
            });
        }

        if (filterSelect) {
            filterSelect.addEventListener('change', function() {
                cargarPublicaciones();
            });
        }
    }

    function cargarPublicaciones() {
        const container = document.getElementById('publicacionesList');
        if (!container) return;

        const estado = document.getElementById('filterEstadoPublicacion')?.value || 'todos';
        const busqueda = document.getElementById('searchPublicaciones')?.value || '';

        container.innerHTML = '<div class="col-12"><div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div></div>';

        let url = `/api/prestador/publicaciones?estado=${estado}`;
        if (busqueda) {
            url += `&busqueda=${encodeURIComponent(busqueda)}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarPublicaciones(data.data, container);
                } else {
                    container.innerHTML = '<div class="col-12"><p class="text-muted text-center py-5">Error al cargar publicaciones</p></div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="col-12"><p class="text-muted text-center py-5">Error al cargar publicaciones</p></div>';
            });
    }

    function mostrarPublicaciones(publicaciones, container) {
        if (publicaciones.length === 0) {
            container.innerHTML = '<div class="col-12"><p class="text-muted text-center py-5">No tienes publicaciones aún. <a href="#" onclick="showSection(\'nueva-publicacion\')">Crea tu primera publicación</a></p></div>';
            return;
        }

        let html = '';
        publicaciones.forEach(publicacion => {
            const estadoClass = publicacion.estado_publicacion === 'activo' ? 'success' : 
                              publicacion.estado_publicacion === 'pendiente' ? 'warning' : 'secondary';
            const estadoText = publicacion.estado_publicacion === 'activo' ? 'Activa' : 
                             publicacion.estado_publicacion === 'pendiente' ? 'Pendiente' : 'Rechazada';

            html += `
                <div class="col-lg-4 col-md-6">
                    <div class="publicacion-card">
                        <div class="publicacion-image">
                            <img src="${publicacion.imagen_url || 'https://via.placeholder.com/400x250?text=Sin+imagen'}" alt="${publicacion.titulo_publicacion}" onerror="this.src='https://via.placeholder.com/400x250?text=Sin+imagen'">
                            <div class="publicacion-status">
                                <span class="badge bg-${estadoClass}">${estadoText}</span>
                            </div>
                        </div>
                        <div class="publicacion-body">
                            <h5>${publicacion.titulo_publicacion}</h5>
                            <div class="publicacion-meta">
                                <span><i class="fas fa-eye me-1"></i>${publicacion.cantidad_visitas || 0} vistas</span>
                                <span><i class="fas fa-calendar-check me-1"></i>${publicacion.total_reservas || 0} reservas</span>
                                ${publicacion.calificacion_promedio > 0 ? `<span><i class="fas fa-star me-1"></i>${publicacion.calificacion_promedio}</span>` : ''}
                            </div>
                            <div class="publicacion-actions">
                                <button class="btn btn-sm btn-outline-info" onclick="window.open('/experiencia/${publicacion.id_publicacion}', '_blank')">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </button>
                                <button class="btn btn-sm btn-outline-primary" onclick="editarPublicacion('${publicacion.id_publicacion}')">
                                    <i class="fas fa-edit me-1"></i>Editar
                                </button>
                                <button class="btn btn-sm btn-outline-danger" onclick="eliminarPublicacion('${publicacion.id_publicacion}')">
                                    <i class="fas fa-trash me-1"></i>Eliminar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        container.innerHTML = html;
    }

    // ============================================
    // NUEVA PUBLICACIÓN
    // ============================================
    function initNuevaPublicacion() {
        const form = document.getElementById('formNuevaPublicacion');
        if (!form) return;

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            crearPublicacion();
        });
    }

    function crearPublicacion() {
        const form = document.getElementById('formNuevaPublicacion');
        if (!form) return;

        // Validar campos antes de enviar
        const titulo = document.getElementById('tituloPublicacion').value.trim();
        const descripcion = document.getElementById('descripcionPublicacion').value.trim();
        const categoria = document.getElementById('categoriaPublicacion').value;
        const ubicacion = document.getElementById('ubicacionPublicacion').value.trim();
        const precio = document.getElementById('precioPublicacion').value;
        const imagenFile = document.getElementById('imagenFile').files[0];
        const imagenUrl = document.getElementById('imagenUrl').value.trim();
        
        // Validaciones básicas
        if (!titulo) {
            mostrarAlerta('warning', 'El título es obligatorio');
            return;
        }
        
        if (!descripcion || descripcion.length < 50) {
            mostrarAlerta('warning', 'La descripción debe tener al menos 50 caracteres');
            return;
        }
        
        if (!categoria) {
            mostrarAlerta('warning', 'Debes seleccionar una categoría');
            return;
        }
        
        if (!ubicacion) {
            mostrarAlerta('warning', 'La ubicación es obligatoria');
            return;
        }
        
        if (!precio || parseFloat(precio) < 0) {
            mostrarAlerta('warning', 'El precio es obligatorio y debe ser mayor o igual a 0');
            return;
        }
        
        if (!imagenFile && !imagenUrl) {
            mostrarAlerta('warning', 'Debes subir una imagen o proporcionar una URL de imagen');
            return;
        }

        const submitButton = form.querySelector('button[type="submit"]');
        const originalText = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Publicando...';

        const formData = new FormData(form);
        
        // Limpiar campos vacíos del FormData
        if (!imagenUrl) {
            formData.delete('imagen_url');
        }
        
        if (!imagenFile) {
            formData.delete('imagen');
        }
        
        const videoFile = document.getElementById('videoFile').files[0];
        const videoUrl = document.getElementById('videoUrl').value.trim();
        
        if (!videoFile) {
            formData.delete('video');
        }
        
        if (!videoUrl) {
            formData.delete('url_multimedia');
        }

        fetch('/api/publicaciones', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                // NO incluir 'Content-Type' para FormData, el navegador lo hace automáticamente
            },
            body: formData
        })
        .then(response => {
            // Manejar respuesta que puede ser JSON o texto
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                return response.text().then(text => {
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        console.error('Error parsing response:', text);
                        return { success: false, message: 'Error en la respuesta del servidor: ' + text };
                    }
                });
            }
        })
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                // Limpiar previews
                const imagenPreview = document.getElementById('imagenPreview');
                const videoPreview = document.getElementById('videoPreview');
                if (imagenPreview) imagenPreview.style.display = 'none';
                if (videoPreview) videoPreview.style.display = 'none';
                form.reset();
                showSection('publicaciones');
                cargarPublicaciones();
            } else {
                // Mostrar errores de validación si existen
                let mensajeError = data.message || 'Error al crear publicación';
                if (data.errors) {
                    const errores = Object.values(data.errors).flat();
                    mensajeError = '<strong>Errores de validación:</strong><br>' + errores.join('<br>');
                }
                mostrarAlerta('danger', mensajeError);
                console.error('Errores de validación:', data.errors);
            }
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('danger', 'Error al procesar la solicitud: ' + error.message);
            submitButton.disabled = false;
            submitButton.innerHTML = originalText;
        });
    }

    // ============================================
    // RESERVAS
    // ============================================
    function initReservas() {
        const filterSelect = document.getElementById('filterEstadoReserva');
        if (filterSelect) {
            filterSelect.addEventListener('change', function() {
                cargarReservas();
            });
        }
    }

    function cargarReservas() {
        const tbody = document.getElementById('reservasTableBody');
        if (!tbody) return;

        tbody.innerHTML = '<tr><td colspan="7" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></td></tr>';

        const estado = document.getElementById('filterEstadoReserva')?.value || '';

        let url = '/api/reservas';
        if (estado) {
            url += `?estado=${estado}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarReservas(data.data, tbody);
                } else {
                    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-muted">Error al cargar reservas</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-muted">Error al cargar reservas</td></tr>';
            });
    }

    function mostrarReservas(reservas, tbody) {
        if (reservas.length === 0) {
            tbody.innerHTML = '<tr><td colspan="7" class="text-center py-5 text-muted">No hay reservas</td></tr>';
            return;
        }

        let html = '';
        reservas.forEach(reserva => {
            const estadoClass = reserva.estado_reserva === 'confirmada' ? 'success' : 
                              reserva.estado_reserva === 'pendiente' ? 'warning' : 
                              reserva.estado_reserva === 'cancelada' ? 'danger' : 'secondary';
            const estadoText = reserva.estado_reserva === 'confirmada' ? 'Confirmada' : 
                             reserva.estado_reserva === 'pendiente' ? 'Pendiente' : 
                             reserva.estado_reserva === 'cancelada' ? 'Cancelada' : 'Completada';

            html += `
                <tr>
                    <td>${reserva.usuario?.nombre || 'N/A'}</td>
                    <td>${reserva.experiencia?.titulo || 'N/A'}</td>
                    <td>${formatearFecha(reserva.fecha_evento)}</td>
                    <td>${reserva.cantidad_personas || 0}</td>
                    <td>${formatearMoneda(reserva.precio_total || 0)}</td>
                    <td><span class="badge bg-${estadoClass}">${estadoText}</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="verReserva('${reserva.id_reserva}')">
                            Ver
                        </button>
                        ${reserva.estado_reserva === 'pendiente' ? `
                            <button class="btn btn-sm btn-success" onclick="confirmarReserva('${reserva.id_reserva}')">
                                Confirmar
                            </button>
                        ` : ''}
                    </td>
                </tr>
            `;
        });
        tbody.innerHTML = html;
    }

    // ============================================
    // MÉTRICAS
    // ============================================
    function initMetricas() {
        // Los gráficos se cargarán cuando se acceda a la sección
    }

    function cargarMetricas() {
        fetch('/api/prestador/metricas')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    actualizarGraficosMetricas(data.data);
                }
            })
            .catch(error => {
                console.error('Error al cargar métricas:', error);
            });
    }

    function actualizarGraficosMetricas(metricas) {
        // Gráfico de visualizaciones
        const viewsCtx = document.getElementById('viewsChart');
        if (viewsCtx && typeof Chart !== 'undefined') {
            if (charts.views) {
                charts.views.destroy();
            }

            const labels = metricas.map(m => m.titulo?.substring(0, 15) || 'Sin título');
            const datos = metricas.map(m => m.visitas || 0);

            charts.views = new Chart(viewsCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Visualizaciones',
                        data: datos,
                        backgroundColor: '#0067a3',
                        borderRadius: 8
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        // Gráfico de categorías
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx && typeof Chart !== 'undefined') {
            if (charts.category) {
                charts.category.destroy();
            }

            // Agrupar por categoría (simplificado)
            const categorias = ['Naturaleza', 'Cultura', 'Gastronomía', 'Aventura'];
            const valores = [25, 30, 20, 25];

            charts.category = new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categorias,
                    datasets: [{
                        data: valores,
                        backgroundColor: ['#0067a3', '#2ecc71', '#f39c12', '#e74c3c']
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }
    }

    // ============================================
    // COMENTARIOS
    // ============================================
    function initComentarios() {
        // Se cargarán cuando se acceda a la sección
    }

    function cargarComentarios() {
        const container = document.getElementById('comentariosList');
        if (!container) return;

        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';

        // Aquí se cargarían los comentarios desde la API
        setTimeout(() => {
            container.innerHTML = '<div class="text-center py-5 text-muted">No hay comentarios aún</div>';
        }, 1000);
    }

    // ============================================
    // PERFIL
    // ============================================
    function initPerfil() {
        const form = document.getElementById('formPerfilPrestador');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                guardarPerfil();
            });
        }
    }

    function guardarPerfil() {
        // Implementar guardado de perfil
        mostrarAlerta('success', 'Perfil actualizado exitosamente');
    }

    // ============================================
    // GRÁFICOS
    // ============================================
    function initCharts() {
        // Los gráficos se inicializarán cuando se acceda a la sección de métricas
    }

    // ============================================
    // LOGOUT
    // ============================================
    function initLogout() {
        const logoutBtn = document.querySelector('.logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('¿Estás seguro de cerrar sesión?')) {
                    window.location.href = '/logout';
                }
            });
        }
    }

    // ============================================
    // FUNCIONES AUXILIARES
    // ============================================
    function loadInitialData() {
        cargarDashboard();
        cargarPublicaciones();
    }

    function loadSectionData(section) {
        switch(section) {
            case 'dashboard':
                cargarDashboard();
                break;
            case 'publicaciones':
                cargarPublicaciones();
                break;
            case 'reservas':
                cargarReservas();
                break;
            case 'metricas':
                cargarMetricas();
                break;
            case 'comentarios':
                cargarComentarios();
                break;
        }
    }

    function showSection(section) {
        const navItem = document.querySelector(`[data-section="${section}"]`);
        if (navItem) navItem.click();
    }

    function formatearFecha(fecha) {
        if (!fecha) return 'N/A';
        const date = new Date(fecha);
        return date.toLocaleDateString('es-ES', { 
            year: 'numeric', 
            month: 'short', 
            day: 'numeric' 
        });
    }

    function formatearMoneda(valor) {
        return new Intl.NumberFormat('es-CO', {
            style: 'currency',
            currency: 'COP',
            minimumFractionDigits: 0
        }).format(valor);
    }

    function mostrarAlerta(tipo, mensaje) {
        const alert = document.createElement('div');
        alert.className = `alert alert-${tipo} alert-dismissible fade show position-fixed`;
        alert.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        alert.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alert);
        setTimeout(() => alert.remove(), 5000);
    }

    function editarPublicacion(id) {
        currentPublicacionId = id;
        mostrarAlerta('info', 'Funcionalidad de edición en desarrollo');
    }

    function eliminarPublicacion(id) {
        if (!confirm('¿Estás seguro de eliminar esta publicación?')) return;

        fetch(`/api/publicaciones/${id}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                cargarPublicaciones();
            } else {
                mostrarAlerta('danger', data.message || 'Error al eliminar publicación');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('danger', 'Error al procesar la solicitud');
        });
    }

    function verReserva(id) {
        currentReservaId = id;
        mostrarAlerta('info', 'Funcionalidad de ver reserva en desarrollo');
    }

    function confirmarReserva(id) {
        fetch(`/api/reservas/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ estado_reserva: 'confirmada' })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                cargarReservas();
                cargarDashboard();
            } else {
                mostrarAlerta('danger', data.message || 'Error al confirmar reserva');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('danger', 'Error al procesar la solicitud');
        });
    }

    // Funciones de preview
    function previewImage(input) {
        const preview = document.getElementById('imagenPreview');
        const previewImg = document.getElementById('previewImg');
        const imagenUrl = document.getElementById('imagenUrl');
        
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                preview.style.display = 'block';
                imagenUrl.value = ''; // Limpiar URL si se sube archivo
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.style.display = 'none';
        }
    }

    function previewVideo(input) {
        const preview = document.getElementById('videoPreview');
        const previewVideo = document.getElementById('previewVideo');
        const videoUrl = document.getElementById('videoUrl');
        
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const url = URL.createObjectURL(file);
            previewVideo.src = url;
            preview.style.display = 'block';
            videoUrl.value = ''; // Limpiar URL si se sube archivo
        } else {
            preview.style.display = 'none';
        }
    }

    // Hacer funciones globales
    window.showSection = showSection;
    window.editarPublicacion = editarPublicacion;
    window.eliminarPublicacion = eliminarPublicacion;
    window.verReserva = verReserva;
    window.confirmarReserva = confirmarReserva;
    window.previewImage = previewImage;
    window.previewVideo = previewVideo;

})();

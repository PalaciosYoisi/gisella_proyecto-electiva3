/**
 * Panel de Administrador - JavaScript Completo
 * ExploraQuibdó
 */

(function() {
    'use strict';

    // Variables globales
    let currentPrestadorId = null;
    let currentReporteId = null;
    let currentUsuarioId = null;
    let charts = {};

    document.addEventListener('DOMContentLoaded', function() {
        initNavigation();
        initSidebarToggle();
        initCharts();
        initValidaciones();
        initModeracion();
        initUsuarios();
        initPublicaciones();
        initLogout();
        initRegistrarAdmin();
        loadInitialData();
        // No inicializar reportes automáticamente, solo cuando se acceda a la sección
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
            'validaciones': 'Validar Prestadores',
            'moderacion': 'Moderar Contenido',
            'usuarios': 'Gestionar Usuarios',
            'publicaciones': 'Publicaciones',
            'reportes': 'Reportes',
            'configuracion': 'Configuración'
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
                    
                    // Cargar datos de la sección si es necesario
                    loadSectionData(targetSection);
                    
                    // Inicializar funcionalidades específicas de la sección
                    if (targetSection === 'publicaciones') {
                        initPublicaciones();
                        cargarPublicaciones();
                    }
                }
                
                // Actualizar título
                if (pageTitle && sectionTitles[targetSection]) {
                    pageTitle.textContent = sectionTitles[targetSection];
                }
            });
        });
    }

    // ============================================
    // TOGGLE SIDEBAR (Responsive)
    // ============================================
    function initSidebarToggle() {
        const btnToggle = document.getElementById('btnToggleSidebar');
        const sidebar = document.getElementById('sidebarAdmin');
        
        if (btnToggle && sidebar) {
            btnToggle.addEventListener('click', function() {
                sidebar.classList.toggle('active');
            });
        }

        // Mostrar botón en móviles
        if (window.innerWidth <= 991) {
            if (btnToggle) btnToggle.style.display = 'block';
        }
    }

    // ============================================
    // GRÁFICOS
    // ============================================
    function initCharts() {
        // Solo inicializar el gráfico del dashboard (usersChart)
        // Los gráficos de reportes se inicializarán cuando se acceda a esa sección
        const usersCtx = document.getElementById('usersChart');
        if (usersCtx) {
            // Cargar datos del dashboard
            fetch('/api/admin/usuarios?tipo=todos&estado=todos')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const usuarios = data.data || [];
                        const turistas = usuarios.filter(u => u.tipo_usuario === 'turista').length;
                        const prestadores = usuarios.filter(u => u.tipo_usuario === 'prestador').length;
                        const administradores = usuarios.filter(u => u.tipo_usuario === 'administrador').length;

                        charts.users = new Chart(usersCtx, {
                            type: 'doughnut',
                            data: {
                                labels: ['Turistas', 'Prestadores', 'Administradores'],
                                datasets: [{
                                    data: [turistas, prestadores, administradores],
                                    backgroundColor: ['#0067a3', '#2ecc71', '#e74c3c']
                                }]
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    }
                })
                .catch(error => {
                    console.error('Error al cargar datos del dashboard:', error);
                    // Crear gráfico vacío si falla
                    charts.users = new Chart(usersCtx, {
                        type: 'doughnut',
                        data: {
                            labels: ['Turistas', 'Prestadores', 'Administradores'],
                            datasets: [{
                                data: [0, 0, 0],
                                backgroundColor: ['#0067a3', '#2ecc71', '#e74c3c']
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'bottom'
                                }
                            }
                        }
                    });
                });
        }
    }

    // ============================================
    // VALIDACIONES DE PRESTADORES
    // ============================================
    function initValidaciones() {
        const btnAprobar = document.getElementById('btnAprobarPrestador');
        const btnRechazar = document.getElementById('btnRechazarPrestador');
        const searchInput = document.getElementById('searchPrestadores');
        const filterSelect = document.getElementById('filterEstadoPrestadores');

        if (btnAprobar) {
            btnAprobar.addEventListener('click', function() {
                if (currentPrestadorId) {
                    validarPrestador(currentPrestadorId, 'aprobar');
                }
            });
        }

        if (btnRechazar) {
            btnRechazar.addEventListener('click', function() {
                if (currentPrestadorId) {
                    if (confirm('¿Estás seguro de rechazar este prestador?')) {
                        validarPrestador(currentPrestadorId, 'rechazar');
                    }
                }
            });
        }

        if (searchInput) {
            searchInput.addEventListener('input', debounce(cargarPrestadores, 300));
        }

        if (filterSelect) {
            filterSelect.addEventListener('change', cargarPrestadores);
        }
    }

    function cargarPrestadores() {
        const container = document.getElementById('validacionesList');
        if (!container) return;

        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';

        fetch('/api/admin/prestadores-pendientes')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarPrestadores(data.data, container);
                    actualizarBadgePendientes(data.data.length);
                } else {
                    container.innerHTML = '<p class="text-muted text-center py-5">Error al cargar prestadores</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<p class="text-muted text-center py-5">Error al cargar prestadores</p>';
            });
    }

    function mostrarPrestadores(prestadores, container) {
        if (prestadores.length === 0) {
            container.innerHTML = '<p class="text-muted text-center py-5">No hay prestadores pendientes de validación</p>';
            return;
        }

        let html = '';
        prestadores.forEach(prestador => {
            const estadoClass = prestador.estado === 'pendiente' ? 'warning' : 
                              prestador.estado === 'activo' ? 'success' : 'danger';
            const estadoText = prestador.estado === 'pendiente' ? 'Pendiente' : 
                             prestador.estado === 'activo' ? 'Activo' : 'Rechazado';

            html += `
                <div class="validacion-card">
                    <div class="validacion-header">
                        <div class="validacion-info">
                            <h5>${prestador.nombre_completo || 'Sin nombre'}</h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-envelope me-2"></i>${prestador.correo || 'N/A'}
                                ${prestador.telefono ? `<i class="fas fa-phone ms-3 me-2"></i>${prestador.telefono}` : ''}
                            </p>
                        </div>
                        <span class="badge bg-${estadoClass}">${estadoText}</span>
                    </div>
                    <div class="validacion-body">
                        <p><strong>Fecha de registro:</strong> ${formatearFecha(prestador.fecha_registro)}</p>
                        <div class="validacion-actions mt-3">
                            ${prestador.estado === 'pendiente' ? `
                                <button class="btn btn-success" onclick="validarPrestador('${prestador.id_usuario}', 'aprobar')">
                                    <i class="fas fa-check me-2"></i>Aprobar
                                </button>
                                <button class="btn btn-danger" onclick="validarPrestador('${prestador.id_usuario}', 'rechazar')">
                                    <i class="fas fa-times me-2"></i>Rechazar
                                </button>
                            ` : ''}
                            <button class="btn btn-outline-primary" onclick="verDetallesPrestador('${prestador.id_usuario}')">
                                <i class="fas fa-eye me-2"></i>Ver Detalles
                            </button>
                        </div>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    function validarPrestador(id, accion) {
        currentPrestadorId = id;
        
        fetch(`/api/admin/validar-prestador/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ accion: accion })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                cargarPrestadores();
                loadInitialData(); // Actualizar estadísticas
            } else {
                mostrarAlerta('danger', data.message || 'Error al validar prestador');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('danger', 'Error al procesar la solicitud');
        });
    }

    // ============================================
    // MODERACIÓN DE CONTENIDO
    // ============================================
    function initModeracion() {
        const btnAprobar = document.getElementById('btnAprobarContenido');
        const btnEliminar = document.getElementById('btnEliminarContenido');
        const btnAdvertencia = document.getElementById('btnAdvertenciaContenido');

        if (btnAprobar) {
            btnAprobar.addEventListener('click', function() {
                if (currentReporteId) {
                    moderarContenido(currentReporteId, 'aprobar');
                }
            });
        }

        if (btnEliminar) {
            btnEliminar.addEventListener('click', function() {
                if (currentReporteId && confirm('¿Estás seguro de eliminar este contenido?')) {
                    moderarContenido(currentReporteId, 'eliminar');
                }
            });
        }

        if (btnAdvertencia) {
            btnAdvertencia.addEventListener('click', function() {
                if (currentReporteId) {
                    moderarContenido(currentReporteId, 'advertencia');
                }
            });
        }
    }

    function cargarContenidoReportado() {
        const container = document.getElementById('moderacionList');
        if (!container) return;

        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';

        fetch('/api/admin/contenido-reportado')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarReportes(data.data, container);
                    actualizarBadgeReportados(data.data.length);
                } else {
                    container.innerHTML = '<p class="text-muted text-center py-5">Error al cargar reportes</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<p class="text-muted text-center py-5">Error al cargar reportes</p>';
            });
    }

    function mostrarReportes(reportes, container) {
        if (reportes.length === 0) {
            container.innerHTML = '<p class="text-muted text-center py-5">No hay contenido reportado</p>';
            return;
        }

        let html = '';
        reportes.forEach(reporte => {
            html += `
                <div class="reporte-card">
                    <div class="reporte-header">
                        <div class="reporte-meta">
                            <h5>Reporte #${reporte.id_reporte || 'N/A'}</h5>
                            <p class="text-muted mb-0">
                                <i class="fas fa-calendar me-2"></i>${formatearFecha(reporte.fecha_reporte)}
                            </p>
                        </div>
                        <span class="badge bg-danger">Pendiente</span>
                    </div>
                    <div class="motivo-reporte">
                        <strong>Motivo del reporte:</strong>
                        <p class="mb-0">${reporte.motivo_reporte || 'No especificado'}</p>
                    </div>
                    <div class="validacion-actions mt-3">
                        <button class="btn btn-success" onclick="moderarContenido('${reporte.id_reporte}', 'aprobar')">
                            <i class="fas fa-check me-2"></i>Aprobar
                        </button>
                        <button class="btn btn-warning" onclick="moderarContenido('${reporte.id_reporte}', 'advertencia')">
                            <i class="fas fa-exclamation-triangle me-2"></i>Advertencia
                        </button>
                        <button class="btn btn-danger" onclick="moderarContenido('${reporte.id_reporte}', 'eliminar')">
                            <i class="fas fa-trash me-2"></i>Eliminar
                        </button>
                    </div>
                </div>
            `;
        });

        container.innerHTML = html;
    }

    function moderarContenido(id, accion) {
        currentReporteId = id;
        
        fetch(`/api/admin/moderar-contenido/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ accion: accion })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                cargarContenidoReportado();
                loadInitialData();
            } else {
                mostrarAlerta('danger', data.message || 'Error al moderar contenido');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('danger', 'Error al procesar la solicitud');
        });
    }

    // ============================================
    // GESTIÓN DE USUARIOS
    // ============================================
    function initUsuarios() {
        const accionSelect = document.getElementById('accionUsuario');
        const tipoUsuarioDiv = document.getElementById('divTipoUsuario');
        const btnConfirmar = document.getElementById('btnConfirmarGestionUsuario');

        if (accionSelect && tipoUsuarioDiv) {
            accionSelect.addEventListener('change', function() {
                if (this.value === 'cambiar_tipo') {
                    tipoUsuarioDiv.style.display = 'block';
                } else {
                    tipoUsuarioDiv.style.display = 'none';
                }
            });
        }

        if (btnConfirmar) {
            btnConfirmar.addEventListener('click', function() {
                const form = document.getElementById('formGestionarUsuario');
                if (form && currentUsuarioId) {
                    gestionarUsuario();
                }
            });
        }
    }

    function cargarUsuarios() {
        const tbody = document.getElementById('usuariosTableBody');
        if (!tbody) return;

        tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></td></tr>';

        const tipo = document.getElementById('filterTipoUsuario')?.value || 'todos';
        const estado = document.getElementById('filterEstadoUsuario')?.value || 'todos';

        fetch(`/api/admin/usuarios?tipo=${tipo}&estado=${estado}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarUsuarios(data.data, tbody);
                } else {
                    tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">Error al cargar usuarios</td></tr>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">Error al cargar usuarios</td></tr>';
            });
    }

    function mostrarUsuarios(usuarios, tbody) {
        if (usuarios.length === 0) {
            tbody.innerHTML = '<tr><td colspan="6" class="text-center py-5 text-muted">No hay usuarios</td></tr>';
            return;
        }

        let html = '';
        usuarios.forEach(usuario => {
            const estadoClass = usuario.estado === 'activo' ? 'activo' : 
                              usuario.estado === 'pendiente' ? 'pendiente' : 'inactivo';
            const estadoText = usuario.estado === 'activo' ? 'Activo' : 
                             usuario.estado === 'pendiente' ? 'Pendiente' : 'Inactivo';

            html += `
                <tr>
                    <td>${usuario.nombre_completo || 'N/A'}</td>
                    <td>${usuario.correo || 'N/A'}</td>
                    <td><span class="badge badge-admin bg-primary">${usuario.tipo_usuario || 'N/A'}</span></td>
                    <td><span class="badge badge-admin badge-${estadoClass}">${estadoText}</span></td>
                    <td>${formatearFecha(usuario.fecha_registro)}</td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" onclick="abrirModalGestionUsuario('${usuario.id_usuario}')">
                            <i class="fas fa-cog"></i>
                        </button>
                    </td>
                </tr>
            `;
        });

        tbody.innerHTML = html;
    }

    function gestionarUsuario() {
        const form = document.getElementById('formGestionarUsuario');
        const formData = new FormData(form);
        const data = {
            accion: formData.get('accion'),
            tipo_usuario: formData.get('tipo_usuario'),
            comentario: formData.get('comentario')
        };

        fetch(`/api/admin/gestionar-usuario/${currentUsuarioId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                bootstrap.Modal.getInstance(document.getElementById('modalGestionarUsuario')).hide();
                cargarUsuarios();
                loadInitialData();
            } else {
                mostrarAlerta('danger', data.message || 'Error al gestionar usuario');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('danger', 'Error al procesar la solicitud');
        });
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

        container.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"></div></div>';

        const estado = document.getElementById('filterEstadoPublicacion')?.value || 'todos';
        const busqueda = document.getElementById('searchPublicaciones')?.value || '';

        let url = `/api/admin/publicaciones?estado=${estado}`;
        if (busqueda) {
            url += `&busqueda=${encodeURIComponent(busqueda)}`;
        }

        fetch(url)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    mostrarPublicaciones(data.data, container);
                } else {
                    container.innerHTML = '<div class="text-center py-5 text-muted">Error al cargar publicaciones</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                container.innerHTML = '<div class="text-center py-5 text-muted">Error al cargar publicaciones</div>';
            });
    }

    function mostrarPublicaciones(publicaciones, container) {
        if (publicaciones.length === 0) {
            container.innerHTML = '<div class="text-center py-5 text-muted">No hay publicaciones</div>';
            return;
        }

        let html = '<div class="row g-4">';
        publicaciones.forEach(publicacion => {
            const estadoClass = publicacion.estado_publicacion === 'activo' ? 'success' : 
                              publicacion.estado_publicacion === 'pendiente' ? 'warning' : 
                              publicacion.estado_publicacion === 'rechazado' ? 'danger' : 'secondary';
            const estadoText = publicacion.estado_publicacion === 'activo' ? 'Activa' : 
                             publicacion.estado_publicacion === 'pendiente' ? 'Pendiente' : 
                             publicacion.estado_publicacion === 'rechazado' ? 'Rechazada' : 'Eliminada';

            html += `
                <div class="col-lg-4 col-md-6">
                    <div class="card-admin">
                        <div style="position: relative; height: 200px; overflow: hidden; border-radius: 12px 12px 0 0;">
                            <img src="${publicacion.imagen_url || 'https://via.placeholder.com/400x250?text=Sin+imagen'}" 
                                 alt="${publicacion.titulo_publicacion}" 
                                 style="width: 100%; height: 100%; object-fit: cover;"
                                 onerror="this.src='https://via.placeholder.com/400x250?text=Sin+imagen'">
                            <div style="position: absolute; top: 1rem; right: 1rem;">
                                <span class="badge bg-${estadoClass}">${estadoText}</span>
                            </div>
                        </div>
                        <div class="card-body-admin">
                            <h5 style="font-weight: 700; margin-bottom: 0.5rem;">${publicacion.titulo_publicacion}</h5>
                            <p style="color: #6c757d; font-size: 0.9rem; margin-bottom: 1rem; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                ${publicacion.descripcion || 'Sin descripción'}
                            </p>
                            <div style="display: flex; gap: 1rem; margin-bottom: 1rem; font-size: 0.85rem; color: #6c757d;">
                                <span><i class="fas fa-user me-1"></i>${publicacion.autor?.nombre || 'N/A'}</span>
                                <span><i class="fas fa-eye me-1"></i>${publicacion.cantidad_visitas || 0} vistas</span>
                                <span><i class="fas fa-calendar-check me-1"></i>${publicacion.total_reservas || 0} reservas</span>
                            </div>
                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                ${publicacion.estado_publicacion === 'rechazado' ? `
                                    <button class="btn btn-sm btn-success" onclick="gestionarPublicacion('${publicacion.id_publicacion}', 'aprobar')">
                                        <i class="fas fa-check me-1"></i>Aprobar
                                    </button>
                                ` : ''}
                                ${publicacion.estado_publicacion !== 'eliminado' ? `
                                    <button class="btn btn-sm btn-danger" onclick="gestionarPublicacion('${publicacion.id_publicacion}', 'eliminar')">
                                        <i class="fas fa-trash me-1"></i>Eliminar
                                    </button>
                                ` : ''}
                                <button class="btn btn-sm btn-outline-primary" onclick="verPublicacion('${publicacion.id_publicacion}')">
                                    <i class="fas fa-eye me-1"></i>Ver
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
        });
        html += '</div>';
        container.innerHTML = html;
    }

    function gestionarPublicacion(id, accion) {
        if (!confirm(`¿Estás seguro de ${accion === 'aprobar' ? 'aprobar' : 'eliminar'} esta publicación?`)) {
            return;
        }

        fetch(`/api/admin/gestionar-publicacion/${id}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ accion: accion })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                cargarPublicaciones();
            } else {
                mostrarAlerta('danger', data.message || 'Error al gestionar publicación');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('danger', 'Error al procesar la solicitud');
        });
    }

    function verPublicacion(id) {
        // Redirigir a la vista de detalle de la publicación
        window.open(`/experiencia/${id}`, '_blank');
    }

    // Hacer funciones globales
    window.gestionarPublicacion = gestionarPublicacion;
    window.verPublicacion = verPublicacion;

    // ============================================
    // REPORTES
    // ============================================
    let reportesCargados = false;

    function initReportes() {
        if (!reportesCargados) {
            cargarReportes();
            reportesCargados = true;
        }
    }

    function cargarReportes() {
        // Verificar que Chart.js esté disponible
        if (typeof Chart === 'undefined') {
            console.error('Chart.js no está disponible');
            setTimeout(cargarReportes, 500); // Reintentar después de 500ms
            return;
        }

        // Obtener contenedores de gráficos
        const cardUsuariosTipo = document.querySelector('#chartUsuariosTipo')?.closest('.card-admin');
        const cardPublicacionesCategoria = document.querySelector('#chartPublicacionesCategoria')?.closest('.card-admin');
        
        // Mostrar indicadores de carga
        if (cardUsuariosTipo) {
            const chartContainer = cardUsuariosTipo.querySelector('.card-header-admin').nextElementSibling;
            if (chartContainer && !chartContainer.querySelector('canvas')) {
                chartContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
            }
        }
        if (cardPublicacionesCategoria) {
            const chartContainer = cardPublicacionesCategoria.querySelector('.card-header-admin').nextElementSibling;
            if (chartContainer && !chartContainer.querySelector('canvas')) {
                chartContainer.innerHTML = '<div class="text-center py-5"><div class="spinner-border text-primary" role="status"><span class="visually-hidden">Cargando...</span></div></div>';
            }
        }

        fetch('/api/admin/reportes')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    actualizarGraficosReportes(data.data);
                } else {
                    console.error('Error en datos:', data);
                    mostrarErrorGraficos();
                }
            })
            .catch(error => {
                console.error('Error al cargar reportes:', error);
                mostrarErrorGraficos();
            });
    }

    function actualizarGraficosReportes(datos) {
        // Verificar que Chart.js esté cargado
        if (typeof Chart === 'undefined') {
            console.error('Chart.js no está cargado');
            mostrarErrorGraficos();
            return;
        }

        // Gráfico de usuarios por tipo
        const containerUsuariosTipo = document.querySelector('#chartUsuariosTipo')?.parentElement;
        if (containerUsuariosTipo) {
            // Limpiar contenedor y crear canvas
            containerUsuariosTipo.innerHTML = '<div style="position: relative; height: 300px;"><canvas id="chartUsuariosTipo"></canvas></div>';
            const ctx = document.getElementById('chartUsuariosTipo');
            
            if (ctx) {
                const usuariosData = datos.usuarios_por_tipo || {};
                const turistas = usuariosData.turista || 0;
                const prestadores = usuariosData.prestador || 0;
                const administradores = usuariosData.administrador || 0;

                // Destruir gráfico anterior si existe
                if (charts.usuariosTipo) {
                    charts.usuariosTipo.destroy();
                }

                charts.usuariosTipo = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: ['Turistas', 'Prestadores', 'Administradores'],
                        datasets: [{
                            label: 'Cantidad de Usuarios',
                            data: [turistas, prestadores, administradores],
                            backgroundColor: ['#0067a3', '#2ecc71', '#e74c3c'],
                            borderRadius: 8,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 800
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12,
                                titleFont: {
                                    size: 14,
                                    weight: 'bold'
                                },
                                bodyFont: {
                                    size: 13
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    stepSize: 1
                                }
                            }
                        }
                    }
                });
            }
        }

        // Gráfico de publicaciones por categoría
        const containerPublicacionesCategoria = document.querySelector('#chartPublicacionesCategoria')?.parentElement;
        if (containerPublicacionesCategoria) {
            containerPublicacionesCategoria.innerHTML = '<div style="position: relative; height: 300px;"><canvas id="chartPublicacionesCategoria"></canvas></div>';
            const ctx = document.getElementById('chartPublicacionesCategoria');
            
            if (ctx) {
                const categorias = datos.publicaciones_por_categoria || {};
                const labels = Object.keys(categorias);
                const values = Object.values(categorias);

                // Destruir gráfico anterior si existe
                if (charts.publicacionesCategoria) {
                    charts.publicacionesCategoria.destroy();
                }

                charts.publicacionesCategoria = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels.length > 0 ? labels : ['Sin categorías'],
                        datasets: [{
                            data: values.length > 0 ? values : [0],
                            backgroundColor: ['#0067a3', '#2ecc71', '#f39c12', '#e74c3c', '#9b59b6', '#1abc9c', '#34495e']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: {
                            duration: 800
                        },
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    font: {
                                        size: 12
                                    }
                                }
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                padding: 12
                            }
                        }
                    }
                });
            }
        }
    }

    function mostrarErrorGraficos() {
        const cardUsuariosTipo = document.querySelector('#chartUsuariosTipo')?.closest('.card-admin');
        const cardPublicacionesCategoria = document.querySelector('#chartPublicacionesCategoria')?.closest('.card-admin');
        
        if (cardUsuariosTipo) {
            const chartContainer = cardUsuariosTipo.querySelector('.card-header-admin').nextElementSibling;
            if (chartContainer && !chartContainer.querySelector('canvas')) {
                chartContainer.innerHTML = '<div class="text-center py-5"><i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 2rem;"></i><p class="text-muted">Error al cargar datos del gráfico</p><button class="btn btn-sm btn-primary mt-2" onclick="initReportes()">Reintentar</button></div>';
            }
        }
        if (cardPublicacionesCategoria) {
            const chartContainer = cardPublicacionesCategoria.querySelector('.card-header-admin').nextElementSibling;
            if (chartContainer && !chartContainer.querySelector('canvas')) {
                chartContainer.innerHTML = '<div class="text-center py-5"><i class="fas fa-exclamation-triangle text-warning mb-3" style="font-size: 2rem;"></i><p class="text-muted">Error al cargar datos del gráfico</p><button class="btn btn-sm btn-primary mt-2" onclick="initReportes()">Reintentar</button></div>';
            }
        }
    }

    // Hacer funciones globales para uso en HTML
    window.initReportes = initReportes;
    window.cargarReportes = cargarReportes;

    // ============================================
    // REGISTRAR ADMINISTRADOR
    // ============================================
    function initRegistrarAdmin() {
        const form = document.getElementById('formRegistrarAdmin');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                registrarAdministrador();
            });
        }
    }

    function registrarAdministrador() {
        const form = document.getElementById('formRegistrarAdmin');
        const formData = new FormData(form);
        const data = {
            nombre: formData.get('nombre'),
            correo: formData.get('correo'),
            telefono: formData.get('telefono'),
            contraseña: formData.get('contraseña')
        };

        fetch('/api/admin/registrar-administrador', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                mostrarAlerta('success', data.message);
                form.reset();
                bootstrap.Modal.getInstance(document.getElementById('modalRegistrarAdmin')).hide();
                cargarUsuarios();
            } else {
                const errorDiv = document.getElementById('adminError');
                if (errorDiv) {
                    errorDiv.textContent = data.message || 'Error al registrar administrador';
                    errorDiv.classList.remove('d-none');
                }
            }
        })
        .catch(error => {
            console.error('Error:', error);
            mostrarAlerta('danger', 'Error al procesar la solicitud');
        });
    }

    // ============================================
    // LOGOUT
    // ============================================
    function initLogout() {
        const btnLogout = document.getElementById('btnLogout');
        if (btnLogout) {
            btnLogout.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('¿Estás seguro de cerrar sesión?')) {
                    window.location.href = '/logout';
                }
            });
        }
        
        // También manejar enlaces directos
        const logoutLinks = document.querySelectorAll('.logout-btn');
        logoutLinks.forEach(link => {
            if (link.id !== 'btnLogout') {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    if (confirm('¿Estás seguro de cerrar sesión?')) {
                        window.location.href = '/logout';
                    }
                });
            }
        });
    }

    // ============================================
    // FUNCIONES AUXILIARES
    // ============================================
    function loadInitialData() {
        // Cargar datos iniciales del dashboard
        cargarPrestadores();
        cargarContenidoReportado();
        cargarUsuarios();
    }

    function loadSectionData(section) {
        switch(section) {
            case 'publicaciones':
                if (typeof initPublicaciones === 'function') {
                    initPublicaciones();
                }
                if (typeof cargarPublicaciones === 'function') {
                    cargarPublicaciones();
                }
                break;
            case 'validaciones':
                if (typeof cargarValidaciones === 'function') {
                    cargarValidaciones();
                }
                break;
            case 'moderacion':
                if (typeof cargarModeracion === 'function') {
                    cargarModeracion();
                }
                break;
            case 'usuarios':
                if (typeof cargarUsuarios === 'function') {
                    cargarUsuarios();
                }
                break;
        }
        switch(section) {
            case 'validaciones':
                cargarPrestadores();
                break;
            case 'moderacion':
                cargarContenidoReportado();
                break;
            case 'usuarios':
                cargarUsuarios();
                break;
            case 'publicaciones':
                cargarPublicaciones();
                break;
            case 'reportes':
                initReportes();
                break;
        }
    }

    function actualizarBadgePendientes(count) {
        const badge = document.getElementById('badgePendientes');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'block' : 'none';
        }
    }

    function actualizarBadgeReportados(count) {
        const badge = document.getElementById('badgeReportados');
        if (badge) {
            badge.textContent = count;
            badge.style.display = count > 0 ? 'block' : 'none';
        }
    }

    function formatearFecha(fecha) {
        if (!fecha) return 'N/A';
        const date = new Date(fecha);
        return date.toLocaleDateString('es-CO', { 
            year: 'numeric', 
            month: 'long', 
            day: 'numeric' 
        });
    }

    function mostrarAlerta(tipo, mensaje) {
        // Crear alerta temporal
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${tipo} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
        alertDiv.style.zIndex = '9999';
        alertDiv.innerHTML = `
            ${mensaje}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        document.body.appendChild(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }

    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Funciones globales para uso en HTML
    window.validarPrestador = validarPrestador;
    window.moderarContenido = moderarContenido;
    window.cargarReportes = cargarReportes;
    window.abrirModalGestionUsuario = function(id) {
        currentUsuarioId = id;
        const modal = new bootstrap.Modal(document.getElementById('modalGestionarUsuario'));
        modal.show();
    };
    window.verDetallesPrestador = function(id) {
        // Implementar ver detalles
        console.log('Ver detalles prestador:', id);
    };

})();

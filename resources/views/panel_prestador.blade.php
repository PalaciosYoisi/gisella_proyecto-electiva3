@extends('layouts.app')

@section('title', 'Panel de Prestador - ExploraQuibdó')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/panel-prestador.css') }}">
@endpush

@section('content')
<div class="panel-prestador-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar-prestador" id="sidebarPrestador">
        <div class="sidebar-header">
            <div class="logo-sidebar">
                <span class="brand-primary">Explora</span><span class="brand-secondary">Quibdó</span>
            </div>
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-briefcase"></i>
                </div>
                <div class="user-details">
                    <h5 class="user-name">{{ session('usuario_nombre', 'Prestador') }}</h5>
                    <span class="user-status verified">
                        <i class="fas fa-check-circle"></i> {{ ucfirst(session('usuario_tipo', 'prestador')) }}
                    </span>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="#dashboard" class="nav-item active" data-section="dashboard">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
            <a href="#publicaciones" class="nav-item" data-section="publicaciones">
                <i class="fas fa-images"></i>
                <span>Mis Publicaciones</span>
                <span class="badge-count" id="badgePublicaciones">0</span>
            </a>
            <a href="#nueva-publicacion" class="nav-item" data-section="nueva-publicacion">
                <i class="fas fa-plus-circle"></i>
                <span>Nueva Publicación</span>
            </a>
            <a href="#reservas" class="nav-item" data-section="reservas">
                <i class="fas fa-calendar-check"></i>
                <span>Reservas Recibidas</span>
                <span class="badge-count badge-warning" id="badgeReservas">0</span>
            </a>
            <a href="#metricas" class="nav-item" data-section="metricas">
                <i class="fas fa-chart-bar"></i>
                <span>Métricas</span>
            </a>
            <a href="#comentarios" class="nav-item" data-section="comentarios">
                <i class="fas fa-comments"></i>
                <span>Comentarios</span>
                <span class="badge-count" id="badgeComentarios">0</span>
            </a>
            <a href="#perfil" class="nav-item" data-section="perfil">
                <i class="fas fa-user-circle"></i>
                <span>Mi Perfil</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <a href="/" class="nav-item">
                <i class="fas fa-home"></i>
                <span>Volver al Inicio</span>
            </a>
            <a href="/logout" class="nav-item logout-btn">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="main-content-prestador">
        <header class="content-header">
            <div class="header-top">
                <h1 class="page-title" id="pageTitle">Dashboard</h1>
                <div class="header-actions">
                    <button class="btn btn-primary" id="btnNuevaPublicacion">
                        <i class="fas fa-plus me-2"></i>Nueva Publicación
                    </button>
                    <button class="btn btn-outline-primary btn-sm" id="btnToggleSidebar" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Sección: Dashboard -->
        <section id="section-dashboard" class="content-section active">
            @if(isset($mensaje_pendiente))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle me-2"></i>{{ $mensaje_pendiente }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <div class="stats-grid-admin">
                <div class="stat-card-admin">
                    <div class="stat-icon-admin bg-primary">
                        <i class="fas fa-eye"></i>
                    </div>
                    <div class="stat-content-admin">
                        <h3 id="statVisualizaciones">0</h3>
                        <p>Visualizaciones</p>
                        <span class="stat-trend positive">
                            <i class="fas fa-arrow-up me-1"></i>Total
                        </span>
                    </div>
                </div>
                <div class="stat-card-admin">
                    <div class="stat-icon-admin bg-success">
                        <i class="fas fa-calendar-check"></i>
                    </div>
                    <div class="stat-content-admin">
                        <h3 id="statReservasActivas">0</h3>
                        <p>Reservas Activas</p>
                        <span class="stat-trend positive">
                            <i class="fas fa-calendar me-1"></i>Confirmadas
                        </span>
                    </div>
                </div>
                <div class="stat-card-admin">
                    <div class="stat-icon-admin bg-warning">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-content-admin">
                        <h3 id="statCalificacion">0.0</h3>
                        <p>Calificación Promedio</p>
                        <span class="stat-trend neutral">
                            <i class="fas fa-star me-1"></i>Promedio
                        </span>
                    </div>
                </div>
                <div class="stat-card-admin">
                    <div class="stat-icon-admin bg-info">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="stat-content-admin">
                        <h3 id="statIngresos">$0</h3>
                        <p>Ingresos del Mes</p>
                        <span class="stat-trend positive">
                            <i class="fas fa-chart-line me-1"></i>Estimado
                        </span>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-3">
                <div class="col-lg-8">
                    <div class="card-admin">
                        <div class="card-header-admin">
                            <h5>Reservas Recientes</h5>
                            <a href="#reservas" class="btn-link" onclick="showSection('reservas')">Ver todas</a>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Cliente</th>
                                        <th>Experiencia</th>
                                        <th>Fecha Evento</th>
                                        <th>Personas</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="reservasRecientesTable">
                                    <tr>
                                        <td colspan="6" class="text-center py-5">
                                            <div class="spinner-border text-primary" role="status"></div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-admin">
                        <div class="card-header-admin">
                            <h5>Actividad Reciente</h5>
                        </div>
                        <div class="activity-timeline" id="activityTimeline">
                            <div class="timeline-item">
                                <div class="timeline-marker bg-success"></div>
                                <div class="timeline-content">
                                    <h6>Cargando actividad...</h6>
                                    <p class="text-muted mb-0">Espera un momento</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección: Publicaciones -->
        <section id="section-publicaciones" class="content-section">
            <div class="section-header-custom">
                <div>
                    <h2>Mis Publicaciones</h2>
                    <p class="text-muted mb-0">Gestiona todas tus publicaciones de experiencias turísticas</p>
                </div>
                <button class="btn btn-primary" onclick="showSection('nueva-publicacion')">
                    <i class="fas fa-plus me-2"></i>Nueva Publicación
                </button>
            </div>

            <div class="filters-bar">
                <div class="search-box">
                    <i class="fas fa-search"></i>
                    <input type="text" id="searchPublicaciones" placeholder="Buscar publicaciones..." class="form-control">
                </div>
                <select class="form-select" id="filterEstadoPublicacion" style="width: auto;">
                    <option value="todos">Todos los estados</option>
                    <option value="activo">Activas</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="rechazado">Rechazadas</option>
                </select>
            </div>

            <div id="publicacionesList" class="row g-4">
                <div class="col-12">
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección: Nueva Publicación -->
        <section id="section-nueva-publicacion" class="content-section">
            <div class="section-header-custom">
                <h2>Nueva Publicación</h2>
                <p class="text-muted mb-0">Crea una nueva experiencia turística para promocionar</p>
            </div>
            <div class="card-admin">
                <form id="formNuevaPublicacion">
                    <div class="row g-4">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Título de la Experiencia <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="tituloPublicacion" name="titulo_publicacion" required placeholder="Ej: Tour por el Río Atrato">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Descripción <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="descripcionPublicacion" name="descripcion" rows="5" required placeholder="Describe detalladamente la experiencia (mínimo 50 caracteres)"></textarea>
                                <small class="text-muted">Mínimo 50 caracteres</small>
                            </div>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Categoría <span class="text-danger">*</span></label>
                                    <select class="form-select" id="categoriaPublicacion" name="categoria" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="naturaleza">Naturaleza</option>
                                        <option value="cultura">Cultura</option>
                                        <option value="gastronomia">Gastronomía</option>
                                        <option value="aventura">Aventura</option>
                                        <option value="relax">Relax</option>
                                        <option value="eventos">Eventos</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Precio por persona (COP) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="precioPublicacion" name="precio_aproximado" required min="0" step="1000" placeholder="0">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Imagen <span class="text-muted">(o URL)</span></label>
                                <div class="d-flex gap-2">
                                    <input type="file" class="form-control" id="imagenFile" name="imagen" accept="image/*" onchange="previewImage(this)">
                                    <input type="url" class="form-control" id="imagenUrl" name="imagen_url" placeholder="O ingresa una URL">
                                </div>
                                <small class="text-muted">Formatos: JPG, PNG, GIF, WEBP (máx. 10MB)</small>
                                <div id="imagenPreview" class="mt-2" style="display: none;">
                                    <img id="previewImg" src="" alt="Vista previa" style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 2px solid #e9ecef;">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Video <span class="text-muted">(o URL)</span></label>
                                <div class="d-flex gap-2">
                                    <input type="file" class="form-control" id="videoFile" name="video" accept="video/*" onchange="previewVideo(this)">
                                    <input type="url" class="form-control" id="videoUrl" name="url_multimedia" placeholder="O ingresa una URL">
                                </div>
                                <small class="text-muted">Formatos: MP4, AVI, MOV, WMV, FLV, WEBM (máx. 50MB)</small>
                                <div id="videoPreview" class="mt-2" style="display: none;">
                                    <video id="previewVideo" controls style="max-width: 300px; max-height: 200px; border-radius: 8px; border: 2px solid #e9ecef;">
                                        Tu navegador no soporta la reproducción de video.
                                    </video>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card-admin bg-light">
                                <h6 class="mb-3 fw-bold">Información Adicional</h6>
                                <div class="mb-3">
                                    <label class="form-label">Ubicación <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="ubicacionPublicacion" name="ubicacion" required placeholder="Ej: Quibdó, Chocó">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Duración (horas)</label>
                                    <input type="number" class="form-control" id="duracionPublicacion" name="duracion_horas" min="0.5" step="0.5" placeholder="2.5">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Capacidad máxima</label>
                                    <input type="number" class="form-control" id="capacidadPublicacion" name="capacidad_maxima" min="1" placeholder="20">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Fecha del evento (opcional)</label>
                                    <input type="date" class="form-control" id="fechaEventoPublicacion" name="fecha_evento">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Publicar Experiencia
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="showSection('publicaciones')">Cancelar</button>
                    </div>
                </form>
            </div>
        </section>

        <!-- Sección: Reservas -->
        <section id="section-reservas" class="content-section">
            <div class="section-header-custom">
                <div>
                    <h2>Reservas Recibidas</h2>
                    <p class="text-muted mb-0">Gestiona todas las reservas de tus experiencias</p>
                </div>
                <select class="form-select form-select-sm" id="filterEstadoReserva" style="width: auto;">
                    <option value="">Todas</option>
                    <option value="pendiente">Pendientes</option>
                    <option value="confirmada">Confirmadas</option>
                    <option value="cancelada">Canceladas</option>
                    <option value="completada">Completadas</option>
                </select>
            </div>
            <div class="card-admin">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Experiencia</th>
                                <th>Fecha Evento</th>
                                <th>Personas</th>
                                <th>Total</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="reservasTableBody">
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="spinner-border text-primary" role="status"></div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Sección: Métricas -->
        <section id="section-metricas" class="content-section">
            <div class="section-header-custom">
                <h2>Métricas de Desempeño</h2>
                <p class="text-muted mb-0">Analiza el rendimiento de tus publicaciones</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card-admin">
                        <div class="card-header-admin">
                            <h5>Visualizaciones (Últimos 6 meses)</h5>
                        </div>
                        <div style="position: relative; height: 300px; padding: 1rem;">
                            <canvas id="viewsChart"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-admin">
                        <div class="card-header-admin">
                            <h5>Reservas por Categoría</h5>
                        </div>
                        <div style="position: relative; height: 300px; padding: 1rem;">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección: Comentarios -->
        <section id="section-comentarios" class="content-section">
            <div class="section-header-custom">
                <h2>Comentarios y Reseñas</h2>
                <p class="text-muted mb-0">Gestiona los comentarios de tus experiencias</p>
            </div>
            <div id="comentariosList" class="card-admin">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                </div>
            </div>
        </section>

        <!-- Sección: Perfil -->
        <section id="section-perfil" class="content-section">
            <div class="section-header-custom">
                <h2>Mi Perfil de Prestador</h2>
                <p class="text-muted mb-0">Actualiza tu información personal y de servicio</p>
            </div>
            <div class="card-admin">
                <form id="formPerfilPrestador">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <h5 class="mb-3">Información Personal</h5>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nombre Completo</label>
                                <input type="text" class="form-control" name="nombre_completo" value="{{ session('usuario_nombre') }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Correo Electrónico</label>
                                <input type="email" class="form-control" name="correo" value="{{ session('usuario_correo') }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input type="tel" class="form-control" name="telefono" placeholder="+57 300 000 0000">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="mb-3">Información del Servicio</h5>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Nombre del Servicio</label>
                                <input type="text" class="form-control" name="nombre_servicio" placeholder="Nombre de tu servicio">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Descripción del Servicio</label>
                                <textarea class="form-control" name="descripcion_servicio" rows="3" placeholder="Describe tu servicio"></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Dirección</label>
                                <input type="text" class="form-control" name="direccion" placeholder="Dirección completa">
                            </div>
                        </div>
                    </div>
                    <div class="form-actions mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>
</div>

@push('scripts')
<script src="{{ asset('js/panel-prestador.js') }}"></script>
@endpush
@endsection


@extends('layouts.app')

@section('title', 'Panel de Administrador - ExploraQuibdó')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/panel-administrador.css') }}">
@endpush

@section('content')
<div class="panel-admin-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar-admin" id="sidebarAdmin">
        <div class="sidebar-header">
            <div class="logo-sidebar">
                <span class="brand-primary">Explora</span><span class="brand-secondary">Quibdó</span>
            </div>
            <div class="user-info">
                <div class="user-avatar admin-avatar">
                    <i class="fas fa-user-shield"></i>
                </div>
                <div class="user-details">
                    <h5 class="user-name">{{ session('usuario_nombre', 'Administrador') }}</h5>
                    <span class="user-role">Super Administrador</span>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="#dashboard" class="nav-item active" data-section="dashboard">
                <i class="fas fa-chart-pie"></i>
                <span>Dashboard</span>
            </a>
            <a href="#validaciones" class="nav-item" data-section="validaciones">
                <i class="fas fa-check-circle"></i>
                <span>Validar Prestadores</span>
                <span class="badge-count badge-warning" id="badgePendientes">0</span>
            </a>
            <a href="#moderacion" class="nav-item" data-section="moderacion">
                <i class="fas fa-shield-alt"></i>
                <span>Moderar Contenido</span>
                <span class="badge-count badge-danger" id="badgeReportados">0</span>
            </a>
            <a href="#usuarios" class="nav-item" data-section="usuarios">
                <i class="fas fa-users"></i>
                <span>Gestionar Usuarios</span>
            </a>
            <a href="#publicaciones" class="nav-item" data-section="publicaciones">
                <i class="fas fa-images"></i>
                <span>Publicaciones</span>
            </a>
            <a href="#reportes" class="nav-item" data-section="reportes">
                <i class="fas fa-file-alt"></i>
                <span>Reportes</span>
            </a>
            <a href="#configuracion" class="nav-item" data-section="configuracion">
                <i class="fas fa-cog"></i>
                <span>Configuración</span>
            </a>
        </nav>
        
        <div class="sidebar-footer">
            <a href="/" class="nav-item">
                <i class="fas fa-home"></i>
                <span>Volver al Inicio</span>
            </a>
            <a href="#" class="nav-item logout-btn" id="btnLogout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Cerrar Sesión</span>
            </a>
        </div>
    </aside>

    <!-- Contenido Principal -->
    <main class="main-content-admin">
        <header class="content-header">
            <div class="header-top">
                <h1 class="page-title" id="pageTitle">Dashboard</h1>
                <div class="header-actions">
                    <button class="btn btn-outline-primary btn-sm" id="btnToggleSidebar" style="display: none;">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </header>

        <!-- Sección: Dashboard -->
        <section id="section-dashboard" class="content-section active">
            <div class="stats-grid-admin">
                <div class="stat-card-admin">
                    <div class="stat-icon-admin bg-primary">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="stat-content-admin">
                        <h3 id="statUsuariosTotales">{{ $stats['usuarios_totales'] ?? 0 }}</h3>
                        <p>Usuarios Totales</p>
                        <span class="stat-trend positive">
                            <i class="fas fa-arrow-up me-1"></i>+12% este mes
                        </span>
                    </div>
                </div>
                <div class="stat-card-admin">
                    <div class="stat-icon-admin bg-success">
                        <i class="fas fa-briefcase"></i>
                    </div>
                    <div class="stat-content-admin">
                        <h3 id="statPrestadoresActivos">{{ $stats['prestadores_activos'] ?? 0 }}</h3>
                        <p>Prestadores Activos</p>
                        <span class="stat-trend positive">
                            <i class="fas fa-arrow-up me-1"></i>+5 este mes
                        </span>
                    </div>
                </div>
                <div class="stat-card-admin">
                    <div class="stat-icon-admin bg-warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="stat-content-admin">
                        <h3 id="statPendientes">{{ $stats['prestadores_pendientes'] ?? 0 }}</h3>
                        <p>Pendientes Validación</p>
                        <span class="stat-trend neutral">
                            <i class="fas fa-clock me-1"></i>Requieren atención
                        </span>
                    </div>
                </div>
                <div class="stat-card-admin">
                    <div class="stat-icon-admin bg-danger">
                        <i class="fas fa-flag"></i>
                    </div>
                    <div class="stat-content-admin">
                        <h3 id="statReportados">{{ $stats['contenido_reportado'] ?? 0 }}</h3>
                        <p>Contenido Reportado</p>
                        <span class="stat-trend neutral">
                            <i class="fas fa-eye me-1"></i>Requiere revisión
                        </span>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-3">
                <div class="col-lg-8">
                    <div class="card-admin">
                        <div class="card-header-admin">
                            <h5>Actividad Reciente</h5>
                        </div>
                        <div class="activity-timeline" id="activityTimeline">
                            @if(isset($actividad_reciente) && count($actividad_reciente) > 0)
                                @foreach($actividad_reciente as $actividad)
                                <div class="timeline-item">
                                    <div class="timeline-marker bg-{{ $actividad['tipo'] === 'prestador_registrado' ? 'success' : 'warning' }}"></div>
                                    <div class="timeline-content">
                                        <h6>{{ $actividad['tipo'] === 'prestador_registrado' ? 'Nuevo prestador registrado' : 'Contenido reportado' }}</h6>
                                        <p class="text-muted mb-0">{{ $actividad['mensaje'] }}</p>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($actividad['fecha'])->diffForHumans() }}</small>
                                    </div>
                                </div>
                                @endforeach
                            @else
                                <p class="text-muted text-center py-4">No hay actividad reciente</p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card-admin">
                        <div class="card-header-admin">
                            <h5>Distribución de Usuarios</h5>
                        </div>
                        <div style="position: relative; height: 250px;">
                            <canvas id="usersChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección: Validaciones -->
        <section id="section-validaciones" class="content-section">
            <div class="section-header-custom">
                <h2>Validar Prestadores</h2>
                <p class="text-muted mb-0">Revisa y aprueba las solicitudes de nuevos prestadores de servicios</p>
            </div>
            
            <div class="filters-bar">
                <div class="search-box">
                    <input type="text" id="searchPrestadores" placeholder="Buscar prestador..." class="form-control">
                </div>
                <select class="form-select" id="filterEstadoPrestadores" style="width: auto;">
                    <option value="todos">Todos los estados</option>
                    <option value="pendiente" selected>Pendientes</option>
                    <option value="activo">Activos</option>
                    <option value="rechazado">Rechazados</option>
                </select>
            </div>

            <div class="validaciones-list" id="validacionesList">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección: Moderación -->
        <section id="section-moderacion" class="content-section">
            <div class="section-header-custom">
                <h2>Moderar Contenido</h2>
                <p class="text-muted mb-0">Revisa y gestiona el contenido reportado por los usuarios</p>
            </div>

            <div class="filters-bar">
                <div class="search-box">
                    <input type="text" id="searchReportes" placeholder="Buscar reportes..." class="form-control">
                </div>
                <select class="form-select" id="filterTipoReporte" style="width: auto;">
                    <option value="todos">Todos los tipos</option>
                    <option value="publicacion">Publicaciones</option>
                    <option value="reseña">Reseñas</option>
                </select>
            </div>

            <div class="moderacion-list" id="moderacionList">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección: Usuarios -->
        <section id="section-usuarios" class="content-section">
            <div class="section-header-custom">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2>Gestionar Usuarios</h2>
                        <p class="text-muted mb-0">Administra todos los usuarios del sistema</p>
                    </div>
                    <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalRegistrarAdmin">
                        <i class="fas fa-user-plus me-2"></i>Registrar Administrador
                    </button>
                </div>
            </div>

            <div class="filters-bar">
                <div class="search-box">
                    <input type="text" id="searchUsuarios" placeholder="Buscar usuario..." class="form-control">
                </div>
                <select class="form-select" id="filterTipoUsuario" style="width: auto;">
                    <option value="todos">Todos los tipos</option>
                    <option value="turista">Turistas</option>
                    <option value="prestador">Prestadores</option>
                    <option value="administrador">Administradores</option>
                </select>
                <select class="form-select" id="filterEstadoUsuario" style="width: auto;">
                    <option value="todos">Todos los estados</option>
                    <option value="activo">Activos</option>
                    <option value="inactivo">Inactivos</option>
                    <option value="pendiente">Pendientes</option>
                </select>
            </div>

            <div class="table-admin">
                <table class="table table-hover mb-0">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Correo</th>
                            <th>Tipo</th>
                            <th>Estado</th>
                            <th>Fecha Registro</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody id="usuariosTableBody">
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Cargando...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Sección: Publicaciones -->
        <section id="section-publicaciones" class="content-section">
            <div class="section-header-custom">
                <h2>Gestionar Publicaciones</h2>
                <p class="text-muted mb-0">Modera y gestiona todas las publicaciones del sistema</p>
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
                    <option value="eliminado">Eliminadas</option>
                </select>
            </div>

            <div id="publicacionesList">
                <div class="text-center py-5">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección: Reportes -->
        <section id="section-reportes" class="content-section">
            <div class="section-header-custom">
                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div>
                        <h2><i class="fas fa-chart-bar me-2"></i>Reportes Estadísticos</h2>
                        <p class="text-muted mb-0">Visualiza estadísticas y métricas del sistema</p>
                    </div>
                    <button class="btn btn-outline-primary btn-sm mt-2 mt-md-0" onclick="initReportes()" id="btnActualizarReportes">
                        <i class="fas fa-sync-alt me-2"></i>Actualizar
                    </button>
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-6">
                    <div class="card-admin">
                        <div class="card-header-admin">
                            <h5><i class="fas fa-users me-2 text-primary"></i>Usuarios por Tipo</h5>
                        </div>
                        <div style="position: relative; height: 300px; padding: 1rem;">
                            <canvas id="chartUsuariosTipo"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card-admin">
                        <div class="card-header-admin">
                            <h5><i class="fas fa-images me-2 text-primary"></i>Publicaciones por Categoría</h5>
                        </div>
                        <div style="position: relative; height: 300px; padding: 1rem;">
                            <canvas id="chartPublicacionesCategoria"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-4 mt-2">
                <div class="col-12">
                    <div class="card-admin">
                        <div class="card-header-admin">
                            <h5><i class="fas fa-info-circle me-2 text-primary"></i>Resumen General</h5>
                        </div>
                        <div class="row g-3 p-3" id="resumenReportes">
                            <div class="col-md-3">
                                <div class="stat-mini-card">
                                    <div class="stat-mini-icon bg-primary">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                    <div class="stat-mini-content">
                                        <h4 id="statReservasTotales">0</h4>
                                        <p class="mb-0">Reservas Totales</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-mini-card">
                                    <div class="stat-mini-icon bg-success">
                                        <i class="fas fa-star"></i>
                                    </div>
                                    <div class="stat-mini-content">
                                        <h4 id="statPublicacionesTotales">0</h4>
                                        <p class="mb-0">Publicaciones Activas</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-mini-card">
                                    <div class="stat-mini-icon bg-info">
                                        <i class="fas fa-heart"></i>
                                    </div>
                                    <div class="stat-mini-content">
                                        <h4 id="statFavoritosTotales">0</h4>
                                        <p class="mb-0">Favoritos Totales</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="stat-mini-card">
                                    <div class="stat-mini-icon bg-warning">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <div class="stat-mini-content">
                                        <h4 id="resumenUsuarios">0</h4>
                                        <p class="mb-0">Total Usuarios</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="text-success mb-1" id="resumenPublicaciones">0</h4>
                                    <p class="text-muted mb-0 small">Total Publicaciones</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="text-warning mb-1" id="resumenReservas">0</h4>
                                    <p class="text-muted mb-0 small">Total Reservas</p>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <h4 class="text-info mb-1" id="resumenCategorias">0</h4>
                                    <p class="text-muted mb-0 small">Categorías Activas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección: Configuración -->
        <section id="section-configuracion" class="content-section">
            <div class="section-header-custom">
                <h2>Configuración del Sistema</h2>
                <p class="text-muted mb-0">Gestiona la configuración general del sitio</p>
            </div>

            <div class="card-admin">
                <div class="card-header-admin">
                    <h5>Configuración General</h5>
                </div>
                <div class="p-4">
                    <p class="text-muted">Próximamente: Configuración del sistema</p>
                </div>
            </div>
        </section>
    </main>
</div>

<!-- Modales -->
@include('modales.admin.validar_prestador')
@include('modales.admin.moderar_contenido')
@include('modales.admin.gestionar_usuario')
@include('modales.admin.registrar_admin')

@push('scripts')
<script src="{{ asset('js/panel-administrador.js') }}"></script>
@endpush
@endsection

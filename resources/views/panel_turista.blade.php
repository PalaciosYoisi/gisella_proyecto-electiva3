@extends('layouts.app')

@section('title', 'Panel de Turista - ExploraQuibdó')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/panel-turista.css') }}">
@endpush

@section('content')
<div class="panel-turista-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar-turista">
        <div class="sidebar-header">
            <div class="logo-sidebar">
                <span class="brand-primary">Explora</span><span class="brand-secondary">Quibdó</span>
            </div>
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <h5 class="user-name">{{ session('usuario_nombre', 'Turista') }}</h5>
                    <span class="user-role">{{ ucfirst(session('usuario_tipo', 'turista')) }}</span>
                </div>
            </div>
        </div>
        
        <nav class="sidebar-nav">
            <a href="#explorar" class="nav-item active" data-section="explorar">
                <i class="fas fa-compass"></i>
                <span>Explorar</span>
            </a>
            <a href="#favoritos" class="nav-item" data-section="favoritos">
                <i class="fas fa-heart"></i>
                <span>Favoritos</span>
                <span class="badge-count">3</span>
            </a>
            <a href="#reservas" class="nav-item" data-section="reservas">
                <i class="fas fa-calendar-check"></i>
                <span>Mis Reservas</span>
                <span class="badge-count">5</span>
            </a>
            <a href="#reseñas" class="nav-item" data-section="reseñas">
                <i class="fas fa-star"></i>
                <span>Mis Reseñas</span>
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
    <main class="main-content-turista">
        <!-- Header -->
        <header class="content-header">
            <div class="header-top">
                <h1 class="page-title" id="pageTitle">Explorar Experiencias</h1>
                <div class="header-actions">
                    <button class="btn btn-outline-primary btn-sm" id="btnFiltros">
                        <i class="fas fa-filter me-2"></i>Filtros
                    </button>
                    <div class="search-box">
                        <i class="fas fa-search"></i>
                        <input type="text" placeholder="Buscar experiencias..." id="searchInput">
                    </div>
                </div>
            </div>
        </header>

        <!-- Sección: Explorar -->
        <section id="section-explorar" class="content-section active">
            <div class="filters-panel" id="filtersPanel" style="display: none;">
                <div class="filters-content">
                    <h5 class="filters-title">Filtros de Búsqueda</h5>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label">Categoría</label>
                            <select class="form-select" id="filterCategoria">
                                <option value="">Todas</option>
                                <option value="naturaleza">Naturaleza</option>
                                <option value="cultura">Cultura</option>
                                <option value="gastronomia">Gastronomía</option>
                                <option value="ecoturismo">Ecoturismo</option>
                                <option value="festividades">Festividades</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Precio</label>
                            <select class="form-select" id="filterPrecio">
                                <option value="">Todos</option>
                                <option value="0-50000">$0 - $50.000</option>
                                <option value="50000-100000">$50.000 - $100.000</option>
                                <option value="100000+">Más de $100.000</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Calificación</label>
                            <select class="form-select" id="filterCalificacion">
                                <option value="">Todas</option>
                                <option value="5">5 estrellas</option>
                                <option value="4">4+ estrellas</option>
                                <option value="3">3+ estrellas</option>
                            </select>
                        </div>
                    </div>
                    <div class="filters-actions mt-3">
                        <button class="btn btn-primary" id="applyFilters">Aplicar Filtros</button>
                        <button class="btn btn-outline-secondary" id="clearFilters">Limpiar</button>
                    </div>
                </div>
            </div>

            <div class="experiences-grid" id="experiencesGrid">
                <!-- Las experiencias se cargarán aquí dinámicamente -->
                <div class="row g-4">
                    <div class="col-lg-4 col-md-6">
                        <div class="experience-card-turista">
                            <div class="card-image-wrapper">
                                <img src="{{ asset('img/atrato.jpg') }}" alt="Tour Río Atrato">
                                <button class="btn-favorite" data-experience-id="1">
                                    <i class="far fa-heart"></i>
                                </button>
                                <span class="badge-price">$80.000</span>
                            </div>
                            <div class="card-body">
                                <div class="card-header-info">
                                    <h5 class="card-title">Tour por el Río Atrato</h5>
                                    <div class="card-rating">
                                        <i class="fas fa-star text-warning"></i>
                                        <span>4.9</span>
                                    </div>
                                </div>
                                <p class="card-text">Navega por uno de los ríos más importantes de Colombia con guías expertos.</p>
                                <div class="card-footer-info">
                                    <span class="badge badge-nature">
                                        <i class="fas fa-water me-1"></i>Naturaleza
                                    </span>
                                    <a href="#" class="btn btn-sm btn-primary">Ver Detalles</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Más cards se agregarán dinámicamente -->
                </div>
            </div>
        </section>

        <!-- Sección: Favoritos -->
        <section id="section-favoritos" class="content-section">
            <div class="section-header-custom">
                <h2>Mis Experiencias Favoritas</h2>
                <p class="text-muted">Guarda tus experiencias favoritas para acceder rápidamente</p>
            </div>
            <div class="favorites-grid">
                <div class="row g-4" id="favoritesGrid">
                    <!-- Los favoritos se cargarán aquí -->
                    <div class="col-12 text-center py-5">
                        <i class="fas fa-heart text-muted" style="font-size: 3rem; opacity: 0.3;"></i>
                        <p class="text-muted mt-3">No tienes experiencias favoritas aún</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sección: Reservas -->
        <section id="section-reservas" class="content-section">
            <div class="section-header-custom">
                <h2>Mis Reservas</h2>
                <div class="reservas-tabs">
                    <button class="tab-btn active" data-tab="activas">Activas</button>
                    <button class="tab-btn" data-tab="completadas">Completadas</button>
                    <button class="tab-btn" data-tab="canceladas">Canceladas</button>
                </div>
            </div>
            <div class="reservas-list">
                <div id="reservasActivas" class="tab-content active">
                    <!-- Reservas activas -->
                    <div class="reserva-card">
                        <div class="reserva-header">
                            <div class="reserva-info">
                                <h5>Tour por el Río Atrato</h5>
                                <p class="text-muted mb-0">
                                    <i class="fas fa-calendar me-2"></i>15 de Diciembre, 2024
                                    <i class="fas fa-clock ms-3 me-2"></i>8:00 AM
                                </p>
                            </div>
                            <span class="badge bg-success">Confirmada</span>
                        </div>
                        <div class="reserva-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <p><strong>Prestador:</strong> Guías del Chocó</p>
                                    <p><strong>Participantes:</strong> 2 personas</p>
                                </div>
                                <div class="col-md-6 text-md-end">
                                    <p><strong>Total:</strong> $160.000</p>
                                    <button class="btn btn-sm btn-outline-danger mt-2">Cancelar Reserva</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="reservasCompletadas" class="tab-content">
                    <!-- Reservas completadas -->
                </div>
                <div id="reservasCanceladas" class="tab-content">
                    <!-- Reservas canceladas -->
                </div>
            </div>
        </section>

        <!-- Sección: Reseñas -->
        <section id="section-reseñas" class="content-section">
            <div class="section-header-custom">
                <h2>Mis Reseñas y Calificaciones</h2>
            </div>
            <div class="reseñas-list">
                <!-- Las reseñas se mostrarán aquí -->
            </div>
        </section>

        <!-- Sección: Perfil -->
        <section id="section-perfil" class="content-section">
            <div class="section-header-custom">
                <h2>Mi Perfil</h2>
            </div>
            <div class="profile-content">
                <div class="row">
                    <div class="col-md-4">
                        <div class="profile-card">
                            <div class="profile-avatar-large">
                                <i class="fas fa-user"></i>
                            </div>
                            <h4 class="text-center mt-3">{{ session('usuario_nombre', 'Turista') }}</h4>
                            <p class="text-center text-muted">Miembro desde {{ session('fecha_registro') ? \Carbon\Carbon::parse(session('fecha_registro'))->format('M Y') : 'Enero 2024' }}</p>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="profile-form-card">
                            <h5>Información Personal</h5>
                            <form id="profileForm">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nombre Completo</label>
                                        <input type="text" class="form-control" value="Turista Ejemplo">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Correo Electrónico</label>
                                        <input type="email" class="form-control" value="turista@example.com">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Teléfono</label>
                                        <input type="tel" class="form-control" value="+57 300 123 4567">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Fecha de Nacimiento</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
@push('scripts')
<script src="{{ asset('js/panel-turista.js') }}"></script>
@endpush
@endsection


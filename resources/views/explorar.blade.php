@extends('layouts.app')

@section('title', 'Explorar Experiencias - ExploraQuibdó')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/explorar.css') }}">
@endpush

@section('content')
<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNavbar">
    <div class="container">
        <a class="navbar-brand" href="/">
            <span class="brand-primary">Explora</span><span class="brand-secondary">Quibdó</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="/#inicio">Inicio</a></li>
                <li class="nav-item"><a class="nav-link" href="/#destinos">Destinos</a></li>
                <li class="nav-item"><a class="nav-link active" href="/explorar">Explorar</a></li>
                <li class="nav-item"><a class="nav-link" href="/#contacto">Contacto</a></li>
                @if(session('autenticado'))
                    <li class="nav-item dropdown ms-lg-3">
                        <a class="nav-link dropdown-toggle user-menu" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <div class="user-avatar-nav">
                                <i class="fas fa-user-circle"></i>
                            </div>
                            <span class="user-name-nav">{{ session('usuario_nombre', 'Usuario') }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <h6 class="dropdown-header">
                                    <i class="fas fa-user me-2"></i>{{ session('usuario_nombre', 'Usuario') }}
                                </h6>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="/panel/{{ session('usuario_tipo', 'turista') }}">
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
                    </li>
                @else
                    <li class="nav-item ms-lg-3">
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#loginModal">
                            <i class="fas fa-user me-1"></i>Iniciar Sesión
                        </button>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>

<!-- Hero de Exploración -->
<section class="explorar-hero">
    <div class="hero-overlay-explorar"></div>
    <div class="hero-content-explorar">
        <div class="container">
            <h1 class="hero-title-explorar">Explora Quibdó</h1>
            <p class="hero-subtitle-explorar">Descubre experiencias únicas y planifica tu viaje perfecto</p>
            <div class="search-hero-box">
                <div class="search-input-group">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Buscar experiencias, destinos, actividades..." id="heroSearch">
                    <button class="btn btn-primary">
                        <i class="fas fa-search me-2"></i>Buscar
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Filtros y Ordenamiento -->
<section class="filters-section">
    <div class="container">
        <div class="filters-bar">
            <div class="filters-left">
                <button class="btn btn-outline-primary" id="toggleFilters">
                    <i class="fas fa-filter me-2"></i>Filtros
                </button>
                <div class="filter-chips" id="activeFilters">
                    <!-- Filtros activos aparecerán aquí -->
                </div>
            </div>
            <div class="filters-right">
                <label class="sort-label">Ordenar por:</label>
                <select class="form-select form-select-sm" id="sortSelect" style="width: auto;">
                    <option value="relevancia">Más Relevantes</option>
                    <option value="precio-asc">Precio: Menor a Mayor</option>
                    <option value="precio-desc">Precio: Mayor a Menor</option>
                    <option value="calificacion">Mejor Calificados</option>
                    <option value="nuevos">Más Recientes</option>
                </select>
            </div>
        </div>
        
        <div class="filters-panel-expanded" id="filtersPanelExpanded" style="display: none;">
            <div class="row g-4">
                <div class="col-md-3">
                    <h6 class="filter-group-title">Categoría</h6>
                    <div class="filter-options">
                        <label class="filter-option">
                            <input type="checkbox" value="naturaleza">
                            <span>Naturaleza</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="cultura">
                            <span>Cultura</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="gastronomia">
                            <span>Gastronomía</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="ecoturismo">
                            <span>Ecoturismo</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="festividades">
                            <span>Festividades</span>
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <h6 class="filter-group-title">Precio</h6>
                    <div class="price-range">
                        <input type="range" class="form-range" min="0" max="500000" step="10000" id="priceRange">
                        <div class="price-labels">
                            <span>$0</span>
                            <span id="priceValue">$250.000</span>
                            <span>$500.000+</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <h6 class="filter-group-title">Calificación</h6>
                    <div class="filter-options">
                        <label class="filter-option">
                            <input type="checkbox" value="5">
                            <span>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                                <i class="fas fa-star text-warning"></i>
                            </span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="4">
                            <span>4+ estrellas</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="3">
                            <span>3+ estrellas</span>
                        </label>
                    </div>
                </div>
                <div class="col-md-3">
                    <h6 class="filter-group-title">Duración</h6>
                    <div class="filter-options">
                        <label class="filter-option">
                            <input type="checkbox" value="1-2">
                            <span>1-2 horas</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="3-4">
                            <span>3-4 horas</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="5+">
                            <span>5+ horas</span>
                        </label>
                        <label class="filter-option">
                            <input type="checkbox" value="dia-completo">
                            <span>Día completo</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="filters-actions-bar">
                <button class="btn btn-primary" id="applyFiltersBtn">Aplicar Filtros</button>
                <button class="btn btn-outline-secondary" id="clearFiltersBtn">Limpiar Todo</button>
            </div>
        </div>
    </div>
</section>

<!-- Resultados -->
<section class="results-section">
    <div class="container">
        <div class="results-header">
            <h2 id="resultsCount">24 experiencias encontradas</h2>
            <div class="view-toggle">
                <button class="view-btn active" data-view="grid">
                    <i class="fas fa-th"></i>
                </button>
                <button class="view-btn" data-view="list">
                    <i class="fas fa-list"></i>
                </button>
            </div>
        </div>
        
        <div class="experiences-results" id="experiencesResults">
            <div class="row g-4" id="experiencesGrid">
                <!-- Las experiencias se cargarán aquí -->
            </div>
        </div>
        
        <div class="pagination-wrapper">
            <nav aria-label="Paginación">
                <ul class="pagination justify-content-center">
                    <li class="page-item disabled">
                        <a class="page-link" href="#" tabindex="-1">Anterior</a>
                    </li>
                    <li class="page-item active"><a class="page-link" href="#">1</a></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item">
                        <a class="page-link" href="#">Siguiente</a>
                    </li>
                </ul>
            </nav>
        </div>
    </div>
</section>

<!-- Modales -->
@include('modales.login')
@include('modales.register')

@push('scripts')
<script src="{{ asset('js/explorar.js') }}"></script>
@endpush
@endsection


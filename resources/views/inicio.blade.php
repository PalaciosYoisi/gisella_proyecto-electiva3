<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="usuario-autenticado" content="{{ session('autenticado') ? 'true' : 'false' }}">
    <meta name="description" content="ExploraQuibdó - Descubre la magia del Chocó, su riqueza cultural, biodiversidad y experiencias únicas en Quibdó">
    <title>ExploraQuibdó - Vive la Magia del Chocó</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700;800&family=Open+Sans:wght@300;400;600&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/inicio.css') }}">
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top" id="mainNavbar">
        <div class="container">
            <a class="navbar-brand" href="#inicio">
                <span class="brand-primary">Explora</span><span class="brand-secondary">Quibdó</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="#inicio">Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#destinos">Destinos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#experiencias">Experiencias</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#informacion">Información</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contacto">Contacto</a>
                    </li>
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
                            <button class="btn btn-primary btn-login" data-bs-toggle="modal" data-bs-target="#loginModal">
                                <i class="fas fa-user me-2"></i>Iniciar Sesión
                            </button>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section" id="inicio">
        <div class="hero-video-wrapper">
            <video class="hero-video" autoplay loop playsinline>
                <source src="{{ asset('video/quibdo_video.mp4') }}" type="video/mp4">
                Tu navegador no soporta video HTML5.
            </video>
            <div class="hero-overlay"></div>
        </div>
        <div class="hero-content">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10 text-center">
                        <h1 class="hero-title">Descubre la magia del Chocó</h1>
                        <p class="hero-subtitle">
                            Explora la riqueza cultural, la biodiversidad y las experiencias únicas que ofrece Quibdó, una tierra vibrante.
                        </p>
                        <div class="hero-buttons">
                            <a href="#destinos" class="btn btn-hero btn-hero-primary">
                                <i class="fas fa-map-marked-alt me-2"></i>Explorar Destinos
                            </a>
                            <button class="btn btn-hero btn-hero-secondary" data-bs-toggle="modal" data-bs-target="#registerModal">
                                <i class="fas fa-user-plus me-2"></i>Registrarse
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Destinos Imperdibles -->
    <section class="section-destinos py-5" id="destinos">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">
                    <i class="fas fa-map-marked-alt me-2"></i>Destinos Imperdibles
                </h2>
                <p class="section-subtitle">Descubre los lugares más emblemáticos de Quibdó y el Chocó</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card">
                        <div class="card-image-wrapper">
                            <img src="{{ asset('img/icho.jpg') }}" class="card-image" alt="Río Ichó" onerror="this.src='https://via.placeholder.com/400x250?text=Río+Ichó'">
                            <div class="card-overlay">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#destinoIchoModal">
                                    <i class="fas fa-eye me-1"></i>Ver Detalles
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Río Ichó</h5>
                            <p class="card-text">Disfruta de un paseo en bote por este majestuoso río, rodeado de exuberante vegetación.</p>
                            <div class="card-badges">
                                <span class="badge badge-nature">
                                    <i class="fas fa-water me-1"></i>Naturaleza
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card">
                        <div class="card-image-wrapper">
                            <img src="{{ asset('img/Catedral.jpg') }}" class="card-image" alt="Catedral San Francisco de Asís" onerror="this.src='https://via.placeholder.com/400x250?text=Catedral'">
                            <div class="card-overlay">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#destinoCatedralModal">
                                    <i class="fas fa-eye me-1"></i>Ver Detalles
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Catedral San Francisco de Asís</h5>
                            <p class="card-text">Un ícono arquitectónico y espiritual en el corazón de la ciudad.</p>
                            <div class="card-badges">
                                <span class="badge badge-culture">
                                    <i class="fas fa-church me-1"></i>Cultura
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="destination-card">
                        <div class="card-image-wrapper">
                            <img src="{{ asset('img/cascada.jpg') }}" class="card-image" alt="Cascada de Tutunendo" onerror="this.src='https://via.placeholder.com/400x250?text=Cascadas'">
                            <div class="card-overlay">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#destinoTutunendoModal">
                                    <i class="fas fa-eye me-1"></i>Ver Detalles
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Cascada de Tutunendo</h5>
                            <p class="card-text">Descubre paisajes de ensueño y senderos ecológicos únicos.</p>
                            <div class="card-badges">
                                <span class="badge badge-eco">
                                    <i class="fas fa-tree me-1"></i>Ecoturismo
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Experiencias Destacadas -->
    <section class="section-experiencias py-5" id="experiencias">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">
                    <i class="fas fa-star me-2"></i>Experiencias Destacadas
                </h2>
                <p class="section-subtitle">Vive la magia de Quibdó a través de sus experiencias únicas y actividades</p>
            </div>
            <div class="row g-4" id="experienciasContainer">
                <!-- Primera Fila - 4 Experiencias -->
                <!-- 1. Taller de Cocina Tradicional -->
                <div class="col-lg-3 col-md-6">
                    <div class="experience-card">
                        <div class="card-image-wrapper">
                            <img src="{{ asset('img/taller.jpg') }}" class="card-image" alt="Taller de Cocina Tradicional" onerror="this.src='https://via.placeholder.com/400x250?text=Taller+Cocina'">
                            <div class="card-overlay">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#experienciaGastronomiaModal">
                                    <i class="fas fa-eye me-1"></i>Ver Experiencia
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Taller de Cocina Tradicional</h5>
                            <p class="card-text">Aprende a preparar platos típicos chocoanos con chefs locales.</p>
                            <div class="card-footer-experiencia">
                                <span class="badge badge-gastronomy">
                                    <i class="fas fa-utensils me-1"></i>Gastronomía
                                </span>
                                <div class="card-rating">
                                    <span class="rating-value">4.8</span>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 2. Tour por el Río Atrato -->
                <div class="col-lg-3 col-md-6">
                    <div class="experience-card">
                        <div class="card-image-wrapper">
                            <img src="{{ asset('img/atrato.jpg') }}" class="card-image" alt="Tour por el Río Atrato" onerror="this.src='https://via.placeholder.com/400x250?text=Río+Atrato'">
                            <div class="card-overlay">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#experienciaAtratoModal">
                                    <i class="fas fa-eye me-1"></i>Ver Experiencia
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Tour por el Río Atrato</h5>
                            <p class="card-text">Navega por uno de los ríos más importantes de Colombia con guías expertos.</p>
                            <div class="card-footer-experiencia">
                                <span class="badge badge-nature">
                                    <i class="fas fa-water me-1"></i>Naturaleza
                                </span>
                                <div class="card-rating">
                                    <span class="rating-value">4.9</span>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 3. Clase de Danza Afro -->
                <div class="col-lg-3 col-md-6">
                    <div class="experience-card">
                        <div class="card-image-wrapper">
                            <img src="{{ asset('img/danza.jpg') }}" class="card-image" alt="Clase de Danza Afro" onerror="this.src='https://via.placeholder.com/400x250?text=Danza+Afro'">
                            <div class="card-overlay">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#experienciaCulturaModal">
                                    <i class="fas fa-eye me-1"></i>Ver Experiencia
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Clase de Danza Afro</h5>
                            <p class="card-text">Sumérgete en los ritmos y movimientos de la cultura afrocolombiana.</p>
                            <div class="card-footer-experiencia">
                                <span class="badge badge-culture">
                                    <i class="fas fa-music me-1"></i>Cultura
                                </span>
                                <div class="card-rating">
                                    <span class="rating-value">4.7</span>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 4. Avistamiento de Aves -->
                <div class="col-lg-3 col-md-6">
                    <div class="experience-card">
                        <div class="card-image-wrapper">
                            <img src="https://vivirenelpoblado.com/wp-content/uploads/2022/08/La-maravillosa-ruta-de-avistamiento-de-aves-del-sur-de-Colombia.jpg" class="card-image" alt="Avistamiento de Aves" onerror="this.src='https://via.placeholder.com/400x250?text=Avistamiento+Aves'">
                            <div class="card-overlay">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#experienciaAvesModal">
                                    <i class="fas fa-eye me-1"></i>Ver Experiencia
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Avistamiento de Aves</h5>
                            <p class="card-text">Descubre la increíble diversidad de aves en los bosques del Chocó.</p>
                            <div class="card-footer-experiencia">
                                <span class="badge badge-eco">
                                    <i class="fas fa-tree me-1"></i>Ecoturismo
                                </span>
                                <div class="card-rating">
                                    <span class="rating-value">4.9</span>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botón Ver Menos (inicialmente oculto) -->
            <div class="text-center mt-4" id="btnVerMenosContainer" style="display: none;">
                <button class="btn btn-outline-primary" id="btnVerMenosExperiencias">
                    <i class="fas fa-minus-circle me-2"></i>Ver menos
                </button>
            </div>
            
            <!-- Segunda Fila - 4 Experiencias (inicialmente oculta) -->
            <div class="row g-4 mt-0" id="experienciasFila2" style="display: none;">
                <!-- 5. Fiestas de San Pacho -->
                <div class="col-lg-3 col-md-6">
                    <div class="experience-card">
                        <div class="card-image-wrapper">
                            <video class="card-image" loop playsinline>
                                <source src="{{ asset('video/sanpacho.mp4') }}" type="video/mp4">
                                Tu navegador no soporta video HTML5.
                            </video>
                            <div class="card-overlay">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#eventoSanPachoModal">
                                    <i class="fas fa-eye me-1"></i>Ver Experiencia
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Fiestas de San Pacho</h5>
                            <p class="card-text">Vive la fiesta patronal más importante del Chocó, declarada Patrimonio Cultural de la Humanidad.</p>
                            <div class="card-footer-experiencia">
                                <span class="badge badge-culture">
                                    <i class="fas fa-calendar me-1"></i>Festividades
                                </span>
                                <div class="card-rating">
                                    <span class="rating-value">5.0</span>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 6. Ritmos Exóticos de Quibdó -->
                <div class="col-lg-3 col-md-6">
                    <div class="experience-card">
                        <div class="card-image-wrapper">
                            <video class="card-image" loop playsinline>
                                <source src="{{ asset('video/exotico.mp4') }}" type="video/mp4">
                                Tu navegador no soporta video HTML5.
                            </video>
                            <div class="card-overlay">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#eventoChirimiaModal">
                                    <i class="fas fa-eye me-1"></i>Ver Experiencia
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Ritmos Exóticos de Quibdó</h5>
                            <p class="card-text">Explora la chirimía, la música tradicional y los ritmos modernos que hacen vibrar al Chocó.</p>
                            <div class="card-footer-experiencia">
                                <span class="badge badge-culture">
                                    <i class="fas fa-music me-1"></i>Música
                                </span>
                                <div class="card-rating">
                                    <span class="rating-value">4.9</span>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 7. Alabaos y Gualies -->
                <div class="col-lg-3 col-md-6">
                    <div class="experience-card">
                        <div class="card-image-wrapper">
                            <video class="card-image" loop playsinline>
                                <source src="{{ asset('video/alabao.mp4') }}" type="video/mp4">
                                Tu navegador no soporta video HTML5.
                            </video>
                            <div class="card-overlay">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#experienciaAlabaoModal">
                                    <i class="fas fa-eye me-1"></i>Ver Experiencia
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Alabaos y Gualíes</h5>
                            <p class="card-text">Conoce los cantos ancestrales y rituales que preservan la memoria cultural del Pacífico.</p>
                            <div class="card-footer-experiencia">
                                <span class="badge badge-culture">
                                    <i class="fas fa-book me-1"></i>Tradición
                                </span>
                                <div class="card-actions">
                                    <button class="btn btn-sm btn-outline-primary" onclick="shareContent('whatsapp', window.location.href, 'Alabaos y Gualíes - ExploraQuibdó')">
                                        <i class="fas fa-share-alt me-1"></i>Compartir
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- 8. Sabores del Pacífico -->
                <div class="col-lg-3 col-md-6">
                    <div class="experience-card">
                        <div class="card-image-wrapper">
                            <img src="{{ asset('img/comida.jpg') }}" class="card-image" alt="Sabores del Pacífico" onerror="this.src='https://via.placeholder.com/400x250?text=Sabores+Pacífico'">
                            <div class="card-overlay">
                                <a href="#" class="btn btn-sm btn-light" data-bs-toggle="modal" data-bs-target="#experienciaSaboresModal">
                                    <i class="fas fa-eye me-1"></i>Ver Experiencia
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">Sabores del Pacífico</h5>
                            <p class="card-text">Degusta platos tradicionales como el pescado ahumado, el sancocho de pescado y el arroz clavado.</p>
                            <div class="card-footer-experiencia">
                                <span class="badge badge-gastronomy">
                                    <i class="fas fa-utensils me-1"></i>Gastronomía
                                </span>
                                <div class="card-rating">
                                    <span class="rating-value">4.9</span>
                                    <i class="fas fa-star text-warning"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Botón Ver Más (inicialmente visible) -->
            <div class="text-center mt-4" id="btnVerMasContainer">
                <button class="btn btn-outline-primary" id="btnVerMasExperiencias">
                    <i class="fas fa-plus-circle me-2"></i>Ver más experiencias
                </button>
            </div>
        </div>
    </section>

    <!-- Información Turística -->
    <section class="section-informacion py-5" id="informacion">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">
                    <i class="fas fa-info-circle me-2"></i>Información Turística
                </h2>
                <p class="section-subtitle">Consulta información relevante y planifica tu viaje</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="info-card">
                        <div class="info-card-header">
                            <h3 class="info-card-title">
                                <i class="fas fa-chart-line me-2"></i>Estadísticas Turísticas
                            </h3>
                            <p class="info-card-subtitle">Datos actualizados del turismo en Quibdó</p>
                        </div>
                        <div class="info-card-body">
                            <div class="row g-3">
                                <div class="col-6">
                                    <div class="stat-box" data-bs-toggle="modal" data-bs-target="#statVisitantesModal">
                                        <i class="fas fa-users stat-icon"></i>
                                        <div class="stat-value counter" data-target="15000">0</div>
                                        <div class="stat-label">Visitantes Mensuales</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-box" data-bs-toggle="modal" data-bs-target="#statSitiosModal">
                                        <i class="fas fa-map-marked-alt stat-icon"></i>
                                        <div class="stat-value counter" data-target="50">0</div>
                                        <div class="stat-label">Sitios Turísticos</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-box" data-bs-toggle="modal" data-bs-target="#statGuiasModal">
                                        <i class="fas fa-user-tie stat-icon"></i>
                                        <div class="stat-value counter" data-target="200">0</div>
                                        <div class="stat-label">Guías Locales</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="stat-box" data-bs-toggle="modal" data-bs-target="#statSatisfaccionModal">
                                        <i class="fas fa-smile stat-icon"></i>
                                        <div class="stat-value counter" data-target="95">0</div>
                                        <div class="stat-label">% Satisfacción</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="info-card">
                        <div class="info-card-header">
                            <h3 class="info-card-title">
                                <i class="fas fa-calendar-alt me-2"></i>Próximos Eventos
                            </h3>
                            <p class="info-card-subtitle">No te pierdas nuestros eventos culturales</p>
                        </div>
                        <div class="info-card-body">
                            <div class="events-list">
                                <div class="event-item">
                                    <div class="event-date">
                                        <span class="event-day">20</span>
                                        <span class="event-month">SEP</span>
                                    </div>
                                    <div class="event-content">
                                        <h4 class="event-title">Fiestas de San Pacho</h4>
                                        <p class="event-location">
                                            <i class="fas fa-map-marker-alt me-2"></i>Centro Histórico de Quibdó
                                        </p>
                                        <p class="event-description">Celebración del santo patrono de Quibdó con desfiles, música y gastronomía.</p>
                                        <div class="event-meta">
                                            <span class="event-time">
                                                <i class="fas fa-clock me-2"></i>Todo el día
                                            </span>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#eventoSanPachoModal">
                                                Más información
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-item">
                                    <div class="event-date">
                                        <span class="event-day">15</span>
                                        <span class="event-month">OCT</span>
                                    </div>
                                    <div class="event-content">
                                        <h4 class="event-title">Festival Gastronómico del Pacífico</h4>
                                        <p class="event-location">
                                            <i class="fas fa-map-marker-alt me-2"></i>Malecón de Quibdó
                                        </p>
                                        <p class="event-description">Degustación de platos típicos y showcooking con chefs locales.</p>
                                        <div class="event-meta">
                                            <span class="event-time">
                                                <i class="fas fa-clock me-2"></i>10:00 AM - 8:00 PM
                                            </span>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#eventoGastronomicoModal">
                                                Más información
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="event-item">
                                    <div class="event-date">
                                        <span class="event-day">05</span>
                                        <span class="event-month">NOV</span>
                                    </div>
                                    <div class="event-content">
                                        <h4 class="event-title">Festival de Chirimía</h4>
                                        <p class="event-location">
                                            <i class="fas fa-map-marker-alt me-2"></i>Plaza de la Cultura
                                        </p>
                                        <p class="event-description">Música tradicional del Chocó en vivo con artistas locales.</p>
                                        <div class="event-meta">
                                            <span class="event-time">
                                                <i class="fas fa-clock me-2"></i>4:00 PM - 10:00 PM
                                            </span>
                                            <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#eventoChirimiaModal">
                                                Más información
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    

    <!-- Sobre ExploraQuibdó -->
    <section class="section-about py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="about-map-wrapper">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63353.32145669714!2d-76.67162350895994!3d5.6922889325244615!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e488e2ac33cce7f%3A0x91aa5d91afc5c454!2sQuibd%C3%B3%2C%20Choc%C3%B3!5e0!3m2!1ses!2sco!4v1709534775144!5m2!1ses!2sco"
                            width="100%"
                            height="400"
                            style="border:0; border-radius: 15px;"
                            allowfullscreen=""
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade">
                        </iframe>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="about-content">
                        <h2 class="section-title text-start mb-4">Sobre ExploraQuibdó</h2>
                        <p class="lead mb-4">
                            ExploraQuibdó es una plataforma creada para promover el turismo en Quibdó y sus alrededores, conectando a visitantes con experiencias auténticas y a emprendedores locales con nuevas oportunidades.
                        </p>
                        <p class="mb-4">
                            Nuestro objetivo es visibilizar la riqueza cultural y natural del Chocó, contribuyendo al desarrollo económico de la región mientras preservamos nuestras tradiciones y medio ambiente.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="section-cta py-5">
        <div class="container text-center">
            <h2 class="cta-title mb-4">¿Listo para explorar Quibdó?</h2>
            <p class="cta-subtitle mb-4">
                Regístrate ahora y descubre todo lo que te espera en este magnífico lugar para tu próxima aventura.
            </p>
            <button class="btn btn-cta" data-bs-toggle="modal" data-bs-target="#registerModal">
                <i class="fas fa-user-plus me-2"></i>Crear Cuenta Gratis
            </button>
        </div>
    </section>

    <!-- Contacto -->
    <section class="section-contacto py-5" id="contacto">
        <div class="container">
            <div class="section-header text-center mb-5">
                <h2 class="section-title">
                    <i class="fas fa-envelope me-2"></i>Contáctanos
                </h2>
                <p class="section-subtitle">Estamos aquí para ayudarte a planificar tu viaje</p>
            </div>
            <div class="row g-4">
                <div class="col-lg-5">
                    <div class="contact-info-card">
                        <h3 class="contact-info-title mb-4">Información de Contacto</h3>
                        <ul class="contact-list">
                            <li class="contact-item">
                                <i class="fas fa-map-marker-alt contact-icon"></i>
                                <div>
                                    <strong>Dirección:</strong><br>
                                    Calle 12 # 23-45, Quibdó, Chocó
                                </div>
                            </li>
                            <li class="contact-item">
                                <i class="fas fa-phone contact-icon"></i>
                                <div>
                                    <strong>Teléfono:</strong><br>
                                    +57 310 123 4567
                                </div>
                            </li>
                            <li class="contact-item">
                                <i class="fas fa-envelope contact-icon"></i>
                                <div>
                                    <strong>Email:</strong><br>
                                    info@exploraquibdo.com
                                </div>
                            </li>
                            <li class="contact-item">
                                <i class="fas fa-globe contact-icon"></i>
                                <div>
                                    <strong>Sitio Web:</strong><br>
                                    www.exploraquibdo.com
                                </div>
                            </li>
                        </ul>
                        <div class="social-media mt-4">
                            <a href="#" class="social-link" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="#" class="social-link" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="contact-form-card">
                        <h3 class="contact-form-title mb-4">Envíanos un mensaje</h3>
                        <form id="contactForm">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="contactName" name="nombre" placeholder="Nombre completo" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="email" class="form-control" id="contactEmail" name="correo" placeholder="Correo electrónico" required>
                                </div>
                                <div class="col-md-6">
                                    <input type="tel" class="form-control" id="contactPhone" name="telefono" placeholder="Número de teléfono">
                                </div>
                                <div class="col-md-6">
                                    <input type="text" class="form-control" id="contactSubject" name="asunto" placeholder="Asunto" required>
                                </div>
                                <div class="col-12">
                                    <textarea class="form-control" id="contactMessage" name="mensaje" rows="5" placeholder="Mensaje" required></textarea>
                                </div>
                                <div class="col-12">
                                    <div class="alert alert-success d-none" id="contactSuccess">
                                        <i class="fas fa-check-circle me-2"></i>¡Mensaje enviado con éxito!
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100">
                                        <i class="fas fa-paper-plane me-2"></i>Enviar Mensaje
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4">
                    <h5 class="footer-title">Sobre ExploraQuibdó</h5>
                    <p class="footer-text">Descubre la magia del Chocó a través de experiencias auténticas y memorables.</p>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">Enlaces rápidos</h5>
                    <ul class="footer-links">
                        <li><a href="#inicio">Inicio</a></li>
                        <li><a href="#destinos">Destinos</a></li>
                        <li><a href="#experiencias">Experiencias</a></li>
                        <li><a href="#contacto">Contacto</a></li>
                    </ul>
                </div>
                <div class="col-lg-4">
                    <h5 class="footer-title">Síguenos</h5>
                    <div class="footer-social">
                        <a href="#" class="footer-social-link" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="footer-social-link" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="footer-social-link" aria-label="YouTube">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>
            </div>
            <hr class="footer-divider">
            <div class="row">
                <div class="col-md-6 text-center text-md-start">
                    <p class="footer-copyright">&copy; 2024 ExploraQuibdó. Todos los derechos reservados.</p>
                </div>
                <div class="col-md-6 text-center text-md-end">
                    <a href="#" class="footer-link">Términos y Condiciones</a> | 
                    <a href="#" class="footer-link">Política de Privacidad</a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Modales -->
    @include('modales.login')
    @include('modales.register')
    @include('modales.destinos')
    @include('modales.experiencias')
    @include('modales.eventos')
    @include('modales.estadisticas')
    @include('modales.videos')
    @include('modales.reserva')

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/inicio.js') }}"></script>
</body>
</html>

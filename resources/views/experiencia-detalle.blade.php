@extends('layouts.app')

@section('title', 'Detalle de Experiencia - ExploraQuibdó')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/experiencia-detalle.css') }}">
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
                <li class="nav-item"><a class="nav-link" href="/explorar">Explorar</a></li>
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

<!-- Galería Principal -->
<section class="experiencia-gallery">
    <div class="gallery-main">
        @if($publicacion->url_multimedia)
            <video id="mainVideo" controls autoplay loop playsinline style="width: 100%; height: 100%; object-fit: cover;">
                <source src="{{ $publicacion->url_multimedia }}" type="video/mp4">
                Tu navegador no soporta video HTML5.
            </video>
        @else
            <img src="{{ $publicacion->imagen_url ?? asset('img/placeholder.jpg') }}" alt="{{ $publicacion->titulo_publicacion }}" id="mainImage" onerror="this.src='https://via.placeholder.com/800x500?text={{ urlencode($publicacion->titulo_publicacion) }}'">
        @endif
        @if($publicacion->imagen_url && $publicacion->url_multimedia)
            <button class="btn-gallery-prev">
                <i class="fas fa-chevron-left"></i>
            </button>
            <button class="btn-gallery-next">
                <i class="fas fa-chevron-right"></i>
            </button>
        @endif
    </div>
    @if($publicacion->imagen_url && $publicacion->url_multimedia)
    <div class="gallery-thumbs">
        <img src="{{ $publicacion->imagen_url }}" alt="Imagen" class="thumb active">
        <img src="{{ $publicacion->url_multimedia }}" alt="Video" class="thumb">
    </div>
    @endif
</section>

<!-- Contenido Principal -->
<section class="experiencia-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-8">
                <!-- Header de la Experiencia -->
                <div class="experiencia-header">
                    <div class="experiencia-title-section">
                        <h1 class="experiencia-title">{{ $publicacion->titulo_publicacion }}</h1>
                        <div class="experiencia-meta">
                            <div class="location-meta">
                                <i class="fas fa-map-marker-alt"></i>
                                <span>{{ $publicacion->ubicacion ?? 'Quibdó, Chocó' }}</span>
                            </div>
                            <div class="rating-meta">
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($calificacion_promedio))
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i - 0.5 <= $calificacion_promedio)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="rating-value">{{ number_format($calificacion_promedio, 1) }}</span>
                                <span class="rating-count">({{ count($reseñas) }} reseñas)</span>
                            </div>
                        </div>
                    </div>
                    <div class="experiencia-actions">
                        <button class="btn btn-outline-danger btn-favorite-detail" data-publicacion-id="{{ $publicacion->id_publicacion }}" data-es-favorito="{{ $es_favorito ? 'true' : 'false' }}">
                            <i class="{{ $es_favorito ? 'fas' : 'far' }} fa-heart"></i>
                        </button>
                        <button class="btn btn-outline-primary" onclick="shareContent('whatsapp', window.location.href, '{{ $publicacion->titulo_publicacion }}')">
                            <i class="fas fa-share-alt me-2"></i>Compartir
                        </button>
                    </div>
                </div>

                <!-- Información Principal -->
                <div class="experiencia-info-card">
                    <h3>Acerca de esta experiencia</h3>
                    <p>{{ $publicacion->descripcion }}</p>
                    
                    @if(!empty($publicacion->incluye) && is_array($publicacion->incluye) && count($publicacion->incluye) > 0)
                    <h4 class="mt-4">Qué incluye</h4>
                    <ul class="includes-list">
                        @foreach($publicacion->incluye as $item)
                            <li><i class="fas fa-check text-success me-2"></i>{{ trim($item) }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if(!empty($publicacion->no_incluye) && is_array($publicacion->no_incluye) && count($publicacion->no_incluye) > 0)
                    <h4 class="mt-4">No incluye</h4>
                    <ul class="includes-list">
                        @foreach($publicacion->no_incluye as $item)
                            <li><i class="fas fa-times text-danger me-2"></i>{{ trim($item) }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if(!empty($publicacion->requisitos) && is_array($publicacion->requisitos) && count($publicacion->requisitos) > 0)
                    <h4 class="mt-4">Requisitos</h4>
                    <ul class="includes-list">
                        @foreach($publicacion->requisitos as $item)
                            <li><i class="fas fa-info-circle text-info me-2"></i>{{ trim($item) }}</li>
                        @endforeach
                    </ul>
                    @endif

                    @if($publicacion->duracion_horas)
                    <div class="mt-4">
                        <strong><i class="fas fa-clock me-2"></i>Duración:</strong> {{ $publicacion->duracion_horas }} horas
                    </div>
                    @endif

                    @if($publicacion->capacidad_maxima)
                    <div class="mt-2">
                        <strong><i class="fas fa-users me-2"></i>Capacidad máxima:</strong> {{ $publicacion->capacidad_maxima }} personas
                    </div>
                    @endif

                    @if($autor)
                    <div class="mt-4">
                        <h4>Prestador de servicio</h4>
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <i class="fas fa-user-circle" style="font-size: 3rem; color: #0067a3;"></i>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $autor->nombre_completo }}</h5>
                                @if($autor->descripcion_servicio)
                                    <p class="text-muted mb-0">{{ $autor->descripcion_servicio }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Reseñas -->
                <div class="experiencia-reviews">
                    <div class="reviews-header">
                        <h3>Reseñas</h3>
                        <div class="reviews-summary">
                            <div class="summary-rating">
                                <span class="big-rating">{{ number_format($calificacion_promedio, 1) }}</span>
                                <div class="stars">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($calificacion_promedio))
                                            <i class="fas fa-star text-warning"></i>
                                        @elseif($i - 0.5 <= $calificacion_promedio)
                                            <i class="fas fa-star-half-alt text-warning"></i>
                                        @else
                                            <i class="far fa-star text-warning"></i>
                                        @endif
                                    @endfor
                                </div>
                                <span class="reviews-count">{{ count($reseñas) }} reseñas</span>
                            </div>
                        </div>
                    </div>
                    <div class="reviews-list">
                        @forelse($reseñas as $reseña)
                        <div class="review-item">
                            <div class="review-header">
                                <div class="reviewer-info">
                                    <div class="reviewer-avatar">{{ substr($reseña->usuario['nombre'] ?? 'U', 0, 1) }}</div>
                                    <div>
                                        <h6>{{ $reseña->usuario['nombre'] ?? 'Usuario Anónimo' }}</h6>
                                        <div class="review-rating">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $reseña->calificacion)
                                                    <i class="fas fa-star text-warning"></i>
                                                @else
                                                    <i class="far fa-star text-warning"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                <span class="review-date">{{ \Carbon\Carbon::parse($reseña->fecha_reseña)->diffForHumans() }}</span>
                            </div>
                            <p class="review-text">{{ $reseña->comentario ?? 'Sin comentario' }}</p>
                        </div>
                        @empty
                        <div class="text-center py-4 text-muted">
                            <p>No hay reseñas aún. Sé el primero en dejar una reseña.</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar de Reserva -->
            <div class="col-lg-4">
                <div class="reserva-sidebar">
                    <div class="reserva-card-sticky">
                        <div class="reserva-price">
                            <span class="price-label">Desde</span>
                            <span class="price-amount">${{ number_format($publicacion->precio_aproximado, 0, ',', '.') }}</span>
                            <span class="price-unit">por persona</span>
                        </div>
                        <div class="reserva-form">
                            <div class="form-group">
                                <label>Fecha</label>
                                <input type="date" class="form-control" id="reservaFecha" 
                                       min="{{ date('Y-m-d') }}" 
                                       value="{{ $publicacion->fecha_evento ? \Carbon\Carbon::parse($publicacion->fecha_evento)->format('Y-m-d') : '' }}">
                            </div>
                            <div class="form-group">
                                <label>Participantes</label>
                                <div class="participants-selector">
                                    <button class="btn-count" data-action="decrease">-</button>
                                    <input type="number" class="form-control" value="1" min="1" 
                                           max="{{ $publicacion->capacidad_maxima ?? 999 }}" 
                                           id="participantsCount">
                                    <button class="btn-count" data-action="increase">+</button>
                                </div>
                                @if($publicacion->capacidad_maxima)
                                    <small class="text-muted">Máximo {{ $publicacion->capacidad_maxima }} personas</small>
                                @endif
                            </div>
                            <div class="reserva-total">
                                <div class="total-line">
                                    <span>${{ number_format($publicacion->precio_aproximado, 0, ',', '.') }} x <span id="totalPersonas">1</span> persona</span>
                                    <span id="subtotal">${{ number_format($publicacion->precio_aproximado, 0, ',', '.') }}</span>
                                </div>
                                <div class="total-line total-final">
                                    <span><strong>Total</strong></span>
                                    <span><strong id="totalFinal">${{ number_format($publicacion->precio_aproximado, 0, ',', '.') }}</strong></span>
                                </div>
                            </div>
                            <button class="btn btn-primary btn-lg w-100 mt-3" id="btnReservar" data-publicacion-id="{{ $publicacion->id_publicacion }}">
                                <i class="fas fa-calendar-check me-2"></i>Reservar Ahora
                            </button>
                            <p class="text-center text-muted small mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Cancelación gratuita hasta 24h antes
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="{{ asset('js/experiencia-detalle.js') }}"></script>
@endpush
@endsection



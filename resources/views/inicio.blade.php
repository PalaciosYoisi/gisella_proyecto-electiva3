<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>ExploraQuibdó - Descubre la magia del Chocó</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@700;600&family=Open+Sans:wght@300;400&display=swap"
    rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="{{ asset('css1/inicio.css') }}">
</head>

<body>
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg navbar-light sticky-top">
    <div class="container">
      <a class="navbar-brand" href="#inicio">
        <span style="color: var(--secondary-color);">Explora</span>Quibdó
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">

        <ul class="navbar-nav ms-auto">
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
            <a class="nav-link" href="#nosotros">Nosotros</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#contacto">Contacto</a>
          </li>
          <?php if (isset($_SESSION['usuario_nombre'])): ?>
            <li class="nav-item ms-lg-3 dropdown">
              <a class="btn btn-success dropdown-toggle" href="#" id="usuarioDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="usuarioDropdown">
                <li>
                  <form action="logout.php" method="post" style="margin:0;">
                    <button type="submit" class="dropdown-item">Cerrar sesión</button>
                  </form>
                </li>
              </ul>
            </li>
          <?php else: ?>
            <li class="nav-item ms-lg-3">
              <a class="btn btn-primary" href="#" data-bs-toggle="modal" data-bs-target="#loginModal"> Iniciar Sesión </a>
            </li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero-section position-relative" id="inicio">
    <!-- Video Background -->
    <div class="video-background">
      <video autoplay muted loop playsinline class="hero-video">
        <source src="{{ asset('video/quibdo_video.mp4') }}" type="video/mp4">
        Tu navegador no soporta el elemento de video.
      </video>
      <!-- Overlay para mejorar la legibilidad del texto -->
      <div class="video-overlay"></div>
    </div>

    <div class="container position-relative">
      <div class="row">
        <div class="col-lg-8 mx-auto slide-up text-center text-white">
          <h1 class="display-4 fw-bold mb-4">Descubre la magia del Chocó</h1>
          <p class="lead mb-5">Explora la riqueza cultural, la biodiversidad y las experiencias únicas que Quibdó tiene
            para ofrecerte.</p>
          <div class="d-flex justify-content-center gap-3">
            <a href="#destinos" class="btn btn-primary btn-lg pulse" onclick="scrollToDestinos(event)">Explorar Destinos</a>
            <a href="#" class="btn btn-outline-light btn-lg" data-bs-toggle="modal"
              data-bs-target="#registerModal">Registrarse</a>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section class="py-5">
    <div class="container">
      <div class="row g-4">
        <div class="col-md-4 fade-in">
          <div class="text-center p-4">
            <div class="feature-icon">
              <i class="fas fa-map-marked-alt"></i>
            </div>
            <h3>Rutas Turísticas</h3>
            <p>Descubre los mejores recorridos por Quibdó y sus alrededores, guiados por expertos locales.</p>
          </div>
        </div>
        <div class="col-md-4 fade-in">
          <div class="text-center p-4">
            <div class="feature-icon">
              <i class="fas fa-utensils"></i>
            </div>
            <h3>Gastronomía Local</h3>
            <p>Prueba los sabores auténticos de la cocina chocoana, preparada con tradición y pasión.</p>
          </div>
        </div>
        <div class="col-md-4 fade-in">
          <div class="text-center p-4">
            <div class="feature-icon">
              <i class="fas fa-music"></i>
            </div>
            <h3>Cultura Viva</h3>
            <p>Conoce las tradiciones, música y danzas que hacen único al pueblo afrocolombiano.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Panel Section -->
  <section class="panel-section py-5" id="paneles">
    <div class="container">
      <div class="text-center mb-5">
        <h2 class="section-title">Información Turística</h2>
        <p class="lead text-muted">Descubre las últimas estadísticas y eventos de nuestra región</p>
      </div>

      <div class="row g-4 justify-content-center">
        <!-- Panel de Estadísticas -->
        <div class="col-lg-5">
          <div class="panel-card dashboard-stats h-100">
            <div class="panel-header">
              <h3 class="panel-title">
                <i class="fas fa-chart-line me-2"></i>
                Estadísticas Turísticas
              </h3>
              <p class="panel-subtitle mb-0">Datos actualizados del turismo en Quibdó</p>
            </div>
            <div class="panel-body">
              <div class="row g-4">
                <div class="col-6">
                  <div class="stat-item text-center" data-bs-toggle="modal" data-bs-target="#visitantesModal">
                    <i class="fas fa-users stat-icon"></i>
                    <div class="stat-value counter" data-target="15000">0</div>
                    <div class="stat-label">Visitantes Mensuales</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="stat-item text-center" data-bs-toggle="modal" data-bs-target="#sitiosModal">
                    <i class="fas fa-map-marked-alt stat-icon"></i>
                    <div class="stat-value counter" data-target="50">0</div>
                    <div class="stat-label">Sitios Turísticos</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="stat-item text-center" data-bs-toggle="modal" data-bs-target="#guiasModal">
                    <i class="fas fa-user-tie stat-icon"></i>
                    <div class="stat-value counter" data-target="200">0</div>
                    <div class="stat-label">Guías Locales</div>
                  </div>
                </div>
                <div class="col-6">
                  <div class="stat-item text-center" data-bs-toggle="modal" data-bs-target="#satisfaccionModal">
                    <i class="fas fa-smile stat-icon"></i>
                    <div class="stat-value counter" data-target="95">0</div>
                    <div class="stat-label">% Satisfacción</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Panel de Próximos Eventos -->
        <div class="col-lg-7">
          <div class="panel-card events-panel h-100">
            <div class="panel-header">
              <h3 class="panel-title">
                <i class="fas fa-calendar-alt me-2"></i>
                Próximos Eventos
              </h3>
              <p class="panel-subtitle mb-0">No te pierdas nuestros eventos culturales</p>
            </div>
            <div class="panel-body">
              <div class="event-list">
                <div class="event-item">
                  <div class="event-date">
                    <div class="date-circle">
                      <span class="day">20</span>
                      <span class="month">SEP</span>
                    </div>
                  </div>
                  <div class="event-content">
                    <h4>Fiestas de San Pacho</h4>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Centro Histórico de Quibdó</p>
                    <p class="event-description">Celebración del santo patrono de Quibdó con desfiles, música y gastronomía.</p>
                    <div class="event-meta">
                      <span><i class="fas fa-clock me-2"></i>Todo el día</span>
                      <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#sanPachoModal">
                        Más información
                      </button>
                    </div>
                  </div>
                </div>
                <div class="event-item">
                  <div class="event-date">
                    <div class="date-circle">
                      <span class="day">15</span>
                      <span class="month">OCT</span>
                    </div>
                  </div>
                  <div class="event-content">
                    <h4>Festival Gastronómico del Pacífico</h4>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Malecón de Quibdó</p>
                    <p class="event-description">Degustación de platos típicos y showcooking con chefs locales.</p>
                    <div class="event-meta">
                      <span><i class="fas fa-clock me-2"></i>10:00 AM - 8:00 PM</span>
                      <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#festivalGastronomicoModal">
                        Más información
                      </button>
                    </div>
                  </div>
                </div>
                <div class="event-item">
                  <div class="event-date">
                    <div class="date-circle">
                      <span class="day">05</span>
                      <span class="month">NOV</span>
                    </div>
                  </div>
                  <div class="event-content">
                    <h4>Festival de Chirimía</h4>
                    <p><i class="fas fa-map-marker-alt me-2"></i>Plaza de la Cultura</p>
                    <p class="event-description">Música tradicional del Chocó en vivo con artistas locales.</p>
                    <div class="event-meta">
                      <span><i class="fas fa-clock me-2"></i>4:00 PM - 10:00 PM</span>
                      <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#chirimiaModal">
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

  <!-- Destinations Section -->
  <section class="py-5" id="destinos">
    <div class="container">
      <h2 class="text-center section-title slide-up">Destinos Imperdibles</h2>
      <div class="row g-4">
        <div class="col-lg-4 col-md-6 slide-up">
          <div class="card destination-card">
            <img src="{{ asset('img/cascada.jpg') }}" class="card-img-top" alt="Cascada de Tutunendo">
            <div class="card-body">
              <h5 class="card-title">Cascada de Tutunendo</h5>
              <p class="card-text">Una de las cascadas más hermosas del Chocó, con aguas cristalinas y un entorno selvático único.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 slide-up">
          <div class="card destination-card">
            <img src="imagenes/icho.jpg" class="card-img-top" alt="Río Icho">
            <div class="card-body">
              <h5 class="card-title">Río Icho</h5>
              <p class="card-text">Disfruta de un paseo en bote por este majestuoso río, rodeado de exuberante vegetación.</p>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-6 slide-up">
          <div class="card destination-card">
            <img src="imagenes/Catedral.jpg" class="card-img-top" alt="Malecón de Quibdó">
            <div class="card-body">
              <h5 class="card-title">Malecón de Quibdó</h5>
              <p class="card-text">El corazón de la ciudad, donde convergen cultura, gastronomía y la belleza del río Atrato.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Parallax Section -->
  <section class="py-5 parallax">
    <div class="container h-100">
      <div class="row h-100 align-items-center">
        <div class="col-lg-8 mx-auto text-center text-white">
          <h2 class="display-5 fw-bold mb-4">Vive experiencias únicas en Quibdó</h2>
          <p class="lead mb-5">Desde recorridos por la selva hasta talleres de música tradicional, cada experiencia te
            conectará con la esencia del Chocó.</p>
          <a href="#experiencias" class="btn btn-primary btn-lg">Descubrir Experiencias</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Experiences Section -->
  <section class="py-5" id="experiencias">
    <div class="container">
      <h2 class="text-center section-title slide-up">Experiencias Destacadas</h2>
      <!-- Experiencias principales -->
      <div class="row g-4">
        <div class="col-lg-3 col-md-6 slide-up">
          <div class="card h-100">
            <img src="imagenes/taller.jpg" class="card-img-top" alt="Taller de cocina tradicional">
            <div class="card-body">
              <h5 class="card-title">Taller de Cocina Tradicional</h5>
              <p class="card-text">Aprende a preparar platos típicos chocoanos con chefs locales.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-primary">Gastronomía</span>
                <small class="text-muted">4.8 <i class="fas fa-star text-warning"></i></small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 slide-up">
          <div class="card h-100">
            <img src="imagenes/atrato.jpg" class="card-img-top" alt="Tour por el río Atrato">
            <div class="card-body">
              <h5 class="card-title">Tour por el Río Atrato</h5>
              <p class="card-text">Navega por uno de los ríos más importantes de Colombia con guías expertos.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-primary">Naturaleza</span>
                <small class="text-muted">4.9 <i class="fas fa-star text-warning"></i></small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 slide-up">
          <div class="card h-100">
            <img src="imagenes/danza.jpg" class="card-img-top" alt="Clase de danza afro">
            <div class="card-body">
              <h5 class="card-title">Clase de Danza Afro</h5>
              <p class="card-text">Sumérgete en los ritmos y movimientos de la cultura afrocolombiana.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-primary">Cultura</span>
                <small class="text-muted">4.7 <i class="fas fa-star text-warning"></i></small>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-3 col-md-6 slide-up">
          <div class="card h-100">
            <img src="https://vivirenelpoblado.com/wp-content/uploads/2022/08/La-maravillosa-ruta-de-avistamiento-de-aves-del-sur-de-Colombia.jpg"
              class="card-img-top" alt="Avistamiento de aves">
            <div class="card-body">
              <h5 class="card-title">Avistamiento de Aves</h5>
              <p class="card-text">Descubre la increíble diversidad de aves en los bosques del Chocó.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-primary">Ecoturismo</span>
                <small class="text-muted">4.9 <i class="fas fa-star text-warning"></i></small>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Botón Ver Más -->
      <div class="text-center mt-4">
        <button class="btn btn-primary btn-lg" id="verMasExperiencias">
          <i class="fas fa-plus-circle me-2"></i>Ver más experiencias
        </button>
      </div>

      <!-- Experiencias adicionales (inicialmente ocultas) -->
      <div class="row g-4 mt-4" id="experienciasAdicionales" style="display: none;">
        <!-- San Pacho -->
        <div class="col-lg-3 col-md-6 slide-up">
          <div class="card h-100">
            <div class="card-img-top position-relative" style="height: 200px;">
              <video class="w-100 h-100" style="object-fit: cover;" controls playsinline id="sanPachoVideo">
                <source src="video/sanpacho.mp4" type="video/mp4">
                Tu navegador no soporta el elemento de video.
              </video>
            </div>
            <div class="card-body">
              <h5 class="card-title">Fiestas de San Pacho</h5>
              <p class="card-text">Vive la fiesta patronal más importante del Chocó, declarada Patrimonio Cultural de la Humanidad.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-primary">Festividades</span>
                <small class="text-muted">5.0 <i class="fas fa-star text-warning"></i></small>
              </div>
            </div>
          </div>
        </div>
        <!-- Chirimía -->
        <div class="col-lg-3 col-md-6 slide-up">
          <div class="card h-100">
            <div class="card-img-top position-relative" style="height: 200px;">
              <video class="w-100 h-100" style="object-fit: cover;" controls playsinline id="exoticoVideo">
                <source src="video/exotico.mp4" type="video/mp4">
                Tu navegador no soporta el elemento de video.
              </video>
            </div>
            <div class="card-body">
              <h5 class="card-title">Ritmos Exóticos de Quibdó</h5>
              <p class="card-text">Explora la chirimía, la música tradicional y los ritmos modernos que hacen vibrar al Chocó.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-primary">Música</span>
                <small class="text-muted">4.9 <i class="fas fa-star text-warning"></i></small>
              </div>
            </div>
          </div>
        </div>
        <!-- Alabaos -->
        <div class="col-lg-3 col-md-6 slide-up">
          <div class="card h-100">
            <div class="card-img-top position-relative" style="height: 200px;">
              <video class="w-100 h-100" style="object-fit: cover;" controls playsinline>
                <source src="video/alabao.mp4" type="video/mp4">
                Tu navegador no soporta el elemento de video.
              </video>
            </div>
            <div class="card-body">
              <h5 class="card-title">Alabaos y Gualíes</h5>
              <p class="card-text">Conoce los cantos ancestrales y rituales que preservan la memoria cultural del Pacífico.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-primary">Tradición</span>
                <button class="btn btn-sm btn-outline-primary" onclick="shareVideo()">
                  <i class="fas fa-share-alt"></i> Compartir
                </button>
              </div>
            </div>
          </div>
        </div>
        <!-- Gastronomía -->
        <div class="col-lg-3 col-md-6 slide-up">
          <div class="card h-100">
            <img src="imagenes/comida.jpg" class="card-img-top" alt="Sabores del Pacífico">
            <div class="card-body">
              <h5 class="card-title">Sabores del Pacífico</h5>
              <p class="card-text">Degusta platos tradicionales como el pescado ahumado, el sancocho de pescado y el arroz clavado.</p>
              <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-primary">Gastronomía</span>
                <small class="text-muted">4.9 <i class="fas fa-star text-warning"></i></small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Nosotros Section -->
  <section class="py-5" id="nosotros">
    <div class="container">
      <div class="row align-items-center">
        <div class="col-lg-6 mb-4 mb-lg-0">
          <div class="about-image-container">
            <div class="map-container rounded shadow-sm">
              <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d63353.32145669714!2d-76.67162350895994!3d5.6922889325244615!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x8e488e2ac33cce7f%3A0x91aa5d91afc5c454!2sQuibd%C3%B3%2C%20Choc%C3%B3!5e0!3m2!1ses!2sco!4v1709534775144!5m2!1ses!2sco"
                width="100%"
                height="250"
                style="border:0;"
                allowfullscreen=""
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade">
              </iframe>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="about-content">
            <h2 class="section-title mb-4">Sobre ExploraQuibdó</h2>
            <p class="lead mb-4">
              ExploraQuibdó es una plataforma creada para promover el turismo en Quibdó y sus
              alrededores, conectando a visitantes con experiencias auténticas y a emprendedores
              locales con nuevas oportunidades.
            </p>
            <p class="mb-4">
              Nuestro objetivo es visibilizar la riqueza cultural y natural del Chocó, contribuyendo al
              desarrollo económico de la región mientras preservamos nuestras tradiciones y medio
              ambiente.
            </p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Call to Action Section -->
  <section class="py-5 bg-light">
    <div class="container text-center">
      <h2 class="display-5 mb-4">¿Listo para explorar Quibdó?</h2>
      <p class="lead mb-4">Regístrate ahora y descubre todo lo que esta maravillosa región tiene para ofrecerte.</p>
      <a href="#" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#registerModal">
        Crear Cuenta Gratis
      </a>
    </div>
  </section>

  <!-- Contacto Section -->
  <section class="py-5" id="contacto">
    <div class="container">
      <h2 class="text-center section-title mb-5">Contáctanos</h2>

      <div class="row">
        <!-- Información de Contacto -->
        <div class="col-lg-5 mb-4">
          <div class="contact-info p-4 bg-white rounded shadow-sm">
            <h3 class="mb-4">Información de Contacto</h3>

            <div class="d-flex align-items-start mb-3">
              <i class="fas fa-map-marker-alt text-primary me-3 mt-1"></i>
              <div>
                <strong>Dirección:</strong><br>
                Calle 12 # 10-20, Quibdó, Chocó
              </div>
            </div>

            <div class="d-flex align-items-start mb-3">
              <i class="fas fa-phone text-primary me-3 mt-1"></i>
              <div>
                <strong>Teléfono:</strong><br>
                +57 123 456 7890
              </div>
            </div>

            <div class="d-flex align-items-start mb-3">
              <i class="fas fa-envelope text-primary me-3 mt-1"></i>
              <div>
                <strong>Email:</strong><br>
                info@exploraquibdo.com
              </div>
            </div>

            <div class="d-flex align-items-start mb-4">
              <i class="fas fa-clock text-primary me-3 mt-1"></i>
              <div>
                <strong>Horario:</strong><br>
                Lunes a Viernes, 8:00 AM - 6:00 PM
              </div>
            </div>

            <div class="social-links mt-4">
              <a href="#" class="me-3"><i class="fab fa-facebook-f"></i></a>
              <a href="#" class="me-3"><i class="fab fa-twitter"></i></a>
              <a href="#" class="me-3"><i class="fab fa-instagram"></i></a>
              <a href="#"><i class="fab fa-youtube"></i></a>
            </div>
          </div>
        </div>

        <!-- Formulario de Contacto -->
        <div class="col-lg-7">
          <div class="contact-form p-4 bg-white rounded shadow-sm">
            <h3 class="mb-4">Envíanos un Mensaje</h3>
            <form id="contactForm" method="POST">
              <div class="mb-3">
                <input type="text" class="form-control" id="nombre" name="nombre"
                  placeholder="Nombre completo" required>
              </div>

              <div class="mb-3">
                <input type="email" class="form-control" id="correo" name="correo"
                  placeholder="Correo electrónico" required>
              </div>

              <div class="mb-3">
                <input type="tel" class="form-control" id="telefono" name="telefono"
                  placeholder="Número de teléfono">
              </div>

              <div class="mb-3">
                <input type="text" class="form-control" id="asunto" name="asunto"
                  placeholder="Asunto" required>
              </div>

              <div class="mb-3">
                <textarea class="form-control" id="mensaje" name="mensaje" rows="5"
                  placeholder="Mensaje" required></textarea>
              </div>

              <div class="alert alert-success" id="mensajeExito" style="display: none;">
                ¡Mensaje enviado con éxito!
              </div>

              <button type="submit" class="btn btn-primary">
                ENVIAR MENSAJE
              </button>
            </form>
          </div>

          <script>
            document.getElementById('contactForm').addEventListener('submit', function(e) {
              e.preventDefault();

              const form = this;
              const formData = new FormData(form);
              const mensajeExito = document.getElementById('mensajeExito');
              const submitButton = this.querySelector('button[type="submit"]');

              submitButton.disabled = true;

              fetch('contactanos.php', {
                  method: 'POST',
                  body: formData
                })
                .then(() => {
                  form.reset();
                  mensajeExito.style.display = 'block';
                  setTimeout(() => {
                    mensajeExito.style.display = 'none';
                  }, 3000);
                  submitButton.disabled = false;
                });
            });
          </script>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="row">
        <div class="col-lg-4 mb-4">
          <h5>Sobre ExploraQuibdó</h5>
          <p>Descubre la magia del Chocó a través de experiencias auténticas y memorables.</p>
        </div>
        <div class="col-lg-4 mb-4">
          <h5>Enlaces Rápidos</h5>
          <ul class="list-unstyled">
            <li><a href="#inicio">Inicio</a></li>
            <li><a href="#destinos">Destinos</a></li>
            <li><a href="#experiencias">Experiencias</a></li>
            <li><a href="#contacto">Contacto</a></li>
          </ul>
        </div>
        <div class="col-lg-4 mb-4">
          <h5>Síguenos</h5>
          <div class="social-icons">
            <a href="#"><i class="fab fa-facebook-f"></i></a>
            <a href="#"><i class="fab fa-instagram"></i></a>
            <a href="#"><i class="fab fa-twitter"></i></a>
            <a href="#"><i class="fab fa-youtube"></i></a>
          </div>
        </div>
      </div>
      <hr class="mt-4 mb-4">
      <div class="row">
        <div class="col-md-6 text-center text-md-start">
          <p class="mb-0">&copy; 2024 ExploraQuibdó. Todos los derechos reservados.</p>
        </div>
        <div class="col-md-6 text-center text-md-end">
          <a href="#">Términos y Condiciones</a> | <a href="#">Política de Privacidad</a>
        </div>
      </div>
    </div>
  </footer>

  <!-- Login Modal -->
  <div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0 pb-0">
          <div class="w-100 text-center">
            <h4 class="modal-title fw-bold" id="loginModalLabel">¡Bienvenido de nuevo!</h4>
            <p class="text-muted mb-0">Ingresa tus credenciales para continuar</p>
          </div>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body px-4 py-4">
          <div class="login-form">
            <form id="" action="login.php" method="POST" class="needs-validation" novalidate>
              <div class="mb-4">
                <label for="correo" class="form-label">Correo electrónico</label>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-envelope text-muted"></i>
                  </span>
                  <input type="email" class="form-control form-control-lg" id="email" name="email" placeholder="tu@email.com" required>
                  <div class="invalid-feedback">
                    Por favor, ingresa un correo electrónico válido.
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <div class="d-flex justify-content-between align-items-center">
                  <label for="password" class="form-label">Contraseña</label>
                  <a href="#" class="text-primary text-decoration-none small">¿Olvidaste tu contraseña?</a>
                </div>
                <div class="input-group">
                  <span class="input-group-text bg-light">
                    <i class="fas fa-lock text-muted"></i>
                  </span>
                  <input type="password" class="form-control form-control-lg" id="password" name="password"
                    placeholder="Ingresa tu contraseña" required minlength="6">
                  <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                    <i class="fas fa-eye"></i>
                  </button>
                  <div class="invalid-feedback">
                    La contraseña debe tener al menos 6 caracteres.
                  </div>
                </div>
              </div>

              <div class="mb-4">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="rememberMe" name="remember_me">
                  <label class="form-check-label" for="rememberMe">Mantener sesión iniciada</label>
                </div>
              </div>

              <div class="alert d-none" id="loginError" role="alert"></div>

              <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">
                  <i class="fas fa-sign-in-alt me-2"></i>Iniciar Sesión
                </button>
              </div>
            </form>

            <div class="separator text-center my-4">
              <span class="separator-text"></span>
            </div>

            <div class="social-login">
              <div class="d-grid gap-2">

              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <p class="mb-0">¿No tienes una cuenta?
            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#registerModal" data-bs-dismiss="modal">
              Regístrate aquí
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Register Modal -->
  <div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header border-0">
          <h5 class="modal-title" id="registerModalLabel">Crear Cuenta</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body px-4 py-3">
          <div class="login-form">
            <form id="registerForm" action="registro.php" method="POST" class="needs-validation" novalidate>
              <div class="mb-3">
                <label for="registerName" class="form-label">Nombre completo</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-user"></i></span>
                  <input type="text" class="form-control" id="registerName" name="nombre"
                    placeholder="Tu nombre completo" required>
                  <div class="invalid-feedback">
                    Por favor, ingresa tu nombre completo.
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="registerEmail" class="form-label">Correo electrónico</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                  <input type="email" class="form-control" id="registerEmail" name="correo"
                    placeholder="tu@email.com" required>
                  <div class="invalid-feedback">
                    Por favor, ingresa un correo electrónico válido.
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="registerPhone" class="form-label">Teléfono (opcional)</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-phone"></i></span>
                  <input type="tel" class="form-control" id="registerPhone" name="telefono"
                    placeholder="Tu número de teléfono">
                </div>
              </div>
              <div class="mb-3">
                <label for="registerPassword" class="form-label">Contraseña</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" class="form-control" id="registerPassword" name="contraseña"
                    placeholder="Mínimo 8 caracteres" required minlength="8">
                  <button class="btn btn-outline-secondary" type="button" id="toggleRegisterPassword">
                    <i class="fas fa-eye"></i>
                  </button>
                  <div class="invalid-feedback">
                    La contraseña debe tener al menos 8 caracteres.
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirmar contraseña</label>
                <div class="input-group">
                  <span class="input-group-text"><i class="fas fa-lock"></i></span>
                  <input type="password" class="form-control" id="confirmPassword" name="confirmar"
                    placeholder="Repite tu contraseña" required>
                  <div class="invalid-feedback">
                    Las contraseñas no coinciden.
                  </div>
                </div>
              </div>
              <div class="mb-3">
                <div class="form-check">
                  <input type="checkbox" class="form-check-input" id="acceptTerms" required>
                  <label class="form-check-label" for="acceptTerms">
                    Acepto los <a href="#">términos y condiciones</a>
                  </label>
                  <div class="invalid-feedback">
                    Debes aceptar los términos y condiciones para continuar.
                  </div>
                </div>
              </div>
              <div class="alert alert-danger d-none" id="registerError" role="alert">
              </div>
              <div class="d-grid">
                <button type="submit" class="btn btn-primary">
                  <i class="fas fa-user-plus me-2"></i>Crear Cuenta
                </button>
              </div>
            </form>
          </div>
        </div>
        <div class="modal-footer border-0 justify-content-center">
          <p class="mb-0">¿Ya tienes una cuenta?
            <a href="#" class="text-primary" data-bs-toggle="modal" data-bs-target="#loginModal" data-bs-dismiss="modal">
              Inicia sesión
            </a>
          </p>
        </div>
      </div>
    </div>
  </div>

  <!-- Modales de Estadísticas -->
  <!-- Modal Visitantes -->
  <div class="modal fade" id="visitantesModal" tabindex="-1" aria-labelledby="visitantesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="visitantesModalLabel">
            <i class="fas fa-users me-2"></i>
            Estadísticas de Visitantes
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="stats-detail">
            <div class="chart-container mb-4">
              <canvas id="visitantesChart"></canvas>
            </div>
            <div class="stats-info">
              <h6 class="mb-3">Desglose de Visitantes:</h6>
              <ul class="list-unstyled">
                <li class="mb-2">
                  <i class="fas fa-plane-arrival me-2"></i>
                  <strong>Turistas Nacionales:</strong> 10,500
                </li>
                <li class="mb-2">
                  <i class="fas fa-globe-americas me-2"></i>
                  <strong>Turistas Internacionales:</strong> 4,500
                </li>
                <li class="mb-2">
                  <i class="fas fa-chart-line me-2"></i>
                  <strong>Crecimiento Mensual:</strong> +15%
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Sitios Turísticos -->
  <div class="modal fade" id="sitiosModal" tabindex="-1" aria-labelledby="sitiosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="sitiosModalLabel">
            <i class="fas fa-map-marked-alt me-2"></i>
            Sitios Turísticos Destacados
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row g-4">
            <div class="col-md-6">
              <div class="site-card">
                <img src="imagenes/cascada.jpg" alt="Cascada de Tutunendo" class="img-fluid rounded">
                <h6 class="mt-2">Cascada de Tutunendo</h6>
                <div class="rating">
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star-half-alt text-warning"></i>
                  <span class="ms-2">4.5</span>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="site-card">
                <img src="imagenes/Catedral.jpg" alt="Catedral" class="img-fluid rounded">
                <h6 class="mt-2">Catedral San Francisco de Asís</h6>
                <div class="rating">
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <span class="ms-2">5.0</span>
                </div>
              </div>
            </div>
          </div>
          <div class="text-center mt-4">
            <a href="#destinos" class="btn btn-primary">Ver Todos los Sitios</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Guías -->
  <div class="modal fade" id="guiasModal" tabindex="-1" aria-labelledby="guiasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="guiasModalLabel">
            <i class="fas fa-user-tie me-2"></i>
            Nuestros Guías Turísticos
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <!-- Columna de Información -->
            <div class="col-lg-6">
              <div class="guides-info">
                <div class="guide-description mb-4">
                  <h6 class="fw-bold mb-3">¿Por qué elegir nuestros guías?</h6>
                  <ul class="list-unstyled">
                    <li class="mb-2">
                      <i class="fas fa-check-circle text-success me-2"></i>
                      Certificados por el Ministerio de Turismo
                    </li>
                    <li class="mb-2">
                      <i class="fas fa-check-circle text-success me-2"></i>
                      Conocimiento profundo de la cultura local
                    </li>
                    <li class="mb-2">
                      <i class="fas fa-check-circle text-success me-2"></i>
                      Multilingües y profesionales
                    </li>
                    <li class="mb-2">
                      <i class="fas fa-check-circle text-success me-2"></i>
                      Expertos en seguridad y primeros auxilios
                    </li>
                  </ul>
                </div>

                <div class="guide-stats mb-4">
                  <h6 class="fw-bold mb-3">Nuestras Especialidades:</h6>
                  <div class="row g-3">
                    <div class="col-6">
                      <div class="specialty-item">
                        <i class="fas fa-hiking me-2"></i>
                        <span class="specialty-title">Ecoturismo</span>
                        <div class="specialty-desc">Rutas naturales y observación de flora y fauna</div>
                        <div class="specialty-count">80 guías</div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="specialty-item">
                        <i class="fas fa-history me-2"></i>
                        <span class="specialty-title">Historia y Cultura</span>
                        <div class="specialty-desc">Patrimonio cultural y tradiciones locales</div>
                        <div class="specialty-count">60 guías</div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="specialty-item">
                        <i class="fas fa-utensils me-2"></i>
                        <span class="specialty-title">Gastronomía</span>
                        <div class="specialty-desc">Tours culinarios y experiencias gastronómicas</div>
                        <div class="specialty-count">40 guías</div>
                      </div>
                    </div>
                    <div class="col-6">
                      <div class="specialty-item">
                        <i class="fas fa-camera me-2"></i>
                        <span class="specialty-title">Fotografía</span>
                        <div class="specialty-desc">Rutas fotográficas y spots instagrameables</div>
                        <div class="specialty-count">20 guías</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Columna de Formulario de Contacto -->
            <div class="col-lg-6">
              <div class="contact-guide-form">
                <h6 class="fw-bold mb-3">Contacta a un Guía</h6>
                <form id="guideContactForm">
                  <div class="mb-3">
                    <label for="guideName" class="form-label">Nombre completo</label>
                    <input type="text" class="form-control" id="guideName" required>
                  </div>
                  <div class="mb-3">
                    <label for="guideEmail" class="form-label">Correo electrónico</label>
                    <input type="email" class="form-control" id="guideEmail" required>
                  </div>
                  <div class="mb-3">
                    <label for="guidePhone" class="form-label">Teléfono</label>
                    <input type="tel" class="form-control" id="guidePhone">
                  </div>
                  <div class="mb-3">
                    <label for="guideSpecialty" class="form-label">Especialidad requerida</label>
                    <select class="form-select" id="guideSpecialty" required>
                      <option value="">Selecciona una especialidad</option>
                      <option value="ecoturismo">Ecoturismo</option>
                      <option value="historia">Historia y Cultura</option>
                      <option value="gastronomia">Gastronomía</option>
                      <option value="fotografia">Fotografía</option>
                    </select>
                  </div>
                  <div class="mb-3">
                    <label for="guideDatePreference" class="form-label">Fecha preferida</label>
                    <input type="date" class="form-control" id="guideDatePreference" required>
                  </div>
                  <div class="mb-3">
                    <label for="guideMessage" class="form-label">Mensaje o requerimientos especiales</label>
                    <textarea class="form-control" id="guideMessage" rows="3"></textarea>
                  </div>
                  <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                      <i class="fas fa-paper-plane me-2"></i>Enviar Solicitud
                    </button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Satisfacción -->
  <div class="modal fade" id="satisfaccionModal" tabindex="-1" aria-labelledby="satisfaccionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="satisfaccionModalLabel">
            <i class="fas fa-smile me-2"></i>
            Índice de Satisfacción
          </h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="satisfaction-stats">
            <div class="chart-container mb-4">
              <canvas id="satisfactionChart"></canvas>
            </div>
            <div class="testimonial-preview">
              <h6 class="mb-3">Comentarios Recientes:</h6>
              <div class="testimonial-item">
                <div class="rating mb-2">
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                  <i class="fas fa-star text-warning"></i>
                </div>
                <p class="testimonial-text">"Una experiencia inolvidable. Los guías son muy profesionales y conocedores."</p>
                <small class="text-muted">- María G.</small>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Modales de Eventos -->
  <!-- Modal San Pacho -->
  <div class="modal fade" id="sanPachoModal" tabindex="-1" aria-labelledby="sanPachoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="sanPachoModalLabel">Fiestas de San Pacho</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Carrusel de imágenes principal -->
          <div id="sanPachoCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#sanPachoCarousel" data-bs-slide-to="0" class="active"></button>
              <button type="button" data-bs-target="#sanPachoCarousel" data-bs-slide-to="1"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="imagenes/ceremoniareligiosa.jpg" class="d-block w-100" alt="Ceremonia Religiosa San Pacho" style="height: 400px; object-fit: cover;">
              </div>
              <div class="carousel-item">
                <img src="imagenes/catedral.jpg" class="d-block w-100" alt="Catedral de San Pacho" style="height: 400px; object-fit: cover;">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#sanPachoCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#sanPachoCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Siguiente</span>
            </button>
          </div>

          <div class="row">
            <div class="col-md-12">
              <h6 class="fw-bold text-primary">Descripción del Evento</h6>
              <p>Las Fiestas de San Francisco de Asís, conocidas como "San Pacho", son la máxima expresión cultural y religiosa del pueblo chocoano. Declaradas Patrimonio Cultural Inmaterial de la Humanidad por la UNESCO, estas fiestas combinan la devoción religiosa con la alegría y el colorido de las tradiciones afrocolombianas.</p>

              <h6 class="fw-bold text-primary mt-4">Detalles del Evento</h6>
              <div class="row">
                <div class="col-md-6">
                  <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-calendar me-2 text-primary"></i><strong>Fecha:</strong> 20 de Septiembre - 4 de Octubre</li>
                    <li class="mb-2"><i class="fas fa-clock me-2 text-primary"></i><strong>Horario:</strong> Eventos durante todo el día</li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i><strong>Ubicación:</strong> Centro Histórico de Quibdó</li>
                    <li class="mb-2"><i class="fas fa-users me-2 text-primary"></i><strong>Organizador:</strong> Fundación Fiestas Franciscanas de Quibdó</li>
                  </ul>
                </div>
              </div>

              <h6 class="fw-bold text-primary mt-4">Actividades Principales</h6>
              <div class="row">
                <div class="col-md-6">
                  <ul>
                    <li>Procesión del Santo Patrono</li>
                    <li>Desfiles de comparsas y disfraces</li>
                    <li>Presentaciones de chirimía tradicional</li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul>
                    <li>Gastronomía típica chocoana</li>
                    <li>Verbenas populares</li>
                    <li>Eventos culturales</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <a href="#" class="btn btn-primary">Más información</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Festival Gastronómico -->
  <div class="modal fade" id="festivalGastronomicoModal" tabindex="-1" aria-labelledby="festivalGastronomicoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header bg-primary text-white">
          <h5 class="modal-title" id="festivalGastronomicoModalLabel">Festival Gastronómico del Pacífico</h5>
          <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- Carrusel de imágenes principal -->
          <div id="festivalGastronomicoCarousel" class="carousel slide mb-4" data-bs-ride="carousel">
            <div class="carousel-indicators">
              <button type="button" data-bs-target="#festivalGastronomicoCarousel" data-bs-slide-to="0" class="active"></button>
              <button type="button" data-bs-target="#festivalGastronomicoCarousel" data-bs-slide-to="1"></button>
              <button type="button" data-bs-target="#festivalGastronomicoCarousel" data-bs-slide-to="2"></button>
              <button type="button" data-bs-target="#festivalGastronomicoCarousel" data-bs-slide-to="3"></button>
            </div>
            <div class="carousel-inner">
              <div class="carousel-item active">
                <img src="imagenes/ps.jpeg" class="d-block w-100" alt="Festival Gastronómico" style="height: 400px; object-fit: cover;">
              </div>
              <div class="carousel-item">
                <img src="imagenes/Degustación.jpeg" class="d-block w-100" alt="Degustación de platos típicos" style="height: 400px; object-fit: cover;">
              </div>
              <div class="carousel-item">
                <img src="imagenes/comida.jpg" class="d-block w-100" alt="Platos típicos del Pacífico" style="height: 400px; object-fit: cover;">
              </div>
              <div class="carousel-item">
                <img src="imagenes/Chef.jpg" class="d-block w-100" alt="Chef preparando comida tradicional" style="height: 400px; object-fit: cover;">
              </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#festivalGastronomicoCarousel" data-bs-slide="prev">
              <span class="carousel-control-prev-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Anterior</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#festivalGastronomicoCarousel" data-bs-slide="next">
              <span class="carousel-control-next-icon" aria-hidden="true"></span>
              <span class="visually-hidden">Siguiente</span>
            </button>
          </div>

          <div class="row">
            <div class="col-md-12">
              <h6 class="fw-bold text-primary">Descripción del Evento</h6>
              <p>El Festival Gastronómico del Pacífico es una celebración de los sabores ancestrales y la riqueza culinaria de la región. Los visitantes podrán degustar platos tradicionales preparados por cocineras locales que preservan las recetas transmitidas de generación en generación.</p>

              <h6 class="fw-bold text-primary mt-4">Detalles del Evento</h6>
              <div class="row">
                <div class="col-md-6">
                  <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-calendar me-2 text-primary"></i><strong>Fecha:</strong> 15 de Octubre, 2024</li>
                    <li class="mb-2"><i class="fas fa-clock me-2 text-primary"></i><strong>Horario:</strong> 10:00 AM - 8:00 PM</li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-map-marker-alt me-2 text-primary"></i><strong>Ubicación:</strong> Malecón de Quibdó</li>
                    <li class="mb-2"><i class="fas fa-ticket-alt me-2 text-primary"></i><strong>Entrada:</strong> Gratuita</li>
                  </ul>
                </div>
              </div>

              <h6 class="fw-bold text-primary mt-4">Platos Destacados</h6>
              <div class="row">
                <div class="col-md-6">
                  <ul>
                    <li>Sancocho de pescado</li>
                    <li>Arroz con mariscos</li>
                    <li>Pescado encocado</li>
                  </ul>
                </div>
                <div class="col-md-6">
                  <ul>
                    <li>Tapao de pescado</li>
                    <li>Dulces típicos chocoanos</li>
                    <li>Bebidas tradicionales</li>
                  </ul>
                </div>
              </div>

              <div class="alert alert-info mt-4">
                <i class="fas fa-info-circle me-2"></i>
                <strong>Nota:</strong> Durante el festival, los chefs locales realizarán demostraciones en vivo de la preparación de platos tradicionales.
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
          <a href="#" class="btn btn-primary">Reservar cupo</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Modal Festival de Chirimía -->
  <div class="modal fade" id="chirimiaModal" tabindex="-1" aria-labelledby="chirimiaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="chirimiaModalLabel">Festival de Chirimía</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="row">
            <div class="col-md-6">
              <!-- Imágenes del Festival de Chirimía -->
              <img src="imagenes/eventos/chirimia_principal.jpg" class="img-fluid rounded mb-3" alt="Grupo de Chirimía" style="width: 100%; height: 300px; object-fit: cover;">
              <div class="row g-2">
                <div class="col-6">
                  <img src="imagenes/eventos/chirimia_baile.jpg" class="img-fluid rounded" alt="Baile tradicional" style="width: 100%; height: 150px; object-fit: cover;">
                </div>
                <div class="col-6">
                  <img src="imagenes/eventos/chirimia_instrumentos.jpg" class="img-fluid rounded" alt="Instrumentos tradicionales" style="width: 100%; height: 150px; object-fit: cover;">
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <h6 class="fw-bold">Descripción del Evento</h6>
              <p>El Festival de Chirimía celebra la música tradicional del Chocó, reuniendo a los mejores intérpretes de este género musical único. La chirimía, declarada Patrimonio Cultural de la Nación, es una expresión musical que combina instrumentos de viento y percusión con ritmos ancestrales africanos.</p>

              <h6 class="fw-bold mt-3">Detalles del Evento</h6>
              <ul class="list-unstyled">
                <li><i class="fas fa-calendar me-2"></i><strong>Fecha:</strong> 5 de Noviembre, 2024</li>
                <li><i class="fas fa-clock me-2"></i><strong>Horario:</strong> 4:00 PM - 10:00 PM</li>
                <li><i class="fas fa-map-marker-alt me-2"></i><strong>Ubicación:</strong> Plaza de la Cultura</li>
                <li><i class="fas fa-music me-2"></i><strong>Tipo:</strong> Festival Musical Tradicional</li>
              </ul>

              <h6 class="fw-bold mt-3">Programación</h6>
              <ul>
                <li>Concurso de agrupaciones de chirimía</li>
                <li>Presentaciones de grupos tradicionales</li>
                <li>Talleres de instrumentos típicos</li>
                <li>Muestra de bailes tradicionales</li>
                <li>Homenaje a músicos destacados</li>
              </ul>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap JS and Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Custom JS -->
  <script src="./asser/js/scripts.js"></script>
  <script src="login.js"></script>
</body>

</html>
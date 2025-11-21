<!-- Modal Estadística: Visitantes -->
<div class="modal fade" id="statVisitantesModal" tabindex="-1" aria-labelledby="statVisitantesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statVisitantesModalLabel">
                    <i class="fas fa-users me-2"></i>Estadísticas de Visitantes
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="stats-detail">
                    <div class="stats-info">
                        <h6 class="mb-3">Desglose de Visitantes:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2">
                                <i class="fas fa-plane-arrival me-2 text-primary"></i>
                                <strong>Turistas Nacionales:</strong> 10,500
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-globe-americas me-2 text-primary"></i>
                                <strong>Turistas Internacionales:</strong> 4,500
                            </li>
                            <li class="mb-2">
                                <i class="fas fa-chart-line me-2 text-success"></i>
                                <strong>Crecimiento Mensual:</strong> +15%
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Estadística: Sitios -->
<div class="modal fade" id="statSitiosModal" tabindex="-1" aria-labelledby="statSitiosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statSitiosModalLabel">
                    <i class="fas fa-map-marked-alt me-2"></i>Sitios Turísticos Destacados
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <img src="{{ asset('img/cascada.jpg') }}" alt="Cascada" class="img-fluid rounded mb-2" onerror="this.src='https://via.placeholder.com/300x200?text=Cascada'">
                        <h6>Cascada de Tutunendo</h6>
                        <div class="rating">
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star text-warning"></i>
                            <i class="fas fa-star-half-alt text-warning"></i>
                            <span class="ms-2">4.5</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <img src="{{ asset('img/Catedral.jpg') }}" alt="Catedral" class="img-fluid rounded mb-2" onerror="this.src='https://via.placeholder.com/300x200?text=Catedral'">
                        <h6>Catedral San Francisco de Asís</h6>
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
                <div class="text-center mt-4">
                    <a href="#destinos" class="btn btn-primary" data-bs-dismiss="modal">Ver Todos los Sitios</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Estadística: Guías -->
<div class="modal fade" id="statGuiasModal" tabindex="-1" aria-labelledby="statGuiasModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statGuiasModalLabel">
                    <i class="fas fa-user-tie me-2"></i>Nuestros Guías Turísticos
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-6">
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
                    <div class="col-lg-6">
                        <h6 class="fw-bold mb-3">Especialidades:</h6>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-hiking me-2 text-primary"></i><strong>Ecoturismo:</strong> 80 guías</li>
                            <li class="mb-2"><i class="fas fa-history me-2 text-primary"></i><strong>Historia y Cultura:</strong> 60 guías</li>
                            <li class="mb-2"><i class="fas fa-utensils me-2 text-primary"></i><strong>Gastronomía:</strong> 40 guías</li>
                            <li class="mb-2"><i class="fas fa-camera me-2 text-primary"></i><strong>Fotografía:</strong> 20 guías</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Contactar Guía</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Estadística: Satisfacción -->
<div class="modal fade" id="statSatisfaccionModal" tabindex="-1" aria-labelledby="statSatisfaccionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statSatisfaccionModalLabel">
                    <i class="fas fa-smile me-2"></i>Índice de Satisfacción
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="satisfaction-stats">
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


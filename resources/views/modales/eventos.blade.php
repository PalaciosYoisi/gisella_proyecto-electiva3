<!-- Modal Evento: Fiestas de San Pacho -->
<div class="modal fade" id="eventoSanPachoModal" tabindex="-1" aria-labelledby="eventoSanPachoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="eventoSanPachoModalLabel">Fiestas de San Pacho</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9 mb-4">
                    <video controls autoplay>
                        <source src="{{ asset('video/sanpacho.mp4') }}" type="video/mp4">
                        Tu navegador no soporta video HTML5.
                    </video>
                </div>
                <div class="experience-rating mb-3">
                    <div class="stars">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                    </div>
                    <span class="rating-value-modal">5.0</span>
                    <span class="rating-count-modal">(203 reseñas)</span>
                </div>
                <h6 class="fw-bold text-primary">Descripción del Evento</h6>
                <p>Vive la fiesta patronal más importante del Chocó, declarada Patrimonio Cultural de la Humanidad. Las Fiestas de San Francisco de Asís, conocidas como "San Pacho", son la máxima expresión cultural y religiosa del pueblo chocoano, declaradas Patrimonio Cultural Inmaterial de la Humanidad por la UNESCO.</p>
                <h6 class="fw-bold text-primary mt-4">Detalles</h6>
                <ul>
                    <li><strong>Fecha:</strong> 20 de Septiembre - 4 de Octubre</li>
                    <li><strong>Ubicación:</strong> Centro Histórico de Quibdó</li>
                    <li><strong>Actividades:</strong> Procesiones, desfiles, música tradicional, gastronomía</li>
                    <li><strong>Duración:</strong> 15 días de celebración continua</li>
                    <li><strong>Patrimonio:</strong> UNESCO - Patrimonio Cultural Inmaterial</li>
                </ul>
                <div class="mt-3">
                    <span class="badge badge-culture">
                        <i class="fas fa-calendar me-1"></i>Festividades
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Reservar Experiencia</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Evento: Gastronómico -->
<div class="modal fade" id="eventoGastronomicoModal" tabindex="-1" aria-labelledby="eventoGastronomicoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="eventoGastronomicoModalLabel">Festival Gastronómico del Pacífico</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <img src="{{ asset('img/comida.jpg') }}" class="img-fluid rounded mb-3" alt="Festival Gastronómico" onerror="this.src='https://via.placeholder.com/800x400?text=Festival'">
                <h6 class="fw-bold text-primary">Descripción del Evento</h6>
                <p>El Festival Gastronómico del Pacífico es una celebración de los sabores ancestrales y la riqueza culinaria de la región.</p>
                <h6 class="fw-bold text-primary mt-4">Detalles</h6>
                <ul>
                    <li><strong>Fecha:</strong> 15 de Octubre, 2024</li>
                    <li><strong>Horario:</strong> 10:00 AM - 8:00 PM</li>
                    <li><strong>Ubicación:</strong> Malecón de Quibdó</li>
                    <li><strong>Entrada:</strong> Gratuita</li>
                </ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Reservar cupo</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Evento: Ritmos Exóticos de Quibdó -->
<div class="modal fade" id="eventoChirimiaModal" tabindex="-1" aria-labelledby="eventoChirimiaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventoChirimiaModalLabel">Ritmos Exóticos de Quibdó</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9 mb-4">
                    <video controls autoplay>
                        <source src="{{ asset('video/exotico.mp4') }}" type="video/mp4">
                        Tu navegador no soporta video HTML5.
                    </video>
                </div>
                <div class="experience-rating mb-3">
                    <div class="stars">
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star text-warning"></i>
                        <i class="fas fa-star-half-alt text-warning"></i>
                    </div>
                    <span class="rating-value-modal">4.9</span>
                    <span class="rating-count-modal">(142 reseñas)</span>
                </div>
                <h6 class="fw-bold text-primary">Descripción del Evento</h6>
                <p>Explora la chirimía, la música tradicional y los ritmos modernos que hacen vibrar al Chocó. Este evento celebra la música tradicional del Chocó, reuniendo a los mejores intérpretes de este género musical único y fusionándolo con ritmos contemporáneos.</p>
                <h6 class="fw-bold text-primary mt-4">Detalles</h6>
                <ul>
                    <li><strong>Fecha:</strong> 5 de Noviembre, 2024</li>
                    <li><strong>Horario:</strong> 4:00 PM - 10:00 PM</li>
                    <li><strong>Ubicación:</strong> Plaza de la Cultura</li>
                    <li><strong>Tipo:</strong> Festival Musical Tradicional</li>
                    <li><strong>Géneros:</strong> Chirimía, currulao, salsa chocoana, reggae</li>
                </ul>
                <div class="mt-3">
                    <span class="badge badge-culture">
                        <i class="fas fa-music me-1"></i>Música
                    </span>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary">Reservar Experiencia</button>
            </div>
        </div>
    </div>
</div>


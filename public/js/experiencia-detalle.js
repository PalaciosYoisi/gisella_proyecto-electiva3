/**
 * Detalle de Experiencia - JavaScript
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initGallery();
        initReserva();
        initFavorite();
    });

    function initGallery() {
        const thumbs = document.querySelectorAll('.thumb');
        const mainImage = document.getElementById('mainImage');
        const prevBtn = document.querySelector('.btn-gallery-prev');
        const nextBtn = document.querySelector('.btn-gallery-next');

        let currentIndex = 0;

        thumbs.forEach((thumb, index) => {
            thumb.addEventListener('click', function() {
                thumbs.forEach(t => t.classList.remove('active'));
                this.classList.add('active');
                if (mainImage) {
                    mainImage.src = this.src;
                }
                currentIndex = index;
            });
        });

        if (prevBtn) {
            prevBtn.addEventListener('click', function() {
                if (currentIndex > 0) {
                    currentIndex--;
                    thumbs[currentIndex].click();
                }
            });
        }

        if (nextBtn) {
            nextBtn.addEventListener('click', function() {
                if (currentIndex < thumbs.length - 1) {
                    currentIndex++;
                    thumbs[currentIndex].click();
                }
            });
        }
    }

    function initReserva() {
        const fechaInput = document.getElementById('reservaFecha');
        const participantsInput = document.getElementById('participantsCount');
        const countButtons = document.querySelectorAll('.btn-count');
        const btnReservar = document.getElementById('btnReservar');

        // Establecer fecha mínima (hoy)
        if (fechaInput) {
            const today = new Date().toISOString().split('T')[0];
            fechaInput.setAttribute('min', today);
        }

        // Contador de participantes
        countButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const action = this.getAttribute('data-action');
                const current = parseInt(participantsInput.value) || 1;
                
                if (action === 'increase') {
                    participantsInput.value = current + 1;
                } else if (action === 'decrease' && current > 1) {
                    participantsInput.value = current - 1;
                }
                
                updateTotal();
            });
        });

        if (participantsInput) {
            participantsInput.addEventListener('change', updateTotal);
        }

        if (btnReservar) {
            btnReservar.addEventListener('click', function() {
                const fecha = fechaInput?.value;
                const participantes = participantsInput?.value;
                
                if (!fecha) {
                    alert('Por favor selecciona una fecha');
                    return;
                }
                
                console.log('Reservando:', { fecha, participantes });
                // Lógica de reserva
            });
        }
    }

    function updateTotal() {
        const participants = parseInt(document.getElementById('participantsCount')?.value) || 1;
        const pricePerPerson = 80000;
        const total = participants * pricePerPerson;
        
        const totalElement = document.querySelector('.total-final strong');
        if (totalElement) {
            totalElement.textContent = '$' + total.toLocaleString('es-CO');
        }
    }

    function initFavorite() {
        const favoriteBtn = document.querySelector('.btn-favorite-detail');
        if (favoriteBtn) {
            favoriteBtn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                this.classList.toggle('active');
                
                if (this.classList.contains('active')) {
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                } else {
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                }
            });
        }
    }
})();


/**
 * Panel de Turista - JavaScript
 * Funcionalidad para el panel del turista
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initNavigation();
        initSearch();
        initFilters();
        initFavorites();
        initReservasTabs();
        initProfileForm();
        initLogout();
    });

    // ============================================
    // NAVEGACIÓN ENTRE SECCIONES
    // ============================================
    function initNavigation() {
        const navItems = document.querySelectorAll('.sidebar-nav .nav-item[data-section]');
        const sections = document.querySelectorAll('.content-section');
        const pageTitle = document.getElementById('pageTitle');

        const sectionTitles = {
            'explorar': 'Explorar Experiencias',
            'favoritos': 'Mis Favoritos',
            'reservas': 'Mis Reservas',
            'reseñas': 'Mis Reseñas',
            'perfil': 'Mi Perfil'
        };

        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                e.preventDefault();
                
                const targetSection = this.getAttribute('data-section');
                
                // Actualizar navegación activa
                navItems.forEach(nav => nav.classList.remove('active'));
                this.classList.add('active');
                
                // Mostrar sección correspondiente
                sections.forEach(section => section.classList.remove('active'));
                const targetElement = document.getElementById(`section-${targetSection}`);
                if (targetElement) {
                    targetElement.classList.add('active');
                }
                
                // Actualizar título
                if (pageTitle && sectionTitles[targetSection]) {
                    pageTitle.textContent = sectionTitles[targetSection];
                }
                
                // Scroll al inicio
                window.scrollTo({ top: 0, behavior: 'smooth' });
            });
        });
    }

    // ============================================
    // BÚSQUEDA
    // ============================================
    function initSearch() {
        const searchInput = document.getElementById('searchInput');
        if (!searchInput) return;

        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();
            
            searchTimeout = setTimeout(() => {
                if (query.length >= 2) {
                    performSearch(query);
                } else if (query.length === 0) {
                    loadAllExperiences();
                }
            }, 300);
        });
    }

    function performSearch(query) {
        console.log('Buscando:', query);
        // Aquí se implementaría la búsqueda real
        const cards = document.querySelectorAll('.experience-card-turista');
        cards.forEach(card => {
            const title = card.querySelector('.card-title').textContent.toLowerCase();
            const text = card.querySelector('.card-text').textContent.toLowerCase();
            const searchTerm = query.toLowerCase();
            
            if (title.includes(searchTerm) || text.includes(searchTerm)) {
                card.closest('.col-lg-4').style.display = '';
            } else {
                card.closest('.col-lg-4').style.display = 'none';
            }
        });
    }

    function loadAllExperiences() {
        const cards = document.querySelectorAll('.experience-card-turista');
        cards.forEach(card => {
            card.closest('.col-lg-4').style.display = '';
        });
    }

    // ============================================
    // FILTROS
    // ============================================
    function initFilters() {
        const btnFiltros = document.getElementById('btnFiltros');
        const filtersPanel = document.getElementById('filtersPanel');
        const applyFilters = document.getElementById('applyFilters');
        const clearFilters = document.getElementById('clearFilters');

        if (btnFiltros && filtersPanel) {
            btnFiltros.addEventListener('click', function() {
                const isVisible = filtersPanel.style.display !== 'none';
                filtersPanel.style.display = isVisible ? 'none' : 'block';
                
                if (!isVisible) {
                    filtersPanel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                }
            });
        }

        if (applyFilters) {
            applyFilters.addEventListener('click', function() {
                applyFilterLogic();
            });
        }

        if (clearFilters) {
            clearFilters.addEventListener('click', function() {
                document.getElementById('filterCategoria').value = '';
                document.getElementById('filterPrecio').value = '';
                document.getElementById('filterCalificacion').value = '';
                loadAllExperiences();
            });
        }
    }

    function applyFilterLogic() {
        const categoria = document.getElementById('filterCategoria').value;
        const precio = document.getElementById('filterPrecio').value;
        const calificacion = document.getElementById('filterCalificacion').value;

        console.log('Aplicando filtros:', { categoria, precio, calificacion });
        // Aquí se implementaría la lógica de filtrado real
    }

    // ============================================
    // FAVORITOS
    // ============================================
    function initFavorites() {
        const favoriteButtons = document.querySelectorAll('.btn-favorite');
        
        favoriteButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const icon = this.querySelector('i');
                const isActive = this.classList.contains('active');
                
                if (isActive) {
                    this.classList.remove('active');
                    icon.classList.remove('fas');
                    icon.classList.add('far');
                    removeFromFavorites(this.getAttribute('data-experience-id'));
                } else {
                    this.classList.add('active');
                    icon.classList.remove('far');
                    icon.classList.add('fas');
                    addToFavorites(this.getAttribute('data-experience-id'));
                }
            });
        });
    }

    function addToFavorites(experienceId) {
        console.log('Agregando a favoritos:', experienceId);
        // Aquí se implementaría la lógica para guardar en favoritos
        showNotification('Experiencia agregada a favoritos', 'success');
    }

    function removeFromFavorites(experienceId) {
        console.log('Eliminando de favoritos:', experienceId);
        // Aquí se implementaría la lógica para eliminar de favoritos
        showNotification('Experiencia eliminada de favoritos', 'info');
    }

    // ============================================
    // TABS DE RESERVAS
    // ============================================
    function initReservasTabs() {
        const tabButtons = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                
                // Actualizar botones
                tabButtons.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                // Mostrar contenido correspondiente
                tabContents.forEach(content => {
                    content.classList.remove('active');
                    if (content.id === `reservas${targetTab.charAt(0).toUpperCase() + targetTab.slice(1)}`) {
                        content.classList.add('active');
                    }
                });
            });
        });
    }

    // ============================================
    // FORMULARIO DE PERFIL
    // ============================================
    function initProfileForm() {
        const profileForm = document.getElementById('profileForm');
        if (!profileForm) return;

        profileForm.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('Guardando perfil...');
            // Aquí se implementaría la lógica para guardar el perfil
            showNotification('Perfil actualizado correctamente', 'success');
        });
    }

    // ============================================
    // LOGOUT
    // ============================================
    function initLogout() {
        const logoutBtn = document.querySelector('.logout-btn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('¿Estás seguro de cerrar sesión?')) {
                    window.location.href = '/logout';
                }
            });
        }
    }

    // ============================================
    // UTILIDADES
    // ============================================
    function showNotification(message, type = 'info') {
        // Crear notificación temporal
        const notification = document.createElement('div');
        notification.className = `alert alert-${type === 'success' ? 'success' : 'info'} alert-dismissible fade show position-fixed`;
        notification.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        notification.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.remove();
        }, 3000);
    }

})();


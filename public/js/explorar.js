/**
 * Vista de Exploración - JavaScript
 */

(function() {
    'use strict';

    document.addEventListener('DOMContentLoaded', function() {
        initFilters();
        initViewToggle();
        initPriceRange();
        initSearch();
    });

    function initFilters() {
        const toggleBtn = document.getElementById('toggleFilters');
        const panel = document.getElementById('filtersPanelExpanded');
        
        if (toggleBtn && panel) {
            toggleBtn.addEventListener('click', function() {
                const isVisible = panel.style.display !== 'none';
                panel.style.display = isVisible ? 'none' : 'block';
            });
        }

        const applyBtn = document.getElementById('applyFiltersBtn');
        const clearBtn = document.getElementById('clearFiltersBtn');

        if (applyBtn) {
            applyBtn.addEventListener('click', applyFilters);
        }

        if (clearBtn) {
            clearBtn.addEventListener('click', clearFilters);
        }
    }

    function initViewToggle() {
        const viewBtns = document.querySelectorAll('.view-btn');
        const resultsContainer = document.getElementById('experiencesResults');

        viewBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const view = this.getAttribute('data-view');
                
                viewBtns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                if (resultsContainer) {
                    if (view === 'list') {
                        resultsContainer.classList.add('list-view');
                    } else {
                        resultsContainer.classList.remove('list-view');
                    }
                }
            });
        });
    }

    function initPriceRange() {
        const priceRange = document.getElementById('priceRange');
        const priceValue = document.getElementById('priceValue');

        if (priceRange && priceValue) {
            priceRange.addEventListener('input', function() {
                const value = parseInt(this.value);
                priceValue.textContent = '$' + value.toLocaleString('es-CO');
            });
        }
    }

    function initSearch() {
        const searchInput = document.getElementById('heroSearch');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch(this.value);
                }
            });
        }
    }

    function applyFilters() {
        console.log('Aplicando filtros...');
        // Lógica de filtrado
    }

    function clearFilters() {
        document.querySelectorAll('.filter-option input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
        });
        const priceRange = document.getElementById('priceRange');
        if (priceRange) priceRange.value = 250000;
        applyFilters();
    }

    function performSearch(query) {
        console.log('Buscando:', query);
        // Lógica de búsqueda
    }
})();


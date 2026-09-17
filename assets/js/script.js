// Mobile menu toggle
const menuToggle = document.getElementById('mobileMenuToggle');
const mainNav = document.getElementById('mainNav');

if (menuToggle && mainNav) {
    menuToggle.addEventListener('click', () => {
        mainNav.classList.toggle('open');
    });
}

// Scroll fade-in for elements marked .fade-in
const fadeEls = document.querySelectorAll('.fade-in');

if (fadeEls.length) {
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.15 });

    fadeEls.forEach(el => observer.observe(el));
}

// Sticky header shadow on scroll
const header = document.querySelector('.site-header');

if (header) {
    window.addEventListener('scroll', () => {
        header.style.boxShadow = window.scrollY > 10 ? '0 4px 20px rgba(27,43,34,0.08)' : 'none';
    });
}

// Menu page: category filter + search
const filterBtns = document.querySelectorAll('.filter-btn');
const menuCards = document.querySelectorAll('.menu-card');
const menuSearch = document.getElementById('menuSearch');
const noResults = document.getElementById('noResults');

if (filterBtns.length && menuCards.length) {
    let activeCategory = 'all';

    function applyFilters() {
        const query = menuSearch ? menuSearch.value.trim().toLowerCase() : '';
        let visibleCount = 0;

        menuCards.forEach(card => {
            const matchesCategory = activeCategory === 'all' || card.dataset.category === activeCategory;
            const matchesSearch = card.dataset.name.includes(query);
            const show = matchesCategory && matchesSearch;
            card.style.display = show ? '' : 'none';
            if (show) visibleCount++;
        });

        if (noResults) {
            noResults.style.display = visibleCount === 0 ? 'block' : 'none';
        }
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            activeCategory = btn.dataset.category;
            applyFilters();
        });
    });

    if (menuSearch) {
        menuSearch.addEventListener('input', applyFilters);
    }
}

// Reservation page: block Mondays client-side (server still enforces this either way)
const reservationDateInput = document.querySelector('input[name="reservation_date"]');
if (reservationDateInput) {
    reservationDateInput.addEventListener('input', () => {
        const selected = new Date(reservationDateInput.value + 'T00:00:00');
        if (!isNaN(selected) && selected.getDay() === 1) {
            reservationDateInput.setCustomValidity("We're closed Mondays — please pick another date.");
        } else {
            reservationDateInput.setCustomValidity('');
        }
        reservationDateInput.reportValidity();
    });
}

// Gallery page: category filter (separate from menu filter, different classes)
const galleryFilterBtns = document.querySelectorAll('.gallery-filter-btn');
const galleryItems = document.querySelectorAll('.gallery-item');

if (galleryFilterBtns.length && galleryItems.length) {
    galleryFilterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            galleryFilterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            const category = btn.dataset.category;

            galleryItems.forEach(item => {
                const show = category === 'all' || item.dataset.category === category;
                item.style.display = show ? '' : 'none';
            });
        });
    });
}
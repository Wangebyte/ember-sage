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
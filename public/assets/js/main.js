/**
 * PINJAMORANG - Global Application JavaScript
 * File: public/assets/js/main.js
 * Deskripsi: Menangani navigasi responsif, auto-dismiss flash message, dan interaktivitas global.
 */

document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------------------
    // 1. Mobile Menu Drawer Toggle
    // -------------------------------------------------------------------------
    const btnMobileMenu = document.getElementById('btnMobileMenu');
    const mobileNavDrawer = document.getElementById('mobileNavDrawer');

    if (btnMobileMenu && mobileNavDrawer) {
        btnMobileMenu.addEventListener('click', () => {
            const isHidden = mobileNavDrawer.style.display === 'none' || mobileNavDrawer.style.display === '';
            mobileNavDrawer.style.display = isHidden ? 'flex' : 'none';
            btnMobileMenu.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
        });
    }

    // -------------------------------------------------------------------------
    // 2. Auto-Dismiss Flash Alert (5 Detik)
    // -------------------------------------------------------------------------
    const flashAlerts = document.querySelectorAll('.flash-alert-container .alert');
    flashAlerts.forEach((alert) => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });

    // -------------------------------------------------------------------------
    // 3. Smooth Scroll untuk Anchor Links
    // -------------------------------------------------------------------------
    const smoothLinks = document.querySelectorAll('a[href^="#"]');
    smoothLinks.forEach((link) => {
        link.addEventListener('click', (e) => {
            const targetId = link.getAttribute('href');
            if (targetId && targetId !== '#') {
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({ behavior: 'smooth' });
                }
            }
        });
    });
});

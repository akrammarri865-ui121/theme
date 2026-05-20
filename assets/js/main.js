/**
 * SmartToolsBlog - Main JavaScript
 * No jQuery dependency. Vanilla JS only.
 *
 * @package SmartToolsBlog
 */

(function () {
    'use strict';

    /* --- Dark/Light Mode Toggle --- */
    const themeToggle = document.getElementById('theme-toggle');
    const html = document.documentElement;

    function getPreferredTheme() {
        const stored = localStorage.getItem('stb-theme');
        if (stored) return stored;
        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    function setTheme(theme) {
        html.setAttribute('data-theme', theme);
        localStorage.setItem('stb-theme', theme);
    }

    // Initialize theme
    setTheme(getPreferredTheme());

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const current = html.getAttribute('data-theme');
            setTheme(current === 'dark' ? 'light' : 'dark');
        });
    }

    /* --- Mobile Menu Toggle --- */
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mobileNav = document.getElementById('mobile-navigation');

    if (menuToggle && mobileNav) {
        menuToggle.addEventListener('click', function () {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !expanded);
            mobileNav.hidden = expanded;
        });
    }



    /* --- Reading Progress Bar --- */
    const progressBar = document.getElementById('reading-progress-bar');

    if (progressBar) {
        window.addEventListener('scroll', function () {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            progressBar.style.width = Math.min(progress, 100) + '%';
        }, { passive: true });
    }

    /* --- Sticky Header Shadow on Scroll --- */
    const header = document.getElementById('site-header');

    if (header) {
        let lastScroll = 0;
        window.addEventListener('scroll', function () {
            const currentScroll = window.scrollY;
            if (currentScroll > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            lastScroll = currentScroll;
        }, { passive: true });
    }

})();

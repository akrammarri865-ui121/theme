/**
 * SmartToolsBlog - Main JavaScript
 * No jQuery dependency. Vanilla JS only. Performance optimized.
 *
 * @package SmartToolsBlog
 */

(function () {
    'use strict';

    /* =========================================
       Dark/Light Mode Toggle
       ========================================= */
    const themeToggle = document.getElementById('theme-toggle');
    const html = document.documentElement;

    function getPreferredTheme() {
        const stored = localStorage.getItem('stb-theme');
        if (stored) return stored;

        // Check if theme sets a default via body class.
        if (document.body && document.body.classList.contains('stb-default-dark')) {
            return 'dark';
        }

        return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
    }

    function setTheme(theme) {
        html.setAttribute('data-theme', theme);
        localStorage.setItem('stb-theme', theme);

        // Update toggle aria-label for accessibility.
        if (themeToggle) {
            const label = theme === 'dark' ? 'Switch to light mode' : 'Switch to dark mode';
            themeToggle.setAttribute('aria-label', label);
        }
    }

    // Initialize theme immediately (before paint if possible).
    setTheme(getPreferredTheme());

    // Listen for system preference changes.
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function (e) {
        if (!localStorage.getItem('stb-theme')) {
            setTheme(e.matches ? 'dark' : 'light');
        }
    });

    if (themeToggle) {
        themeToggle.addEventListener('click', function () {
            const current = html.getAttribute('data-theme');
            setTheme(current === 'dark' ? 'light' : 'dark');
        });
    }

    /* =========================================
       Mobile Menu Toggle
       ========================================= */
    const menuToggle = document.getElementById('mobile-menu-toggle');
    const mobileNav = document.getElementById('mobile-navigation');

    if (menuToggle && mobileNav) {
        menuToggle.addEventListener('click', function () {
            const expanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', String(!expanded));
            mobileNav.hidden = expanded;

            // Toggle hamburger animation class.
            this.classList.toggle('is-active', !expanded);

            // Trap focus in mobile menu when open.
            if (!expanded) {
                const firstLink = mobileNav.querySelector('a');
                if (firstLink) firstLink.focus();
            }
        });

        // Close menu on Escape key.
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !mobileNav.hidden) {
                menuToggle.setAttribute('aria-expanded', 'false');
                mobileNav.hidden = true;
                menuToggle.classList.remove('is-active');
                menuToggle.focus();
            }
        });

        // Close menu when clicking outside.
        document.addEventListener('click', function (e) {
            if (!mobileNav.hidden && !mobileNav.contains(e.target) && !menuToggle.contains(e.target)) {
                menuToggle.setAttribute('aria-expanded', 'false');
                mobileNav.hidden = true;
                menuToggle.classList.remove('is-active');
            }
        });
    }

    /* =========================================
       Reading Progress Bar (optimized with rAF)
       ========================================= */
    const progressBar = document.getElementById('reading-progress-bar');

    if (progressBar) {
        let ticking = false;

        function updateProgress() {
            const scrollTop = window.scrollY;
            const docHeight = document.documentElement.scrollHeight - window.innerHeight;
            const progress = docHeight > 0 ? (scrollTop / docHeight) * 100 : 0;
            progressBar.style.width = Math.min(progress, 100) + '%';
            ticking = false;
        }

        window.addEventListener('scroll', function () {
            if (!ticking) {
                requestAnimationFrame(updateProgress);
                ticking = true;
            }
        }, { passive: true });
    }

    /* =========================================
       Sticky Header - Shadow on Scroll
       ========================================= */
    const header = document.getElementById('site-header');

    if (header) {
        let headerTicking = false;

        function updateHeader() {
            if (window.scrollY > 10) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
            headerTicking = false;
        }

        window.addEventListener('scroll', function () {
            if (!headerTicking) {
                requestAnimationFrame(updateHeader);
                headerTicking = true;
            }
        }, { passive: true });
    }

    /* =========================================
       Tools Page - Category Filter
       ========================================= */
    const filterButtons = document.querySelectorAll('.tools-filter__btn');

    if (filterButtons.length > 0) {
        const toolCards = document.querySelectorAll('.tool-card[data-categories]');

        filterButtons.forEach(function (btn) {
            btn.addEventListener('click', function () {
                const filter = this.getAttribute('data-filter');

                // Update active state.
                filterButtons.forEach(function (b) { b.classList.remove('active'); });
                this.classList.add('active');

                // Filter cards.
                toolCards.forEach(function (card) {
                    if (filter === 'all') {
                        card.style.display = '';
                    } else {
                        const categories = card.getAttribute('data-categories') || '';
                        card.style.display = categories.includes(filter) ? '' : 'none';
                    }
                });
            });
        });
    }

    /* =========================================
       Smooth Scroll for Anchor Links
       ========================================= */
    document.querySelectorAll('a[href^="#"]').forEach(function (anchor) {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            if (targetId === '#') return;

            const target = document.querySelector(targetId);
            if (target) {
                e.preventDefault();
                target.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

})();

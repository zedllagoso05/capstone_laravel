<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Capstone Tracker — MCC | Intelligent Student Tracking System</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:wght@500;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500;9..40,600&display=swap" rel="stylesheet">
@vite(['resources/css/app.css', 'resources/js/app.js'])
    {{ $styles ?? '' }}
</head>
<body>

    {{-- TOP NAVBAR WITH REFINED MICRO-INTERACTIONS --}}
    <header class="navbar">
        <a href="/" class="brand">
            <div>
                <p class="brand-eyebrow">MCC System</p>
                <p class="brand-title">Capstone <em>Tracker</em></p>
            </div>
        </a>

        <button class="menu-toggle" id="menuToggle" aria-label="Toggle navigation menu">
            <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.8">
                <line x1="3" y1="6"  x2="21" y2="6"  stroke-linecap="round"/>
                <line x1="3" y1="12" x2="21" y2="12" stroke-linecap="round"/>
                <line x1="3" y1="18" x2="21" y2="18" stroke-linecap="round"/>
            </svg>
        </button>

        <nav id="mainNav">
            <ul>
                <li>
                    <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z" stroke-linecap="round" stroke-linejoin="round"/>
                            <polyline points="9,22 9,12 15,12 15,22" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Home
                    </a>
                </li>
                <li>
                    <a href="#about" class="{{ request()->is('about') ? 'active' : '' }}">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke-linecap="round" stroke-linejoin="round"/>
                            <line x1="12" y1="8" x2="12" y2="12" stroke-linecap="round"/>
                            <line x1="12" y1="16" x2="12.01" y2="16" stroke-linecap="round" stroke-width="2.2"/>
                        </svg>
                        About
                    </a>
                </li>
                <li>
                    <a href="#contact">
                        <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Contact
                    </a>
                </li>
            </ul>
        </nav>
    </header>

    {{-- MAIN DYNAMIC CONTENT SLOT --}}
    <main>
        {{ $slot }}
    </main>

    {{-- ABOUT SECTION WITH UNIFIED GOLD/NAVY THEME (ENHANCED) --}}
    <div id="about" class="about_form">
        <h2 class="about_heading">About the Capstone Tracker System</h2>
        <div class="cards_grid">
            <div class="cards">
                <div class="icon_wrap">📱</div>
                <h3>QR-Based Tracking</h3>
                <p>Each item, record, and student activity is assigned a unique QR code for instant scanning, secure validation, and real-time tracking across campus.</p>
            </div>
            <div class="cards">
                <div class="icon_wrap">⏱️</div>
                <h3>Automated Student Logs</h3>
                <p>Scan student IDs to capture time-in/out, visit details, symptoms, and treatment history — fully automated and audit-ready logs.</p>
            </div>
            <div class="cards">
                <div class="icon_wrap">📊</div>
                <h3>Data Insights Dashboard</h3>
                <p>Powerful real-time charts and analytics transform health records into actionable insights, enabling smarter clinic decisions.</p>
            </div>
            <div class="cards">
                <div class="icon_wrap">📄</div>
                <h3>Report Generation</h3>
                <p>Generate official clinic reports and excuse letters with integrated approvals, one-click PDF exports, and professional formatting.</p>
            </div>
        </div>
    </div>

    {{-- FOOTER WITH BETTER ACCESSIBILITY --}}
    <footer id="contact">
        <p>© 2026 Capstone Tracker — MCC &nbsp;|&nbsp; Designed for excellence</p>
        <div class="footer-links">
            <a href="mailto:contact@mcc.edu" aria-label="Email support">📧 zedlaurence.llagoso@mcc.edu</a>
            <a href="https://www.facebook.com/mcc" target="_blank" rel="noopener noreferrer" aria-label="Facebook page">🌐 Facebook</a>
        </div>
    </footer>

    <script>
        (function() {
            // mobile menu toggle with smooth a11y
            const menuToggle = document.getElementById('menuToggle');
            const mainNav = document.getElementById('mainNav');
            if (menuToggle && mainNav) {
                menuToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    mainNav.classList.toggle('open');
                    const expanded = mainNav.classList.contains('open');
                    menuToggle.setAttribute('aria-expanded', expanded);
                });
                // close when clicking outside (optional but user-friendly)
                document.addEventListener('click', function(event) {
                    if (!mainNav.contains(event.target) && !menuToggle.contains(event.target) && mainNav.classList.contains('open')) {
                        mainNav.classList.remove('open');
                        menuToggle.setAttribute('aria-expanded', 'false');
                    }
                });
            }

            // active highlight for current hash link (simple)
            const sections = document.querySelectorAll('#about, #contact');
            const navLinks = document.querySelectorAll('.navbar nav ul li a');
            function setActiveBasedOnHash() {
                let currentHash = window.location.hash;
                if (currentHash === '#about') {
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#about') link.classList.add('active');
                    });
                } else if (currentHash === '#contact') {
                    navLinks.forEach(link => {
                        link.classList.remove('active');
                        if (link.getAttribute('href') === '#contact') link.classList.add('active');
                    });
                } else if (window.location.pathname === '/' || window.location.pathname === '') {
                    navLinks.forEach(link => {
                        if (link.getAttribute('href') === '/') link.classList.add('active');
                        else if (!link.getAttribute('href').startsWith('#')) link.classList.remove('active');
                    });
                }
            }
            window.addEventListener('hashchange', setActiveBasedOnHash);
            setActiveBasedOnHash();
        })();
    </script>
    {{ $scripts ?? '' }}
</body>
</html>
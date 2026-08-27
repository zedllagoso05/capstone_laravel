<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Capstone Tracker — MCC | Intelligent Student Tracking System</title>
    <link rel="stylesheet" href="/css/app.css">
    <link rel="icon" type="image/jpeg" href="{{ asset('pictures/favicon.jpg') }}"> 
    <script src="/js/app.js" defer></script>
    {{ $styles ?? '' }}

    {{-- Toast notification styles --}}
    <style>
        .toast-notification {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 99999;
            background: #1e6b3a; /* success green */
            color: #fff;
            padding: 14px 28px;
            border-radius: 8px;
            box-shadow: 0 8px 24px rgba(0,0,0,0.2);
            font-family: 'DM Sans', sans-serif;
            font-size: 1rem;
            font-weight: 500;
            min-width: 280px;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s ease, transform 0.3s ease;
            pointer-events: none; /* so clicks pass through */
        }
        .toast-notification.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
        }
        .toast-content {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .toast-icon {
            font-size: 1.4rem;
        }
        @keyframes slideDown {
            from {
                transform: translateX(-50%) translateY(-30px);
                opacity: 0;
            }
            to {
                transform: translateX(-50%) translateY(0);
                opacity: 1;
            }
        }
        .toast-notification {
            animation: slideDown 0.4s ease-out;
        }
    </style>
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

    {{-- ABOUT SECTION – updated to reflect the actual Capstone Tracker features --}}
    <div id="about" class="about_form">
        <h2 class="about_heading">About the Capstone Tracker System</h2>
        <div class="cards_grid">
            <div class="cards">
                <div class="icon_wrap">👥</div>
                <h3>Group Management</h3>
                <p>Organise students into project groups, assign faculty advisers, and keep track of team members and roles throughout the capstone journey.</p>
            </div>
            <div class="cards">
                <div class="icon_wrap">📋</div>
                <h3>Milestone Tracking</h3>
                <p>Define milestones for each capstone stage (Proposal and Implementation). Monitor completion status, view progress, and ensure deadlines are met.</p>
            </div>
            <div class="cards">
                <div class="icon_wrap">⭐</div>
                <h3>Evaluations &amp; Rubrics</h3>
                <p>Faculty evaluate group presentations using customisable rubrics. Score criteria, provide feedback, and automatically compute overall scores.</p>
            </div>
            <div class="cards">
                <div class="icon_wrap">🏆</div>
                <h3>Certificates &amp; Reports</h3>
                <p>Automatically issue certificates upon milestone completion. Generate official reports and approval sheets for administration and records.</p>
            </div>
        </div>
    </div>

    {{-- FOOTER WITH BETTER ACCESSIBILITY --}}
    <footer id="contact">
        <p>© 2026 Capstone Tracker — MCC &nbsp;|&nbsp; Designed for excellence</p>
        <div class="footer-links">
            <a href="mailto:mcccapstonetracker@gmail.com" aria-label="Email support">mcccapstonetracker@gmail.com</a>
            <a href="https://www.facebook.com/myroe.26" target="_blank" rel="noopener noreferrer" aria-label="Facebook page">🌐 Facebook</a>
        </div>
    </footer>

    {{-- ========== LOGIN SUCCESS TOAST ========== --}}
    @if(session('login_success'))
        <div id="loginSuccessToast" class="toast-notification">
            <div class="toast-content">
                <span class="toast-icon">✅</span>
                <span class="toast-message">{{ session('login_message') ?? 'Login successful!' }}</span>
            </div>
        </div>
    @endif

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

            // ========== LOGIN SUCCESS TOAST AUTO-HIDE ==========
            const toast = document.getElementById('loginSuccessToast');
            if (toast) {
                // Show with a tiny delay so the entrance animation plays
                setTimeout(() => {
                    toast.classList.add('show');
                }, 100);

                // Hide after 3 seconds
                setTimeout(() => {
                    toast.classList.remove('show');
                    // Remove from DOM after fade-out (optional)
                    setTimeout(() => {
                        if (toast.parentNode) toast.remove();
                    }, 300);
                }, 3000);
            }
        })();
    </script>

    {{ $scripts ?? '' }}
</body>
</html>
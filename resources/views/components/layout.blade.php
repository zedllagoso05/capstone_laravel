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
        /* ─── TOAST NOTIFICATIONS ─────────────────────────── */
        .toast-notification {
            position: fixed;
            top: 24px;
            left: 50%;
            transform: translateX(-50%) translateY(-20px);
            z-index: 99999;
            display: flex;
            align-items: center;
            gap: 14px;
            min-width: 320px;
            max-width: 420px;
            background: #ffffff;
            border-radius: 14px;
            padding: 16px 20px;
            box-shadow:
                0 4px 6px rgba(10, 20, 40, 0.05),
                0 12px 32px rgba(10, 20, 40, 0.16);
            border-left: 4px solid #1e6b3a;
            font-family: 'DM Sans', sans-serif;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.35s cubic-bezier(0.22, 1, 0.36, 1),
                        transform 0.35s cubic-bezier(0.22, 1, 0.36, 1);
            overflow: hidden;
        }

        .toast-notification.show {
            opacity: 1;
            transform: translateX(-50%) translateY(0);
            pointer-events: all;
        }

        .toast-notification.toast-error {
            border-left-color: #a12b2b;
        }

        .toast-icon-wrap {
            flex-shrink: 0;
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: rgba(30, 107, 58, 0.1);
        }

        .toast-notification.toast-error .toast-icon-wrap {
            background: rgba(161, 43, 43, 0.1);
        }

        .toast-icon-wrap svg {
            width: 18px;
            height: 18px;
            stroke: #1e6b3a;
            stroke-width: 2.2;
            fill: none;
        }

        .toast-notification.toast-error .toast-icon-wrap svg {
            stroke: #a12b2b;
        }

        .toast-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 2px;
            min-width: 0;
        }

        .toast-title {
            font-size: 0.82rem;
            font-weight: 700;
            color: #0a1428;
            letter-spacing: 0.01em;
        }

        .toast-message {
            font-size: 0.78rem;
            font-weight: 400;
            color: #5b6375;
            line-height: 1.35;
        }

        .toast-close {
            flex-shrink: 0;
            background: none;
            border: none;
            color: #b8b0a0;
            cursor: pointer;
            padding: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            transition: background 0.15s, color 0.15s;
            pointer-events: all;
        }

        .toast-close:hover {
            background: #f0ece4;
            color: #0a1428;
        }

        .toast-close svg {
            width: 14px;
            height: 14px;
            stroke: currentColor;
            stroke-width: 2;
            fill: none;
        }

        .toast-progress {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 3px;
            background: #1e6b3a;
            width: 100%;
            transform-origin: left;
            animation: toastProgress 3.5s linear forwards;
        }

        .toast-notification.toast-error .toast-progress {
            background: #a12b2b;
        }

        @keyframes toastProgress {
            from { transform: scaleX(1); }
            to   { transform: scaleX(0); }
        }

        @media (max-width: 480px) {
            .toast-notification {
                min-width: unset;
                width: calc(100% - 32px);
                left: 16px;
                right: 16px;
                transform: translateY(-20px);
            }
            .toast-notification.show {
                transform: translateY(0);
            }
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
        <div id="loginSuccessToast" class="toast-notification" role="status">
            <div class="toast-icon-wrap">
                <svg viewBox="0 0 24 24"><path d="M20 6L9 17l-5-5" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </div>
            <div class="toast-content">
                <span class="toast-title">Success</span>
                <span class="toast-message">{{ session('login_message') ?? 'Login successful!' }}</span>
            </div>
            <button type="button" class="toast-close" onclick="dismissToast('loginSuccessToast')">
                <svg viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="toast-progress"></div>
        </div>
    @endif

    {{-- ========== LOGIN ERROR TOAST ========== --}}
    @if($errors->any())
        <div id="loginErrorToast" class="toast-notification toast-error" role="alert">
            <div class="toast-icon-wrap">
                <svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12.5" stroke-linecap="round"/><circle cx="12" cy="16" r="0.5" fill="currentColor" stroke="currentColor"/></svg>
            </div>
            <div class="toast-content">
                <span class="toast-title">Login Failed</span>
                <span class="toast-message">{{ $errors->first() }}</span>
            </div>
            <button type="button" class="toast-close" onclick="dismissToast('loginErrorToast')">
                <svg viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
            <div class="toast-progress"></div>
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

            // ========== TOAST NOTIFICATIONS ==========
            window.dismissToast = function(id) {
                const el = document.getElementById(id);
                if (!el) return;
                el.classList.remove('show');
                setTimeout(() => { if (el.parentNode) el.remove(); }, 350);
            };

            function initToast(id, duration) {
                const el = document.getElementById(id);
                if (!el) return;
                setTimeout(() => el.classList.add('show'), 80);
                setTimeout(() => dismissToast(id), duration);
            }

            initToast('loginSuccessToast', 3500);
            initToast('loginErrorToast', 4000);
        })();
    </script>

    {{ $scripts ?? '' }}
</body>
</html>
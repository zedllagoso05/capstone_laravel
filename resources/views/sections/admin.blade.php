<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Capstone Tracker | Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy: #0a1428;
            --navy-deep: #051021;
            --navy-hover: #162c47;
            --gold: #d6b15c;
            --gold-dark: #b88d3a;
            --gold-light: #f0e0b0;
            --gold-glow: rgba(214, 177, 92, 0.10);
            --cream: #f8f6f0;
            --cream-dark: #ece6db;
            --white: #ffffff;
            --text: #171e2c;
            --text-muted: #5b6375;
            --border: #e2dacf;
            --shadow-sm: 0 10px 25px -5px rgba(0, 0, 0, 0.04), 0 8px 10px -6px rgba(0, 0, 0, 0.02);
            --shadow-md: 0 20px 35px -12px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0, 0, 0, 0.02);
            --shadow-lg: 0 30px 45px -15px rgba(0, 0, 0, 0.10);
            --transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.0);
        }

        * {
            font-family: 'DM Sans', sans-serif;
        }
        body {
            background: linear-gradient(145deg, var(--cream) 0%, #f2ede2 100%);
            color: var(--text);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
            font-size: 0.95rem;
        }
        ::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        ::-webkit-scrollbar-track {
            background: var(--cream-dark);
            border-radius: 8px;
        }
        ::-webkit-scrollbar-thumb {
            background: var(--gold);
            border-radius: 8px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: var(--gold-dark);
        }
        ::selection {
            background: var(--gold);
            color: var(--navy);
        }

        .transition-smooth {
            transition: var(--transition);
        }
        .section-card {
            animation: fadeInUp 0.35s cubic-bezier(0.22, 1, 0.36, 1) both;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(14px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* ── SIDEBAR ── */
        .desktop-sidebar {
            background: linear-gradient(180deg, var(--navy-deep) 0%, var(--navy) 100%);
            border-right: 1px solid rgba(214, 177, 92, 0.18);
            box-shadow: 2px 0 30px rgba(5, 16, 33, 0.25);
        }
        .nav-link {
            border-radius: 0.75rem;
            transition: var(--transition);
        }
        .nav-link.active-link {
            background: rgba(214, 177, 92, 0.13);
            color: var(--gold) !important;
            box-shadow: inset 0 0 0 1px rgba(214, 177, 92, 0.25);
        }
        .nav-link:not(.active-link):hover {
            background: rgba(255, 255, 255, 0.04);
            color: var(--gold-light) !important;
        }
        .nav-link i.fa-chevron-right {
            transition: transform 0.2s ease, opacity 0.2s ease;
        }
        .nav-link.active-link i.fa-chevron-right {
            opacity: 1;
            transform: translateX(2px);
        }

        /* ── CARDS ── */
        .stat-card {
            background: var(--white);
            border-radius: 1.25rem;
            border: 1px solid rgba(214, 177, 92, 0.14);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
            overflow: hidden;
        }
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg), 0 0 0 1px rgba(214, 177, 92, 0.25);
            border-color: rgba(214, 177, 92, 0.35);
        }
        .stat-card .icon-circle {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 16px -4px rgba(10, 20, 40, 0.25);
            transition: var(--transition);
            color: var(--gold);
            font-size: 1.3rem;
        }
        .stat-card:hover .icon-circle {
            background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
            box-shadow: 0 12px 22px -4px rgba(214, 177, 92, 0.35);
            color: var(--navy);
        }

        /* ── CONTENT CARDS ── */
        .content-card {
            background: var(--white);
            border-radius: 1.25rem;
            border: 1px solid rgba(214, 177, 92, 0.12);
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .content-card:hover {
            box-shadow: var(--shadow-md);
            border-color: rgba(214, 177, 92, 0.22);
        }
        .content-card .card-accent {
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light), var(--gold-dark));
            border-radius: 3px 3px 0 0;
            opacity: 0;
            transition: opacity 0.35s ease;
        }
        .content-card:hover .card-accent {
            opacity: 1;
        }

        /* ── PROGRESS BARS ── */
        .progress-bar-bg {
            background: #e8e3d7;
            border-radius: 999px;
            overflow: hidden;
        }
        .progress-fill {
            border-radius: 999px;
            transition: width 0.8s cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* ── BADGES ── */
        .badge {
            padding: 0.3rem 0.8rem;
            border-radius: 9999px;
            font-size: 0.7rem;
            font-weight: 500;
            letter-spacing: 0.02em;
            line-height: 1.2;
            display: inline-flex;
            align-items: center;
            white-space: nowrap;
        }
        .badge-gold {
            background-color: rgba(214, 177, 92, 0.15);
            color: #8b6914;
            border: 1px solid rgba(214, 177, 92, 0.3);
        }
        .badge-navy {
            background-color: rgba(10, 20, 40, 0.08);
            color: var(--navy);
            border: 1px solid rgba(10, 20, 40, 0.15);
        }
        .badge-muted {
            background-color: #f0ece4;
            color: var(--text-muted);
            border: 1px solid var(--border);
        }
        .badge-green {
            background-color: #e6f4ea;
            color: #1e6b3a;
            border: 1px solid #b7dfc5;
        }
        .badge-amber {
            background-color: #fef7e6;
            color: #8a5d0b;
            border: 1px solid #f5d78a;
        }
        .badge-red {
            background-color: #fdecea;
            color: #a12b2b;
            border: 1px solid #f2b8b8;
        }

        /* ── BUTTONS ── */
        .btn-primary {
            background: var(--navy);
            color: var(--gold-light);
            border: none;
            padding: 0.65rem 1.4rem;
            border-radius: 2rem;
            font-weight: 500;
            font-size: 0.85rem;
            letter-spacing: 0.02em;
            transition: var(--transition);
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(10, 20, 40, 0.12);
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-primary:hover {
            background: var(--navy-hover);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(10, 20, 40, 0.2);
            color: #fff;
        }
        .btn-primary:active {
            transform: scale(0.97);
        }
        .btn-outline {
            background: transparent;
            border: 1.5px solid var(--gold);
            color: var(--navy);
            padding: 0.6rem 1.3rem;
            border-radius: 2rem;
            font-weight: 500;
            font-size: 0.85rem;
            transition: var(--transition);
            cursor: pointer;
        }
        .btn-outline:hover {
            background: var(--gold);
            color: var(--navy);
            box-shadow: 0 4px 14px rgba(214, 177, 92, 0.3);
        }
        .btn-ghost {
            background: transparent;
            border: none;
            color: var(--text-muted);
            padding: 0.5rem 1rem;
            border-radius: 2rem;
            font-size: 0.8rem;
            cursor: pointer;
            transition: var(--transition);
        }
        .btn-ghost:hover {
            background: rgba(214, 177, 92, 0.08);
            color: var(--navy);
        }

        /* ── TABLES ── */
        table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
        }
        table th {
            font-size: 0.68rem;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: var(--text-muted);
            font-weight: 600;
            padding: 0.9rem 0.8rem 0.7rem 0.8rem;
            border-bottom: 2px solid var(--border);
            text-align: left;
            background: rgba(248, 246, 240, 0.4);
        }
        table td {
            padding: 0.9rem 0.8rem;
            border-bottom: 1px solid rgba(226, 218, 207, 0.5);
            font-size: 0.875rem;
            color: var(--text);
        }
        table tr:last-child td {
            border-bottom: none;
        }
        table tbody tr {
            transition: background 0.15s ease;
        }
        table tbody tr:hover td {
            background: rgba(248, 246, 240, 0.5);
        }

        /* ── MODALS ── */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(5, 16, 33, 0.55);
            backdrop-filter: blur(4px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 50;
            padding: 1rem;
        }
        .modal-overlay.active {
            display: flex;
        }
        .modal-box {
            background: var(--white);
            border-radius: 1.25rem;
            width: 100%;
            max-width: 30rem;
            max-height: 90vh;
            overflow-y: auto;
            padding: 1.75rem;
            animation: fadeInUp 0.25s ease-out both;
            box-shadow: 0 40px 60px -20px rgba(5, 16, 33, 0.3);
            border: 1px solid rgba(214, 177, 92, 0.2);
        }
        .modal-box.wide {
            max-width: 44rem;
        }
        .modal-box .modal-accent {
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-light));
            border-radius: 3px;
            margin-bottom: 1.25rem;
        }
        .form-label {
            display: block;
            font-size: 0.7rem;
            color: var(--text-muted);
            margin-bottom: 0.35rem;
            font-weight: 600;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .form-input,
        .form-select {
            width: 100%;
            background: #faf8f4;
            border: 1.5px solid var(--border);
            border-radius: 0.65rem;
            padding: 0.65rem 0.9rem;
            font-size: 0.875rem;
            color: var(--text);
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
            font-family: 'DM Sans', sans-serif;
        }
        .form-input:focus,
        .form-select:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-glow);
            background: var(--white);
        }
        .form-input::placeholder {
            color: #c8c4bc;
        }
        .form-select option {
            background: var(--white);
            color: var(--text);
        }

        /* ── PROFILE SPECIFIC ── */
        .profile-banner {
            height: 80px;
            background: linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);
            border-radius: 1.25rem 1.25rem 0 0;
        }
        .profile-avatar-ring {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            padding: 3px;
            margin: -45px auto 0;
            box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        }
        .profile-avatar-inner {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: var(--navy);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-light);
            font-weight: 700;
            font-size: 1.8rem;
            font-family: 'DM Sans', sans-serif;
        }
        .info-row {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.6rem 0;
            border-bottom: 1px solid rgba(0,0,0,0.03);
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-icon {
            width: 32px;
            height: 32px;
            border-radius: 10px;
            background: rgba(214, 177, 92, 0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gold-dark);
            font-size: 0.9rem;
            flex-shrink: 0;
        }
        .info-label {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-muted);
            font-weight: 500;
        }
        .info-value {
            font-size: 0.85rem;
            color: var(--text);
            font-weight: 500;
        }
        .form-fieldset-title {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--navy);
            font-weight: 600;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .security-note ul li {
            display: flex;
            align-items: flex-start;
            gap: 0.4rem;
            color: var(--text-muted);
            line-height: 1.5;
        }
        .security-note ul li i {
            color: var(--gold-dark);
            margin-top: 0.15rem;
        }

        /* ── MOBILE BOTTOM NAV ── */
        .mobile-bottom-nav {
            background: rgba(10, 20, 40, 0.96);
            backdrop-filter: blur(12px);
            border-top: 1px solid rgba(214, 177, 92, 0.3);
            box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.2);
        }
        .mobile-nav-link {
            transition: color 0.2s;
        }

        /* ── STAGE TABS ── */
        .stage-tab-btn {
            background: transparent;
            color: var(--text-muted);
            border: none;
            transition: var(--transition);
        }
        .stage-tab-btn.active {
            background: var(--navy);
            color: var(--gold-light);
            box-shadow: 0 2px 8px rgba(10, 20, 40, 0.15);
        }

        /* ── TOAST ── */
        .toast-container {
            position: fixed;
            top: 1.5rem;
            right: 1.5rem;
            z-index: 9999;
            max-width: 28rem;
            width: 100%;
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1), opacity 0.4s ease;
            opacity: 0;
        }
        .toast-container.show {
            transform: translateX(0);
            opacity: 1;
        }
        .toast-content {
            background: var(--white);
            border-radius: 1rem;
            padding: 1rem 1.5rem;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            border-left: 5px solid var(--gold);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .toast-content i {
            color: var(--gold);
            font-size: 1.25rem;
        }

        /* ── RESPONSIVE ── */
        @media (max-width: 768px) {
            .desktop-sidebar {
                display: none;
            }
            .mobile-bottom-nav {
                display: flex;
            }
            main {
                padding-bottom: 6rem;
                padding-left: 1rem;
                padding-right: 1rem;
            }
            .stat-card {
                padding: 1rem !important;
            }
            .stat-card .icon-circle {
                width: 40px;
                height: 40px;
                border-radius: 12px;
            }
        }
        @media (min-width: 769px) {
            .mobile-bottom-nav {
                display: none;
            }
        }

        /* ── HEADING FONTS ── */
        h1, h2, h3, h4, .serif-heading {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            letter-spacing: -0.01em;
        }
        h1 { font-size: 2rem; color: var(--navy); }
        h2 { font-size: 1.5rem; color: var(--navy); }
        h3 { font-size: 1.2rem; color: var(--navy); }

        .gold-accent-line {
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-dark));
            border-radius: 3px;
            margin-top: 0.4rem;
        }
        /* ── Collapsible sections ── */
#milestones-list,
#rubrics-list {
    transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1);
    overflow: hidden;
}
#milestones-list.collapsed,
#rubrics-list.collapsed {
    max-height: 0;
    opacity: 0;
    margin: 0;
    padding: 0;
    pointer-events: none;
}
#milestones-list:not(.collapsed),
#rubrics-list:not(.collapsed) {
    max-height: 2000px;
    opacity: 1;
}
.toggle-section-btn {
    background: none;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    transition: var(--transition);
    cursor: pointer;
}
.toggle-section-btn:hover {
    background: rgba(214, 177, 92, 0.15);
}
    </style>
</head>
<body class="bg-[#f8f6f0] text-[#171e2c]">

    <!-- TOAST NOTIFICATION -->
    <div id="toast" class="toast-container">
        <div class="toast-content">
            <i class="fas fa-check-circle"></i>
            <span id="toastMessage" class="toast-message text-sm font-medium text-[#171e2c]"></span>
            <button type="button" class="toast-close text-[#5b6375] hover:text-[#0a1428] text-lg" onclick="hideToast()">&times;</button>
        </div>
    </div>

    <!-- DESKTOP SIDEBAR -->
    <aside class="desktop-sidebar fixed left-0 top-0 h-full w-64 flex flex-col justify-between z-20  hidden md:flex">
        <div>
            <div class="p-6 flex items-center space-x-3 border-b border-[rgba(214,177,92,0.15)]">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-md"
                     style="background:linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);">
                    <i class="fas fa-graduation-cap text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-sm font-bold tracking-wide text-white" style="font-family:'DM Sans',sans-serif;">Capstone Tracker</h1>
                    <p class="text-[10px] uppercase tracking-widest font-semibold" style="color:var(--gold-light);">Admin</p>
                </div>
            </div>
            <nav class="mt-6 px-4 space-y-1">
                <a href="#" data-section="dashboard" class="nav-link active-link flex items-center justify-between px-4 py-3 text-sm font-medium text-[#d6b15c]">
                    <div class="flex items-center space-x-3"><i class="fas fa-th-large w-4"></i><span>Dashboard</span></div>
                    <i class="fas fa-chevron-right text-xs opacity-70"></i>
                </a>
                <a href="#" data-section="teachers" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                    <i class="fas fa-users w-4"></i><span>Teachers</span>
                </a>
                <a href="#" data-section="students" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                    <i class="fas fa-user-graduate w-4"></i><span>Students & Groups</span>
                </a>
                <a href="#" data-section="rubrics" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                    <i class="fas fa-file-alt w-4"></i><span>Rubrics</span>
                </a>
                <a href="#" data-section="progress" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                    <i class="fas fa-chart-line w-4"></i><span>Progress</span>
                </a>
                <a href="#" data-section="evaluation" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                    <i class="fas fa-door-open w-4"></i><span>Evaluation Room</span>
                </a>
                <a href="#" data-section="profile" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                    <i class="fas fa-user w-4"></i><span>Profile</span>
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-[rgba(214,177,92,0.15)]">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-lg"
                     style="background:linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);">
                    {{ strtoupper(substr($user->name ?? 'MS', 0, 2)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ $user->name ?? 'Dr. Maria Santos' }}</p>
                    <p class="text-[11px]" style="color:rgba(255,255,255,0.5);">System Administrator</p>
                </div>
            </div>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="flex items-center space-x-2 text-sm text-[rgba(255,255,255,0.5)] hover:text-[#f0e0b0] transition">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i><span>Sign Out</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- MOBILE BOTTOM NAV -->
    <div class="mobile-bottom-nav fixed bottom-0 left-0 right-0 py-2 px-2 justify-around items-center z-30 flex md:hidden">
        <a href="#" data-section="dashboard" class="mobile-nav-link flex flex-col items-center text-[#d6b15c] text-xs py-1">
            <i class="fas fa-th-large text-lg"></i><span class="text-[10px] mt-1">Home</span>
        </a>
        <a href="#" data-section="teachers" class="mobile-nav-link flex flex-col items-center text-[rgba(255,255,255,0.55)] text-xs py-1">
            <i class="fas fa-users text-lg"></i><span class="text-[10px] mt-1">Teachers</span>
        </a>
        <a href="#" data-section="students" class="mobile-nav-link flex flex-col items-center text-[rgba(255,255,255,0.55)] text-xs py-1">
            <i class="fas fa-user-graduate text-lg"></i><span class="text-[10px] mt-1">Students</span>
        </a>
        <a href="#" data-section="rubrics" class="mobile-nav-link flex flex-col items-center text-[rgba(255,255,255,0.55)] text-xs py-1">
            <i class="fas fa-file-alt text-lg"></i><span class="text-[10px] mt-1">Rubrics</span>
        </a>
        <a href="#" data-section="progress" class="mobile-nav-link flex flex-col items-center text-[rgba(255,255,255,0.55)] text-xs py-1">
            <i class="fas fa-chart-line text-lg"></i><span class="text-[10px] mt-1">Progress</span>
        </a>
        <a href="#" data-section="evaluation" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                    <i class="fas fa-chart-line w-4"></i><span>Evaluation Room</span>
                </a>
        <a href="#" data-section="profile" class="mobile-nav-link flex flex-col items-center text-[rgba(255,255,255,0.55)] text-xs py-1">
            <i class="fas fa-user text-lg"></i><span class="text-[10px] mt-1">Profile</span>
        </a>
    </div>

    <!-- MAIN CONTENT -->
    <main class="ml-0 md:ml-64 p-4 md:p-8 overflow-y-auto max-h-screen pb-20">

        <!-- ==================== DASHBOARD ==================== -->
        <div id="dashboard-section" class="section-container section-card max-w-7xl mx-auto">
            <div class="mb-8">
                <h1>Dashboard</h1>
                <div class="gold-accent-line"></div>
                <p class="text-[#5b6375] mt-2 text-sm">Overview of your capstone tracking system</p>
            </div>
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="stat-card p-4 md:p-5 flex justify-between items-center">
                    <div>
                        <p class="text-[#5b6375] text-xs font-medium">Total Students</p>
                        <p class="text-2xl md:text-3xl font-bold mt-1 text-[#0a1428]" style="font-family:'Cormorant Garamond',serif;">{{ $totalStudents ?? 0 }}</p>
                    </div>
                    <div class="icon-circle"><i class="fas fa-users text-lg"></i></div>
                </div>
                <div class="stat-card p-4 md:p-5 flex justify-between items-center">
                    <div>
                        <p class="text-[#5b6375] text-xs font-medium">Capstone Groups</p>
                        <p class="text-2xl md:text-3xl font-bold mt-1 text-[#0a1428]" style="font-family:'Cormorant Garamond',serif;">{{ $totalGroups ?? 0 }}</p>
                    </div>
                    <div class="icon-circle"><i class="fas fa-layer-group text-lg"></i></div>
                </div>
                <div class="stat-card p-4 md:p-5 flex justify-between items-center">
                    <div>
                        <p class="text-[#5b6375] text-xs font-medium">Teachers</p>
                        <p class="text-2xl md:text-3xl font-bold mt-1 text-[#0a1428]" style="font-family:'Cormorant Garamond',serif;">{{ $totalTeachers ?? 0 }}</p>
                    </div>
                    <div class="icon-circle"><i class="fas fa-chalkboard-teacher text-lg"></i></div>
                </div>
                <div class="stat-card p-4 md:p-5 flex justify-between items-center">
                    <div>
                        <p class="text-[#5b6375] text-xs font-medium">Sections</p>
                        <p class="text-2xl md:text-3xl font-bold mt-1 text-[#0a1428]" style="font-family:'Cormorant Garamond',serif;">{{ $totalSections ?? 0 }}</p>
                    </div>
                    <div class="icon-circle"><i class="fas fa-columns text-lg"></i></div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="content-card lg:col-span-2">
                    <div class="card-accent"></div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3>Capstone Completion Progress</h3>
                            <i class="fas fa-arrow-up-right-from-square text-[#b8b0a0] cursor-pointer hover:text-[#0a1428] transition text-sm"></i>
                        </div>
                        <div class="flex gap-1.5 bg-[#f5f1e8] p-1 rounded-full mb-5">
                            <button type="button" class="stage-tab-btn text-xs font-medium py-1.5 px-4 rounded-full transition-smooth active" data-stage="1">Capstone 1</button>
                            <button type="button" class="stage-tab-btn text-xs font-medium py-1.5 px-4 rounded-full transition-smooth" data-stage="2">Capstone 2</button>
                        </div>
                        <div class="space-y-4">
                            @forelse($milestoneCompletion ?? [] as $m)
                            @php
                                $pct = ($m->total > 0) ? round(($m->completed / $m->total) * 100) : 0;
                                if ($pct >= 75) { $pctColor = '#1e6b3a'; }
                                elseif ($pct >= 40) { $pctColor = '#b88d3a'; }
                                else { $pctColor = '#a12b2b'; }
                            @endphp
                            <div class="milestone-row flex items-center gap-4" data-stage="{{ $m->stage }}">
                                <div class="w-28 text-sm text-[#171e2c] font-medium flex-shrink-0 truncate" title="{{ $m->name }}">{{ $m->name }}</div>
                                <div class="flex-1 progress-bar-bg h-2.5">
                                    <div class="progress-fill fill-animate h-full" data-target="{{ $pct }}" style="width:0%; background:{{ $m->color ?? $pctColor }};"></div>
                                </div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-xs text-[#9a9385] w-10 text-right">{{ $m->completed }}/{{ $m->total }}</span>
                                    <span class="text-xs font-bold w-9 text-right" style="color:{{ $pctColor }};">{{ $pct }}%</span>
                                </div>
                            </div>
                            @empty
                            <p class="text-[#5b6375] text-sm text-center py-6">No milestones created yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
                <div class="content-card lg:col-span-1">
                    <div class="card-accent"></div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6">
                            <h3>Recent Activity</h3>
                            <i class="fas fa-clock text-[#b8b0a0] cursor-pointer hover:text-[#0a1428] transition text-sm"></i>
                        </div>
                        <div class="space-y-5">
                          @forelse($recentActivities ?? [] as $act)
                        <div class="flex items-start gap-3">
                            <div class="w-6 h-6 rounded-full border flex items-center justify-center flex-shrink-0 mt-0.5 text-[11px]"
                                style="border-color:{{ $act['color'] }}; color:{{ $act['color'] }};">
                                <i class="fas {{ $act['icon'] }}"></i>
                            </div>
                            <div>
                                <p class="text-sm font-semibold text-[#171e2c]">{{ $act['title'] }}</p>
                                <p class="text-xs text-[#5b6375]">{{ $act['subtitle'] }}</p>
                                <p class="text-[10px] text-[#9a9385] mt-0.5">{{ \Carbon\Carbon::parse($act['timestamp'])->diffForHumans() }}</p>
                            </div>
                        </div>
                        @empty
                        <p class="text-[#5b6375] text-sm text-center py-6">No recent activity yet.</p>
                        @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== TEACHERS ==================== -->
        <div id="teachers-section" class="section-container hidden section-card">
            <div class="mb-8">
                <h1>Teachers</h1>
                <div class="gold-accent-line"></div>
                <p class="text-[#5b6375] mt-2 text-sm">Manage faculty accounts and section assignments.</p>
            </div>
            <div class="content-card">
                <div class="card-accent"></div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-5 flex-wrap gap-3">
                        <h3>All Teachers</h3>
                        <div class="flex gap-2">
                        <button onclick="resetAssignModal(); openModal('assign_modal')" class="btn-outline text-sm"><i class="fas fa-link mr-1"></i> Change Adviser</button>
                        <button onclick="openModal('import_teacher_modal')" class="btn-outline text-sm"><i class="fas fa-file-import mr-1"></i> Import Excel</button>
                        <button onclick="openModal('teacher_modal')" class="btn-primary text-sm"><i class="fas fa-plus mr-1"></i> Add Teacher</button>
                    </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table>
                            <thead><tr><th>Teacher</th><th>Email</th><th>Assigned Groups</th><th class="pr-2">Actions</th></tr></thead>
                            <tbody>
                                @forelse($allTeachers ?? [] as $teacher)
                                <tr>
                                    <td>
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                                 style="background:linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);">
                                                {{ strtoupper(substr($teacher->teacher_first_name, 0, 1) . substr($teacher->teacher_last_name, 0, 1)) }}
                                            </div>
                                            <div><p class="text-sm font-semibold">{{ $teacher->teacher_first_name }} {{ $teacher->teacher_last_name }}</p><p class="text-xs text-[#5b6375]">{{ $teacher->user->user_id ?? 'N/A' }}</p></div>
                                        </div>
                                    </td>
                                    <td class="text-sm text-[#3d4450]">{{ $teacher->teacher_email }}</td>
                                    <td>
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($teacher->groups as $group)
                                            <span class="badge badge-navy">{{ $group->group_name }}</span>
                                            @empty
                                            <span class="badge badge-muted">No Group</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="pr-2">
                                        <div class="flex gap-3 text-[#5b6375]">
                                            <button onclick='openEditTeacherModal(@json($teacher))' class="hover:text-[#0a1428] transition"><i class="fas fa-pen"></i></button>
                                            <button type="button" onclick="openDeleteTeacherModal('{{ $teacher->user_id }}', '{{ addslashes($teacher->teacher_first_name . ' ' . $teacher->teacher_last_name) }}')" class="hover:text-red-500 transition"><i class="fas fa-trash"></i></button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="py-6 text-center text-[#5b6375]">No teachers found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== STUDENTS ==================== -->
        <div id="students-section" class="section-container hidden section-card">
    <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
        <div>
            <h1>Students & Groups</h1>
            <div class="gold-accent-line"></div>
            <p class="text-[#5b6375] mt-2 text-sm">Manage student accounts and group assignments.</p>
        </div>
        <div class="flex gap-1.5 bg-[#f5f1e8] p-1 rounded-full">
            <button type="button" class="stage-tab-btn sg-tab-btn text-xs font-medium py-1.5 px-4 rounded-full transition-smooth active" data-view="students">Students</button>
            <button type="button" class="stage-tab-btn sg-tab-btn text-xs font-medium py-1.5 px-4 rounded-full transition-smooth" data-view="groups">Groups</button>
        </div>
    </div>

    <!-- STUDENTS VIEW -->
    <div id="sg-students-view" class="content-card">
        <div class="card-accent"></div>
        <div class="p-6">
            <div class="flex justify-between items-center mb-5 flex-wrap gap-3">
                <h3>All Students</h3>
                <div class="flex gap-2">
                    <button onclick="openModal('createGroupModal')" class="btn-outline text-sm"><i class="fas fa-users mr-1"></i> Create Group</button>
                    <button onclick="openModal('import_student_modal')" class="btn-outline text-sm"><i class="fas fa-file-import mr-1"></i> Import Excel</button>
                    <button onclick="openModal('student_modal')" class="btn-primary text-sm"><i class="fas fa-plus mr-1"></i> Register Student</button>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table>
                    <thead><tr><th>Student</th><th>ID</th><th>Course</th><th>Section</th><th>Group</th><th class="pr-2">Actions</th></tr></thead>
                    <tbody>
                        @forelse($allStudents ?? [] as $student)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white flex-shrink-0"
                                         style="background:linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);">
                                        {{ strtoupper(substr($student->student_first_name, 0, 1) . substr($student->student_last_name, 0, 1)) }}
                                    </div>
                                    <span class="text-sm">{{ $student->student_first_name }} {{ $student->student_last_name }}</span>
                                </div>
                            </td>
                            <td class="text-sm text-[#3d4450]">{{ $student->user->user_id ?? 'N/A' }}</td>
                            <td class="text-sm text-[#3d4450]">{{ $student->course }}</td>
                            <td class="text-sm text-[#3d4450]">{{ $student->section }}</td>
                            <td class="text-sm text-[#3d4450]">{{ $student->groups->pluck('group_name')->join(', ') ?: 'No Group' }}</td>
                            <td class="pr-2">
                                <div class="flex gap-3 text-[#5b6375]">
                                    <button onclick='openEditStudentModal(@json($student))' class="hover:text-[#0a1428] transition"><i class="fas fa-pen"></i></button>
                                    <button type="button" onclick="openDeleteStudentModal('{{ $student->user_id }}', '{{ addslashes($student->student_first_name . ' ' . $student->student_last_name) }}')" class="hover:text-red-500 transition"><i class="fas fa-trash"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-6 text-center text-[#5b6375]">No students found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- GROUPS VIEW -->
    <div id="sg-groups-view" class="content-card hidden">
        <div class="card-accent"></div>
        <div class="p-6">
            <div class="flex justify-between items-center mb-5 flex-wrap gap-3">
                <h3>All Groups</h3>
                <button onclick="openModal('createGroupModal')" class="btn-primary text-sm"><i class="fas fa-plus mr-1"></i> Create Group</button>
            </div>
            <div class="overflow-x-auto">
                <table>
                    <thead><tr><th>Group</th><th>Capstone Title</th><th>Section</th><th>Adviser</th><th>Members</th><th>Room</th><th class="pr-2">Actions</th></tr></thead>
                    <tbody>
                        @forelse($groupsData ?? [] as $group)
                        <tr>
                            <td class="text-sm font-semibold text-[#171e2c]">{{ $group['name'] }}</td>
                            <td class="text-sm text-[#3d4450]">{{ $group['capstone_title'] ?? '—' }}</td>
                            <td class="text-sm text-[#3d4450]">{{ $group['section_name'] }}</td>
                            <td class="text-sm text-[#3d4450]">{{ $group['assigned_teacher_name'] ?? 'Unassigned' }}</td>
                            <td class="text-sm text-[#3d4450]">{{ $group['member_count'] ?? 0 }}</td>
                            <td class="text-sm text-[#3d4450]">
                                <span class="badge {{ $group['room_name'] !== 'Unassigned' ? 'badge-gold' : 'badge-muted' }}">
                                    {{ $group['room_name'] }}
                                </span>
                            </td>
                            <td class="pr-2">
                                <div class="flex gap-3 text-[#5b6375]">
                                    <button onclick="openEditGroupModal({{ $group['id'] }})" class="hover:text-[#0a1428] transition"><i class="fas fa-pen"></i></button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="py-6 text-center text-[#5b6375]">No groups found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

       <!-- ==================== RUBRICS ==================== -->
<div id="rubrics-section" class="section-container hidden section-card">
    <div class="mb-8">
        <h1>Rubrics</h1>
        <div class="gold-accent-line"></div>
        <p class="text-[#5b6375] mt-2 text-sm">Create and manage evaluation rubrics for each capstone stage.</p>
    </div>

    <!-- Action buttons (no filter here) -->
    <div class="flex flex-wrap items-center justify-end gap-3 mb-5">
        <button onclick="openModal('milestone_modal')" class="btn-outline text-sm"><i class="fas fa-plus mr-1"></i> Add Milestone</button>
        <button onclick="openModal('rubrics_modal')" class="btn-primary text-sm"><i class="fas fa-plus mr-1"></i> Create Rubric</button>
    </div>

    <!-- Milestones Section -->
    <div class="content-card mb-6">
        <div class="card-accent"></div>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center gap-4">
                    <h3>All Milestones</h3>
                    <!-- Filter dropdown -->
                    <select id="milestone-stage-filter" class="form-select w-36 py-1.5 text-sm">
                        <option value="all">All Stages</option>
                        <option value="1">Capstone 1</option>
                        <option value="2">Capstone 2</option>
                    </select>
                </div>
                <button type="button" class="toggle-section-btn text-[#5b6375] hover:text-[#0a1428] transition-transform duration-200" data-target="milestones-list">
                    <i class="fa-solid fa-chevron-up"></i>
                </button>
            </div>
            <div id="milestones-list" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($milestones as $milestone)
                <div class="bg-[#faf8f4] border border-[#e2dacf] rounded-xl p-5 hover:border-[#d6b15c] transition cursor-pointer group milestone-item" data-stage="{{ $milestone->capstone_stage_id }}">
                    <div class="flex justify-between items-start">
                        <h4 class="font-semibold text-sm text-[#0a1428]">{{ $milestone->milestone_title }}</h4>
                        <div class="flex gap-3 text-[#5b6375] ml-2 opacity-0 group-hover:opacity-100 transition">
                            <button onclick="openEditMilestoneModal({{ $milestone->id }})" class="hover:text-[#0a1428]"><i class="fas fa-pen text-xs"></i></button>
                            <button onclick="openDeleteMilestoneModal({{$milestone->id}})" class="hover:text-red-500"><i class="fas fa-trash text-xs"></i></button>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <span class="badge badge-gold">{{ $milestone->milestone_title }}</span>
                    </div>
                </div>
                @empty
                <p class="text-[#5b6375] col-span-2 text-center py-6">No milestones created yet.</p>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Rubrics Section -->
    <div class="content-card">
        <div class="card-accent"></div>
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3>All Rubrics</h3>
                <button type="button" class="toggle-section-btn text-[#5b6375] hover:text-[#0a1428] transition-transform duration-200" data-target="rubrics-list">
                    <i class="fa-solid fa-chevron-up"></i>
                </button>
            </div>
            <div id="rubrics-list" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @forelse($rubrics as $rubric)
                @php $stage = $rubric->milestone->capstone_stage_id ?? null; @endphp
                <div class="bg-[#faf8f4] border border-[#e2dacf] rounded-xl p-5 hover:border-[#d6b15c] transition cursor-pointer group rubric-item" data-stage="{{ $stage }}">
                    <div class="flex justify-between items-start">
                        <h4 class="font-semibold text-sm text-[#0a1428]">{{ $rubric->rubric_name }}</h4>
                        <div class="flex gap-3 text-[#5b6375] ml-2 opacity-0 group-hover:opacity-100 transition">
                            <button onclick="openEditRubricModal({{ $rubric->id }})" class="hover:text-[#0a1428]"><i class="fas fa-pen text-xs"></i></button>
                            <button onclick="openDeleterubricModal({{$rubric->id}})" class="hover:text-red-500"><i class="fas fa-trash text-xs"></i></button>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-3">
                        <span class="badge badge-gold">{{ $stage == 1 ? 'Capstone 1' : ($stage == 2 ? 'Capstone 2' : 'No Stage') }}</span>
                        <span class="badge badge-muted">{{ $rubric->criteria->count() }} Criteria</span>
                    </div>
                    <div class="flex gap-4 mt-3 text-sm text-[#5b6375]">
                        <span>Max: {{ $rubric->criteria->sum('max_score') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-[#5b6375] col-span-2 text-center py-6">No rubrics created yet.</p>
                @endforelse
            </div>
        </div>
    </div>
</div>

        <!-- ==================== PROGRESS ==================== -->
        <div id="progress-section" class="section-container hidden section-card">
            <div class="mb-8 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
                <div>
                    <h1>Progress Monitoring</h1>
                    <div class="gold-accent-line"></div>
                    <p class="text-[#5b6375] mt-2 text-sm">Real-time tracking of all capstone sections and groups.</p>
                </div>
                <div class="flex items-center gap-2 text-xs text-[#5b6375]">
                    <i class="fas fa-circle text-[8px]" style="color:#1e6b3a;"></i> On Track
                    <i class="fas fa-circle text-[8px] ml-3" style="color:#b88d3a;"></i> At Risk
                    <i class="fas fa-circle text-[8px] ml-3" style="color:#a12b2b;"></i> Needs Attention
                </div>
            </div>
            <div class="content-card mb-8">
                <div class="card-accent"></div>
                <div class="p-6 grid grid-cols-2 lg:grid-cols-4 divide-x divide-[#eee7d9]">
                    @php
                    $progressStats = [
                        ['label'=>'On Track','count'=>$onTrackCount??48,'color'=>'#1e6b3a','icon'=>'fa-check-circle'],
                        ['label'=>'At Risk','count'=>$atRiskCount??9,'color'=>'#b88d3a','icon'=>'fa-clock'],
                        ['label'=>'Needs Attention','count'=>$delayedCount??5,'color'=>'#a12b2b','icon'=>'fa-exclamation-triangle'],
                    ];
                    $avgP = $avgProgress ?? 73;
                    $ringColor = $avgP >= 75 ? '#1e6b3a' : ($avgP >= 50 ? '#b88d3a' : '#a12b2b');
                    $ringDeg = round(($avgP/100)*360);
                    @endphp
                    @foreach($progressStats as $i => $ps)
                    <div class="flex items-center gap-3 {{ $i === 0 ? 'pl-0' : 'pl-6' }} pr-6">
                        <div class="w-9 h-9 rounded-lg flex items-center justify-center flex-shrink-0" style="background:{{ $ps['color'] }}14; color:{{ $ps['color'] }};">
                            <i class="fas {{ $ps['icon'] }} text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-[#9a9385] font-semibold">{{ $ps['label'] }}</p>
                            <p class="text-xl font-bold text-[#0a1428] leading-tight" style="font-family:'Cormorant Garamond',serif;">{{ $ps['count'] }} <span class="text-xs font-normal text-[#9a9385]" style="font-family:'DM Sans',sans-serif;">groups</span></p>
                        </div>
                    </div>
                    @endforeach
                    <div class="flex items-center gap-4 pl-6">
                        <div class="relative w-14 h-14 flex-shrink-0" style="border-radius:50%; background:conic-gradient({{ $ringColor }} {{ $ringDeg }}deg, #eee7d9 0deg);">
                            <div class="absolute inset-[3px] rounded-full bg-white flex items-center justify-center"><span class="text-sm font-bold text-[#0a1428]">{{ $avgP }}%</span></div>
                        </div>
                        <div>
                            <p class="text-[11px] uppercase tracking-wide text-[#9a9385] font-semibold">Overall</p>
                            <p class="text-sm font-semibold text-[#171e2c]">Average Progress</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-start">
                <div class="content-card">
                    <div class="card-accent"></div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-6 pb-4 border-b border-[#f0ece4]">
                            <div><h3>Section Progress</h3><p class="text-xs text-[#9a9385] mt-0.5">Completion rate by student section</p></div>
                            <i class="fas fa-chart-simple text-[#c9a24a]"></i>
                        </div>
                        @if(count($sectionProgress ?? []) <= 0)
                        <p class="text-[#5b6375] text-sm text-center py-6">No student sections found.</p>
                        @else
                        <div class="space-y-5">
                            @foreach($sectionProgress as $section)
                            @php
                                $avg = $section->avg ?? 0;
                                if ($avg >= 75) { $statusColor = '#1e6b3a'; $statusBg = '#e6f4ea'; }
                                elseif ($avg >= 50) { $statusColor = '#8a5d0b'; $statusBg = '#fef7e6'; }
                                else { $statusColor = '#a12b2b'; $statusBg = '#fdecea'; }
                                $total = $section->done + $section->in_progress + $section->not_started;
                            @endphp
                            <div class="pb-5 border-b border-[#f5f1e8] last:border-0 last:pb-0">
                                <div class="flex justify-between items-center mb-1.5">
                                    <h4 class="font-bold text-sm text-[#0a1428] tracking-tight">{{ $section->name }}</h4>
                                    <span class="badge flex items-center gap-1.5" style="background:{{ $statusBg }}; color:{{ $statusColor }}; border:1px solid {{ $statusColor }}30;">
                                        <span class="inline-block w-1.5 h-1.5 rounded-full" style="background:{{ $statusColor }};"></span>{{ $avg }}%
                                    </span>
                                </div>
                                <div class="progress-bar-bg h-2.5 w-full"><div class="progress-fill fill-animate h-full" data-target="{{ $avg }}" style="width:0%; background:{{ $statusColor }};"></div></div>
                                @if($total > 0)
                                <p class="text-[11px] text-[#8b8477] mt-2 font-medium flex flex-wrap items-center gap-x-3 gap-y-1">
                                    <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full inline-block" style="background:#1e6b3a;"></span> {{ $section->done }} done</span>
                                    <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full inline-block" style="background:#b88d3a;"></span> {{ $section->in_progress }} in progress</span>
                                    <span class="inline-flex items-center gap-1"><span class="w-2 h-2 rounded-full inline-block" style="background:#9a9385;"></span> {{ $section->not_started }} not started</span>
                                </p>
                                @else
                                <p class="text-xs text-[#9a9385] mt-2">No groups yet</p>
                                @endif
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
                <div class="content-card">
                    <div class="card-accent"></div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-2 pb-4 border-b border-[#f0ece4]">
                            <div><h3>Milestone Completion</h3><p class="text-xs text-[#9a9385] mt-0.5">Progress across capstone stages</p></div>
                            <div class="flex gap-1.5 bg-[#f5f1e8] p-1 rounded-full">
                                <button type="button" class="stage-tab-btn text-xs font-medium py-1.5 px-4 rounded-full transition-smooth active" data-stage="1">Capstone 1</button>
                                <button type="button" class="stage-tab-btn text-xs font-medium py-1.5 px-4 rounded-full transition-smooth" data-stage="2">Capstone 2</button>
                            </div>
                        </div>
                        <div class="space-y-4 mt-5">
                            @forelse($milestoneCompletion ?? [] as $m)
                            @php
                                $pct = ($m->total > 0) ? round(($m->completed / $m->total) * 100) : 0;
                                if ($pct >= 75) { $pctColor = '#1e6b3a'; }
                                elseif ($pct >= 40) { $pctColor = '#b88d3a'; }
                                else { $pctColor = '#a12b2b'; }
                            @endphp
                            <div class="milestone-row flex items-center gap-4" data-stage="{{ $m->stage }}">
                                <div class="w-28 text-sm text-[#171e2c] font-medium flex-shrink-0 truncate" title="{{ $m->name }}">{{ $m->name }}</div>
                                <div class="flex-1 progress-bar-bg h-2.5"><div class="progress-fill fill-animate h-full" data-target="{{ $pct }}" style="width:0%; background:{{ $m->color ?? $pctColor }};"></div></div>
                                <div class="flex items-center gap-2 flex-shrink-0">
                                    <span class="text-xs text-[#9a9385] w-10 text-right">{{ $m->completed }}/{{ $m->total }}</span>
                                    <span class="text-xs font-bold w-9 text-right" style="color:{{ $pctColor }};">{{ $pct }}%</span>
                                </div>
                            </div>
                            @empty
                            <p class="text-[#5b6375] text-sm text-center py-6">No milestones created yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- evaluation --}}
      <div id="evaluation-section" class="section-container hidden section-card">
            <div class="mb-8"><h1>Evaluation</h1><div class="gold-accent-line"></div><p class="text-[#5b6375] mt-2 text-sm">Create evaluation rooms and manage panelists.</p></div>
            <div class="content-card">
                <div class="card-accent"></div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-5 flex-wrap gap-3">
                        <h3>All Rooms</h3>
                        <button onclick="openModal('evaluation_modal')" class="btn-primary text-sm"><i class="fas fa-plus mr-1"></i> Create Room</button>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @forelse($evaluationRooms ?? [] as $room)
                        <div class="bg-[#faf8f4] border border-[#e2dacf] rounded-xl p-5 hover:border-[#d6b15c] transition cursor-pointer group" onclick="openEditRoomModal({{ $room->id }})">
                            <div class="flex justify-between items-start">
                                <h4 class="font-semibold text-sm text-[#0a1428]">{{ $room->room_name }}</h4>
                                <button onclick="event.stopPropagation(); deleteRoom({{ $room->id }})" class="text-[#5b6375] hover:text-red-500 opacity-0 group-hover:opacity-100 transition"><i class="fas fa-trash text-xs"></i></button>
                            </div>
                            <div class="flex items-center gap-2 mt-2" onclick="event.stopPropagation();">
                                <span class="badge badge-amber font-mono tracking-wider">{{ $room->join_code }}</span>
                                <button onclick="event.stopPropagation(); regenerateRoomCode({{ $room->id }}, this)" class="text-[#5b6375] hover:text-[#0a1428] text-xs" title="Regenerate code">
                                    <i class="fas fa-rotate"></i>
                                </button>
                            </div>
                            <div class="flex flex-wrap gap-1 mt-3">
                                @forelse($room->panelists as $p)
                                <span class="badge badge-navy">{{ $p->teacher_first_name }} {{ $p->teacher_last_name }}</span>
                                @empty
                                <span class="badge badge-muted">No panelists yet</span>
                                @endforelse
                            </div>
                            <div class="mt-4 pt-3 border-t border-[#e2dacf]/50">
                                <div class="text-[11px] uppercase tracking-wider font-semibold text-[#8b6914] mb-1.5">Assigned Groups</div>
                                <div class="flex flex-wrap gap-1">
                                    @forelse($room->groups as $g)
                                    <span class="badge badge-gold">{{ $g->group_name }}</span>
                                    @empty
                                    <span class="badge badge-muted">No groups yet</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                        @empty
                        <p class="text-[#5b6375] col-span-2 text-center py-6">No evaluation rooms created yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <!-- ==================== PROFILE ==================== -->
        <div id="profile-section" class="section-container hidden section-card max-w-7xl mx-auto">
            <div class="mb-8"><h1>Profile</h1><div class="gold-accent-line"></div><p class="text-[#5b6375] mt-2 text-sm">Manage your personal information and contact details</p></div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="content-card lg:col-span-1 overflow-hidden">
                    <div class="profile-banner"></div>
                    <div class="p-6 -mt-12 flex flex-col items-center text-center">
                        <div class="profile-avatar-ring">
                            <div class="profile-avatar-inner">{{ strtoupper(substr($admin->admin_first_name ?? $user->name ?? 'T', 0, 2)) }}</div>
                        </div>
                        <h2 class="mt-4">{{ $admin->admin_first_name ?? '' }} {{ $admin->admin_last_name ?? '' }}</h2>
                        <p class="text-[#5b6375] text-xs font-medium tracking-wide uppercase mt-0.5">Administrator</p>
                        <p class="text-[#5b6375] text-sm mt-1">ID: {{ $admin->user_id ?? $user->user_id ?? '—' }}</p>
                        <div class="mt-5 w-full pt-5 border-t border-[#e2dacf] space-y-1">
                            <div class="info-row">
                                <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                                <div class="text-left"><p class="info-label">Contact</p><p class="info-value">{{ $admin->contact_number ?? 'Not provided' }}</p></div>
                            </div>
                            <div class="info-row">
                                <div class="info-icon"><i class="fa-regular fa-envelope"></i></div>
                                <div class="text-left"><p class="info-label">Email</p><p class="info-value break-all">{{ $admin->admin_email ?? $user->email ?? 'Not provided' }}</p></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content-card lg:col-span-2">
                    <div class="card-accent"></div>
                    <div class="p-6">
                        <h3 class="mb-5 flex items-center gap-2"><i class="fa-regular fa-pen-to-square text-[#d6b15c]"></i> Edit Profile</h3>
                        <form action="{{ route('admin.profile_update') }}" method="POST" class="space-y-6">
                            @csrf
                            <div>
                                <p class="form-fieldset-title"><i class="fa-regular fa-user"></i> Personal Information</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div><label class="form-label">First Name</label><input type="text" name="admin_first_name" value="{{ old('admin_first_name', $admin->admin_first_name ?? '') }}" class="form-input"></div>
                                    <div><label class="form-label">Last Name</label><input type="text" name="admin_last_name" value="{{ old('admin_last_name', $admin->admin_last_name ?? '') }}" class="form-input"></div>
                                    <div><label class="form-label">Middle Name</label><input type="text" name="admin_middle_name" value="{{ old('admin_middle_name', $admin->admin_middle_name ?? '') }}" class="form-input"></div>
                                    <div><label class="form-label">Administrator ID</label><input type="text" disabled value="{{ $admin->user_id ?? $user->user_id ?? '' }}" class="form-input opacity-70 cursor-not-allowed"></div>
                                </div>
                            </div>
                            <div>
                                <p class="form-fieldset-title"><i class="fa-regular fa-address-card"></i> Contact Details</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div><label class="form-label">Contact Number</label><input type="text" name="contact_number" value="{{ old('contact_number', $admin->contact_number ?? '') }}" class="form-input" placeholder="e.g. 09XX XXX XXXX"></div>
                                    <div><label class="form-label">Email</label><input type="email" name="admin_email" value="{{ old('admin_email', $admin->admin_email ?? $user->email ?? '') }}" class="form-input"></div>
                                </div>
                            </div>
                            <div class="flex justify-end pt-2 border-t border-[#e2dacf]"><button type="submit" class="btn-primary"><i class="fa-regular fa-floppy-disk mr-2"></i>Save Changes</button></div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="content-card lg:col-span-2">
                    <div class="card-accent"></div>
                    <div class="p-6">
                        <h3 class="mb-5 flex items-center gap-2"><i class="fa-solid fa-key text-[#d6b15c]"></i> Change Password</h3>
                        <form action="{{ route('admin.profile.update_password') }}" method="POST" class="space-y-4" id="passwordForm">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="md:col-span-2"><label class="form-label">Username</label><input type="text" value="{{ $user->name ?? '' }}" class="form-input bg-[#e8e3d7] cursor-not-allowed" disabled></div>
                                <div>
                                    <label class="form-label">Current Password</label>
                                    <div class="relative">
                                        <input type="password" name="current_password" id="current_password" class="form-input pr-10" placeholder="Enter current password" required>
                                        <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#5b6375] hover:text-[#0a1428]" data-target="current_password"><i class="fa-regular fa-eye"></i></button>
                                    </div>
                                    @error('current_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div>
                                    <label class="form-label">New Password</label>
                                    <div class="relative">
                                        <input type="password" name="new_password" id="new_password" class="form-input pr-10" placeholder="Min 6 characters" required>
                                        <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#5b6375] hover:text-[#0a1428]" data-target="new_password"><i class="fa-regular fa-eye"></i></button>
                                    </div>
                                    <div class="mt-2 h-1.5 rounded-full bg-[#e8e3d7] overflow-hidden"><div id="password_strength_bar" class="h-full w-0 rounded-full"></div></div>
                                    @error('new_password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                                </div>
                                <div class="md:col-span-2">
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="relative">
                                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-input pr-10" placeholder="Re-enter new password" required>
                                        <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#5b6375] hover:text-[#0a1428]" data-target="new_password_confirmation"><i class="fa-regular fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                            <div class="flex justify-end pt-2 border-t border-[#e2dacf]"><button type="submit" class="btn-primary"><i class="fa-solid fa-floppy-disk mr-2"></i>Update Password</button></div>
                        </form>
                    </div>
                </div>
                <div class="content-card lg:col-span-1">
                    <div class="card-accent"></div>
                    <div class="p-6">
                        <h3 class="mb-4 flex items-center gap-2"><i class="fa-solid fa-shield-halved text-[#d6b15c]"></i> Account Security</h3>
                        <div class="security-note">
                            <ul class="space-y-2.5 text-xs text-[#5b6375]">
                                <li><i class="fa-solid fa-circle-check"></i> Use at least 8 characters with a mix of letters and numbers.</li>
                                <li><i class="fa-solid fa-circle-check"></i> Avoid reusing passwords from other accounts.</li>
                                <li><i class="fa-solid fa-circle-check"></i> Update your password periodically.</li>
                                <li><i class="fa-solid fa-circle-check"></i> Never share your login details with students or colleagues.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <!-- ======================= MODALS ======================= -->
<div id="assign_modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-accent"></div>
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Change Adviser</h2>
            <button type="button" onclick="closeModal('assign_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
        </div>
        <form action="{{ route('admin.assign_group') }}" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="form-label">Section</label>
                <select id="ca_section_select" class="form-select" required>
                    <option value="" disabled selected>Select section</option>
                    @foreach($sections as $section)
                    <option value="{{ $section->id }}">{{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Group</label>
                <select name="group_id" id="ca_group_select" class="form-select" required disabled>
                    <option value="" disabled selected>Select a section first</option>
                </select>
            </div>
            <div>
                <label class="form-label">Current Adviser</label>
                <input type="text" id="ca_current_adviser" class="form-input opacity-70" readonly placeholder="—">
            </div>
            <div>
                <label class="form-label">New Adviser</label>
                <select name="adviser_id" id="ca_adviser_select" class="form-select" required disabled>
                    <option value="" disabled selected>Select a group first</option>
                    @foreach($allTeachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->teacher_first_name }} {{ $teacher->teacher_last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeModal('assign_modal')" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary">Change Adviser</button>
            </div>
        </form>
    </div>
</div>

    <!-- ADD TEACHER MODAL -->
    <div id="teacher_modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-accent"></div>
            <div class="flex justify-between items-center mb-4">
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Add Teacher</h2>
                <button type="button" onclick="closeModal('teacher_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
            </div>
            <form action="{{ route('admin.add_teacher') }}" method="POST" class="space-y-3">
                @csrf
                <div><label class="form-label">Teacher ID</label><input type="text" name="teacher_id" class="form-input" placeholder="e.g. PJR-2026" required></div>
                <div class="grid grid-cols-3 gap-2">
                    <div><label class="form-label">First Name</label><input type="text" name="teacher_first_name" class="form-input" required></div>
                    <div><label class="form-label">Middle Name</label><input type="text" name="teacher_middle_name" class="form-input"></div>
                    <div><label class="form-label">Last Name</label><input type="text" name="teacher_last_name" class="form-input" required></div>
                </div>
                <div><label class="form-label">Email</label><input type="email" name="teacher_email" class="form-input" required></div>
                <div class="flex justify-end gap-2 pt-3"><button type="button" onclick="closeModal('teacher_modal')" class="btn-ghost">Cancel</button><button type="submit" class="btn-primary">Save Teacher</button></div>
            </form>
        </div>
    </div>

    <!-- EDIT TEACHER MODAL -->
    <div id="teacher_edit_modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-accent"></div>
            <div class="flex justify-between items-center mb-4">
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Edit Teacher</h2>
                <button type="button" onclick="closeModal('teacher_edit_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
            </div>
            <form action="{{ route('admin.edit_teacher') }}" method="POST" class="space-y-3" id="teacher_edit_form">
                @csrf
                <input type="hidden" name="original_teacher_id" id="edit_original_teacher_id">
                <div><label class="form-label">Teacher ID</label><input type="text" name="teacher_id" id="edit_teacher_id" class="form-input opacity-70" readonly></div>
                <div class="grid grid-cols-3 gap-2">
                    <div><label class="form-label">First Name</label><input type="text" name="teacher_first_name" id="edit_teacher_first_name" class="form-input" required></div>
                    <div><label class="form-label">Middle Name</label><input type="text" name="teacher_middle_name" id="edit_teacher_middle_name" class="form-input"></div>
                    <div><label class="form-label">Last Name</label><input type="text" name="teacher_last_name" id="edit_teacher_last_name" class="form-input" required></div>
                </div>
                <div><label class="form-label">Email</label><input type="email" name="teacher_email" id="edit_teacher_email" class="form-input" required></div>
                <div><label class="form-label">Contact Number</label><input type="text" name="contact_number" id="edit_teacher_contact" class="form-input" pattern="09[0-9]{9}" maxlength="11" minlength="11"></div>
                <div class="flex justify-end gap-2 pt-3"><button type="button" onclick="closeModal('teacher_edit_modal')" class="btn-ghost">Cancel</button><button type="submit" class="btn-primary">Save Teacher</button></div>
            </form>
        </div>
    </div>

    <!-- DELETE TEACHER MODAL -->
    <div id="delete_teacher_modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-accent"></div>
            <div class="flex justify-between items-center mb-4">
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Confirm Deletion</h2>
                <button type="button" onclick="closeModal('delete_teacher_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
            </div>
            <form action="{{ route('admin.delete_teacher') }}" method="POST" class="space-y-3">
                @csrf
                @if ($errors->any() && old('confirm_delete_teacher'))
                <div class="bg-red-50 border border-red-300 text-red-700 p-3 rounded-lg text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <input type="hidden" name="confirm_delete_teacher" value="1">
                <input type="hidden" name="teacher_id" id="delete_teacher_id">
                <p class="text-sm text-[#5b6375]">You are about to permanently delete <strong id="delete_teacher_name" class="text-[#171e2c]"></strong>. This cannot be undone.</p>
                <div>
                    <label class="form-label">Confirm Your Admin Password</label>
                    <div class="relative">
                        <input type="password" name="admin_password" id="delete_teacher_admin_password" class="form-input pr-10" placeholder="Enter your password" required>
                        <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#5b6375] hover:text-[#0a1428]" onclick="toggleVisibility('delete_teacher_admin_password', this)"><i class="fa-regular fa-eye"></i></button>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3"><button type="button" onclick="closeModal('delete_teacher_modal')" class="btn-ghost">Cancel</button><button type="submit" class="btn-primary" style="background:#a12b2b;"><i class="fas fa-trash mr-1"></i> Delete Teacher</button></div>
            </form>
        </div>
    </div>

    <!-- EDIT STUDENT MODAL -->
    <div id="student_edit_modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-accent"></div>
            <div class="flex justify-between items-center mb-4">
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Edit Student</h2>
                <button type="button" onclick="closeModal('student_edit_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
            </div>
            <form action="{{ route('admin.edit_student') }}" method="POST" class="space-y-3" id="student_edit_form">
                @csrf
                @if ($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 p-3 rounded-lg mb-2 text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <input type="hidden" name="original_student_id" id="edit_original_student_id">
                <div><label class="form-label">Student ID</label><input type="text" name="student_id" id="edit_student_id" class="form-input" readonly></div>
                <div class="grid grid-cols-3 gap-2">
                    <div><label class="form-label">First Name</label><input type="text" name="student_first_name" id="edit_first_name" class="form-input" required></div>
                    <div><label class="form-label">Middle Name</label><input type="text" name="student_middle_name" id="edit_middle_name" class="form-input"></div>
                    <div><label class="form-label">Last Name</label><input type="text" name="student_last_name" id="edit_last_name" class="form-input" required></div>
                </div>
                <div><label class="form-label">Email</label><input type="email" name="student_email" id="edit_email" class="form-input" required></div>
                <div><label class="form-label">Contact Number</label><input type="text" name="contact_number" id="edit_contact" class="form-input" pattern="09[0-9]{9}" maxlength="11" minlength="11" required></div>
                <div class="grid grid-cols-2 gap-2">
                    <div><label class="form-label">Course</label><input type="text" name="course" value="BSIT" class="form-input opacity-70" readonly></div>
                    <div><label class="form-label">Section</label><select name="section" id="edit_section" class="form-select" required><option value="East">East</option><option value="West">West</option><option value="North">North</option><option value="South">South</option><option value="SouthEast">SouthEast</option><option value="SouthWest">SouthWest</option><option value="NorthEast">NorthEast</option><option value="NorthWest">NorthWest</option></select></div>
                </div>
                <div class="flex justify-end gap-2 pt-3"><button type="button" onclick="closeModal('student_edit_modal')" class="btn-ghost">Cancel</button><button type="submit" class="btn-primary">Save Student</button></div>
            </form>
        </div>
    </div>

    <!-- DELETE STUDENT MODAL -->
    <div id="delete_student_modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-accent"></div>
            <div class="flex justify-between items-center mb-4">
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Confirm Deletion</h2>
                <button type="button" onclick="closeModal('delete_student_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
            </div>
            <form action="{{ route('admin.delete_student') }}" method="POST" class="space-y-3">
                @csrf
                @if ($errors->any() && old('confirm_delete'))
                <div class="bg-red-50 border border-red-300 text-red-700 p-3 rounded-lg text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <input type="hidden" name="confirm_delete" value="1">
                <input type="hidden" name="student_id" id="delete_student_id">
                <p class="text-sm text-[#5b6375]">You are about to permanently delete <strong id="delete_student_name" class="text-[#171e2c]"></strong>. This cannot be undone.</p>
                <div>
                    <label class="form-label">Confirm Your Admin Password</label>
                    <div class="relative">
                        <input type="password" name="admin_password" id="delete_admin_password" class="form-input pr-10" placeholder="Enter your password" required>
                        <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#5b6375] hover:text-[#0a1428]" onclick="toggleVisibility('delete_admin_password', this)"><i class="fa-regular fa-eye"></i></button>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3"><button type="button" onclick="closeModal('delete_student_modal')" class="btn-ghost">Cancel</button><button type="submit" class="btn-primary" style="background:#a12b2b;"><i class="fas fa-trash mr-1"></i> Delete Student</button></div>
            </form>
        </div>
    </div>

    <!-- REGISTER STUDENT MODAL -->
    <div id="student_modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-accent"></div>
            <div class="flex justify-between items-center mb-4">
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Register Student</h2>
                <button type="button" onclick="closeModal('student_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
            </div>
            <form action="{{ route('admin.add_student') }}" method="POST" class="space-y-3">
                @csrf
                <div><label class="form-label">Student ID</label><input type="text" name="student_id" class="form-input" placeholder="e.g. 2021-1234" required></div>
                <div class="grid grid-cols-3 gap-2">
                    <div><label class="form-label">First Name</label><input type="text" name="student_first_name" class="form-input" required></div>
                    <div><label class="form-label">Middle Name</label><input type="text" name="student_middle_name" class="form-input"></div>
                    <div><label class="form-label">Last Name</label><input type="text" name="student_last_name" class="form-input" required></div>
                </div>
                <div><label class="form-label">Email</label><input type="email" name="student_email" class="form-input" required></div>
                <div><label class="form-label">Contact Number</label><input type="text" name="contact_number" class="form-input" pattern="09[0-9]{9}" maxlength="11" minlength="11" required></div>
                <div class="grid grid-cols-2 gap-2">
                    <div><label class="form-label">Course</label><input type="text" name="course" value="BSIT" class="form-input opacity-70" readonly></div>
                    <div><label class="form-label">Section</label><select name="section" class="form-select" required><option value="East">East</option><option value="West">West</option><option value="North">North</option><option value="South">South</option><option value="SouthEast">SouthEast</option><option value="SouthWest">SouthWest</option><option value="NorthEast">NorthEast</option><option value="NorthWest">NorthWest</option></select></div>
                </div>
                <div class="flex justify-end gap-2 pt-3"><button type="button" onclick="closeModal('student_modal')" class="btn-ghost">Cancel</button><button type="submit" class="btn-primary">Save Student</button></div>
            </form>
        </div>
    </div>

    <!-- ADD MILESTONE MODAL -->
    <div id="milestone_modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-accent"></div>
            <div class="flex justify-between items-center mb-4">
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Add Milestone</h2>
                <button type="button" onclick="closeModal('milestone_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
            </div>
            <form action="{{ route('admin.add_milestone') }}" method="POST" class="space-y-3">
                @csrf
                @if ($errors->any())
                <div class="bg-red-50 border border-red-300 text-red-700 p-3 rounded-lg mb-4 text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <div><label class="form-label">Milestone Name</label><input type="text" name="milestone_title" class="form-input" placeholder="e.g. title hearing" required></div>
                <div><label class="form-label">Capstone Stage</label><select name="capstone_stage" class="form-select" required>@foreach($capstoneStages as $stageS)<option value="{{ $stageS->id }}">{{ $stageS->stage_title }}</option>@endforeach</select></div>
                <div><label class="form-label">Order Position</label><select name="order" class="form-select" required>@php $existingOrders = $milestones->pluck('step_order')->toArray(); $maxOrder = $milestones->count() + 1; @endphp @for ($i = 1; $i <= $maxOrder; $i++)<option value="{{ $i }}" {{ in_array($i, $existingOrders) ? 'disabled' : '' }}>Position {{ $i }} {{ in_array($i, $existingOrders) ? '(taken)' : '' }}</option>@endfor</select></div>
                <div><label class="form-label">Description <span class="text-[#9a9385]">(optional)</span></label><textarea name="description" rows="3" class="form-input resize-none" placeholder="What students need to accomplish..."></textarea></div>
                <div class="grid grid-cols-2 gap-2">
                    <div><label class="form-label">Start Date</label><input type="date" name="start_date" min="{{ date('Y-m-d') }}" class="form-input" style="color-scheme:light;"></div>
                    <div><label class="form-label">Due Date</label><input type="date" name="due_date" min="{{ date('Y-m-d') }}" class="form-input" style="color-scheme:light;"></div>
                </div>
                <div class="flex justify-end gap-2 pt-3"><button type="button" onclick="closeModal('milestone_modal')" class="btn-ghost">Cancel</button><button type="submit" class="btn-primary">Save Milestone</button></div>
            </form>
        </div>
    </div>

    <!-- CREATE RUBRIC MODAL -->
    <div id="rubrics_modal" class="modal-overlay">
        <div class="modal-box wide">
            <div class="modal-accent"></div>
            <div class="flex justify-between items-center mb-4">
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Create Rubric</h2>
                <button type="button" onclick="closeModal('rubrics_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
            </div>
            <form action="{{ route('admin.add_rubric') }}" method="POST" class="space-y-4" onsubmit="return validateWeights()">
                @csrf
                <div><label class="form-label">Rubric Name</label><input type="text" name="rubric_name" class="form-input" placeholder="e.g. Proposal Defense Rubric" required></div>
                <div><label class="form-label">Capstone Stage</label><select name="capstone_id" id="rubric_capstone_id" class="form-select" required><option value="" disabled selected>Select capstone stage</option>@foreach($capstoneStages as $stage)<option value="{{ $stage->id }}">{{ $stage->stage_title }}</option>@endforeach</select></div>
                <div>
                    <label class="form-label">Milestone</label>
                    <select name="milestone_id" class="form-select" required id="milestone-select">
                        <option value="" disabled selected>Select milestone</option>
                        @foreach($milestones as $milestone)
                        <option value="{{ $milestone->id }}" data-capstone-stage-id="{{ $milestone->capstone_stage_id }}">{{ $milestone->milestone_title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <label class="form-label !mb-0">Criteria <span class="text-[#9a9385] font-normal normal-case">(weights must total 100%)</span></label>
                        <button type="button" onclick="addCriteriaRow()" class="text-xs text-[#d6b15c] hover:text-[#b88d3a] transition font-medium"><i class="fas fa-plus mr-1"></i>Add criteria</button>
                    </div>
                    <div class="grid grid-cols-12 gap-2 mb-1 text-[11px] text-[#5b6375]"><span class="col-span-6">Criteria</span><span class="col-span-2">Weight %</span><span class="col-span-3">Max score</span><span class="col-span-1"></span></div>
                    <div id="criteria-list" class="space-y-2"></div>
                    <div id="error-message" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
                <div class="flex justify-end gap-2 pt-2"><button type="button" onclick="closeModal('rubrics_modal')" class="btn-ghost">Cancel</button><button type="submit" class="btn-primary">Save Rubric</button></div>
            </form>
        </div>
    </div>
    <!-- EDIT MILESTONE MODAL -->
    <div id="edit_milestone_modal" class="modal-overlay">
        <div class="modal-box wide">
            <div class="modal-accent"></div>
            <div class="flex justify-between items-center mb-4">
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Edit Milestone</h2>
                <button type="button" onclick="closeModal('edit_milestone_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
            </div>
            <div id="edit_milestone_errors" class="hidden mb-3 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"></div>
            <form id="edit_milestone_form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="milestone_id" id="edit_milestone_id">

                <div>
                    <label class="form-label">Milestone Name</label>
                    <input type="text" name="milestone_title" id="edit_milestone_title" class="form-input" required>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">Capstone Stage</label>
                        <select name="capstone_stage" id="edit_milestone_stage" class="form-select" required>
                            @foreach($capstoneStages as $stageS)
                            <option value="{{ $stageS->id }}">{{ $stageS->stage_title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="form-label">Order Position</label>
                        <input type="number" name="order" id="edit_milestone_order" min="1" class="form-input" required>
                    </div>
                </div>

                <div>
                    <label class="form-label">Description</label>
                    <textarea name="description" id="edit_milestone_description" rows="3" class="form-input resize-none" required></textarea>
                </div>

                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label class="form-label">Start Date</label>
                        <input type="date" name="start_date" id="edit_milestone_start" class="form-input" style="color-scheme:light;" required>
                    </div>
                    <div>
                        <label class="form-label">Due Date</label>
                        <input type="date" name="due_date" id="edit_milestone_due" class="form-input" style="color-scheme:light;" required>
                    </div>
                </div>

                <div class="border-t border-dashed border-[#e2dacf] pt-4">
                    <label class="flex items-center gap-2 cursor-pointer mb-3">
                        <input type="checkbox" name="has_certificate" id="edit_milestone_has_cert" value="1"
                            class="form-checkbox text-[#d6b15c] focus:ring-[#d6b15c]">
                        <span class="text-sm font-medium text-[#0a1428]">Award a certificate when this milestone is completed</span>
                    </label>
                    <p class="text-xs text-[#9a9385] mb-3">
                        The moment a group finishes this milestone (rubric evaluation or remark evaluation), this certificate is added to the group automatically — no extra step needed.
                    </p>

                    <div id="edit_milestone_cert_fields" class="space-y-3 hidden">
                        <div>
                            <label class="form-label">Certificate Title</label>
                            <input type="text" name="certificate_title" id="edit_certificate_title" class="form-input" placeholder="e.g. Certificate of Completion">
                        </div>
                        <div>
                            <label class="form-label">Certificate Description</label>
                            <textarea name="certificate_description" id="edit_certificate_description" rows="2" class="form-input resize-none" placeholder="Awarded for successfully completing..."></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex justify-end gap-2 pt-2">
                    <button type="button" onclick="closeModal('edit_milestone_modal')" class="btn-ghost">Cancel</button>
                    <button type="submit" class="btn-primary">Save Milestone</button>
                </div>
            </form>
        </div>
    </div>

    <!-- EDIT RUBRIC MODAL -->
    <div id="rubrics_edit_modal" class="modal-overlay">
        <div class="modal-box wide">
            <div class="modal-accent"></div>
            <div class="flex justify-between items-center mb-4">
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Edit Rubric</h2>
                <button type="button" onclick="closeModal('rubrics_edit_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
            </div>
            <div id="edit_rubric_errors" class="hidden mb-3 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"></div>
            <form id="edit_rubric_form" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" name="rubric_id" id="edit_rubric_id">
                <div><label class="form-label">Rubric Name</label><input type="text" name="rubric_name" id="edit_rubric_name" class="form-input" required></div>
                <div><label class="form-label">Capstone Stage</label><select name="capstone_id" id="edit_capstone_id" class="form-select" required><option value="" disabled>Select capstone stage</option>@foreach($capstoneStages as $stage)<option value="{{ $stage->id }}">{{ $stage->stage_title }}</option>@endforeach</select></div>
                <div>
                    <label class="form-label">Milestone</label>
                    <select name="milestone_id" class="form-select" required id="edit_milestone_select">
                        <option value="" disabled>Select milestone</option>
                        @foreach($milestones as $milestone)
                        <option value="{{ $milestone->id }}" data-capstone-stage-id="{{ $milestone->capstone_stage_id }}">{{ $milestone->milestone_title }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <div class="flex justify-between items-end mb-2">
                        <label class="form-label !mb-0">Criteria <span class="text-[#9a9385] font-normal normal-case">(weights must total 100%)</span></label>
                        <button type="button" onclick="addCriteriaRow('edit_criteria_list')" class="text-xs text-[#d6b15c] hover:text-[#b88d3a] transition font-medium"><i class="fas fa-plus mr-1"></i>Add criteria</button>
                    </div>
                    <div class="grid grid-cols-12 gap-2 mb-1 text-[11px] text-[#5b6375]"><span class="col-span-6">Criteria</span><span class="col-span-2">Weight %</span><span class="col-span-3">Max score</span><span class="col-span-1"></span></div>
                    <div id="edit_criteria_list" class="space-y-2"></div>
                    <div id="edit_error_message" class="text-red-500 text-xs mt-1 hidden"></div>
                </div>
                <div class="flex justify-end gap-2 pt-2"><button type="button" onclick="closeModal('rubrics_edit_modal')" class="btn-ghost">Cancel</button><button type="submit" class="btn-primary">Update Rubric</button></div>
            </form>
        </div>
    </div>

    <!-- DELETE RUBRIC MODAL -->
    <div id="delete_rubric_modal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-accent"></div>
            <div class="flex justify-between items-center mb-4">
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Confirm Deletion</h2>
                <button type="button" onclick="closeModal('delete_rubric_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
            </div>
            <form action="{{ route('admin.delete_rubrics') }}" method="POST" class="space-y-3">
                @csrf
                @if ($errors->any() && old('confirm_delete'))
                <div class="bg-red-50 border border-red-300 text-red-700 p-3 rounded-lg text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
                @endif
                <input type="hidden" name="confirm_delete" value="1">
                <input type="hidden" name="rubric_id" id="delete_rubric_id">
                <p class="text-sm text-[#5b6375]">You are about to permanently delete <strong id="delete_rubric_name" class="text-[#171e2c]"></strong>. This cannot be undone.</p>
                <div>
                    <label class="form-label">Confirm Your Admin Password</label>
                    <div class="relative">
                        <input type="password" name="admin_password" id="delete_rubric_admin_password" class="form-input pr-10" placeholder="Enter your password" required>
                        <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#5b6375] hover:text-[#0a1428]" onclick="toggleVisibility('delete_rubric_admin_password', this)"><i class="fa-regular fa-eye"></i></button>
                    </div>
                </div>
                <div class="flex justify-end gap-2 pt-3"><button type="button" onclick="closeModal('delete_rubric_modal')" class="btn-ghost">Cancel</button><button type="submit" class="btn-primary" style="background:#a12b2b;"><i class="fas fa-trash mr-1"></i> Delete Rubric</button></div>
            </form>
        </div>
    </div>
    {{-- evaluation modal --}}
<div id="evaluation_modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-accent"></div>
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Create Evaluation Rooms</h2>
            <button type="button" onclick="closeModal('evaluation_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
        </div>
        <form action="{{ route('admin.create_room') }}" method="POST" class="space-y-4">
            @csrf
            @if ($errors->any())
            <div class="bg-red-50 border border-red-300 text-red-700 p-3 rounded-lg text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <div>
                <label class="form-label">Number of Rooms to Create</label>
                <input type="number" name="room_count" class="form-input" min="1" value="1" required>
                <p class="text-xs text-[#9a9385] mt-1">All groups in the system will be divided evenly among the newly created rooms/classrooms.</p>
            </div>
            <div>
                <label class="form-label">Panelists <span class="text-[#9a9385] font-normal normal-case">(Optional, can assign later)</span></label>
                <select name="panelists[]" class="form-select" multiple style="height:120px;">
                    @foreach($allTeachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->teacher_first_name }} {{ $teacher->teacher_last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeModal('evaluation_modal')" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary">Create Rooms</button>
            </div>
        </form>
    </div>
</div>
{{-- edit room modal --}}
<div id="edit_room_modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-accent"></div>
        <div class="flex justify-between items-center mb-4">
            <h2 id="edit_room_title" style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Edit Room</h2>
            <button type="button" onclick="closeModal('edit_room_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
        </div>
        <div class="space-y-4">
            <div>
                <label class="form-label">Add Panelist</label>
                <div class="flex gap-2">
                    <select id="edit_room_teacher_select" class="form-select flex-1">
                        @foreach($allTeachers as $teacher)
                        <option value="{{ $teacher->id }}">{{ $teacher->teacher_first_name }} {{ $teacher->teacher_last_name }}</option>
                        @endforeach
                    </select>
                    <button type="button" id="edit_room_add_btn" class="btn-primary whitespace-nowrap"><i class="fas fa-plus mr-1"></i> Add</button>
                </div>
            </div>
            <div>
                <label class="form-label">Current Panelists</label>
                <div id="edit_room_panelists_container" class="space-y-2 max-h-56 overflow-y-auto"></div>
            </div>
            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeModal('edit_room_modal')" class="btn-ghost">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- IMPORT STUDENTS MODAL -->
<div id="import_student_modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-accent"></div>
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Import Students</h2>
            <button type="button" onclick="closeModal('import_student_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
        </div>
        <form action="{{ route('admin.import_students') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @if ($errors->any() && session('import_students'))
            <div class="bg-red-50 border border-red-300 text-red-700 p-3 rounded-lg text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <div>
                <label class="form-label">Excel File (.xlsx, .csv)</label>
                <input type="file" name="file" accept=".xlsx,.csv" class="form-input" required>
                <p class="text-xs text-[#9a9385] mt-1">
                    Columns: student_id, student_first_name, student_middle_name, student_last_name, student_email, contact_number, course, section
                </p>
            </div>
            <a href="{{ route('admin.download_student_template') }}" class="text-xs text-[#d6b15c] hover:text-[#b88d3a] font-medium inline-flex items-center gap-1">
                <i class="fas fa-download"></i> Download template
            </a>
            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeModal('import_student_modal')" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary"><i class="fas fa-file-import mr-1"></i> Import Students</button>
            </div>
        </form>
    </div>
</div>
{{-- edit group modal --}}
<div id="edit_group_modal" class="modal-overlay">
    <div class="modal-box wide">
        <div class="modal-accent"></div>
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Edit Group</h2>
            <button type="button" onclick="closeModal('edit_group_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
        </div>
        <div id="edit_group_errors" class="hidden mb-3 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"></div>
        <form id="edit_group_form" method="POST" class="space-y-4">
            @csrf
            @method('PUT')
            <input type="hidden" name="group_id" id="edit_group_id">
            <div><label class="form-label">Group Name</label><input type="text" name="group_name" id="edit_group_name" class="form-input" required></div>
            <div><label class="form-label">Capstone Title</label><input type="text" name="capstone_title" id="edit_group_capstone_title" class="form-input" required></div>
            <div>
                <label class="form-label">Adviser</label>
                <select name="adviser_id" id="edit_group_adviser" class="form-select" required>
                    @foreach($allTeachers as $teacher)
                    <option value="{{ $teacher->id }}">{{ $teacher->teacher_first_name }} {{ $teacher->teacher_last_name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="form-label">Section</label>
                <input type="text" id="edit_group_section_display" class="form-input opacity-70 cursor-not-allowed" readonly>
            </div>
            <div>
                <label class="form-label">Add Student to Team</label>
                <div class="flex gap-2">
                    <select id="edit_group_student_select" class="form-select flex-1" multiple style="height:100px;"><option disabled>Loading section students...</option></select>
                    <button type="button" id="edit_group_add_students_btn" class="btn-primary whitespace-nowrap"><i class="fas fa-plus mr-1"></i> Add</button>
                </div>
            </div>
            <div>
                <label class="form-label">Current Team</label>
                <div id="edit_group_members_container" class="space-y-2 max-h-56 overflow-y-auto"></div>
            </div>
            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeModal('edit_group_modal')" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary">Save Group</button>
            </div>
        </form>
    </div>
</div>
<!-- IMPORT TEACHERS MODAL -->
<div id="import_teacher_modal" class="modal-overlay">
    <div class="modal-box">
        <div class="modal-accent"></div>
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Import Teachers</h2>
            <button type="button" onclick="closeModal('import_teacher_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
        </div>
        <form action="{{ route('admin.import_teachers') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
            @csrf
            @if ($errors->any() && session('import_teachers'))
            <div class="bg-red-50 border border-red-300 text-red-700 p-3 rounded-lg text-sm"><ul class="list-disc list-inside">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
            @endif
            <div>
                <label class="form-label">Excel File (.xlsx, .csv)</label>
                <input type="file" name="file" accept=".xlsx,.csv" class="form-input" required>
                <p class="text-xs text-[#9a9385] mt-1">
                    Columns: teacher_id, teacher_first_name, teacher_middle_name, teacher_last_name, teacher_email, contact_number
                </p>
            </div>
            <a href="{{ route('admin.download_teacher_template') }}" class="text-xs text-[#d6b15c] hover:text-[#b88d3a] font-medium inline-flex items-center gap-1">
                <i class="fas fa-download"></i> Download template
            </a>
            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeModal('import_teacher_modal')" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary"><i class="fas fa-file-import mr-1"></i> Import Teachers</button>
            </div>
        </form>
    </div>
</div>
{{-- create group modal --}}
<div id="createGroupModal" class="modal-overlay">
    <div class="modal-box wide">
        <div class="modal-accent"></div>
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Create New Group</h2>
            <button type="button" onclick="closeModal('createGroupModal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
        </div>
        <form action="{{ route('admin.create_group') }}" method="POST" class="space-y-4">
            @csrf
            <div><label class="form-label">Group Name</label><input type="text" name="group_name" class="form-input" required></div>
            <div><label class="form-label">Capstone Title</label><input type="text" name="capstone_title" class="form-input" required></div>
            <div>
                <label class="form-label">Section</label>
              <select name="section" id="sectionSelect" class="form-select" required>
                    <option value="" disabled selected>Select a section</option>
                    @foreach($sections ?? [] as $section)
                        <!-- value holds the ID for form submission, data-name holds the Name for AJAX fetch -->
                        <option value="{{ $section->id }}" data-name="{{ $section->section_name }}">{{ $section->section_name }}</option>
                    @endforeach
                </select>
            </div>
            
            <div>
                <label class="form-label">Select Students (Ctrl to select multiple)</label>
                <div class="flex gap-2">
                    <select id="studentSelect" class="form-select flex-1" multiple style="height:100px;"><option disabled>Select a section first</option></select>
                    <button type="button" id="addStudentsBtn" class="btn-primary whitespace-nowrap"><i class="fas fa-plus mr-1"></i> Add</button>
                </div>
            </div>
            
            <div>
                <label class="form-label">Assigned Roles</label>
                <div id="selectedStudentsContainer" class="space-y-2 max-h-48 overflow-y-auto"></div>
                <p id="noStudentsMsg" class="text-sm text-[#5b6375] text-center py-2">No students added yet.</p>
            </div>
           <div>
    <label class="form-label">Adviser</label>
    <select name="adviser" id="adviserSelect" class="form-select" required>
        <option value="" disabled selected>Select an adviser</option>
        @foreach($allTeachers as $teacher)
            <option value="{{ $teacher->id }}">{{ $teacher->teacher_first_name }} {{ $teacher->teacher_last_name }}</option>
            @endforeach
        </select>
    </div>
            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeModal('createGroupModal')" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary">Create Group</button>
            </div>
        </form>
    </div>
</div>
    <script>
        // ---- SECTION NAVIGATION ----
        const sections = {
            dashboard: document.getElementById('dashboard-section'),
            teachers: document.getElementById('teachers-section'),
            students: document.getElementById('students-section'),
            rubrics: document.getElementById('rubrics-section'),
            progress: document.getElementById('progress-section'),
            evaluation: document.getElementById('evaluation-section'),
            profile: document.getElementById('profile-section'),
        };
        const navLinks = document.querySelectorAll('.nav-link');
        const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

        function activateSection(sectionId) {
            Object.values(sections).forEach(s => s.classList.add('hidden'));
            if (sections[sectionId]) sections[sectionId].classList.remove('hidden');
            navLinks.forEach(link => {
                const isActive = link.dataset.section === sectionId;
                link.classList.toggle('active-link', isActive);
                if (isActive) {
                    link.style.color = 'var(--gold)';
                } else {
                    link.style.color = 'rgba(255,255,255,0.65)';
                }
                const chevron = link.querySelector('.fa-chevron-right');
                if (chevron) chevron.style.display = isActive ? 'inline-block' : 'none';
            });
            mobileNavLinks.forEach(link => {
                link.style.color = link.dataset.section === sectionId ? 'var(--gold)' : 'rgba(255,255,255,0.55)';
            });
            localStorage.setItem('activeSection', sectionId);
        }
        document.querySelectorAll('.sg-tab-btn').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.sg-tab-btn').forEach(b => b.classList.toggle('active', b === btn));
        const view = btn.dataset.view;
        document.getElementById('sg-students-view').classList.toggle('hidden', view !== 'students');
        document.getElementById('sg-groups-view').classList.toggle('hidden', view !== 'groups');
    });
});

        // Restore active section
        document.addEventListener('DOMContentLoaded', () => {
            navLinks.forEach(link => {
                const chevron = link.querySelector('.fa-chevron-right');
                if (chevron) chevron.style.display = link.classList.contains('active-link') ? 'inline-block' : 'none';
            });
            const stored = localStorage.getItem('activeSection');
            if (stored && sections[stored]) activateSection(stored);
            else activateSection('dashboard');

            @if(session('success'))
                showToast('{{ session('success') }}', false);
            @endif

            @if(session('error'))
                showToast('{{ session('error') }}', true);
            @endif

            @if ($errors->any() && session('import_teachers'))
                openModal('import_teacher_modal');
            @endif

            @if ($errors->any() && session('import_students'))
                openModal('import_student_modal');
            @endif

            // animate fill bars on load
            document.querySelectorAll('.fill-animate').forEach(bar => {
                const target = bar.dataset.target || 0;
                requestAnimationFrame(() => {
                    setTimeout(() => { bar.style.width = target + '%'; }, 100);
                });
            });
            filterMilestones();
            addCriteriaRow();
            showStage('1');
            // ── RUBRICS: Toggle sections ──
                document.querySelectorAll('.toggle-section-btn').forEach(btn => {
                    btn.addEventListener('click', function () {
                        const targetId = this.dataset.target;
                        const target = document.getElementById(targetId);
                        if (!target) return;
                        const icon = this.querySelector('i');
                        const isCollapsed = target.classList.toggle('collapsed');
                        icon.className = isCollapsed ? 'fa-solid fa-chevron-down' : 'fa-solid fa-chevron-up';
                    });
                });

                // ── RUBRICS: Filter by stage ──
                const stageFilter = document.getElementById('rubric-stage-filter');
                if (stageFilter) {
                    stageFilter.addEventListener('change', function () {
                        const selected = this.value;
                        const items = document.querySelectorAll('.rubric-item');
                        items.forEach(el => {
                            const stage = el.dataset.stage;
                            if (selected === 'all' || String(stage) === selected) {
                                el.style.display = '';
                            } else {
                                el.style.display = 'none';
                            }
                        });
                    });
                    
                     }
                     // ── RUBRICS: Milestone filter ──
                const milestoneFilter = document.getElementById('milestone-stage-filter');
                if (milestoneFilter) {
                    milestoneFilter.addEventListener('change', function () {
                        const selected = this.value;
                        const items = document.querySelectorAll('.milestone-item');
                        items.forEach(el => {
                            const stage = el.dataset.stage;
                            if (selected === 'all' || String(stage) === selected) {
                                el.style.display = '';
                            } else {
                                el.style.display = 'none';
                            }
                        });
                    });
                }
        });

        [...navLinks, ...mobileNavLinks].forEach(el => {
            el.addEventListener('click', e => {
                e.preventDefault();
                const s = el.dataset.section;
                if (s && sections[s]) activateSection(s);
            });
        });

        // ---- MODALS ----
        function openModal(id) { document.getElementById(id).classList.add('active'); }
        function closeModal(id) { document.getElementById(id).classList.remove('active'); }
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', e => { if (e.target === overlay) overlay.classList.remove('active'); });
        });

        // ---- TOAST ----
        const toastEl = document.getElementById('toast');
        const toastMessageEl = document.getElementById('toastMessage');
        let toastTimeout;
        function showToast(msg, isError = false) {
            toastMessageEl.textContent = msg;
            const iconEl = toastEl.querySelector('.toast-content i');
            const contentEl = toastEl.querySelector('.toast-content');
            if (iconEl && contentEl) {
                if (isError) {
                    iconEl.className = 'fas fa-exclamation-circle text-red-500';
                    contentEl.style.borderLeftColor = '#a12b2b';
                } else {
                    iconEl.className = 'fas fa-check-circle text-gold';
                    contentEl.style.borderLeftColor = 'var(--gold)';
                }
            }
            toastEl.classList.add('show');
            if (toastTimeout) clearTimeout(toastTimeout);
            toastTimeout = setTimeout(() => hideToast(), 3000);
        }
        function hideToast() {
            toastEl.classList.remove('show');
            if (toastTimeout) { clearTimeout(toastTimeout); toastTimeout = null; }
        }

        // ---- PASSWORD TOGGLE ----
        document.querySelectorAll('.password-toggle').forEach(btn => {
            btn.addEventListener('click', function () {
                const targetId = this.dataset.target;
                if (!targetId) return;
                const input = document.getElementById(targetId);
                if (input) {
                    const icon = this.querySelector('i');
                    if (input.type === 'password') {
                        input.type = 'text';
                        if (icon) {
                            icon.classList.remove('fa-eye');
                            icon.classList.add('fa-eye-slash');
                        }
                    } else {
                        input.type = 'password';
                        if (icon) {
                            icon.classList.remove('fa-eye-slash');
                            icon.classList.add('fa-eye');
                        }
                    }
                }
            });
        });
        function toggleVisibility(id, btn) {
            const input = document.getElementById(id);
            if (input) {
                if (input.type === 'password') {
                    input.type = 'text';
                    if (btn) {
                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.className = 'fa-regular fa-eye-slash';
                        }
                    }
                } else {
                    input.type = 'password';
                    if (btn) {
                        const icon = btn.querySelector('i');
                        if (icon) {
                            icon.className = 'fa-regular fa-eye';
                        }
                    }
                }
            }
        }

        // ---- CRITERIA ROWS ----
        function addCriteriaRow(listId = 'criteria-list', values = null) {
            const list = document.getElementById(listId);
            const row = document.createElement('div');
            row.className = 'criteria-row grid grid-cols-12 gap-2';
            row.innerHTML = `
                <input type="text" name="criteria_name[]" placeholder="Criteria name" class="form-input col-span-6" required value="${values?.criteria_name ?? ''}">
                <input type="number" name="weight[]" min="0" max="100" step="0.01" placeholder="Weight %" class="form-input col-span-2" required value="${values?.weight ?? ''}">
                <input type="number" name="score[]" min="0" step="0.01" placeholder="Max score" class="form-input col-span-3" required value="${values?.max_score ?? ''}">
                <button type="button" onclick="this.closest('.criteria-row').remove()" class="col-span-1 text-[#5b6375] hover:text-red-500 flex items-center justify-center"><i class="fas fa-trash text-xs"></i></button>`;
            list.appendChild(row);
        }

        function validateWeights() {
            const errorEl = document.getElementById('error-message');
            errorEl.classList.add('hidden');
            const weights = document.querySelectorAll('#criteria-list input[name="weight[]"]');
            let total = 0;
            weights.forEach(w => total += parseFloat(w.value) || 0);
            total = Math.round(total * 100) / 100;
            if (total !== 100) {
                errorEl.textContent = `Total weight must equal 100%. Current total: ${total}%`;
                errorEl.classList.remove('hidden');
                return false;
            }
            return true;
        }
        // edit milestone modal
        function openEditMilestoneModal(milestoneId) {
                fetch(`/admin/get-milestone/${milestoneId}`)
                    .then(r => r.json())
                    .then(data => {
                        document.getElementById('edit_milestone_id').value = data.id;
                        document.getElementById('edit_milestone_title').value = data.milestone_title;
                        document.getElementById('edit_milestone_stage').value = data.capstone_stage_id;
                        document.getElementById('edit_milestone_order').value = data.step_order;
                        document.getElementById('edit_milestone_description').value = data.milestone_description;
                        document.getElementById('edit_milestone_start').value = data.start_date ? data.start_date.substring(0, 10) : '';
                        document.getElementById('edit_milestone_due').value = data.due_date ? data.due_date.substring(0, 10) : '';

                        const hasCertCheckbox = document.getElementById('edit_milestone_has_cert');
                        const certFields = document.getElementById('edit_milestone_cert_fields');
                        if (data.certificate) {
                            hasCertCheckbox.checked = true;
                            certFields.classList.remove('hidden');
                            document.getElementById('edit_certificate_title').value = data.certificate.certificate_title;
                            document.getElementById('edit_certificate_description').value = data.certificate.certificate_description;
                        } else {
                            hasCertCheckbox.checked = false;
                            certFields.classList.add('hidden');
                            document.getElementById('edit_certificate_title').value = '';
                            document.getElementById('edit_certificate_description').value = '';
                        }

                        document.getElementById('edit_milestone_form').action = `/admin/update-milestone/${data.id}`;
                        document.getElementById('edit_milestone_errors').classList.add('hidden');
                        openModal('edit_milestone_modal');
                    })
                    .catch(() => showToast('Failed to load milestone.', true));
            }

            document.getElementById('edit_milestone_has_cert').addEventListener('change', function () {
                document.getElementById('edit_milestone_cert_fields').classList.toggle('hidden', !this.checked);
            });

            document.getElementById('edit_milestone_form').addEventListener('submit', function (e) {
                e.preventDefault();
                const form = this;
                const errorsBox = document.getElementById('edit_milestone_errors');
                errorsBox.classList.add('hidden');
                errorsBox.innerHTML = '';

                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: new FormData(form)
                })
                .then(async r => {
                    if (r.redirected) { window.location.href = r.url; return; }
                    const data = await r.json().catch(() => null);
                    if (data?.errors) {
                        errorsBox.innerHTML = Object.values(data.errors).flat().join('<br>');
                        errorsBox.classList.remove('hidden');
                    } else {
                        showToast('Failed to update milestone.', true);
                    }
                })
                .catch(() => showToast('Failed to update milestone.', true));
            });

        // ---- EDIT RUBRIC LOGIC ----
        function openEditRubricModal(rubricId) {
            fetch(`/admin/get-rubric/${rubricId}`)
                .then(r => r.json())
                .then(data => {
                    document.getElementById('edit_rubric_id').value = data.id;
                    document.getElementById('edit_rubric_name').value = data.rubric_name;
                    document.getElementById('edit_capstone_id').value = data.capstone_id;
                    document.getElementById('edit_rubric_form').action = `/admin/update-rubric/${data.id}`;

                    const milestoneSelect = document.getElementById('edit_milestone_select');
                    Array.from(milestoneSelect.options).forEach(opt => {
                        if (!opt.value) return;
                        opt.style.display = (opt.dataset.capstoneStageId == data.capstone_id) ? '' : 'none';
                    });
                    milestoneSelect.value = data.milestone_id;

                    const list = document.getElementById('edit_criteria_list');
                    list.innerHTML = '';
                    data.criteria.forEach(c => addCriteriaRow('edit_criteria_list', c));
                    if (data.criteria.length === 0) addCriteriaRow('edit_criteria_list');

                    document.getElementById('edit_rubric_errors').classList.add('hidden');
                    openModal('rubrics_edit_modal');
                })
                .catch(() => showToast('Failed to load rubric.'));
        }

        document.getElementById('edit_capstone_id').addEventListener('change', function () {
            const stageId = this.value;
            const ms = document.getElementById('edit_milestone_select');
            Array.from(ms.options).forEach(opt => {
                if (!opt.value) return;
                opt.style.display = (!stageId || opt.dataset.capstoneStageId == stageId) ? '' : 'none';
            });
            ms.value = '';
        });

        document.getElementById('edit_rubric_form').addEventListener('submit', function(e) {
            e.preventDefault();
            const weights = document.querySelectorAll('#edit_criteria_list input[name="weight[]"]');
            let total = 0; weights.forEach(w => total += parseFloat(w.value) || 0);
            total = Math.round(total * 100) / 100;
            const errorEl = document.getElementById('edit_error_message');
            if (total !== 100) {
                errorEl.textContent = `Total weight must equal 100%. Current total: ${total}%`;
                errorEl.classList.remove('hidden');
                return;
            }
            errorEl.classList.add('hidden');
            const form = this;
            const errorsBox = document.getElementById('edit_rubric_errors');
            errorsBox.classList.add('hidden');
            errorsBox.innerHTML = '';
            fetch(form.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json'
                },
                body: new FormData(form)
            })
            .then(async r => {
                if (r.redirected) { window.location.href = r.url; return; }
                const data = await r.json().catch(() => null);
                if (data?.errors) {
                    errorsBox.innerHTML = Object.values(data.errors).flat().join('<br>');
                    errorsBox.classList.remove('hidden');
                } else {
                    showToast('Failed to update rubric.');
                }
            })
            .catch(() => showToast('Failed to update rubric.'));
        });

        function openDeleterubricModal(rubricId) {
            document.getElementById('delete_rubric_id').value = rubricId;
            document.getElementById('delete_rubric_name').textContent = 'this rubric';
            openModal('delete_rubric_modal');
        }
        let currentEditRoomId = null;
// delete room

function openEditRoomModal(roomId) {
    fetch(`/admin/get-room/${roomId}`)
        .then(r => r.json())
        .then(data => {
            currentEditRoomId = data.id;
            document.getElementById('edit_room_title').textContent = data.room_name;
            renderRoomPanelists(data.panelists);
            openModal('edit_room_modal');
        })
        .catch(() => showToast('Failed to load room.', true));
}
function renderRoomPanelists(panelists) {
    const container = document.getElementById('edit_room_panelists_container');
    container.innerHTML = '';
    if (!panelists.length) {
        container.innerHTML = '<p class="text-sm text-[#5b6375] text-center py-2">No panelists yet.</p>';
        return;
    }
    panelists.forEach(p => {
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 p-2 bg-[#faf8f4] rounded border border-[#e2dacf]';
        row.innerHTML = `<span class="flex-1 text-sm">${p.name}</span>
            <button type="button" class="text-[#5b6375] hover:text-red-500 transition remove-panelist-btn"><i class="fas fa-times"></i></button>`;
        row.querySelector('.remove-panelist-btn').addEventListener('click', () => removePanelistFromRoom(p.id, row));
        container.appendChild(row);
    });
}

function removePanelistFromRoom(teacherId, row) {
    fetch(`/admin/evaluation-rooms/${currentEditRoomId}/panelists/${teacherId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => { if (data.success) row.remove(); })
    .catch(() => showToast('Failed to remove panelist.', true));
}

document.getElementById('edit_room_add_btn').addEventListener('click', () => {
    const select = document.getElementById('edit_room_teacher_select');
    const teacherId = select.value;
    if (!teacherId) return;

    fetch(`/admin/evaluation-rooms/${currentEditRoomId}/panelists`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ teacher_id: teacherId })
    })
    .then(async r => {
        const data = await r.json();
        if (data.error) {
            showToast(data.error, true);
            return;
        }
        const container = document.getElementById('edit_room_panelists_container');
        const emptyMsg = container.querySelector('p');
        if (emptyMsg) emptyMsg.remove();
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 p-2 bg-[#faf8f4] rounded border border-[#e2dacf]';
        row.innerHTML = `<span class="flex-1 text-sm">${data.panelist.name}</span>
            <button type="button" class="text-[#5b6375] hover:text-red-500 transition remove-panelist-btn"><i class="fas fa-times"></i></button>`;
        row.querySelector('.remove-panelist-btn').addEventListener('click', () => removePanelistFromRoom(data.panelist.id, row));
        container.appendChild(row);
    })
    .catch(() => showToast('Failed to add panelist.', true));
});

function deleteRoom(roomId) {
    if (!confirm('Delete this evaluation room?')) return;
    fetch(`/admin/evaluation-rooms/${roomId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => { if (data.success) window.location.reload(); })
    .catch(() => showToast('Failed to delete room.', true));
}
       // ---- CHANGE ADVISER MODAL ----
const groupsDataJs = @json($groupsData);

function resetAssignModal() {
    const sectionSel = document.getElementById('ca_section_select');
    const groupSel = document.getElementById('ca_group_select');
    const adviserSel = document.getElementById('ca_adviser_select');
    sectionSel.value = '';
    groupSel.innerHTML = '<option value="" disabled selected>Select a section first</option>';
    groupSel.disabled = true;
    adviserSel.innerHTML = adviserSel.innerHTML; // keep options
    adviserSel.value = '';
    adviserSel.disabled = true;
    document.getElementById('ca_current_adviser').value = '';
}

document.getElementById('ca_section_select').addEventListener('change', function() {
    const sectionId = this.value;
    const groupSelect = document.getElementById('ca_group_select');
    const filtered = groupsDataJs.filter(g => String(g.section_id) === String(sectionId));

    groupSelect.innerHTML = '';
    if (filtered.length === 0) {
        groupSelect.innerHTML = '<option value="" disabled selected>No groups in this section</option>';
        groupSelect.disabled = true;
    } else {
        groupSelect.innerHTML = '<option value="" disabled selected>Select group</option>';
        filtered.forEach(g => {
            const opt = document.createElement('option');
            opt.value = g.id;
            opt.textContent = g.name;
            groupSelect.appendChild(opt);
        });
        groupSelect.disabled = false;
    }

    document.getElementById('ca_current_adviser').value = '';
    const adviserSelect = document.getElementById('ca_adviser_select');
    adviserSelect.value = '';
    adviserSelect.disabled = true;
});

document.getElementById('ca_group_select').addEventListener('change', function() {
    const groupId = this.value;
    const group = groupsDataJs.find(g => String(g.id) === String(groupId));
    document.getElementById('ca_current_adviser').value = group?.assigned_teacher_name ?? 'Unassigned';

    const adviserSelect = document.getElementById('ca_adviser_select');
    adviserSelect.disabled = false;
    adviserSelect.value = '';
});

        // ---- MILESTONE FILTER ----
        const capstoneSelect = document.querySelector('select[name="capstone_id"]');
        const milestoneSelect = document.getElementById('milestone-select');
        function filterMilestones() {
            if (!capstoneSelect || !milestoneSelect) return;
            const selected = capstoneSelect.value;
            milestoneSelect.querySelectorAll('option').forEach(opt => {
                if (opt.value === '') return;
                opt.style.display = (!selected || opt.dataset.capstoneStageId == selected) ? '' : 'none';
            });
            const sel = milestoneSelect.options[milestoneSelect.selectedIndex];
            if (sel && sel.style.display === 'none') milestoneSelect.value = '';
        }
        if (capstoneSelect) capstoneSelect.addEventListener('change', filterMilestones);

        // ---- STAGE TABS ----
        const stageTabs = document.querySelectorAll('.stage-tab-btn');
        const milestoneRows = document.querySelectorAll('.milestone-row');
        function showStage(stage) {
            stageTabs.forEach(b => b.classList.toggle('active', b.dataset.stage === stage));
            milestoneRows.forEach(row => row.style.display = row.dataset.stage === stage ? 'flex' : 'none');
            document.querySelectorAll('.fill-animate').forEach(bar => {
                bar.style.width = '0%';
                requestAnimationFrame(() => { setTimeout(() => { bar.style.width = (bar.dataset.target || 0) + '%'; }, 50); });
            });
        }
        stageTabs.forEach(btn => btn.addEventListener('click', () => showStage(btn.dataset.stage)));
        showStage('1');

        // ---- STUDENT/TEACHER EDIT MODALS ----
        function openEditStudentModal(student) {
            document.getElementById('edit_original_student_id').value = student.user_id ?? '';
            document.getElementById('edit_student_id').value = student.user_id ?? '';
            document.getElementById('edit_first_name').value = student.student_first_name ?? '';
            document.getElementById('edit_middle_name').value = student.student_middle_name ?? '';
            document.getElementById('edit_last_name').value = student.student_last_name ?? '';
            document.getElementById('edit_email').value = student.student_email ?? '';
            document.getElementById('edit_contact').value = student.contact_number ?? '';
            document.getElementById('edit_section').value = student.section ?? '';
            openModal('student_edit_modal');
        }
        function openDeleteStudentModal(id, name) {
            document.getElementById('delete_student_id').value = id;
            document.getElementById('delete_student_name').textContent = name;
            openModal('delete_student_modal');
        }
        function openEditTeacherModal(teacher) {
            document.getElementById('edit_original_teacher_id').value = teacher.user_id ?? '';
            document.getElementById('edit_teacher_id').value = teacher.user_id ?? '';
            document.getElementById('edit_teacher_first_name').value = teacher.teacher_first_name ?? '';
            document.getElementById('edit_teacher_middle_name').value = teacher.teacher_middle_name ?? '';
            document.getElementById('edit_teacher_last_name').value = teacher.teacher_last_name ?? '';
            document.getElementById('edit_teacher_email').value = teacher.teacher_email ?? '';
            document.getElementById('edit_teacher_contact').value = teacher.contact_number ?? '';
            openModal('teacher_edit_modal');
        }
        function openDeleteTeacherModal(id, name) {
            document.getElementById('delete_teacher_id').value = id;
            document.getElementById('delete_teacher_name').textContent = name;
            openModal('delete_teacher_modal');
        }
        // ----- CREATE GROUP MODAL (ADMIN) -----
const sectionSelect = document.getElementById('sectionSelect');
const studentSelect = document.getElementById('studentSelect');
const addBtn = document.getElementById('addStudentsBtn');
const container = document.getElementById('selectedStudentsContainer');
const noMsg = document.getElementById('noStudentsMsg');
let idx = 0;

if (sectionSelect) {
    sectionSelect.addEventListener('change', function () {
        // Extract the ID and the Name from the selected option
        const selectedOption = this.options[this.selectedIndex];
        const sectionId = this.value;           // Used for validation (backend exists:sections,id)
        const sectionName = selectedOption ? selectedOption.dataset.name : ''; // Used for fetch

        studentSelect.innerHTML = '<option disabled>Loading...</option>';
        if (!sectionId) {
            studentSelect.innerHTML = '<option disabled>Select a section first</option>';
            return;
        }
        
        // Pass the NAME to the backend URL
        fetch(`/admin/get-students/${encodeURIComponent(sectionName)}`)
            .then(r => r.json())
            .then(students => {
                studentSelect.innerHTML = '';
                if (students.length === 0) {
                    studentSelect.innerHTML = '<option disabled>No students in this section</option>';
                    return;
                }
                students.forEach(s => {
                    const opt = document.createElement('option');
                    opt.value = s.user_id;
                    opt.textContent = `${s.student_first_name} ${s.student_last_name} (${s.user_id})`;
                    studentSelect.appendChild(opt);
                });
            })
            .catch(() => studentSelect.innerHTML = '<option disabled>Error loading students</option>');
    });
}

function addRow(user_id, name) {
    if (container.querySelector(`input[value="${user_id}"]`)) {
        showToast('Student already added.');
        return;
    }
    const row = document.createElement('div');
    row.className = 'flex items-center gap-2 p-2 bg-[#faf8f4] rounded border border-[#e2dacf]';
    row.innerHTML = `<input type="hidden" name="students[${idx}][user_id]" value="${user_id}">
        <span class="flex-1 text-sm">${name}</span>
        <select name="students[${idx}][role]" class="form-select w-32" required>
            <option disabled selected>Role</option>
            <option value="programmer">Programmer</option>
            <option value="designer">Designer</option>
            <option value="researcher">Researcher</option>
        </select>
        <button type="button" class="remove-student text-[#5b6375] hover:text-red-500 transition"><i class="fas fa-times"></i></button>`;
    container.appendChild(row);
    idx++;
    noMsg.style.display = 'none';
    row.querySelector('.remove-student').addEventListener('click', () => {
        row.remove();
        if (!container.children.length) noMsg.style.display = 'block';
    });
}

if (addBtn) {
    addBtn.addEventListener('click', () => {
        Array.from(studentSelect.selectedOptions).forEach(o => {
            if (o.value) addRow(o.value, o.textContent);
        });
        studentSelect.selectedIndex = -1;
    });
}
let editGroupIdx = 0;

function editGroupMemberRow(name, userId, role) {
    const container = document.getElementById('edit_group_members_container');
    if (container.querySelector(`input[value="${userId}"]`)) {
        showToast('Student already in team.');
        return;
    }
    const row = document.createElement('div');
    row.className = 'flex items-center gap-2 p-2 bg-[#faf8f4] rounded border border-[#e2dacf]';
    row.innerHTML = `<input type="hidden" name="students[${editGroupIdx}][user_id]" value="${userId}">
        <span class="flex-1 text-sm">${name} <span class="text-[#5b6375]">(${userId})</span></span>
        <select name="students[${editGroupIdx}][role]" class="form-select w-32" required>
            <option value="programmer" ${role === 'programmer' ? 'selected' : ''}>Programmer</option>
            <option value="designer" ${role === 'designer' ? 'selected' : ''}>Designer</option>
            <option value="researcher" ${role === 'researcher' ? 'selected' : ''}>Researcher</option>
        </select>
        <button type="button" class="edit-group-remove-student text-[#5b6375] hover:text-red-500 transition"><i class="fas fa-times"></i></button>`;
    container.appendChild(row);
    editGroupIdx++;
    row.querySelector('.edit-group-remove-student').addEventListener('click', () => row.remove());
}

function openEditGroupModal(groupId) {
    fetch(`/admin/get-group/${groupId}`)
        .then(r => r.json())
        .then(data => {
            document.getElementById('edit_group_id').value = data.id;
            document.getElementById('edit_group_name').value = data.group_name;
            document.getElementById('edit_group_capstone_title').value = data.capstone_title ?? '';
            document.getElementById('edit_group_adviser').value = data.adviser_id;
            document.getElementById('edit_group_form').action = `/admin/update-group/${data.id}`;
            document.getElementById('edit_group_errors').classList.add('hidden');
            document.getElementById('edit_group_section_display').value = data.section_name ?? '—';

            editGroupIdx = 0;
            const membersContainer = document.getElementById('edit_group_members_container');
            membersContainer.innerHTML = '';
            (data.members || []).forEach(m => editGroupMemberRow(m.name, m.user_id, m.role));

            const studentSelect = document.getElementById('edit_group_student_select');
            studentSelect.innerHTML = '<option disabled>Loading...</option>';
            if (data.section_name) {
                fetch(`/admin/get-students/${encodeURIComponent(data.section_name)}`)
                    .then(r => r.json())
                    .then(students => {
                        studentSelect.innerHTML = '';
                        if (!students.length) {
                            studentSelect.innerHTML = '<option disabled>No unassigned students in this section</option>';
                            return;
                        }
                        students.forEach(s => {
                            const opt = document.createElement('option');
                            opt.value = s.user_id;
                            opt.textContent = `${s.student_first_name} ${s.student_last_name} (${s.user_id})`;
                            studentSelect.appendChild(opt);
                        });
                    })
                    .catch(() => studentSelect.innerHTML = '<option disabled>Error loading students</option>');
            } else {
                studentSelect.innerHTML = '<option disabled>No section on file for this group</option>';
            }

            openModal('edit_group_modal');
        })
        .catch(() => showToast('Failed to load group.'));
}

document.getElementById('edit_group_add_students_btn').addEventListener('click', () => {
    const sel = document.getElementById('edit_group_student_select');
    Array.from(sel.selectedOptions).forEach(o => {
        if (o.value) editGroupMemberRow(o.textContent.split(' (')[0].trim(), o.value, 'programmer');
    });
    sel.selectedIndex = -1;
});

document.getElementById('edit_group_form').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const errorsBox = document.getElementById('edit_group_errors');
    errorsBox.classList.add('hidden');
    errorsBox.innerHTML = '';
    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        },
        body: new FormData(form)
    })
    .then(async r => {
        if (r.redirected) { window.location.href = r.url; return; }
        const data = await r.json().catch(() => null);
        if (data?.errors) {
            errorsBox.innerHTML = Object.values(data.errors).flat().join('<br>');
            errorsBox.classList.remove('hidden');
        } else if (data?.success) {
            window.location.reload();
        } else {
            showToast('Failed to update group.');
        }
    })
    .catch(() => showToast('Failed to update group.'));
});
function regenerateRoomCode(roomId, btn) {
    if (!confirm('Regenerate this classroom\'s join code? The old code will stop working.')) return;
    fetch(`/admin/evaluation-rooms/${roomId}/regenerate-code`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            'Accept': 'application/json'
        }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const badge = btn.previousElementSibling;
            badge.textContent = data.join_code;
            showToast('Code regenerated.');
        }
    })
    .catch(() => showToast('Failed to regenerate code.', true));
}
    </script>
 
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Capstone Tracker | Teacher Dashboard</title>
    <link rel="stylesheet" href="/css/dashboard.css">
    <script src="/js/app.js" defer></script>
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
        --shadow-md: 0 20px 35px -12px rgba(0, 0, 0, 0.08), 0 1px 2px rgba(0,0,0,0.02);
        --shadow-lg: 0 30px 45px -15px rgba(0, 0, 0, 0.10);
        --transition: all 0.3s cubic-bezier(0.2, 0.9, 0.4, 1.0);
    }
    * { font-family: 'DM Sans', sans-serif; }
    body {
        background: linear-gradient(145deg, var(--cream) 0%, #f2ede2 100%);
        color: var(--text);
        min-height: 100vh;
        -webkit-font-smoothing: antialiased;
    }
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: var(--cream-dark); border-radius: 8px; }
    ::-webkit-scrollbar-thumb { background: var(--gold); border-radius: 8px; }
    ::-webkit-scrollbar-thumb:hover { background: var(--gold-dark); }
    ::selection { background: var(--gold); color: var(--navy); }

    .transition-smooth { transition: var(--transition); }
    .section-card { animation: fadeInUp 0.35s cubic-bezier(0.22, 1, 0.36, 1) both; }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(14px); }
        to { opacity: 1; transform: translateY(0); }
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

    /* ── CARDS ── */
    .stat-card {
        background: var(--white);
        border-radius: 1.25rem;
        border: 1px solid rgba(214, 177, 92, 0.14);
        box-shadow: var(--shadow-md);
        transition: var(--transition);
        cursor: pointer;
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
    }
    .stat-card:hover .icon-circle {
        background: linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);
        box-shadow: 0 12px 22px -4px rgba(214, 177, 92, 0.35);
    }

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
    .content-card:hover .card-accent { opacity: 1; }

    .progress-bar-bg { background: #e8e3d7; border-radius: 999px; overflow: hidden; }
    .progress-fill { border-radius: 999px; transition: width 0.6s cubic-bezier(0.22, 1, 0.36, 1); }

    .badge {
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 11px;
        font-weight: 500;
        letter-spacing: 0.02em;
    }
    .badge-gold { background-color: rgba(214,177,92,0.15); color: #8b6914; border: 1px solid rgba(214,177,92,0.3); }
    .badge-navy { background-color: rgba(10,20,40,0.08); color: var(--navy); border: 1px solid rgba(10,20,40,0.15); }
    .badge-muted { background-color: #f0ece4; color: var(--text-muted); border: 1px solid var(--border); }
    .badge-green { background-color: #e6f4ea; color: #1e6b3a; border: 1px solid #b7dfc5; }
    .badge-amber { background-color: #fef7e6; color: #8a5d0b; border: 1px solid #f5d78a; }

    .btn-primary {
        background: var(--navy);
        color: var(--gold-light);
        border: none;
        padding: 0.6rem 1.3rem;
        border-radius: 2rem;
        font-weight: 500;
        font-size: 0.85rem;
        letter-spacing: 0.02em;
        transition: var(--transition);
        cursor: pointer;
        box-shadow: 0 2px 8px rgba(10,20,40,0.12);
        display: inline-flex;
        align-items: center;
        gap: 0.4rem;
    }
    .btn-primary:hover { background: var(--navy-hover); transform: translateY(-2px); box-shadow: 0 8px 20px rgba(10,20,40,0.2); color: #fff; }
    .btn-primary:active { transform: scale(0.97); }
    .btn-outline {
        background: transparent;
        border: 1.5px solid var(--gold);
        color: var(--navy);
        padding: 0.55rem 1.2rem;
        border-radius: 2rem;
        font-weight: 500;
        font-size: 0.85rem;
        transition: var(--transition);
        cursor: pointer;
    }
    .btn-outline:hover { background: var(--gold); color: var(--navy); box-shadow: 0 4px 14px rgba(214,177,92,0.3); }
    .btn-ghost {
        background: transparent;
        border: none;
        color: var(--text-muted);
        padding: 0.5rem 0.9rem;
        border-radius: 2rem;
        font-size: 0.8rem;
        cursor: pointer;
        transition: var(--transition);
    }
    .btn-ghost:hover { background: rgba(214,177,92,0.08); color: var(--navy); }

    table { border-collapse: separate; border-spacing: 0; }
    table th {
        font-size: 0.7rem;
        text-transform: uppercase;
        letter-spacing: 0.06em;
        color: var(--text-muted);
        font-weight: 600;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border);
    }
    table td {
        padding: 0.85rem 0;
        border-bottom: 1px solid rgba(226,218,207,0.5);
        font-size: 0.875rem;
        color: var(--text);
    }
    table tr:last-child td { border-bottom: none; }
    table tr:hover td { background: rgba(248,246,240,0.5); }

    /* ── MODALS ── */
    .modal-overlay {
        position: fixed;
        inset: 0;
        background: rgba(5,16,33,0.55);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 50;
        padding: 1rem;
    }
    .modal-overlay.active { display: flex; }
    .modal-box {
        background: var(--white);
        border-radius: 1.25rem;
        width: 100%;
        max-width: 30rem;
        max-height: 90vh;
        overflow-y: auto;
        padding: 1.75rem;
        animation: fadeInUp 0.25s ease-out both;
        box-shadow: 0 40px 60px -20px rgba(5,16,33,0.3);
        border: 1px solid rgba(214,177,92,0.2);
    }
    .modal-box.wide { max-width: 44rem; }
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
        margin-bottom: 0.3rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }
    .form-input, .form-select {
        width: 100%;
        background: #faf8f4;
        border: 1.5px solid var(--border);
        border-radius: 0.65rem;
        padding: 0.6rem 0.85rem;
        font-size: 0.875rem;
        color: var(--text);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
        font-family: 'DM Sans', sans-serif;
    }
    .form-input:focus, .form-select:focus { border-color: var(--gold); box-shadow: 0 0 0 3px var(--gold-glow); background: var(--white); }
    .form-input::placeholder { color: #c8c4bc; }
    .form-select option { background: var(--white); color: var(--text); }

    /* ── MOBILE BOTTOM NAV ── */
.mobile-bottom-nav {
    display: none !important;
}

@media (max-width: 768px) {
    .mobile-bottom-nav {
        display: flex !important;
        background: rgba(10,20,40,0.96);
        backdrop-filter: blur(12px);
        border-top: 1px solid rgba(214,177,92,0.3);
        box-shadow: 0 -4px 20px rgba(0,0,0,0.15);
        padding-bottom: env(safe-area-inset-bottom, 0px);
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }
    .mobile-bottom-nav::-webkit-scrollbar {
        display: none; /* Safari and Chrome */
    }
    .mobile-nav-link {
        transition: color 0.2s;
        flex-shrink: 0;
        min-width: 60px;
    }
    main {
        padding-bottom: calc(5rem + env(safe-area-inset-bottom, 0px));
        padding-left: 1rem;
        padding-right: 1rem;
    }
}
    @media (min-width: 769px) {
        /* Desktop: no mobile nav; already hidden by the base rule */
        .mobile-bottom-nav { display: none; }
    }

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
.toast-container.show { transform: translateX(0); opacity: 1; }
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
.toast-content i { color: var(--gold); font-size: 1.25rem; }
.toast-content .toast-message { font-size: 0.9rem; color: var(--text); font-weight: 500; flex: 1; }
.toast-content .toast-close { background: transparent; border: none; color: var(--text-muted); cursor: pointer; font-size: 1.2rem; transition: color 0.2s; }
.toast-content .toast-close:hover { color: var(--navy); }

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
    gap: 0.75rem;
    padding: 0.65rem 0;
    border-bottom: 1px solid rgba(226,218,207,0.6);
}
.info-row:last-child { border-bottom: none; }
.info-row .info-icon {
    width: 34px;
    height: 34px;
    border-radius: 10px;
    background: var(--gold-glow);
    color: var(--gold-dark);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    flex-shrink: 0;
}
.info-row .info-label {
    font-size: 0.68rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
    font-weight: 600;
}
.info-row .info-value {
    font-size: 0.875rem;
    color: var(--text);
    font-weight: 500;
}
.form-fieldset-title {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.72rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--gold-dark);
    font-weight: 700;
    margin-bottom: 0.9rem;
    padding-bottom: 0.5rem;
    border-bottom: 1px dashed var(--border);
}
.security-note {
    background: #faf8f4;
    border: 1px solid var(--border);
    border-radius: 0.85rem;
    padding: 1rem 1.1rem;
}
.security-note li { display: flex; gap: 0.5rem; align-items: flex-start; }
.security-note li i { color: var(--gold-dark); margin-top: 0.2rem; font-size: 0.75rem; }
#password_strength_bar { transition: width 0.3s ease, background 0.3s ease; }

/* ── Timeline table (matches the printed schedule sheet) ── */
#viewModal .timeline-table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.8rem;
}
#viewModal .timeline-table th,
#viewModal .timeline-table td {
    border: 1px solid var(--border);
    padding: 0.6rem 0.75rem;
    vertical-align: top;
    text-align: left;
}
#viewModal .timeline-table thead th {
    background: var(--gold);
    color: var(--navy);
    text-align: center;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.03em;
    font-size: 0.7rem;
}
#viewModal .timeline-table td:first-child {
    text-align: center;
    white-space: nowrap;
    font-weight: 600;
    color: var(--navy);
    width: 15%;
}
#viewModal .timeline-table td:nth-child(2) { width: 45%; }
#viewModal .timeline-table td:nth-child(3) { width: 40%; }

#viewModal .timeline-table .stage-divider td {
    background: var(--navy);
    color: var(--gold-light);
    text-align: center;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.06em;
    font-size: 0.75rem;
    padding: 0.5rem;
}

#viewModal .timeline-table tr.row-completed td { background: #f4faf6; }
#viewModal .timeline-table tr.row-next td { background: #fdf9ef; }

#viewModal .task-title {
    font-weight: 600;
    color: var(--text);
    display: block;
    margin-bottom: 0.25rem;
}
#viewModal .task-desc {
    font-size: 0.75rem;
    color: var(--text-muted);
    display: block;
}
#viewModal .task-status {
    display: inline-block;
    margin-top: 0.4rem;
    font-size: 0.68rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
    padding: 0.15rem 0.55rem;
    border-radius: 9999px;
}
#viewModal .task-status.completed { background: #e6f4ea; color: #1e6b3a; }
#viewModal .task-status.next { background: var(--gold); color: var(--navy); }
#viewModal .task-status.pending { background: #f0ece4; color: #8b8477; }

#viewModal .remark-line {
    display: block;
    margin-bottom: 0.3rem;
    color: var(--text);
}
#viewModal .remark-line .box {
    display: inline-block;
    width: 12px;
    height: 12px;
    border: 1.5px solid var(--text);
    margin-right: 0.4rem;
    text-align: center;
    line-height: 10px;
    font-size: 0.65rem;
    vertical-align: middle;
}
#viewModal .remark-line .box.checked {
    background: var(--navy);
    color: white;
    border-color: var(--navy);
}
#viewModal .remark-names {
    font-size: 0.75rem;
    color: var(--text-muted);
    margin-left: 1.1rem;
    display: block;
}
#viewModal .remark-empty {
    color: #b8b0a0;
    font-style: italic;
    font-size: 0.75rem;
}
#viewModal .remark-feedback {
    background: #faf8f4;
    border: 1px dashed var(--border);
    border-radius: 0.4rem;
    padding: 0.4rem 0.55rem;
    margin-top: 0.4rem;
    font-style: italic;
    font-size: 0.75rem;
    color: var(--text);
}
#viewModal .remark-summary { display:flex; flex-direction:column; gap:0.5rem; }

#viewModal .remark-status-badge {
    display:inline-flex; align-items:center; gap:0.4rem;
    padding:0.25rem 0.7rem; border-radius:9999px;
    font-size:0.7rem; font-weight:700; text-transform:uppercase; letter-spacing:0.03em;
    width:fit-content;
}
#viewModal .remark-status-badge.on-time { background:#e6f4ea; color:#1e6b3a; border:1px solid #b7dfc5; }
#viewModal .remark-status-badge.late    { background:#fbe9e7; color:#a12b2b; border:1px solid #f3c1ba; }
#viewModal .remark-status-badge.early   { background:#eaf1fb; color:#1e4e8b; border:1px solid #c1d6f3; }

#viewModal .remark-deduction {
    font-size:0.72rem; color:#a12b2b; font-weight:600;
    display:flex; align-items:center; gap:0.35rem;
}
#viewModal .remark-attendance {
    font-size:0.78rem; color:var(--text);
    display:flex; align-items:center; gap:0.4rem;
}
#viewModal .remark-attendance i { color:var(--gold-dark); }

#viewModal .absence-table {
    width:100%; border-collapse:collapse; margin-top:0.35rem; font-size:0.72rem;
    border:1px solid var(--border); border-radius:0.5rem; overflow:hidden;
}
#viewModal .absence-table thead th {
    background:#faf2df; color:var(--gold-dark); text-transform:uppercase; letter-spacing:0.03em;
    font-size:0.63rem; font-weight:700; padding:0.4rem 0.6rem; text-align:left;
    border-bottom:1px solid var(--border);
}
#viewModal .absence-table td {
    padding:0.4rem 0.6rem; border-bottom:1px solid rgba(226,218,207,0.5); color:var(--text);
}
#viewModal .absence-table tr:last-child td { border-bottom:none; }
#viewModal .absence-table td:first-child { width:1.75rem; color:var(--text-muted); font-weight:600; }
/* ── Stage toggle ── */
.stage-toggle {
    user-select: none;
}
.stage-toggle .stage-toggle-btn {
    background: none;
    border: none;
    padding: 0.25rem 0.5rem;
    border-radius: 0.375rem;
    transition: var(--transition);
    cursor: pointer;
}
.stage-toggle .stage-toggle-btn:hover {
    background: rgba(214, 177, 92, 0.15);
}
.stage-toggle .stage-toggle-btn .fa-chevron-down {
    transform: rotate(0deg);
}
.stage-toggle .stage-toggle-btn .fa-chevron-up {
    transform: rotate(180deg);
}
.stage-milestones {
    transition: all 0.25s cubic-bezier(0.22, 1, 0.36, 1);
    overflow: hidden;
}
.stage-milestones.collapsed {
    max-height: 0;
    opacity: 0;
    margin: 0;
    padding: 0;
    pointer-events: none;
}
.stage-milestones:not(.collapsed) {
    max-height: 2000px;
    opacity: 1;
}
#viewModal .milestone-card {
    flex-direction: column;
    align-items: stretch;
}
#viewModal .milestone-card .milestone-top {
    display: flex;
    align-items: center;
    justify-content: space-between;
}
#viewModal .remarks-block {
    margin-top: 0.75rem;
    padding-top: 0.75rem;
    border-top: 1px dashed var(--border);
    font-size: 0.78rem;
    color: var(--text-muted);
}
#viewModal .remarks-block .remark-row {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    margin-bottom: 0.35rem;
}
#viewModal .remarks-block .remark-row i {
    font-size: 0.75rem;
    width: 14px;
}
#viewModal .remarks-block .remark-ok { color: #1e6b3a; }
#viewModal .remarks-block .remark-bad { color: #a12b2b; }
#viewModal .remarks-block .remark-feedback {
    background: #faf8f4;
    border: 1px solid var(--border);
    border-radius: 0.5rem;
    padding: 0.5rem 0.65rem;
    margin-top: 0.35rem;
    font-style: italic;
    color: var(--text);
}
#viewModal .cert-row {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0.75rem 1rem;
    background: #faf8f4;
    border: 1px solid #e2dacf;
    border-radius: 0.65rem;
}
#viewModal .cert-row .cert-title {
    font-size: 0.85rem;
    font-weight: 600;
    color: #0a1428;
}
#viewModal .cert-row .cert-date {
    font-size: 0.7rem;
    color: #5b6375;
}
</style>
</head>
<body class="bg-[#f8f6f0] text-[#171e2c]">
    <div id="toast" class="toast-container">
    <div class="toast-content">
        <i class="fas fa-check-circle"></i>
        <span id="toastMessage" class="toast-message">Operation successful!</span>
        <button type="button" class="toast-close" onclick="hideToast()">&times;</button>
    </div>
</div>

<!-- ======================= SIDEBAR (DESKTOP) ======================= -->
<aside class="desktop-sidebar fixed left-0 top-0 h-full w-64 flex flex-col justify-between z-20">
    <div>
        <div class="p-6 flex items-center space-x-3 border-b border-[rgba(214,177,92,0.12)]">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center shadow-md flex-shrink-0"
                 style="background:linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);">
                <i class="fas fa-graduation-cap text-white text-lg"></i>
            </div>
            <div class="flex flex-col">
                <span class="font-bold text-sm text-white tracking-[0.5px]">Capstone Tracker</span>
                <small class="text-[10px] font-semibold tracking-[1px] uppercase mt-0.5" style="color: var(--gold-light); opacity: 0.85;">Teacher</small>
            </div>
        </div>
        <nav class="mt-6 px-4 space-y-1">
            <a href="#" data-section="dashboard" class="nav-link active-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[#d6b15c]">
                <i class="fas fa-th-large w-4"></i> <span>Dashboard</span>
            </a>
            <a href="#" data-section="assignedsections" class="nav-link active-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[#d6b15c]">
                <i class="fas fa-th-large w-4"></i> <span>Sections</span>
            </a>
            <a href="#" data-section="sections" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                <i class="fas fa-layer-group w-4"></i> <span>Assigned Groups</span>
            </a>
            <a href="#" data-section="profile" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                <i class="fa-regular fa-user w-4"></i> <span>Profile</span>
            </a>
            <a href="#" data-section="evaluate" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                <i class="fas fa-door-open w-4"></i> <span>Classrooms</span>
            </a>

        </nav>
    </div>
    <div class="p-4 border-t border-[rgba(214,177,92,0.15)]">
        <div class="flex items-center space-x-3 mb-4">
            <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-lg"
                 style="background:linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);">
                {{ strtoupper(substr($teacher->teacher_first_name ?? $user->name ?? 'T', 0, 2)) }}
            </div>
            <div>
                <p class="text-sm font-medium text-white">{{ $teacher->teacher_first_name ?? '' }} {{ $teacher->teacher_last_name ?? $user->name ?? 'Teacher' }}</p>
                <p class="text-[11px]" style="color:rgba(255,255,255,0.5);">{{ $teacher->teacher_email ?? $user->email ?? 'No email' }}</p>
            </div>
        </div>
        <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="flex items-center space-x-2 text-sm text-[rgba(255,255,255,0.5)] hover:text-[#f0e0b0] transition">
                <i class="fa-solid fa-arrow-right-from-bracket"></i> <span>Sign Out</span>
            </button>
        </form>
    </div>
</aside>

<!-- ======================= MOBILE BOTTOM NAV ======================= -->
<div class="mobile-bottom-nav fixed bottom-0 left-0 right-0 py-2 px-2 justify-around items-center z-30 flex md:hidden">
    <a href="#" data-section="dashboard" class="mobile-nav-link flex flex-col items-center text-[#d6b15c] text-xs py-1">
        <i class="fas fa-th-large text-lg"></i><span class="text-[10px] mt-1">Home</span>
    </a>
    <a href="#" data-section="assignedsections" class="mobile-nav-link flex flex-col items-center text-[#d6b15c] text-xs py-1">
        <i class="fas fa-th-large text-lg"></i><span class="text-[10px] mt-1">Sections</span>
    </a>
    <a href="#" data-section="sections" class="mobile-nav-link flex flex-col items-center text-[rgba(255,255,255,0.55)] text-xs py-1">
        <i class="fas fa-layer-group text-lg"></i><span class="text-[10px] mt-1">Groups</span>
    </a>
    <a href="#" data-section="evaluate" class="mobile-nav-link flex flex-col items-center text-[rgba(255,255,255,0.55)] text-xs py-1">
        <i class="fa-regular fa-pen-to-square text-lg"></i><span class="text-[10px] mt-1">Evaluate</span>
    </a>
    <a href="#" data-section="profile" class="mobile-nav-link flex flex-col items-center text-[rgba(255,255,255,0.55)] text-xs py-1">
        <i class="fa-regular fa-user text-lg"></i><span class="text-[10px] mt-1">Profile</span>
    </a>
    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="mobile-nav-link flex flex-col items-center text-red-400 hover:text-red-300 text-xs py-1">
        <i class="fas fa-sign-out-alt text-lg"></i><span class="text-[10px] mt-1">Sign Out</span>
    </a>
</div>

<!-- ======================= MAIN CONTENT ======================= -->
<main class="ml-0 md:ml-64 p-4 md:p-8 overflow-y-auto max-h-screen pb-20">

    <!-- ==================== DASHBOARD ==================== -->
    <div id="dashboard-section" class="section-container section-card max-w-7xl mx-auto">
        <div class="mb-8">
            <h1>Teacher Dashboard</h1>
            <div class="gold-accent-line"></div>
            <p class="text-[#5b6375] mt-2 text-sm mb-4">Welcome back, {{ $teacher->teacher_first_name ?? $user->name ?? 'Teacher' }}! You are handling {{ $totalGroups ?? 0 }} groups with {{ $totalStudents ?? 0 }} students.</p>

            <div class="space-y-3">
                @if($teacherSections->isNotEmpty())
                <div class="p-4 bg-[#faf8f4] border border-[#d6b15c] rounded-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white flex-shrink-0" style="background:linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);">
                            <i class="fas fa-columns"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-[#0a1428] uppercase tracking-wide">Assigned Section(s)</h4>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($teacherSections as $section)
                                <span class="badge badge-gold font-medium">
                                    <i class="fas fa-chalkboard mr-1"></i> {{ $section->section_name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="text-xs text-[#5b6375] italic">
                        You can manage these sections and create groups within them.
                    </div>
                </div>
                @else
                <div class="p-4 bg-[#faf8f4] border border-[#e2dacf] rounded-xl flex items-center space-x-3 shadow-sm">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-[#5b6375] bg-[#f0ece4] flex-shrink-0">
                        <i class="fas fa-ban"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[#5b6375] uppercase tracking-wide">No Assigned Sections</h4>
                        <p class="text-xs text-[#5b6375] mt-0.5">Contact the administrator to assign sections to your account.</p>
                    </div>
                </div>
                @endif

                @if($assignedRooms->isNotEmpty())
                <div class="p-4 bg-[#faf8f4] border border-[#d6b15c] rounded-xl flex flex-col md:flex-row justify-between items-start md:items-center gap-4 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center text-white flex-shrink-0" style="background:linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%); shadow: 0 4px 10px rgba(10,20,40,0.15);">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <div>
                            <h4 class="font-bold text-sm text-[#0a1428] uppercase tracking-wide">Assigned Evaluation Classrooms</h4>
                            <div class="flex flex-wrap gap-2 mt-1">
                                @foreach($assignedRooms as $room)
                                <span class="badge badge-gold font-medium">
                                    <i class="fas fa-location-dot mr-1"></i> {{ $room->room_name }}
                                </span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="text-xs text-[#5b6375] italic flex-shrink-0">
                        You can only see and evaluate the groups assigned to these rooms.
                    </div>
                </div>
                @else
                <div class="p-4 bg-[#faf8f4] border border-[#e2dacf] rounded-xl flex items-center space-x-3 shadow-sm">
                    <div class="w-10 h-10 rounded-lg flex items-center justify-center text-[#5b6375] bg-[#f0ece4] flex-shrink-0">
                        <i class="fas fa-door-closed"></i>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm text-[#5b6375] uppercase tracking-wide">No Assigned Classrooms</h4>
                        <p class="text-xs text-[#5b6375] mt-0.5">You have not been assigned to any evaluation classrooms/rooms yet.</p>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
            <div class="stat-card p-4 md:p-5 flex justify-between items-center" onclick="openDashboardDetailModal('groups')">
                <div><p class="text-[#5b6375] text-xs font-medium">Total Groups</p><p class="text-2xl md:text-3xl font-bold mt-1 text-[#0a1428]" style="font-family:'Cormorant Garamond',serif;">{{ $totalGroups ?? 0 }}</p></div>
                <div class="icon-circle text-[#d6b15c]"><i class="fas fa-layer-group text-lg"></i></div>
            </div>
            <div class="stat-card p-4 md:p-5 flex justify-between items-center" onclick="openDashboardDetailModal('students')">
                <div><p class="text-[#5b6375] text-xs font-medium">Total Students</p><p class="text-2xl md:text-3xl font-bold mt-1 text-[#0a1428]" style="font-family:'Cormorant Garamond',serif;">{{ $totalStudents ?? 0 }}</p></div>
                <div class="icon-circle text-[#d6b15c]"><i class="fa-regular fa-user text-lg"></i></div>
            </div>
            <div class="stat-card p-4 md:p-5 flex justify-between items-center" onclick="openDashboardDetailModal('evaluations')">
                <div><p class="text-[#5b6375] text-xs font-medium">Evaluations</p><p class="text-2xl md:text-3xl font-bold mt-1 text-[#0a1428]" style="font-family:'Cormorant Garamond',serif;">{{ $totalEvaluations ?? 0 }}</p></div>
                <div class="icon-circle text-[#d6b15c]"><i class="fa-regular fa-circle-check text-lg"></i></div>
            </div>
            <div class="stat-card p-4 md:p-5 flex justify-between items-center" onclick="openDashboardDetailModal('sections')">
                <div><p class="text-[#5b6375] text-xs font-medium">My Sections</p><p class="text-2xl md:text-3xl font-bold mt-1 text-[#0a1428]" style="font-family:'Cormorant Garamond',serif;">{{ count($teacherSections ?? []) }}</p></div>
                <div class="icon-circle text-[#d6b15c]"><i class="fas fa-columns text-lg"></i></div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="content-card">
                <div class="card-accent"></div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6"><h3>Group Progress</h3><i class="fas fa-chart-simple text-[#b8b0a0]"></i></div>
                    <div class="space-y-4">
                        @forelse($groupProgress ?? [] as $gp)
                            <div class="p-3 bg-[#faf8f4] rounded-lg border border-[#e2dacf]">
                                <div class="flex justify-between items-start mb-2">
                                    <div><p class="font-semibold text-sm text-[#0a1428]">{{ $gp->group_name }}</p><p class="text-xs text-[#5b6375]">{{ $gp->capstone_title }}</p></div>
                                    <span class="text-sm font-bold text-[#b88d3a]">{{ $gp->progress }}%</span>
                                </div>
                                <div class="progress-bar-bg h-2.5 w-full"><div class="progress-fill h-full" style="width:{{ $gp->progress }}%; background: var(--gold);"></div></div>
                                <p class="text-xs text-[#5b6375] mt-1">{{ $gp->completed }} / {{ $gp->total }} milestones</p>
                            </div>
                        @empty
                            <div class="text-center py-8 text-[#5b6375]"><i class="fa-regular fa-folder-open text-3xl mb-2"></i><p>No groups assigned yet</p></div>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="content-card">
                <div class="card-accent"></div>
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6"><h3>Recent Evaluations</h3><i class="fa-regular fa-file-lines text-[#b8b0a0]"></i></div>
                    <div class="space-y-3 max-h-80 overflow-y-auto">
                        @forelse($evaluations ?? [] as $eval)
                            <div class="p-3 bg-[#faf8f4] rounded-lg border border-[#e2dacf] hover:border-[#d6b15c] transition">
                                <div class="flex justify-between items-start">
                                    <div><p class="font-semibold text-sm text-[#0a1428]">{{ $eval->group->group_name ?? 'Unknown' }}</p><p class="text-xs text-[#5b6375]">{{ $eval->milestone->milestone_title ?? '' }}</p></div>
                                    <div class="text-right"><span class="text-sm font-bold text-[#1e6b3a]">{{ $eval->score }}/{{ $eval->max_score }}</span><p class="text-[10px] text-[#5b6375]">{{ \Carbon\Carbon::parse($eval->evaluation_date)->format('M d, Y') }}</p></div>
                                </div>
                                <p class="text-xs text-[#5b6375] mt-1 truncate">{{ Str::limit($eval->feedback, 60) }}</p>
                            </div>
                        @empty
                            <div class="text-center py-8 text-[#5b6375]"><i class="fa-regular fa-pen-to-square text-3xl mb-2"></i><p>No evaluations yet</p></div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <div class="content-card">
            <div class="card-accent"></div>
            <div class="p-6">
                <div class="flex justify-between items-center mb-5"><h3>All Assigned Groups</h3><span class="text-xs text-[#5b6375]">{{ $totalGroups ?? 0 }} groups</span></div>
                <div class="space-y-3">
                    @forelse($groups ?? [] as $group)
                        @php 
                            $completed = $group->groupMilestones->where('status','completed')->count(); 
                            $total = $milestones->count()??1; 
                            $progress = round(($completed/max($total,1))*100); 
                            $section = $group->students->first()->section ?? 'No Section';
                        @endphp
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 bg-[#faf8f4] border border-[#e2dacf] rounded-xl text-sm group-item transition hover:shadow-sm" data-search="{{ strtolower($group->group_name . ' ' . $group->capstone_title . ' ' . $section) }}">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span class="font-bold text-[#0a1428] text-base">{{ $group->group_name }}</span>
                                    <span class="badge badge-navy text-[9px]">{{ $section }}</span>
                                    <span class="text-xs text-[#5b6375]">• {{ $group->students->count()??0 }} members</span>
                                </div>
                                <span class="text-xs text-[#3d4450]">{{ $group->capstone_title }}</span>
                                <div class="flex items-center gap-2 mt-1">
                                    <span class="text-xs text-[#5b6375]">Progress:</span>
                                    <div class="w-24 progress-bar-bg h-1.5"><div class="progress-fill h-1.5" style="width:{{ $progress }}%; background:var(--gold);"></div></div>
                                    <span class="text-xs font-semibold">{{ $progress }}%</span>
                                </div>
                            </div>
                            <div class="mt-3 sm:mt-0 flex gap-2">
                                <button onclick="window.openEvaluationModal({{ $group->id }})" class="btn-primary text-xs px-4 py-2 rounded-lg flex items-center gap-1.5 focus:outline-none transition shadow-sm evaluate-btn" data-group="{{ $group->id }}">
                                    <i class="fa-regular fa-pen-to-square"></i> Evaluate Group
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-[#5b6375]"><i class="fa-regular fa-folder-open text-2xl mb-2 block"></i>No groups assigned</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
 <!-- ==================== ASSIGNED SECTIONS ==================== -->
    <div id="assignedsections-section" class="section-container hidden section-card max-w-7xl mx-auto">
        <div class="mb-8 flex flex-wrap justify-between items-center gap-4">
            <div><h1>Assigned Section</h1><div class="gold-accent-line"></div><p class="text-[#5b6375] mt-2 text-sm">Manage handled sections</p></div>
        </div>
        <div class="grid grid-cols-1 gap-6">
            @forelse($teacherSections ?? [] as $section)
                @php 
                    $groupsInSection = \App\Models\Group::where('section_id', $section->id)->where('is_archived', false)->with(['students', 'groupMilestones', 'room'])->get();
                    $sectionStudents = \App\Models\Student::where('section', $section->section_name)->with(['user', 'groups'])->get();
                @endphp
                <div class="content-card">
                    <div class="card-accent"></div>
                    <div class="p-6">
                        <div class="flex flex-wrap justify-between items-center mb-4 gap-2 border-b border-[#faf8f4] pb-2">
                            <div class="flex items-center gap-2.5">
                                <button type="button" onclick="toggleSectionCollapse('{{ $section->id }}')" class="text-[#b8b0a0] hover:text-[#0a1428] transition focus:outline-none w-6 h-6 flex items-center justify-center rounded-full hover:bg-[#faf8f4]">
                                    <i class="fas fa-chevron-down transform transition-transform duration-200 text-xs" id="collapse_icon_{{ $section->id }}"></i>
                                </button>
                                <h3 class="text-lg font-bold text-[#0a1428] cursor-pointer select-none" onclick="toggleSectionCollapse('{{ $section->id }}')">{{ $section->section_name }}</h3>
                            </div>
                        </div>

                        <!-- Collapsible Content container -->
                        <div id="section_collapsible_content_{{ $section->id }}" class="space-y-4">
                            <div class="flex flex-wrap justify-between items-center mb-2 gap-2">
                                <div class="flex items-center gap-2">
                                    <div class="flex bg-[#faf8f4] border border-[#e2dacf] rounded-lg p-0.5 text-[10px]">
                                        <button type="button" onclick="toggleSectionView('as', '{{ $section->id }}', 'groups')" id="as_toggle_btn_{{ $section->id }}_groups" class="px-2.5 py-1 rounded-md font-semibold text-[#b88d3a] bg-white shadow-sm transition-all focus:outline-none">
                                            <i class="fas fa-layer-group mr-1"></i> Groups ({{ $groupsInSection->count() }})
                                        </button>
                                        <button type="button" onclick="toggleSectionView('as', '{{ $section->id }}', 'students')" id="as_toggle_btn_{{ $section->id }}_students" class="px-2.5 py-1 rounded-md font-semibold text-[#5b6375] hover:text-[#0a1428] transition-all focus:outline-none">
                                            <i class="fas fa-user-graduate mr-1"></i> Students ({{ $sectionStudents->count() }})
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Groups View -->
                            <div id="as_section_groups_view_{{ $section->id }}" class="space-y-3">
                                @forelse($groupsInSection as $g)
                                    <div class="p-3 bg-[#faf8f4] rounded-lg border border-[#e2dacf]">
                                        <div class="flex justify-between items-center">
                                            <div><p class="font-semibold text-sm text-[#0a1428]">{{ $g->group_name }}</p><p class="text-xs text-[#5b6375]">{{ Str::limit($g->capstone_title,25) }}</p></div>
                                            <div class="text-right flex flex-col items-end gap-1">
                                                <span class="text-xs text-[#5b6375]">{{ $g->students->count()??0 }} students</span>
                                                <div class="flex gap-2">
                                                    <button onclick="openViewModal({{ $g->id }})" class="text-[#b88d3a] hover:text-[#8b6914] text-xs font-semibold transition">
                                                        <i class="fas fa-star mr-1"></i>Rubric Scores
                                                    </button>
                                                    <button onclick="openViewModal({{ $g->id }})" class="text-[#5b6375] hover:text-[#0a1428] text-xs font-medium transition">
                                                        <i class="fa-regular fa-eye mr-1"></i>{{ $g->adviser_id == $teacher->id ? 'Check' : 'Show' }}
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-6 text-[#5b6375] text-sm bg-[#faf8f4]/50 border border-[#e2dacf] border-dashed rounded-lg"><i class="fa-regular fa-folder-open mr-1.5"></i> No groups in this section</div>
                                @endforelse
                            </div>

                            <!-- Students View (Table of Students) -->
                            <div id="as_section_students_view_{{ $section->id }}" class="hidden overflow-x-auto border border-[#e2dacf] rounded-lg bg-white">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="bg-[#faf8f4] text-[#0a1428] font-semibold text-[11px] border-b border-[#e2dacf]">
                                            <th class="p-2 pl-3">Student Name</th>
                                            <th class="p-2">User ID</th>
                                            <th class="p-2">Group</th>
                                            <th class="p-2">Contact</th>
                                            <th class="p-2 pr-3 text-right">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-[#faf1e0] text-[11px]">
                                        @forelse($sectionStudents as $st)
                                            @php
                                                $stGroup = $groupsInSection->first(fn($g) => $g->students->contains($st->id));
                                            @endphp
                                            <tr class="hover:bg-[#faf8f4]/30">
                                                <td class="p-2 pl-3 font-semibold text-[#0a1428]">{{ $st->student_first_name }} {{ $st->student_last_name }}</td>
                                                <td class="p-2 text-[#5b6375] font-mono">{{ $st->user->user_id ?? 'N/A' }}</td>
                                                <td class="p-2">
                                                    @if($stGroup)
                                                        <span class="badge badge-navy text-[9px] px-1.5 py-0.5"><i class="fas fa-layer-group text-[8px] mr-0.5"></i> {{ $stGroup->group_name }}</span>
                                                    @else
                                                        <span class="badge badge-muted text-[9px] px-1.5 py-0.5">Unassigned</span>
                                                    @endif
                                                </td>
                                                <td class="p-2 text-[#5b6375]">
                                                    <div class="flex flex-col text-[9px]">
                                                        <span>{{ $st->student_email }}</span>
                                                        @if($st->contact_number)
                                                            <span class="text-gray-400 mt-0.5">{{ $st->contact_number }}</span>
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="p-2 pr-3 text-right">
                                                    @if($stGroup)
                                                        <button onclick="openViewModal({{ $stGroup->id }})" class="text-[#b88d3a] hover:text-[#8b6914] text-[11px] font-semibold transition">
                                                            <i class="fas fa-star mr-1"></i>Rubric Scores
                                                        </button>
                                                    @else
                                                        <span class="text-gray-400 italic text-[10px]">No Group</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr><td colspan="5" class="p-6 text-center text-[#5b6375]"><i class="fa-regular fa-folder-open text-xl mb-1 block"></i> No students registered in this section.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            @empty
                <div class="content-card col-span-2 p-8 text-center"><i class="fa-regular fa-folder-open text-4xl text-[#b8b0a0] mb-3"></i><h3>No Section Assigned</h3><p class="text-[#5b6375] text-sm">Contact the admin to get sections assigned.</p></div>
            @endforelse
        </div>
    </div>
    <!-- ==================== ASSIGNED GROUPS ==================== -->
    <div id="sections-section" class="section-container hidden section-card max-w-7xl mx-auto">
        <div class="mb-8 flex flex-wrap justify-between items-center gap-4">
            <div><h1>Assigned Groups</h1><div class="gold-accent-line"></div><p class="text-[#5b6375] mt-2 text-sm">Manage handled sections</p></div>
            {{--    <button onclick="openCreateGroupModal()" class="btn-primary"><i class="fas fa-plus mr-1"></i> Create Group</button> --}}
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @forelse($sectionsWithGroups ?? [] as $section)
                @php $groupsInSection = $groups->where('section_id', $section->id);
                @endphp
                <div class="content-card">
                    <div class="card-accent"></div>
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3>{{ $section->section_name }}</h3>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-[#5b6375]">{{ $groupsInSection->count() }} groups</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            @forelse($groupsInSection as $g)
                                <div class="p-3 bg-[#faf8f4] rounded-lg border border-[#e2dacf]">
                                    <div class="flex justify-between items-center">
                                        <div><p class="font-semibold text-sm text-[#0a1428]">{{ $g->group_name }}</p><p class="text-xs text-[#5b6375]">{{ Str::limit($g->capstone_title,25) }}</p></div>
                                        <div class="text-right flex flex-col items-end gap-1">
                                        <span class="text-xs text-[#5b6375]">{{ $g->students->count()??0 }} students</span>
                                        <div class="flex gap-2">
                                            <button onclick="openViewModal({{ $g->id }})" class="text-[#5b6375] hover:text-[#0a1428] text-xs font-medium transition">
                                                <i class="fa-regular fa-eye mr-1"></i>{{ $g->adviser_id == $teacher->id ? 'Check' : 'Show' }}
                                            </button>
                                        </div>
                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-4 text-[#5b6375] text-sm"><i class="fa-regular fa-folder-open mr-1"></i> No groups in this section</div>
                                        @endforelse
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="content-card col-span-2 p-8 text-center"><i class="fa-regular fa-folder-open text-4xl text-[#b8b0a0] mb-3"></i><h3>No Groups Assigned</h3><p class="text-[#5b6375] text-sm">Contact the admin to get sections assigned.</p></div>
                        @endforelse
                    </div>
    </div>
<!-- ==================== CLASSROOMS ==================== -->
<div id="evaluate-section" class="section-container hidden section-card max-w-7xl mx-auto">
    <div class="mb-8">
        <h1>Classrooms</h1>
        <div class="gold-accent-line"></div>
        <p class="text-[#5b6375] mt-2 text-sm">Your assigned evaluation classroom, and other classrooms you can join with a code from the admin.</p>
    </div>

    @php
        $assignedRoomIds = $assignedRooms->pluck('id')->toArray();
        $unassignedRooms = $allRooms->reject(fn($r) => in_array($r->id, $assignedRoomIds));
    @endphp

    @if($assignedRooms->isNotEmpty())
        <h3 class="text-lg font-bold text-[#0a1428] mb-4 flex items-center gap-2">
            <i class="fas fa-check-circle text-[#1e6b3a]"></i> Your Classrooms
        </h3>
        <div class="space-y-4 mb-8">
            @foreach($assignedRooms as $room)
                <div class="content-card w-full">
                    <div class="card-accent"></div>
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background:linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);">
                                    <i class="fas fa-door-open text-[#d6b15c]"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-[#0a1428]">{{ $room->room_name }}</h3>
                                    <p class="text-xs text-[#5b6375]">
                                        {{ $room->groups->count() }} group(s) assigned
                                    </p>
                                    <div class="flex flex-wrap gap-1 mt-1">
                                        @foreach($room->panelists as $p)
                                            <span class="badge badge-navy">{{ $p->teacher_first_name }} {{ $p->teacher_last_name }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <span class="badge badge-green"><i class="fas fa-check-circle mr-1"></i> Joined</span>
                        </div>

                        <div class="mt-4 pt-4 border-t border-[#e2dacf]">
                            <div class="flex justify-between items-center mb-2">
                                <p class="text-xs font-semibold text-[#5b6375] uppercase tracking-wide">Groups in this room</p>
                                <input type="text" class="room-group-filter form-input text-xs py-1 px-2 w-48" 
                                       placeholder="Search by group, title, or section…" data-room-id="{{ $room->id }}">
                            </div>
                            <div class="flex flex-col gap-3 w-full room-group-list" data-room-id="{{ $room->id }}">
                                @forelse($room->groups as $g)
                                    @php
                                        $section = $g->students->first()->section ?? 'No Section';
                                        $searchData = strtolower($g->group_name . ' ' . ($g->capstone_title ?? '') . ' ' . $section);
                                    @endphp
                                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center p-4 bg-[#faf8f4] border border-[#e2dacf] rounded-xl text-sm group-item transition hover:shadow-sm w-full" data-search="{{ $searchData }}">
                                        <div class="flex flex-col gap-1">
                                            <div class="flex items-center gap-2 flex-wrap">
                                                <span class="font-bold text-[#0a1428] text-base">{{ $g->group_name }}</span>
                                                <span class="badge badge-navy text-[9px]">{{ $section }}</span>
                                                <span class="text-xs text-[#5b6375]">• {{ $g->students->count() ?? 0 }} members</span>
                                            </div>
                                            <span class="text-xs text-[#3d4450]">{{ $g->capstone_title ?? 'No title' }}</span>
                                        </div>
                                        <div class="mt-3 sm:mt-0 flex gap-2">
                                            <button onclick="window.openEvaluationModal({{ $g->id }})" class="btn-primary text-xs px-4 py-2 rounded-lg flex items-center gap-1.5 focus:outline-none transition shadow-sm evaluate-btn" data-group="{{ $g->id }}">
                                                <i class="fa-regular fa-pen-to-square"></i> Evaluate Group
                                            </button>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-xs text-[#5b6375] col-span-full text-center py-2">No groups assigned to this room yet.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($unassignedRooms->isNotEmpty())
        <h3 class="text-lg font-bold text-[#0a1428] mb-4 flex items-center gap-2">
            <i class="fas fa-lock text-[#8a5d0b]"></i> Available Classrooms
        </h3>
        <div class="space-y-4">
            @foreach($unassignedRooms as $room)
                <div class="content-card w-full opacity-80 hover:opacity-100 transition">
                    <div class="card-accent"></div>
                    <div class="p-6">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0"
                                     style="background:#f0ece4;">
                                    <i class="fas fa-door-closed text-[#b8b0a0]"></i>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-[#0a1428]">{{ $room->room_name }}</h3>
                                    <p class="text-xs text-[#5b6375]">
                                        {{ $room->groups->count() }} group(s) assigned
                                    </p>
                                    @if($room->panelists->isNotEmpty())
                                        <p class="text-[11px] text-[#5b6375] mt-1">
                                            <strong>Panelists:</strong> 
                                            {{ $room->panelists->map(fn($p) => $p->teacher_first_name . ' ' . $p->teacher_last_name)->join(', ') }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                            @php
                                $isTeacherAlreadyAssigned = $assignedRooms->isNotEmpty();
                                $roomIsFull = $room->panelists->isNotEmpty();
                                $isDisabled = $isTeacherAlreadyAssigned || $roomIsFull;
                            @endphp
                            @if($isDisabled)
                                <div class="text-right text-xs font-semibold uppercase tracking-wider text-[#5b6375] bg-[#f5f1e8] border border-[#e2dacf] px-4 py-2.5 rounded-lg flex items-center gap-1.5">
                                    <i class="fas fa-ban text-red-500"></i>
                                    @if($roomIsFull)
                                        Classroom Full
                                    @else
                                        Already Assigned
                                    @endif
                                </div>
                            @else
                                <form class="join-room-form flex gap-2" data-room-id="{{ $room->id }}" action="{{ route('teacher.join_room') }}" method="POST">
                                    @csrf
                                    <input type="text" name="join_code" class="form-input join-code-input flex-1 text-sm" placeholder="6-char code" maxlength="6" required style="text-transform:uppercase; min-width:120px;">
                                    <button type="submit" class="btn-primary text-xs px-4 whitespace-nowrap">
                                        <i class="fas fa-key mr-1"></i> Join
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    @if($allRooms->isEmpty())
        <div class="content-card col-span-3 p-8 text-center">
            <i class="fa-regular fa-folder-open text-4xl text-[#b8b0a0] mb-3"></i>
            <h3>No Classrooms Created Yet</h3>
            <p class="text-[#5b6375] text-sm">Ask the admin to create evaluation classrooms.</p>
        </div>
    @endif
</div>
    <!-- ==================== PROFILE ==================== -->
<div id="profile-section" class="section-container hidden section-card max-w-7xl mx-auto">
    <div class="mb-8"><h1>Profile</h1><div class="gold-accent-line"></div><p class="text-[#5b6375] mt-2 text-sm">Manage your personal information and contact details</p></div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
        <!-- Profile summary / identity card -->
        <div class="content-card lg:col-span-1 overflow-hidden">
            <div class="profile-banner"></div>
            <div class="p-6 -mt-12 flex flex-col items-center text-center">
                <div class="profile-avatar-ring">
                    <div class="profile-avatar-inner">
                        {{ strtoupper(substr($teacher->teacher_first_name ?? $user->name ?? 'T', 0, 2)) }}
                    </div>
                </div>
                <h2 class="mt-4">{{ $teacher->teacher_first_name ?? '' }} {{ $teacher->teacher_last_name ?? '' }}</h2>
                <p class="text-[#5b6375] text-xs font-medium tracking-wide uppercase mt-0.5">Capstone Adviser</p>
                <p class="text-[#5b6375] text-sm mt-1">ID: {{ $teacher->user_id ?? $user->user_id ?? '—' }}</p>
                
                <div class="mt-4 flex flex-wrap gap-2 justify-center">
                    <span class="badge badge-gold"><i class="fas fa-layer-group mr-1"></i>{{ $totalGroups ?? 0 }} Groups</span>
                    <span class="badge badge-navy"><i class="fa-regular fa-user mr-1"></i>{{ $totalStudents ?? 0 }} Students</span>
                </div>

                <div class="mt-5 w-full pt-5 border-t border-[#e2dacf] space-y-1">
                    <div class="info-row">
                        <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                        <div class="text-left">
                            <p class="info-label">Contact</p>
                            <p class="info-value">{{ $teacher->contact_number ?? 'Not provided' }}</p>
                        </div>
                    </div>
                    <div class="info-row">
                        <div class="info-icon"><i class="fa-regular fa-envelope"></i></div>
                        <div class="text-left">
                            <p class="info-label">Email</p>
                            <p class="info-value break-all">{{ $teacher->teacher_email ?? $user->email ?? 'Not provided' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit profile form -->
        <div class="content-card lg:col-span-2">
            <div class="card-accent"></div>
            <div class="p-6">
                <h3 class="mb-5 flex items-center gap-2"><i class="fa-regular fa-pen-to-square text-[#d6b15c]"></i> Edit Profile</h3>
                <form action="{{ route('teacher.profile_update') }}" method="POST" class="space-y-6">
                    @csrf
                    <div>
                        <p class="form-fieldset-title"><i class="fa-regular fa-user"></i> Personal Information</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="form-label">First Name</label><input type="text" name="teacher_first_name" value="{{ old('teacher_first_name', $teacher->teacher_first_name ?? '') }}" class="form-input"></div>
                            <div><label class="form-label">Last Name</label><input type="text" name="teacher_last_name" value="{{ old('teacher_last_name', $teacher->teacher_last_name ?? '') }}" class="form-input"></div>
                            <div><label class="form-label">Middle Name</label><input type="text" name="teacher_middle_name" value="{{ old('teacher_middle_name', $teacher->teacher_middle_name ?? '') }}" class="form-input"></div>
                            <div><label class="form-label">Teacher ID</label><input type="text" disabled value="{{ $teacher->user_id ?? $user->user_id ?? '' }}" class="form-input opacity-70 cursor-not-allowed"></div>
                        </div>
                    </div>

                    <div>
                        <p class="form-fieldset-title"><i class="fa-regular fa-address-card"></i> Contact Details</p>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div><label class="form-label">Contact Number</label><input type="text" name="contact_number" value="{{ old('contact_number', $teacher->contact_number ?? '') }}" class="form-input" placeholder="e.g. 09XX XXX XXXX"></div>
                            <div><label class="form-label">Email</label><input type="email" name="teacher_email" value="{{ old('teacher_email', $teacher->teacher_email ?? $user->email ?? '') }}" class="form-input"></div>
                        </div>
                    </div>

                    <div class="flex justify-end pt-2 border-t border-[#e2dacf]">
                        <button type="submit" class="btn-primary"><i class="fa-regular fa-floppy-disk mr-2"></i>Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Security -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="content-card lg:col-span-2">
            <div class="card-accent"></div>
            <div class="p-6">
                <h3 class="mb-5 flex items-center gap-2"><i class="fa-solid fa-key text-[#d6b15c]"></i> Change Password</h3>
                <form action="{{ route('teacher.update_password') }}" method="POST" class="space-y-4" id="passwordForm">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="md:col-span-2">
                            <label class="form-label">Username</label>
                            <input type="text" value="{{ $user->name ?? '' }}" class="form-input bg-[#e8e3d7] cursor-not-allowed" disabled>
                        </div>
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

        <!-- Security tips -->
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

<!-- ==================== MODALS ==================== -->

<!-- VIEW PROGRESS MODAL -->
<div id="viewModal" class="modal-overlay">
    <div class="modal-box wide">
        <div class="modal-accent"></div>
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.6rem; font-weight:600; color:var(--navy);" id="view_modal_title">Group Progress</h2>
            <button type="button" onclick="closeModal('viewModal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-2xl leading-none">&times;</button>
        </div>

        <div id="view_modal_content">
            <!-- Loading -->
            <div id="view_loading" class="flex flex-col items-center justify-center py-12">
                <div class="spinner"></div>
                <p class="mt-4 text-sm text-[#5b6375] font-medium">Loading group progress…</p>
            </div>

            <!-- Dynamic content -->
            <div id="view_data" class="hidden">
                <div class="w-full">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-[#0a1428]">Capstone Progress</h3>
                        <span class="text-xs font-medium text-[#5b6375] bg-[#f0ece4] px-3 py-1 rounded-full">
                            <span id="view_progress_label">0</span>% complete
                        </span>
                    </div>
                    <div class="progress-bar-bg h-2.5 w-full mb-6">
                        <div id="view_overall_progress" class="progress-fill h-full" style="width:0%;"></div>
                    </div>
                    <div id="view_milestones_container" class="overflow-x-auto">
                    <table class="timeline-table">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Tasks / Requirements</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody id="view_milestones_tbody"></tbody>
                    </table>
                </div>

                <div id="view_certificates_container" class="mt-6 pt-4 border-t border-[#e2dacf] hidden">
                    <h4 class="text-sm font-bold text-[#0a1428] mb-3 flex items-center gap-2">
                        <i class="fas fa-award text-[#d6b15c]"></i> Earned Certificates
                    </h4>
                    <div id="view_certificates_list" class="space-y-2"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- EVALUATION MODAL -->
<div id="evaluationModal" class="modal-overlay">
    <div class="modal-box wide">
        <div class="modal-accent"></div>
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Evaluate Group</h2>
            <button type="button" onclick="closeModal('evaluationModal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
        </div>
        <form  action="/teacher/submit-evaluation" method="POST" class="space-y-4">
            @csrf
            <input type="hidden" name="form_type" value="evaluation">
    <div id="evalErrors" class="hidden mb-3 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"></div>
            <input type="hidden" name="group_id" id="eval_group_id">
            <input type="hidden" name="milestone_id" id="eval_milestone_id">
            <input type="hidden" name="score" id="eval_total_score">
            <input type="hidden" name="max_score" id="eval_max_score">
            <div class="grid grid-cols-2 gap-4">
                <div><label class="form-label">Group</label><input type="text" id="eval_group_name" class="form-input" readonly></div>
                <div>
                    <label class="form-label">Milestone</label>
                    <select id="milestone_select" class="form-select" required>
                        <option value="">-- Select Milestone --</option>
                        @foreach($milestones as $milestone)
                            <option value="{{ $milestone->id }}">{{ $milestone->milestone_title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <!-- Attendance -->
            <div>
                <label class="form-label">Attendance</label>
                <div class="flex gap-4 mt-1">
                    <label class="flex items-center gap-2 text-xs cursor-pointer">
                        <input type="radio" name="attendance" value="present" checked class="form-radio text-[#d6b15c] focus:ring-[#d6b15c]">
                        <span>All members present</span>
                    </label>
                    <label class="flex items-center gap-2 text-xs cursor-pointer">
                        <input type="radio" name="attendance" value="absent" class="form-radio text-[#d6b15c] focus:ring-[#d6b15c]">
                        <span>Some members absent</span>
                    </label>
                </div>
            </div>

            <!-- Absent Students Checklist -->
            <div id="absent_students_container" class="hidden">
                <label class="form-label">Select Absent Student(s)</label>
                <div id="student_checklist" class="grid grid-cols-2 gap-2 mt-1 p-3 border border-[#e2dacf] rounded-xl bg-[#faf8f4] max-h-36 overflow-y-auto">
                    <!-- Loaded dynamically via JS -->
                </div>
            </div>
            <div id="rubric_container" class="mb-4 hidden">
                <label class="form-label">Rubric: <span id="rubric_name_display" class="text-[#b88d3a]"></span></label>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead><tr class="text-[#5b6375] border-b border-[#e2dacf]"><th class="text-left py-2">Criteria</th><th class="text-center py-2">Weight</th><th class="text-center py-2">Max Score</th><th class="text-center py-2">Your Score</th></tr></thead>
                        <tbody id="criteria_tbody"></tbody>
                        <tfoot><tr class="border-t border-[#e2dacf] font-semibold"><td class="py-2">Total</td><td class="text-center" id="total_weight">100%</td><td class="text-center" id="total_max">0</td><td class="text-center" id="total_score_display">0</td></tr></tfoot>
                    </table>
                </div>
            </div>
            <div><label class="form-label">Feedback</label><textarea name="feedback" rows="3" class="form-input" placeholder="Overall feedback for this milestone..."></textarea></div>
            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeModal('evaluationModal')" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary">Submit Evaluation</button>
            </div>
        </form>
    </div>
</div>
{{-- edit group modal --}}
<div id="editGroupModal" class="modal-overlay">
    <div class="modal-box wide">
        <div class="modal-accent"></div>
        <div class="flex justify-between items-center mb-4">
            <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.4rem; font-weight:600; color:var(--navy);">Edit Group Members</h2>
            <button type="button" onclick="closeModal('editGroupModal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-lg">&times;</button>
        </div>
        <div id="editGroupErrors" class="hidden mb-3 p-3 bg-red-50 border border-red-200 text-red-700 rounded-lg text-sm"></div>
        <form id="editGroupForm" method="POST" class="space-y-4">
            @csrf
            <div><label class="form-label">Group Name</label><input type="text" name="group_name" id="edit_group_name" class="form-input" required></div>
            <div><label class="form-label">Capstone Title</label><input type="text" name="capstone_title" id="edit_capstone_title" class="form-input" required></div>
            <div>
                <label class="form-label">Add Student to Team</label>
                <div class="flex gap-2">
                    <select id="editStudentSelect" class="form-select flex-1" multiple style="height:100px;"><option disabled>Loading section students...</option></select>
                    <button type="button" id="editAddStudentsBtn" class="btn-primary whitespace-nowrap"><i class="fas fa-plus mr-1"></i> Add</button>
                </div>
            </div>
            <div>
                <label class="form-label">Current Team</label>
                <div id="editSelectedStudentsContainer" class="space-y-2 max-h-56 overflow-y-auto"></div>
            </div>
            <div class="flex justify-end gap-2 pt-3">
                <button type="button" onclick="closeModal('editGroupModal')" class="btn-ghost">Cancel</button>
                <button type="submit" class="btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

@php
    $uniqueStudents = $groups->flatMap(fn($g) => $g->students)->unique('id');
@endphp

<!-- TEACHER DIRECTORY DETAIL MODAL (TABBED OVERVIEW) -->
<div id="dashboard_detail_modal" class="modal-overlay">
    <div class="modal-box wide" style="max-width: 68rem;">
        <div class="modal-accent"></div>
        <div class="flex justify-between items-center mb-6 p-1">
            <div>
                <h2 style="font-family:'Cormorant Garamond',serif; font-size:1.6rem; font-weight:600; color:var(--navy);" id="ddm_modal_title">Teacher System Directory Details</h2>
                <p class="text-xs text-[#5b6375] mt-1">Detailed directory of all entities handled or assigned to you.</p>
            </div>
            <button type="button" onclick="closeModal('dashboard_detail_modal')" class="text-[#5b6375] hover:text-[#0a1428] transition text-2xl focus:outline-none">&times;</button>
        </div>

        <!-- Tab Buttons -->
        <div class="flex gap-2 border-b border-[#e2dacf] pb-3 mb-6 overflow-x-auto">
            <button type="button" onclick="switchDdmTab('groups')" id="ddm_tab_groups" class="ddm-tab-btn px-4 py-2 text-xs font-semibold rounded-lg transition-all border border-[#e2dacf] text-[#5b6375] hover:bg-[#faf8f4]">
                <i class="fas fa-layer-group mr-1.5 text-[#d6b15c]"></i> Handled Groups (<span id="ddm_count_groups">{{ count($groups ?? []) }}</span>)
            </button>
            <button type="button" onclick="switchDdmTab('students')" id="ddm_tab_students" class="ddm-tab-btn px-4 py-2 text-xs font-semibold rounded-lg transition-all border border-[#e2dacf] text-[#5b6375] hover:bg-[#faf8f4]">
                <i class="fas fa-user-graduate mr-1.5 text-[#d6b15c]"></i> My Students (<span id="ddm_count_students">{{ count($uniqueStudents ?? []) }}</span>)
            </button>
            <button type="button" onclick="switchDdmTab('evaluations')" id="ddm_tab_evaluations" class="ddm-tab-btn px-4 py-2 text-xs font-semibold rounded-lg transition-all border border-[#e2dacf] text-[#5b6375] hover:bg-[#faf8f4]">
                <i class="fa-regular fa-circle-check mr-1.5 text-[#d6b15c]"></i> My Evaluations (<span id="ddm_count_evaluations">{{ count($evaluations ?? []) }}</span>)
            </button>
            <button type="button" onclick="switchDdmTab('sections')" id="ddm_tab_sections" class="ddm-tab-btn px-4 py-2 text-xs font-semibold rounded-lg transition-all border border-[#e2dacf] text-[#5b6375] hover:bg-[#faf8f4]">
                <i class="fas fa-columns mr-1.5 text-[#d6b15c]"></i> My Sections (<span id="ddm_count_sections">{{ count($teacherSections ?? []) }}</span>)
            </button>
        </div>

        <!-- Search Filter -->
        <div class="mb-4">
            <div class="relative">
                <input type="text" id="ddm_search_input" oninput="filterDdmTable()" class="form-input text-xs w-full pl-9 py-2 border border-[#e2dacf] rounded-xl bg-[#faf8f4]" placeholder="Search by name, title, section, or details...">
                <i class="fas fa-search absolute left-3 top-3 text-[#b8b0a0] text-xs"></i>
            </div>
        </div>

        <!-- Tabs Content Container -->
        <div class="overflow-y-auto max-h-[50vh] border border-[#e2dacf] bg-white rounded-xl">
            
            <!-- Handled Groups Tab Content -->
            <div id="ddm_content_groups" class="ddm-tab-content hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#faf8f4] text-[#0a1428] font-semibold text-xs border-b border-[#e2dacf]">
                            <th class="p-3 pl-4">Group Name</th>
                            <th class="p-3">Capstone Title</th>
                            <th class="p-3">Section</th>
                            <th class="p-3">Role</th>
                            <th class="p-3">Room</th>
                            <th class="p-3 pr-4 text-center">Members</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#faf1e0]">
                        @forelse($groups ?? [] as $gr)
                            @php
                                $isAdviser = $gr->adviser_id == $teacher->id;
                            @endphp
                            <tr class="hover:bg-[#faf8f4]/50 text-xs ddm-row-item transition duration-150" data-search-text="{{ strtolower($gr->group_name . ' ' . $gr->capstone_title . ' ' . ($gr->section->section_name ?? '') . ' ' . ($isAdviser ? 'Adviser' : 'Panelist') . ' ' . ($gr->room->room_name ?? '')) }}">
                                <td class="p-3 pl-4 font-semibold text-[#0a1428]">{{ $gr->group_name }}</td>
                                <td class="p-3 text-[#3d4450] max-w-xs truncate" title="{{ $gr->capstone_title }}">{{ $gr->capstone_title }}</td>
                                <td class="p-3 text-[#3d4450]">{{ $gr->section->section_name ?? 'Unassigned' }}</td>
                                <td class="p-3">
                                    @if($isAdviser)
                                        <span class="badge badge-navy text-[10px]"><i class="fas fa-user-tie text-[9px] mr-1"></i> Adviser</span>
                                    @else
                                        <span class="badge badge-gold text-[10px]" style="background-color:rgba(214,177,92,0.1); color:rgba(184,141,58,1); border:1px solid rgba(214,177,92,0.25);"><i class="fas fa-users text-[9px] mr-1"></i> Panelist</span>
                                    @endif
                                </td>
                                <td class="p-3">
                                    @if($gr->room)
                                        <span class="badge badge-green text-[10px]"><i class="fas fa-door-open text-[9px] mr-1"></i> {{ $gr->room->room_name }}</span>
                                    @else
                                        <span class="badge badge-muted text-[10px]">Unassigned</span>
                                    @endif
                                </td>
                                <td class="p-3 pr-4 text-center text-[#5b6375] font-semibold">{{ $gr->students->count() }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-8 text-center text-[#5b6375]"><i class="fa-regular fa-folder-open text-2xl mb-2 block"></i> No handled groups.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- My Students Tab Content -->
            <div id="ddm_content_students" class="ddm-tab-content hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#faf8f4] text-[#0a1428] font-semibold text-xs border-b border-[#e2dacf]">
                            <th class="p-3 pl-4">Student</th>
                            <th class="p-3">User ID</th>
                            <th class="p-3">Course</th>
                            <th class="p-3">Section</th>
                            <th class="p-3">Group</th>
                            <th class="p-3 pr-4">Contact</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#faf1e0]">
                        @forelse($uniqueStudents ?? [] as $st)
                            @php
                                $studentGroup = $groups->first(fn($g) => $g->students->contains($st->id));
                                $grpName = $studentGroup ? $studentGroup->group_name : 'No Group';
                            @endphp
                            <tr class="hover:bg-[#faf8f4]/50 text-xs ddm-row-item transition duration-150" data-search-text="{{ strtolower($st->student_first_name . ' ' . $st->student_last_name . ' ' . $st->user_id . ' ' . $st->course . ' ' . $st->section . ' ' . $grpName) }}">
                                <td class="p-3 pl-4">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-7 h-7 rounded-full flex items-center justify-center text-[10px] font-bold text-white flex-shrink-0" style="background:linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);">
                                            {{ strtoupper(substr($st->student_first_name, 0, 1) . substr($st->student_last_name, 0, 1)) }}
                                        </div>
                                        <span class="font-semibold text-[#0a1428]">{{ $st->student_first_name }} {{ $st->student_last_name }}</span>
                                    </div>
                                </td>
                                <td class="p-3 text-[#3d4450] font-mono">{{ $st->user->user_id ?? 'N/A' }}</td>
                                <td class="p-3 text-[#3d4450]">{{ $st->course }}</td>
                                <td class="p-3 text-[#3d4450]">{{ $st->section }}</td>
                                <td class="p-3">
                                    @if($studentGroup)
                                        <span class="badge badge-navy text-[10px]"><i class="fas fa-layer-group text-[9px] mr-1"></i> {{ $grpName }}</span>
                                    @else
                                        <span class="badge badge-muted text-[10px]">No Group</span>
                                    @endif
                                </td>
                                <td class="p-3 pr-4 text-[#5b6375]">
                                    <div class="flex flex-col text-[10px]">
                                        <span><i class="fa-regular fa-envelope mr-1 text-[#b8b0a0]"></i> {{ $st->student_email }}</span>
                                        @if($st->contact_number)
                                            <span class="mt-0.5"><i class="fa-solid fa-phone mr-1 text-[#b8b0a0]"></i> {{ $st->contact_number }}</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="p-8 text-center text-[#5b6375]"><i class="fa-regular fa-folder-open text-2xl mb-2 block"></i> No students registered in your handled groups.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- My Evaluations Tab Content -->
            <div id="ddm_content_evaluations" class="ddm-tab-content hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#faf8f4] text-[#0a1428] font-semibold text-xs border-b border-[#e2dacf]">
                            <th class="p-3 pl-4">Group Name</th>
                            <th class="p-3">Milestone</th>
                            <th class="p-3 text-center">Score</th>
                            <th class="p-3">Date Evaluated</th>
                            <th class="p-3 pr-4">Feedback</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#faf1e0]">
                        @forelse($evaluations ?? [] as $ev)
                            <tr class="hover:bg-[#faf8f4]/50 text-xs ddm-row-item transition duration-150" data-search-text="{{ strtolower(($ev->group->group_name ?? 'Unknown') . ' ' . ($ev->milestone->milestone_title ?? '') . ' ' . $ev->feedback) }}">
                                <td class="p-3 pl-4 font-semibold text-[#0a1428]">{{ $ev->group->group_name ?? 'Unknown' }}</td>
                                <td class="p-3 text-[#3d4450]">{{ $ev->milestone->milestone_title ?? '' }}</td>
                                <td class="p-3 text-center text-[#1e6b3a] font-bold">{{ $ev->score }} / {{ $ev->max_score }}</td>
                                <td class="p-3 text-[#3d4450] font-mono">{{ \Carbon\Carbon::parse($ev->evaluation_date)->format('M d, Y') }}</td>
                                <td class="p-3 pr-4 text-[#5b6375] italic max-w-sm truncate" title="{{ $ev->feedback }}">{{ $ev->feedback }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="p-8 text-center text-[#5b6375]"><i class="fa-regular fa-folder-open text-2xl mb-2 block"></i> No evaluations submitted by you yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- My Sections Tab Content -->
            <div id="ddm_content_sections" class="ddm-tab-content hidden">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-[#faf8f4] text-[#0a1428] font-semibold text-xs border-b border-[#e2dacf]">
                            <th class="p-3 pl-4">Section Name</th>
                            <th class="p-3">Assigned Groups Count</th>
                            <th class="p-3 pr-4">Assigned Groups</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-[#faf1e0]">
                        @forelse($teacherSections ?? [] as $sec)
                            @php
                                $secGroups = $groups->where('section_id', $sec->id);
                            @endphp
                            <tr class="hover:bg-[#faf8f4]/50 text-xs ddm-row-item transition duration-150" data-search-text="{{ strtolower($sec->section_name . ' ' . $secGroups->pluck('group_name')->join(' ')) }}">
                                <td class="p-3 pl-4 font-semibold text-[#0a1428]">{{ $sec->section_name }}</td>
                                <td class="p-3 text-[#3d4450] font-mono">{{ $secGroups->count() }} group(s)</td>
                                <td class="p-3 pr-4">
                                    <div class="flex flex-wrap gap-1">
                                        @forelse($secGroups as $sg)
                                            <span class="badge badge-navy text-[9px]">{{ $sg->group_name }}</span>
                                        @empty
                                            <span class="text-gray-400 italic text-[10px]">No groups in this section</span>
                                        @endforelse
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="3" class="p-8 text-center text-[#5b6375]"><i class="fa-regular fa-folder-open text-2xl mb-2 block"></i> No sections assigned.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <div class="flex justify-end pt-4 border-t border-[#e2dacf] mt-5">
            <button type="button" onclick="closeModal('dashboard_detail_modal')" class="btn-primary text-xs px-5 py-2">Close Details</button>
        </div>
    </div>
</div>

<script>
// ══════════════════════════════════════════════
// MODAL HELPERS
// ══════════════════════════════════════════════
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.add('active');
}
function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.classList.remove('active');
}

window.toggleSectionCollapse = function(sectionId) {
    const content = document.getElementById(`section_collapsible_content_${sectionId}`);
    const icon = document.getElementById(`collapse_icon_${sectionId}`);
    if (!content || !icon) return;

    const isCollapsed = content.classList.toggle('hidden');
    if (isCollapsed) {
        icon.style.transform = 'rotate(-90deg)';
    } else {
        icon.style.transform = 'rotate(0deg)';
    }
};

window.toggleSectionView = function(prefix, sectionId, viewType) {
    const groupsView = document.getElementById(`${prefix}_section_groups_view_${sectionId}`);
    const studentsView = document.getElementById(`${prefix}_section_students_view_${sectionId}`);
    const groupsBtn = document.getElementById(`${prefix}_toggle_btn_${sectionId}_groups`);
    const studentsBtn = document.getElementById(`${prefix}_toggle_btn_${sectionId}_students`);

    if (!groupsView || !studentsView || !groupsBtn || !studentsBtn) return;

    if (viewType === 'groups') {
        groupsView.classList.remove('hidden');
        studentsView.classList.add('hidden');

        // Style groups button active
        groupsBtn.className = 'px-2.5 py-1 rounded-md font-semibold text-[#b88d3a] bg-white shadow-sm transition-all focus:outline-none';
        // Style students button inactive
        studentsBtn.className = 'px-2.5 py-1 rounded-md font-semibold text-[#5b6375] hover:text-[#0a1428] transition-all focus:outline-none';
    } else {
        groupsView.classList.add('hidden');
        studentsView.classList.remove('hidden');

        // Style students button active
        studentsBtn.className = 'px-2.5 py-1 rounded-md font-semibold text-[#b88d3a] bg-white shadow-sm transition-all focus:outline-none';
        // Style groups button inactive
        groupsBtn.className = 'px-2.5 py-1 rounded-md font-semibold text-[#5b6375] hover:text-[#0a1428] transition-all focus:outline-none';
    }
};

window.openDashboardDetailModal = function(tabName) {
    openModal('dashboard_detail_modal');
    switchDdmTab(tabName);
    // Reset search input
    const sInput = document.getElementById('ddm_search_input');
    if (sInput) {
        sInput.value = '';
        filterDdmTable();
    }
};

window.switchDdmTab = function(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.ddm-tab-content').forEach(el => el.classList.add('hidden'));
    
    // Reset all tab button styles to default state
    document.querySelectorAll('.ddm-tab-btn').forEach(btn => {
        btn.style.borderColor = '#e2dacf';
        btn.style.color = '#5b6375';
        btn.style.backgroundColor = 'transparent';
        btn.classList.remove('active');
    });

    // Show selected content
    const content = document.getElementById(`ddm_content_${tabName}`);
    if (content) content.classList.remove('hidden');

    // Set active button style
    const activeBtn = document.getElementById(`ddm_tab_${tabName}`);
    if (activeBtn) {
        activeBtn.style.borderColor = 'var(--gold)';
        activeBtn.style.color = 'var(--gold)';
        activeBtn.style.backgroundColor = '#faf8f4';
        activeBtn.classList.add('active');
    }

    // Update modal title prefix based on selected tab
    const modalTitle = document.getElementById('ddm_modal_title');
    if (modalTitle) {
        const titleMap = {
            students: 'My Students Directory',
            groups: 'Handled Groups Directory',
            evaluations: 'Submitted Evaluations',
            sections: 'My Sections Directory'
        };
        modalTitle.textContent = titleMap[tabName] || 'Teacher System Directory Details';
    }

    // Run search filter on switch to apply current filter to the active table
    filterDdmTable();
};

window.filterDdmTable = function() {
    const query = document.getElementById('ddm_search_input').value.trim().toLowerCase();
    const activeTab = document.querySelector('.ddm-tab-btn.active');
    if (!activeTab) return;

    const tabIdName = activeTab.id.replace('ddm_tab_', '');
    const activeContent = document.getElementById(`ddm_content_${tabIdName}`);
    if (!activeContent) return;

    const rows = activeContent.querySelectorAll('.ddm-row-item');
    let visibleCount = 0;

    rows.forEach(row => {
        const searchVal = row.dataset.searchText || '';
        if (!query || searchVal.includes(query)) {
            row.style.display = '';
            visibleCount++;
        } else {
            row.style.display = 'none';
        }
    });

    // Update counter in active tab
    const counterEl = document.getElementById(`ddm_count_${tabIdName}`);
    if (counterEl) {
        const originalTotal = rows.length;
        if (query) {
            counterEl.textContent = `${visibleCount}/${originalTotal}`;
        } else {
            counterEl.textContent = originalTotal;
        }
    }
};
document.addEventListener('click', function (e) {
    if (e.target.classList.contains('modal-overlay') && e.target.classList.contains('active')) {
        e.target.classList.remove('active');
    }
});

// ══════════════════════════════════════════════
// TOAST NOTIFICATIONS
// ══════════════════════════════════════════════
const toastEl = document.getElementById('toast');
const toastMessageEl = document.getElementById('toastMessage');
let toastTimeout = null;

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
    toastTimeout = setTimeout(hideToast, 3000);
}
function hideToast() {
    toastEl.classList.remove('show');
    if (toastTimeout) {
        clearTimeout(toastTimeout);
        toastTimeout = null;
    }
}

// ══════════════════════════════════════════════
// MAIN
// ══════════════════════════════════════════════
function fmtDate(d) {
    return d ? new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }) : '—';
}

// ── VIEW PROGRESS MODAL ────────────────────────
function openViewModal(groupId) {
    const modal = document.getElementById('viewModal');
    const loading = document.getElementById('view_loading');
    const dataDiv = document.getElementById('view_data');

    modal.classList.add('active');
    loading.classList.remove('hidden');
    dataDiv.classList.add('hidden');

    Promise.all([
        fetch(`/teacher/get-group-progress/${groupId}`).then(r => r.json()),
        fetch(`/teacher/get-group/${groupId}`).then(r => r.json())
    ])
    .then(([data, groupData]) => {
        loading.classList.add('hidden');
        dataDiv.classList.remove('hidden');
        const members = groupData.members || [];

        document.getElementById('view_modal_title').textContent = `Progress: ${data.group_name}`;
        document.getElementById('view_progress_label').textContent = data.overall_progress;
        document.getElementById('view_overall_progress').style.width = data.overall_progress + '%';

        const tbody = document.getElementById('view_milestones_tbody');
        tbody.innerHTML = '';
        let currentStage = null;

        data.milestones.forEach(m => {
            if (m.capstone_stage_id != currentStage) {
                currentStage = m.capstone_stage_id;
                const divider = document.createElement('tr');
                divider.className = 'stage-divider';
                divider.innerHTML = `<td colspan="3">${m.capstone_stage_title}</td>`;
                tbody.appendChild(divider);
            }

            const row = document.createElement('tr');
            row.className = m.is_completed ? 'row-completed' : (m.is_next ? 'row-next' : '');

            const dateHtml = `${fmtDate(m.start_date)}${m.due_date ? ' – ' + fmtDate(m.due_date) : ''}`;

            let statusBadge = m.is_completed
                ? `<span class="task-status completed">Completed ${fmtDate(m.completion_date)}</span>`
                : (m.is_next ? `<span class="task-status next">Next Step</span>` : `<span class="task-status pending">Pending</span>`);
            const taskHtml = `
                <span class="task-title">${m.title}</span>
                ${m.description ? `<span class="task-desc">${m.description}</span>` : ''}
                ${statusBadge}
            `;

            const evaluations = data.evaluations || [];
            const milestoneEvals = evaluations.filter(e => e.milestone_id == m.id);
            let remarksHtml = '';

            if (m.has_rubric) {
                if (milestoneEvals.length > 0) {
                    remarksHtml = '';
                } else if (data.is_adviser || data.is_panelist) {
                    remarksHtml = `
                        <div class="flex flex-col gap-1.5 items-start">
                            <span class="task-status next" style="background-color: rgba(10,20,40,0.08); color: var(--navy); border: 1px solid rgba(10,20,40,0.15);"><i class="fa-solid fa-circle-info mr-1"></i> Panelist Evaluation Only</span>
                          
                        </div>
                    `;
                } else {
                    remarksHtml = `<span class="task-status pending"><i class="fa-solid fa-clock mr-1"></i> Awaiting Panelist Evaluation</span>`;
                }
            } else {
                if (m.remarks) {
                    const r = m.remarks;
                    const statusText = r.remarks_status || (r.compiled ? 'On Time Compliance' : 'Late Submission');

                    let statusClass = 'on-time', statusIcon = 'fa-circle-check';
                    if (/late/i.test(statusText)) { statusClass = 'late'; statusIcon = 'fa-triangle-exclamation'; }
                    else if (/early/i.test(statusText)) { statusClass = 'early'; statusIcon = 'fa-clock'; }

                    const absentNames = (m.absent_students && m.absent_students.length) ? m.absent_students : [];

                    const absenceTableHtml = (!r.all_present && absentNames.length)
                        ? `<table class="absence-table">
                            <thead><tr><th>#</th><th>Absent Student</th></tr></thead>
                            <tbody>
                                ${absentNames.map((name, i) => `<tr><td>${i + 1}</td><td>${name}</td></tr>`).join('')}
                            </tbody>
                        </table>`
                        : '';

                    const feedbackHtml = r.feedback ? `<div class="remark-feedback">"${r.feedback}"</div>` : '';

                    remarksHtml = `
                        <div class="remark-summary">
                            <span class="remark-status-badge ${statusClass}"><i class="fa-solid ${statusIcon}"></i> ${statusText}</span>
                            ${r.deduction_points ? `<span class="remark-deduction"><i class="fa-solid fa-minus"></i> ${r.deduction_points} pts deduction</span>` : ''}
                            <span class="remark-attendance"><i class="fa-solid fa-user-group"></i> ${r.all_present ? 'All members present' : `${absentNames.length} member(s) absent`}</span>
                            ${absenceTableHtml}
                            ${feedbackHtml}
                        </div>
                    `;
                } else if (m.is_next && data.is_adviser) {
                    remarksHtml = `
                        <div class="mb-2">
                            <label class="form-label text-[10px]">Attendance</label>
                            <div class="flex flex-col gap-1 mt-1">
                                <label class="flex items-center gap-2 text-xs cursor-pointer">
                                    <input type="radio" name="attendance_${m.id}" value="present" class="attendance-radio" checked> All present
                                </label>
                                <label class="flex items-center gap-2 text-xs cursor-pointer">
                                    <input type="radio" name="attendance_${m.id}" value="absent" class="attendance-radio"> Some absent
                                </label>
                            </div>
                        </div>
                        <div id="absent_container_${m.id}" class="mb-2 hidden">
                            <label class="form-label text-[10px]">Absent Students</label>
                            <div id="absent_list_${m.id}" class="grid grid-cols-1 gap-1 mt-1 p-2 border border-[#e2dacf] rounded-lg bg-[#faf8f4] max-h-28 overflow-y-auto text-xs"></div>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-input text-xs remark-feedback-input" placeholder="Optional remarks...">
                        </div>
                        <button type="button" class="btn-primary text-xs submit-remark-btn" data-milestone-id="${m.id}">
                            <i class="fas fa-check mr-1"></i> Evaluate
                        </button>
                    `;
                } else if (m.is_next && !data.is_adviser) {
                    remarksHtml = `<span class="task-status next">Next Step — awaiting adviser evaluation</span>`;
                } else {
                    remarksHtml = `<span class="remark-empty">Not yet available</span>`;
                }
            }

            let evaluationHtml = '';
            if (milestoneEvals.length > 0) {
                evaluationHtml = milestoneEvals.map((evaluation, idx) => {
                    let criteriaRowsHtml = '';
                    if (evaluation.criteria && evaluation.criteria.length > 0) {
                        criteriaRowsHtml = `
                            <div id="eval_rubric_details_${m.id}_${idx}" class="hidden mt-2 p-2 bg-[#faf8f4] border border-[#e2dacf] rounded-lg">
                                <table class="w-full text-[11px]">
                                    <thead>
                                        <tr class="border-b border-[#e2dacf] text-left text-[#5b6375]">
                                            <th class="py-1 text-left">Criterion</th>
                                            <th class="py-1 text-center font-normal">Weight</th>
                                            <th class="py-1 text-center font-normal">Max</th>
                                            <th class="py-1 text-center font-normal">Score</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${evaluation.criteria.map(c => `
                                            <tr class="border-b border-[#faf1e0]">
                                                <td class="py-1 text-left text-[#171e2c] font-medium">${c.criteria_name}</td>
                                                <td class="py-1 text-center text-[#5b6375]">${c.weight}%</td>
                                                <td class="py-1 text-center text-[#5b6375]">${c.max_score}</td>
                                                <td class="py-1 text-center font-bold text-[#1e6b3a]">${c.given_score}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            </div>
                        `;
                    }

                    return `
                        <div class="mt-3 pt-3 border-t border-[#e2dacf] text-xs">
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-[#0a1428]"><i class="fa-solid fa-square-poll-vertical text-[#d6b15c] mr-1"></i> Panelist Evaluation</span>
                                <span class="font-bold text-[#1e6b3a]">${evaluation.score} / ${evaluation.max_score}</span>
                            </div>
                            <p class="text-[10px] text-[#5b6375] mt-0.5">By ${evaluation.teacher_name} on ${fmtDate(evaluation.evaluation_date)}</p>
                            ${evaluation.feedback ? `<p class="italic text-[#5b6375] mt-1 bg-[#fbfaf7] p-1.5 border-l-2 border-[#d6b15c]">${evaluation.feedback}</p>` : ''}
                            ${criteriaRowsHtml ? `
                                <button onclick="document.getElementById('eval_rubric_details_${m.id}_${idx}').classList.toggle('hidden'); event.stopPropagation();" class="text-[#b88d3a] hover:text-[#8b6914] text-[11px] font-medium mt-1.5 block focus:outline-none">
                                    <i class="fas fa-list mr-1"></i> Toggle Rubric Criteria Scores
                                </button>
                                ${criteriaRowsHtml}
                            ` : ''}
                        </div>
                    `;
                }).join('');
            } else if (m.has_rubric) {
                evaluationHtml = `
                    <div class="mt-3 text-xs text-[#5b6375] italic bg-[#faf8f4] p-2 border border-[#e2dacf] rounded-lg">
                        <i class="fa-solid fa-triangle-exclamation text-amber-500 mr-1.5"></i> This group has not been evaluated yet for this milestone.
                    </div>
                `;
            }

            row.innerHTML = `<td>${dateHtml}</td><td>${taskHtml}</td><td>${remarksHtml}${evaluationHtml}</td>`;
            tbody.appendChild(row);

           if (!m.remarks && m.is_next && data.is_adviser) {
                const submitBtn = row.querySelector('.submit-remark-btn');
                if (submitBtn) {
                    const absentList = row.querySelector(`#absent_list_${m.id}`);
                    if (absentList) {
                        absentList.innerHTML = members.length
                            ? members.map(mem => `
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="checkbox" value="${mem.user_id}" class="absent-checkbox">
                                    ${mem.name} <span class="text-[#5b6375]">(${mem.user_id})</span>
                                </label>`).join('')
                            : '<span class="text-[#5b6375]">No members found.</span>';
                    }
                    row.querySelectorAll(`input[name="attendance_${m.id}"]`).forEach(radio => {
                        radio.addEventListener('change', function () {
                            row.querySelector(`#absent_container_${m.id}`).classList.toggle('hidden', this.value !== 'absent');
                        });
                    });
                    submitBtn.addEventListener('click', function () {
                        const attendance = row.querySelector(`input[name="attendance_${m.id}"]:checked`)?.value || 'present';
                        const checkedAbsent = row.querySelectorAll('.absent-checkbox:checked');
                        if (attendance === 'absent' && checkedAbsent.length === 0) {
                            showToast('Please select at least one absent student.', true);
                            return;
                        }
                        submitRemarkEvaluation(groupId, m.id, this, row);
                    });
                }
           }
            
        });

        // ── Load and render earned certificates for this group ──
        fetch(`/group/${groupId}/certificates`)
            .then(r => r.json())
            .then(certs => {
                const certContainer = document.getElementById('view_certificates_container');
                const certList = document.getElementById('view_certificates_list');
                certList.innerHTML = '';

                if (!Array.isArray(certs) || certs.length === 0) {
                    certContainer.classList.add('hidden');
                    return;
                }

                certContainer.classList.remove('hidden');
                certs.forEach(c => {
                    const row = document.createElement('div');
                    row.className = 'cert-row';
                    row.innerHTML = `
                        <div>
                            <p class="cert-title">${c.certificate_title}</p>
                            <p class="cert-date">Issued ${fmtDate(c.issued_date)}</p>
                        </div>
                        <a href="/certificate/${groupId}/${c.certificate_id}" target="_blank" class="btn-outline text-xs py-1.5 px-3">
                            <i class="fas fa-print mr-1"></i> Print / Download
                        </a>
                    `;
                    certList.appendChild(row);
                });
            })
            .catch(() => {
                document.getElementById('view_certificates_container').classList.add('hidden');
            });
    })
    .catch(() => {
        loading.classList.add('hidden');
        dataDiv.classList.add('hidden');
        showToast('Failed to load group progress.', true);
    });
}   
function submitRemarkEvaluation(groupId, milestoneId, btn, row) {
    const attendance = row.querySelector(`input[name="attendance_${milestoneId}"]:checked`)?.value || 'present';
    const absentIds = Array.from(row.querySelectorAll('.absent-checkbox:checked')).map(cb => cb.value);
    const feedback = row.querySelector('.remark-feedback-input')?.value || '';

    btn.disabled = true;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i> Saving...';

    fetch('/teacher/evaluate-remark', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: JSON.stringify({ group_id: groupId, milestone_id: milestoneId, attendance, absent_students: absentIds, feedback })
    })
    .then(async r => {
        const data = await r.json();
        if (!r.ok) throw data;
        return data;
    })
    .then(data => {
        showToast('Milestone evaluated — ' + data.remarks.remarks_status);
        const pctEl = document.querySelector(`.progress-complete-label[data-group-id="${groupId}"] .progress-pct`);
        if (pctEl) pctEl.textContent = data.overall_progress;
        openViewModal(groupId); // re-render with the row now marked completed
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = originalHtml;
        showToast((err && err.error) || 'Failed to save evaluation.', true);
    });
}

document.addEventListener('DOMContentLoaded', function () {

    @if(session('success'))
        showToast('{{ session('success') }}');
    @endif
    @if(session('error'))
        showToast('{{ session('error') }}', true);
    @endif
    @if($errors->any())
        showToast('{{ $errors->first() }}', true);
    @endif

    // ── ATTENDANCE TOGGLE LOGIC ──────────────────────
    const attendanceRadios = document.querySelectorAll('input[name="attendance"]');
    if (attendanceRadios.length) {
        attendanceRadios.forEach(radio => {
            radio.addEventListener('change', function() {
                const container = document.getElementById('absent_students_container');
                if (this.value === 'absent') {
                    container.classList.remove('hidden');
                } else {
                    container.classList.add('hidden');
                    document.querySelectorAll('#student_checklist input[type="checkbox"]').forEach(cb => cb.checked = false);
                }
            });
        });
    }

    // ── SECTION SWITCHING ──────────────────────
    const sections = {
        dashboard: document.getElementById('dashboard-section'),
        assignedsections: document.getElementById('assignedsections-section'),

        sections: document.getElementById('sections-section'),
        evaluate: document.getElementById('evaluate-section'),
        profile: document.getElementById('profile-section'),
    };
    const navLinks = document.querySelectorAll('.nav-link');
    const mobileNavLinks = document.querySelectorAll('.mobile-nav-link');

    function activateSection(sectionId) {
        Object.values(sections).forEach(s => s && s.classList.add('hidden'));
        if (sections[sectionId]) sections[sectionId].classList.remove('hidden');
        navLinks.forEach(link => {
            const isActive = link.dataset.section === sectionId;
            link.classList.toggle('active-link', isActive);
            link.style.color = isActive ? 'var(--gold)' : 'rgba(255,255,255,0.65)';
        });
        mobileNavLinks.forEach(link => {
            link.style.color = link.dataset.section === sectionId ? 'var(--gold)' : 'rgba(255,255,255,0.55)';
        });
    }

    [...navLinks, ...mobileNavLinks].forEach(link =>
        link.addEventListener('click', e => {
            e.preventDefault();
            const s = link.dataset.section;
            if (s && sections[s]) activateSection(s);
        })
    );
    activateSection('dashboard');

    // ── CREATE GROUP MODAL ─────────────────────
    const sectionSelect = document.getElementById('sectionSelect');
    const studentSelect = document.getElementById('studentSelect');
    const addBtn = document.getElementById('addStudentsBtn');
    const container = document.getElementById('selectedStudentsContainer');
    const noMsg = document.getElementById('noStudentsMsg');
    let idx = 0;

    window.openCreateGroupModal = function (sectionName = null) {
        const modal = document.getElementById('createGroupModal');
        if (!modal) return;
        modal.classList.add('active');
        if (sectionName) {
            sectionSelect.value = sectionName;
            sectionSelect.dispatchEvent(new Event('change'));
        } else {
            sectionSelect.value = '';
            studentSelect.innerHTML = '<option disabled>Select a section first</option>';
        }
    };

    if (sectionSelect) {
        sectionSelect.addEventListener('change', function () {
            const section = this.value;
            studentSelect.innerHTML = '<option disabled>Loading...</option>';
            if (!section) {
                studentSelect.innerHTML = '<option disabled>Select a section first</option>';
                return;
            }
            fetch(`/teacher/get-students/${encodeURIComponent(section)}`)
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

  if (addBtn && studentSelect) {
    addBtn.addEventListener('click', () => {
        Array.from(studentSelect.selectedOptions).forEach(o => {
            if (o.value) addRow(o.value, o.textContent);
        });
        studentSelect.selectedIndex = -1;
    });
}

    // ── EVALUATION MODAL ────────────────────────
    window.openEvaluationModal = function (groupId, milestoneId = null) {
    // ── Set the hidden group ID ──
    document.getElementById('eval_group_id').value = groupId;

    // ── Try to set a nice group name, but don't crash if it fails ──
    try {
        const groupEl = document.querySelector(`[data-group="${groupId}"]`);
        if (groupEl) {
            const nameEl = groupEl.closest('.content-card, tr, .group-item')?.querySelector('.font-bold, .font-semibold, h4');
            if (nameEl) {
                document.getElementById('eval_group_name').value = nameEl.textContent.trim();
            }
        }
    } catch (e) {
        console.warn('Could not set group name', e);
    }

    // ── Reset the form ──
    const milestoneSelect = document.getElementById('milestone_select');
    milestoneSelect.value = milestoneId || '';
    if (milestoneId) {
        document.getElementById('eval_milestone_id').value = milestoneId;
        setTimeout(() => {
            milestoneSelect.dispatchEvent(new Event('change'));
        }, 100);
    } else {
        document.getElementById('rubric_container').classList.add('hidden');
        document.getElementById('criteria_tbody').innerHTML = '';
        document.getElementById('eval_total_score').value = '';
        document.getElementById('eval_max_score').value = '';
    }

    // ── Open the modal first (user sees it immediately) ──
    openModal('evaluationModal');

    // ── Then load group members in the background ──
    const checklist = document.getElementById('student_checklist');
    checklist.innerHTML = '<p class="text-xs text-[#5b6375] col-span-2 text-center py-2">Loading students…</p>';
    fetch(`/teacher/get-group/${groupId}`)
        .then(r => r.json())
        .then(data => {
            checklist.innerHTML = '';
            if (data.error || !data.members || data.members.length === 0) {
                checklist.innerHTML = '<p class="text-xs text-[#5b6375] col-span-2 text-center py-2">No members found.</p>';
                return;
            }
            data.members.forEach(m => {
                const label = document.createElement('label');
                label.className = 'flex items-center gap-2 cursor-pointer text-sm text-[#171e2c]';
                label.innerHTML = `<input type="checkbox" name="absent_students[]" value="${m.user_id}" class="form-checkbox text-[#d6b15c] focus:ring-[#d6b15c]">
                    <span>${m.name} <span class="text-[#5b6375] text-xs">(${m.user_id})</span></span>`;
                checklist.appendChild(label);
            });
        })
        .catch(() => {
            checklist.innerHTML = '<p class="text-xs text-red-500 col-span-2 text-center py-2">Failed to load students.</p>';
        });

    // ── Disable already evaluated milestones in the background ──
    Array.from(milestoneSelect.options).forEach(opt => {
        opt.disabled = false;
        opt.textContent = opt.textContent.replace(' (Already Evaluated)', '');
    });

    fetch(`/teacher/get-evaluated-milestones/${groupId}`)
        .then(r => r.json())
        .then(evaluatedIds => {
            evaluatedIds.forEach(id => {
                if (id == milestoneId) return; // Keep current milestone option active
                const opt = milestoneSelect.querySelector(`option[value="${id}"]`);
                if (opt) {
                    opt.disabled = true;
                    opt.textContent += ' (Already Evaluated)';
                }
            });
        })
        .catch(() => {
            // If we can't get evaluated milestones, just leave them enabled – not a dealbreaker
        });

    // Reset attendance radio (if present)
    const presentRadio = document.querySelector('input[name="attendance"][value="present"]');
    if (presentRadio) presentRadio.checked = true;
    const absentContainer = document.getElementById('absent_students_container');
    if (absentContainer) absentContainer.classList.add('hidden');
};

    document.querySelectorAll('.evaluate-btn').forEach(btn =>
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const groupId = this.dataset.group;
            if (groupId) window.openEvaluationModal(groupId);
        })
    );

    const milestoneSelect = document.getElementById('milestone_select');
    if (milestoneSelect) {
        milestoneSelect.addEventListener('change', function () {
            const milestoneId = this.value;
            const rubricContainer = document.getElementById('rubric_container');
            document.getElementById('eval_milestone_id').value = milestoneId;
            const tbody = document.getElementById('criteria_tbody');
            const rubricName = document.getElementById('rubric_name_display');
            if (!milestoneId) {
                rubricContainer.classList.add('hidden');
                tbody.innerHTML = '';
                return;
            }
            tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-[#5b6375]">Loading rubric...</td></tr>';
            rubricContainer.classList.remove('hidden');
            fetch(`/teacher/get-rubric/${milestoneId}`)
                .then(r => r.json())
                .then(data => {
                    if (data.error) {
                        tbody.innerHTML = `<tr><td colspan="4" class="text-center py-4 text-red-500">${data.error}</td></tr>`;
                        return;
                    }
                    rubricName.textContent = data.rubric_name;
                    let html = '';
                    data.criteria.forEach((c) => {
                        html += `<tr class="border-b border-[#e2dacf]">
                            <td class="py-2">${c.criteria_name}</td>
                            <td class="text-center">${c.weight}%</td>
                            <td class="text-center">${c.max_score}</td>
                            <td class="text-center">
                                <input type="number" name="rubric_scores[${c.id}]" class="criteria-score w-20 form-input text-center" data-weight="${c.weight}" data-max="${c.max_score}" min="0" max="${c.max_score}" step="0.01" value="0">
                            </td>
                        </tr>`;
                    });
                    tbody.innerHTML = html;
                    document.querySelectorAll('.criteria-score').forEach(inp => inp.addEventListener('input', recalcTotals));
                    recalcTotals();
                })
                .catch(() => tbody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-red-500">Failed to load rubric.</td></tr>');
        });
    }

    function recalcTotals() {
        let totalScore = 0;
        document.querySelectorAll('.criteria-score').forEach(inp => {
            const max = parseFloat(inp.dataset.max) || 0;
            const weight = parseFloat(inp.dataset.weight) || 0;
            let score = parseFloat(inp.value) || 0;
            if (score > max) score = max;
            if (score < 0) score = 0;
            totalScore += max > 0 ? (score / max) * weight : 0;
        });
        document.getElementById('total_score_display').textContent = totalScore.toFixed(2);
        document.getElementById('total_max').textContent = '100';
        document.getElementById('total_weight').textContent = '100%';
        document.getElementById('eval_total_score').value = totalScore.toFixed(2);
        document.getElementById('eval_max_score').value = '100';
    }

    // ── EDIT TEAM MEMBERS ───────────────────────
    let editIdx = 0;

    function editRow(name, userId, role) {
        const editContainer = document.getElementById('editSelectedStudentsContainer');
        if (editContainer.querySelector(`input[value="${userId}"]`)) {
            showToast('Student already in team.', true);
            return;
        }
        const row = document.createElement('div');
        row.className = 'flex items-center gap-2 p-2 bg-[#faf8f4] rounded border border-[#e2dacf]';
        row.innerHTML = `<input type="hidden" name="students[${editIdx}][user_id]" value="${userId}">
            <span class="flex-1 text-sm">${name} <span class="text-[#5b6375]">(${userId})</span></span>
            <select name="students[${editIdx}][role]" class="form-select w-32" required>
                <option value="programmer" ${role === 'programmer' ? 'selected' : ''}>Programmer</option>
                <option value="designer" ${role === 'designer' ? 'selected' : ''}>Designer</option>
                <option value="researcher" ${role === 'researcher' ? 'selected' : ''}>Researcher</option>
            </select>
            <button type="button" class="edit-remove-student text-[#5b6375] hover:text-red-500 transition"><i class="fas fa-times"></i></button>`;
        editContainer.appendChild(row);
        editIdx++;
        row.querySelector('.edit-remove-student').addEventListener('click', () => row.remove());
    }

    window.openEditGroupModal = function (groupId) {
        fetch(`/teacher/get-group/${groupId}`)
            .then(r => r.json())
            .then(data => {
                if (data.error) {
                    showToast(data.error, true);
                    return;
                }
                document.getElementById('editGroupErrors').classList.add('hidden');
                document.getElementById('editGroupForm').action = `/teacher/update-group/${groupId}`;
                document.getElementById('edit_group_name').value = data.group_name;
                document.getElementById('edit_capstone_title').value = data.capstone_title;

                editIdx = 0;
                const editContainer = document.getElementById('editSelectedStudentsContainer');
                editContainer.innerHTML = '';
                data.members.forEach(m => editRow(m.name, m.user_id, m.role));

                const editStudentSelect = document.getElementById('editStudentSelect');
                editStudentSelect.innerHTML = '<option disabled>Loading...</option>';
                fetch(`/teacher/get-students/${encodeURIComponent(data.section)}`)
                    .then(r => r.json())
                    .then(students => {
                        editStudentSelect.innerHTML = '';
                        if (!students.length) {
                            editStudentSelect.innerHTML = '<option disabled>No unassigned students in this section</option>';
                            return;
                        }
                        students.forEach(s => {
                            const opt = document.createElement('option');
                            opt.value = s.user_id;
                            opt.textContent = `${s.student_first_name} ${s.student_last_name} (${s.user_id})`;
                            editStudentSelect.appendChild(opt);
                        });
                    });

                openModal('editGroupModal');
            })
            .catch(() => showToast('Failed to load group.', true));
    };

    document.getElementById('editGroupForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const form = this;
        const errorsBox = document.getElementById('editGroupErrors');
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
            if (r.redirected) {
                window.location.href = r.url;
                return;
            }
            const data = await r.json().catch(() => null);
            if (data?.errors) {
                const messages = Object.values(data.errors).flat();
                errorsBox.innerHTML = messages.join('<br>');
                errorsBox.classList.remove('hidden');
            } else {
                showToast('Failed to update team.', true);
            }
        })
        .catch(() => showToast('Failed to update team.', true));
    });

    document.getElementById('editAddStudentsBtn').addEventListener('click', () => {
        const sel = document.getElementById('editStudentSelect');
        Array.from(sel.selectedOptions).forEach(o => {
            if (o.value) editRow(o.textContent.split(' (')[0].trim(), o.value, 'programmer');
        });
        sel.selectedIndex = -1;
    });

    document.querySelectorAll('.edit-team-btn').forEach(btn =>
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            const groupId = this.dataset.group;
            if (groupId) window.openEditGroupModal(groupId);
        })
    );

    // classroom
    // Classroom join submits via standard HTML form and redirects back
// ── FILTER GROUPS IN ROOMS (by name, capstone title, section) ──
document.querySelectorAll('.room-group-filter').forEach(input => {
    input.addEventListener('input', function () {
        const roomId = this.dataset.roomId;
        const searchTerm = this.value.toLowerCase().trim();
        const list = document.querySelector(`.room-group-list[data-room-id="${roomId}"]`);
        if (!list) return;
        const items = list.querySelectorAll('.group-item');
        let visibleCount = 0;
        items.forEach(item => {
            const searchData = item.dataset.search || '';
            const match = !searchTerm || searchData.includes(searchTerm);
            item.style.display = match ? 'flex' : 'none';
            if (match) visibleCount++;
        });
        // Show "no results" message if none visible
        let noResult = list.querySelector('.no-result-msg');
        if (visibleCount === 0) {
            if (!noResult) {
                noResult = document.createElement('p');
                noResult.className = 'text-xs text-[#5b6375] col-span-full text-center py-2 no-result-msg';
                noResult.textContent = 'No groups match your filter.';
                list.appendChild(noResult);
            }
            noResult.style.display = 'block';
        } else if (noResult) {
            noResult.style.display = 'none';
        }
    });
});
});

// ── PASSWORD TOGGLE ────────────────────────────
document.querySelectorAll('.password-toggle').forEach(btn => {
    btn.addEventListener('click', function () {
        const targetId = this.dataset.target;
        const input = document.getElementById(targetId);
        if (!input) return;
        const icon = this.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    });
});

// ── PASSWORD STRENGTH METER ─────────────────────
const newPasswordInput = document.getElementById('new_password');
const strengthBar = document.getElementById('password_strength_bar');
if (newPasswordInput && strengthBar) {
    newPasswordInput.addEventListener('input', function () {
        const val = this.value;
        let score = 0;
        if (val.length >= 6) score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val) && /[a-z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { width: '0%', color: '#e8e3d7' },
            { width: '20%', color: '#d9534f' },
            { width: '40%', color: '#e8935a' },
            { width: '60%', color: '#e8c25a' },
            { width: '80%', color: '#9bc27a' },
            { width: '100%', color: '#1e6b3a' }
        ];
        const level = levels[Math.min(score, 5)];
        strengthBar.style.width = val.length ? level.width : '0%';
        strengthBar.style.background = level.color;
    });
}
</script>
</body>
</html>
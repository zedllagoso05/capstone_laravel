<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
    <title>Capstone Tracker | Student Dashboard</title>
    <script src="https://cdn.tailwindcss.com">
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,400;0,500;0,600;0,700;1,400;1,600&family=DM+Sans:ital,opsz,wght@0,9..40,300;0,9..40,400;0,9..40,500;0,9..40,600;0,9..40,700;1,9..40,400&display=swap" rel="stylesheet" />
    <style>
        /* ── ROOT ── */
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

        /* ── CARDS ── */
        .stat-card {
            background: var(--white);
            border-radius: 1.25rem;
            border: 1px solid rgba(214, 177, 92, 0.14);
            box-shadow: var(--shadow-md);
            transition: var(--transition);
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
            position: relative;
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

        .progress-bar-bg {
            background: #e8e3d7;
            border-radius: 999px;
            overflow: hidden;
        }
        .progress-fill {
            border-radius: 999px;
            transition: width 0.6s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .badge {
            padding: 4px 12px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 500;
            letter-spacing: 0.02em;
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
        .btn-primary:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
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
        .btn-outline:hover {
            background: var(--gold);
            color: var(--navy);
            box-shadow: 0 4px 14px rgba(214, 177, 92, 0.3);
        }
        .btn-outline:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            background: transparent;
            color: var(--navy);
            box-shadow: none;
        }

        input,
        select {
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
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
        .form-input {
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
        .form-input:focus {
            border-color: var(--gold);
            box-shadow: 0 0 0 3px var(--gold-glow);
            background: var(--white);
        }
        .form-input:disabled {
            background: #f0ece4;
            color: var(--text-muted);
        }

        .locked-card {
            filter: grayscale(0.35);
            opacity: 0.75;
        }

        /* ── MOBILE BOTTOM NAV ── */
        .mobile-bottom-nav {
            display: none !important;
        }
        @media (max-width: 768px) {
            .mobile-bottom-nav {
                display: flex !important;
                background: rgba(10, 20, 40, 0.96);
                backdrop-filter: blur(12px);
                border-top: 1px solid rgba(214, 177, 92, 0.3);
                box-shadow: 0 -4px 20px rgba(0, 0, 0, 0.15);
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

        h1,
        h2,
        h3,
        h4,
        .serif-heading {
            font-family: 'Cormorant Garamond', serif;
            font-weight: 600;
            letter-spacing: -0.01em;
        }
        h1 {
            font-size: 2rem;
            color: var(--navy);
        }
        h2 {
            font-size: 1.5rem;
            color: var(--navy);
        }
        h3 {
            font-size: 1.2rem;
            color: var(--navy);
        }
        .gold-accent-line {
            width: 50px;
            height: 3px;
            background: linear-gradient(90deg, var(--gold), var(--gold-dark));
            border-radius: 3px;
            margin-top: 0.4rem;
        }

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
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
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
            border-bottom: 1px solid rgba(226, 218, 207, 0.6);
        }
        .info-row:last-child {
            border-bottom: none;
        }
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
        .security-note li {
            display: flex;
            gap: 0.5rem;
            align-items: flex-start;
        }
        .security-note li i {
            color: var(--gold-dark);
            margin-top: 0.2rem;
            font-size: 0.75rem;
        }
        #password_strength_bar {
            transition: width 0.3s ease, background 0.3s ease;
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
        .toast-content .toast-message {
            font-size: 0.9rem;
            color: var(--text);
            font-weight: 500;
            flex: 1;
        }
        .toast-content .toast-close {
            background: transparent;
            border: none;
            color: var(--text-muted);
            cursor: pointer;
            font-size: 1.2rem;
            transition: color 0.2s;
        }
        .toast-content .toast-close:hover {
            color: var(--navy);
        }

        /* ── MILESTONE CARD (for reference) ── */
        .milestone-card {
            background: var(--white);
            border-radius: 0.75rem;
            border: 1px solid var(--border);
            padding: 1rem 1.25rem;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-sm);
        }
        .milestone-card:hover {
            border-color: var(--gold);
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }
        .milestone-card .status-icon {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            font-weight: 700;
            flex-shrink: 0;
        }
        .milestone-card .status-icon.completed {
            background: #e6f4ea;
            color: #1e6b3a;
            border: 2px solid #1e6b3a;
        }
        .milestone-card .status-icon.next {
            background: linear-gradient(135deg, var(--gold), var(--gold-dark));
            color: white;
            border: none;
            box-shadow: 0 4px 12px rgba(214, 177, 92, 0.3);
        }
        .milestone-card .status-icon.pending {
            background: #f0ece4;
            color: #b8b0a0;
            border: 2px solid #d6cfc2;
        }
        .milestone-card .milestone-info {
            flex: 1;
            margin: 0 1rem;
        }
        .milestone-card .milestone-info h5 {
            font-size: 0.9rem;
            font-weight: 600;
            color: var(--text);
            margin: 0;
        }
        .milestone-card .milestone-info p {
            font-size: 0.75rem;
            color: var(--text-muted);
            margin: 0.2rem 0 0;
        }
        .milestone-card .badge-next {
            background: var(--gold);
            color: var(--navy);
            font-weight: 600;
            font-size: 0.7rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            letter-spacing: 0.03em;
        }
        .next-milestone-box {
            background: linear-gradient(135deg, #faf8f4 0%, #f5f0e8 100%);
            border: 1px solid var(--gold);
            border-radius: 0.85rem;
            padding: 1.25rem;
            box-shadow: 0 8px 24px rgba(214, 177, 92, 0.12);
        }
        .next-milestone-box .icon-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--gold);
            color: var(--navy);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            margin-bottom: 0.75rem;
        }
        .next-milestone-box .icon-badge i {
            font-size: 0.8rem;
        }
        .next-milestone-box h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--navy);
            margin: 0 0 0.25rem;
        }
        .next-milestone-box p {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin: 0 0 0.75rem;
        }
        .next-milestone-box .dates {
            display: flex;
            flex-wrap: wrap;
            gap: 1.25rem;
            font-size: 0.8rem;
            color: var(--gold-dark);
        }
        .next-milestone-box .dates span {
            display: flex;
            align-items: center;
            gap: 0.3rem;
        }
        .eval-item {
            background: var(--white);
            border-radius: 0.65rem;
            border: 1px solid var(--border);
            padding: 0.75rem 1rem;
            transition: all 0.2s ease;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .eval-item:hover {
            border-color: var(--gold);
            box-shadow: var(--shadow-sm);
        }
        .eval-item .eval-meta {
            font-size: 0.8rem;
            color: var(--text-muted);
        }
        .eval-item .eval-score {
            font-weight: 700;
            color: #1e6b3a;
            background: #e6f4ea;
            padding: 0.2rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.8rem;
        }
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

        /* ── TIMELINE TABLE ── */
        .timeline-wrapper {
            background: var(--white);
            border-radius: 1rem;
            border: 1px solid var(--border);
            box-shadow: var(--shadow-sm);
            overflow: hidden;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .timeline-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.82rem;
            table-layout: fixed;
        }

        .timeline-table th,
        .timeline-table td {
            padding: 0.85rem 1rem;
            vertical-align: top;
            text-align: left;
            border-bottom: 1px solid rgba(226, 218, 207, 0.6);
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        .timeline-table thead th {
            background: linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);
            color: var(--gold-light);
            text-align: left;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-size: 0.68rem;
            padding: 0.9rem 1rem;
            border-bottom: none;
            position: sticky;
            top: 0;
            z-index: 2;
        }

        /* column widths */
        .timeline-table td:first-child {
            white-space: nowrap;
            font-weight: 600;
            color: var(--navy);
            width: 16%;
            font-size: 0.75rem;
        }
        .timeline-table td:nth-child(2) {
            width: 44%;
        }
        .timeline-table td:nth-child(3) {
            width: 40%;
        }

        .timeline-table tbody tr:last-child td {
            border-bottom: none;
        }
        .timeline-table tbody tr.milestone-row {
            transition: background 0.15s ease;
        }
        .timeline-table tbody tr.milestone-row:hover td {
            background: rgba(214, 177, 92, 0.05);
        }
        .timeline-table tr.row-completed td {
            background: #f4faf6;
        }
        .timeline-table tr.row-completed:hover td {
            background: #eef7f1;
        }
        .timeline-table tr.row-next td {
            background: #fdf9ef;
        }
        .timeline-table tr.row-next:hover td {
            background: #fbf3de;
        }

        /* Stage divider */
        .timeline-table .stage-divider td {
            background: linear-gradient(90deg, var(--gold-dark) 0%, var(--gold) 100%);
            color: var(--navy);
            padding: 0;
            border-bottom: none;
        }
        .stage-divider-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.65rem 1.1rem;
            cursor: pointer;
            user-select: none;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            font-size: 0.75rem;
        }
        .stage-divider-inner span {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .stage-divider-inner .stage-count {
            font-weight: 500;
            text-transform: none;
            letter-spacing: normal;
            font-size: 0.68rem;
            opacity: 0.85;
            margin-left: 0.4rem;
        }
        .stage-toggle-btn {
            background: rgba(10, 20, 40, 0.12);
            border: none;
            color: var(--navy);
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: background 0.2s ease, transform 0.25s ease;
        }
        .stage-divider-inner:hover .stage-toggle-btn {
            background: rgba(10, 20, 40, 0.22);
        }
        .stage-toggle-btn i {
            transition: transform 0.25s cubic-bezier(0.22, 1, 0.36, 1);
        }
        .stage-toggle-btn.collapsed i {
            transform: rotate(180deg);
        }

        .task-title {
            font-weight: 600;
            color: var(--text);
            display: block;
            margin-bottom: 0.25rem;
            font-size: 0.83rem;
        }
        .task-desc {
            font-size: 0.74rem;
            color: var(--text-muted);
            display: block;
            line-height: 1.45;
        }
        .task-status {
            display: inline-block;
            margin-top: 0.5rem;
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.2rem 0.65rem;
            border-radius: 9999px;
        }
        .task-status.completed {
            background: #e6f4ea;
            color: #1e6b3a;
            border: 1px solid #b7dfc5;
        }
        .task-status.next {
            background: var(--gold);
            color: var(--navy);
        }
        .task-status.pending {
            background: #f0ece4;
            color: #8b8477;
            border: 1px solid var(--border);
        }

        .remark-summary {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        .remark-status-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.25rem 0.7rem;
            border-radius: 9999px;
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            width: fit-content;
        }
        .remark-status-badge.on-time {
            background: #e6f4ea;
            color: #1e6b3a;
            border: 1px solid #b7dfc5;
        }
        .remark-status-badge.late {
            background: #fbe9e7;
            color: #a12b2b;
            border: 1px solid #f3c1ba;
        }
        .remark-status-badge.early {
            background: #eaf1fb;
            color: #1e4e8b;
            border: 1px solid #c1d6f3;
        }
        .remark-deduction {
            font-size: 0.7rem;
            color: #a12b2b;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .remark-attendance {
            font-size: 0.76rem;
            color: var(--text);
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }
        .remark-attendance i {
            color: var(--gold-dark);
        }
        .absence-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0.4rem;
            font-size: 0.7rem;
            border: 1px solid var(--border);
            border-radius: 0.5rem;
            overflow: hidden;
        }
        .absence-table thead th {
            background: #faf2df;
            color: var(--gold-dark);
            text-transform: uppercase;
            letter-spacing: 0.03em;
            font-size: 0.62rem;
            font-weight: 700;
            padding: 0.4rem 0.6rem;
            text-align: left;
            border-bottom: 1px solid var(--border);
        }
        .absence-table td {
            padding: 0.4rem 0.6rem;
            border-bottom: 1px solid rgba(226, 218, 207, 0.5);
            color: var(--text);
        }
        .absence-table tr:last-child td {
            border-bottom: none;
        }
        .absence-table td:first-child {
            width: 1.75rem;
            color: var(--text-muted);
            font-weight: 600;
        }
        .remark-feedback {
            background: #faf8f4;
            border: 1px dashed var(--border);
            border-radius: 0.5rem;
            padding: 0.5rem 0.65rem;
            margin-top: 0.4rem;
            font-style: italic;
            font-size: 0.74rem;
            color: var(--text);
            line-height: 1.4;
        }
        .remark-empty {
            color: #b8b0a0;
            font-style: italic;
            font-size: 0.75rem;
        }

        /* ── MOBILE RESPONSIVE OVERRIDES ── */
        @media (max-width: 768px) {
            .timeline-wrapper {
                border-radius: 0.75rem;
                overflow-x: auto;
                -webkit-overflow-scrolling: touch;
            }

            .timeline-table {
                font-size: 0.7rem;
                table-layout: fixed;
                min-width: 0; /* allow shrinking */
                width: 100%;
            }

            .timeline-table th,
            .timeline-table td {
                padding: 0.4rem 0.4rem;
            }

            .timeline-table thead th {
                font-size: 0.55rem;
                padding: 0.4rem 0.4rem;
            }

            /* narrower columns to fit all three */
            .timeline-table td:first-child {
                width: 18%;
                min-width: 60px;
                font-size: 0.6rem;
                white-space: nowrap;
            }
            .timeline-table td:nth-child(2) {
                width: 40%;
                min-width: 100px;
            }
            .timeline-table td:nth-child(3) {
                width: 42%;
                min-width: 80px;
            }

            .stage-divider-inner {
                font-size: 0.6rem;
                padding: 0.4rem 0.5rem;
            }
            .stage-divider-inner .stage-count {
                font-size: 0.55rem;
            }
            .task-title {
                font-size: 0.7rem;
            }
            .task-desc {
                font-size: 0.6rem;
            }
            .task-status {
                font-size: 0.5rem;
                padding: 0.1rem 0.4rem;
                margin-top: 0.2rem;
            }
            .remark-status-badge {
                font-size: 0.55rem;
                padding: 0.1rem 0.4rem;
            }
            .remark-deduction {
                font-size: 0.55rem;
            }
            .remark-attendance {
                font-size: 0.6rem;
            }
            .absence-table {
                font-size: 0.5rem;
            }
            .absence-table thead th {
                font-size: 0.45rem;
                padding: 0.2rem 0.3rem;
            }
            .absence-table td {
                padding: 0.2rem 0.3rem;
            }
            .remark-feedback {
                font-size: 0.55rem;
                padding: 0.2rem 0.3rem;
            }
            .remark-empty {
                font-size: 0.55rem;
            }
        }

        @media (max-width: 480px) {
            .timeline-table {
                font-size: 0.6rem;
                min-width: 0;
            }
            .timeline-table th,
            .timeline-table td {
                padding: 0.25rem 0.25rem;
            }
            .timeline-table td:first-child {
                min-width: 50px;
                font-size: 0.5rem;
            }
            .timeline-table td:nth-child(2) {
                min-width: 80px;
            }
            .timeline-table td:nth-child(3) {
                min-width: 70px;
            }
            .task-title {
                font-size: 0.6rem;
            }
            .task-desc {
                font-size: 0.5rem;
            }
            .task-status {
                font-size: 0.45rem;
                padding: 0.05rem 0.3rem;
            }
            .remark-status-badge {
                font-size: 0.45rem;
                padding: 0.05rem 0.3rem;
            }
            .remark-deduction {
                font-size: 0.45rem;
            }
            .remark-attendance {
                font-size: 0.5rem;
            }
            .remark-empty {
                font-size: 0.5rem;
            }
            /* hide detailed tables on very small screens to save space */
            .absence-table,
            .remark-feedback {
                display: none !important;
            }
        }
    </style>
</head>
<body class="bg-[#f8f6f0] text-[#171e2c]">

    <!-- ═══════════════ TOAST ═══════════════ -->
    <div id="toast" class="toast-container">
        <div class="toast-content">
            <i class="fas fa-check-circle"></i>
            <span id="toastMessage" class="toast-message">Operation successful!</span>
            <button type="button" class="toast-close" onclick="hideToast()">&times;</button>
        </div>
    </div>

    <!-- ═══════════════ SIDEBAR (DESKTOP) ═══════════════ -->
    <aside class="desktop-sidebar fixed left-0 top-0 h-full w-64 flex flex-col justify-between z-20 hidden md:flex">
        <div>
            <div class="p-6 flex items-center space-x-3 border-b border-[rgba(214,177,92,0.15)]">
                <div class="w-9 h-9 rounded-xl flex items-center justify-center shadow-md" style="background:linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);">
                    <i class="fas fa-graduation-cap text-white text-sm"></i>
                </div>
                <div>
                    <h1 class="text-sm font-bold tracking-wide text-white" style="font-family:'DM Sans',sans-serif;">Capstone Tracker</h1>
                    <p class="text-[10px] uppercase tracking-widest font-semibold" style="color:var(--gold-light);">Student</p>
                </div>
            </div>
            <nav class="mt-6 px-4 space-y-1">
                <a href="#" data-section="dashboard" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                    <i class="fas fa-th-large w-4"></i> <span>Dashboard</span>
                </a>
                <a href="#" data-section="profile" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                    <i class="fa-regular fa-user w-4"></i> <span>Profile</span>
                </a>
                <a href="#" data-section="certificates" class="nav-link flex items-center space-x-3 px-4 py-3 text-sm font-medium text-[rgba(255,255,255,0.65)]">
                    <i class="fa-regular fa-file-lines w-4"></i> <span>Certificates</span>
                </a>
            </nav>
        </div>
        <div class="p-4 border-t border-[rgba(214,177,92,0.15)]">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-lg" style="background:linear-gradient(135deg, var(--gold) 0%, var(--gold-dark) 100%);">
                    {{ strtoupper(substr($user->name ?? '', 0, 2)) }}
                </div>
                <div>
                    <p class="text-sm font-medium text-white">{{ $user->name ?? 'User' }}</p>
                    <p class="text-[11px]" style="color:rgba(255,255,255,0.5);">
                        {{ $student?->course ?? 'not assigned' }}-{{ $student?->section ?? 'not assigned' }} | {{$groups?->group_name ?? 'no group'}} #{{ $groups?->id ?? '—' }}
                    </p>
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

    <!-- ═══════════════ MOBILE BOTTOM NAV ═══════════════ -->
    <div class="mobile-bottom-nav fixed bottom-0 left-0 right-0 py-2 px-2 flex justify-around items-center z-30">
        <a href="#" data-section="dashboard" class="mobile-nav-link flex flex-col items-center text-[rgba(255,255,255,0.55)] text-xs py-1">
            <i class="fas fa-th-large text-lg"></i><span class="text-[10px] mt-1">Home</span>
        </a>
        <a href="#" data-section="profile" class="mobile-nav-link flex flex-col items-center text-[rgba(255,255,255,0.55)] text-xs py-1">
            <i class="fa-regular fa-user text-lg"></i><span class="text-[10px] mt-1">Profile</span>
        </a>
        <a href="#" data-section="certificates" class="mobile-nav-link flex flex-col items-center text-[rgba(255,255,255,0.55)] text-xs py-1">
            <i class="fa-regular fa-file-lines text-lg"></i><span class="text-[10px] mt-1">Docs</span>
        </a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="mobile-nav-link flex flex-col items-center text-red-400 hover:text-red-300 text-xs py-1">
            <i class="fas fa-sign-out-alt text-lg"></i><span class="text-[10px] mt-1">Sign Out</span>
        </a>
    </div>

    <!-- ═══════════════ MAIN CONTENT ═══════════════ -->
    <main class="ml-0 md:ml-64 p-4 md:p-8 overflow-y-auto max-h-screen pb-20">

        <!-- ═══════ DASHBOARD ═══════ -->
        <div id="dashboard-section" class="section-container section-card max-w-7xl mx-auto">
            <div class="mb-8">
                <h1>Student Dashboard</h1>
                <div class="gold-accent-line"></div>
                <p class="text-[#5b6375] mt-2 text-sm">Welcome back, {{ $user->name ?? 'User' }}. Here's your capstone progress.</p>
            </div>

            @if($groups)
            <!-- Team Card -->
            <div class="content-card mb-8">
                <div class="card-accent"></div>
                <div class="p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                    <div class="flex flex-col space-y-3">
                        <div class="flex items-center space-x-3">
                            <div class="icon-circle text-[#d6b15c]">
                                {{ strtoupper(substr($groups->group_name, 0, 2)) }}
                            </div>
                            <div>
                                <h2 class="flex items-center gap-2">
                                    {{ $groups->group_name ?? 'Group' }}
                                    <span class="badge badge-gold">#{{ $groups->id ?? '—' }}</span>
                                </h2>
                                <p class="text-sm text-[#3d4450]">{{ $groups->capstone_title ?? '—' }}</p>
                                <p class="text-xs text-[#5b6375] mt-1">Adviser: {{ $adviser?->teacher_last_name . ', ' . $adviser?->teacher_first_name ?? 'Not assigned' }}</p>
                                @if($groups->room)
                                <p class="text-xs text-[#8b6914] mt-1.5 font-medium flex items-center gap-1.5">
                                    <i class="fas fa-door-open"></i> Presentation Room: <strong>{{ $groups->room->room_name }}</strong>
                                </p>
                                @else
                                <p class="text-xs text-[#5b6375] mt-1.5 italic">
                                    Presentation Room: Not assigned yet
                                </p>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2">
                            <p class="text-xs text-[#5b6375] font-semibold mb-2 uppercase tracking-wide">Group Members</p>
                            <div class="flex flex-wrap gap-4">
                                @if($members->isNotEmpty())
                                @foreach($members as $member)
                                <div class="flex items-center space-x-2">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-[10px] font-bold text-white" style="background:linear-gradient(135deg, var(--navy) 0%, #1e3a5f 100%);">
                                        @if($member->student && $member->student->user)
                                        {{ strtoupper(substr($member->student->student_first_name, 0, 1)) }} {{ strtoupper(substr($member->student->student_last_name, 0, 1)) }}
                                        @else
                                        ?
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-medium text-[#0a1428]">
                                            {{ $member->student->student_first_name ?? '—' }}
                                        </p>
                                        <p class="text-[9px] text-[#5b6375]">{{ $member->role }}</p>
                                    </div>
                                </div>
                                @endforeach
                                @else
                                <p class="text-[#5b6375] text-xs">No members found.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-[#5b6375]">Overall Progress</p>
                        <h2 class="text-4xl font-bold" style="color:var(--gold-dark); font-family:'Cormorant Garamond',serif;">{{ $overallProgress ?? '0' }}%</h2>
                    </div>
                </div>
            </div>

            <!-- Progress + Sidebar -->
            <div class="flex flex-col lg:flex-row gap-6">
                <!-- Left: Full progress with stages -->
                <div class="flex-1 min-w-0">
                    <div class="flex items-center justify-between mb-4">
                        <h3>Capstone Progress</h3>
                        <span class="text-xs font-medium text-[#5b6375] bg-[#f0ece4] px-3 py-1 rounded-full">
                            {{ $overallProgress ?? 0 }}% complete
                        </span>
                    </div>
                    <div class="progress-bar-bg h-2.5 w-full mb-6">
                        <div class="progress-fill h-full" style="width: {{ $overallProgress ?? '0' }}%; background: var(--gold);"></div>
                    </div>

                    <!-- ═══ TIMELINE TABLE ═══ -->
                    <div class="timeline-wrapper">
                        <table class="timeline-table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Tasks / Requirements</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $currentStage = null;
                                $stageCounts = $milestones->countBy('capstone_stage_id');
                                @endphp
                                @foreach($milestones as $milestone)
                                @php
                                $isCompleted = in_array($milestone->id, $completedMilestoneIds);
                                $isNext = $milestone->id === $nextMilestone?->id && !$isCompleted;
                                $remarks = $remarksByMilestone->get($milestone->id);
                                $absentStudents = $absencesByMilestone->get($milestone->id, collect());
                                $absentNames = $absentStudents->map(function($absence) {
                                $student = \App\Models\Student::where('user_id', $absence->user_id)->first();
                                return $student ? trim($student->student_first_name.' '.$student->student_last_name) : $absence->user_id;
                                })->filter()->values();
                                @endphp

                                @if ($milestone->capstone_stage_id != $currentStage)
                                @php $currentStage = $milestone->capstone_stage_id; @endphp
                                <tr class="stage-divider" data-stage-toggle="{{ $currentStage }}">
                                    <td colspan="3">
                                        <div class="stage-divider-inner">
                                            <span>
                                                <i class="fa-solid fa-flag"></i> {{ $milestone->capstoneStage->stage_title ?? 'Capstone' }}
                                                <span class="stage-count">({{ $stageCounts[$currentStage] ?? 0 }} milestones)</span>
                                            </span>
                                            <button type="button" class="stage-toggle-btn" aria-label="Toggle {{ $milestone->capstoneStage->stage_title ?? 'Capstone' }}">
                                                <i class="fa-solid fa-chevron-up"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @endif

                                <tr class="milestone-row {{ $isCompleted ? 'row-completed' : ($isNext ? 'row-next' : '') }}" data-stage="{{ $milestone->capstone_stage_id }}">
                                    <!-- Date -->
                                    <td>
                                        {{ $milestone->start_date ? \Carbon\Carbon::parse($milestone->start_date)->format('M d, Y') : '—' }}
                                        @if($milestone->due_date)
                                        – {{ \Carbon\Carbon::parse($milestone->due_date)->format('M d, Y') }}
                                        @endif
                                    </td>

                                    <!-- Tasks -->
                                    <td>
                                        <span class="task-title">{{ $milestone->milestone_title }}</span>
                                        @if($milestone->milestone_description)
                                        <span class="task-desc">{{ $milestone->milestone_description }}</span>
                                        @endif

                                        @if($isCompleted)
                                        <span class="task-status completed">
                                            Completed
                                            @php
                                            $gm = $groups?->groupMilestones->firstWhere('milestone_id', $milestone->id);
                                            @endphp
                                            @if($gm && $gm->completion_date)
                                            {{ \Carbon\Carbon::parse($gm->completion_date)->format('M d, Y') }}
                                            @endif
                                        </span>
                                        @elseif($isNext)
                                        <span class="task-status next">Next Step</span>
                                        @else
                                        <span class="task-status pending">Pending</span>
                                        @endif
                                    </td>

                                    <!-- Remarks -->
                                    <td>
                                        @if($remarks)
                                        @php
                                        $statusText = $remarks->remarks ?? ($remarks->compiled ? 'On Time Compliance' : 'Late Submission');
                                        $statusClass = stripos($statusText, 'late') !== false ? 'late' :
                                        (stripos($statusText, 'early') !== false ? 'early' : 'on-time');
                                        $statusIcon = $statusClass === 'late' ? 'fa-triangle-exclamation' :
                                        ($statusClass === 'early' ? 'fa-clock' : 'fa-circle-check');
                                        @endphp
                                        <div class="remark-summary">
                                            <span class="remark-status-badge {{ $statusClass }}">
                                                <i class="fa-solid {{ $statusIcon }}"></i> {{ $statusText }}
                                            </span>
                                            @if($remarks->deduction_points)
                                            <span class="remark-deduction">
                                                <i class="fa-solid fa-minus"></i> {{ $remarks->deduction_points }} pts deduction
                                            </span>
                                            @endif
                                            <span class="remark-attendance">
                                                <i class="fa-solid fa-user-group"></i>
                                                {{ $remarks->all_present ? 'All members present' : $absentNames->count().' member(s) absent' }}
                                            </span>
                                            @if(!$remarks->all_present && $absentNames->isNotEmpty())
                                            <table class="absence-table">
                                                <thead>
                                                    <tr><th>#</th><th>Absent Student</th></tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($absentNames as $i => $name)
                                                    <tr>
                                                        <td>{{ $i + 1 }}</td>
                                                        <td>{{ $name }}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                            @endif
                                            @if($remarks->feedback)
                                            <div class="remark-feedback">"{{ $remarks->feedback }}"</div>
                                            @endif
                                        </div>
                                        @else
                                        <span class="remark-empty">No remarks yet</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- ═══ END TIMELINE TABLE ═══ -->
                </div>

                <!-- Right sidebar -->
                <div class="w-full lg:w-80 space-y-4 flex-shrink-0">
                    @if($nextMilestone)
                    <div class="next-milestone-box">
                        <div class="icon-badge">
                            <i class="fa-regular fa-compass"></i> Next Step
                        </div>
                        <h3>{{ $nextMilestone->milestone_title }}</h3>
                        <p>{{ $nextMilestone->milestone_description }}</p>
                        <div class="dates">
                            <span><i class="fa-regular fa-calendar mr-1"></i> Start: {{ $nextMilestone->start_date ? \Carbon\Carbon::parse($nextMilestone->start_date)->format('M d, Y') : '—' }}</span>
                            <span><i class="fa-regular fa-calendar-check mr-1"></i> Due: {{ $nextMilestone->due_date ? \Carbon\Carbon::parse($nextMilestone->due_date)->format('M d, Y') : '—' }}</span>
                        </div>
                    </div>
                    @endif

                    <div class="bg-white rounded-xl border border-[#e2dacf] shadow-sm overflow-hidden">
                        <div class="px-5 py-4 border-b border-[#e2dacf] flex items-center justify-between">
                            <h4 class="text-sm font-bold text-[#0a1428] flex items-center gap-2">
                                <i class="fa-regular fa-star text-[#d6b15c]"></i> Recent Evaluations
                            </h4>
                        </div>
                        <div class="p-4 space-y-3 max-h-72 overflow-y-auto">
                            @forelse($evaluations as $eval)
                            <div class="eval-item">
                                <div>
                                    <p class="text-sm font-medium text-[#0a1428]">{{ $eval->milestone->milestone_title ?? 'Evaluation' }}</p>
                                    <p class="eval-meta">{{ $eval->teacher->user->name ?? 'Teacher' }} • {{ \Carbon\Carbon::parse($eval->evaluation_date)->format('M d, Y') }}</p>
                                </div>
                                <div class="eval-score">{{ $eval->score }}/{{ $eval->max_score }}</div>
                            </div>
                            @empty
                            <p class="text-sm text-[#5b6375] text-center py-4">No evaluations yet.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            @else
            <!-- No Group Assigned -->
            <div class="content-card text-center">
                <div class="card-accent"></div>
                <div class="p-8">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center" style="background:#f0ece4;">
                        <i class="fa-regular fa-users text-2xl text-[#b8b0a0]"></i>
                    </div>
                    <h2 class="mb-2">Not in a Group</h2>
                    <p class="text-[#5b6375] text-sm max-w-md mx-auto">
                        You haven't been assigned to a capstone group yet. Please contact your adviser or capstone coordinator.
                    </p>
                </div>
            </div>
            @endif
        </div>

        <!-- ═══════ PROFILE ═══════ -->
        <div id="profile-section" class="section-container hidden section-card max-w-7xl mx-auto">
            <div class="mb-8">
                <h1>Profile</h1>
                <div class="gold-accent-line"></div>
                <p class="text-[#5b6375] mt-2 text-sm">Manage your personal information and contact details</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Profile summary -->
                <div class="content-card lg:col-span-1 overflow-hidden">
                    <div class="profile-banner"></div>
                    <div class="p-6 -mt-12 flex flex-col items-center text-center">
                        <div class="profile-avatar-ring">
                            <div class="profile-avatar-inner">
                                {{ strtoupper(substr($student->student_first_name, 0, 1)) }}{{ strtoupper(substr($student->student_last_name, 0, 1)) }}
                            </div>
                        </div>
                        <h2 class="mt-4">{{ $student->student_first_name }} {{ $student->student_middle_name }} {{ $student->student_last_name }}</h2>
                        <p class="text-[#5b6375] text-xs font-medium tracking-wide uppercase mt-0.5">{{ $student->course ?? '—' }}-{{ $student->section ?? '—' }}</p>
                        <p class="text-[#5b6375] text-sm mt-1">ID: {{ $student->user_id ?? $user->user_id }}</p>

                        <div class="mt-4 flex flex-wrap gap-2 justify-center">
                            <span class="badge badge-gold"><i class="fa-solid fa-layer-group mr-1"></i>{{ $groups?->group_name ?? 'No Group' }} (#{{ $groups?->id ?? '—' }})</span>
                        </div>

                        <div class="mt-5 w-full pt-5 border-t border-[#e2dacf] space-y-1">
                            <div class="info-row">
                                <div class="info-icon"><i class="fa-solid fa-phone"></i></div>
                                <div class="text-left">
                                    <p class="info-label">Contact</p>
                                    <p class="info-value">{{ $student->contact_number ?? 'Not provided' }}</p>
                                </div>
                            </div>
                            <div class="info-row">
                                <div class="info-icon"><i class="fa-regular fa-envelope"></i></div>
                                <div class="text-left">
                                    <p class="info-label">Email</p>
                                    <p class="info-value break-all">{{ $user->email ?? 'Not provided' }}</p>
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
                        <form id="profileForm" class="space-y-6" action="{{ route('student.profile.update') }}" method="POST">
                            @csrf

                            <div>
                                <p class="form-fieldset-title"><i class="fa-regular fa-user"></i> Personal Information</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label">First Name</label>
                                        <input type="text" name="first_name" value="{{ old('first_name',  $student->student_first_name) }}" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Last Name</label>
                                        <input type="text" name="last_name" value="{{ old('last_name',  $student->student_last_name) }}" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Student ID</label>
                                        <input type="text" disabled value="{{ $user->user_id ?? $student->user_id }}" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Group</label>
                                        <input type="text" disabled value="{{ $groups->group_name ?? '—' }} (#{{ $groups->id ?? '—' }})" class="form-input">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <p class="form-fieldset-title"><i class="fa-regular fa-address-card"></i> Contact Details</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label">Email</label>
                                        <input type="email" name="email" value="{{ old('email', $student->student_email) }}" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Phone</label>
                                        <input type="text" name="phone" value="{{ old('phone', $student->contact_number ?? '') }}" class="form-input" placeholder="e.g. 09XX XXX XXXX">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <p class="form-fieldset-title"><i class="fa-regular fa-id-card"></i> Academic Information</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="form-label">Course</label>
                                        <input type="text" disabled value="{{ $student->course ?? '—' }}" class="form-input">
                                    </div>
                                    <div>
                                        <label class="form-label">Section</label>
                                        <input type="text" disabled value="{{ $student->section ?? '—' }}" class="form-input">
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between pt-2 border-t border-[#e2dacf]">
                                @if(session('success'))
                                <p class="text-xs" style="color:#1e6b3a;"><i class="fa-regular fa-circle-check mr-1"></i>{{ session('success') }}</p>
                                @else
                                <span></span>
                                @endif
                                <button type="submit" class="btn-primary">
                                    <i class="fa-regular fa-floppy-disk mr-2"></i>Save Changes
                                </button>
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
                        <h3 class="mb-5 flex items-center gap-2">
                            <i class="fa-solid fa-key text-[#d6b15c]"></i> Change Password
                        </h3>
                        <form action="{{ route('student.profile.update_password') }}" method="POST" class="space-y-4" id="passwordForm">
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
                                        <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#5b6375] hover:text-[#0a1428] transition" data-target="current_password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                    @error('current_password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label class="form-label">New Password</label>
                                    <div class="relative">
                                        <input type="password" name="new_password" id="new_password" class="form-input pr-10" placeholder="Min 6 characters" required>
                                        <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#5b6375] hover:text-[#0a1428] transition" data-target="new_password">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                    <div class="mt-2 h-1.5 rounded-full bg-[#e8e3d7] overflow-hidden">
                                        <div id="password_strength_bar" class="h-full w-0 rounded-full"></div>
                                    </div>
                                    @error('new_password')
                                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="md:col-span-2">
                                    <label class="form-label">Confirm New Password</label>
                                    <div class="relative">
                                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-input pr-10" placeholder="Re-enter new password" required>
                                        <button type="button" class="password-toggle absolute right-3 top-1/2 -translate-y-1/2 text-[#5b6375] hover:text-[#0a1428] transition" data-target="new_password_confirmation">
                                            <i class="fa-regular fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="flex justify-end pt-2 border-t border-[#e2dacf]">
                                <button type="submit" class="btn-primary">
                                    <i class="fa-solid fa-floppy-disk mr-2"></i>Update Password
                                </button>
                            </div>
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
                                <li><i class="fa-solid fa-circle-check"></i> Never share your login details with classmates.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ═══════ CERTIFICATES ═══════ -->
        <div id="certificates-section" class="section-container hidden section-card max-w-7xl mx-auto">
            <div class="mb-8">
                <h1>Certificates & Documents</h1>
                <div class="gold-accent-line"></div>
                <p class="text-[#5b6375] mt-2 text-sm">Download certificates, evaluation results, and progress reports</p>
            </div>
            <div class="flex gap-3 mb-6 text-sm">
                @php
                $availableCount = $certificates->where('unlocked', true)->count();
                @endphp
                <span class="badge badge-green">
                    Available: <strong>{{ $availableCount }}/{{ $certificates->count() }}</strong>
                </span>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($certificates as $cert)
                <div class="content-card {{ !$cert->unlocked ? 'locked-card' : '' }}">
                    <div class="card-accent"></div>
                    <div class="p-5">
                        @if(!$cert->unlocked)
                        <div class="absolute top-3 right-3 badge badge-muted">
                            <i class="fa-solid fa-lock"></i> Locked
                        </div>
                        @endif

                        <h3 class="mt-3 text-lg {{ !$cert->unlocked ? 'text-[#5b6375]' : 'text-[#0a1428]' }}">{{ $cert->certificate_title }}</h3>
                        <p class="text-[#5b6375] text-xs my-2">{{ $cert->certificate_description }}</p>
                        @if($cert->completion_date)
                        <p class="text-[11px] text-[#5b6375]"><i class="fa-regular fa-calendar"></i> {{ \Carbon\Carbon::parse($cert->issued_date)->format('M d, Y') }}</p>
                        @elseif($cert->unlocked)
                        <p class="text-xs" style="color:var(--gold-dark);"><i class="fa-regular fa-hourglass-half"></i> Not yet issued</p>
                        @else
                        <p class="text-xs" style="color:var(--gold-dark);"><i class="fa-regular fa-hourglass-half"></i> Not yet available</p>
                        <p class="text-[10px] text-[#5b6375] mt-1">Complete the required milestone to unlock</p>
                        @endif
                        <div class="flex gap-3 mt-4">
                            @if($cert->unlocked && $groups)
                            <a href="{{ route('certificate.show', ['groupId' => $groups->id, 'certificateId' => $cert->id]) }}"
                               target="_blank"
                               class="btn-outline text-xs py-1.5 px-3">
                                <i class="fas fa-print mr-1"></i> Print / Download
                            </a>
                            @else
                            <button class="btn-outline text-xs py-1.5 px-3" disabled>
                                <i class="fas fa-print mr-1"></i> Print / Download
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

    </main>

    <script>
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

        document.addEventListener('DOMContentLoaded', function() {

            // ── Timeline: foldable capstone stages ──
            document.querySelectorAll('.timeline-table .stage-divider').forEach(divider => {
                const stage = divider.dataset.stageToggle;
                const btn = divider.querySelector('.stage-toggle-btn');
                const rows = document.querySelectorAll(`.timeline-table .milestone-row[data-stage="${stage}"]`);

                const toggle = () => {
                    const collapsed = btn.classList.toggle('collapsed');
                    rows.forEach(r => r.style.display = collapsed ? 'none' : '');
                };

                divider.querySelector('.stage-divider-inner').addEventListener('click', toggle);
            });

            // ── SECTION SWITCHING WITH PERSISTENCE ──
            const sections = {
                dashboard: document.getElementById('dashboard-section'),
                profile: document.getElementById('profile-section'),
                certificates: document.getElementById('certificates-section')
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
                    link.style.color = link.dataset.section === sectionId ? 'var(--gold)' :
                        'rgba(255,255,255,0.55)';
                });
                localStorage.setItem('studentActiveSection', sectionId);
            }

            const storedSection = localStorage.getItem('studentActiveSection');
            if (storedSection && sections[storedSection]) {
                activateSection(storedSection);
            } else {
                activateSection('dashboard');
            }

            [...navLinks, ...mobileNavLinks].forEach(link => link.addEventListener('click', e => {
                e.preventDefault();
                const s = link.dataset.section;
                if (s && sections[s]) activateSection(s);
            }));

            // ── SHOW CONFIRMATION TOAST ──
            @if(session('success'))
            showToast('{{ session('success') }}', false);
            @endif

            @if(session('error'))
            showToast('{{ session('error') }}', true);
            @endif

            @if($errors->any())
            showToast('{{ $errors->first() }}', true);
            @endif

            
            // ── PASSWORD TOGGLE ──
            document.querySelectorAll('.password-toggle').forEach(btn => {
                btn.addEventListener('click', function() {
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

            // ── PASSWORD STRENGTH METER ──
            const newPasswordInput = document.getElementById('new_password');
            const strengthBar = document.getElementById('password_strength_bar');
            if (newPasswordInput && strengthBar) {
                newPasswordInput.addEventListener('input', function() {
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
        });
    </script>

</body>
</html>
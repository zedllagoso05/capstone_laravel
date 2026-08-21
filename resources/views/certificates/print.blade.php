<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $certificate->certificate_title }} — {{ $group->group_name }}</title>
    <link rel="stylesheet" href="/css/dashboard.css">
    <script src="/js/app.js" defer></script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'DM Sans', sans-serif; }
        body {
            background: #f2ede2;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }
        .document {
            background: #fffdf8;
            width: 100%;
            max-width: 900px;
            aspect-ratio: 1.414 / 1; /* A4 landscape ratio */
            border: 2px solid #0a1428;
            padding: 3rem 4rem;
            display: flex;
            flex-direction: column;
            text-align: center;
            position: relative;
            box-shadow: 0 8px 24px rgba(0,0,0,0.06);
        }

        /* ── Header image ── */
        .header-image {
            text-align: center;
            margin-bottom: 1rem;
        }
        .header-image img {
            max-width: 60%;
            height: auto;
            display: inline-block;
        }

        /* ── Document title ── */
        .doc-title {
            font-size: 2rem;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: #0a1428;
            border-bottom: 2px solid #0a1428;
            padding-bottom: 0.75rem;
            margin-bottom: 2rem;
            font-family: 'Cormorant Garamond', serif;
        }

        .body-text {
            font-size: 1.05rem;
            line-height: 1.7;
            color: #171e2c;
            max-width: 680px;
            margin: 0 auto;
            flex: 1;
        }

        .body-text .capstone-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.6rem;
            font-weight: 600;
            font-style: italic;
            margin: 0.75rem 0 1rem;
            color: #0a1428;
        }

        .body-text .members-list {
            display: inline;
            font-weight: 600;
            color: #0a1428;
        }
        .body-text .members-list .member {
            display: inline;
        }
        .body-text .members-list .member:not(:last-child)::after {
            content: ", ";
        }
        .body-text .members-list .member:last-child::before {
            content: " and ";
        }

        .body-text .degree {
            font-weight: 600;
            color: #0a1428;
        }

        /* ── Adviser signature block ── */
        .signature-block {
            width: 100%;
            max-width: 400px;
            margin: 2rem auto 0;
        }
        .signature-line {
            border-top: 1.5px solid #0a1428;
            width: 100%;
            margin-bottom: 0.3rem;
        }
        .signature-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #0a1428;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .signature-label {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #5b6375;
        }

        /* ── Footer with date & group ── */
        .footer {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            padding-top: 1.5rem;
            border-top: 1px solid #e2dacf;
        }
        .footer-block { text-align: center; min-width: 160px; }
        .footer-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.05em; color: #9a9385; }
        .footer-value { font-size: 0.85rem; font-weight: 600; color: #171e2c; }

        /* ── Actions ── */
        .actions {
            margin-top: 1.5rem;
            display: flex;
            gap: 0.75rem;
            justify-content: center;
        }
        .btn {
            padding: 0.6rem 1.4rem;
            border-radius: 2rem;
            font-size: 0.85rem;
            font-weight: 500;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }
        .btn-primary { background: #0a1428; color: #f0e0b0; }
        .btn-outline { background: transparent; border: 1.5px solid #d6b15c; color: #0a1428; }

        @media print {
            body { background: #fff; padding: 0; }
            .document { border: 2px solid #0a1428; box-shadow: none; }
            .actions { display: none; }
        }
        @media (max-width: 640px) {
            .document { padding: 2rem 1.5rem; }
            .doc-title { font-size: 1.5rem; }
            .body-text { font-size: 0.95rem; }
            .header-image img { max-width: 80%; }
        }
    </style>
</head>
<body>
    <div>
        <!-- Document -->
        <div class="document">
            <!-- Header image -->
            <div class="header-image">
            <img src="{{ asset('pictures/mccheader.jpg') }}" alt="MCC Header">
            </div>

            <!-- Main title -->
            <h1 class="doc-title">{{ $certificate->certificate_title }}</h1>

            <div class="body-text">
                This <strong>Capstone Project </strong> hereto entitled:
                <div class="capstone-title">"{{ $group->capstone_title }}"</div>
                <br>
                <br>
                <br>
                <br>

                prepared and submitted by
                <span class="members-list">
                    @forelse($members as $member)
                        <span class="member">{{ $member }}</span>
                    @empty
                        <span class="member">{{ $group->group_name }}</span>
                    @endforelse
                </span>
                <br>
                in partial fulfillment of the requirements for the degree of
                <span class="degree">Bachelor of Science in Information Technology</span>
                <br>
                has been examined, accepted, and recommended for Oral Presentation.
            </div>
                <br>
                <br>

            <!-- Adviser signature -->
            <div class="signature-block">
                <div class="signature-name"><u>{{ $adviserName ?? 'Capstone Adviser' }}</u></div>

                <div class="signature-label">Capstone Adviser</div>
            </div>
                <br>
                <br>
            <br>

            <!-- Footer -->
            <div class="footer">
                <div class="footer-block">
                    <div class="footer-value">{{ \Carbon\Carbon::parse($issuedDate)->format('F d, Y') }}</div>
                    <div class="footer-label">Date Issued</div>
                </div>
                <div class="footer-block">
                    <div class="footer-value">{{ $group->group_name }}</div>
                    <div class="footer-label">Group</div>
                </div>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="actions">
            <button class="btn btn-primary" onclick="window.print()">
                🖨️ Print / Save as PDF
            </button>
            <a class="btn btn-outline" href="javascript:history.back()">← Back</a>
        </div>
    </div>
</body>
</html>
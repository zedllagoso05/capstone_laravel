<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $certificate->certificate_title }} — {{ $group->group_name }}</title>
    @vite(['resources/css/dashboard.css', 'resources/js/app.js'])
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
        .certificate {
            background: #fffdf8;
            width: 100%;
            max-width: 900px;
            aspect-ratio: 1.414 / 1; /* A4 landscape ratio */
            border: 3px solid #d6b15c;
            outline: 1px solid #d6b15c;
            outline-offset: -10px;
            padding: 3.5rem 4rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            text-align: center;
            position: relative;
        }
        .seal {
            width: 64px;
            height: 64px;
            border-radius: 50%;
            background: linear-gradient(135deg, #d6b15c, #b88d3a);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #0a1428;
            font-size: 1.5rem;
            font-weight: 700;
            box-shadow: 0 6px 16px rgba(214,177,92,0.35);
            margin-bottom: 1rem;
        }
        .eyebrow {
            font-size: 0.7rem;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            color: #8b6914;
            font-weight: 600;
        }
        h1.title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 2.75rem;
            color: #0a1428;
            font-weight: 700;
            margin: 0.5rem 0 0.25rem;
        }
        .subtitle {
            font-size: 0.85rem;
            color: #5b6375;
            margin-bottom: 1.75rem;
        }
        .presented-to {
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #9a9385;
            margin-bottom: 0.5rem;
        }
        .capstone-title {
            font-family: 'Cormorant Garamond', serif;
            font-size: 1.9rem;
            font-weight: 600;
            color: #0a1428;
            font-style: italic;
            max-width: 640px;
            margin-bottom: 1.5rem;
            line-height: 1.3;
        }
        .members {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 0.5rem 1.75rem;
            margin-bottom: 1.5rem;
        }
        .member {
            font-size: 1.05rem;
            font-weight: 600;
            color: #171e2c;
            padding-bottom: 0.2rem;
            border-bottom: 1.5px solid #d6b15c;
        }
        .description {
            font-size: 0.9rem;
            color: #5b6375;
            max-width: 560px;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .footer {
            width: 100%;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
            margin-top: auto;
            padding-top: 1.5rem;
        }
        .footer-block { text-align: center; min-width: 180px; }
        .footer-line { border-top: 1px solid #171e2c; margin-bottom: 0.3rem; }
        .footer-label { font-size: 0.68rem; text-transform: uppercase; letter-spacing: 0.05em; color: #9a9385; }
        .footer-value { font-size: 0.85rem; font-weight: 600; color: #171e2c; }

        .actions {
            margin-top: 1.5rem;
            display: flex;
            gap: 0.75rem;
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
            .certificate { border: 3px solid #d6b15c; box-shadow: none; }
            .actions { display: none; }
        }
    </style>
</head>
<body>
    <div>
        <div class="certificate">
            <div class="seal"><i>★</i></div>
            <div class="eyebrow">Capstone Tracker</div>
            <h1 class="title">{{ $certificate->certificate_title }}</h1>
            <div class="subtitle">{{ $milestone->milestone_title }}</div>

            <div class="presented-to">This certificate is proudly presented to</div>
            <div class="members">
                @forelse($members as $member)
                    <span class="member">{{ $member }}</span>
                @empty
                    <span class="member">{{ $group->group_name }}</span>
                @endforelse
            </div>

            <div class="presented-to">For successfully completing the capstone project</div>
            <div class="capstone-title">"{{ $group->capstone_title }}"</div>

            <p class="description">{{ $certificate->certificate_description }}</p>

            <div class="footer">
                <div class="footer-block">
                    <div class="footer-line"></div>
                    <div class="footer-value">{{ $adviserName ?? 'Capstone Adviser' }}</div>
                    <div class="footer-label">Adviser</div>
                </div>
                <div class="footer-block">
                    <div class="footer-line"></div>
                    <div class="footer-value">{{ \Carbon\Carbon::parse($issuedDate)->format('F d, Y') }}</div>
                    <div class="footer-label">Date Issued</div>
                </div>
                <div class="footer-block">
                    <div class="footer-line"></div>
                    <div class="footer-value">{{ $group->group_name }}</div>
                    <div class="footer-label">Group</div>
                </div>
            </div>
        </div>

        <div class="actions">
            <button class="btn btn-primary" onclick="window.print()">
                🖨️ Print / Save as PDF
            </button>
            <a class="btn btn-outline" href="javascript:history.back()">← Back</a>
        </div>
    </div>
</body>
</html>
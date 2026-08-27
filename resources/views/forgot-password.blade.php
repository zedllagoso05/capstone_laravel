<x-layout>

<x-slot:styles>
<style>
    /* ─── FORGOT CARD ─────────────────────────────────── */
    .forgot-card {
        width: 100%;
        max-width: 440px;
        background: var(--white);
        border-radius: 16px;
        box-shadow:
            0 2px 8px  rgba(13, 27, 46, 0.06),
            0 8px 32px rgba(13, 27, 46, 0.08);
        overflow: hidden;
        margin: 2rem auto;
        animation: cardIn 0.4s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    .card-accent {
        height: 4px;
        background: linear-gradient(90deg, var(--gold), var(--gold-light));
    }

    .card-body {
        padding: 2.25rem 2rem 2.5rem;
    }

    .card-heading {
        text-align: center;
        margin-bottom: 1.75rem;
    }

    .card-heading h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.9rem;
        font-weight: 600;
        color: var(--navy);
        line-height: 1.2;
        margin-bottom: 0.35rem;
    }

    .card-heading p {
        font-size: 0.78rem;
        color: var(--muted);
    }

    .form-group {
        margin-bottom: 1.25rem;
        text-align: left;
        position: relative;
    }

    .form-group label {
        display: block;
        font-size: 0.72rem;
        color: var(--text-muted);
        margin-bottom: 0.4rem;
        font-weight: 600;
        letter-spacing: 0.05em;
        text-transform: uppercase;
    }

    .form-group input {
        width: 100%;
        background: #faf8f4;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        padding: 0.75rem 1rem;
        font-size: 0.875rem;
        color: var(--text);
        outline: none;
        transition: border-color 0.2s, box-shadow 0.2s;
    }

    .form-group input:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-glow);
        background: var(--white);
    }

    .password-input-wrapper {
        position: relative;
        display: flex;
        align-items: center;
    }

    .password-input-wrapper input {
        padding-right: 2.5rem;
    }

    .password-toggle-btn {
        position: absolute;
        right: 0.875rem;
        background: none;
        border: none;
        color: var(--muted);
        cursor: pointer;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.15s;
    }

    .password-toggle-btn:hover {
        color: var(--navy);
    }

    .btn-submit {
        width: 100%;
        margin-top: 0.5rem;
        padding: 0.75rem 1.5rem;
        background: var(--navy);
        color: var(--gold);
        border: none;
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.875rem;
        font-weight: 500;
        letter-spacing: 0.025em;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
    }

    .btn-submit:hover {
        background: var(--navy-hover);
        transform: translateY(-1px);
    }

    .btn-submit:active { transform: translateY(0); }

    .btn-submit:disabled {
        opacity: 0.55;
        cursor: not-allowed;
        transform: none;
    }

    .btn-outline-small {
        background: transparent;
        border: 1.5px solid var(--gold);
        color: var(--navy);
        padding: 0.55rem 1rem;
        border-radius: 8px;
        font-size: 0.78rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s, color 0.2s;
    }

    .btn-outline-small:hover:not(:disabled) {
        background: var(--gold);
    }

    .btn-outline-small:disabled {
        opacity: 0.55;
        cursor: not-allowed;
    }

    .resend-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 1.25rem;
        gap: 0.75rem;
    }

    .resend-row .sent-to {
        font-size: 0.75rem;
        color: var(--text-muted);
    }

    .error {
        color: #e57373;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
</style>
</x-slot:styles>

<div class="forgot-card">
    <div class="card-accent"></div>
    <div class="card-body">
        <div class="card-heading">
            <h2>Reset Password</h2>
            <p>
                @if(session('reset_code_sent'))
                    Enter the verification code we sent and choose a new password.
                @else
                    Enter the email address on your account to receive a reset code.
                @endif
            </p>
        </div>

        @if(!session('reset_code_sent'))
        {{-- STEP 1: Enter email to request code --}}
        <form action="{{ route('password.email') }}" method="POST" id="sendCodeForm">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="e.g. you@example.com"
                    value="{{ old('email') }}"
                    required
                >
                @error('email')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>
            <button type="submit" class="btn-submit" style="background:transparent;border:1.5px solid var(--gold);color:var(--navy);">
                Send Reset Code
            </button>
        </form>
        @else
        {{-- STEP 2: Verification code + new password (email step hidden) --}}
        <div class="resend-row">
            <span class="sent-to">Code sent to <strong>{{ session('reset_email') }}</strong></span>
            <form action="{{ route('password.email') }}" method="POST" id="resendForm">
                @csrf
                <input type="hidden" name="email" value="{{ session('reset_email') }}">
                <button type="submit" class="btn-outline-small" id="resendBtn" disabled>
                    Resend in <span id="resendCountdown">30</span>s
                </button>
            </form>
        </div>

        @if(session('success'))
        <p style="color:#1e6b3a;font-size:0.75rem;margin-bottom:1rem;">{{ session('success') }}</p>
        @endif

        <form action="{{ route('password.update') }}" method="POST">
            @csrf
            <input type="hidden" name="user_id" value="{{ session('reset_user_id') }}">

            <div class="form-group">
                <label for="reset-code">Reset Verification Code</label>
                <input type="text" id="reset-code" name="code" placeholder="6-digit code" maxlength="6" required>
                @error('code')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <div class="form-group">
                <label for="new-password">New Password</label>
                <div class="password-input-wrapper">
                    <input type="password" id="new-password" name="password" placeholder="Min 6 characters" required autocomplete="new-password">
                    <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('new-password', this)">
                        <i class="fas fa-eye"></i>
                    </button>
                </div>
                @error('password')
                <p class="error">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Reset Password</button>
        </form>
        @endif

        <div style="margin-top:1.5rem; text-align:center;">
            <a href="/" style="color:var(--text-muted); font-size:0.78rem; text-decoration:underline;">
                Back to Sign In
            </a>
        </div>
    </div>
</div>

<x-slot:scripts>
<script>
    function togglePasswordVisibility(inputId, btn) {
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fas fa-eye-slash';
        } else {
            input.type = 'password';
            icon.className = 'fas fa-eye';
        }
    }

    // 30-second resend cooldown
    @if(session('reset_code_sent'))
    (function () {
        const resendBtn = document.getElementById('resendBtn');
        const countdownEl = document.getElementById('resendCountdown');
        if (!resendBtn || !countdownEl) return;

        let seconds = 30;
        resendBtn.disabled = true;

        const timer = setInterval(() => {
            seconds--;
            if (seconds <= 0) {
                clearInterval(timer);
                resendBtn.disabled = false;
                resendBtn.textContent = 'Resend Code';
            } else {
                countdownEl.textContent = seconds;
            }
        }, 1000);
    })();
    @endif
</script>
</x-slot:scripts>

</x-layout>
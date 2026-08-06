<x-layout>

<x-slot:styles>
<style>
    /* ─── AUTH CARD ─────────────────────────────────── */
    .auth-card {
        width: 100%;
        max-width: 440px;
        background: var(--white);
        border-radius: 16px;
        box-shadow:
            0 2px 8px  rgba(13, 27, 46, 0.06),
            0 8px 32px rgba(13, 27, 46, 0.08);
        overflow: hidden;
        animation: cardIn 0.4s cubic-bezier(0.22, 1, 0.36, 1) both;
    }

    @keyframes cardIn {
        from { opacity: 0; transform: translateY(18px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Gold accent stripe */
    .card-accent {
        height: 4px;
        background: linear-gradient(90deg, var(--gold), var(--gold-light));
    }

    .card-body {
        padding: 2.25rem 2rem 2.5rem;
    }

    /* ─── HEADING ─────────────────────────────────── */
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
        font-weight: 300;
    }

    /* ─── TAB SWITCHER ────────────────────────────── */
    .tab-switch {
        display: flex;
        background: #eeebe3;
        border-radius: 10px;
        padding: 4px;
        margin-bottom: 1.75rem;
    }

    .tab-btn {
        flex: 1;
        padding: 0.55rem 1rem;
        border: none;
        border-radius: 7px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.8rem;
        font-weight: 500;
        cursor: pointer;
        background: transparent;
        color: var(--muted);
        transition: background 0.2s, color 0.2s, box-shadow 0.2s;
    }

    .tab-btn.active {
        background: var(--white);
        color: var(--navy);
        box-shadow: 0 1px 4px rgba(13, 27, 46, 0.1);
    }

    /* ─── TAB PANES ───────────────────────────────── */
    .tab-pane          { display: none; }
    .tab-pane.active   { display: block; animation: paneIn 0.22s ease both; }

    @keyframes paneIn {
        from { opacity: 0; transform: translateY(5px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* ─── FORM ELEMENTS ───────────────────────────── */
    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        font-size: 0.68rem;
        font-weight: 500;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        color: var(--muted);
        margin-bottom: 0.4rem;
    }

    .form-group input {
        width: 100%;
        padding: 0.65rem 0.875rem;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.875rem;
        color: var(--text);
        background: #faf8f4;
        outline: none;
        transition: border-color 0.15s, box-shadow 0.15s, background 0.15s;
    }

    .form-group input:focus {
        border-color: var(--gold);
        box-shadow: 0 0 0 3px var(--gold-glow);
        background: var(--white);
    }

    .form-group input::placeholder { color: #c8c4bc; }

    /* Password field with toggle icon */
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

    /* ─── AUTHENTICATED STATE ─────────────────────── */
    .auth-welcome {
        text-align: center;
        padding: 0.5rem 0 0.75rem;
    }

    .avatar-ring {
        width: 58px; height: 58px;
        background: var(--gold-glow);
        border: 1.5px solid var(--gold);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.25rem;
    }

    .avatar-ring svg {
        width: 26px; height: 26px;
        stroke: var(--gold);
        stroke-width: 1.75;
        fill: none;
    }

    .auth-welcome h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--navy);
        line-height: 1.2;
        margin-bottom: 0.35rem;
    }

    .auth-welcome .sub {
        font-size: 0.78rem;
        color: var(--muted);
        margin-bottom: 1.75rem;
    }

    .btn-logout {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.62rem 1.4rem;
        border: 1.5px solid var(--border);
        border-radius: 8px;
        background: transparent;
        font-family: 'DM Sans', sans-serif;
        font-size: 0.8rem;
        font-weight: 500;
        color: var(--muted);
        cursor: pointer;
        transition: border-color 0.15s, color 0.15s;
    }

    .btn-logout:hover {
        border-color: #e57373;
        color: #d95f5f;
    }

    .btn-logout svg {
        width: 14px; height: 14px;
        stroke: currentColor;
        stroke-width: 1.75;
        fill: none;
        flex-shrink: 0;
    }
    /* error1 */
    .error {
        color: #e57373;
        font-size: 0.75rem;
        margin-top: 0.25rem;
    }
</style>
</x-slot:styles>
@php
  $user = App\Models\User::where('user_id', session('user_id'))->first(); 
@endphp


@if($user)
<div class="auth-card">
        <div class="card-accent"></div>
        <div class="card-body">

            <div class="card-heading">
                <h2>{{ $greetings }}</h2>
                <p>Sign in or create an account to continue.</p>
                <p class="sub">Welcome, {{ $user->name }} ({{ $user->user_id }})!</p>
                <form action="{{ route('destroy.session') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn-logout">
                        Change User
                        <svg viewBox="0 0 24 24">
                            <path d="M16 17l5-5m0 0l-5-5m5 5H9m4 5v1a2 2 0 002 2h3a2 2 0 002-2v-1m-6-10V7a2 2 0 012-2h3a2 2 0 012 2v1" />
                        </svg>
                    </button>
                </form>
            </div>

            {{-- Tab buttons --}}
            <div class="tab-switch">
                <button class="tab-btn active" data-tab="login">Sign In</button>
                <button class="tab-btn"         data-tab="register">Register</button>
            </div>

            {{-- ── SIGN IN ── --}}
            <div class="tab-pane active" id="tab-login">
                <form action="/login" method="POST">
                    @csrf   

                    <div class="form-group">
                        <label for="logname">Username</label>
                        <input
                            type="text"
                            id="logname"
                            name="logname"
                            placeholder="Enter your name"
                            autocomplete="username"
                        >
                        @error('id')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.4rem;">
                            <label for="logpassword" style="margin-bottom: 0;">Password</label>
                            <a href="/forgot-password" style="font-size: 0.68rem; color: #b88d3a; font-weight: 500; text-decoration: none;" class="hover:underline">Forgot Password?</a>
                        </div>
                        <div class="password-input-wrapper">
                            <input
                                type="password"
                                id="logpassword"
                                name="logpassword"
                                placeholder="••••••••"
                                autocomplete="current-password"
                            >
                            <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('logpassword', this)">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn-submit">Sign In</button>
                </form>
            </div>

           {{-- ── REGISTER ── --}}
<div class="tab-pane" id="tab-register">
    <form action="/register" method="POST">
        @csrf

        <div class="form-group">
            <label for="reg-name">Username</label>
            <input type="text" id="reg-name" name="name" placeholder="Your Username" autocomplete="name" required>
            @error('name')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="reg-email">Email Address</label>
            <input type="email" id="reg-email" name="email" placeholder="you@email.com" autocomplete="email" required>
            @error('email')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label for="reg-password">Password</label>
            <div class="password-input-wrapper">
                <input type="password" id="reg-password" name="password" placeholder="Create a password" autocomplete="new-password" required>
                <button type="button" class="password-toggle-btn" onclick="togglePasswordVisibility('reg-password', this)">
                    <i class="fas fa-eye"></i>
                </button>
            </div>
            @error('password')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-submit">Create Account</button>
    </form>
</div>

        </div>
    </div>
    
@else
    <div class="auth-card">
         <div class="card-body">
                     <div class="card-heading">
                        <h2>WELCOME TO CAPSTONE TRACKER</h2>
                <p>Please input Your Student ID</p>
            </div>
                    <div class="tab-pane active" id="tab-login">
                <form action="/id" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="id"></label>
                        <input
                            type="text"
                            id="id"
                            name="id"
                            placeholder="Enter your ID"
                            autocomplete="id"
                        >
                            @error('id')
                        <p class="error">{{ $message }}</p>
                             @enderror
                    </div>

                    <button type="submit" class="btn-submit">Check Id</button>
                </form>
            </div>
        </div>
    </div>
@endif


<x-slot:scripts>
<script>
    document.querySelectorAll('.tab-btn').forEach(function (btn) {
        btn.addEventListener('click', function () {
            var tab = this.dataset.tab;

            // Toggle active button
            document.querySelectorAll('.tab-btn').forEach(function (b) {
                b.classList.remove('active');
            });
            this.classList.add('active');

            // Toggle active pane
            document.querySelectorAll('.tab-pane').forEach(function (p) {
                p.classList.remove('active');
            });
            document.getElementById('tab-' + tab).classList.add('active');
        });
    });

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
</script>
</x-slot:scripts>

</x-layout> 
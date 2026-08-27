<x-layout>

<x-slot:styles>
<style>
    .confirmation-card {
        max-width: 440px;
        margin: 2rem auto;
        background: var(--white);
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(13,27,46,0.06), 0 8px 32px rgba(13,27,46,0.08);
        overflow: hidden;
        animation: cardIn 0.4s cubic-bezier(0.22,1,0.36,1) both;
    }
    @keyframes cardIn {
        from { opacity:0; transform:translateY(18px); }
        to   { opacity:1; transform:translateY(0); }
    }
    .card-accent {
        height: 4px;
        background: linear-gradient(90deg, var(--gold), var(--gold-light));
    }
    .card-body {
        padding: 2.25rem 2rem 2.5rem;
        text-align: center;
    }
    .icon-success {
        font-size: 3.5rem;
        color: #1e6b3a;
        margin-bottom: 0.5rem;
    }
    .card-body h2 {
        font-family: 'Cormorant Garamond', serif;
        font-size: 1.8rem;
        font-weight: 600;
        color: var(--navy);
        margin-bottom: 0.5rem;
    }
    .card-body p {
        color: var(--text-muted);
        font-size: 0.9rem;
        margin-bottom: 1.5rem;
    }
    .btn-home {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.7rem 2rem;
        background: var(--navy);
        color: var(--gold);
        border: none;
        border-radius: 8px;
        font-size: 0.9rem;
        font-weight: 500;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
    }
    .btn-home:hover:not(:disabled) {
        background: var(--navy-hover);
        transform: translateY(-2px);
    }
    .btn-home:disabled {
        opacity: 0.6;
        cursor: not-allowed;
        transform: none;
    }
    .spinner {
        display: inline-block;
        width: 18px;
        height: 18px;
        border: 2px solid rgba(255,255,255,0.2);
        border-top-color: var(--gold);
        border-radius: 50%;
        animation: spin 0.7s linear infinite;
    }
    @keyframes spin {
        to { transform: rotate(360deg); }
    }
    .loading-overlay {
        position: fixed;
        inset: 0;
        background: rgba(5,16,33,0.6);
        backdrop-filter: blur(4px);
        display: none;
        align-items: center;
        justify-content: center;
        z-index: 9999;
    }
    .loading-overlay.active {
        display: flex;
    }
    .loading-box {
        background: var(--white);
        padding: 2rem 3rem;
        border-radius: 12px;
        text-align: center;
        box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    }
    .loading-box .spinner {
        width: 40px;
        height: 40px;
        border-width: 4px;
    }
    .loading-box p {
        margin-top: 0.8rem;
        color: var(--text);
        font-weight: 500;
    }
</style>
</x-slot:styles>

<div class="confirmation-card">
    <div class="card-accent"></div>
    <div class="card-body">
        <div class="icon-success">
            <i class="fas fa-check-circle"></i>
        </div>
        <h2>Password Reset</h2>
        <p>
            {{ session('success') ?? 'Your password has been reset successfully!' }}
            <br>
            <small>You can now log in with your new password.</small>
        </p>

        <button type="button" class="btn-home" id="goHomeBtn">
            <i class="fas fa-arrow-right"></i> Go to Login
        </button>
    </div>
</div>

<!-- Loading Overlay -->
<div class="loading-overlay" id="loadingOverlay">
    <div class="loading-box">
        <div class="spinner"></div>
        <p>Redirecting to login…</p>
    </div>
</div>

<x-slot:scripts>
<script>
    document.getElementById('goHomeBtn').addEventListener('click', function () {
        // Disable button and show loading overlay
        this.disabled = true;
        document.getElementById('loadingOverlay').classList.add('active');

        // Redirect after a short delay (allows overlay to render)
        setTimeout(function () {
            window.location.href = '/';
        }, 400);
    });
</script>
</x-slot:scripts>

</x-layout> 
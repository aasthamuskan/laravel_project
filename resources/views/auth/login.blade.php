<x-guest-layout>

    {{-- ── Session Status ── --}}
    @if (session('status'))
        <div class="status-msg">{{ session('status') }}</div>
    @endif

    {{-- ── Brand mark (mobile only, hidden on desktop split-screen) ── --}}
    <div class="lg:hidden flex items-center gap-2 mb-6">
        <div style="width:34px;height:34px;background:linear-gradient(135deg,#10b981,#0d9488);border-radius:9px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 16px rgba(16,185,129,0.4);">
            <svg width="18" height="18" fill="none" viewBox="0 0 24 24"><path fill="#fff" d="M12 2C7 2 3 6 3 11c0 3.7 2.1 6.9 5.2 8.5l.8.4v1.6c0 .3.2.5.5.5h5c.3 0 .5-.2.5-.5v-1.6l.8-.4C18.9 17.9 21 14.7 21 11c0-5-4-9-9-9z"/></svg>
        </div>
        <span style="font-size:1.1rem;font-weight:700;color:#f1f5f9;letter-spacing:-0.02em;">FarmAdviser</span>
    </div>

    {{-- ── Heading ── --}}
    <div style="margin-bottom:2rem;">
        <h2 style="font-size:1.625rem;font-weight:700;color:#f1f5f9;letter-spacing:-0.02em;margin-bottom:0.4rem;">
            Welcome back
        </h2>
        <p style="font-size:0.875rem;color:rgba(148,163,184,0.6);line-height:1.6;">
            Sign in to your agriculture intelligence dashboard
        </p>
    </div>

    {{-- ── Login Form ── --}}
    <form method="POST" action="{{ route('login') }}" style="display:flex;flex-direction:column;gap:1.25rem;">
        @csrf

        {{-- Email --}}
        <div>
            <label for="email" class="auth-label">Email address</label>
            <div style="position:relative;">
                <span style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);pointer-events:none;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="rgba(148,163,184,0.5)" stroke-width="1.8">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                    </svg>
                </span>
                <input
                    id="email"
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="you@example.com"
                    class="auth-input"
                    style="padding-left:2.75rem;"
                >
            </div>
            @error('email')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Password --}}
        <div>
            <label for="password" class="auth-label">Password</label>
            <div style="position:relative;">
                <span style="position:absolute;left:1rem;top:50%;transform:translateY(-50%);pointer-events:none;">
                    <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="rgba(148,163,184,0.5)" stroke-width="1.8">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
                    </svg>
                </span>
                <input
                    id="password"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                    placeholder="••••••••••"
                    class="auth-input"
                    style="padding-left:2.75rem;padding-right:3rem;"
                >
                {{-- Show/hide toggle --}}
                <button type="button" onclick="togglePassword()" style="position:absolute;right:1rem;top:50%;transform:translateY(-50%);background:none;border:none;cursor:pointer;padding:0;" id="eye-btn">
                    <svg id="eye-icon" width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="rgba(148,163,184,0.5)" stroke-width="1.8">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </button>
            </div>
            @error('password')
                <p class="auth-error">{{ $message }}</p>
            @enderror
        </div>

        {{-- Remember + Forgot --}}
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <label for="remember_me" style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                <input id="remember_me" type="checkbox" name="remember" class="auth-checkbox">
                <span style="font-size:0.8125rem;color:rgba(148,163,184,0.7);">Remember me</span>
            </label>

            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}" class="auth-link">
                    Forgot password?
                </a>
            @endif
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn-primary" style="margin-top:0.5rem;">
            <span style="display:flex;align-items:center;justify-content:center;gap:8px;">
                <svg width="16" height="16" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/>
                </svg>
                Sign in to FarmAdviser
            </span>
        </button>

        {{-- Divider --}}
        @if (Route::has('register'))
        <div class="auth-divider">
            <span style="font-size:0.75rem;color:rgba(148,163,184,0.35);">New to FarmAdviser?</span>
        </div>

        {{-- Register link --}}
        <a href="{{ route('register') }}" style="
            display:block;
            text-align:center;
            padding:0.75rem;
            border:1px solid rgba(255,255,255,0.08);
            border-radius:12px;
            font-size:0.875rem;
            font-weight:500;
            color:rgba(148,163,184,0.75);
            text-decoration:none;
            transition:all 0.25s ease;
        " onmouseover="this.style.borderColor='rgba(16,185,129,0.3)';this.style.color='#34d399';this.style.background='rgba(16,185,129,0.05)';"
           onmouseout="this.style.borderColor='rgba(255,255,255,0.08)';this.style.color='rgba(148,163,184,0.75)';this.style.background='transparent';">
            Create a free account →
        </a>
        @endif

    </form>

    {{-- ── Trust badges ── --}}
    <div style="margin-top:2rem;padding-top:1.5rem;border-top:1px solid rgba(255,255,255,0.05);display:flex;align-items:center;justify-content:center;gap:1.5rem;flex-wrap:wrap;">
        <div style="display:flex;align-items:center;gap:5px;font-size:0.7rem;color:rgba(148,163,184,0.35);">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/></svg>
            Secure · Encrypted
        </div>
        <div style="display:flex;align-items:center;gap:5px;font-size:0.7rem;color:rgba(148,163,184,0.35);">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            Real-time data
        </div>
        <div style="display:flex;align-items:center;gap:5px;font-size:0.7rem;color:rgba(148,163,184,0.35);">
            <svg width="12" height="12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"/></svg>
            AI Powered
        </div>
    </div>

    <script>
    function togglePassword() {
        const input = document.getElementById('password');
        const icon  = document.getElementById('eye-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/><line x1="1" y1="1" x2="23" y2="23"/>';
        } else {
            input.type = 'password';
            icon.innerHTML = '<path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>';
        }
    }
    </script>

</x-guest-layout>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'FarmAdviser') }} – @yield('title', 'Home')</title>
    <meta name="description" content="Real-time weather data and crop-specific farming advisory for smarter agricultural decisions.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* ── Reset & Base ───────────────────────────────── */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --green-50:  #f0fdf4; --green-100: #dcfce7; --green-500: #22c55e;
            --green-600: #16a34a; --green-700: #15803d; --green-900: #14532d;
            --sky-400: #38bdf8; --sky-500: #0ea5e9; --amber-400: #fbbf24;
            --amber-500: #f59e0b; --red-500: #ef4444;
            --gray-50:#f9fafb;--gray-100:#f3f4f6;--gray-200:#e5e7eb;
            --gray-400:#9ca3af;--gray-600:#4b5563;--gray-700:#374151;
            --gray-800:#1f2937;--gray-900:#111827;
            /* Theme tokens - dark default */
            --bg-deep:   #020d18;
            --bg-mid:    #0a1628;
            --surface:   rgba(255,255,255,0.03);
            --surface-2: rgba(255,255,255,0.06);
            --border:    rgba(255,255,255,0.07);
            --border-2:  rgba(255,255,255,0.12);
            --text-pri:  #f1f5f9;
            --text-sec:  rgba(148,163,184,0.75);
            --text-muted:rgba(148,163,184,0.4);
            --navbar-bg: rgba(2,13,24,0.80);
            --input-bg:  rgba(255,255,255,0.04);
            --input-border: rgba(255,255,255,0.09);
            --opt-bg:    #0f1e2e;
            --accent-g:  #10b981;
            --accent-b:  #06b6d4;
            --accent-y:  #f59e0b;
            --shadow:    rgba(0,0,0,0.4);
            --grid-c:    rgba(16,185,129,0.04);
            --amb1:      rgba(16,185,129,0.10);
            --amb2:      rgba(6,182,212,0.08);
        }
        /* ═══ LIGHT THEME ═══ */
        html.light {
            --bg-deep:   #eef7f2;
            --bg-mid:    #dff0e8;
            --surface:   rgba(255,255,255,0.92);
            --surface-2: rgba(255,255,255,0.98);
            --border:    rgba(0,80,40,0.15);
            --border-2:  rgba(16,185,129,0.40);
            --text-pri:  #0d1f14;
            --text-sec:  rgba(15,50,28,0.80);
            --text-muted:rgba(15,50,28,0.50);
            --navbar-bg: rgba(238,247,242,0.92);
            --input-bg:  rgba(255,255,255,0.95);
            --input-border: rgba(0,80,40,0.20);
            --opt-bg:    #ffffff;
            --accent-g:  #047857;
            --accent-b:  #0369a1;
            --accent-y:  #b45309;
            --shadow:    rgba(0,60,30,0.12);
            --grid-c:    rgba(16,185,129,0.08);
            --amb1:      rgba(16,185,129,0.10);
            --amb2:      rgba(6,182,212,0.06);
        }
        /* ── Global light-mode text overrides ──
           Fixes hardcoded dark-theme colors (#f1f5f9 etc) in dashboard */
        html.light .page-title,
        html.light h1, html.light h2, html.light h3 {
            color: #0d1f14 !important;
        }
        html.light .temp-big {
            background: linear-gradient(135deg, #0d1f14 0%, #047857 100%) !important;
            -webkit-background-clip: text !important;
            -webkit-text-fill-color: transparent !important;
        }
        html.light .metric-value { color: #0d1f14 !important; }
        html.light .metric-label { color: rgba(15,50,28,0.55) !important; }
        html.light .metric-tile {
            background: rgba(255,255,255,0.75) !important;
            border-color: rgba(0,80,40,0.12) !important;
        }
        html.light .metric-tile:hover { background: rgba(255,255,255,0.95) !important; }
        html.light .g-panel {
            background: rgba(255,255,255,0.88) !important;
            border-color: rgba(0,80,40,0.15) !important;
            box-shadow: 0 8px 32px rgba(0,60,30,0.10), inset 0 1px 0 rgba(255,255,255,0.9) !important;
        }
        html.light .g-panel:hover {
            border-color: rgba(4,120,87,0.35) !important;
            box-shadow: 0 12px 40px rgba(0,60,30,0.15), 0 0 0 1px rgba(4,120,87,0.15), inset 0 1px 0 #fff !important;
        }
        html.light .g-header {
            color: rgba(15,50,28,0.65) !important;
            border-bottom-color: rgba(0,80,40,0.10) !important;
        }
        html.light .insight-chip {
            background: rgba(255,255,255,0.80) !important;
            border-color: rgba(0,80,40,0.12) !important;
            color: rgba(15,50,28,0.75) !important;
        }
        html.light .insight-chip:hover { color: #0d1f14 !important; }
        html.light .adv-badge-green { background:rgba(4,120,87,0.12)!important; color:#065f46!important; border-color:rgba(4,120,87,0.3)!important; }
        html.light .adv-badge-blue  { background:rgba(3,105,161,0.10)!important; color:#0c4a6e!important; border-color:rgba(3,105,161,0.25)!important; }
        html.light .adv-badge-amber { background:rgba(180,83,9,0.10)!important;  color:#78350f!important; border-color:rgba(180,83,9,0.25)!important; }
        html.light .rain-alert {
            background: rgba(220,38,38,0.07) !important;
            border-color: rgba(220,38,38,0.25) !important;
            color: #7f1d1d !important;
        }
        html.light .error-bar {
            background: rgba(180,83,9,0.07) !important;
            border-color: rgba(180,83,9,0.25) !important;
            color: #78350f !important;
        }
        html.light .gdot { background: #047857 !important; box-shadow: 0 0 6px #047857 !important; }
        html.light .prog-fill { background: linear-gradient(90deg, #047857, rgba(4,120,87,0.6)) !important; }
        html.light .prog-bar { background: rgba(0,80,40,0.08) !important; }
        html.light .empty-orb { background: radial-gradient(circle, #10b981 0%, transparent 70%) !important; }
        /* Light dashboard ambient */
        html.light .dash-ambient {
            background: radial-gradient(ellipse 60% 50% at 20% 30%, rgba(4,120,87,0.08) 0%, transparent 60%),
                        radial-gradient(ellipse 50% 40% at 80% 70%, rgba(6,182,212,0.06) 0%, transparent 60%) !important;
        }
        /* Smooth theme transition */
        html { transition: background 0.4s ease; }
        body, .navbar, .card, .g-panel, .form-control, .btn-logout
            { transition: background 0.35s ease, border-color 0.35s ease,
                          color 0.3s ease, box-shadow 0.35s ease !important; }
        html { scroll-behavior: smooth; }
        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-deep);
            color: var(--text-pri);
            min-height: 100vh;
            overflow-x: hidden;
        }
        /* ── Light body bg ── */
        html.light body { background: linear-gradient(135deg,#f0faf5 0%,#e6f5ee 100%); }
        html.light body::before { background:
            radial-gradient(ellipse 70% 50% at 15% 25%, rgba(16,185,129,0.13) 0%, transparent 60%),
            radial-gradient(ellipse 50% 40% at 85% 75%, rgba(6,182,212,0.09) 0%, transparent 60%); }
        html.light body::after { background-image:
            linear-gradient(rgba(16,185,129,0.07) 1px, transparent 1px),
            linear-gradient(90deg, rgba(16,185,129,0.07) 1px, transparent 1px);
            background-size: 56px 56px; }

        /* ── Ambient background ─────────────────────────── */
        body::before {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background:
                radial-gradient(ellipse 70% 50% at 15% 25%, rgba(16,185,129,0.10) 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 85% 75%, rgba(6,182,212,0.08) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 50% 10%,  rgba(245,158,11,0.04) 0%, transparent 60%);
        }

        /* ── Grid pattern ───────────────────────────────── */
        body::after {
            content: '';
            position: fixed; inset: 0; z-index: 0; pointer-events: none;
            background-image:
                linear-gradient(rgba(16,185,129,0.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(16,185,129,0.04) 1px, transparent 1px);
            background-size: 56px 56px;
        }

        /* ── Floating navbar ────────────────────────────── */
        .navbar {
            position: sticky; top: 0; z-index: 200;
            height: 64px;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0 1.5rem;
            background: var(--navbar-bg);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border);
            box-shadow: 0 4px 32px var(--shadow);
        }
        /* ── Theme Toggle ── */
        .theme-toggle {
            width: 52px; height: 28px; border-radius: 99px;
            background: var(--surface); border: 1px solid var(--border);
            cursor: pointer; position: relative; display: flex; align-items: center;
            padding: 3px; transition: all 0.3s ease; flex-shrink: 0;
        }
        .theme-toggle:hover { border-color: var(--accent-g); box-shadow: 0 0 12px rgba(16,185,129,0.25); }
        .theme-knob {
            width: 20px; height: 20px; border-radius: 50%;
            background: linear-gradient(135deg,#fbbf24,#f59e0b);
            box-shadow: 0 2px 8px rgba(245,158,11,0.5);
            transition: all 0.35s cubic-bezier(.34,1.56,.64,1);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; line-height: 1;
        }
        html.light .theme-knob {
            transform: translateX(24px);
            background: linear-gradient(135deg,#6366f1,#4f46e5);
            box-shadow: 0 2px 8px rgba(99,102,241,0.5);
        }
        /* ── Language Dropdown ── */
        .lang-wrap { position: relative; }
        .lang-btn {
            display: flex; align-items: center; gap: 6px;
            padding: 6px 12px; border-radius: 10px;
            background: var(--surface); border: 1px solid var(--border);
            color: var(--text-sec); font-size: 0.82rem; font-weight: 600;
            cursor: pointer; transition: all 0.2s ease;
        }
        .lang-btn:hover { background: var(--surface-2); color: var(--text-pri); border-color: var(--border-2); }
        .lang-menu {
            position: absolute; right: 0; top: calc(100% + 8px);
            width: 210px; border-radius: 14px;
            background: var(--navbar-bg); border: 1px solid var(--border);
            backdrop-filter: blur(20px); box-shadow: 0 16px 48px var(--shadow);
            padding: 6px; z-index: 500;
            opacity: 0; transform: translateY(-8px) scale(0.97);
            pointer-events: none; transition: all 0.22s cubic-bezier(.16,1,.3,1);
        }
        .lang-menu.open { opacity: 1; transform: translateY(0) scale(1); pointer-events: auto; }
        .lang-item {
            display: flex; align-items: center; gap: 10px;
            padding: 9px 12px; border-radius: 9px; cursor: pointer;
            font-size: 0.85rem; color: var(--text-sec);
            transition: all 0.15s ease;
        }
        .lang-item:hover, .lang-item.active { background: rgba(16,185,129,0.12); color: var(--accent-g); }
        .lang-item .lang-flag { font-size: 1.1rem; }
        .lang-item .lang-native { font-size: 0.78rem; opacity: 0.6; margin-left: auto; }

        .navbar-brand {
            display: flex; align-items: center; gap: 10px;
            color: var(--text-pri); font-weight: 700; font-size: 1.15rem;
            text-decoration: none; letter-spacing: -0.02em;
        }
        .brand-icon {
            width: 36px; height: 36px;
            background: linear-gradient(135deg, #10b981, #0d9488);
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            box-shadow: 0 0 16px rgba(16,185,129,0.4);
            font-size: 1.1rem;
        }

        .nav-links { display: flex; align-items: center; gap: 4px; }

        .nav-link {
            color: var(--text-sec); text-decoration: none;
            padding: 6px 14px; border-radius: 8px;
            font-size: 0.875rem; font-weight: 500;
            transition: all 0.2s ease;
            display: flex; align-items: center; gap: 6px;
            position: relative;
        }
        .nav-link:hover {
            color: var(--text-pri);
            background: var(--surface-2);
        }
        .nav-link.active {
            color: #10b981;
            background: rgba(16,185,129,0.1);
            border: 1px solid rgba(16,185,129,0.2);
        }
        .nav-link.active::after {
            content: '';
            position: absolute; bottom: -1px; left: 50%; transform: translateX(-50%);
            width: 60%; height: 2px;
            background: linear-gradient(90deg, transparent, #10b981, transparent);
            border-radius: 99px;
        }

        .nav-sep { width: 1px; height: 24px; background: var(--border); margin: 0 8px; }

        .nav-user {
            display: flex; align-items: center; gap: 8px;
            font-size: 0.85rem; color: var(--text-sec);
        }
        .nav-user strong { color: var(--text-pri); font-weight: 600; }

        .btn-logout {
            background: var(--surface);
            border: 1px solid var(--border);
            color: var(--text-sec); padding: 6px 14px; border-radius: 8px;
            font-size: 0.85rem; cursor: pointer; font-family: 'Inter', sans-serif;
            transition: all 0.2s ease; font-weight: 500;
        }
        .btn-logout:hover {
            background: rgba(239,68,68,0.12);
            border-color: rgba(239,68,68,0.25);
            color: #dc2626;
        }

        /* ── Role badges ─────────────────────────────────── */
        .badge { padding: 3px 10px; border-radius: 99px; font-size: 0.72rem; font-weight: 600; letter-spacing: 0.02em; }
        .badge-admin   { background: rgba(245,158,11,0.15); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }
        .badge-expert  { background: rgba(59,130,246,0.15); color: #93c5fd; border: 1px solid rgba(59,130,246,0.25); }
        .badge-farmer  { background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.25); }

        /* ── Layout container ───────────────────────────── */
        .container { max-width: 1240px; margin: 0 auto; padding: 2rem 1.5rem; position: relative; z-index: 10; }

        /* ── Alerts ─────────────────────────────────────── */
        .alert {
            padding: 14px 18px; border-radius: 12px; margin-bottom: 1.25rem;
            font-size: 0.9rem; font-weight: 500; display: flex;
            align-items: center; gap: 10px;
            backdrop-filter: blur(12px);
        }
        .alert-success { background: rgba(16,185,129,0.1); color: #34d399; border: 1px solid rgba(16,185,129,0.2); }
        .alert-danger  { background: rgba(239,68,68,0.1);  color: #fca5a5; border: 1px solid rgba(239,68,68,0.2); }
        .alert-warning { background: rgba(245,158,11,0.1); color: #fbbf24; border: 1px solid rgba(245,158,11,0.2); }
        .alert-info    { background: rgba(6,182,212,0.1);  color: #67e8f9; border: 1px solid rgba(6,182,212,0.2); }

        /* ── Glass Cards ─────────────────────────────────── */
        .card {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.07);
            border-radius: 20px;
            backdrop-filter: blur(16px);
            box-shadow: 0 8px 32px rgba(0,0,0,0.3), inset 0 1px 0 rgba(255,255,255,0.06);
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        .card:hover {
            border-color: rgba(16,185,129,0.15);
            box-shadow: 0 12px 40px rgba(0,0,0,0.35), 0 0 0 1px rgba(16,185,129,0.1), inset 0 1px 0 rgba(255,255,255,0.08);
        }
        .card-header {
            padding: 1.1rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            font-weight: 700; font-size: 0.9rem; color: rgba(148,163,184,0.9);
            display: flex; align-items: center; gap: 8px;
            letter-spacing: 0.01em;
        }
        .card-body { padding: 1.5rem; }

        /* ── Forms ───────────────────────────────────────── */
        .form-label {
            display: block; font-size: 0.8rem; font-weight: 600;
            color: var(--text-sec); margin-bottom: 6px;
            text-transform: uppercase; letter-spacing: 0.06em;
        }
        .form-control {
            width: 100%; padding: 10px 14px;
            background: var(--input-bg);
            border: 1px solid var(--input-border);
            border-radius: 10px; font-size: 0.9rem; outline: none;
            transition: all 0.25s ease;
            color: var(--text-pri); font-family: 'Inter', sans-serif;
        }
        .form-control::placeholder { color: var(--text-muted); }
        .form-control:focus {
            border-color: rgba(16,185,129,0.5);
            background: rgba(16,185,129,0.05);
            box-shadow: 0 0 0 3px rgba(16,185,129,0.12), 0 0 20px rgba(16,185,129,0.06);
        }
        .form-control:hover:not(:focus) { border-color: var(--border-2); background: var(--surface-2); }
        .form-control option { background: var(--opt-bg); color: var(--text-pri); }
        .form-group { margin-bottom: 1.1rem; }

        /* ── Buttons ─────────────────────────────────────── */
        .btn {
            display: inline-flex; align-items: center; gap: 6px;
            padding: 10px 20px; border-radius: 10px; border: none;
            font-size: 0.9rem; font-weight: 600; cursor: pointer;
            text-decoration: none; transition: all 0.25s ease;
            font-family: 'Inter', sans-serif;
        }
        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 50%, #0d9488 100%);
            color: white;
            box-shadow: 0 4px 20px rgba(16,185,129,0.35), 0 0 0 1px rgba(16,185,129,0.3);
            position: relative; overflow: hidden;
        }
        .btn-primary::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
            opacity: 0; transition: opacity 0.25s;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(16,185,129,0.45), 0 0 0 1px rgba(16,185,129,0.4);
        }
        .btn-primary:hover::before { opacity: 1; }
        .btn-primary:active { transform: translateY(0); }
        .btn-danger  { background: rgba(239,68,68,0.15); color: #fca5a5; border: 1px solid rgba(239,68,68,0.25); }
        .btn-danger:hover { background: rgba(239,68,68,0.25); transform: translateY(-1px); }
        .btn-secondary { background: rgba(255,255,255,0.06); color: rgba(148,163,184,0.85); border: 1px solid rgba(255,255,255,0.1); }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); }
        .btn-sm { padding: 6px 12px; font-size: 0.8rem; border-radius: 8px; }

        /* ── Tables ──────────────────────────────────────── */
        table { width: 100%; border-collapse: collapse; }
        th {
            font-size: 0.75rem; font-weight: 600; text-transform: uppercase;
            letter-spacing: 0.06em; color: rgba(148,163,184,0.5);
            padding: 12px 16px; text-align: left;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }
        td {
            padding: 14px 16px; border-bottom: 1px solid rgba(255,255,255,0.04);
            font-size: 0.9rem; vertical-align: top; color: rgba(241,245,249,0.85);
        }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: rgba(255,255,255,0.02); }

        /* ── Page titles ─────────────────────────────────── */
        .page-title { font-size: 1.8rem; font-weight: 800; color: #f1f5f9; margin-bottom: 0.3rem; letter-spacing: -0.02em; }
        .page-subtitle { font-size: 0.9rem; color: rgba(148,163,184,0.55); }

        /* ── Footer ──────────────────────────────────────── */
        .site-footer {
            text-align: center; color: rgba(148,163,184,0.25);
            font-size: 0.78rem; padding: 2rem 0; margin-top: 3rem;
            border-top: 1px solid rgba(255,255,255,0.05);
            position: relative; z-index: 10;
        }

        /* ── Glow dot ─────────────────────────────────────── */
        .glow-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: #10b981; display: inline-block;
            animation: glowPulse 2s infinite;
            box-shadow: 0 0 6px #10b981;
        }
        @keyframes glowPulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50%       { opacity: 0.5; transform: scale(0.75); }
        }

        /* ── Page fade-in ────────────────────────────────── */
        .page-enter {
            animation: pageIn 0.5s cubic-bezier(0.16,1,0.3,1) forwards;
        }
        @keyframes pageIn {
            from { opacity: 0; transform: translateY(12px); }
            to   { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body>

    <!-- ══ Floating Navbar ══ -->
    <nav class="navbar">
        <a href="{{ route('home') }}" class="navbar-brand">
            <div class="brand-icon">🌾</div>
            <span>FarmAdviser</span>
        </a>

        @auth
        <div class="nav-links">
            <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
                {{ __('messages.dashboard') }}
            </a>

            @if(in_array(auth()->user()->role, ['Expert', 'Admin']))
                <a href="{{ route('advisories.index') }}" class="nav-link {{ request()->routeIs('advisories.*') ? 'active' : '' }}">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                    Advisories
                </a>
            @endif

            @if(auth()->user()->role === 'Admin')
                <a href="{{ route('admin.overview') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}">
                    <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path d="M19.07 4.93A10 10 0 0 0 4.93 19.07M4.93 4.93a10 10 0 0 0 14.14 14.14"/></svg>
                    Admin
                </a>
            @endif

            <a href="{{ route('advisory.filter') }}" class="nav-link {{ request()->routeIs('advisory.filter') ? 'active' : '' }}">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2a7 7 0 0 1 7 7c0 5-7 13-7 13S5 14 5 9a7 7 0 0 1 7-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
                {{ __('messages.browse') }}
            </a>

            <div class="nav-sep"></div>

            {{-- Language Selector --}}
            <div class="lang-wrap">
                <button class="lang-btn" id="langBtn" onclick="toggleLang()" aria-label="Select Language">
                    <span id="langFlag">🌐</span>
                    <span id="langLabel">{{ strtoupper(app()->getLocale()) }}</span>
                    <svg width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="6 9 12 15 18 9"/></svg>
                </button>
                <div class="lang-menu" id="langMenu">
                    @foreach([
                        ['en','🇬🇧','English','English'],
                        ['hi','🇮🇳','Hindi','हिन्दी'],
                        ['pa','🇮🇳','Punjabi','ਪੰਜਾਬੀ'],
                        ['bn','🇧🇩','Bengali','বাংলা'],
                        ['ta','🇮🇳','Tamil','தமிழ்'],
                        ['te','🇮🇳','Telugu','తెలుగు'],
                        ['mr','🇮🇳','Marathi','मराठी'],
                        ['gu','🇮🇳','Gujarati','ગુજરાતી'],
                        ['kn','🇮🇳','Kannada','ಕನ್ನಡ'],
                        ['ml','🇮🇳','Malayalam','മലയാളം'],
                    ] as [$code,$flag,$name,$native])
                    <a class="lang-item {{ app()->getLocale()===$code ? 'active' : '' }}"
                       href="{{ route('lang.switch', $code) }}">
                        <span class="lang-flag">{{ $flag }}</span>
                        <span>{{ $name }}</span>
                        <span class="lang-native">{{ $native }}</span>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Theme Toggle --}}
            <button class="theme-toggle" id="themeToggle" onclick="toggleTheme()" title="Toggle theme">
                <div class="theme-knob" id="themeKnob">☀️</div>
            </button>

            <div class="nav-user">
                <strong>{{ auth()->user()->name }}</strong>
                <span class="badge badge-{{ strtolower(auth()->user()->role) }}">{{ auth()->user()->role }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}" style="margin:0;">
                @csrf
                <button type="submit" class="btn-logout">{{ __('messages.sign_out') }}</button>
            </form>
        </div>
        @else
        <div class="nav-links">
            <a href="{{ route('login') }}"    class="nav-link">Login</a>
            <a href="{{ route('register') }}" class="nav-link btn-primary btn btn-sm" style="border:none;">Get Started</a>
        </div>
        @endauth
    </nav>

    <!-- Flash Messages -->
    <div class="container" style="padding-bottom:0; padding-top:1.2rem;">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">❌ {{ session('error') }}</div>
        @endif
    </div>

    <!-- Main Content -->
    <div class="page-enter">
        @yield('content')
    </div>

    <footer class="site-footer">
        FarmAdviser &copy; {{ date('Y') }} — {{ __('messages.footer_text') }}
    </footer>

<script>
// ═══ THEME SWITCHER ═══
(function(){
    const saved = localStorage.getItem('fa-theme') || 'dark';
    if(saved === 'light') applyLight(true);
})();
function applyLight(silent){
    document.documentElement.classList.add('light');
    const k = document.getElementById('themeKnob');
    if(k) k.textContent = '🌙';
}
function toggleTheme(){
    const isLight = document.documentElement.classList.toggle('light');
    const k = document.getElementById('themeKnob');
    k.textContent = isLight ? '🌙' : '☀️';
    localStorage.setItem('fa-theme', isLight ? 'light' : 'dark');
}

// ═══ LANGUAGE SWITCHER ═══
const LANGS = {
    en: { dashboard:'Dashboard', browse:'Browse', admin:'Admin', advisories:'Advisories',
          signout:'Sign Out', footer:'AI-Powered Weather & Farming Advisory System',
          greeting:'Hello', smarttip:'Smart Tip' },
    hi: { dashboard:'डैशबोर्ड', browse:'ब्राउज़', admin:'व्यवस्थापक', advisories:'सलाह',
          signout:'साइन आउट', footer:'AI-संचालित मौसम और कृषि सलाह प्रणाली',
          greeting:'नमस्ते', smarttip:'स्मार्ट टिप' },
    pa: { dashboard:'ਡੈਸ਼ਬੋਰਡ', browse:'ਬ੍ਰਾਊਜ਼', admin:'ਪ੍ਰਸ਼ਾਸਕ', advisories:'ਸਲਾਹਾਂ',
          signout:'ਸਾਈਨ ਆਊਟ', footer:'AI-ਸੰਚਾਲਿਤ ਮੌਸਮ ਅਤੇ ਖੇਤੀਬਾੜੀ ਸਲਾਹ',
          greeting:'ਸਤ ਸ੍ਰੀ ਅਕਾਲ', smarttip:'ਸਮਾਰਟ ਸੁਝਾਅ' },
    bn: { dashboard:'ড্যাশবোর্ড', browse:'ব্রাউজ', admin:'অ্যাডমিন', advisories:'পরামর্শ',
          signout:'সাইন আউট', footer:'AI-চালিত আবহাওয়া ও কৃষি পরামর্শ ব্যবস্থা',
          greeting:'নমস্কার', smarttip:'স্মার্ট টিপ' },
    ta: { dashboard:'டாஷ்போர்டு', browse:'உலாவு', admin:'நிர்வாகி', advisories:'ஆலோசனை',
          signout:'வெளியேறு', footer:'AI-இயக்கப்படும் வானிலை மற்றும் விவசாய அறிவுரை',
          greeting:'வணக்கம்', smarttip:'ஸ்மார்ட் டிப்' },
    te: { dashboard:'డాష్‌బోర్డ్', browse:'బ్రౌజ్', admin:'అడ్మిన్', advisories:'సలహాలు',
          signout:'సైన్ అవుట్', footer:'AI-ఆధారిత వాతావరణ మరియు వ్యవసాయ సలహా వ్యవస్థ',
          greeting:'నమస్కారం', smarttip:'స్మార్ట్ టిప్' },
    mr: { dashboard:'डॅशबोर्ड', browse:'ब्राउझ', admin:'प्रशासक', advisories:'सल्ला',
          signout:'साइन आउट', footer:'AI-चालित हवामान आणि शेती सल्ला प्रणाली',
          greeting:'नमस्कार', smarttip:'स्मार्ट टीप' },
    gu: { dashboard:'ડેશબોર્ડ', browse:'બ્રાઉઝ', admin:'વ્યવસ્થાપક', advisories:'સલાહ',
          signout:'સાઇન આઉટ', footer:'AI-સંચાલિત હવામાન અને ખેતી સલાહ સિસ્ટમ',
          greeting:'નમસ્તે', smarttip:'સ્માર્ટ ટિપ' },
    kn: { dashboard:'ಡ್ಯಾಶ್‌ಬೋರ್ಡ್', browse:'ಬ್ರೌಸ್', admin:'ನಿರ್ವಾಹಕ', advisories:'ಸಲಹೆ',
          signout:'ಸೈನ್ ಔಟ್', footer:'AI-ಚಾಲಿತ ಹವಾಮಾನ ಮತ್ತು ಕೃಷಿ ಸಲಹೆ ವ್ಯವಸ್ಥೆ',
          greeting:'ನಮಸ್ಕಾರ', smarttip:'ಸ್ಮಾರ್ಟ್ ಟಿಪ್' },
    ml: { dashboard:'ഡാഷ്‌ബോർഡ്', browse:'ബ്രൗസ്', admin:'അഡ്മിൻ', advisories:'ഉപദേശം',
          signout:'സൈൻ ഔട്ട്', footer:'AI-ഒരുക്കിയ കാലാവസ്ഥ കൃഷി ഉപദേശ സംവിധാനം',
          greeting:'നമസ്കാരം', smarttip:'സ്മാർട്ട് ടിപ്' },
};
function applyLangTexts(code){
    const t = LANGS[code] || LANGS.en;
    document.querySelectorAll('[data-lang-key]').forEach(el=>{
        const k = el.dataset.langKey;
        if(t[k]) el.textContent = t[k];
    });
    // Nav links text
    document.querySelectorAll('.nav-link').forEach(a=>{
        const txt = a.textContent.trim();
        if(txt.match(/Dashboard|डैशबोर्ड|ড্যাশবোর্ড|டாஷ்|డాష్|ਡੈਸ਼|ডাশ|डॅश|ડેશ|ಡ್ಯಾ|ഡാ/)) a.childNodes[a.childNodes.length-1].textContent = ' '+t.dashboard;
        if(txt.match(/Browse|ब्राउज़|ব্রাউজ|உலாவு|బ్రౌ|ਬ੍ਰਾ|ब्राउझ|બ્રા|ಬ್ರ|ബ്രൗ/)) a.childNodes[a.childNodes.length-1].textContent = ' '+t.browse;
    });
    localStorage.setItem('fa-lang', code);
}
function setLang(code, flag, name){
    document.getElementById('langFlag').textContent = flag;
    document.getElementById('langLabel').textContent = code.toUpperCase();
    document.getElementById('langMenu').classList.remove('open');
    document.querySelectorAll('.lang-item').forEach(el=>el.classList.toggle('active', el.dataset.lang===code));
    applyLangTexts(code);
}
function toggleLang(){
    document.getElementById('langMenu').classList.toggle('open');
}
document.addEventListener('click', e=>{
    if(!e.target.closest('.lang-wrap')) document.getElementById('langMenu')?.classList.remove('open');
});
// Restore saved lang
(function(){
    const lang = localStorage.getItem('fa-lang') || 'en';
    if(lang !== 'en'){
        const item = document.querySelector('.lang-item[data-lang="'+lang+'"]');
        if(item) item.click();
    }
})();
</script>

</body>
</html>

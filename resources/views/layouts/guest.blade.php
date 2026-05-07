<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>FarmAdviser — Sign In</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { font-family: 'Inter', sans-serif; box-sizing: border-box; }

            body {
                background: #020d18;
                min-height: 100vh;
                overflow-x: hidden;
            }

            /* ── Animated mesh background ── */
            .mesh-bg {
                position: fixed;
                inset: 0;
                z-index: 0;
                background:
                    radial-gradient(ellipse 80% 60% at 10% 20%, rgba(16,185,129,0.12) 0%, transparent 60%),
                    radial-gradient(ellipse 60% 50% at 90% 80%, rgba(59,130,246,0.10) 0%, transparent 60%),
                    radial-gradient(ellipse 50% 40% at 50% 50%, rgba(6,182,212,0.06) 0%, transparent 70%);
            }

            /* ── Floating blobs ── */
            .blob {
                position: fixed;
                border-radius: 50%;
                filter: blur(80px);
                opacity: 0.35;
                animation: blobFloat 12s ease-in-out infinite;
                pointer-events: none;
                z-index: 0;
            }
            .blob-1 {
                width: 500px; height: 500px;
                background: radial-gradient(circle, #10b981 0%, transparent 70%);
                top: -120px; left: -120px;
                animation-delay: 0s; animation-duration: 14s;
            }
            .blob-2 {
                width: 400px; height: 400px;
                background: radial-gradient(circle, #3b82f6 0%, transparent 70%);
                bottom: -100px; right: -100px;
                animation-delay: -5s; animation-duration: 18s;
            }
            .blob-3 {
                width: 300px; height: 300px;
                background: radial-gradient(circle, #06b6d4 0%, transparent 70%);
                top: 40%; left: 30%;
                animation-delay: -9s; animation-duration: 22s;
            }

            @keyframes blobFloat {
                0%, 100% { transform: translate(0, 0) scale(1); }
                33%       { transform: translate(30px, -30px) scale(1.05); }
                66%       { transform: translate(-20px, 20px) scale(0.97); }
            }

            /* ── Particle canvas ── */
            #particles { position: fixed; inset: 0; z-index: 1; pointer-events: none; }

            /* ── Page wrapper ── */
            .auth-wrapper {
                position: relative;
                z-index: 10;
                min-height: 100vh;
                display: flex;
                align-items: stretch;
            }

            /* ── Left panel ── */
            .left-panel {
                display: none;
                flex: 1;
                position: relative;
                overflow: hidden;
                padding: 3rem;
                flex-direction: column;
                justify-content: space-between;
                border-right: 1px solid rgba(255,255,255,0.05);
            }
            @media (min-width: 1024px) { .left-panel { display: flex; } }

            .grid-bg {
                position: absolute;
                inset: 0;
                background-image:
                    linear-gradient(rgba(16,185,129,0.06) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(16,185,129,0.06) 1px, transparent 1px);
                background-size: 48px 48px;
            }

            /* ── Floating cards ── */
            .float-card {
                background: rgba(255,255,255,0.04);
                border: 1px solid rgba(255,255,255,0.08);
                backdrop-filter: blur(16px);
                border-radius: 16px;
                padding: 1.25rem 1.5rem;
                animation: cardFloat 6s ease-in-out infinite;
            }
            .float-card:nth-child(2) { animation-delay: -2s; }
            .float-card:nth-child(3) { animation-delay: -4s; }

            @keyframes cardFloat {
                0%, 100% { transform: translateY(0px); }
                50%       { transform: translateY(-8px); }
            }

            /* ── Metric bar ── */
            .metric-bar {
                height: 4px;
                border-radius: 99px;
                background: linear-gradient(90deg, #10b981, #06b6d4);
                animation: barGrow 2s ease-out forwards;
            }
            @keyframes barGrow {
                from { width: 0; }
            }

            /* ── Right panel ── */
            .right-panel {
                width: 100%;
                max-width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 2rem 1.5rem;
            }
            @media (min-width: 1024px) { .right-panel { width: 480px; min-width: 480px; } }

            /* ── Auth card ── */
            .auth-card {
                width: 100%;
                max-width: 420px;
                background: rgba(255,255,255,0.03);
                border: 1px solid rgba(255,255,255,0.08);
                border-radius: 24px;
                padding: 2.5rem;
                backdrop-filter: blur(24px);
                box-shadow:
                    0 0 0 1px rgba(16,185,129,0.08),
                    0 32px 80px rgba(0,0,0,0.5),
                    inset 0 1px 0 rgba(255,255,255,0.08);
                animation: cardIn 0.6s cubic-bezier(0.16,1,0.3,1) forwards;
                opacity: 0;
                transform: translateY(24px);
            }
            @keyframes cardIn {
                to { opacity: 1; transform: translateY(0); }
            }

            /* ── Glowing border on hover ── */
            .auth-card:hover {
                border-color: rgba(16,185,129,0.2);
                box-shadow:
                    0 0 0 1px rgba(16,185,129,0.15),
                    0 32px 80px rgba(0,0,0,0.5),
                    0 0 40px rgba(16,185,129,0.08),
                    inset 0 1px 0 rgba(255,255,255,0.08);
                transition: all 0.4s ease;
            }

            /* ── Input styles ── */
            .auth-input {
                width: 100%;
                background: rgba(255,255,255,0.04);
                border: 1px solid rgba(255,255,255,0.1);
                border-radius: 12px;
                padding: 0.875rem 1rem;
                color: #f1f5f9;
                font-size: 0.9375rem;
                outline: none;
                transition: all 0.25s ease;
            }
            .auth-input::placeholder { color: rgba(148,163,184,0.5); }
            .auth-input:focus {
                border-color: rgba(16,185,129,0.5);
                background: rgba(16,185,129,0.04);
                box-shadow: 0 0 0 3px rgba(16,185,129,0.12), 0 0 20px rgba(16,185,129,0.06);
            }
            .auth-input:hover:not(:focus) {
                border-color: rgba(255,255,255,0.18);
                background: rgba(255,255,255,0.06);
            }

            /* ── Label ── */
            .auth-label {
                display: block;
                font-size: 0.8125rem;
                font-weight: 500;
                color: rgba(148,163,184,0.9);
                margin-bottom: 0.5rem;
                letter-spacing: 0.02em;
            }

            /* ── Error ── */
            .auth-error { font-size: 0.8rem; color: #f87171; margin-top: 0.375rem; }

            /* ── CTA Button ── */
            .btn-primary {
                width: 100%;
                padding: 0.9375rem;
                background: linear-gradient(135deg, #10b981 0%, #059669 50%, #0d9488 100%);
                color: #fff;
                font-weight: 600;
                font-size: 0.9375rem;
                border: none;
                border-radius: 12px;
                cursor: pointer;
                position: relative;
                overflow: hidden;
                transition: all 0.25s ease;
                box-shadow: 0 4px 20px rgba(16,185,129,0.35), 0 0 0 1px rgba(16,185,129,0.3);
                letter-spacing: 0.01em;
            }
            .btn-primary::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(135deg, rgba(255,255,255,0.15) 0%, transparent 60%);
                opacity: 0;
                transition: opacity 0.25s;
            }
            .btn-primary:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 30px rgba(16,185,129,0.45), 0 0 0 1px rgba(16,185,129,0.4);
            }
            .btn-primary:hover::before { opacity: 1; }
            .btn-primary:active { transform: translateY(0); }

            /* ── Checkbox ── */
            .auth-checkbox {
                width: 16px; height: 16px;
                accent-color: #10b981;
                border-radius: 4px;
                cursor: pointer;
            }

            /* ── Divider ── */
            .auth-divider {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                margin: 1.5rem 0;
            }
            .auth-divider::before, .auth-divider::after {
                content: '';
                flex: 1;
                height: 1px;
                background: rgba(255,255,255,0.07);
            }

            /* ── Glow badge ── */
            .glow-badge {
                display: inline-flex;
                align-items: center;
                gap: 6px;
                padding: 4px 12px;
                background: rgba(16,185,129,0.1);
                border: 1px solid rgba(16,185,129,0.25);
                border-radius: 99px;
                font-size: 0.75rem;
                color: #34d399;
                font-weight: 500;
            }
            .glow-dot {
                width: 6px; height: 6px;
                background: #10b981;
                border-radius: 50%;
                animation: pulse 2s infinite;
            }
            @keyframes pulse {
                0%, 100% { opacity: 1; transform: scale(1); }
                50%       { opacity: 0.5; transform: scale(0.8); }
            }

            /* ── Status badge ── */
            .status-msg {
                background: rgba(16,185,129,0.08);
                border: 1px solid rgba(16,185,129,0.2);
                border-radius: 10px;
                padding: 0.75rem 1rem;
                color: #34d399;
                font-size: 0.875rem;
                margin-bottom: 1.25rem;
            }

            /* ── Auth links ── */
            .auth-link {
                color: rgba(148,163,184,0.7);
                font-size: 0.8125rem;
                text-decoration: none;
                transition: color 0.2s;
            }
            .auth-link:hover { color: #34d399; }

            /* ── Shimmer on left stat numbers ── */
            .stat-num {
                font-size: 1.5rem;
                font-weight: 700;
                background: linear-gradient(90deg, #10b981, #06b6d4, #10b981);
                background-size: 200% auto;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                animation: shimmer 3s linear infinite;
            }
            @keyframes shimmer {
                to { background-position: 200% center; }
            }

            /* ── Scroll fade-in for left items ── */
            .fade-up {
                opacity: 0;
                transform: translateY(20px);
                animation: fadeUp 0.7s cubic-bezier(0.16,1,0.3,1) forwards;
            }
            .fade-up:nth-child(1) { animation-delay: 0.1s; }
            .fade-up:nth-child(2) { animation-delay: 0.25s; }
            .fade-up:nth-child(3) { animation-delay: 0.4s; }
            .fade-up:nth-child(4) { animation-delay: 0.55s; }
            @keyframes fadeUp {
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    </head>
    <body>

        <!-- Animated mesh -->
        <div class="mesh-bg"></div>
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
        <div class="blob blob-3"></div>
        <canvas id="particles"></canvas>

        <div class="auth-wrapper">

            <!-- ══════════ LEFT PANEL ══════════ -->
            <div class="left-panel">
                <div class="grid-bg"></div>

                <!-- Brand -->
                <div class="relative z-10 fade-up">
                    <div class="flex items-center gap-3 mb-3">
                        <div style="width:40px;height:40px;background:linear-gradient(135deg,#10b981,#0d9488);border-radius:10px;display:flex;align-items:center;justify-content:center;box-shadow:0 0 20px rgba(16,185,129,0.4);">
                            <svg width="22" height="22" fill="none" viewBox="0 0 24 24"><path fill="#fff" d="M12 2C7 2 3 6 3 11c0 3.7 2.1 6.9 5.2 8.5l.8.4v1.6c0 .3.2.5.5.5h5c.3 0 .5-.2.5-.5v-1.6l.8-.4C18.9 17.9 21 14.7 21 11c0-5-4-9-9-9zm0 2c3.9 0 7 3.1 7 7 0 2.9-1.7 5.4-4.3 6.7L14 18.2V20h-4v-1.8l-.7-.5C6.7 16.4 5 13.9 5 11c0-3.9 3.1-7 7-7z"/><path fill="#10b981" d="M9 11l2 2 4-4"/></svg>
                        </div>
                        <span style="font-size:1.25rem;font-weight:700;color:#f1f5f9;letter-spacing:-0.02em;">FarmAdviser</span>
                    </div>
                    <div class="glow-badge">
                        <div class="glow-dot"></div>
                        AI-Powered Agriculture Intelligence
                    </div>
                </div>

                <!-- Hero text -->
                <div class="relative z-10 flex-1 flex flex-col justify-center gap-8 my-10">

                    <!-- Headline -->
                    <div class="fade-up">
                        <h1 style="font-size:2.5rem;font-weight:800;color:#f1f5f9;line-height:1.15;letter-spacing:-0.03em;margin-bottom:1rem;">
                            Smart Farming<br>
                            <span style="background:linear-gradient(90deg,#10b981,#06b6d4);-webkit-background-clip:text;-webkit-text-fill-color:transparent;">Powered by AI</span>
                        </h1>
                        <p style="color:rgba(148,163,184,0.75);font-size:1rem;line-height:1.7;max-width:360px;">
                            Real-time weather intelligence, crop recommendations and climate insights — all in one platform.
                        </p>
                    </div>

                    <!-- Live metric cards -->
                    <div class="fade-up flex flex-col gap-4">

                        <!-- Card 1: Weather -->
                        <div class="float-card">
                            <div class="flex items-center justify-between mb-3">
                                <div class="flex items-center gap-2">
                                    <span style="font-size:1.25rem;">🌤️</span>
                                    <span style="font-size:0.8125rem;font-weight:500;color:rgba(148,163,184,0.8);">Live Weather — Delhi</span>
                                </div>
                                <div class="glow-badge" style="font-size:0.7rem;padding:2px 8px;">LIVE</div>
                            </div>
                            <div class="flex items-end justify-between">
                                <div>
                                    <div class="stat-num">34°C</div>
                                    <div style="font-size:0.75rem;color:rgba(148,163,184,0.55);margin-top:2px;">Partly Cloudy · 72% humidity</div>
                                </div>
                                <div style="text-align:right;">
                                    <div style="font-size:0.75rem;color:rgba(148,163,184,0.6);">Wind</div>
                                    <div style="font-size:1rem;font-weight:600;color:#f1f5f9;">12 km/h ↗</div>
                                </div>
                            </div>
                        </div>

                        <!-- Card 2: Crop Advisory -->
                        <div class="float-card">
                            <div class="flex items-center gap-2 mb-3">
                                <span style="font-size:1.25rem;">🌾</span>
                                <span style="font-size:0.8125rem;font-weight:500;color:rgba(148,163,184,0.8);">AI Crop Advisory</span>
                            </div>
                            <div style="font-size:0.875rem;color:#f1f5f9;margin-bottom:0.75rem;">Wheat · Summer season</div>
                            <div style="font-size:0.8rem;color:rgba(148,163,184,0.65);line-height:1.6;">
                                ✅ Optimal irrigation recommended<br>
                                ⚠️ Apply NPK fertilizer within 3 days
                            </div>
                            <div style="margin-top:1rem;">
                                <div style="font-size:0.7rem;color:rgba(148,163,184,0.5);margin-bottom:4px;">Confidence Score</div>
                                <div style="height:4px;background:rgba(255,255,255,0.06);border-radius:99px;overflow:hidden;">
                                    <div class="metric-bar" style="width:87%"></div>
                                </div>
                                <div style="font-size:0.7rem;color:#34d399;margin-top:4px;">87% confidence</div>
                            </div>
                        </div>

                        <!-- Card 3: Analytics row -->
                        <div class="float-card" style="padding:1rem 1.25rem;">
                            <div class="flex items-center justify-between">
                                <div style="text-align:center;">
                                    <div class="stat-num" style="font-size:1.1rem;">2.4k</div>
                                    <div style="font-size:0.7rem;color:rgba(148,163,184,0.5);">Farmers</div>
                                </div>
                                <div style="width:1px;height:36px;background:rgba(255,255,255,0.07);"></div>
                                <div style="text-align:center;">
                                    <div class="stat-num" style="font-size:1.1rem;">98%</div>
                                    <div style="font-size:0.7rem;color:rgba(148,163,184,0.5);">Accuracy</div>
                                </div>
                                <div style="width:1px;height:36px;background:rgba(255,255,255,0.07);"></div>
                                <div style="text-align:center;">
                                    <div class="stat-num" style="font-size:1.1rem;">50+</div>
                                    <div style="font-size:0.7rem;color:rgba(148,163,184,0.5);">Crops</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Footer -->
                <div class="relative z-10 fade-up">
                    <p style="font-size:0.75rem;color:rgba(148,163,184,0.35);">
                        © 2026 FarmAdviser · AI Agriculture Intelligence Platform
                    </p>
                </div>
            </div>

            <!-- ══════════ RIGHT PANEL ══════════ -->
            <div class="right-panel">
                <div class="auth-card">
                    {{ $slot }}
                </div>
            </div>

        </div>

        <!-- Particle animation script -->
        <script>
        (function() {
            const canvas = document.getElementById('particles');
            const ctx = canvas.getContext('2d');
            let particles = [];

            function resize() {
                canvas.width  = window.innerWidth;
                canvas.height = window.innerHeight;
            }
            resize();
            window.addEventListener('resize', resize);

            for (let i = 0; i < 60; i++) {
                particles.push({
                    x: Math.random() * window.innerWidth,
                    y: Math.random() * window.innerHeight,
                    vx: (Math.random() - 0.5) * 0.3,
                    vy: -Math.random() * 0.4 - 0.1,
                    r: Math.random() * 1.5 + 0.5,
                    o: Math.random() * 0.4 + 0.1,
                    c: Math.random() > 0.5 ? '16,185,129' : '59,130,246'
                });
            }

            function animate() {
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                particles.forEach(p => {
                    p.x += p.vx; p.y += p.vy;
                    if (p.y < -10) { p.y = canvas.height + 10; p.x = Math.random() * canvas.width; }
                    if (p.x < -10 || p.x > canvas.width + 10) p.x = Math.random() * canvas.width;
                    ctx.beginPath();
                    ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
                    ctx.fillStyle = `rgba(${p.c},${p.o})`;
                    ctx.fill();
                });
                requestAnimationFrame(animate);
            }
            animate();
        })();
        </script>
    </body>
</html>

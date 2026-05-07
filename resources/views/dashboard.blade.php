@extends('layouts.app-farm')
@section('title', 'Dashboard')

@php
$weatherCondition = $weather['condition'] ?? '';
$currentSeason    = $season ?? 'Summer';
$themeKey = match(true) {
    str_contains($weatherCondition,'Rain') || str_contains($weatherCondition,'Drizzle') => 'rain',
    str_contains($weatherCondition,'Snow')        => 'winter',
    str_contains($weatherCondition,'Storm')       => 'storm',
    str_contains($weatherCondition,'Cloud')       => 'cloudy',
    $currentSeason === 'Winter'                   => 'winter',
    $currentSeason === 'Monsoon'                  => 'rain',
    $currentSeason === 'Spring'                   => 'spring',
    default                                       => 'sunny',
};
$themeColors = [
    'sunny'  => ['from'=>'rgba(245,158,11,0.12)',  'to'=>'rgba(16,185,129,0.10)',  'accent'=>'#f59e0b', 'glow'=>'rgba(245,158,11,0.3)'],
    'rain'   => ['from'=>'rgba(6,182,212,0.12)',   'to'=>'rgba(59,130,246,0.10)', 'accent'=>'#06b6d4', 'glow'=>'rgba(6,182,212,0.3)'],
    'storm'  => ['from'=>'rgba(139,92,246,0.12)',  'to'=>'rgba(59,130,246,0.10)', 'accent'=>'#8b5cf6', 'glow'=>'rgba(139,92,246,0.3)'],
    'cloudy' => ['from'=>'rgba(100,116,139,0.12)', 'to'=>'rgba(71,85,105,0.10)',  'accent'=>'#94a3b8', 'glow'=>'rgba(100,116,139,0.3)'],
    'winter' => ['from'=>'rgba(147,197,253,0.12)', 'to'=>'rgba(6,182,212,0.08)',  'accent'=>'#93c5fd', 'glow'=>'rgba(147,197,253,0.3)'],
    'spring' => ['from'=>'rgba(16,185,129,0.14)',  'to'=>'rgba(34,197,94,0.08)',  'accent'=>'#10b981', 'glow'=>'rgba(16,185,129,0.35)'],
];
$tc = $themeColors[$themeKey];
$weatherEmoji = match(true) {
    str_contains($weatherCondition,'Rain')  => '🌧️',
    str_contains($weatherCondition,'Storm') => '⛈️',
    str_contains($weatherCondition,'Snow')  => '❄️',
    str_contains($weatherCondition,'Cloud') => '☁️',
    default => '☀️',
};
@endphp

@section('content')
<style>
:root {
    --theme-accent: {{ $tc['accent'] }};
    --theme-glow:   {{ $tc['glow'] }};
}
/* Dashboard ambient */
.dash-ambient {
    position: fixed; inset: 0; z-index: 0; pointer-events: none;
    background: radial-gradient(ellipse 60% 50% at 20% 30%, {{ $tc['from'] }} 0%, transparent 60%),
                radial-gradient(ellipse 50% 40% at 80% 70%, {{ $tc['to'] }} 0%, transparent 60%);
    transition: background 1s ease;
}

/* ── Weather particle canvas ── */
#wxCanvas { position:fixed; inset:0; z-index:1; pointer-events:none; }

/* ── Glass panel ── */
.g-panel {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 20px;
    backdrop-filter: blur(20px);
    box-shadow: 0 8px 32px rgba(0,0,0,0.35), inset 0 1px 0 rgba(255,255,255,0.07);
    transition: border-color .3s, box-shadow .3s;
    overflow: hidden;
}
.g-panel:hover {
    border-color: rgba(255,255,255,0.12);
    box-shadow: 0 12px 40px rgba(0,0,0,0.4), 0 0 0 1px var(--theme-glow), inset 0 1px 0 rgba(255,255,255,0.09);
}
.g-header {
    padding: 1rem 1.4rem;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    font-size: 0.8rem; font-weight: 700;
    color: rgba(148,163,184,0.7);
    text-transform: uppercase; letter-spacing: 0.08em;
    display: flex; align-items: center; gap: 8px;
}
.g-body { padding: 1.4rem; }

/* ── Metric tile ── */
.metric-tile {
    background: rgba(255,255,255,0.03);
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 14px; padding: 1rem 1.1rem;
    transition: all .25s ease;
}
.metric-tile:hover { background: rgba(255,255,255,0.06); transform: translateY(-2px); }
.metric-label { font-size: 0.72rem; font-weight: 600; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.08em; margin-bottom: 4px; }
.metric-value { font-size: 1.3rem; font-weight: 800; color: var(--text-pri); letter-spacing: -0.02em; }

/* ── Temp display ── */
.temp-big {
    font-size: 4.5rem; font-weight: 900; line-height: 1;
    letter-spacing: -0.04em;
    background: linear-gradient(135deg, var(--text-pri) 0%, var(--theme-accent) 100%);
    -webkit-background-clip: text; -webkit-text-fill-color: transparent;
}

/* ── Advisory badge ── */
.adv-badge {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 3px 10px; border-radius: 99px;
    font-size: 0.72rem; font-weight: 600;
}
.adv-badge-green { background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.25); }
.adv-badge-blue  { background: rgba(6,182,212,0.12);  color: #67e8f9; border: 1px solid rgba(6,182,212,0.25); }
.adv-badge-amber { background: rgba(245,158,11,0.12); color: #fbbf24; border: 1px solid rgba(245,158,11,0.25); }

/* ── Progress bar ── */
.prog-bar { height: 4px; background: rgba(255,255,255,0.06); border-radius: 99px; overflow: hidden; margin-top: 6px; }
.prog-fill { height: 100%; border-radius: 99px; background: linear-gradient(90deg, var(--theme-accent), rgba(16,185,129,0.6)); animation: fillBar .8s ease-out forwards; }
@keyframes fillBar { from { width: 0 !important; } }

/* ── Alert bar ── */
.rain-alert {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: 14px; margin-bottom: 1.25rem;
    background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2);
    color: #fca5a5; font-size: 0.9rem; font-weight: 500;
    animation: alertPulse 3s ease-in-out infinite;
}
@keyframes alertPulse { 0%,100%{border-color:rgba(239,68,68,0.2)} 50%{border-color:rgba(239,68,68,0.45)} }
.error-bar {
    display: flex; align-items: center; gap: 12px;
    padding: 14px 18px; border-radius: 14px; margin-bottom: 1.25rem;
    background: rgba(245,158,11,0.08); border: 1px solid rgba(245,158,11,0.2);
    color: #fbbf24; font-size: 0.9rem;
}

/* ── Empty state ── */
.empty-state { padding: 4rem 2rem; text-align: center; }
.empty-orb {
    width: 100px; height: 100px; margin: 0 auto 1.5rem;
    background: radial-gradient(circle, var(--theme-accent) 0%, transparent 70%);
    border-radius: 50%; opacity: 0.3;
    animation: orbPulse 3s ease-in-out infinite;
}
@keyframes orbPulse { 0%,100%{transform:scale(1);opacity:.3} 50%{transform:scale(1.1);opacity:.5} }

/* ── Section fade-in ── */
.s-enter { animation: sEnter .5s cubic-bezier(.16,1,.3,1) both; }
.s-enter:nth-child(1){animation-delay:.05s} .s-enter:nth-child(2){animation-delay:.12s} .s-enter:nth-child(3){animation-delay:.2s}
@keyframes sEnter { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }

/* ── Insight card ── */
.insight-row { display: flex; gap: 10px; flex-wrap: wrap; margin-top: 1rem; }
.insight-chip {
    display: flex; align-items: center; gap: 6px;
    padding: 6px 12px; border-radius: 10px;
    background: var(--surface); border: 1px solid var(--border);
    font-size: 0.78rem; color: var(--text-sec);
    transition: all .2s;
}
.insight-chip:hover { background: var(--surface-2); color: var(--text-pri); }

/* ── Glow dot ── */
.gdot { width:7px;height:7px;border-radius:50%;background:var(--theme-accent);display:inline-block;box-shadow:0 0 6px var(--theme-accent);animation:gdotP 2s infinite; }
@keyframes gdotP{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.5;transform:scale(.75)}}
</style>

<!-- Weather Particle Canvas -->
<canvas id="wxCanvas"></canvas>
<div class="dash-ambient"></div>

<div class="container" style="position:relative;z-index:10;">

    {{-- ── Page header ── --}}
    <div class="s-enter" style="margin-bottom:1.75rem;">
        <div style="display:flex;align-items:center;gap:10px;margin-bottom:.4rem;">
            <div class="gdot"></div>
            <span style="font-size:.75rem;font-weight:600;color:var(--theme-accent);text-transform:uppercase;letter-spacing:.1em;">{{ __('messages.live_intelligence') }}</span>
        </div>
        <h1 style="font-size:2rem;font-weight:900;color:#f1f5f9;letter-spacing:-.03em;margin-bottom:.3rem;">
            {{ __('messages.farmer_dashboard') }}
        </h1>
        <p style="font-size:.9rem;color:rgba(148,163,184,.55);">
            {{ __('messages.dash_subtitle') }}
        </p>
    </div>

    {{-- ── Rain alert ── --}}
    @if($rainAlert ?? false)
    <div class="rain-alert s-enter">
        <span style="font-size:1.4rem;">🚨</span>
        <div><strong>{{ __('messages.rain_alert_title') }}</strong> {{ __('messages.rain_alert_text') }}</div>
    </div>
    @endif

    {{-- ── API error ── --}}
    @if(isset($error))
    <div class="error-bar s-enter">
        <span style="font-size:1.2rem;">⚠️</span>
        <div><strong>{{ __('messages.weather_error') }}</strong> {{ $error }}</div>
    </div>
    @endif

    {{-- ── Main grid ── --}}
    <div style="display:grid;grid-template-columns:320px 1fr;gap:1.5rem;align-items:start;">

        {{-- ════ LEFT: Search panel ════ --}}
        <div class="g-panel s-enter">
            <div class="g-header">
                <svg width="14" height="14" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
                {{ __('messages.search_params') }}
            </div>
            <div class="g-body">
                <form action="{{ route('dashboard') }}" method="GET" id="dashboard-form">
                    @csrf

                    <div class="form-group">
                        <label class="form-label" for="city">{{ __('messages.city_label') }}</label>
                        <input type="text" id="city" name="city" class="form-control"
                               value="{{ $city ?? '' }}"
                               placeholder="{{ __('messages.city_placeholder') }}"
                               required autocomplete="off">
                        @error('city')
                            <p style="color:#fca5a5;font-size:.8rem;margin-top:4px;">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="crop_id">{{ __('messages.crop_label') }}</label>
                        <select id="crop_id" name="crop_id" class="form-control">
                            <option value="">{{ __('messages.select_crop') }}</option>
                            @foreach($crops as $crop)
                                <option value="{{ $crop->id }}" {{ ($cropId ?? '') == $crop->id ? 'selected' : '' }}>
                                    {{ $crop->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label class="form-label" for="season">{{ __('messages.season_label') }}</label>
                        <select id="season" name="season" class="form-control">
                            @foreach(['Spring','Summer','Monsoon','Winter'] as $s)
                                <option value="{{ $s }}" {{ ($season ?? '') === $s ? 'selected' : '' }}>{{ $s }}</option>
                            @endforeach
                        </select>
                        <p style="font-size:.75rem;color:rgba(148,163,184,.4);margin-top:5px;">{{ __('messages.auto_detected') }}</p>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width:100%;justify-content:center;margin-top:4px;">
                        <svg width="15" height="15" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M12 2a10 10 0 1 0 0 20A10 10 0 0 0 12 2z"/><path d="M12 6v6l4 2"/></svg>
                        {{ __('messages.get_advisory_btn') }}
                    </button>
                </form>

                <div style="margin-top:1.25rem;padding:12px 14px;background:rgba(16,185,129,.07);border:1px solid rgba(16,185,129,.15);border-radius:12px;">
                    <p style="font-size:.75rem;font-weight:700;color:#34d399;margin-bottom:3px;">{{ __('messages.smart_tip') }}</p>
                    <p style="font-size:.76rem;color:rgba(148,163,184,.65);line-height:1.6;">
                        {{ __('messages.smart_tip_text') }}
                    </p>
                </div>
            </div>
        </div>

        {{-- ════ RIGHT: Results ════ --}}
        <div style="display:flex;flex-direction:column;gap:1.25rem;">

            @if($weather ?? false)

                {{-- ── Weather Card ── --}}
                <div class="g-panel s-enter">
                    <div class="g-header">
                        <span>{{ $weatherEmoji }}</span>
                        {{ __('messages.current_weather') }} — {{ $weather['city'] }}, {{ $weather['country'] }}
                        @if($weather['is_fallback'])
                            <span class="adv-badge adv-badge-amber" style="margin-left:auto;">{{ __('messages.cached') }}</span>
                        @else
                            <span style="margin-left:auto;display:flex;align-items:center;gap:5px;font-size:.72rem;color:#34d399;">
                                <span class="gdot"></span> {{ __('messages.live') }}
                            </span>
                        @endif
                    </div>
                    <div class="g-body">
                        <div style="display:grid;grid-template-columns:auto 1fr;gap:2rem;align-items:center;">

                            {{-- Temp + icon --}}
                            <div style="text-align:center;">
                                <img src="https://openweathermap.org/img/wn/{{ $weather['icon'] }}@2x.png"
                                     alt="{{ $weather['description'] }}"
                                     style="width:72px;height:72px;filter:drop-shadow(0 0 12px var(--theme-glow));"
                                     onerror="this.style.display='none';this.nextElementSibling.style.display='block';">
                                <span style="display:none;font-size:3.5rem;">{{ $weatherEmoji }}</span>
                                <div class="temp-big">{{ $weather['temp'] }}°</div>
                                <div style="font-size:.85rem;color:rgba(148,163,184,.6);margin-top:4px;">{{ $weather['description'] }}</div>
                            </div>

                            {{-- Metric grid --}}
                            <div style="display:grid;grid-template-columns:1fr 1fr;gap:.75rem;">
                                @foreach([
                                    ['🌡️', __('messages.feels_like'),  $weather['feels_like'].'°C', 70],
                                    ['💧', __('messages.humidity'),    $weather['humidity'].'%',      $weather['humidity']],
                                    ['💨', __('messages.wind_speed'),  $weather['wind_speed'].' m/s', 50],
                                    ['🔖', __('messages.condition'),   $weather['condition'],          null],
                                ] as [$icon,$label,$val,$prog])
                                <div class="metric-tile">
                                    <div class="metric-label">{{ $icon }} {{ $label }}</div>
                                    <div class="metric-value">{{ $val }}</div>
                                    @if($prog !== null)
                                    <div class="prog-bar"><div class="prog-fill" style="width:{{ min($prog,100) }}%;"></div></div>
                                    @endif
                                </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- Insight chips --}}
                        <div class="insight-row">
                            <div class="insight-chip">📅 {{ __('messages.season_chip') }}: {{ $currentSeason }}</div>
                            <div class="insight-chip">🌍 {{ $weather['country'] }}</div>
                            <div class="insight-chip">🕐 {{ __('messages.updated_now') }}</div>
                        </div>
                    </div>
                </div>

                {{-- ── Recommendation Alert ── --}}
                @if($alert ?? false)
                <div class="g-panel s-enter" style="border-color:rgba(16,185,129,.15);">
                    <div class="g-body" style="display:flex;align-items:flex-start;gap:14px;">
                        <span style="font-size:2rem;line-height:1;flex-shrink:0;">{{ $alert['icon'] }}</span>
                        <div>
                            <p style="font-size:.72rem;font-weight:700;color:var(--theme-accent);text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">{{ __('messages.ai_recommendation') }}</p>
                            <p style="color:rgba(241,245,249,.85);font-size:.9rem;line-height:1.65;">{{ $alert['message'] }}</p>
                        </div>
                    </div>
                </div>
                @endif

                {{-- ── Crop Advisory ── --}}
                @if($cropId ?? false)
                <div class="g-panel s-enter">
                    <div class="g-header">
                        🌱 {{ __('messages.crop_advisory') }}
                    </div>
                    <div class="g-body">
                        @if($advisory)
                            <div style="display:flex;gap:14px;align-items:flex-start;">
                                <div style="font-size:2.2rem;flex-shrink:0;line-height:1;">📋</div>
                                <div style="flex:1;">
                                    <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:12px;">
                                        <span class="adv-badge adv-badge-green">{{ $advisory->crop->name }}</span>
                                        <span class="adv-badge adv-badge-blue">{{ $advisory->season }}</span>
                                        <span class="adv-badge adv-badge-amber">{{ $advisory->weather_condition }}</span>
                                    </div>
                                    <p style="color:rgba(241,245,249,.8);line-height:1.75;font-size:.9rem;">{{ $advisory->advice }}</p>
                                </div>
                            </div>
                        @else
                            <div style="text-align:center;padding:2.5rem 1rem;">
                                <div style="font-size:2.5rem;margin-bottom:.75rem;">🔍</div>
                                <p style="font-weight:700;color:rgba(241,245,249,.8);margin-bottom:.4rem;">{{ __('messages.no_advisory') }}</p>
                                <p style="font-size:.85rem;color:rgba(148,163,184,.5);line-height:1.6;">
                                    {{ __('messages.no_advisory_sub') }}
                                    <a href="{{ route('advisory.filter', ['crop_id' => $cropId]) }}"
                                       style="color:var(--theme-accent);text-decoration:none;">
                                       {{ __('messages.browse_all') }}
                                    </a>
                                </p>
                            </div>
                        @endif
                    </div>
                </div>
                @endif

            @else

                {{-- ── Empty State ── --}}
                <div class="g-panel s-enter">
                    <div class="empty-state">
                        <div class="empty-orb"></div>
                        <h2 style="font-size:1.5rem;font-weight:800;color:#f1f5f9;letter-spacing:-.02em;margin-bottom:.6rem;">
                            {{ __('messages.welcome') }} {{ auth()->user()->name }}!
                        </h2>
                        <p style="color:rgba(148,163,184,.5);max-width:360px;margin:0 auto 2rem;line-height:1.7;font-size:.9rem;">
                            {{ __('messages.welcome_sub') }}
                        </p>
                        <div style="display:flex;gap:1.5rem;justify-content:center;flex-wrap:wrap;">
                            @foreach([
                                ['☀️', __('messages.weather_intel'), __('messages.weather_intel_sub')],
                                ['🌾', __('messages.crop_advisory_s'), __('messages.crop_adv_sub')],
                                ['📊', __('messages.analytics'), __('messages.analytics_sub')]
                            ] as [$e,$t,$s])
                            <div style="text-align:center;">
                                <div style="font-size:1.75rem;margin-bottom:4px;">{{ $e }}</div>
                                <div style="font-size:.8rem;font-weight:700;color:rgba(241,245,249,.7);">{{ $t }}</div>
                                <div style="font-size:.72rem;color:rgba(148,163,184,.4);">{{ $s }}</div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            @endif

        </div>{{-- end right --}}
    </div>{{-- end grid --}}
</div>

<script>
(function(){
    const canvas = document.getElementById('wxCanvas');
    const ctx    = canvas.getContext('2d');
    const theme  = @json($themeKey);
    let   W, H, particles = [];

    function resize(){ W = canvas.width = innerWidth; H = canvas.height = innerHeight; }
    resize(); window.addEventListener('resize', resize);

    function mkParticle(){
        if(theme === 'rain'){
            return { x:Math.random()*W, y:-20, vx:-1+Math.random(), vy:8+Math.random()*6,
                     len:12+Math.random()*10, alpha:.15+Math.random()*.2, type:'rain' };
        } else if(theme === 'winter'){
            return { x:Math.random()*W, y:-10, vx:(Math.random()-.5)*.5, vy:.4+Math.random()*.6,
                     r:1.5+Math.random()*2.5, alpha:.2+Math.random()*.3, type:'snow' };
        } else if(theme === 'storm'){
            return { x:Math.random()*W, y:-20, vx:-2+Math.random(), vy:12+Math.random()*8,
                     len:18+Math.random()*12, alpha:.1+Math.random()*.15, type:'rain' };
        } else if(theme === 'spring'){
            return { x:Math.random()*W, y:Math.random()*H, vx:(Math.random()-.5)*.4, vy:-.2-.3*Math.random(),
                     r:2+Math.random()*2, alpha:.15+Math.random()*.2, type:'petal', hue:120+Math.random()*60 };
        } else { // sunny / cloudy
            return { x:Math.random()*W, y:Math.random()*H, vx:(Math.random()-.5)*.2, vy:-.1-.2*Math.random(),
                     r:.8+Math.random()*1.5, alpha:.08+Math.random()*.15, type:'dust' };
        }
    }

    const COUNT = theme==='rain'||theme==='storm' ? 80 : 40;
    for(let i=0;i<COUNT;i++) particles.push(mkParticle());

    function draw(){
        ctx.clearRect(0,0,W,H);
        particles.forEach((p,i)=>{
            if(p.type==='rain'||p.type==='storm'){
                ctx.beginPath();
                ctx.moveTo(p.x,p.y); ctx.lineTo(p.x+p.vx*2,p.y+p.len);
                ctx.strokeStyle=`rgba(147,210,250,${p.alpha})`; ctx.lineWidth=.8; ctx.stroke();
                p.x+=p.vx; p.y+=p.vy;
                if(p.y>H+20) particles[i]=mkParticle();
            } else if(p.type==='snow'){
                ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
                ctx.fillStyle=`rgba(200,220,255,${p.alpha})`; ctx.fill();
                p.x+=p.vx; p.y+=p.vy;
                if(p.y>H+10) particles[i]=mkParticle();
            } else if(p.type==='petal'){
                ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
                ctx.fillStyle=`hsla(${p.hue},70%,70%,${p.alpha})`; ctx.fill();
                p.x+=p.vx; p.y+=p.vy;
                if(p.y<-10||p.x<-10||p.x>W+10) particles[i]=mkParticle();
            } else {
                ctx.beginPath(); ctx.arc(p.x,p.y,p.r,0,Math.PI*2);
                ctx.fillStyle=`rgba(245,158,11,${p.alpha})`; ctx.fill();
                p.x+=p.vx; p.y+=p.vy;
                if(p.y<-10||p.x<-10||p.x>W+10) particles[i]=mkParticle();
            }
        });
        requestAnimationFrame(draw);
    }
    draw();
})();
</script>

{{-- ═══ FLOATING HELPER BUTTON + ONBOARDING SYSTEM ═══ --}}
<style>
/* Helper Button */
.helper-orb {
    position: fixed; bottom: 28px; right: 28px; z-index: 1000;
    width: 56px; height: 56px; border-radius: 50%;
    background: linear-gradient(135deg,#10b981,#059669);
    border: none; cursor: pointer;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.4rem;
    box-shadow: 0 4px 24px rgba(16,185,129,0.5), 0 0 0 0 rgba(16,185,129,0.4);
    animation: orbPulseHelper 2.5s ease-in-out infinite;
    transition: all .3s cubic-bezier(.34,1.56,.64,1);
}
.helper-orb:hover { transform: scale(1.12); box-shadow: 0 6px 32px rgba(16,185,129,0.65); }
@keyframes orbPulseHelper {
    0%,100%{box-shadow:0 4px 24px rgba(16,185,129,0.5),0 0 0 0 rgba(16,185,129,0.4)}
    50%{box-shadow:0 4px 24px rgba(16,185,129,0.5),0 0 0 12px rgba(16,185,129,0)}
}
/* Helper Panel */
.helper-panel {
    position: fixed; bottom: 96px; right: 28px; z-index: 1000;
    width: 320px; border-radius: 20px;
    background: rgba(10,22,40,0.95);
    border: 1px solid rgba(16,185,129,0.25);
    backdrop-filter: blur(24px);
    box-shadow: 0 20px 60px rgba(0,0,0,0.5), 0 0 0 1px rgba(16,185,129,0.1);
    transform: translateY(16px) scale(0.96); opacity: 0; pointer-events: none;
    transition: all .3s cubic-bezier(.16,1,.3,1);
    overflow: hidden;
}
.helper-panel.open { transform: translateY(0) scale(1); opacity: 1; pointer-events: auto; }
.helper-panel-header {
    padding: 16px 18px 12px;
    background: linear-gradient(135deg,rgba(16,185,129,0.15),rgba(6,182,212,0.08));
    border-bottom: 1px solid rgba(255,255,255,0.06);
}
.helper-tips { padding: 12px 6px; max-height: 340px; overflow-y: auto; }
.helper-tip-item {
    display: flex; align-items: flex-start; gap: 12px;
    padding: 11px 12px; border-radius: 12px; cursor: pointer;
    transition: background .15s;
}
.helper-tip-item:hover { background: rgba(16,185,129,0.08); }
.helper-tip-icon { font-size: 1.4rem; flex-shrink: 0; line-height: 1.3; }
.helper-tip-title { font-size: .83rem; font-weight: 700; color: #f1f5f9; margin-bottom: 2px; }
.helper-tip-desc { font-size: .76rem; color: rgba(148,163,184,0.65); line-height: 1.5; }
.helper-start-tour {
    margin: 0 12px 12px;
    padding: 11px; border-radius: 12px; border: none;
    background: linear-gradient(135deg,#10b981,#059669);
    color: #fff; font-weight: 700; font-size: .88rem;
    width: calc(100% - 24px); cursor: pointer;
    transition: all .2s; display: flex; align-items: center; justify-content: center; gap: 8px;
}
.helper-start-tour:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(16,185,129,0.4); }

/* Tour Overlay */
.tour-overlay {
    position: fixed; inset: 0; z-index: 2000;
    background: rgba(0,0,0,0.75); backdrop-filter: blur(4px);
    display: none; align-items: center; justify-content: center;
}
.tour-overlay.active { display: flex; }
.tour-card {
    background: rgba(10,22,40,0.97); border: 1px solid rgba(16,185,129,0.3);
    border-radius: 24px; padding: 2rem; max-width: 420px; width: 90%;
    box-shadow: 0 24px 80px rgba(0,0,0,0.6), 0 0 60px rgba(16,185,129,0.1);
    animation: tourCardIn .4s cubic-bezier(.16,1,.3,1);
    text-align: center;
}
@keyframes tourCardIn { from{opacity:0;transform:scale(.9)translateY(20px)} to{opacity:1;transform:scale(1)translateY(0)} }
.tour-emoji { font-size: 3rem; margin-bottom: 1rem; display: block; }
.tour-title { font-size: 1.3rem; font-weight: 800; color: #f1f5f9; margin-bottom: .6rem; letter-spacing: -.02em; }
.tour-desc { font-size: .9rem; color: rgba(148,163,184,.7); line-height: 1.7; margin-bottom: 1.5rem; }
.tour-progress {
    display: flex; justify-content: center; gap: 6px; margin-bottom: 1.25rem;
}
.tour-dot {
    width: 8px; height: 8px; border-radius: 50%;
    background: rgba(255,255,255,0.15); transition: all .3s;
}
.tour-dot.active { background: #10b981; width: 24px; border-radius: 4px; }
.tour-btns { display: flex; gap: 10px; justify-content: center; }
.tour-btn {
    padding: 10px 22px; border-radius: 10px; font-size: .88rem;
    font-weight: 600; cursor: pointer; border: none; transition: all .2s;
}
.tour-btn-skip { background: rgba(255,255,255,0.06); color: rgba(148,163,184,.7); }
.tour-btn-skip:hover { background: rgba(255,255,255,0.1); }
.tour-btn-next { background: linear-gradient(135deg,#10b981,#059669); color: #fff; }
.tour-btn-next:hover { transform: translateY(-1px); box-shadow: 0 4px 16px rgba(16,185,129,0.4); }
.tour-step-label { font-size: .72rem; color: rgba(148,163,184,.4); margin-bottom: .75rem; }

/* Welcome Modal */
.welcome-modal-overlay {
    position: fixed; inset: 0; z-index: 3000;
    background: rgba(0,0,0,0.85); backdrop-filter: blur(8px);
    display: flex; align-items: center; justify-content: center;
}
.welcome-modal {
    background: linear-gradient(135deg,rgba(10,22,40,0.98),rgba(2,13,24,0.99));
    border: 1px solid rgba(16,185,129,0.3);
    border-radius: 28px; padding: 2.5rem; max-width: 460px; width: 90%;
    box-shadow: 0 32px 100px rgba(0,0,0,0.7), 0 0 80px rgba(16,185,129,0.08);
    animation: tourCardIn .5s cubic-bezier(.16,1,.3,1);
    text-align: center; position: relative;
}
.welcome-glow {
    position: absolute; top: -40px; left: 50%; transform: translateX(-50%);
    width: 120px; height: 120px; border-radius: 50%;
    background: radial-gradient(circle, rgba(16,185,129,0.4) 0%, transparent 70%);
    pointer-events: none;
}
.welcome-lang-grid {
    display: grid; grid-template-columns: 1fr 1fr;
    gap: 8px; margin: 1.2rem 0;
}
.welcome-lang-btn {
    padding: 10px; border-radius: 11px; border: 1px solid rgba(255,255,255,0.08);
    background: rgba(255,255,255,0.04); cursor: pointer;
    font-size: .82rem; color: rgba(148,163,184,.8); transition: all .2s;
    text-decoration: none; display: block; text-align: center;
}
.welcome-lang-btn:hover { background: rgba(16,185,129,0.1); border-color: rgba(16,185,129,0.3); color: #34d399; }
</style>

{{-- Welcome Modal (first visit) --}}
<div class="welcome-modal-overlay" id="welcomeModal" style="display:none;">
    <div class="welcome-modal">
        <div class="welcome-glow"></div>
        <div style="font-size:3.5rem;margin-bottom:1rem;">🌾</div>
        <h2 style="font-size:1.5rem;font-weight:800;color:#f1f5f9;margin-bottom:.5rem;letter-spacing:-.02em;">
            {{ __('messages.tour_welcome') }}
        </h2>
        <p style="font-size:.9rem;color:rgba(148,163,184,.65);margin-bottom:1rem;line-height:1.6;">
            {{ __('messages.tour_welcome_sub') }}
        </p>
        <p style="font-size:.78rem;font-weight:600;color:rgba(148,163,184,.4);text-transform:uppercase;letter-spacing:.08em;margin-bottom:.7rem;">Choose Language / भाषा चुनें</p>
        <div class="welcome-lang-grid">
            @foreach([['en','🇬🇧','English'],['hi','🇮🇳','हिन्दी'],['pa','🇮🇳','ਪੰਜਾਬੀ'],['bn','🇧🇩','বাংলা'],['ta','🇮🇳','தமிழ்'],['te','🇮🇳','తెలుగు'],['mr','🇮🇳','मराठी'],['gu','🇮🇳','ગુજરાતી'],['kn','🇮🇳','ಕನ್ನಡ'],['ml','🇮🇳','മലയാളം']] as [$c,$f,$n])
            <a class="welcome-lang-btn" href="{{ route('lang.switch', $c) }}?welcome=1">{{ $f }} {{ $n }}</a>
            @endforeach
        </div>
        <button onclick="closeWelcome()" class="tour-btn tour-btn-next" style="width:100%;padding:13px;font-size:.95rem;margin-top:.5rem;">
            {{ __('messages.tour_start') }} 🚀
        </button>
        <button onclick="closeWelcome()" class="tour-btn tour-btn-skip" style="width:100%;margin-top:8px;">
            {{ __('messages.tour_skip') }}
        </button>
    </div>
</div>

{{-- Tour Overlay --}}
<div class="tour-overlay" id="tourOverlay">
    <div class="tour-card" id="tourCard">
        <span class="tour-emoji" id="tourEmoji">🌾</span>
        <div class="tour-progress" id="tourProgress"></div>
        <div class="tour-step-label" id="tourStepLabel"></div>
        <div class="tour-title" id="tourTitle"></div>
        <div class="tour-desc" id="tourDesc"></div>
        <div class="tour-btns">
            <button class="tour-btn tour-btn-skip" onclick="endTour()">{{ __('messages.tour_skip') }}</button>
            <button class="tour-btn tour-btn-next" id="tourNextBtn" onclick="nextStep()">{{ __('messages.tour_next') }}</button>
        </div>
    </div>
</div>

{{-- Floating Helper --}}
<button class="helper-orb" id="helperOrb" onclick="toggleHelper()" title="{{ __('messages.helper_title') }}">🤖</button>
<div class="helper-panel" id="helperPanel">
    <div class="helper-panel-header">
        <div style="font-size:.72rem;font-weight:700;color:#10b981;text-transform:uppercase;letter-spacing:.08em;margin-bottom:4px;">{{ __('messages.helper_title') }}</div>
        <div style="font-size:.82rem;font-weight:600;color:#f1f5f9;">{{ __('messages.helper_subtitle') }}</div>
    </div>
    <div class="helper-tips">
        @foreach([
            ['🌤️','Weather Search','Enter your city name to get live weather. Try: Agra, Delhi, Pune'],
            ['🌾','Crop Selection','Select your crop from dropdown to get personalized farming advice'],
            ['📅','Season Auto-detect','Season is auto-detected. You can change it manually if needed'],
            ['🤖','AI Advisory','The AI matches your crop + weather to give specific recommendations'],
            ['🌡️','Weather Metrics','Humidity, wind speed, and feels-like help plan farm activities'],
            ['🚨','Rain Alerts','Red banner means rain is coming. Delay irrigation!'],
            ['🌍','Language','Use the language button (top-right) to switch to your language'],
            ['🌗','Theme','Toggle dark/light mode with the sun/moon button'],
        ] as [$icon,$title,$desc])
        <div class="helper-tip-item">
            <span class="helper-tip-icon">{{ $icon }}</span>
            <div>
                <div class="helper-tip-title">{{ $title }}</div>
                <div class="helper-tip-desc">{{ $desc }}</div>
            </div>
        </div>
        @endforeach
    </div>
    <button class="helper-start-tour" onclick="closeHelper();startTour();">
        🗺️ {{ __('messages.tour_start') }}
    </button>
</div>

<script>
// ═══ TOUR STEPS ═══
const STEPS = [
    { emoji:'🌾', title:'{{ __("messages.tour_welcome") }}', desc:'{{ __("messages.tour_welcome_sub") }}' },
    { emoji:'🔍', title:'Search Your City', desc:'Type your city name in the Search Parameters panel on the left. E.g. "Agra", "Pune", "Lucknow". Press Get Advisory.' },
    { emoji:'🌡️', title:'Live Weather Data', desc:'After searching, the right panel shows live temperature, humidity, wind speed and condition fetched from OpenWeather API.' },
    { emoji:'🌾', title:'Select Your Crop', desc:'Choose your crop from the dropdown. The AI will match your crop with current weather to give farming-specific advice.' },
    { emoji:'🤖', title:'AI Recommendation', desc:'The AI Advisory card shows what action to take — whether to irrigate, spray, harvest, or wait based on live conditions.' },
    { emoji:'🌍', title:'Change Language', desc:'Use the language selector (top-right) to switch to Hindi, Punjabi, Bengali, Tamil, or any regional language.' },
    { emoji:'✅', title:'You Are Ready! 🎉', desc:'You now know how to use FarmAdviser. Start by entering your city and selecting your crop. Happy Farming! 🌱' },
];
let currentStep = 0;

function buildProgress(){
    const p = document.getElementById('tourProgress');
    p.innerHTML = STEPS.map((_,i)=>`<div class="tour-dot ${i===currentStep?'active':''}"></div>`).join('');
}
function renderStep(){
    const s = STEPS[currentStep];
    document.getElementById('tourEmoji').textContent = s.emoji;
    document.getElementById('tourTitle').textContent = s.title;
    document.getElementById('tourDesc').textContent = s.desc;
    document.getElementById('tourStepLabel').textContent = `${currentStep+1} {{ __('messages.tour_step_of') }} ${STEPS.length}`;
    const btn = document.getElementById('tourNextBtn');
    btn.textContent = currentStep === STEPS.length-1 ? '{{ __("messages.tour_done") }}' : '{{ __("messages.tour_next") }}';
    buildProgress();
}
function nextStep(){
    if(currentStep < STEPS.length-1){ currentStep++; renderStep(); }
    else endTour();
}
function startTour(){
    currentStep = 0;
    document.getElementById('tourOverlay').classList.add('active');
    renderStep();
}
function endTour(){
    document.getElementById('tourOverlay').classList.remove('active');
    localStorage.setItem('fa-tour-done','1');
}

// ═══ HELPER PANEL ═══
function toggleHelper(){ document.getElementById('helperPanel').classList.toggle('open'); }
function closeHelper(){ document.getElementById('helperPanel').classList.remove('open'); }
document.addEventListener('click',e=>{
    if(!e.target.closest('#helperPanel')&&!e.target.closest('#helperOrb'))
        document.getElementById('helperPanel')?.classList.remove('open');
});

// ═══ WELCOME MODAL ═══
function closeWelcome(){
    document.getElementById('welcomeModal').style.display='none';
    localStorage.setItem('fa-welcomed','1');
}
// Show welcome on first visit
if(!localStorage.getItem('fa-welcomed') && !localStorage.getItem('fa-tour-done')){
    setTimeout(()=>{ document.getElementById('welcomeModal').style.display='flex'; }, 800);
}
// Handle lang switch from welcome (clears welcome flag so it doesn't loop)
const urlParams = new URLSearchParams(window.location.search);
if(urlParams.get('welcome')==='1'){ localStorage.setItem('fa-welcomed','1'); }
</script>
@endsection

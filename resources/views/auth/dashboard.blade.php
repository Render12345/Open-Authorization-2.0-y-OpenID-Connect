<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConnectID · Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:       #07090f;
            --surface:  #0d1119;
            --surface2: #131926;
            --border:   rgba(255,255,255,0.06);
            --text:     #dde3ef;
            --muted:    #5a6275;
            --accent:   #7c6af7;
            --discord:  #5865F2;
            --spotify:  #1DB954;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: grid;
            place-items: center;
            padding: 1.5rem;
        }

        .bg-layer { position: fixed; inset: 0; pointer-events: none; overflow: hidden; }
        .bg-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(124,106,247,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,106,247,.04) 1px, transparent 1px);
            background-size: 52px 52px;
            animation: gridMove 24s linear infinite;
        }
        .bg-orb {
            position: absolute; border-radius: 50%; filter: blur(80px);
        }
        .bg-orb-1 {
            width: 500px; height: 500px;
            background: radial-gradient(circle, rgba(124,106,247,.12) 0%, transparent 65%);
            top: -100px; right: -60px;
            animation: orbFloat 9s ease-in-out infinite alternate;
        }

        @keyframes gridMove { to { background-position: 52px 52px; } }
        @keyframes orbFloat { to { transform: translate(-30px, 60px) scale(1.06); } }
        @keyframes fadeUp   { from { opacity:0; transform:translateY(18px); } to { opacity:1; transform:none; } }
        @keyframes popIn    { from { opacity:0; transform:scale(.9);         } to { opacity:1; transform:scale(1); } }
        @keyframes pulse    { 0%,100% { box-shadow: 0 0 0 0 rgba(124,106,247,.4); } 50% { box-shadow: 0 0 0 8px rgba(124,106,247,0); } }

        .card {
            position: relative; z-index: 1;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.6rem;
            width: 100%; max-width: 480px;
            box-shadow: 0 0 0 1px rgba(124,106,247,.07), 0 40px 80px rgba(0,0,0,.55);
            animation: fadeUp .55s cubic-bezier(.22,1,.36,1) both;
        }

        /* ── Top nav ── */
        .topnav {
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 2rem;
        }
        .logo-wrap { display: flex; align-items: center; gap: .55rem; }
        .logo-icon {
            width: 32px; height: 32px; border-radius: 8px;
            background: linear-gradient(135deg, var(--accent), #4f3ff0);
            display: grid; place-items: center; font-size: .9rem;
        }
        .logo-text {
            font-family: 'Syne', sans-serif; font-weight: 800; font-size: 1rem;
        }
        .logo-text span { color: var(--accent); }

        .logout-btn {
            display: flex; align-items: center; gap: .4rem;
            background: none; border: 1px solid var(--border);
            color: var(--muted); border-radius: 8px;
            padding: .4rem .9rem; font-size: .8rem;
            cursor: pointer; font-family: inherit;
            transition: border-color .2s, color .2s;
        }
        .logout-btn:hover { border-color: var(--border-h, rgba(255,255,255,.15)); color: var(--text); }

        /* ── Success banner ── */
        .success-banner {
            background: rgba(124,106,247,.08);
            border: 1px solid rgba(124,106,247,.2);
            border-radius: 12px;
            padding: .8rem 1rem;
            display: flex; align-items: center; gap: .6rem;
            font-size: .82rem; color: #a89ef5;
            margin-bottom: 1.8rem;
        }
        .success-dot {
            width: 8px; height: 8px; border-radius: 50%;
            background: var(--accent); flex-shrink: 0;
            animation: pulse 2s ease-in-out infinite;
        }

        /* ── Avatar section ── */
        .profile-section {
            display: flex; align-items: center; gap: 1.2rem;
            margin-bottom: 2rem;
        }
        .avatar-wrap { position: relative; flex-shrink: 0; }
        .avatar {
            width: 72px; height: 72px; border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border);
            animation: popIn .4s .2s cubic-bezier(.22,1,.36,1) both;
        }
        .avatar-fallback {
            width: 72px; height: 72px; border-radius: 50%;
            background: linear-gradient(135deg, var(--accent), #4f3ff0);
            display: grid; place-items: center;
            font-family: 'Syne', sans-serif; font-size: 1.6rem; font-weight: 800;
            border: 2px solid rgba(124,106,247,.3);
        }
        .provider-badge {
            position: absolute; bottom: -2px; right: -2px;
            width: 22px; height: 22px; border-radius: 50%;
            display: grid; place-items: center;
            border: 2px solid var(--surface);
        }
        .provider-badge.discord { background: var(--discord); }
        .provider-badge.spotify { background: var(--spotify); }
        .provider-badge svg { width: 11px; height: 11px; }

        .profile-info h2 {
            font-family: 'Syne', sans-serif;
            font-size: 1.35rem; font-weight: 800;
            letter-spacing: -.025em;
        }
        .profile-info .handle {
            font-size: .84rem; color: var(--muted); margin-top: .15rem;
        }
        .profile-info .email {
            font-size: .82rem; color: var(--muted); margin-top: .2rem;
            display: flex; align-items: center; gap: .3rem;
        }

        /* ── Info grid ── */
        .info-grid {
            display: grid; grid-template-columns: 1fr 1fr;
            gap: .7rem; margin-bottom: 1.4rem;
        }
        .info-cell {
            background: var(--surface2);
            border: 1px solid var(--border);
            border-radius: 12px; padding: .9rem 1rem;
        }
        .info-cell label {
            display: block; font-size: .7rem; color: var(--muted);
            text-transform: uppercase; letter-spacing: .08em; margin-bottom: .3rem;
        }
        .info-cell .value {
            font-size: .88rem; font-weight: 500;
            white-space: nowrap; overflow: hidden; text-overflow: ellipsis;
        }
        .provider-pill {
            display: inline-flex; align-items: center; gap: .35rem;
            font-size: .82rem; font-weight: 600;
            padding: .2rem .6rem; border-radius: 6px;
        }
        .provider-pill.discord {
            background: rgba(88,101,242,.15); color: #8891f5;
        }
        .provider-pill.spotify {
            background: rgba(29,185,84,.12); color: #2ecc71;
        }
        .provider-pill svg { width: 12px; }

        /* ── Token box ── */
        .token-section { margin-top: .5rem; }
        .token-section label {
            font-size: .75rem; color: var(--muted);
            text-transform: uppercase; letter-spacing: .08em;
            display: block; margin-bottom: .5rem;
        }
        .token-box {
            background: var(--surface2); border: 1px solid var(--border);
            border-radius: 10px; padding: .7rem .9rem;
            font-family: 'Courier New', monospace; font-size: .72rem;
            color: var(--muted); word-break: break-all; line-height: 1.55;
        }

        /* ── OAuth flow diagram ── */
        .flow-section {
            margin-top: 1.5rem; padding-top: 1.5rem;
            border-top: 1px solid var(--border);
        }
        .flow-section h3 {
            font-family: 'Syne', sans-serif; font-size: .88rem;
            font-weight: 700; margin-bottom: .9rem;
            color: var(--muted); letter-spacing: .02em;
            text-transform: uppercase;
        }
        .flow-steps {
            display: flex; align-items: center; gap: 0;
        }
        .flow-step {
            flex: 1; text-align: center;
        }
        .flow-step .step-icon {
            width: 32px; height: 32px; border-radius: 50%;
            background: var(--surface2); border: 1px solid var(--border);
            display: grid; place-items: center; margin: 0 auto .4rem;
            font-size: .85rem;
        }
        .flow-step .step-label {
            font-size: .68rem; color: var(--muted); line-height: 1.4;
        }
        .flow-arrow {
            color: var(--muted); font-size: .75rem; padding: 0 .2rem;
            padding-bottom: 1.2rem;
        }
        .flow-step.active .step-icon {
            background: rgba(124,106,247,.15);
            border-color: rgba(124,106,247,.4);
            color: var(--accent);
        }
        .flow-step.active .step-label { color: var(--text); }
    </style>
</head>
<body>

<div class="bg-layer">
    <div class="bg-grid"></div>
    <div class="bg-orb bg-orb-1"></div>
</div>

<div class="card">

    {{-- Navbar --}}
    <div class="topnav">
        <div class="logo-wrap">
            <div class="logo-icon">🔐</div>
            <div class="logo-text">Connect<span>ID</span></div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg width="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Cerrar sesión
            </button>
        </form>
    </div>

    {{-- Success --}}
    <div class="success-banner">
        <div class="success-dot"></div>
        Autenticación exitosa mediante OAuth 2.0
    </div>

    {{-- Profile --}}
    <div class="profile-section">
        <div class="avatar-wrap">
            @if(Auth::user()->avatar)
                <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="avatar">
            @else
                <div class="avatar-fallback">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>
            @endif

            <div class="provider-badge {{ Auth::user()->provider }}">
                @if(Auth::user()->provider === 'discord')
                    <svg viewBox="0 0 24 24" fill="white"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>
                @else
                    <svg viewBox="0 0 24 24" fill="white"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                @endif
            </div>
        </div>

        <div class="profile-info">
            <h2>{{ Auth::user()->name }}</h2>
            @if(Auth::user()->nickname)
                <div class="handle">@{{ Auth::user()->nickname }}</div>
            @endif
            @if(Auth::user()->email)
                <div class="email">
                    <svg width="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/>
                        <polyline points="22,6 12,13 2,6"/>
                    </svg>
                    {{ Auth::user()->email }}
                </div>
            @endif
        </div>
    </div>

    {{-- Info grid --}}
    <div class="info-grid">
        <div class="info-cell">
            <label>Proveedor</label>
            <div class="value">
                <span class="provider-pill {{ Auth::user()->provider }}">
                    @if(Auth::user()->provider === 'discord')
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 13.56 13.56 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128c.126-.094.252-.192.372-.292a.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.1.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/></svg>
                        Discord
                    @else
                        <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/></svg>
                        Spotify
                    @endif
                </span>
            </div>
        </div>

        <div class="info-cell">
            <label>Provider ID</label>
            <div class="value">{{ Auth::user()->provider_id }}</div>
        </div>

        <div class="info-cell">
            <label>Protocolo</label>
            <div class="value">OAuth 2.0</div>
        </div>

        <div class="info-cell">
            <label>Sesión iniciada</label>
            <div class="value">{{ now()->format('H:i · d M Y') }}</div>
        </div>
    </div>

    {{-- Access token --}}
    @if(Auth::user()->provider_token)
    <div class="token-section">
        <label>🔑 Access Token (OAuth 2.0)</label>
        <div class="token-box">{{ substr(Auth::user()->provider_token, 0, 80) }}...</div>
    </div>
    @endif

    {{-- OAuth flow diagram --}}
    <div class="flow-section">
        <h3>Flujo OAuth 2.0 completado</h3>
        <div class="flow-steps">
            <div class="flow-step active">
                <div class="step-icon">1️⃣</div>
                <div class="step-label">Solicitud<br>de acceso</div>
            </div>
            <div class="flow-arrow">→</div>
            <div class="flow-step active">
                <div class="step-icon">2️⃣</div>
                <div class="step-label">Redirect al<br>proveedor</div>
            </div>
            <div class="flow-arrow">→</div>
            <div class="flow-step active">
                <div class="step-icon">3️⃣</div>
                <div class="step-label">Autorización<br>del usuario</div>
            </div>
            <div class="flow-arrow">→</div>
            <div class="flow-step active">
                <div class="step-icon">4️⃣</div>
                <div class="step-label">Auth code<br>recibido</div>
            </div>
            <div class="flow-arrow">→</div>
            <div class="flow-step active">
                <div class="step-icon">✅</div>
                <div class="step-label">Token<br>obtenido</div>
            </div>
        </div>
    </div>

</div>
</body>
</html>

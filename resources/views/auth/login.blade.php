<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ConnectID · Login</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;700;800&family=DM+Sans:ital,wght@0,300;0,400;0,500;1,300&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg:         #07090f;
            --surface:    #0d1119;
            --surface2:   #131926;
            --border:     rgba(255,255,255,0.06);
            --border-h:   rgba(255,255,255,0.12);
            --text:       #dde3ef;
            --muted:      #5a6275;
            --discord:    #5865F2;
            --discord-h:  #4752c4;
            --spotify:    #1DB954;
            --spotify-h:  #169c46;
            --accent:     #7c6af7;
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

        /* ── Animated background ── */
        .bg-layer {
            position: fixed; inset: 0; pointer-events: none; overflow: hidden;
        }
        .bg-grid {
            position: absolute; inset: 0;
            background-image:
                linear-gradient(rgba(124,106,247,.04) 1px, transparent 1px),
                linear-gradient(90deg, rgba(124,106,247,.04) 1px, transparent 1px);
            background-size: 52px 52px;
            animation: gridMove 24s linear infinite;
        }
        .bg-orb {
            position: absolute; border-radius: 50%;
            filter: blur(80px);
        }
        .bg-orb-1 {
            width: 520px; height: 520px;
            background: radial-gradient(circle, rgba(124,106,247,.15) 0%, transparent 65%);
            top: -160px; right: -80px;
            animation: orbFloat 9s ease-in-out infinite alternate;
        }
        .bg-orb-2 {
            width: 400px; height: 400px;
            background: radial-gradient(circle, rgba(29,185,84,.08) 0%, transparent 65%);
            bottom: -120px; left: -80px;
            animation: orbFloat 11s ease-in-out infinite alternate-reverse;
        }

        @keyframes gridMove   { to { background-position: 52px 52px; } }
        @keyframes orbFloat   { to { transform: translate(30px, -50px) scale(1.08); } }
        @keyframes fadeUp     { from { opacity:0; transform:translateY(20px); } to { opacity:1; transform:none; } }
        @keyframes spinRing   { to { transform: rotate(360deg); } }

        /* ── Card ── */
        .card {
            position: relative; z-index: 1;
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 2.8rem 2.6rem;
            width: 100%; max-width: 430px;
            box-shadow: 0 0 0 1px rgba(124,106,247,.07),
                        0 40px 80px rgba(0,0,0,.55);
            animation: fadeUp .55s cubic-bezier(.22,1,.36,1) both;
        }

        /* ── Logo / Badge ── */
        .logo-wrap {
            display: flex; align-items: center; gap: .65rem;
            margin-bottom: 2rem;
        }
        .logo-icon {
            width: 38px; height: 38px; border-radius: 10px;
            background: linear-gradient(135deg, var(--accent) 0%, #4f3ff0 100%);
            display: grid; place-items: center;
            font-size: 1.1rem;
        }
        .logo-text {
            font-family: 'Syne', sans-serif;
            font-weight: 800; font-size: 1.15rem;
            letter-spacing: -.02em;
        }
        .logo-text span { color: var(--accent); }

        /* ── Headline ── */
        .headline { margin-bottom: .5rem; }
        .headline h1 {
            font-family: 'Syne', sans-serif;
            font-size: 1.75rem; font-weight: 800;
            letter-spacing: -.03em; line-height: 1.15;
        }
        .headline p {
            margin-top: .45rem;
            font-size: .88rem; color: var(--muted); line-height: 1.55;
        }

        /* ── Divider ── */
        .divider {
            display: flex; align-items: center; gap: .8rem;
            margin: 1.8rem 0 1.6rem;
        }
        .divider::before, .divider::after {
            content: ''; flex: 1; height: 1px;
            background: var(--border);
        }
        .divider span { font-size: .75rem; color: var(--muted); white-space: nowrap; }

        /* ── Provider buttons ── */
        .provider-btn {
            display: flex; align-items: center; gap: .85rem;
            width: 100%; padding: .95rem 1.2rem;
            border-radius: 14px; border: 1px solid var(--border);
            background: var(--surface2);
            color: var(--text); text-decoration: none;
            font-size: .93rem; font-weight: 500;
            transition: border-color .2s, transform .15s, box-shadow .2s;
            cursor: pointer; margin-bottom: .75rem;
            position: relative; overflow: hidden;
        }
        .provider-btn::after {
            content: ''; position: absolute; inset: 0;
            background: rgba(255,255,255,.03);
            opacity: 0; transition: opacity .2s;
        }
        .provider-btn:hover { transform: translateY(-2px); }
        .provider-btn:hover::after { opacity: 1; }
        .provider-btn:active { transform: translateY(0); }

        .provider-btn.discord {
            border-color: rgba(88,101,242,.3);
        }
        .provider-btn.discord:hover {
            border-color: rgba(88,101,242,.7);
            box-shadow: 0 4px 24px rgba(88,101,242,.2);
        }

        .provider-btn.spotify {
            border-color: rgba(29,185,84,.3);
        }
        .provider-btn.spotify:hover {
            border-color: rgba(29,185,84,.7);
            box-shadow: 0 4px 24px rgba(29,185,84,.18);
        }

        .provider-icon {
            width: 34px; height: 34px; border-radius: 8px;
            display: grid; place-items: center; flex-shrink: 0;
        }
        .discord .provider-icon { background: rgba(88,101,242,.15); }
        .spotify .provider-icon { background: rgba(29,185,84,.12); }

        .provider-icon svg { width: 18px; height: 18px; }

        .btn-label { flex: 1; }
        .btn-label strong { display: block; font-size: .93rem; }
        .btn-label small { display: block; font-size: .75rem; color: var(--muted); margin-top: 1px; }

        .btn-arrow {
            font-size: .85rem; color: var(--muted);
            transition: transform .2s, color .2s;
        }
        .provider-btn:hover .btn-arrow { transform: translateX(3px); color: var(--text); }

        /* ── Error ── */
        .error-box {
            background: rgba(239,68,68,.08);
            border: 1px solid rgba(239,68,68,.25);
            border-radius: 10px; padding: .75rem 1rem;
            font-size: .83rem; color: #f87171;
            margin-bottom: 1.2rem;
        }

        /* ── Footer note ── */
        .footnote {
            margin-top: 1.8rem; text-align: center;
            font-size: .76rem; color: var(--muted); line-height: 1.6;
        }
        .footnote a { color: var(--accent); text-decoration: none; }

        /* ── Security badges ── */
        .badges {
            display: flex; justify-content: center; gap: 1rem;
            margin-top: 1.4rem; padding-top: 1.4rem;
            border-top: 1px solid var(--border);
        }
        .badge {
            display: flex; align-items: center; gap: .35rem;
            font-size: .72rem; color: var(--muted);
        }
        .badge svg { width: 13px; opacity: .6; }
    </style>
</head>
<body>

<div class="bg-layer">
    <div class="bg-grid"></div>
    <div class="bg-orb bg-orb-1"></div>
    <div class="bg-orb bg-orb-2"></div>
</div>

<div class="card">

    {{-- Logo --}}
    <div class="logo-wrap">
        <div class="logo-icon">🔐</div>
        <div class="logo-text">Connect<span>ID</span></div>
    </div>

    {{-- Headline --}}
    <div class="headline">
        <h1>Iniciar sesión</h1>
        <p>Elige un proveedor para autenticarte con OAuth 2.0 / OpenID Connect.</p>
    </div>

    {{-- Errors --}}
    @if ($errors->any())
        <div class="error-box" style="margin-top:1rem;">
            {{ $errors->first('oauth') }}
        </div>
    @endif

    <div class="divider"><span>Proveedores disponibles</span></div>

    {{-- Discord --}}
    <a href="{{ route('auth.redirect', 'discord') }}" class="provider-btn discord">
        <div class="provider-icon">
            <svg viewBox="0 0 24 24" fill="#5865F2" xmlns="http://www.w3.org/2000/svg">
                <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
            </svg>
        </div>
        <div class="btn-label">
            <strong>Continuar con Discord</strong>
            <small>OAuth 2.0 · scopes: identify, email</small>
        </div>
        <span class="btn-arrow">→</span>
    </a>

    {{-- Spotify --}}
    <a href="{{ route('auth.redirect', 'spotify') }}" class="provider-btn spotify">
        <div class="provider-icon">
            <svg viewBox="0 0 24 24" fill="#1DB954" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 0C5.4 0 0 5.4 0 12s5.4 12 12 12 12-5.4 12-12S18.66 0 12 0zm5.521 17.34c-.24.359-.66.48-1.021.24-2.82-1.74-6.36-2.101-10.561-1.141-.418.122-.779-.179-.899-.539-.12-.421.18-.78.54-.9 4.56-1.021 8.52-.6 11.64 1.32.42.18.479.659.301 1.02zm1.44-3.3c-.301.42-.841.6-1.262.3-3.239-1.98-8.159-2.58-11.939-1.38-.479.12-1.02-.12-1.14-.6-.12-.48.12-1.021.6-1.141C9.6 9.9 15 10.561 18.72 12.84c.361.181.54.78.241 1.2zm.12-3.36C15.24 8.4 8.82 8.16 5.16 9.301c-.6.179-1.2-.181-1.38-.721-.18-.601.18-1.2.72-1.381 4.26-1.26 11.28-1.02 15.721 1.621.539.3.719 1.02.419 1.56-.299.421-1.02.599-1.559.3z"/>
            </svg>
        </div>
        <div class="btn-label">
            <strong>Continuar con Spotify</strong>
            <small>OAuth 2.0 · scopes: user-read-email, user-read-private</small>
        </div>
        <span class="btn-arrow">→</span>
    </a>

    {{-- Security badges --}}
    <div class="badges">
        <span class="badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="11" width="18" height="11" rx="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>
            </svg>
            Conexión cifrada
        </span>
        <span class="badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/>
            </svg>
            OAuth 2.0 / OIDC
        </span>
        <span class="badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
            </svg>
            Sin contraseñas
        </span>
    </div>

    <p class="footnote">
        Al continuar aceptas los <a href="#">Términos de servicio</a> y la <a href="#">Política de privacidad</a>.<br>
        No almacenamos contraseñas. Solo los datos del proveedor que autorices.
    </p>
</div>

</body>
</html>

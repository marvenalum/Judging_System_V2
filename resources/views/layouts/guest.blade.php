<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Crimson+Pro:ital,wght@0,300;0,400;1,300&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            * { box-sizing: border-box; margin: 0; padding: 0; }

            body {
                min-height: 100vh;
                background-color: #0d1117;
                background-image:
                    radial-gradient(ellipse at 20% 50%, rgba(183, 149, 76, 0.06) 0%, transparent 50%),
                    radial-gradient(ellipse at 80% 20%, rgba(255,255,255,0.02) 0%, transparent 40%),
                    repeating-linear-gradient(0deg, transparent, transparent 79px, rgba(255,255,255,0.015) 79px, rgba(255,255,255,0.015) 80px),
                    repeating-linear-gradient(90deg, transparent, transparent 79px, rgba(255,255,255,0.015) 79px, rgba(255,255,255,0.015) 80px);
                display: flex;
                align-items: center;
                justify-content: center;
                font-family: 'Crimson Pro', Georgia, serif;
            }

            .login-shell {
                width: 100%;
                max-width: 460px;
                padding: 20px;
                animation: fadeUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
            }

            @keyframes fadeUp {
                from { opacity: 0; transform: translateY(24px); }
                to   { opacity: 1; transform: translateY(0); }
            }

            .login-header { text-align: center; margin-bottom: 40px; }

            .emblem {
                width: 64px; height: 64px;
                margin: 0 auto 20px;
            }
            .emblem svg { width: 100%; height: 100%; filter: drop-shadow(0 0 12px rgba(183,149,76,0.35)); }

            .login-title {
                font-family: 'Playfair Display', Georgia, serif;
                font-size: 1.85rem; font-weight: 700;
                color: #e8dcc8; letter-spacing: 0.02em; line-height: 1.15;
            }
            .login-subtitle {
                margin-top: 8px; font-size: 0.95rem; font-weight: 300;
                color: #7a7060; letter-spacing: 0.12em;
                text-transform: uppercase; font-style: italic;
            }
            .rule {
                display: flex; align-items: center; gap: 14px; margin: 22px 0 0;
            }
            .rule::before, .rule::after {
                content: ''; flex: 1; height: 1px;
                background: linear-gradient(90deg, transparent, #b7954c55, transparent);
            }
            .rule-diamond {
                width: 6px; height: 6px;
                background: #b7954c; transform: rotate(45deg); flex-shrink: 0;
            }

            .login-card {
                background: linear-gradient(160deg, #161b25 0%, #111520 100%);
                border: 1px solid #2a2d3a;
                border-radius: 4px;
                padding: 40px 44px 36px;
                box-shadow:
                    0 0 0 1px rgba(183,149,76,0.08),
                    0 24px 60px rgba(0,0,0,0.55),
                    0 4px 16px rgba(0,0,0,0.35),
                    inset 0 1px 0 rgba(255,255,255,0.04);
                position: relative; overflow: hidden;
            }
            .login-card::before {
                content: '';
                position: absolute; top: 0; left: 0; right: 0; height: 2px;
                background: linear-gradient(90deg, transparent 0%, #b7954c 30%, #e8d59a 50%, #b7954c 70%, transparent 100%);
            }

            .field-group { margin-bottom: 24px; }
            .field-label {
                display: block; font-size: 0.72rem; font-weight: 400;
                letter-spacing: 0.18em; text-transform: uppercase;
                color: #b7954c; margin-bottom: 8px;
                font-family: 'Crimson Pro', serif;
            }
            .field-input {
                width: 100%;
                background: rgba(255,255,255,0.03);
                border: 1px solid #2c3040;
                border-radius: 2px;
                padding: 13px 16px;
                color: #e8dcc8;
                font-size: 1rem; font-family: 'Crimson Pro', serif;
                font-weight: 300; letter-spacing: 0.02em;
                transition: border-color 0.2s, background 0.2s, box-shadow 0.2s;
                outline: none;
            }
            .field-input::placeholder { color: #3a3d4a; }
            .field-input:focus {
                border-color: #b7954c;
                background: rgba(183,149,76,0.04);
                box-shadow: 0 0 0 3px rgba(183,149,76,0.08), inset 0 1px 4px rgba(0,0,0,0.25);
            }

            .remember-row {
                display: flex; align-items: center;
                justify-content: space-between; margin-bottom: 28px;
            }
            .remember-label { display: flex; align-items: center; gap: 9px; cursor: pointer; }
            .remember-checkbox {
                appearance: none; width: 15px; height: 15px;
                border: 1px solid #3a3d4a; border-radius: 2px;
                background: rgba(255,255,255,0.03); cursor: pointer;
                position: relative;
                transition: border-color 0.2s, background 0.2s; flex-shrink: 0;
            }
            .remember-checkbox:checked { background: #b7954c; border-color: #b7954c; }
            .remember-checkbox:checked::after {
                content: '';
                position: absolute; top: 2px; left: 4px;
                width: 5px; height: 8px;
                border: 1.5px solid #0d1117;
                border-top: none; border-left: none;
                transform: rotate(45deg);
            }
            .remember-text { font-size: 0.85rem; color: #5a5d6a; letter-spacing: 0.08em; text-transform: uppercase; }
            .forgot-link {
                font-size: 0.82rem; color: #5a5260; text-decoration: none;
                letter-spacing: 0.05em; font-style: italic; transition: color 0.2s;
            }
            .forgot-link:hover { color: #b7954c; }

            .btn-primary {
                width: 100%; padding: 15px 24px;
                background: linear-gradient(135deg, #9a7a3a 0%, #c9a455 40%, #b7954c 60%, #8a6a2e 100%);
                border: none; border-radius: 2px;
                color: #0d1117;
                font-family: 'Playfair Display', Georgia, serif;
                font-size: 0.9rem; font-weight: 700;
                letter-spacing: 0.2em; text-transform: uppercase;
                cursor: pointer; position: relative; overflow: hidden;
                transition: opacity 0.2s, transform 0.15s;
                box-shadow: 0 4px 20px rgba(183,149,76,0.25), 0 1px 4px rgba(0,0,0,0.3);
            }
            .btn-primary::after {
                content: ''; position: absolute; inset: 0;
                background: linear-gradient(180deg, rgba(255,255,255,0.15) 0%, transparent 100%);
                pointer-events: none;
            }
            .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
            .btn-primary:active { transform: translateY(0); opacity: 1; }

            .card-footer {
                margin-top: 24px; display: flex;
                align-items: center; justify-content: center;
            }
            .footer-divider { width: 1px; height: 14px; background: #2a2d3a; margin: 0 16px; }
            .footer-link {
                font-size: 0.78rem; letter-spacing: 0.14em;
                text-transform: uppercase; color: #4a4d5a;
                text-decoration: none; transition: color 0.2s;
            }
            .footer-link:hover { color: #b7954c; }

            .login-caption {
                text-align: center; margin-top: 32px;
                font-size: 0.72rem; color: #2a2d36;
                letter-spacing: 0.15em; text-transform: uppercase;
            }

            .error-message {
                color: #e57373;
                font-size: 0.85rem;
                margin-top: 6px;
            }
        </style>
    </head>
    <body>
        <div class="login-shell">
            {{ $slot }}
        </div>
    </body>
</html>


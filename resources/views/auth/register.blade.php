

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Crimson+Pro:ital,wght@0,300;0,400;1,300&display=swap');

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background-color: #0d1117;
            background-image:
                radial-gradient(ellipse at 20% 50%, rgba(183,149,76,0.06) 0%, transparent 50%),
                radial-gradient(ellipse at 80% 20%, rgba(255,255,255,0.02) 0%, transparent 40%),
                repeating-linear-gradient(0deg, transparent, transparent 79px, rgba(255,255,255,0.015) 79px, rgba(255,255,255,0.015) 80px),
                repeating-linear-gradient(90deg, transparent, transparent 79px, rgba(255,255,255,0.015) 79px, rgba(255,255,255,0.015) 80px);
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Crimson Pro', Georgia, serif;
            padding: 40px 20px;
        }

        .login-shell {
            width: 100%;
            max-width: 480px;
            animation: fadeUp 0.7s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .login-header { text-align: center; margin-bottom: 36px; }

        .emblem { width: 64px; height: 64px; margin: 0 auto 20px; }
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
        .rule { display: flex; align-items: center; gap: 14px; margin: 22px 0 0; }
        .rule::before, .rule::after {
            content: ''; flex: 1; height: 1px;
            background: linear-gradient(90deg, transparent, #b7954c55, transparent);
        }
        .rule-diamond { width: 6px; height: 6px; background: #b7954c; transform: rotate(45deg); flex-shrink: 0; }

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

        .field-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }
        .field-group { margin-bottom: 22px; }

        .field-label {
            display: block; font-size: 0.72rem; font-weight: 400;
            letter-spacing: 0.18em; text-transform: uppercase;
            color: #b7954c; margin-bottom: 8px; font-family: 'Crimson Pro', serif;
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
        .field-input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 1000px #161b25 inset;
            -webkit-text-fill-color: #e8dcc8;
        }

        .field-select {
            width: 100%;
            background: rgba(255,255,255,0.03);
            border: 1px solid #2c3040;
            border-radius: 2px;
            padding: 13px 40px 13px 16px;
            color: #e8dcc8;
            font-size: 1rem; font-family: 'Crimson Pro', serif;
            font-weight: 300; letter-spacing: 0.04em;
            outline: none; cursor: pointer; appearance: none; -webkit-appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23b7954c' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat; background-position: right 16px center;
            transition: border-color 0.2s, background-color 0.2s, box-shadow 0.2s;
        }
        .field-select:focus {
            border-color: #b7954c;
            background-color: rgba(183,149,76,0.04);
            box-shadow: 0 0 0 3px rgba(183,149,76,0.08);
        }
        .field-select option { background: #161b25; color: #e8dcc8; }

        .role-hint { margin-top: 7px; font-size: 0.78rem; font-style: italic; color: #4a4d5a; letter-spacing: 0.03em; min-height: 1.1em; }

        .card-divider { display: flex; align-items: center; gap: 12px; margin: 4px 0 22px; }
        .card-divider::before, .card-divider::after { content: ''; flex: 1; height: 1px; background: #1e2230; }
        .card-divider-text { font-size: 0.65rem; letter-spacing: 0.2em; text-transform: uppercase; color: #2e3140; }

        .field-error { margin-top: 6px; font-size: 0.82rem; color: #c0705a; font-style: italic; }

        .btn-primary {
            width: 100%; padding: 15px 24px;
            background: linear-gradient(135deg, #9a7a3a 0%, #c9a455 40%, #b7954c 60%, #8a6a2e 100%);
            border: none; border-radius: 2px; color: #0d1117;
            font-family: 'Playfair Display', Georgia, serif;
            font-size: 0.9rem; font-weight: 700;
            letter-spacing: 0.2em; text-transform: uppercase;
            cursor: pointer; position: relative; overflow: hidden;
            transition: opacity 0.2s, transform 0.15s;
            box-shadow: 0 4px 20px rgba(183,149,76,0.25), 0 1px 4px rgba(0,0,0,0.3);
            margin-top: 6px;
        }
        .btn-primary::after {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(180deg, rgba(255,255,255,0.15) 0%, transparent 100%);
            pointer-events: none;
        }
        .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
        .btn-primary:active { transform: translateY(0); opacity: 1; }

        .card-footer { margin-top: 24px; display: flex; align-items: center; justify-content: center; }
        .footer-divider { width: 1px; height: 14px; background: #2a2d3a; margin: 0 16px; }
        .footer-link {
            font-size: 0.78rem; letter-spacing: 0.14em;
            text-transform: uppercase; color: #4a4d5a;
            text-decoration: none; transition: color 0.2s;
        }
        .footer-link:hover { color: #b7954c; }

        .login-caption { text-align: center; margin-top: 32px; font-size: 0.72rem; color: #2a2d36; letter-spacing: 0.15em; text-transform: uppercase; }
    </style>

    <div class="login-shell">

        <!-- Header -->
        <div class="login-header">
            <div class="emblem">
                <svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <circle cx="32" cy="32" r="30" stroke="#b7954c" stroke-width="1" stroke-dasharray="3 3" opacity="0.4"/>
                    <line x1="32" y1="12" x2="32" y2="52" stroke="#b7954c" stroke-width="1.5"/>
                    <line x1="14" y1="22" x2="50" y2="22" stroke="#b7954c" stroke-width="1.5"/>
                    <circle cx="32" cy="14" r="3" fill="#b7954c" opacity="0.9"/>
                    <path d="M14 22 Q8 28 14 34 Q20 40 26 34 Q32 28 26 22 Z" fill="none" stroke="#b7954c" stroke-width="1.2" opacity="0.85"/>
                    <path d="M38 22 Q32 28 38 34 Q44 40 50 34 Q56 28 50 22 Z" fill="none" stroke="#b7954c" stroke-width="1.2" opacity="0.85"/>
                    <line x1="32" y1="50" x2="24" y2="54" stroke="#b7954c" stroke-width="1.5" stroke-linecap="round"/>
                    <line x1="32" y1="50" x2="40" y2="54" stroke="#b7954c" stroke-width="1.5" stroke-linecap="round"/>
                    <rect x="30" y="30" width="4" height="4" fill="#b7954c" transform="rotate(45 32 32)" opacity="0.7"/>
                </svg>
            </div>
            <h1 class="login-title">REGISTER</h1>
            <p class="login-subtitle">Register for the Judging</p>
            <div class="rule"><div class="rule-diamond"></div></div>
        </div>

        <!-- Card -->
        <div class="login-card">
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name + Email -->
                <div class="field-row">
                    <div class="field-group">
                        <label class="field-label" for="name">Full Name</label>
                        <input id="name" class="field-input" type="text" name="name" value="{{ old('name') }}" placeholder="" required autofocus autocomplete="name" />
                        @error('name')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="email">Email Address</label>
                        <input id="email" class="field-input" type="email" name="email" value="{{ old('email') }}" placeholder="" required autocomplete="username" />
                        @error('email')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="card-divider"><span class="card-divider-text">Security</span></div>

                <!-- Passwords -->
                <div class="field-row">
                    <div class="field-group">
                        <label class="field-label" for="password">Password</label>
                        <input id="password" class="field-input" type="password" name="password" placeholder="••••••••" required autocomplete="new-password" />
                        @error('password')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                    <div class="field-group">
                        <label class="field-label" for="password_confirmation">Confirm</label>
                        <input id="password_confirmation" class="field-input" type="password" name="password_confirmation" placeholder="••••••••" required autocomplete="new-password" />
                        @error('password_confirmation')<p class="field-error">{{ $message }}</p>@enderror
                    </div>
                </div>

                <div class="card-divider"><span class="card-divider-text">Designation</span></div>

                <!-- Role -->
                <div class="field-group">
                    <label class="field-label" for="role">Assigned Role</label>
                    <select id="role" name="role" class="field-select" required onchange="updateRoleHint(this.value)">
                        <option value="participant" {{ old('role') == 'participant' ? 'selected' : '' }}>{{ __('Participant') }}</option>
                        <option value="judge" {{ old('role') == 'judge' ? 'selected' : '' }}>{{ __('Judge') }}</option>
                        <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>{{ __('Administrator') }}</option>
                    </select>
                   
                    @error('role')<p class="field-error">{{ $message }}</p>@enderror
                </div>

                <!-- Submit -->
                <button type="submit" class="btn-primary">{{ __('REGISTER') }}</button>

            </form>

            <div class="card-footer">
                <span class="footer-link" style="color:#3a3d4a; cursor:default;">Already registered?</span>
                <div class="footer-divider"></div>
                <a class="footer-link" href="{{ route('login') }}">Sign In</a>
            </div>
        </div>

        <p class="login-caption">Restricted · Authorized Personnel Only</p>
    </div>

    <script>
        const hints = {
            participant: 'Competing member of the adjudication event.',
            judge: 'Accredited evaluator with scoring privileges.',
            admin: 'System administrator with full portal access.'
        };
        function updateRoleHint(val) {
            document.getElementById('roleHint').textContent = hints[val] || '';
        }
    </script>


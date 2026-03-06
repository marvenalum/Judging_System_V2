<x-guest-layout>
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
        <h1 class="login-title">LOGIN</h1>
        <p class="login-subtitle">Official Judging System</p>
        <div class="rule"><div class="rule-diamond"></div></div>
    </div>

    <div class="login-card">
        <form method="POST" action="{{ route('login') }}">
            @csrf

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Email Address -->
            <div class="field-group">
                <label class="field-label" for="email">EMAIL</label>
                <input 
                    id="email" 
                    class="field-input" 
                    type="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    placeholder="judge@gmail.com" 
                    required 
                    autofocus 
                    autocomplete="username"
                />
                <x-input-error :messages="$errors->get('email')" class="error-message" />
            </div>

            <!-- Password -->
            <div class="field-group">
                <label class="field-label" for="password">PASSWORD</label>
                <input 
                    id="password" 
                    class="field-input"
                    type="password"
                    name="password"
                    placeholder="••••••••••••"
                    required 
                    autocomplete="current-password"
                />
                <x-input-error :messages="$errors->get('password')" class="error-message" />
            </div>

            <!-- Remember Me -->
            <div class="remember-row">
                <label class="remember-label" for="remember_me">
                    <input 
                        id="remember_me" 
                        type="checkbox" 
                        class="remember-checkbox" 
                        name="remember"
                        {{ old('remember') ? 'checked' : '' }}
                    >
                    <span class="remember-text">Keep session</span>
                </label>
                @if (Route::has('password.request'))
                    <a class="forgot-link" href="{{ route('password.request') }}">
                        Forgot pass?
                    </a>
                @endif
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn-primary">
                Enter
            </button>
        </form>

        <div class="card-footer">
            @if (Route::has('register'))
                <a class="footer-link" href="{{ route('register') }}">Request Access</a>
            @endif
            <div class="footer-divider"></div>
            <a class="footer-link" href="#">Help Desk</a>
        </div>
    </div>

    <p class="login-caption">Restricted · Authorized Personnel Only</p>
</x-guest-layout>


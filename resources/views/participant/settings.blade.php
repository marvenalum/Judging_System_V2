<x-participant-layout>
    <x-slot name="header">
        <h2 class="page-title">Settings</h2>
        <p class="page-subtitle">Manage your account settings and preferences</p>
    </x-slot>

    <!-- Settings Styles -->
    <style>
        :root {
            --color-ocean: #0a4d68;
            --color-ocean-light: #088395;
            --color-mint: #05bfdb;
            --color-cream: #fffef7;
            --color-accent: #ff6b35;
            --color-accent-soft: #ff8c61;
            --color-dark: #1a1d29;
            --color-dark-light: #2d3142;
            --color-text: #2d3142;
            --color-text-muted: #6b7280;
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.04);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.08);
            --shadow-lg: 0 12px 32px rgba(0, 0, 0, 0.12);
        }

        * { font-family: 'Epilogue', sans-serif; }

        .settings-container { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1); }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Settings Header */
        .settings-header { 
            background: linear-gradient(135deg, var(--color-ocean) 0%, var(--color-ocean-light) 100%);
            border-radius: 24px;
            padding: 2.5rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        .settings-content { position: relative; z-index: 1; }
        .settings-title { font-size: 2rem; font-weight: 900; color: white; margin-bottom: 0.5rem; }
        .settings-subtitle { font-size: 1rem; color: rgba(255,255,255,0.9); font-weight: 500; }

        /* Settings Grid */
        .settings-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 1.5rem; }
        
        .settings-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0,0,0,0.04);
            transition: all 0.3s;
        }
        .settings-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
        
        .card-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 2px solid #f3f4f6; }
        .card-icon { 
            width: 48px; height: 48px; 
            border-radius: 12px; 
            background: linear-gradient(135deg, var(--color-ocean) 0%, var(--color-ocean-light) 100%);
            display: flex; align-items: center; justify-content: center; 
            color: white; font-size: 1.25rem;
        }
        .card-title { font-size: 1.25rem; font-weight: 800; color: var(--color-dark); }
        .card-desc { font-size: 0.875rem; color: var(--color-text-muted); margin-top: 0.25rem; }

        /* Form Styles */
        .form-group { margin-bottom: 1.25rem; }
        .form-label { display: block; font-weight: 600; color: var(--color-dark); margin-bottom: 0.5rem; font-size: 0.9375rem; }
        .form-input {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f9fafb;
        }
        .form-input:focus {
            outline: none;
            border-color: var(--color-ocean);
            background: white;
            box-shadow: 0 0 0 4px rgba(10, 77, 104, 0.1);
        }
        .form-select {
            width: 100%;
            padding: 0.875rem 1rem;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s;
            background: #f9f            cursor: pointer;
        }
       afb;
 .form-select:focus {
            outline: none;
            border-color: var(--color-ocean);
            background: white;
        }

        .btn-save {
            background: linear-gradient(135deg, var(--color-ocean) 0%, var(--color-ocean-light) 100%);
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 1rem;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all 0.3s;
        }
        .btn-save:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(10, 77, 104, 0.25); }

        /* Info List */
        .info-list { list-style: none; padding: 0; margin: 0; }
        .info-item { 
            display: flex; align-items: center; gap: 1rem; 
            padding: 1rem; border-radius: 12px; 
            margin-bottom: 0.75rem; 
            background: #f8f9fa;
            transition: all 0.2s;
        }
        .info-item:hover { background: #f1f3f4; }
        .info-icon { 
            width: 40px; height: 40px; 
            border-radius: 10px; 
            background: var(--color-ocean-light);
            display: flex; align-items: center; justify-content: center; 
            color: white; font-size: 1rem;
        }
        .info-content { flex: 1; }
        .info-label { font-size: 0.8125rem; color: var(--color-text-muted); font-weight: 600; text-transform: uppercase; }
        .info-value { font-size: 1rem; color: var(--color-dark); font-weight: 600; }

        /* Toggle Switch */
        .toggle-wrapper { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1rem; }
        .toggle-label { font-weight: 600; color: var(--color-dark); }
        .toggle-switch {
            position: relative;
            width: 52px;
            height: 28px;
        }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }
        .toggle-slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #e5e7eb;
            transition: 0.3s;
            border-radius: 28px;
        }
        .toggle-slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
            box-shadow: 0 2px 4px rgba(0,0,0,0.2);
        }
        input:checked + .toggle-slider { background-color: var(--color-ocean); }
        input:checked + .toggle-slider:before { transform: translateX(24px); }

        /* Responsive */
        @media (max-width: 768px) {
            .settings-grid { grid-template-columns: 1fr; }
            .settings-header { padding: 1.5rem; }
            .settings-title { font-size: 1.5rem; }
        }
    </style>

    <div class="settings-container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Settings Header -->
            <div class="settings-header">
                <div class="settings-content">
                    <h1 class="settings-title">Account Settings ⚙️</h1>
                    <p class="settings-subtitle">Manage your profile, preferences, and notification settings.</p>
                </div>
            </div>

            <!-- Settings Grid -->
            <div class="settings-grid">
                <!-- Profile Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <div class="card-icon"><i class="bi bi-person-circle"></i></div>
                        <div>
                            <h3 class="card-title">Profile Information</h3>
                            <p class="card-desc">Update your personal details</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="form-group">
                            <label class="form-label" for="name">Full Name</label>
                            <input type="text" id="name" name="name" class="form-input" value="{{ Auth::user()->name }}" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-input" value="{{ Auth::user()->email }}" required>
                        </div>

                        <button type="submit" class="btn-save">
                            <i class="bi bi-check-lg"></i> Save Changes
                        </button>
                    </form>
                </div>

                <!-- Account Info -->
                <div class="settings-card">
                    <div class="card-header">
                        <div class="card-icon"><i class="bi bi-info-circle"></i></div>
                        <div>
                            <h3 class="card-title">Account Information</h3>
                            <p class="card-desc">Your account details</p>
                        </div>
                    </div>

                    <ul class="info-list">
                        <li class="info-item">
                            <div class="info-icon"><i class="bi bi-person"></i></div>
                            <div class="info-content">
                                <div class="info-label">Username</div>
                                <div class="info-value">{{ Auth::user()->name }}</div>
                            </div>
                        </li>
                        <li class="info-item">
                            <div class="info-icon"><i class="bi bi-envelope"></i></div>
                            <div class="info-content">
                                <div class="info-label">Email</div>
                                <div class="info-value">{{ Auth::user()->email }}</div>
                            </div>
                        </li>
                        <li class="info-item">
                            <div class="info-icon"><i class="bi bi-shield-check"></i></div>
                            <div class="info-content">
                                <div class="info-label">Role</div>
                                <div class="info-value">{{ ucfirst(Auth::user()->role) }}</div>
                            </div>
                        </li>
                        <li class="info-item">
                            <div class="info-icon"><i class="bi bi-calendar-plus"></i></div>
                            <div class="info-content">
                                <div class="info-label">Member Since</div>
                                <div class="info-value">{{ Auth::user()->created_at->format('F j, Y') }}</div>
                            </div>
                        </li>
                    </ul>
                </div>

                <!-- Notification Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <div class="card-icon"><i class="bi bi-bell"></i></div>
                        <div>
                            <h3 class="card-title">Notifications</h3>
                            <p class="card-desc">Manage your preferences</p>
                        </div>
                    </div>

                    <div class="toggle-wrapper">
                        <span class="toggle-label">Email Notifications</span>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="toggle-wrapper">
                        <span class="toggle-label">Event Updates</span>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="toggle-wrapper">
                        <span class="toggle-label">Score Alerts</span>
                        <label class="toggle-switch">
                            <input type="checkbox" checked>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>

                    <div class="toggle-wrapper">
                        <span class="toggle-label">Marketing Emails</span>
                        <label class="toggle-switch">
                            <input type="checkbox">
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                </div>

                <!-- Security Settings -->
                <div class="settings-card">
                    <div class="card-header">
                        <div class="card-icon"><i class="bi bi-shield-lock"></i></div>
                        <div>
                            <h3 class="card-title">Security</h3>
                            <p class="card-desc">Protect your account</p>
                        </div>
                    </div>

                    <form>
                        <div class="form-group">
                            <label class="form-label" for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" class="form-input" placeholder="Enter current password">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" class="form-input" placeholder="Enter new password">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" class="form-input" placeholder="Confirm new password">
                        </div>

                        <button type="submit" class="btn-save">
                            <i class="bi bi-key"></i> Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-participant-layout>

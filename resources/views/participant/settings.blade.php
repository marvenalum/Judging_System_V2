<x-app-sidebar>
    <x-slot name="header">
        <h2 class="page-title">Participant Account</h2>
        <p class="page-subtitle">Manage your account </p>
    </x-slot>


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
            background: #f9fafb;
            cursor: pointer;
        }
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
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-800 px-6 py-4 rounded-xl mb-6 shadow-sm">
                        {{ session('success') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-6 shadow-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-800 px-6 py-4 rounded-xl mb-6 shadow-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

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
                        @method('PATCH')

                        <div class="form-group">
                            <label class="form-label" for="name">Full Name</label>
                            <input type="text" id="name" name="name" class="form-input" value="{{ $user->name }}" required>
                            @error('name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-input" value="{{ $user->email }}" required>
                            @error('email')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if (session('status') === 'profile-updated')
                            <p class="text-green-600 text-sm bg-green-50 p-3 rounded-lg border border-green-200 mb-4">
                                Profile updated successfully!
                            </p>
                        @endif

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



                <!-- Participant Profile -->
                <div class="settings-card">
                    <div class="card-header">
                        <div class="card-icon"><i class="bi bi-trophy"></i></div>
                        <div>
                            <h3 class="card-title">Participant Profile</h3>
                            <p class="card-desc">Complete this to apply for events</p>
                            @if($user->hasCompleteProfile())
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-2">
                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    Profile Complete
                                </span>
                            @endif
                        </div>
                    </div>

                    <form method="POST" action="{{ route('participant.profile.store') }}">
                        @csrf
                        
                        <div class="form-group">
                            <label class="form-label" for="full_name">Full Name (Stage Name)</label>
                            <input type="text" id="full_name" name="full_name" class="form-input" value="{{ $profile->full_name ?? '' }}" required>
                            @error('full_name')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label" for="age">Age</label>
                                <input type="number" id="age" name="age" class="form-input" value="{{ $profile->age ?? '' }}" min="13" max="100">
                                @error('age')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                            <div>
                                <label class="form-label" for="gender">Gender</label>
                                <select id="gender" name="gender" class="form-select">
                                    <option value="">Select</option>
                                    <option value="male" {{ ($profile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                                    <option value="female" {{ ($profile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                                    <option value="other" {{ ($profile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('gender')
                                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="contact_number">Contact Number</label>
                            <input type="tel" id="contact_number" name="contact_number" class="form-input" value="{{ $profile->contact_number ?? '' }}">
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="address">Full Address</label>
                            <textarea id="address" name="address" rows="2" class="form-input">{{ $profile->address ?? '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="bio">Short Bio</label>
                            <textarea id="bio" name="bio" rows="3" class="form-input" placeholder="Tell us about yourself...">{{ $profile->bio ?? '' }}</textarea>
                        </div>

                        <div class="form-group grid grid-cols-2 gap-4">
                            <div>
                                <label class="form-label" for="height">Height (ft)</label>
                                <input type="number" step="0.01" id="height" name="height" class="form-input" value="{{ $profile->height ?? '' }}" placeholder="5.75">
                            </div>
                            <div>
                                <label class="form-label" for="weight">Weight (lbs)</label>
                                <input type="number" step="0.01" id="weight" name="weight" class="form-input" value="{{ $profile->weight ?? '' }}" placeholder="150.5">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Measurements (Bust/Waist/Hips inches)</label>
                            <div class="grid grid-cols-3 gap-3">
                                <div>
                                    <label class="form-label text-xs block mb-1">Bust</label>
                                    <input type="number" step="0.1" name="measurements[0]" class="form-input" value="{{ ($profile->measurements[0] ?? '') }}" placeholder="36">
                                    @error('measurements.0')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label text-xs block mb-1">Waist</label>
                                    <input type="number" step="0.1" name="measurements[1]" class="form-input" value="{{ ($profile->measurements[1] ?? '') }}" placeholder="28">
                                    @error('measurements.1')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="form-label text-xs block mb-1">Hips</label>
                                    <input type="number" step="0.1" name="measurements[2]" class="form-input" value="{{ ($profile->measurements[2] ?? '') }}" placeholder="38">
                                    @error('measurements.2')
                                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            @error('measurements')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="photo">Photo URL</label>
                            <input type="file" id="photo" name="photo" class="form-input" value="{{ $profile->photo ?? '' }}" placeholder="https://example.com/photo.jpg">
                            @error('photo')
                                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <button type="submit" class="btn-save w-full">
                            <i class="bi bi-check-lg"></i> {{ $user->hasCompleteProfile() ? 'Update Profile' : 'Complete Profile & Save' }}
                        </button>
                    </form>
                </div>

 
            </div>
        </div>
    </div>
</x-app-sidebar>

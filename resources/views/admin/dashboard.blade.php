<x-admin-layout>
    <x-slot name="header">
        <h2 class="page-title">Admin Dashboard</h2>
        <p class="page-subtitle">Manage users, events, and criteria efficiently</p>
    </x-slot>

    <!-- Dashboard Styles -->
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
            --shadow-accent: 0 8px 24px rgba(255, 107, 53, 0.25);
        }

        * { font-family: 'Epilogue', sans-serif; }

        .dashboard-container { animation: fadeInUp 0.6s cubic-bezier(0.16, 1, 0.3, 1); }

        @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

        /* Welcome Banner */
        .welcome-banner { 
            background: linear-gradient(135deg, var(--color-ocean) 0%, var(--color-ocean-light) 100%);
            border-radius: 24px;
            padding: 3rem;
            margin-bottom: 2rem;
            position: relative;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }
        .welcome-content { position: relative; z-index: 1; }
        .welcome-title { font-size: 2.5rem; font-weight: 900; color: white; margin-bottom: 0.75rem; }
        .welcome-subtitle { font-size: 1.125rem; color: rgba(255,255,255,0.9); font-weight: 500; }
        .welcome-meta { display: flex; gap: 2rem; flex-wrap: wrap; margin-top: 1.5rem; }
        .meta-item { display: flex; align-items: center; gap: 0.75rem; color: rgba(255,255,255,0.95); font-weight: 600; font-size: 0.9375rem; }
        .meta-icon { width: 40px; height: 40px; background: rgba(255,255,255,0.15); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; }

        /* Stats Grid */
        .stats-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .stat-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(0,0,0,0.04);
            position: relative;
            transition: all 0.4s;
        }
        .stat-card:hover { transform: translateY(-8px); box-shadow: var(--shadow-lg); }
        .stat-card.ocean { --gradient-start: var(--color-ocean); --gradient-end: var(--color-ocean-light); }
        .stat-card.mint { --gradient-start: var(--color-ocean-light); --gradient-end: var(--color-mint); }
        .stat-card.accent { --gradient-start: var(--color-accent); --gradient-end: var(--color-accent-soft); }
        .stat-card.dark { --gradient-start: var(--color-dark); --gradient-end: var(--color-dark-light); }

        .stat-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; }
        .stat-icon-wrapper { width: 64px; height: 64px; border-radius: 16px; background: linear-gradient(135deg, var(--gradient-start) 0%, var(--gradient-end) 100%); display: flex; align-items: center; justify-content: center; color: white; font-size: 1.75rem; box-shadow: 0 8px 16px rgba(0,0,0,0.15); }
        .stat-value { font-size: 3rem; font-weight: 900; color: var(--color-dark); margin-bottom: 0.5rem; }
        .stat-label { font-size: 0.875rem; color: var(--color-text-muted); font-weight: 600; text-transform: uppercase; }

        /* Action Cards Grid */
        .action-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; margin-bottom: 2rem; }
        .action-card { background: white; border-radius: 20px; padding: 2rem; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.04); transition: all 0.3s; }
        .action-card:hover { transform: translateY(-4px); box-shadow: var(--shadow-md); }
        .action-card-title { font-size: 1.25rem; font-weight: 800; color: var(--color-dark); margin-bottom: 1rem; }
        .quick-action-btn { background: linear-gradient(135deg, var(--color-ocean) 0%, var(--color-ocean-light) 100%); color: white; border: none; padding: 0.875rem 1.5rem; border-radius: 12px; font-weight: 700; font-size: 0.9375rem; cursor: pointer; display: flex; align-items: center; gap: 0.625rem; width: 100%; justify-content: center; text-decoration: none; margin-bottom: 0.75rem; transition: all 0.3s; }
        .quick-action-btn.accent { background: linear-gradient(135deg, var(--color-accent) 0%, var(--color-accent-soft) 100%); }
        .quick-action-btn:hover { transform: translateY(-2px); }

        /* Activity Feed */
        .activity-feed { background: white; border-radius: 20px; padding: 2rem; box-shadow: var(--shadow-sm); border: 1px solid rgba(0,0,0,0.04); }
        .feed-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 1.5rem; border-bottom: 2px solid #f3f4f6; padding-bottom: 1rem; }
        .feed-title { font-size: 1.5rem; font-weight: 800; color: var(--color-dark); }
        .activity-item { display: flex; gap: 1.25rem; padding: 1.25rem; border-radius: 16px; border: 1px solid transparent; transition: all 0.2s; margin-bottom: 0.75rem; }
        .activity-item:hover { background: #f8f9fa; border-color: rgba(8,131,149,0.1); }
        .activity-icon { width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: white; font-size: 1.25rem; flex-shrink: 0; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        .activity-content { flex: 1; }
        .activity-title { font-weight: 700; color: var(--color-dark); font-size: 0.9375rem; margin-bottom: 0.25rem; }
        .activity-desc { color: var(--color-text-muted); font-size: 0.875rem; line-height: 1.5; }
        .activity-time { color: var(--color-text-muted); font-size: 0.8125rem; font-weight: 600; white-space: nowrap; font-family: 'JetBrains Mono', monospace; }

        /* Responsive */
        @media (max-width: 768px) {
            .welcome-banner { padding: 2rem; }
            .welcome-title { font-size: 2rem; }
            .stats-grid, .action-grid { grid-template-columns: 1fr; }
            .stat-value { font-size: 2.5rem; }
        }
    </style>

    <div class="dashboard-container">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Banner -->
            <div class="welcome-banner">
                <div class="welcome-content">
                    <h1 class="welcome-title">Welcome back, {{ Auth::user()->name }}! 👋</h1>
                    <p class="welcome-subtitle">You have administrative privileges. Manage users, settings, and more from your dashboard.</p>

                    <div class="welcome-meta">
                        <div class="meta-item">
                            <div class="meta-icon"><i class="bi bi-calendar-check"></i></div>
                            <span>{{ now()->format('l, F j, Y') }}</span>
                        </div>
                        <div class="meta-item">
                            <div class="meta-icon"><i class="bi bi-clock"></i></div>
                            <span>{{ now()->format('g:i A') }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats Grid -->
            <div class="stats-grid">
                <div class="stat-card ocean">
                    <div class="stat-header"><div class="stat-icon-wrapper"><i class="bi bi-people-fill"></i></div></div>
                    <div class="stat-value">{{ \App\Models\User::count() }}</div>
                    <div class="stat-label">Total Users</div>
                </div>

                <div class="stat-card mint">
                    <div class="stat-header"><div class="stat-icon-wrapper"><i class="bi bi-calendar-event-fill"></i></div></div>
                    <div class="stat-value">{{ \App\Models\Event::count() }}</div>
                    <div class="stat-label">Total Events</div>
                </div>

                <div class="stat-card accent">
                    <div class="stat-header"><div class="stat-icon-wrapper"><i class="bi bi-list-check"></i></div></div>
                    <div class="stat-value">{{ \App\Models\Criteria::count() }}</div>
                    <div class="stat-label">Total Criteria</div>
                </div>

                <div class="stat-card dark">
                    <div class="stat-header"><div class="stat-icon-wrapper"><i class="bi bi-trophy-fill"></i></div></div>
                    <div class="stat-value">0</div>
                    <div class="stat-label">Active Competitions</div>
                </div>
            </div>

            <!-- Quick Actions & Activity -->
            <div class="action-grid">
                <div class="action-card">
                    <h3 class="action-card-title">Quick Actions</h3>
                    <a href="{{ route('admin.users.index') }}" class="quick-action-btn"><i class="bi bi-person-plus-fill"></i> Manage Users</a>
                    <a href="{{ route('admin.event.create') }}" class="quick-action-btn"><i class="bi bi-calendar-plus-fill"></i> Create Event</a>
                    <a href="{{ route('admin.criteria.index') }}" class="quick-action-btn accent"><i class="bi bi-clipboard-check-fill"></i> Setup Criteria</a>
                    <a href="{{ route('admin.judge.index') }}" class="quick-action-btn"><i class="bi bi-gavel-fill"></i> Manage Judges</a>
                </div>

                <div class="action-card activity-feed">
                    <div class="feed-header">
                        <h3 class="feed-title">Recent Activity</h3>
                    </div>

                    <div class="activity-item" style="--icon-color-start:#0a4d68; --icon-color-end:#088395;">
                        <div class="activity-icon" style="background: linear-gradient(135deg,#0a4d68 0%,#088395 100%);"><i class="bi bi-person-plus"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">New User Registration</div>
                            <div class="activity-desc">A new user has joined the platform</div>
                        </div>
                        <div class="activity-time">2h ago</div>
                    </div>

                    <div class="activity-item" style="--icon-color-start:#088395; --icon-color-end:#05bfdb;">
                        <div class="activity-icon" style="background: linear-gradient(135deg,#088395 0%,#05bfdb 100%);"><i class="bi bi-calendar-check"></i></div>
                        <div class="activity-content">
                            <div class="activity-title">Event Published</div>
                            <div class="activity-desc">Annual Tech Summit is now live</div>
                        </div>
                        <div class="activity-time">5h ago</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>

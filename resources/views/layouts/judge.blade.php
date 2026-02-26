<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }} - Judge</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=cabinet-grotesk:400,500,600,700,800|satoshi:400,500,700&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            :root {
                --primary-gradient-start: #1e3a5f;
                --primary-gradient-end: #2d4a6f;
                --accent-primary: #f59e0b;
                --accent-secondary: #d97706;
                --sidebar-width: 280px;
                --topbar-height: 70px;
                --text-primary: #0f172a;
                --text-secondary: #64748b;
                --border-color: #e2e8f0;
                --hover-bg: #f8fafc;
                --active-gradient: linear-gradient(135deg, var(--accent-primary) 0%, var(--accent-secondary) 100%);
            }

            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Satoshi', -apple-system, BlinkMacSystemFont, sans-serif;
                background: #f8fafc;
                overflow-x: hidden;
            }

            /* Sidebar Styles */
            .judge-sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: var(--sidebar-width);
                height: 100vh;
                background: linear-gradient(180deg, var(--primary-gradient-start) 0%, var(--primary-gradient-end) 100%);
                padding: 2rem 0;
                z-index: 1000;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
                overflow-y: auto;
                box-shadow: 4px 0 24px rgba(0, 0, 0, 0.12);
            }

            .judge-sidebar::-webkit-scrollbar {
                width: 6px;
            }

            .judge-sidebar::-webkit-scrollbar-track {
                background: rgba(255, 255, 255, 0.05);
            }

            .judge-sidebar::-webkit-scrollbar-thumb {
                background: rgba(255, 255, 255, 0.2);
                border-radius: 3px;
            }

            .sidebar-logo {
                padding: 0 1.5rem 2rem;
                border-bottom: 1px solid rgba(255, 255, 255, 0.1);
                margin-bottom: 2rem;
            }

            .sidebar-logo h2 {
                font-family: 'Cabinet Grotesk', sans-serif;
                font-weight: 800;
                font-size: 1.75rem;
                background: linear-gradient(135deg, #fff 0%, var(--accent-primary) 100%);
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
                background-clip: text;
                margin: 0;
                letter-spacing: -0.02em;
            }

            .sidebar-logo p {
                color: rgba(255, 255, 255, 0.6);
                font-size: 0.75rem;
                margin-top: 0.25rem;
                font-weight: 500;
                letter-spacing: 0.05em;
                text-transform: uppercase;
            }

            .sidebar-nav {
                padding: 0 1rem;
            }

            .nav-section-title {
                color: rgba(255, 255, 255, 0.5);
                font-size: 0.7rem;
                font-weight: 600;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                padding: 0 0.75rem;
                margin: 2rem 0 0.75rem;
            }

            .sidebar-nav-link {
                display: flex;
                align-items: center;
                gap: 0.875rem;
                padding: 0.875rem 0.75rem;
                color: rgba(255, 255, 255, 0.8);
                text-decoration: none;
                border-radius: 12px;
                margin-bottom: 0.375rem;
                font-weight: 500;
                font-size: 0.9375rem;
                transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
                position: relative;
                overflow: hidden;
            }

            .sidebar-nav-link::before {
                content: '';
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                background: var(--active-gradient);
                opacity: 0;
                transition: opacity 0.25s ease;
                border-radius: 12px;
            }

            .sidebar-nav-link:hover::before {
                opacity: 0.1;
            }

            .sidebar-nav-link:hover {
                color: #fff;
                transform: translateX(4px);
            }

            .sidebar-nav-link.active {
                background: var(--active-gradient);
                color: #fff;
                box-shadow: 0 4px 12px rgba(245, 158, 11, 0.3);
                font-weight: 600;
            }

            .sidebar-nav-link.active::before {
                opacity: 0;
            }

            .sidebar-nav-link i {
                font-size: 1.25rem;
                width: 24px;
                text-align: center;
                position: relative;
                z-index: 1;
            }

            .sidebar-nav-link span {
                position: relative;
                z-index: 1;
            }

            .nav-badge {
                margin-left: auto;
                background: rgba(239, 68, 68, 0.9);
                color: #fff;
                font-size: 0.7rem;
                padding: 0.15rem 0.5rem;
                border-radius: 20px;
                font-weight: 600;
                position: relative;
                z-index: 1;
            }

            /* Main Content Area */
            .judge-content-wrapper {
                margin-left: var(--sidebar-width);
                min-height: 100vh;
                transition: margin-left 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .judge-topbar {
                background: #fff;
                height: var(--topbar-height);
                border-bottom: 1px solid var(--border-color);
                display: flex;
                align-items: center;
                justify-content: space-between;
                padding: 0 2rem;
                position: sticky;
                top: 0;
                z-index: 999;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            }

            .topbar-left {
                display: flex;
                align-items: center;
                gap: 1.5rem;
            }

            .judge-sidebar-toggle {
                display: none;
                background: none;
                border: none;
                font-size: 1.5rem;
                color: var(--text-primary);
                cursor: pointer;
                padding: 0.5rem;
                border-radius: 8px;
                transition: all 0.2s ease;
            }

            .judge-sidebar-toggle:hover {
                background: var(--hover-bg);
            }

            .topbar-search {
                position: relative;
                width: 400px;
            }

            .topbar-search input {
                width: 100%;
                padding: 0.75rem 1rem 0.75rem 3rem;
                border: 1px solid var(--border-color);
                border-radius: 12px;
                font-size: 0.9375rem;
                transition: all 0.2s ease;
                background: var(--hover-bg);
            }

            .topbar-search input:focus {
                outline: none;
                border-color: var(--accent-primary);
                box-shadow: 0 0 0 3px rgba(245, 158, 11, 0.1);
                background: #fff;
            }

            .topbar-search i {
                position: absolute;
                left: 1rem;
                top: 50%;
                transform: translateY(-50%);
                color: var(--text-secondary);
                font-size: 1.125rem;
            }

            .topbar-right {
                display: flex;
                align-items: center;
                gap: 1rem;
            }

            .topbar-icon-btn {
                position: relative;
                background: none;
                border: none;
                width: 42px;
                height: 42px;
                border-radius: 10px;
                display: flex;
                align-items: center;
                justify-content: center;
                cursor: pointer;
                transition: all 0.2s ease;
                color: var(--text-secondary);
                font-size: 1.25rem;
            }

            .topbar-icon-btn:hover {
                background: var(--hover-bg); 
                color: var(--text-primary);
            }

            .topbar-icon-btn .badge {
                position: absolute;
                top: 6px;
                right: 6px;
                width: 8px;
                height: 8px;
                background: #ef4444;
                border-radius: 50%;
                border: 2px solid #fff;
            }

            .user-menu {
                display: flex;
                align-items: center;
                gap: 0.75rem;
                padding: 0.5rem 0.75rem 0.5rem 0.5rem;
                border-radius: 12px;
                cursor: pointer;
                transition: all 0.2s ease;
                border: 1px solid var(--border-color);
            }

            .user-menu:hover {
                background: var(--hover-bg);
                border-color: var(--accent-primary);
            }

            .user-avatar {
                width: 38px;
                height: 38px;
                border-radius: 10px;
                background: var(--active-gradient);
                display: flex;
                align-items: center;
                justify-content: center;
                color: #fff;
                font-weight: 700;
                font-size: 0.875rem;
            }

            .user-info {
                display: flex;
                flex-direction: column;
            }

            .user-name {
                font-weight: 600;
                font-size: 0.9375rem;
                color: var(--text-primary);
                line-height: 1.2;
            }

            .user-role {
                font-size: 0.75rem;
                color: var(--text-secondary);
                line-height: 1.2;
            }

            .admin-content {
                padding: 2rem;
            }

            .page-header {
                margin-bottom: 2rem;
            }

            .page-title {
                font-family: 'Cabinet Grotesk', sans-serif;
                font-size: 2rem;
                font-weight: 800;
                color: var(--text-primary);
                margin-bottom: 0.5rem;
                letter-spacing: -0.02em;
            }

            .page-subtitle {
                color: var(--text-secondary);
                font-size: 1rem;
            }

            /* Mobile Responsive */
            @media (max-width: 992px) {
                .judge-sidebar {
                    transform: translateX(-100%);
                }

                .judge-sidebar.active {
                    transform: translateX(0);
                }

                .judge-content-wrapper {
                    margin-left: 0;
                }

                .judge-sidebar-toggle {
                    display: block;
                }

                .topbar-search {
                    display: none;
                }

                .judge-sidebar-overlay {
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background: rgba(0, 0, 0, 0.5);
                    z-index: 999;
                    opacity: 0;
                    visibility: hidden;
                    transition: all 0.3s ease;
                }

                .judge-sidebar-overlay.active {
                    opacity: 1;
                    visibility: visible;
                }
            }

            @media (max-width: 576px) {
                .judge-topbar {
                    padding: 0 1rem;
                }

                .admin-content {
                    padding: 1rem;
                }

                .user-info {
                    display: none;
                }

                .page-title {
                    font-size: 1.5rem;
                }
            }

            /* Animations */
            @keyframes slideInRight {
                from {
                    opacity: 0;
                    transform: translateX(20px);
                }
                to {
                    opacity: 1;
                    transform: translateX(0);
                }
            }

            .admin-content > * {
                animation: slideInRight 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            }
        </style>
    </head>
    <body>
        <!-- Sidebar Overlay for Mobile -->
        <div class="judge-sidebar-overlay" id="judgeSidebarOverlay"></div>

        <!-- Sidebar -->
        <aside class="judge-sidebar" id="judgeSidebar">
            <div class="sidebar-logo">
                <h2>{{ config('app.name', 'Judge') }}</h2>
                <p>Judging Panel</p>
            </div>

            <nav class="sidebar-nav">
                <div class="nav-section-title">Main</div>
                <a href="{{ route('judge.dashboard') }}" class="sidebar-nav-link {{ request()->routeIs('judge.dashboard') ? 'active' : '' }}">
                    <i class="bi bi-grid-fill"></i>
                    <span>Dashboard</span>
                </a>

                <div class="nav-section-title">Management</div>
                <a href="{{ route('judge.manage_participants.index') }}" class="sidebar-nav-link {{ request()->routeIs('judge.manage_participants.*') ? 'active' : '' }}">
                    <i class="bi bi-people-fill"></i>
                    <span>Manage Participants</span>
                </a>

                <a href="{{ route('judge.event.index') }}" class="sidebar-nav-link {{ request()->routeIs('judge.event.*') ? 'active' : '' }}">
                    <i class="bi bi-calendar-event-fill"></i>
                    <span>Events Management</span>
                </a>

                <a href="{{ route('judge.category.index') }}" class="sidebar-nav-link {{ request()->routeIs('judge.category.*') ? 'active' : '' }}">
                    <i class="bi bi-tag-fill"></i>
                    <span>Categories</span>
                </a>

                <a href="{{ route('judge.criteria.index') }}" class="sidebar-nav-link {{ request()->routeIs('judge.criteria.*') ? 'active' : '' }}">
                    <i class="bi bi-list-check"></i>
                    <span>Criteria</span>
                </a>

                <div class="nav-section-title">Scoring</div>
                <a href="{{ route('judge.scoring.index') }}" class="sidebar-nav-link {{ request()->routeIs('judge.scoring.*') ? 'active' : '' }}">
                    <i class="bi bi-calculator-fill"></i>
                    <span>Scoring</span>
                </a>

                <div class="nav-section-title">Settings</div>
                <a href="{{ route('judge.profile.edit') }}" class="sidebar-nav-link {{ request()->routeIs('judge.profile.edit') ? 'active' : '' }}">
                    <i class="bi bi-person-circle"></i>
                    <span>Profile</span>
                </a>

                <a href="#" class="sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="bi bi-box-arrow-right"></i>
                    <span>Logout</span>
                </a>

                <form id="logout-form" method="POST" action="{{ route('logout') }}" style="display: none;">
                    @csrf
                </form>
            </nav>
        </aside>

        <!-- Main Content Wrapper -->
        <div class="judge-content-wrapper">
            <!-- Top Bar -->
            <header class="judge-topbar">
                <div class="topbar-left">
                    <button class="judge-sidebar-toggle" id="judgeSidebarToggle">
                        <i class="bi bi-list"></i>
                    </button>

                    <div class="topbar-search">
                        <i class="bi bi-search"></i>
                        <input type="text" placeholder="Search anything...">
                    </div>
                </div>

                <div class="topbar-right">
                    <button class="topbar-icon-btn">
                        <i class="bi bi-bell-fill"></i>
                        <span class="badge"></span>
                    </button>

                    <button class="topbar-icon-btn">
                        <i class="bi bi-chat-dots-fill"></i>
                    </button>

                    <div class="user-menu" data-bs-toggle="dropdown">
                        <div class="user-avatar">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="user-info">
                            <div class="user-name">{{ Auth::user()->name }}</div>
                            <div class="user-role">Judge</div>
                        </div>
                        <i class="bi bi-chevron-down" style="color: var(--text-secondary); font-size: 0.875rem;"></i>
                    </div>

                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('judge.profile.edit') }}">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </header>

            <!-- Page Content -->
            <main class="admin-content">
                @isset($header)
                    <div class="page-header">
                        {{ $header }}
                    </div>
                @endisset

                {{ $slot }}
            </main>
        </div>

        <!-- Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

        <script>
            // Sidebar Toggle Functionality
            const judgeSidebarToggle = document.getElementById('judgeSidebarToggle');
            const judgeSidebar = document.getElementById('judgeSidebar');
            const judgeSidebarOverlay = document.getElementById('judgeSidebarOverlay');

            if (judgeSidebarToggle) {
                judgeSidebarToggle.addEventListener('click', function() {
                    judgeSidebar.classList.toggle('active');
                    judgeSidebarOverlay.classList.toggle('active');
                });

                judgeSidebarOverlay.addEventListener('click', function() {
                    judgeSidebar.classList.remove('active');
                    judgeSidebarOverlay.classList.remove('active');
                });
            }

            // Close sidebar on mobile when clicking a link
            const sidebarLinks = document.querySelectorAll('.sidebar-nav-link');
            sidebarLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth < 992) {
                        judgeSidebar.classList.remove('active');
                        judgeSidebarOverlay.classList.remove('active');
                    }
                });
            });

            // Search functionality placeholder
            const searchInput = document.querySelector('.topbar-search input');
            if (searchInput) {
                searchInput.addEventListener('input', function(e) {
                    // Add your search logic here
                    console.log('Searching for:', e.target.value);
                });
            }
        </script>

        @stack('scripts')
    </body>
</html>

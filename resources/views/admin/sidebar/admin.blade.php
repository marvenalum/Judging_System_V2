<!-- Sidebar -->
<div class="bg-white shadow-sm rounded p-4 h-100">
    <h5 class="fw-bold mb-4">Admin Navigation</h5>
    <nav class="nav flex-column">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active bg-primary text-white' : 'text-dark' }}">
            Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.index') ? 'active bg-primary text-white' : 'text-dark' }}">
            Manage Users
        </a>
        <a href="{{ route('admin.event.index') }}" class="nav-link {{ request()->routeIs('admin.event.*') ? 'active bg-primary text-white' : 'text-dark' }}">
            Events Management
        </a>
        <a href="{{ route('admin.category.index') }}" class="nav-link {{ request()->routeIs('admin.category.*') ? 'active bg-primary text-white' : 'text-dark' }}">
            Category Management
        </a>
        <a href="{{ route('profile.edit') }}" class="nav-link {{ request()->routeIs('profile.edit') ? 'active bg-primary text-white' : 'text-dark' }}">
            Profile
        </a>
    </nav>
</div>

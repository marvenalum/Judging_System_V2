<x-admin-layout>
    <x-slot name="header">
        <h2 class="page-title">Manage Users</h2>
        <p class="page-subtitle">View and manage all users in the system</p>
    </x-slot>

    <style>
        .page-title { font-family: 'Cabinet Grotesk', sans-serif; font-size: 2rem; font-weight: 800; color: #1a1d29; }
        .page-subtitle { color: #6b7280; font-size: 1rem; margin-bottom: 1.5rem; }

        .card { background: #fff; border-radius: 16px; box-shadow: 0 4px 16px rgba(0,0,0,0.08); padding: 2rem; margin-bottom: 2rem; }
        .card-header { font-weight: 700; font-size: 1.25rem; margin-bottom: 1rem; color: #1a1d29; }

        .btn-primary { background: #0a4d68; color: white; font-weight: 600; padding: 0.5rem 1rem; border-radius: 8px; transition: 0.3s; }
        .btn-primary:hover { backgrou nd: #088395; }

        table { width: 100%; border-collapse: collapse; background: #fff; }
        th, td { padding: 0.75rem 1rem; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { background: #f9fafb; color: #6b7280; font-weight: 600; font-size: 0.875rem; text-transform: uppercase; }
        td { color: #1a1d29; font-size: 0.9375rem; }

        a.action-link { color: #0a4d68; font-weight: 600; transition: 0.2s; }
        a.action-link:hover { color: #088395; }

        button.delete-btn { color: #ef4444; font-weight: 600; background: none; border: none; cursor: pointer; transition: 0.2s; }
        button.delete-btn:hover { color: #b91c1c; }

        @media (max-width: 768px) {
            .page-title { font-size: 1.5rem; }
            .page-subtitle { font-size: 0.875rem; }
            table th, table td { padding: 0.5rem; font-size: 0.875rem; }
        }
    </style>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="card" style="background-color: #d1fae5; border-color: #10b981; color: #065f46;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Actions -->
            <div class="card">
                <div class="card-header">Quick Actions</div>
                <a href="{{ route('admin.users.create') }}" class="btn-primary">Create New User</a>
            </div>

            <!-- Users Table -->
            <div class="card">
                <div class="card-header">All Users</div>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role ?? 'N/A' }}</td>
                                <td>
                                    <a href="{{ route('admin.users.edit', $user) }}" class="action-link">Edit</a>
                                    <span class="mx-1">|</span>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="delete-btn" onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        @if($users->isEmpty())
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-4">No users found.</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-admin-layout>

<x-app-sidebar>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Manage Users') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ $users->count() }} {{ Str::plural('user', $users->count()) }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 mb-6">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">All Users</h3>
                        <p class="mt-1 text-sm text-gray-600">View all users in the system</p>
                    </div>
                </div>

                <div class="p-6">
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Name</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Email</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Role</th>
                                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Created At</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="h-10 w-10 bg-gradient-to-br from-indigo-500 to-indigo-600 rounded-full flex items-center justify-center">
                                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $user->name }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $user->role === 'admin' ? 'bg-blue-100 text-blue-800' : ($user->role === 'judge' ? 'bg-purple-100 text-purple-800' : 'bg-green-100 text-green-800') }}">
                                                {{ ucfirst($user->role) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $user->created_at->format('M d, Y') }}</div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-16 text-center">
                                            <div class="text-gray-500">No users found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    @if($users instanceof \Illuminate\Pagination\LengthAwarePaginator && $users->hasPages())
                        <div class="mt-6">
                            {{ $users->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar>

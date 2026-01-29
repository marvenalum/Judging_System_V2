<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                @include('admin.sidebar.admin')

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">Welcome to the Admin Dashboard!</h1>
                        <p>You have administrative privileges. Manage users, settings, and more.</p>
                        @if(session('success'))
                            <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Admin Stats Section -->
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold mb-4">System Overview</h2>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="bg-gray-50 p-4 rounded">
                                    <h3 class="text-lg font-semibold">Total Users</h3>
                                    <p class="text-2xl font-bold">{{ \App\Models\User::count() }}</p>
                                </div>
                                <div class="bg-gray-50 p-4 rounded">
                                    <h3 class="text-lg font-semibold">Active Competitions</h3>
                                    <p class="text-2xl font-bold">0</p> <!-- Placeholder -->
                                </div>
                                <div class="bg-gray-50 p-4 rounded">
                                    <h3 class="text-lg font-semibold">Pending Submissions</h3>
                                    <p class="text-2xl font-bold">0</p> <!-- Placeholder -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

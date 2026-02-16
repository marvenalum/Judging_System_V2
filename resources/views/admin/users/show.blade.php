<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                @include('admin.sidebar.admin')

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">User Details</h1>

                        <div class="mb-4">
                            <strong>ID:</strong> {{ $user->id }}
                        </div>
                        <div class="mb-4">
                            <strong>Name:</strong> {{ $user->name }}
                        </div>
                        <div class="mb-4">
                            <strong>Email:</strong> {{ $user->email }}
                        </div>
                        <div class="mb-4">
                            <strong>Role:</strong> {{ $user->role ?? 'N/A' }}
                        </div>
                        <div class="mb-4">
                            <strong>Created At:</strong> {{ $user->created_at }}
                        </div>
                        <div class="mb-4">
                            <strong>Updated At:</strong> {{ $user->updated_at }}
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.users.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">Back to Users</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">Edit</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

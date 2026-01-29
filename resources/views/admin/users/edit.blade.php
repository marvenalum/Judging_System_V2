<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                @include('admin.sidebar.admin')

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">Edit User</h1>

                        <form method="POST" action="{{ route('admin.users.update', $user) }}">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="mb-4">
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Email -->
                            <div class="mb-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $user->email)" required autocomplete="email" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="mb-4">
                                <x-input-label for="password" :value="__('Password')" />
                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                <p class="text-sm text-gray-600 mt-1">Leave blank to keep current password.</p>
                            </div>

                            <!-- Confirm Password -->
                            <div class="mb-4">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" autocomplete="new-password" />
                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <!-- Role -->
                            <div class="mb-4">
                                <x-input-label for="role" :value="__('Role')" />
                                <select id="role" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="role" required>
                                    <option value="">Select Role</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                    <option value="judge" {{ old('role', $user->role) == 'judge' ? 'selected' : '' }}>Judge</option>
                                    <option value="participant" {{ old('role', $user->role) == 'participant' ? 'selected' : '' }}>Participant</option>
                                </select>
                                <x-input-error :messages="$errors->get('role')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <a href="{{ route('admin.users.index') }}" class="mr-4 text-gray-600 hover:text-gray-800">Cancel</a>
                                <x-primary-button>
                                    {{ __('Update User') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">My Profile</h1>
                    </div>

                    @if(session('success'))
                        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('profile.update') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <input id="name" name="name" type="text" value="{{ old('name', auth()->user()->name) }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                   required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input id="email" name="email" type="email" value="{{ old('email', auth()->user()->email) }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('email') border-red-500 @enderror"
                                   required>
                            @error('email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Role (Read-only) -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                            <input id="role" name="role" type="text" value="{{ ucfirst(auth()->user()->role) }}"
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-500"
                                   readonly>
                            <p class="mt-1 text-sm text-gray-500">Your role cannot be changed from this interface.</p>
                        </div>

                        <!-- Account Information -->
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h3 class="text-lg font-semibold mb-3">Account Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="block text-sm font-medium text-gray-700">Member Since</span>
                                    <span class="text-gray-600">{{ auth()->user()->created_at->format('F d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-700">Last Updated</span>
                                    <span class="text-gray-600">{{ auth()->user()->updated_at->format('F d, Y') }}</span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-700">Email Verified</span>
                                    <span class="text-gray-600">
                                        @if(auth()->user()->email_verified_at)
                                            <span class="text-green-600">✓ Verified</span>
                                        @else
                                            <span class="text-red-600">✗ Not Verified</span>
                                        @endif
                                    </span>
                                </div>
                                <div>
                                    <span class="block text-sm font-medium text-gray-700">Assigned Events</span>
                                    <span class="text-gray-600">{{ auth()->user()->assignedEvents->count() }} events</span>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-6 py-2 rounded-md font-medium">
                                Update Profile
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Statistics -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mt-6">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Your Statistics</h2>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-blue-600">{{ auth()->user()->assignedEvents->count() }}</div>
                            <div class="text-blue-800">Assigned Events</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-green-600">{{ auth()->user()->givenScores()->where('status', 'submitted')->count() }}</div>
                            <div class="text-green-800">Scores Submitted</div>
                        </div>
                        <div class="bg-yellow-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-yellow-600">{{ auth()->user()->givenScores()->where('status', 'draft')->count() }}</div>
                            <div class="text-yellow-800">Draft Scores</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg text-center">
                            <div class="text-2xl font-bold text-purple-600">{{ auth()->user()->givenScores()->avg('score') ? number_format(auth()->user()->givenScores()->avg('score'), 1) : '0.0' }}</div>
                            <div class="text-purple-800">Average Score Given</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-judge-layout>

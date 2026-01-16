<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Participant Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                <!-- Sidebar -->
                <div class="w-64 bg-white shadow-sm sm:rounded-lg mr-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Navigation</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-gray-700 bg-gray-200 rounded hover:bg-gray-300 {{ request()->routeIs('dashboard') ? 'bg-blue-500 text-white' : '' }}">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Submissions
                                </a>
                            </li>
                            <li>
                                <a href="#" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Competitions
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Profile
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">Welcome to the Participant Dashboard!</h1>
                        <p>You are a participant. View your submissions and competition details.</p>
                        @if(session('success'))
                            <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Submissions Section -->
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold mb-4">Your Submissions</h2>
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-gray-600">No submissions yet. Start by participating in a competition!</p>
                                <!-- Placeholder for submissions list -->
                                <!-- @foreach($submissions as $submission)
                                    <div class="mb-2">
                                        <strong>{{ $submission->title }}</strong> - Status: {{ $submission->status }}
                                    </div>
                                @endforeach -->
                            </div>
                        </div>

                        <!-- Competitions Section -->
                        <div class="mt-8">
                            <h2 class="text-xl font-semibold mb-4">Active Competitions</h2>
                            <div class="bg-gray-50 p-4 rounded">
                                <p class="text-gray-600">No active competitions at the moment.</p>
                                <!-- Placeholder for competitions list -->
                                <!-- @foreach($competitions as $competition)
                                    <div class="mb-2">
                                        <strong>{{ $competition->name }}</strong> - Deadline: {{ $competition->deadline }}
                                    </div>
                                @endforeach -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

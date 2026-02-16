<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Participants') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                <!-- Sidebar -->
                <div class="w-64 bg-white shadow-sm sm:rounded-lg mr-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Judge Module</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('judge.dashboard') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('judge.assigned-events') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Assigned Events Overview
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('judge.my-events') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    My Events
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('judge.participants') }}" class="block px-4 py-2 text-gray-700 bg-blue-500 text-white rounded">
                                    Participants
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('judge.review-scores') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Scoring
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('judge.profile') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Profile
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-6">
                            <div>
                                <h1 class="text-2xl font-bold">Participants</h1>
                                @if(isset($eventId) && $eventId)
                                    <p class="text-gray-600 mt-1">Showing participants for Event #{{ $eventId }}</p>
                                @else
                                    <p class="text-gray-600 mt-1">All participants across your assigned events</p>
                                @endif
                            </div>
                            <div class="flex space-x-2">
                                @if(isset($eventId) && $eventId)
                                    <a href="{{ route('judge.event.details', $eventId) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                                        Back to Event
                                    </a>
                                @endif
                                <a href="{{ route('judge.dashboard') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded text-sm">
                                    Back to Dashboard
                                </a>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($participants as $participant)
                                <div class="bg-gray-50 p-6 rounded-lg border hover:shadow-lg transition-shadow">
                                    <div class="flex items-center mb-4">
                                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center text-white font-bold text-lg mr-4">
                                            {{ strtoupper(substr($participant->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h3 class="font-semibold text-lg text-gray-900">{{ $participant->name }}</h3>
                                            <p class="text-gray-600 text-sm">{{ $participant->email }}</p>
                                        </div>
                                    </div>

                                    <div class="space-y-2 mb-4">
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Role:</span>
                                            <span class="font-medium capitalize">{{ $participant->role }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Joined:</span>
                                            <span class="font-medium">{{ $participant->created_at->format('M d, Y') }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Total Scores:</span>
                                            <span class="font-medium">{{ $participant->receivedScores->count() }}</span>
                                        </div>
                                        <div class="flex justify-between text-sm">
                                            <span class="text-gray-500">Average Score:</span>
                                            <span class="font-medium">
                                                @if($participant->receivedScores->count() > 0)
                                                    {{ number_format($participant->receivedScores->avg('score'), 1) }}/100
                                                @else
                                                    N/A
                                                @endif
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex space-x-2">
                                        @if(isset($eventId) && $eventId)
                                            <a href="{{ route('judge.enter-scores', [$eventId, $participant->id]) }}"
                                               class="flex-1 bg-blue-500 hover:bg-blue-600 text-white text-center py-2 px-4 rounded text-sm font-medium">
                                                Enter Scores
                                            </a>
                                        @else
                                            <button class="flex-1 bg-gray-300 text-gray-500 text-center py-2 px-4 rounded text-sm font-medium cursor-not-allowed"
                                                    disabled>
                                                Select Event First
                                            </button>
                                        @endif
                                        <a href="mailto:{{ $participant->email }}"
                                           class="bg-green-500 hover:bg-green-600 text-white text-center py-2 px-4 rounded text-sm font-medium">
                                            Contact
                                        </a>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-12">
                                    <div class="text-gray-400 mb-4">
                                        <svg class="mx-auto h-12 w-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Participants Found</h3>
                                    <p class="text-gray-500">
                                        @if(isset($eventId) && $eventId)
                                            No participants have registered for this event yet.
                                        @else
                                            No participants found across your assigned events.
                                        @endif
                                    </p>
                                </div>
                            @endforelse
                        </div>

                        @if($participants instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator && $participants->hasPages())
                            <div class="mt-6">
                                {{ $participants->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

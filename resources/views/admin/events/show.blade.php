<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                <!-- Sidebar -->
                <div class="w-64 bg-white shadow-sm sm:rounded-lg mr-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold mb-4">Admin Navigation</h3>
                        <ul class="space-y-2">
                            <li>
                                <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Dashboard
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Manage Users
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.event.index') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Events Management
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('admin.settings') }}" class="block px-4 py-2 text-gray-700 rounded hover:bg-gray-300">
                                    Settings
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
                        <div class="flex justify-between items-center mb-4">
                            <h1 class="text-2xl font-bold">{{ $event->event_name }}</h1>
                            <div>
                                <a href="{{ route('admin.event.edit', $event) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2">
                                    Edit Event
                                </a>
                                <a href="{{ route('admin.event.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                    Back to Events
                                </a>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Event Information</h3>
                                <p><strong>ID:</strong> {{ $event->id }}</p>
                                <p><strong>Name:</strong> {{ $event->event_name }}</p>
                                <p><strong>Description:</strong> {{ $event->event_description }}</p>
                                <p><strong>Type:</strong> {{ ucfirst($event->event_type) }}</p>
                                <p><strong>Start Date:</strong> {{ $event->start_date->format('Y-m-d H:i') }}</p>
                                <p><strong>End Date:</strong> {{ $event->end_date->format('Y-m-d H:i') }}</p>
                                <p><strong>Status:</strong>
                                    <span class="px-2 py-1 rounded text-xs font-semibold
                                        @if($event->event_status === 'upcoming') bg-blue-100 text-blue-800
                                        @elseif($event->event_status === 'ongoing') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif">
                                        {{ ucfirst($event->event_status) }}
                                    </span>
                                </p>
                                <p><strong>Created At:</strong> {{ $event->created_at->format('Y-m-d H:i') }}</p>
                                <p><strong>Updated At:</strong> {{ $event->updated_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>

                        <div class="mt-6">
                            <form method="POST" action="{{ route('admin.event.destroy', $event) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this event?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Delete Event
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

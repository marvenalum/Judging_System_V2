<x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Assigned Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                @include('admin.sidebar.judge')

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div class="flex justify-between items-center mb-4">
                            <h1 class="text-2xl font-bold">My Assigned Events</h1>
                        </div>
                        <p>View events assigned to you for judging.</p>

                        <!-- Events Table -->
                        <div class="mt-8">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">ID</th>
                                        <th class="py-2 px-4 border-b">Event Name</th>
                                        <th class="py-2 px-4 border-b">Description</th>
                                        <th class="py-2 px-4 border-b">Start Date</th>
                                        <th class="py-2 px-4 border-b">End Date</th>
                                        <th class="py-2 px-4 border-b">Status</th>
                                        <th class="py-2 px-4 border-b">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($events as $event)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $event->id }}</td>
                                            <td class="py-2 px-4 border-b">{{ $event->event_name }}</td>
                                            <td class="py-2 px-4 border-b">{{ Str::limit($event->event_description, 50) }}</td>
                                            <td class="py-2 px-4 border-b">{{ $event->start_date->format('Y-m-d H:i') }}</td>
                                            <td class="py-2 px-4 border-b">{{ $event->end_date->format('Y-m-d H:i') }}</td>
                                            <td class="py-2 px-4 border-b">
                                                <span class="px-2 py-1 rounded text-xs font-semibold
                                                    @if($event->event_status === 'upcoming') bg-blue-100 text-blue-800
                                                    @elseif($event->event_status === 'ongoing') bg-green-100 text-green-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($event->event_status) }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                <a href="{{ route('judge.event.show', $event) }}" class="text-green-600 hover:text-green-800 mr-2">View</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                                                No events assigned to you.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-judge-layout>

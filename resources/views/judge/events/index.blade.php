<x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Assigned Events') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                        <div>
                            <h1 class="text-2xl font-bold">My Assigned Events</h1>
                            <p class="text-gray-500 mt-1">View events assigned to you for judging.</p>
                        </div>
                    </div>

                    <!-- Events Table -->
                    <div class="overflow-x-auto">
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
                                                <span class="px-2 py-1 rounded text-xs font-semibold @class([
                                                    'bg-blue-100 text-blue-800' => $event->event_status === 'upcoming',
                                                    'bg-green-100 text-green-800' => $event->event_status === 'ongoing',
                                                    'bg-gray-100 text-gray-800' => $event->event_status !== 'upcoming' && $event->event_status !== 'ongoing'
                                                ])">
                                                    {{ ucfirst($event->event_status) }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                <a href="{{ route('admin.event.show', $event) }}" class="text-green-600 hover:text-green-800 mr-2">View</a>
                                                <a href="{{ route('admin.event.edit', $event) }}" class="text-blue-600 hover:text-blue-800 mr-2">Edit</a>
                                                <form method="POST" action="{{ route('admin.event.destroy', $event) }}" class="inline" onsubmit="return confirm('Are you sure you want to delete this event?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="py-4 px-4 text-center text-gray-500">
                                                No events found. <a href="{{ route('admin.event.create') }}" class="text-blue-600 hover:text-blue-800">Create your first event</a>.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                    </div>

                    <!-- Pagination (only show if paginated) -->
                    @if(method_exists($events, 'hasPages') && $events->hasPages())
                        <div class="mt-4">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-judge-layout>

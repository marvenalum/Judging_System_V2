<x-app-sidebar>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
                {{ __('Manage Events') }}
            </h2>
            <div class="text-sm text-gray-600">
                {{ $events->count() }} {{ Str::plural('event', $events->count()) }}
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Success Message -->
            @if(session('success'))
                <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-green-800 font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
                <div class="mb-6 p-4 bg-gradient-to-r from-red-50 to-red-100 border-l-4 border-red-500 rounded-lg shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-red-800 font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            @endif

            <!-- Header Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100 mb-6">
                <div class="p-6 border-b border-gray-100 bg-gradient-to-r from-gray-50 to-white">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900">All Events</h3>
                            <p class="mt-1 text-sm text-gray-600">View and apply to events in the system</p>
                        </div>
                    </div>
                </div>

                <!-- Table Section -->
                <div class="p-6">
                    <div class="overflow-x-auto rounded-lg border border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                            </svg>
                                            Event Name
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                            </svg>
                                            Description
                                        </div>
                                    </th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Dates</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($events as $event)
                                    <tr class="hover:bg-gray-50 transition-colors duration-150">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="flex-shrink-0 h-10 w-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg flex items-center justify-center shadow-sm">
                                                    <span class="text-white font-bold text-sm">{{ strtoupper(substr($event->event_name, 0, 2)) }}</span>
                                                </div>
                                                <div class="ml-4">
                                                    <div class="text-sm font-semibold text-gray-900">{{ $event->event_name }}</div>
                                                    <div class="text-xs text-gray-500">{{ ucfirst($event->event_type) }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-sm text-gray-600 max-w-md">
                                                {{ $event->event_description ? Str::limit($event->event_description, 60) : 'No description provided' }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-900">
                                                <div class="flex items-center text-xs">
                                                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $event->start_date->format('M d, Y') }}
                                                </div>
                                                <div class="text-gray-400">to</div>
                                                <div class="flex items-center text-xs">
                                                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                    </svg>
                                                    {{ $event->end_date->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="@class([
                                                'px-2 py-1 rounded-full text-xs font-semibold',
                                                'bg-blue-100 text-blue-800' => $event->event_status === 'upcoming',
                                                'bg-green-100 text-green-800' => $event->event_status === 'ongoing',
                                                'bg-gray-100 text-gray-800' => $event->event_status === 'completed'
                                            ])">
                                                {{ ucfirst($event->event_status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex items-center justify-end space-x-2">
                                                <a href="{{ route('participant.event.show', $event) }}" 
                                                   class="inline-flex items-center px-3 py-1.5 bg-green-100 hover:bg-green-200 text-green-700 rounded-md transition-colors duration-150"
                                                   title="View Event">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                @if($event->hasApplied($userId))
                                                    <span class="inline-flex items-center px-3 py-1.5 bg-green-100 text-green-700 rounded-md cursor-default" title="Already Applied">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                        </svg>
                                                        Applied
                                                    </span>
                                                @else
                                                    <form method="POST" action="{{ route('participant.event.apply', $event) }}" class="inline-block">
                                                        @csrf
                                                        <button type="submit" 
                                                                class="inline-flex items-center px-3 py-1.5 bg-purple-100 hover:bg-purple-200 text-purple-700 rounded-md transition-colors duration-150"
                                                                onclick="return confirm('Are you sure you want to apply to this event?')"
                                                                title="Apply to Event">
                                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                                            </svg>
                                                            Apply
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-16 text-center">
                                            <div class="flex flex-col items-center justify-center">
                                                <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <h3 class="text-lg font-semibold text-gray-900 mb-2">No events available</h3>
                                                <p class="text-gray-600">Check back later for new events</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($events instanceof \Illuminate\Pagination\LengthAwarePaginator && $events->hasPages())
                        <div class="mt-6">
                            {{ $events->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar>

<x-app-sidebar>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Event Details') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <!-- Event Header -->
                <div class="bg-gradient-to-r from-blue-600 to-blue-800 p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-white">{{ $event->event_name }}</h3>
                            <p class="text-blue-100 mt-1">{{ ucfirst($event->event_type) }}</p>
                        </div>
                        <span class="@class([
                            'px-3 py-1 rounded-full text-sm font-semibold',
                            'bg-blue-100 text-blue-800' => $event->event_status === 'upcoming',
                            'bg-green-100 text-green-800' => $event->event_status === 'ongoing',
                            'bg-gray-100 text-gray-800' => $event->event_status === 'completed'
                        ])">
                            {{ ucfirst($event->event_status) }}
                        </span>
                    </div>
                </div>

                <!-- Event Details -->
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Description</h4>
                            <p class="text-gray-700">{{ $event->event_description ?? 'No description provided' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Created By</h4>
                            <p class="text-gray-700">{{ $event->user->name ?? 'Unknown' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">Start Date</h4>
                            <p class="text-gray-700">{{ $event->start_date->format('F d, Y') }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-2">End Date</h4>
                            <p class="text-gray-700">{{ $event->end_date->format('F d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Categories Section -->
                    @if($event->categories->count() > 0)
                        <div class="mt-8 pt-6 border-t border-gray-200">
                            <h4 class="text-lg font-semibold text-gray-900 mb-4">Categories</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach($event->categories as $category)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-center justify-between mb-2">
                                            <h5 class="font-semibold text-gray-900">{{ $category->name }}</h5>
                                            <span class="@class([
                                                'px-2 py-1 rounded-full text-xs font-semibold',
                                                'bg-green-100 text-green-800' => $category->status === 'active',
                                                'bg-gray-100 text-gray-800' => $category->status === 'inactive'
                                            ])">
                                                {{ ucfirst($category->status) }}
                                            </span>
                                        </div>
                                        @if($category->description)
                                            <p class="text-sm text-gray-600">{{ $category->description }}</p>
                                        @endif
                                        @if($category->percentage_weight)
                                            <p class="text-sm text-gray-500 mt-2">Weight: {{ $category->percentage_weight }}%</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Actions -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 flex items-center justify-between">
                    <a href="{{ route('participant.event.index') }}" 
                       class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back to Events
                    </a>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('participant.event.edit', $event) }}" 
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 rounded-lg hover:bg-blue-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Edit
                        </a>
                        <form method="POST" action="{{ route('participant.event.destroy', $event) }}" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700"
                                    onclick="return confirm('Are you sure you want to delete this event? This action cannot be undone.')">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar>

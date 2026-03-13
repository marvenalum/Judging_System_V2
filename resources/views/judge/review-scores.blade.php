<x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Scoring') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-center mb-6">
                        <h1 class="text-2xl font-bold">Scoring</h1>
                        <div class="text-sm text-gray-500">
                            Total: {{ $stats['total'] ?? 0 }} | Submitted: {{ $stats['submitted'] ?? 0 }} | Draft: {{ $stats['draft'] ?? 0 }}
                        </div>
                    </div>

                    <p class="mb-4">Create new scores or review and manage your submitted scores for various events and participants.</p>
                    
                    @if(session('success'))
                        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8" id="create-score">
                        <h2 class="text-lg font-semibold text-blue-900 mb-4">Create New Score</h2>
                        <p class="text-sm text-blue-700 mb-4">Select criteria to score a participant.</p>
                        
                        @if($availableCriteria->isEmpty())
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                                No active criteria available
                            </div>
                            <div class="text-sm text-yellow-900">
                                <p><strong>Troubleshoot:</strong></p>
                                <ul class="list-disc ml-5 mt-1 space-y-1">
                                    @if(empty($assignedEvents))
                                        <li>Not assigned to events</li>
                                    @else
                                        <li>{{ $assignedEvents->count() }} event(s), {{ $assignedCategories->count() ?? 0 }} categories</li>
                                    @endif
                                </ul>
                            </div>
                        @else
                            <form method="GET" action="{{ route('judge.criteria.createScore') }}" class="flex flex-col sm:flex-row gap-4">
                                <select name="criterion" class="flex-1 border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">Select Criteria</option>
                                    @foreach($availableCriteria as $criterion)
                                        <option value="{{ $criterion->id }}">
                                            {{ $criterion->event->event_name ?? 'N/A' }} » 
                                            {{ $criterion->category->name ?? 'N/A' }} » 
                                            {{ $criterion->name }} ({{ $criterion->max_score }})
                                        </option>
                                    @endforeach
                                </select>
                                <x-primary-button>{{ __('Start Scoring') }}</x-primary-button>
                            </form>
                        @endif
                    </div>

                    <div class="mb-8">
                        <form method="GET" action="{{ url()->current() }}" class="bg-gray-50 p-6 rounded-lg mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select name="status" class="w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                        <option value="">All Scores</option>
                                        <option value="draft" {{ request('status')=='draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="submitted" {{ request('status')=='submitted' ? 'selected' : '' }}>Submitted</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Event</label>
                                    <select name="event_id" class="w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                        <option value="">All Events</option>
                                        @foreach($assignedEvents as $event)
                                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                                {{ $event->event_name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Category</label>
                                    <select name="category_id" class="w-full p-2 border border-gray-300 rounded-md shadow-sm">
                                        <option value="">All Categories</option>
                                        @foreach($assignedCategories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-end gap-2">
                                    <x-primary-button type="submit">Apply Filters</x-primary-button>
@if(count(array_filter(request()->only(['status','event_id','category_id']))) > 0)
                                        <a href="{{ url()->current() }}" class="px-4 py-2 text-sm bg-white border border-gray-300 rounded-md hover:bg-gray-50">
                                            Clear
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </form>

                        @if($assignedCategories->isNotEmpty())
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h3 class="font-semibold text-green-900 mb-3">Quick Actions</h3>
                                <div class="flex flex-wrap gap-2">
                                    @foreach($assignedCategories as $category)
                                        <a href="{{ route('judge.scoring.bulk', $category->id) }}" 
                                           class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white text-sm rounded font-medium">
                                            Bulk: {{ $category->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    <div>
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-xl font-bold text-gray-900">Your Scores</h2>
                            <span class="text-sm text-gray-500">{{ $scores->total() ?? 0 }} found</span>
                        </div>

                        @if($scores->isEmpty())
                            <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No scores yet</h3>
                                <p class="text-gray-500 mb-6">Your scores will appear here once you start scoring participants.</p>
                                <a href="#create-score" class="inline-flex px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-medium">
                                    Create First Score
                                </a>
                            </div>
                        @else
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criteria</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @foreach($scores as $score)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $score->participant->name ?? 'N/A' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm text-gray-900">{{ $score->event->event_name ?? 'N/A' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $score->criteria->name ?? 'N/A' }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $score->criteria->category->name ?? 'N/A' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                                                    {{ $score->score ?? 0 }}
                                                </td>
                                                <td class="px-6 py-4">
                                                    <div class="max-w-xs">
                                                        <div class="text-sm text-gray-900" title="{{ $score->comments ?? '' }}">
                                                            {{ Str::limit($score->comments ?? 'No comments', 50) }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    @php $status = $score->status ?? 'draft'; @endphp
                                                    @switch($status)
                                                        @case('submitted')
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                                Submitted
                                                            </span>
                                                            @break
                                                        @case('draft')
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Draft
                                                            </span>
                                                            @break
                                                        @default
                                                            <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">
                                                                {{ ucfirst($status) }}
                                                            </span>
                                                    @endswitch
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('judge.scoring.edit', $score->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                                        Edit<span class="sr-only"> score</span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-6 flex justify-between">
                                <div class="text-sm text-gray-700">
                                    Showing {{ $scores->firstItem() ?? 0 }} to {{ $scores->lastItem() ?? 0 }} of {{ $scores->total() }} results
                                </div>
                                <div>
                                    {{ $scores->appends(request()->query())->links() }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-judge-layout>


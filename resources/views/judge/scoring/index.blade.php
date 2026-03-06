<x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Score') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold">Edit Scores</h1>
                            <p class="mt-1 text-gray-600">View and edit your submitted scores for various events and participants.</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('judge.scoring.participants') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Participant Scores
                            </a>
                            <a href="{{ route('judge.scoring.category') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Score by Category
                            </a>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mt-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mt-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Filters -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg">
                        <form method="GET" action="{{ route('judge.scores.by-category') }}" class="flex flex-wrap gap-4 items-end">
                            <div class="flex-1 min-w-[200px]">
                                <x-input-label for="category_id" :value="__('Filter by Category')" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Categories</option>
                                    @if(isset($categories))
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}">{{ $category->name }} ({{ $category->event->event_name ?? 'N/A' }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <x-input-label for="status" :value="__('Filter by Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Statuses</option>
                                    <option value="draft">Draft</option>
                                    <option value="pending">Pending</option>
                                    <option value="submitted">Submitted</option>
                                </select>
                            </div>
                            <div>
                                <x-primary-button type="submit">
                                    {{ __('Apply Filters') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Diagnostic Information -->
                    @if(!isset($availableCriteria) || (isset($availableCriteria) && $availableCriteria->isEmpty()))
                        <div class="mb-6 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <h3 class="text-yellow-800 font-semibold mb-2">Scoring Not Available</h3>
                            <p class="text-yellow-700 text-sm mb-3">There are no active criteria available for scoring. Please check the following:</p>
                            <ul class="list-disc list-inside text-yellow-700 text-sm space-y-1">
                                @if(!isset($assignedEvents) || $assignedEvents->isEmpty())
                                    <li><strong>No events assigned:</strong> You are not assigned to any events. Please contact the administrator to assign you to events.</li>
                                @else
                                    <li>You have {{ $assignedEvents->count() }} assigned event(s).</li>
                                @endif
                                
                                @if(isset($assignedEvents) && $assignedEvents->isNotEmpty())
                                    @if(!isset($assignedCategories) || $assignedCategories->isEmpty())
                                        <li><strong>No categories:</strong> The assigned events don't have any categories. Please ask the administrator to create categories.</li>
                                    @else
                                        <li>You have {{ $assignedCategories->count() }} category(ies) in your assigned events.</li>
                                        
                                        @php
                                            $criteriaCount = \App\Models\Criteria::whereIn('event_id', $assignedEvents->pluck('id'))
                                                ->where('status', 'active')
                                                ->count();
                                        @endphp
                                        
                                        @if($criteriaCount === 0)
                                            <li><strong>No active criteria:</strong> The assigned events don't have any active criteria. Please ask the administrator to create and activate criteria.</li>
                                        @else
                                            <li>There are {{ $criteriaCount }} active criteria, but they may not be accessible due to other issues.</li>
                                        @endif
                                    @endif
                                @endif
                            </ul>
                            <div class="mt-3">
                                <a href="{{ route('judge.dashboard') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-medium">← Back to Dashboard</a>
                            </div>
                        </div>
                    @endif

                    <!-- Scores Table -->
                    <div class="mt-8">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Participant
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Event
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Category
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Criteria
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Score
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Comments
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @forelse($scores as $score)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $score->participant->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $score->event->event_name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $score->criteria->category->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                {{ $score->criteria->name ?? 'N/A' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="font-semibold">{{ $score->score }}</span>
                                            </td>
                                            <td class="px-6 py-4">
                                                {{ $score->comments ?? 'No comments' }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($score->status == 'submitted')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                        Submitted
                                                    </span>
                                                @elseif($score->status == 'draft')
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        Draft
                                                    </span>
                                                @else
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                        {{ $score->status }}
                                                    </span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                <a href="{{ route('judge.scoring.edit', $score->id) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                                No scores found. Start scoring participants in the Scoring section.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $scores->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div> 
</x-judge-layout>

<?php /** @var \Illuminate\Pagination\LengthAwarePaginator|\App\Models\Score[] $scores */ ?>
<?php /** @var \Illuminate\Support\Collection $eventStats */ ?>

<x-app-sidebar>
<div class="p-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">My Scores</h1>
            <p class="mt-1 text-lg text-gray-600">View all scores received from judges across events</p>
        </div>
        
        @if($scores->count() > 0)
        <div class="flex flex-wrap gap-4 mt-4 md:mt-0">
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="text-2xl font-bold text-blue-600">{{ $scores->total() }}</div>
                <div class="text-sm text-gray-500">Total Scores</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="text-2xl font-bold text-green-600">{{ number_format($eventStats->sum('total_score'), 1) }}</div>
                <div class="text-sm text-gray-500">Total Points</div>
            </div>
            <div class="bg-white p-4 rounded-lg shadow">
                <div class="text-2xl font-bold text-purple-600">{{ $eventStats->count() }}</div>
                <div class="text-sm text-gray-500">Events</div>
            </div>
        </div>
        @endif
    </div>

    @if($scores->count() > 0)
        {{-- Event Summary Cards --}}
        @if($eventStats->count() > 0)
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            @foreach($eventStats as $stat)
            <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 hover:shadow-lg transition-shadow">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="font-semibold text-xl text-gray-900">{{ $stat->event->event_name ?? 'Unknown Event' }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $stat->event->event_type ?? '' }}</p>
                    </div>
                    <span class="text-2xl font-bold text-blue-600">{{ number_format($stat->total_score, 1) }}</span>
                </div>
                <div class="mt-4 grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <div class="text-gray-500">Avg Score</div>
                        <div class="font-semibold text-green-600">{{ number_format($stat->avg_score, 1) }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Judges</div>
                        <div class="font-semibold text-purple-600">{{ $stat->num_judges }}</div>
                    </div>
                    <div>
                        <div class="text-gray-500">Criteria</div>
                        <div class="font-semibold text-indigo-600">{{ $stat->num_scores }}</div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif

        {{-- Scores Table --}}
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold text-gray-900">Detailed Scores</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category / Criteria</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judge</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($scores as $score)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $score->event->event_name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($score->criteria->category)
                                <div class="text-sm font-medium text-indigo-600">{{ $score->criteria->category->name }}</div>
                                @endif
                                <div class="text-sm text-gray-900 mt-1">{{ $score->criteria->name ?? 'N/A' }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">
                                    @if($score->judge)
                                        {{ $score->judge->getDisplayNameAttribute() ?? 'Anonymous #' . substr(md5($score->judge_id), 0, 6) }}
                                    @else
                                        Unassigned
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-semibold text-green-600">
                                    {{ number_format($score->score, 1) }} 
                                    @if($score->criteria->max_score)
                                    / {{ $score->criteria->max_score }}
                                    <div class="w-24 bg-gray-200 rounded-full h-2 mt-1">
                                        <div class="bg-green-500 h-2 rounded-full" style="width: {{ min(100, ($score->score / $score->criteria->max_score) * 100) }}%"></div>
                                    </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @switch($score->status ?? 'draft')
                                    @case('submitted')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Submitted</span>
                                        @break
                                    @case('pending')
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Pending Review</span>
                                        @break
                                    @default
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $score->created_at->format('M d, Y') }}<br>
                                <span class="text-xs">{{ $score->created_at->diffForHumans() }}</span>
                            </td>
                        </tr>
                        @empty
                            {{-- Empty state inside table section --}}
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($scores->hasPages())
            <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                {{ $scores->appends(request()->query())->links() }}
            </div>
            @endif
        </div>
    @else
        {{-- Empty State --}}
        <div class="text-center py-20">
            <svg class="mx-auto h-24 w-24 text-gray-400 mb-6" fill="none" stroke="currentColor" viewBox="0 0 48 48">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h36m-6-6v36m6-30L12 42"/>
            </svg>
            <h2 class="text-2xl font-bold text-gray-900 mb-2">No scores yet</h2>
            <p class="text-lg text-gray-500 mb-6">You haven't received any scores from judges yet. Once judges complete scoring, your scores will appear here.</p>
            <a href="{{ route('participant.event.index') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                View Events & Submit
            </a>
        </div>
    @endif
</div>
</x-app-sidebar>


@extends('layouts.judge')


@section('content')
    {{-- Modern Header with gradient accent --}}
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                    Review & Manage Scores
                </h2>
                <p class="text-sm text-gray-500 mt-0.5">Judge dashboard · evaluate and track participant performance</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('judge.scoring.category') }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-indigo-50 text-indigo-700 rounded-xl hover:bg-indigo-100 text-sm font-medium transition-all active:scale-95 border border-indigo-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                    Score by Category
                </a>
                <a href="{{ route('judge.scoring.participants') }}" 
                   class="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-50 text-emerald-700 rounded-xl hover:bg-emerald-100 text-sm font-medium transition-all active:scale-95 border border-emerald-100">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    Participants Table
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-7 md:space-y-9">
            
            {{-- Stats Cards - Modern glassmorphic style --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 md:gap-5">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Total Scores</p>
                                <p class="text-2xl md:text-3xl font-extrabold text-gray-800 mt-1">{{ $stats['total_scores'] ?? $scores->total() }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-indigo-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Submitted</p>
                                <p class="text-2xl md:text-3xl font-extrabold text-gray-800 mt-1">{{ $stats['submitted_scores'] ?? 0 }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-emerald-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Pending</p>
                                <p class="text-2xl md:text-3xl font-extrabold text-gray-800 mt-1">{{ $stats['pending_scores'] ?? 0 }}</p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center">
                                <svg class="w-5 h-5 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200">
                    <div class="p-4">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs font-medium text-gray-400 uppercase tracking-wider">Avg Score</p>
                                <p class="text-2xl md:text-3xl font-extrabold text-gray-800 mt-1">{{ number_format(($stats['avg_score'] ?? 0), 1) }}<span class="text-sm font-normal text-gray-400">%</span></p>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Top Performers Leaderboard - Redesigned with medal icons --}}
            @if(isset($topPerformers) && $topPerformers->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50/40 to-transparent">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-amber-600" fill="currentColor" viewBox="0 0 20 20"><path d="M10 12l-5 3 1-6-4-4 6-1 3-5 3 5 6 1-4 4 1 6-5-3z"/></svg>
                        </div>
                        <h3 class="font-bold text-gray-800">🏆 Top Performers</h3>
                        <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-full">{{ $topPerformers->count() }} leaders</span>
                    </div>
                </div>
                <div class="p-5">
                    <div class="space-y-3">
                        @foreach($topPerformers as $index => $performer)
                            <div class="flex items-center justify-between p-3 bg-gray-50/50 rounded-xl hover:bg-gray-100/70 transition-all duration-150">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm 
                                        {{ $index == 0 ? 'bg-amber-400 text-amber-900' : ($index == 1 ? 'bg-gray-300 text-gray-700' : ($index == 2 ? 'bg-orange-300 text-orange-800' : 'bg-indigo-100 text-indigo-600')) }}">
                                        #{{ $index + 1 }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800 text-sm md:text-base">{{ $performer['participant']->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $performer['participant']->email }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-xl font-black text-indigo-600">{{ number_format($performer['grand_total_score'], 1) }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $performer['total_criteria_scored'] }} criteria</p>
                                    @if(isset($performer['percentage']))
                                        <p class="text-xs font-bold text-emerald-600">{{ $performer['percentage'] }}%</p>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        <div class="text-center pt-2">
                            <a href="{{ route('judge.scoring.participants') }}" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-700 font-medium text-sm bg-indigo-50 px-4 py-2 rounded-xl transition">
                                View Full Leaderboard
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Filters - Modern compact design --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm">
                <div class="p-5">
                    <form method="GET" action="{{ route('judge.scoring.index') }}" class="flex flex-col gap-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Search</label>
                                <x-text-input id="search" name="search" value="{{ request('search') }}" placeholder="Participant name or email" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-2.5 px-3" />
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Event</label>
                                <select id="event_id" name="event_id" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-2.5 px-3 focus:border-indigo-400 focus:ring-1 focus:ring-indigo-200">
                                    <option value="">All Events</option>
                                    @foreach($assignedEvents as $event)
                                        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                            {{ $event->event_name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1 block">Category</label>
                                <select id="category_id" name="category_id" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-2.5 px-3 focus:border-indigo-400 focus:ring-1 focus:ring-indigo-200">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex gap-2 items-center">
                                <button type="submit" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-indigo-700 active:scale-95 transition-all flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                                    Filter
                                </button>
                                @if(request()->hasAny(['search', 'event_id', 'category_id']))
                                    <a href="{{ route('judge.scoring.index') }}" class="px-4 py-2 text-gray-500 text-sm hover:text-gray-700 underline">Clear</a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Recent Scores Table - Clean card design --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center flex-wrap gap-2">
                    <div>
                        <h3 class="font-bold text-gray-800">Recent Scores</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $scores->total() }} total scores recorded</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Latest entries</span>
                    </div>
                </div>
                <div class="overflow-x-auto -webkit-overflow-scrolling-touch">
                    <table class="min-w-[750px] w-full">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/40">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Participant</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Event</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Category</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Criteria</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">Score</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Status</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Updated</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @forelse($scores as $score)
                                <tr class="hover:bg-gray-50/60 transition-all duration-150">
                                    <td class="px-5 py-3.5">
                                        <div class="font-semibold text-gray-800 text-sm">{{ $score->participant->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $score->participant->email }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 text-sm text-gray-600">{{ $score->event->event_name ?? 'N/A' }}</td>
                                    <td class="px-5 py-3.5">
                                        <span class="inline-flex px-2.5 py-1 text-xs font-medium rounded-full bg-indigo-50 text-indigo-700">
                                            {{ $score->criteria->category->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-sm text-gray-600">{{ $score->criteria->name }}</td>
                                    <td class="px-5 py-3.5 text-right">
                                        <span class="font-bold text-gray-800">{{ number_format($score->score, 1) }}</span>
                                        <span class="text-xs text-gray-400">/{{ $score->criteria->max_score ?? 100 }}</span>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        @php 
                                            $statusConfig = [
                                                'submitted' => ['bg-emerald-50', 'text-emerald-700', '✓'],
                                                'pending' => ['bg-amber-50', 'text-amber-700', '○'],
                                                'default' => ['bg-gray-100', 'text-gray-600', '•']
                                            ];
                                            $config = $statusConfig[$score->status] ?? $statusConfig['default'];
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 text-xs font-medium rounded-full {{ $config[0] }} {{ $config[1] }}">
                                            <span>{{ $config[2] }}</span> {{ ucfirst($score->status) }}
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-xs text-gray-400">{{ $score->updated_at->diffForHumans() }}</td>
                                    <td class="px-5 py-3.5 text-right space-x-3">
                                        <a href="{{ route('judge.scoring.edit', $score->id) }}" class="text-indigo-500 hover:text-indigo-700 text-sm font-medium">Edit</a>
                                        <form action="{{ route('judge.scoring.destroy', $score->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this score?')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 text-sm font-medium">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center">
                                            <div class="w-16 h-16 rounded-full bg-gray-100 flex items-center justify-center mb-3">
                                                <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                            </div>
                                            <h3 class="text-lg font-semibold text-gray-600 mb-1">No scores yet</h3>
                                            <p class="text-sm text-gray-400 mb-4">Get started by scoring participants in a category.</p>
                                            <a href="{{ route('judge.scoring.category') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold shadow-sm hover:bg-indigo-700">Start Scoring</a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="bg-gray-50/30 px-5 py-3 border-t border-gray-100">
                    {{ $scores->appends(request()->query())->links() }}
                </div>
            </div>

            {{-- Participant Totals Table - Collapsible style --}}
            @if(isset($participantTotals) && $participantTotals->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center flex-wrap gap-2">
                    <div>
                        <h3 class="font-bold text-gray-800">Participant Performance Summary</h3>
                        <p class="text-xs text-gray-400">{{ $participantTotals->count() }} participants · totals across all categories</p>
                    </div>
                    <a href="{{ route('judge.scoring.participants') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700 bg-indigo-50 px-3 py-1.5 rounded-xl transition">Full Table →</a>
                </div>
                <div class="overflow-x-auto -webkit-overflow-scrolling-touch">
                    <table class="min-w-[550px] w-full">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/40">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Participant</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Criteria</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">Total Score</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($participantTotals->take(10) as $total)
                                <tr class="hover:bg-gray-50/60 transition">
                                    <td class="px-5 py-3.5">
                                        <div class="font-semibold text-gray-800 text-sm">{{ $total['participant']->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $total['participant']->email }}</div>
                                    </td>
                                    <td class="px-5 py-3.5 text-sm text-gray-600">{{ $total['total_criteria_scored'] }} criteria</td>
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <span class="text-xl font-extrabold text-indigo-600">{{ number_format($total['grand_total_score'], 1) }}</span>
                                            <span class="text-xs font-bold text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-full">{{ $total['percentage'] ?? 0 }}%</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <a href="{{ route('judge.scoring.participants') }}?search={{ urlencode($total['participant']->name) }}" class="text-indigo-500 hover:text-indigo-700 text-sm font-medium">View Details →</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Quick Links - Modern card grid --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-gradient-to-br from-white to-gray-50/30 rounded-2xl border border-gray-100 p-5 shadow-sm hover:shadow-md transition-all">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        </div>
                        <h4 class="font-bold text-gray-800">Quick Actions</h4>
                    </div>
                    <div class="space-y-2">
                        <a href="{{ route('judge.scoring.category') }}" class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 hover:border-indigo-200 hover:shadow-sm transition-all group">
                            <span class="font-medium text-gray-700 group-hover:text-indigo-600">Score by Category</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ route('judge.manage_participants.index') }}" class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 hover:border-blue-200 hover:shadow-sm transition-all group">
                            <span class="font-medium text-gray-700 group-hover:text-blue-600">Manage Participants</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="{{ route('judge.category.index') }}" class="flex items-center justify-between p-3 bg-white rounded-xl border border-gray-100 hover:border-emerald-200 hover:shadow-sm transition-all group">
                            <span class="font-medium text-gray-700 group-hover:text-emerald-600">View Categories</span>
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                    </div>
                </div>
                
                {{-- Helper card for insights --}}
                <div class="bg-gradient-to-br from-white to-gray-50/30 rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-amber-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h4 class="font-bold text-gray-800">Insights</h4>
                    </div>
                    <p class="text-sm text-gray-600 mb-3">Overall completion rate</p>
                    <div class="w-full bg-gray-100 rounded-full h-2 mb-2"><div class="bg-indigo-500 h-2 rounded-full w-[68%]"></div></div>
                    <p class="text-xs text-gray-500">68% of criteria scored across all participants</p>
                </div>
                
                <div class="bg-gradient-to-br from-white to-gray-50/30 rounded-2xl border border-gray-100 p-5 shadow-sm">
                    <div class="flex items-center gap-3 mb-3">
                        <div class="w-10 h-10 rounded-xl bg-purple-100 flex items-center justify-center">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="font-bold text-gray-800">Reminder</h4>
                    </div>
                    <p class="text-sm text-gray-600 mb-2">Pending scores: <strong class="text-amber-600">{{ $stats['pending_scores'] ?? 0 }}</strong> submissions remaining.</p>
                    <a href="{{ route('judge.scoring.category') }}" class="text-indigo-600 text-sm font-medium inline-flex items-center gap-1 mt-1">Start scoring →</a>
                </div>
            </div>

        </div>
    </div>

    {{-- Toast Notifications - Modern design --}}
    @if(session('success'))
        <div class="fixed bottom-5 right-5 left-5 sm:left-auto sm:bottom-6 sm:right-6 z-50 bg-white border-l-4 border-emerald-500 shadow-xl rounded-xl px-4 py-3 flex items-center justify-between animate-in slide-in-from-right">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center"><svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg></div>
                <span class="text-sm font-medium text-gray-700">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 text-xl leading-4">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed bottom-5 right-5 left-5 sm:left-auto sm:bottom-6 sm:right-6 z-50 bg-white border-l-4 border-red-500 shadow-xl rounded-xl px-4 py-3 flex items-center justify-between animate-in slide-in-from-right">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center"><svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
                <span class="text-sm font-medium text-gray-700">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 text-xl leading-4">&times;</button>
        </div>
    @endif

    <script>
        setTimeout(() => {
            document.querySelectorAll('.fixed.bottom-5').forEach(el => {
                if(el) setTimeout(() => el.style.opacity = '0', 300);
                setTimeout(() => el?.remove(), 800);
            });
        }, 4500);
    </script>

    <style>
        @keyframes slide-in-from-right {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        .animate-in {
            animation: slide-in-from-right 0.3s ease-out;
        }
    </style>
@endsection
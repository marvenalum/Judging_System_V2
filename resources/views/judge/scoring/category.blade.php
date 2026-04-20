@extends('layouts.judge')

@section('header')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <h2 class="font-bold text-2xl text-gray-800 tracking-tight">
                Score Participants by Category
            </h2>
            <p class="text-sm text-gray-500 mt-0.5">Evaluate and assign scores to participants</p>
        </div>
        <div class="flex items-center gap-2 bg-gray-50 px-4 py-2 rounded-xl">
            <div class="flex items-center gap-3 text-xs md:text-sm">
                <span class="text-gray-500">{{ $judgeAssignmentsCount ?? 0 }} assignments</span>
                <span class="text-gray-300">|</span>
                <span class="text-gray-500">{{ $assignedEventsCount ?? 0 }} events</span>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="py-6 md:py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-7 md:space-y-9">

            {{-- Category Selector - Modern Card Design --}}
            @if($categories->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 bg-gradient-to-r from-indigo-50/30 to-transparent">
                    <div class="flex items-center gap-2">
                        <div class="w-8 h-8 rounded-xl bg-indigo-100 flex items-center justify-center">
                            <svg class="w-4 h-4 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                        <h3 class="font-semibold text-gray-800">Select Scoring Category</h3>
                    </div>
                </div>
                <div class="p-5">
                    <form method="GET" action="{{ route('judge.scoring.category') }}" class="flex flex-col sm:flex-row gap-4 items-end">
                        <div class="flex-1 w-full">
                            <label for="category_id" class="block text-xs font-semibold text-gray-500 uppercase tracking-wide mb-1.5">Category</label>
                            <select id="category_id" name="category_id" class="w-full rounded-xl border-gray-200 bg-gray-50 text-sm py-2.5 px-3 focus:border-indigo-400 focus:ring-1 focus:ring-indigo-200">
                                <option value="">Choose a category...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ ($selectedCategory?->id ?? old('category_id')) == $category->id ? 'selected' : '' }}>
                                        {{ $category->event->event_name ?? 'N/A' }} » {{ $category->name }}
                                        @if($category->percentage_weight) ({{ $category->percentage_weight }}%) @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <button type="submit" class="w-full sm:w-auto px-6 py-2.5 bg-indigo-600 text-white text-sm font-semibold rounded-xl shadow-sm hover:bg-indigo-700 active:scale-95 transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                Load Participants
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif

            {{-- Selected Category Stats - Modern Info Card --}}
            @if($selectedCategory)
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="p-5 bg-gradient-to-r from-indigo-50/20 to-blue-50/20">
                    <div class="flex flex-wrap items-center justify-between gap-4">
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="text-xl font-bold text-gray-800">{{ $selectedCategory->name }}</h3>
                                @if($selectedCategory->percentage_weight)
                                    <span class="inline-flex px-2.5 py-1 bg-blue-100 text-blue-700 text-xs font-semibold rounded-full">
                                        {{ $selectedCategory->percentage_weight }}% weight
                                    </span>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 mt-1">{{ $selectedCategory->event->event_name ?? 'N/A' }}</p>
                        </div>
                        <div class="flex flex-wrap items-center gap-5">
                            <div class="text-center min-w-[60px]">
                                <div class="text-2xl font-extrabold text-emerald-600">{{ $participants->count() }}</div>
                                <div class="text-[11px] text-gray-400 uppercase tracking-wider">Participants</div>
                            </div>
                            <div class="text-center min-w-[60px]">
                                <div class="text-2xl font-extrabold text-indigo-600">{{ $criteria->count() }}</div>
                                <div class="text-[11px] text-gray-400 uppercase tracking-wider">Criteria</div>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-28 bg-gray-200 rounded-full h-2">
                                    <div class="bg-emerald-500 h-2 rounded-full" style="width: {{ $scoringProgressPercent ?? 0 }}%"></div>
                                </div>
                                <span class="font-bold text-sm text-gray-700">{{ $scoringProgressPercent ?? 0 }}%</span>
                            </div>
                            @if($criteria->count() > 0)
                            <a href="{{ route('judge.scoring.bulk', $selectedCategory->id) }}" 
                               class="inline-flex items-center gap-1.5 px-4 py-2 bg-purple-600 text-white rounded-xl hover:bg-purple-700 text-sm font-medium shadow-sm transition active:scale-95">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                                Bulk Score All
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Participants Table - Modern Clean Design --}}
            @if($selectedCategory && $participants->isNotEmpty())
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex justify-between items-center flex-wrap gap-2">
                    <div>
                        <h3 class="font-bold text-gray-800">Scoring Progress</h3>
                        <p class="text-xs text-gray-400 mt-0.5">{{ $participants->count() }} participants to evaluate</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">Click "Score Now" to start</span>
                    </div>
                </div>
                <div class="overflow-x-auto -webkit-overflow-scrolling-touch">
                    <table class="min-w-[750px] w-full">
                        <thead>
                            <tr class="border-b border-gray-100 bg-gray-50/40">
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Participant</th>
                                <th class="px-5 py-3 text-left text-xs font-semibold text-gray-500">Progress</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">Total Score</th>
                                <th class="px-5 py-3 text-center text-xs font-semibold text-gray-500">Status</th>
                                <th class="px-5 py-3 text-right text-xs font-semibold text-gray-500">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($participants as $participant)
                                @php
                                    $progress = $participant->scoring_progress ?? ['percentage' => 0, 'total_score' => 0, 'completed' => 0, 'total' => $criteria->count()];
                                    $statusClass = $progress['percentage'] == 100 ? 'bg-emerald-100 text-emerald-700' : 
                                                  ($progress['percentage'] > 50 ? 'bg-amber-100 text-amber-700' : 'bg-gray-100 text-gray-600');
                                    $totalMax = $criteria->sum('max_score') ?? 100 * $criteria->count();
                                @endphp
                                <tr class="hover:bg-gray-50/60 transition-all duration-150">
                                    <td class="px-5 py-3.5">
                                        <div class="font-semibold text-gray-800 text-sm">{{ $participant->name }}</div>
                                        <div class="text-xs text-gray-400">{{ $participant->email }}</div>
                                    </td>
                                    <td class="px-5 py-3.5">
                                        <div class="flex items-center gap-3">
                                            <div class="w-24 bg-gray-100 rounded-full h-2">
                                                <div class="bg-indigo-500 h-2 rounded-full" style="width: {{ $progress['percentage'] }}%"></div>
                                            </div>
                                            <span class="text-sm font-medium text-gray-600">
                                                {{ $progress['completed'] }}/{{ $progress['total'] }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <div class="flex flex-col items-end">
                                            <span class="text-lg font-extrabold text-gray-800">{{ number_format($progress['total_score'] ?? 0, 1) }}</span>
                                            <span class="text-xs text-gray-400">/ {{ number_format($totalMax, 0) }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-3.5 text-center">
                                        <span class="inline-flex px-3 py-1 text-xs font-semibold rounded-full {{ $statusClass }}">
                                            {{ $progress['percentage'] }}%
                                        </span>
                                    </td>
                                    <td class="px-5 py-3.5 text-right">
                                        <a href="{{ route('judge.scoring.category.participant', ['category' => $selectedCategory->id, 'participant' => $participant->id]) }}"
                                           class="inline-flex items-center gap-1.5 px-4 py-2 border border-indigo-200 text-sm font-medium rounded-xl text-indigo-700 bg-indigo-50 hover:bg-indigo-100 transition-all active:scale-95">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.5h3m1.5-2.5h-5A2.5 2.5 0 013 19V8a2.5 2.5 0 012.5-2.5h5v3m2 0h3v3m0 0h3v3m-3-3h3m-3 3H9"></path>
                                            </svg>
                                            Score Now
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @elseif($selectedCategory && $participants->isEmpty())
            {{-- Empty State - No Participants --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                <div class="w-20 h-20 rounded-full bg-gray-100 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">No Participants Found</h3>
                <p class="text-sm text-gray-500 mb-6">No participants have eligible submissions for this category.</p>
                <a href="{{ route('judge.scoring.category') }}" class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-700 font-medium bg-indigo-50 px-4 py-2 rounded-xl transition">
                    ← Choose Another Category
                </a>
            </div>
            @elseif(!$selectedCategory)
            {{-- Empty State - No Category Selected --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-12 text-center">
                <div class="w-20 h-20 rounded-full bg-indigo-50 flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Select a Category to Begin</h3>
                <p class="text-sm text-gray-500 mb-6">Choose a category from the dropdown above to view participants and start scoring.</p>
            </div>
            @endif

            {{-- Quick Links Section - Modern Card Grid --}}
            @if(!$selectedCategory)
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                {{-- Card 1: Score by Category --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group">
                    <div class="p-6 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-indigo-50 flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                            <svg class="w-7 h-7 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1">Score by Category</h4>
                        <p class="text-xs text-gray-500 mb-4">Evaluate participants per category criteria</p>
                        @php $firstCategory = $categories->first(); @endphp
                        @if($firstCategory)
                            <a href="{{ route('judge.scoring.category') }}?category_id={{ $firstCategory->id }}" 
                               class="inline-flex items-center gap-1 text-indigo-600 hover:text-indigo-700 font-medium text-sm">
                                {{ $firstCategory->name }} →
                            </a>
                        @else
                            <span class="text-gray-400 text-sm">No categories available</span>
                        @endif
                    </div>
                </div>

                {{-- Card 2: All Scores --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group">
                    <div class="p-6 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                            <svg class="w-7 h-7 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m1 9h1"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1">All Scores</h4>
                        <p class="text-xs text-gray-500 mb-4">Review all your scores across all categories</p>
                        <a href="{{ route('judge.review-scores') }}" class="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700 font-medium text-sm">
                            View Scores →
                        </a>
                    </div>
                </div>

                {{-- Card 3: Participants Table --}}
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-md transition-all duration-200 overflow-hidden group">
                    <div class="p-6 text-center">
                        <div class="w-14 h-14 rounded-2xl bg-blue-50 flex items-center justify-center mx-auto mb-4 group-hover:scale-105 transition-transform">
                            <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                        </div>
                        <h4 class="font-bold text-gray-800 mb-1">Participants Table</h4>
                        <p class="text-xs text-gray-500 mb-4">Comprehensive table view with all scoring data</p>
                        <a href="{{ route('judge.scoring.participants') }}" class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 font-medium text-sm">
                            View Table →
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Toast Notifications - Modern Design --}}
    @if(session('success'))
        <div class="fixed bottom-5 right-5 left-5 sm:left-auto sm:bottom-6 sm:right-6 z-50 bg-white border-l-4 border-emerald-500 shadow-xl rounded-xl px-4 py-3 flex items-center justify-between animate-in slide-in-from-right">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <span class="text-sm font-medium text-gray-700">{{ session('success') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 text-xl leading-4">&times;</button>
        </div>
    @endif

    @if(session('error'))
        <div class="fixed bottom-5 right-5 left-5 sm:left-auto sm:bottom-6 sm:right-6 z-50 bg-white border-l-4 border-red-500 shadow-xl rounded-xl px-4 py-3 flex items-center justify-between animate-in slide-in-from-right">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-red-100 flex items-center justify-center">
                    <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <span class="text-sm font-medium text-gray-700">{{ session('error') }}</span>
            </div>
            <button onclick="this.parentElement.remove()" class="text-gray-400 hover:text-gray-600 text-xl leading-4">&times;</button>
        </div>
    @endif

    <script>
        // Auto-hide notifications
        setTimeout(() => {
            document.querySelectorAll('.fixed.bottom-5').forEach(el => {
                if(el) {
                    el.style.opacity = '0';
                    setTimeout(() => el.remove(), 300);
                }
            });
        }, 5000);

        // Auto-submit form when category changes (for better UX)
        const categorySelect = document.querySelector('select[name=category_id]');
        if (categorySelect) {
            categorySelect.addEventListener('change', function() {
                if (this.value) {
                    this.form.submit();
                }
            });
        }
    </script>

    <style>
        @keyframes slide-in-from-right {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @keyframes slide-in-from-top {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-in {
            animation: slide-in-from-right 0.3s ease-out;
        }
    </style>
@endsection
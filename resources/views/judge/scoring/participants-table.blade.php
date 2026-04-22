
@extends('layouts.judge')


@section( 'content')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h1 class="text-2xl font-bold">Participant Scores</h1>
                            <p class="mt-1 text-gray-600">View and edit scores for all participants across categories and criteria.</p>
                        </div>
                        <div class="flex gap-2">
                            <a href="{{ route('judge.scoring.category') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Score by Category
                            </a>
                            <a href="{{ route('judge.scores.by-category') }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Score Details
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
                        <form method="GET" action="{{ route('judge.scoring.participants') }}" class="flex flex-wrap gap-4 items-end">
                            <div class="flex-1 min-w-[200px]">
                                <x-input-label for="category_id" :value="__('Filter by Category')" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Categories</option>
                                    @if(isset($categories))
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }} ({{ $category->event->event_name ?? 'N/A' }})
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <x-input-label for="event_id" :value="__('Filter by Event')" />
                                <select id="event_id" name="event_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Events</option>
                                    @if(isset($events))
                                        @foreach($events as $event)
                                            <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                                                {{ $event->event_name }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="flex-1 min-w-[200px]">
                                <x-input-label for="status" :value="__('Filter by Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">All Statuses</option>
                                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="submitted" {{ request('status') == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                </select>
                            </div>
                            <div>
                                <x-primary-button type="submit">
                                    {{ __('Apply Filters') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </div>

                    <!-- Participants Scores Table -->
                    <div class="mt-8">
                        @if($participants->isNotEmpty())
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky left-0 bg-gray-50">
                                                Participant
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Event
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Category
                                            </th>
                                            @foreach($allCriteria as $criterion)
                                                <th class="px-3 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" title="{{ $criterion->name }} ({{ $criterion->max_score }} pts)">
                                                    {{ Str::limit($criterion->name, 10) }}
                                                </th>
                                            @endforeach
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Total
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider sticky right-0 bg-gray-50">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($participants as $participant)
                                            @foreach($participant->scoreData as $scoreRow)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white">
                                                        <div class="text-sm font-medium text-gray-900">{{ $participant->name }}</div>
                                                        <div class="text-xs text-gray-500">{{ $participant->email }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $scoreRow['event_name'] ?? 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        {{ $scoreRow['category_name'] ?? 'N/A' }}
                                                    </td>
                                                    @foreach($allCriteria as $criterion)
                                                        <td class="px-3 py-4 whitespace-nowrap text-center">
                                                            @php
                                                                $criterionScore = $scoreRow['criteria_scores'][$criterion->id] ?? null;
                                                            @endphp
                                                            @if($criterionScore)
                                                                <span class="inline-flex items-center justify-center min-w-[40px] px-2 py-1 text-sm font-semibold rounded 
                                                                    {{ $criterionScore['status'] === 'submitted' ? 'bg-green-100 text-green-800' : 
                                                                       ($criterionScore['status'] === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                                    {{ $criterionScore['score'] }}
                                                                </span>
                                                            @else
                                                                <span class="text-gray-400">-</span>
                                                            @endif
                                                        </td>
                                                    @endforeach
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="text-lg font-bold text-indigo-600">
                                                            {{ number_format($scoreRow['total_score'], 1) }}
                                                        </span>
                                                        <span class="text-xs text-gray-500">/ {{ $scoreRow['max_possible'] }}</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        @if($scoreRow['overall_status'] == 'submitted')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                                Submitted
                                                            </span>
                                                        @elseif($scoreRow['overall_status'] == 'draft')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                                Draft
                                                            </span>
                                                        @elseif($scoreRow['overall_status'] == 'pending')
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                                Pending
                                                            </span>
                                                        @else
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                                Not Started
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm sticky right-0 bg-white">
                                                        @if($scoreRow['category_id'])
                                                            <a href="{{ route('judge.scoring.category.participant', ['category' => $scoreRow['category_id'], 'participant' => $participant->id]) }}" 
                                                               class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                                Score
                                                            </a>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            <div class="mt-4">
                                {{ $participants->appends(request()->query())->links() }}
                            </div>
                        @else
                            <div class="text-center py-12">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No Participants Found</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    No participants with scores found. Start scoring participants in the Scoring section.
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('judge.scoring.category') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                                        Go to Scoring
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


<x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Score by Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Score Participants by Category</h1>
                    <p class="mb-4">Select a category to view and score participants based on their performance in that category.</p>

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

                    <!-- Category Selection -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('judge.scoring.category') }}" class="flex items-end gap-4">
                            <div class="flex-1">
                                <x-input-label for="category_id" :value="__('Select Category')" />
                                <select id="category_id" name="category_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" onchange="this.form.submit()">
                                    <option value="">-- Select a Category --</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $selectedCategory && $selectedCategory->id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }} ({{ $category->event->event_name ?? 'N/A' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>

                    @if($selectedCategory)
                        <!-- Category Details -->
                        <div class="bg-gray-50 p-4 rounded-lg mb-6">
                            <h3 class="font-semibold text-lg mb-2">Category Details</h3>
                            <div class="grid grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span class="font-medium">Category Name:</span> {{ $selectedCategory->name }}
                                </div>
                                <div>
                                    <span class="font-medium">Event:</span> {{ $selectedCategory->event->event_name ?? 'N/A' }}
                                </div>
                                <div>
                                    <span class="font-medium">Description:</span> {{ $selectedCategory->description ?? 'N/A' }}
                                </div>
                                <div>
                                    <span class="font-medium">Weight:</span> {{ $selectedCategory->percentage_weight ?? 'N/A' }}%
                                </div>
                            </div>
                        </div>

                        <!-- Criteria for this Category -->
                        @if($criteria->isNotEmpty())
                            <div class="mb-6">
                                <h3 class="font-semibold text-lg mb-2">Scoring Criteria</h3>
                                <div class="bg-white border rounded-lg overflow-hidden">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criterion</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Score</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Weight</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($criteria as $criterion)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">{{ $criterion->name }}</td>
                                                    <td class="px-6 py-4">{{ $criterion->description ?? 'N/A' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">{{ $criterion->max_score ?? 'N/A' }}</td>
                                                    <td class="px-6 py-4 whitespace-nowrap">{{ $criterion->percentage_weight ?? $criterion->weight ?? 'N/A' }}%</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-6">
                                No active criteria found for this category.
                            </div>
                        @endif

                        <!-- Participants List -->
                        @if($participants->isNotEmpty())
                            <div class="mb-6">
                                <h3 class="font-semibold text-lg mb-2">Participants</h3>
                                <p class="text-sm text-gray-600 mb-4">Click on a participant to score them for each criterion in this category.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($participants as $participant)
                                        <div class="border rounded-lg p-4 hover:shadow-md transition-shadow">
                                            <div class="flex items-center justify-between mb-2">
                                                <h4 class="font-semibold text-lg">{{ $participant->name }}</h4>
                                            </div>
                                            <p class="text-sm text-gray-600 mb-3">{{ $participant->email }}</p>
                                            
                                            <!-- Current Scores Summary -->
                                            @php
                                                $participantScores = $participant->receivedScores;
                                                $totalScore = $participantScores->sum('score');
                                                $maxPossible = $criteria->sum('max_score') ?: 1;
                                            @endphp
                                            <div class="text-sm mb-3">
                                                <span class="font-medium">Current Total:</span> 
                                                <span class="{{ $totalScore > 0 ? 'text-green-600 font-semibold' : 'text-gray-500' }}">
                                                    {{ number_format($totalScore, 2) }} / {{ $maxPossible }}
                                                </span>
                                            </div>
                                            
                                            <!-- Score Status -->
                                            <div class="flex flex-wrap gap-1 mb-3">
                                                @foreach($criteria as $criterion)
                                                    @php
                                                        $score = $participantScores->firstWhere('criteria_id', $criterion->id);
                                                    @endphp
                                                    @if($score)
                                                        <span class="px-2 py-1 text-xs rounded {{ $score->status === 'submitted' ? 'bg-green-100 text-green-800' : ($score->status === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                            {{ $criterion->name }}: {{ $score->score }}
                                                        </span>
                                                    @else
                                                        <span class="px-2 py-1 text-xs rounded bg-red-100 text-red-800">
                                                            {{ $criterion->name }}: --
                                                        </span>
                                                    @endif
                                                @endforeach
                                            </div>
                                            
                                            <!-- Score Button -->
                                            <a href="{{ route('judge.scoring.category.participant', ['category' => $selectedCategory->id, 'participant' => $participant->id]) }}" 
                                               class="block text-center bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded">
                                                Score Participant
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                                No participants found for this category's event. Please ensure participants have submitted their work.
                            </div>
                        @endif
                    @else
                        <!-- No Category Selected - Show Available Categories -->
                        @if($categories->isNotEmpty())
                            <div class="mt-8">
                                <h3 class="font-semibold text-lg mb-4">Available Categories</h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($categories as $category)
                                        <a href="{{ route('judge.scoring.category', ['category_id' => $category->id]) }}" 
                                           class="border rounded-lg p-4 hover:shadow-md transition-shadow block">
                                            <h4 class="font-semibold text-lg">{{ $category->name }}</h4>
                                            <p class="text-sm text-gray-600 mt-1">{{ $category->event->event_name ?? 'N/A' }}</p>
                                            @if($category->description)
                                                <p class="text-sm text-gray-500 mt-2">{{ Str::limit($category->description, 100) }}</p>
                                            @endif
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @else
                            <div class="mt-8 text-center py-8">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No Categories Found</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    No active categories available for your assigned events. Please contact the administrator.
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-judge-layout>

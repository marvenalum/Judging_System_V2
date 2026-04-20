
@extends('layouts.judge')


@section( 'content')
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Score {{ $participant->name }} - {{ $category->name }}
        </h2>
        <div class="ml-auto text-sm text-gray-500">
            {{ $category->event->event_name ?? 'N/A' }} » {{ $category->name }}
            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                {{ $criteria->count() }} criteria
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900">
                                {{ $participant->name }}
                                <span class="text-sm font-normal text-gray-500 ml-2">({{ $participant->email }})</span>
                            </h1>
                            <p class="text-sm text-gray-600 mt-1">
                                Event: {{ $category->event->event_name ?? 'N/A' }} | Category: <span class="font-semibold">{{ $category->name }}</span>
                            </p>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('judge.scoring.category', ['category_id' => $category->id]) }}" 
                               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm font-medium">
                                ← Back to Category
                            </a>
                            <a href="{{ route('judge.review-scores') }}" 
                               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                                All Scores
                            </a>
                        </div>
                    </div>

                    @if($criteria->isEmpty())
                        <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                            </svg>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No Scoring Criteria</h3>
                            <p class="text-gray-500 mb-6">
                                No active criteria available for this category. Please contact the administrator.
                            </p>
                            <a href="{{ route('judge.scoring.category', ['category_id' => $category->id]) }}" 
                               class="text-indigo-600 hover:text-indigo-800 font-medium">
                                ← Return to Category
                            </a>
                        </div>
                    @else
                        <form method="POST" action="{{ route('judge.scoring.category.participant.store', ['category' => $category->id, 'participant' => $participant->id]) }}" id="scoringForm">
                            @csrf

                            <!-- Category & Scoring Instructions -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                                <h3 class="text-lg font-semibold text-blue-900 mb-4">Scoring Instructions</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 text-sm">
                                    <div>
                                        <strong>Category:</strong> {{ $category->name }}<br>
                                        <strong>Event:</strong> {{ $category->event->event_name ?? 'N/A' }}<br>
                                        <strong>Weight:</strong> {{ $category->percentage_weight ?? 'N/A' }}%
                                    </div>
                                    <div>
                                        <strong>Criteria Count:</strong> {{ $criteria->count() }}<br>
                                        <strong>Max Total Score:</strong> {{ $criteria->sum('max_score') ?? 0 }}
                                    </div>
                                    <div>
                                        <strong>Status:</strong> 
                                        <select name="status" class="ml-2 p-1 border border-blue-300 rounded text-sm font-medium" required>
                                            <option value="draft">💾 Save as Draft</option>
                                            <option value="submitted">✅ Submit Final Scores</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- Current Scores Summary (if any) -->
                            @php
                                $totalCurrent = 0;
                                $maxPossible = 0;
                                foreach($criteria as $criterion) {
                                    $existing = $existingScores->get($criterion->id);
                                    $score = $existing?->score ?? 0;
                                    $totalCurrent += $score;
                                    $maxPossible += $criterion->max_score ?? 100;
                                }
                            @endphp
                            @if($totalCurrent > 0)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-8">
                                <h4 class="font-semibold text-green-900 mb-2">Current Scores Summary</h4>
                                <div class="flex items-center justify-between">
                                    <span>Total: {{ number_format($totalCurrent, 1) }} / {{ $maxPossible }}</span>
                                    <span class="font-mono text-sm text-green-800">({{ number_format(($totalCurrent/$maxPossible)*100, 1) }}%)</span>
                                </div>
                            </div>
                            @endif

                            <!-- Criteria Scoring Table -->
                            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg mb-8">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criterion</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                            <th class="px-8 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Current</th>
                                            <th class="px-8 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Score</th>
                                            <th class="px-20 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($criteria as $criterion)
                                            @php
                                                $existing = $existingScores->get($criterion->id);
                                                $currentScore = $existing?->score ?? null;
                                                $currentStatus = $existing?->status ?? 'none';
                                            @endphp
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                                    {{ $criterion->name }}
                                                    <div class="text-xs text-gray-500">
                                                        Max: {{ $criterion->max_score ?? 100 }} 
                                                        @if($criterion->percentage_weight)
                                                            | {{ $criterion->percentage_weight }}%
                                                        @endif
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4">
                                                    {{ $criterion->description ?? 'No description' }}
                                                </td>
                                                <td class="px-8 py-4 text-center text-sm">
                                                    @if($currentScore !== null)
                                                        <span class="inline-flex px-3 py-1 rounded-full text-xs font-semibold 
                                                            {{ $currentStatus === 'submitted' ? 'bg-green-100 text-green-800' : 
                                                               ($currentStatus === 'draft' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-800') }}">
                                                            {{ $currentScore }}
                                                            @if($currentStatus === 'submitted')
                                                                <span class="ml-1">(Submitted)</span>
                                                            @endif
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-8 py-4">
                                                    <input type="number" 
                                                           name="score_{{ $criterion->id }}" 
                                                           value="{{ old('score_' . $criterion->id, $currentScore ?? '') }}" 
                                                           min="0" 
                                                           max="{{ $criterion->max_score ?? 100 }}" 
                                                           step="0.1"
                                                           class="w-20 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-center font-mono"
                                                           placeholder="0" 
                                                           required>
                                                    <div class="text-xs text-gray-500 mt-1">/ {{ $criterion->max_score ?? 100 }}</div>
                                                    @error('score_' . $criterion->id)
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </td>
                                                <td class="px-6 py-4">
                                                    <textarea name="comment_{{ $criterion->id }}" 
                                                              rows="2" 
                                                              class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                              placeholder="Optional comments on performance...">{{ old('comment_' . $criterion->id, $existing?->comments ?? '') }}</textarea>
                                                    @error('comment_' . $criterion->id)
                                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex justify-end gap-3 pt-4 border-t">
                                <a href="{{ route('judge.scoring.category', ['category_id' => $category->id]) }}" 
                                   class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-medium">
                                    Cancel
                                </a>
                                <x-primary-button type="submit">Save All Scores</x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        // Form validation
        document.getElementById('scoringForm').addEventListener('submit', function(e) {
            let scoreInputs = document.querySelectorAll('input[name^=score_]');
            let valid = true;
            let maxError = 0;
            
            scoreInputs.forEach(function(input) {
                let value = parseFloat(input.value);
                let max = parseFloat(input.getAttribute('max'));
                if (isNaN(value) || value < 0 || value > max) {
                    valid = false;
                    input.classList.add('border-red-500');
                    if (value > max) maxError = max;
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            
            if (!valid) {
                e.preventDefault();
                alert(`Please correct score values (0 to ${maxError}). All fields are required.`);
            }
        });

        // Live validation
        document.querySelectorAll('input[name^=score_]').forEach(function(input) {
            input.addEventListener('input', function() {
                let value = parseFloat(this.value);
                let max = parseFloat(this.getAttribute('max'));
                if (!isNaN(value) && value >= 0 && value <= max) {
                    this.classList.remove('border-red-500');
                    this.classList.add('border-green-300');
                } else {
                    this.classList.remove('border-green-300');
                    this.classList.add('border-red-500');
                }
            });
        });

        // Auto-save draft on blur (optional enhancement)
        // Could add AJAX autosave here
    </script>
 @endsection


<x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Score {{ $criteria->name ?? 'Criteria' }}
        </h2>
        <div class="ml-auto text-sm text-gray-500">
            {{ $criteria->event->event_name ?? 'N/A' }} » {{ $criteria->category->name ?? 'N/A' }}
            <span class="ml-2 px-2 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">
                Max: {{ $criteria->max_score ?? 0 }}
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

                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-6">
                            <h1 class="text-2xl font-bold text-gray-900">
                                Score Participants: {{ $participants->count() }} found
                            </h1>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('judge.review-scores') }}" 
                                   class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm font-medium">
                                    ← Back to Scores
                                </a>
                                <a href="{{ route('judge.criteria.index') }}" 
                                   class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                                    View All Criteria
                                </a>
                            </div>
                        </div>

                        @if($participants->isEmpty())
                            <div class="text-center py-12 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                <h3 class="text-lg font-medium text-gray-900 mb-2">No eligible participants</h3>
                                <p class="text-gray-500 mb-6">
                                    No participants have approved submissions for this event. 
                                    Scores will appear here once participants submit and admins approve.
                                </p>
                            </div>
                        @else
                            <form method="POST" action="{{ route('judge.criteria.storeScore', $criteria->id) }}" id="scoringForm">
                                @csrf
                                
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-8">
                                    <h3 class="text-lg font-semibold text-blue-900 mb-3">Scoring Instructions</h3>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-blue-800">
                                        <div>
                                            <strong>Criterion:</strong> {{ $criteria->name }} ({{ $criteria->max_score }} pts)<br>
                                            <strong>Event:</strong> {{ $criteria->event->event_name ?? 'N/A' }}<br>
                                            <strong>Category:</strong> {{ $criteria->category->name ?? 'N/A' }}
                                        </div>
                                        <div>
                                            <strong>Status:</strong> 
                                            <select name="status" class="ml-2 p-1 border border-blue-300 rounded text-xs" required>
                                                <option value="draft">Save as Draft</option>
                                                <option value="submitted">Submit Final Score</option>
                                            </select>
                                            <p class="text-xs mt-1">All scores below will use this status</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 rounded-lg mb-6">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Current Score</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score (0-{{ $criteria->max_score }})</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Comments</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($participants as $participant)
                                                @php
                                                    $existingScore = $participant->receivedScores->where('criteria_id', $criteria->id)->first();
                                                @endphp
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">{{ $participant->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ $participant->email }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        @if($existingScore)
                                                            <span class="inline-flex px-3 py-1 rounded-full text-sm font-medium
                                                                {{ $existingScore->status === 'submitted' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">
                                                                {{ $existingScore->score }} / {{ $criteria->max_score }}
                                                                @if($existingScore->status === 'submitted')
                                                                    <span class="ml-1 text-xs">(Submitted)</span>
                                                                @endif
                                                            </span>
                                                        @else
                                                            <span class="text-gray-400 text-sm">No score yet</span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <input type="number" 
                                                               name="participant_id" 
                                                               value="{{ $participant->id }}" 
                                                               hidden>
                                                        <input type="number" 
                                                               name="score" 
                                                               value="{{ $existingScore->score ?? '' }}" 
                                                               min="0" 
                                                               max="{{ $criteria->max_score }}" 
                                                               step="0.1"
                                                               class="w-24 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                               placeholder="0" 
                                                               required>
                                                        <div class="text-xs text-gray-500 mt-1">/ {{ $criteria->max_score }}</div>
                                                        @error('score')
                                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                                        @enderror
                                                    </td>
                                                    <td class="px-6 py-4">
                                                        <textarea name="comments" 
                                                                  rows="2" 
                                                                  class="w-full p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                                                  placeholder="Optional comments...">{{ $existingScore->comments ?? '' }}</textarea>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="flex justify-end gap-3 pt-4">
                                    <a href="{{ route('judge.review-scores') }}" 
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
    </div>

    <script>
        // Basic form validation
        document.getElementById('scoringForm').addEventListener('submit', function(e) {
            let scores = document.querySelectorAll('input[name=\"score\"]');
            let valid = true;
            scores.forEach(function(scoreInput) {
                let value = parseFloat(scoreInput.value);
                let max = parseFloat(scoreInput.getAttribute('max'));
                if (isNaN(value) || value < 0 || value > max) {
                    valid = false;
                    scoreInput.classList.add('border-red-500');
                } else {
                    scoreInput.classList.remove('border-red-500');
                }
            });
            if (!valid) {
                e.preventDefault();
                alert('Please correct score values (0 to ' + max + ')');
            }
        });

        // Live score validation
        document.querySelectorAll('input[name=\"score\"]').forEach(function(input) {
            input.addEventListener('input', function() {
                let value = parseFloat(this.value);
                let max = parseFloat(this.getAttribute('max'));
                if (!isNaN(value) && value >= 0 && value <= max) {
                    this.classList.remove('border-red-500');
                } else {
                    this.classList.add('border-red-500');
                }
            });
        });
    </script>
</x-judge-layout>


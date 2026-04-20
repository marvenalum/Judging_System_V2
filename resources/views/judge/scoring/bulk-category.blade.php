@extends('layouts.judge')

@section('content')
<x-slot name="header">
    <div class="flex justify-between items-center">
        <div>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Bulk Score {{ $category->name }}
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                Event: {{ $category->event->event_name ?? 'N/A' }} | 
                {{ $criteria->count() }} criteria | 
                {{ $participants->count() }} participants
                @if($category->percentage_weight)
                    | Weight: {{ $category->percentage_weight }}%
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('judge.scoring.category', ['category_id' => $category->id]) }}" 
               class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 text-sm font-medium">
                ← Back to Category
            </a>
            <a href="{{ route('judge.scoring.index') }}" 
               class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 text-sm font-medium">
                All Scores
            </a>
        </div>
    </div>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        @if(session('success'))
            <div class="mb-8 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-8 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        @if($criteria->isEmpty())
            <div class="text-center py-16 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Criteria Available</h3>
                <p class="text-gray-500 mb-6">No active criteria for this category. Contact administrator.</p>
                <a href="{{ route('judge.scoring.category') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                    ← Choose Another Category
                </a>
            </div>
        @elseif($participants->isEmpty())
            <div class="text-center py-16 bg-gray-50 rounded-lg border-2 border-dashed border-gray-300">
                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                </svg>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Participants</h3>
                <p class="text-gray-500 mb-6">No participants with reviewed submissions for this category.</p>
                <a href="{{ route('judge.scoring.category') }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                    ← Back to Categories
                </a>
            </div>
        @else
            <div class="bg-white shadow-sm sm:rounded-lg border border-gray-200 overflow-hidden">
                <!-- Bulk Status Selector -->
                <div class="bg-blue-50 border-b border-blue-200 p-6">
                    <h3 class="text-lg font-semibold text-blue-900 mb-4">Bulk Action</h3>
                    <div class="flex items-center gap-4">
                        <label class="text-sm font-medium text-gray-700">
                            Set All Scores Status:
                            <select name="overall_status" class="ml-2 p-2 border border-blue-300 rounded-md text-sm font-medium bg-white" required>
                                <option value="draft">💾 Save as Draft</option>
                                <option value="submitted">✅ Submit All Final Scores</option>
                            </select>
                        </label>
                        <div class="text-sm text-gray-600">
                            <span class="font-semibold">{{ $participants->count() }}</span> participants × {{ $criteria->count() }} criteria = 
                            <span class="font-semibold text-blue-600">{{ $participants->count() * $criteria->count() }}</span> scores
                        </div>
                    </div>
                </div>

                <!-- Bulk Scoring Table -->
                <form method="POST" action="{{ route('judge.scoring.bulk.store', $category->id) }}" id="bulkScoringForm">
                    @csrf
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50 sticky top-0 z-10">
                                <tr>
                                    <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Participant</th>
                                    @foreach($criteria as $criterion)
                                        <th class="px-4 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider" colspan="2">
                                            {{ $criterion->name }}<br>
                                            <span class="text-gray-400 text-xs">/ {{ $criterion->max_score ?? 100 }}</span>
                                        </th>
                                    @endforeach
                                    <th class="px-6 py-4 text-right text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($participants as $participant)
                                    <tr class="hover:bg-gray-50">
                                        <!-- Participant Name -->
                                        <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                            {{ $participant->name }}
                                            <div class="text-sm text-gray-500">{{ $participant->email ?? 'N/A' }}</div>
                                        </td>
                                        
                                        <!-- Criteria Columns -->
                                        @foreach($criteria as $criterion)
                                            @php
                                                $scoreKey = 'p' . $participant->id . '_c' . $criterion->id . '_score';
                                                $commentKey = 'p' . $participant->id . '_c' . $criterion->id . '_comment';
                                                $existingScore = $existingScores->get($participant->id . '_' . $criterion->id);
                                                $currentScore = $existingScore?->score ?? '';
                                                $currentStatus = $existingScore?->status ?? 'none';
                                            @endphp
                                            
                                            <td class="px-2 py-4 text-center">
                                                <input type="number" 
                                                       name="{{ $scoreKey }}" 
                                                       value="{{ old($scoreKey, $currentScore) }}" 
                                                       min="0" 
                                                       max="{{ $criterion->max_score ?? 100 }}" 
                                                       step="0.1"
                                                       class="score-input w-20 p-2 border border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 text-center font-mono text-sm {{ $currentStatus === 'submitted' ? 'bg-green-50 border-green-300' : ($currentStatus === 'draft' ? 'bg-yellow-50 border-yellow-300' : '') }}"
                                                       placeholder="0" 
                                                       required>
                                            </td>
                                            <td class="px-1 py-4">
                                                <textarea name="{{ $commentKey }}" 
                                                          rows="1" 
                                                          class="comment-input w-full p-1.5 border border-gray-200 rounded text-xs focus:ring-indigo-500 focus:border-indigo-500 resize-none"
                                                          placeholder="Comments...">{{ old($commentKey, $existingScore?->comments ?? '') }}</textarea>
                                            </td>
                                        @endforeach
                                        
                                        <!-- Actions -->
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('judge.scoring.score-participant', [$category->id, $participant->id]) }}" 
                                               class="text-indigo-600 hover:text-indigo-900 mr-2">
                                                Individual
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="bg-gray-50 px-6 py-4 border-t flex justify-between items-center">
                        <div class="text-sm text-gray-600">
                            ⚠️ Submitting will update/save <strong>{{ $participants->count() * $criteria->count() }}</strong> scores
                        </div>
                        <div class="flex gap-3">
                            <a href="{{ route('judge.scoring.category', ['category_id' => $category->id]) }}" 
                               class="px-6 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400 font-medium">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-8 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 font-semibold transition-all"
                                    onclick="return confirmBulkSubmit()">
                                Save All Scores
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const scoreInputs = document.querySelectorAll('.score-input');
    
    // Live validation for score inputs
    scoreInputs.forEach(input => {
        input.addEventListener('input', function() {
            const value = parseFloat(this.value);
            const max = parseFloat(this.getAttribute('max'));
            
            if (isNaN(value) || value < 0) {
                this.classList.add('border-red-500', 'bg-red-50');
                this.classList.remove('border-green-300', 'bg-green-50');
            } else if (value > max) {
                this.classList.add('border-red-500', 'bg-red-50');
                this.classList.remove('border-green-300', 'bg-green-50');
            } else {
                this.classList.remove('border-red-500', 'bg-red-50');
                this.classList.add('border-green-300', 'bg-green-50');
            }
        });
    });
    
    // Form submit validation
    document.getElementById('bulkScoringForm').addEventListener('submit', function(e) {
        const invalidInputs = document.querySelectorAll('.score-input:not(:valid)');
        if (invalidInputs.length > 0) {
            e.preventDefault();
            alert(`Please correct ${invalidInputs.length} invalid score(s) (must be 0-${Math.max(...Array.from(scoreInputs).map(i => parseFloat(i.max)))})`);
            invalidInputs[0].focus();
            return false;
        }
    });
});

function confirmBulkSubmit() {
    const numScores = {{ $participants->count() * $criteria->count() }};
    const status = document.querySelector('select[name=overall_status]').value;
    if (status === 'submitted' && numScores > 10) {
        return confirm(`Submit ${numScores} scores as FINAL? This cannot be undone without admin intervention.`);
    }
    return true;
}
</script>
@endsection


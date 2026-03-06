<x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Score Participant') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold">Score Participant: {{ $participant->name }}</h1>
                        <a href="{{ route('judge.scoring.category', ['category_id' => $category->id]) }}" class="text-gray-600 hover:text-gray-900">
                            ← Back to Category
                        </a>
                    </div>

                    <!-- Participant & Category Details -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="font-semibold text-lg mb-2">Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium">Participant:</span> {{ $participant->name }}
                            </div>
                            <div>
                                <span class="font-medium">Email:</span> {{ $participant->email }}
                            </div>
                            <div>
                                <span class="font-medium">Category:</span> {{ $category->name }}
                            </div>
                            <div>
                                <span class="font-medium">Event:</span> {{ $category->event->event_name ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    @if($criteria->isEmpty())
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                            No active criteria found for this category. Please contact the administrator.
                        </div>
                    @else
                        <form method="POST" action="{{ route('judge.scoring.category.participant.store', ['category' => $category->id, 'participant' => $participant->id]) }}">
                            @csrf

                            <!-- Criteria Scores -->
                            <div class="mb-6">
                                <h3 class="font-semibold text-lg mb-4">Score each criterion</h3>
                                
                                @foreach($criteria as $criterion)
                                    <div class="border rounded-lg p-4 mb-4">
                                        <div class="flex items-center justify-between mb-2">
                                            <h4 class="font-semibold">{{ $criterion->name }}</h4>
                                            <span class="text-sm text-gray-500">Max: {{ $criterion->max_score ?? 100 }}</span>
                                        </div>
                                        
                                        @if($criterion->description)
                                            <p class="text-sm text-gray-600 mb-3">{{ $criterion->description }}</p>
                                        @endif
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <x-input-label for="score_{{ $criterion->id }}" :value="__('Score')" />
                                                @php
                                                    $existingScore = $existingScores->get($criterion->id);
                                                    $defaultScore = $existingScore ? $existingScore->score : '';
                                                @endphp
                                                <x-text-input 
                                                    id="score_{{ $criterion->id }}" 
                                                    name="score_{{ $criterion->id }}" 
                                                    type="number" 
                                                    step="0.01" 
                                                    min="0" 
                                                    max="{{ $criterion->max_score ?? 100 }}" 
                                                    class="mt-1 block w-full" 
                                                    required 
                                                    value="{{ old('score_' . $criterion->id, $defaultScore) }}"
                                                    placeholder="Enter score between 0 and {{ $criterion->max_score ?? 100 }}" 
                                                />
                                                <x-input-error :messages="$errors->get('score_' . $criterion->id)" class="mt-2" />
                                            </div>
                                            
                                            <div>
                                                <x-input-label for="comment_{{ $criterion->id }}" :value="__('Comment (Optional)')" />
                                                @php
                                                    $defaultComment = $existingScore ? $existingScore->comments : '';
                                                @endphp
                                                <textarea 
                                                    id="comment_{{ $criterion->id }}" 
                                                    name="comment_{{ $criterion->id }}" 
                                                    rows="2" 
                                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                    placeholder="Enter comment..."
                                                >{{ old('comment_' . $criterion->id, $defaultComment) }}</textarea>
                                                <x-input-error :messages="$errors->get('comment_' . $criterion->id)" class="mt-2" />
                                            </div>
                                        </div>
                                        
                                        @if($existingScore)
                                            <div class="mt-2">
                                                <span class="text-xs text-gray-500">
                                                    Current status: 
                                                    @if($existingScore->status == 'submitted')
                                                        <span class="text-green-600 font-semibold">Submitted</span>
                                                    @elseif($existingScore->status == 'draft')
                                                        <span class="text-yellow-600 font-semibold">Draft</span>
                                                    @else
                                                        <span class="text-gray-600">{{ ucfirst($existingScore->status) }}</span>
                                                    @endif
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <!-- Status Selection -->
                            <div class="mb-6">
                                <x-input-label for="status" :value="__('Score Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="draft">Draft</option>
                                    <option value="pending">Pending</option>
                                    <option value="submitted">Submitted</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                                <p class="text-sm text-gray-500 mt-1">
                                    Choose "Draft" to save without submitting, "Pending" for review, or "Submitted" to finalize.
                                </p>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('judge.scoring.category', ['category_id' => $category->id]) }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                    Cancel
                                </a>
                                <x-primary-button type="submit">
                                    {{ __('Save Scores') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-judge-layout>

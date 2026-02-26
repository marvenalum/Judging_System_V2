<x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Enter Score') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold">Enter Score for: {{ $criteria->name }}</h1>
                        <a href="{{ route('judge.review-scores') }}" class="text-gray-600 hover:text-gray-900">
                            ← Back to Scores
                        </a>
                    </div>

                    <!-- Criteria Details -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <h3 class="font-semibold text-lg mb-2">Criteria Details</h3>
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium">Event:</span> {{ $criteria->event->name ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Category:</span> {{ $criteria->category->name ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Description:</span> {{ $criteria->description ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Max Score:</span> {{ $criteria->max_score ?? 'N/A' }}
                            </div>
                            <div>
                                <span class="font-medium">Weight:</span> {{ $criteria->weight ?? 'N/A' }}%
                            </div>
                        </div>
                    </div>

                    @if($participants->isEmpty())
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded">
                            No participants found for this event. Please ensure participants have submitted their work.
                        </div>
                    @else
                        <form method="POST" action="{{ route('judge.criteria.storeScore', $criteria) }}">
                            @csrf

                            <!-- Participant Selection -->
                            <div class="mb-4">
                                <x-input-label for="participant_id" :value="__('Select Participant')" />
                                <select id="participant_id" name="participant_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="">-- Select Participant --</option>
                                    @foreach($participants as $participant)
                                        <option value="{{ $participant->id }}">{{ $participant->name }} ({{ $participant->email }})</option>
                                    @endforeach
                                </select>
                                <x-input-error :messages="$errors->get('participant_id')" class="mt-2" />
                            </div>

                            <!-- Score Input -->
                            <div class="mb-4">
                                <x-input-label for="score" :value="__('Score')" />
                                <x-text-input id="score" name="score" type="number" step="0.01" min="0" max="{{ $criteria->max_score ?? 100 }}" class="mt-1 block w-full" required placeholder="Enter score between 0 and {{ $criteria->max_score ?? 100 }}" />
                                <x-input-error :messages="$errors->get('score')" class="mt-2" />
                                <p class="text-sm text-gray-500 mt-1">Maximum score: {{ $criteria->max_score ?? 100 }}</p>
                            </div>

                            <!-- Comments -->
                            <div class="mb-4">
                                <x-input-label for="comments" :value="__('Comments (Optional)')" />
                                <textarea id="comments" name="comments" rows="4" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter any comments about this score..."></textarea>
                                <x-input-error :messages="$errors->get('comments')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div class="mb-4">
                                <x-input-label for="status" :value="__('Status')" />
                                <select id="status" name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                                    <option value="draft">Draft</option>
                                    <option value="pending">Pending</option>
                                    <option value="submitted">Submitted</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>

                            <!-- Submit Buttons -->
                            <div class="flex items-center justify-end mt-6">
                                <a href="{{ route('judge.review-scores') }}" class="text-gray-600 hover:text-gray-900 mr-4">
                                    Cancel
                                </a>
                                <x-primary-button type="submit">
                                    {{ __('Save Score') }}
                                </x-primary-button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-judge-layout>

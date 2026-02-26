 <x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Score') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="flex items-center justify-between mb-6">
                        <h1 class="text-2xl font-bold">Edit Score</h1>
                        <a href="{{ route('judge.review-scores') }}" class="text-gray-600 hover:text-gray-900">
                            ← Back to Scores
                        </a>
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

                    <!-- Score Details -->
                    @if($score)
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <h3 class="text-lg font-semibold mb-3">Score Details</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <span class="text-gray-500 text-sm">Participant:</span>
                                <p class="font-medium">{{ $score->participant->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 text-sm">Event:</span>
                                <p class="font-medium">{{ $score->event->name ?? 'N/A' }}</p>
                            </div>
                            <div>
                                <span class="text-gray-500 text-sm">Criteria:</span>
                                <p class="font-medium">{{ $score->criteria->name ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Score Edit Form -->
                    <form method="POST" action="{{ route('judge.scoring.update', $score->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="score" class="block text-sm font-medium text-gray-700 mb-2">
                                Score <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="score" 
                                id="score" 
                                value="{{ old('score', $score->score) }}"
                                min="0" 
                                max="{{ $score->criteria->max_score ?? 100 }}"
                                step="0.01"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                            @error('score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-1 text-sm text-gray-500">
                                Maximum score: {{ $score->criteria->max_score ?? 100 }}
                            </p>
                        </div>

                        <div class="mb-4">
                            <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">
                                Comments
                            </label>
                            <textarea 
                                name="comments" 
                                id="comments" 
                                rows="4"
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                placeholder="Enter your comments about this score..."
                            >{{ old('comments', $score->comments) }}</textarea>
                            @error('comments')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                                Status <span class="text-red-500">*</span>
                            </label>
                            <select 
                                name="status" 
                                id="status" 
                                class="w-full border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                required
                            >
                                <option value="draft" {{ old('status', $score->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="pending" {{ old('status', $score->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="submitted" {{ old('status', $score->status) == 'submitted' ? 'selected' : '' }}>Submitted</option>
                            </select>
                            @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end">
                            <a href="{{ route('judge.review-scores') }}" class="mr-4 text-gray-600 hover:text-gray-900">
                                Cancel
                            </a>
                            <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                Update Score
                            </button>
                        </div>
                    </form>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 p-4 rounded">
                            <p>No score record found. Please select a valid score to edit.</p>
                            <a href="{{ route('judge.review-scores') }}" class="mt-2 inline-block text-indigo-600 hover:text-indigo-800">
                                ← Back to Scores
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-judge-layout>

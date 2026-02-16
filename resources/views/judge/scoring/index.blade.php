 <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Score') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                @include('admin.sidebar.judge')

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">Edit Score</h1>
                        
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

                        <div class="mt-6">
                            <!-- Score Details -->
                            <div class="bg-gray-50 p-4 rounded-lg mb-6">
                                <h3 class="text-lg font-semibold mb-3">Score Details</h3>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <span class="text-gray-600">Judging ID:</span>
                                        <span class="font-medium ml-2">{{ $score->judge->name ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Criteria:</span>
                                        <span class="font-medium ml-2">{{ $score->criteria->name ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Score Given:</span>
                                        <span class="font-medium ml-2">{{ $score->score ?? 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Maximum Score:</span>
                                        <span class="font-medium ml-2">100</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Date Submitted:</span>
                                        <span class="font-medium ml-2">{{ $score->created_at ? $score->created_at->format('Y-m-d H:i:s') : 'N/A' }}</span>
                                    </div>
                                    <div>
                                        <span class="text-gray-600">Current Status:</span>
                                        <span class="font-medium ml-2">{{ ucfirst($score->status) }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Edit Form -->
                            <form action="{{ route('judge.scoring.update', $score->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="mb-4">
                                    <label for="score" class="block text-sm font-medium text-gray-700 mb-2">Score</label>
                                    <input type="number" name="score" id="score" value="{{ old('score', $score->score) }}" 
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500"
                                        min="0" max="100" step="0.01" required>
                                    @error('score')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="comments" class="block text-sm font-medium text-gray-700 mb-2">Comments</label>
                                    <textarea name="comments" id="comments" rows="4"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('comments', $score->comments) }}</textarea>
                                    @error('comments')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mb-4">
                                    <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                                    <select name="status" id="status" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500" required>
                                        <option value="draft" {{ $score->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="pending" {{ $score->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="submitted" {{ $score->status == 'submitted' ? 'selected' : '' }}>Submitted</option>
                                    </select>
                                    @error('status')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-between mt-6">
                                    <a href="{{ route('judge.review-scores') }}" class="text-gray-600 hover:text-gray-900">
                                        ← Back to Scores
                                    </a>
                                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                                        Update Score
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

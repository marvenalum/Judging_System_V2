<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Edit Scoring Rubric</h1>
                <a href="{{ route('admin.scoring-rubrics.index') }}"
                   class="text-gray-600 hover:text-gray-900">
                    ← Back to Rubrics
                </a>
            </div>

            <form method="POST" action="{{ route('admin.scoring-rubrics.update', $scoringRubric) }}">
                @csrf
                @method('PUT')

                <!-- Criteria Selection -->
                <div class="mb-4">
                    <label for="criteria_id" class="block text-sm font-medium text-gray-700 mb-1">
                        Criteria <span class="text-red-500">*</span>
                    </label>
                    <select name="criteria_id" id="criteria_id"
                            class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('criteria_id') border-red-500 @enderror"
                            required>
                        <option value="">Select Criteria</option>
                        @foreach($criteria as $criterion)
                            <option value="{{ $criterion->id }}" {{ (old('criteria_id', $scoringRubric->criteria_id) == $criterion->id) ? 'selected' : '' }}>
                                {{ $criterion->name }} ({{ $criterion->category->name }} - {{ $criterion->event->event_name }})
                            </option>
                        @endforeach
                    </select>
                    @error('criteria_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Level -->
                <div class="mb-4">
                    <label for="level" class="block text-sm font-medium text-gray-700 mb-1">
                        Level <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="level" id="level" min="1" max="10"
                           value="{{ old('level', $scoringRubric->level) }}"
                           class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('level') border-red-500 @enderror"
                           required>
                    @error('level')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Level from 1 (lowest) to 10 (highest)</p>
                </div>

                <!-- Score Range -->
                <div class="grid grid-cols-2 gap-4 mb-4">
                    <div>
                        <label for="score_range_min" class="block text-sm font-medium text-gray-700 mb-1">
                            Min Score <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="score_range_min" id="score_range_min" min="0" step="0.1"
                               value="{{ old('score_range_min', $scoringRubric->score_range_min) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('score_range_min') border-red-500 @enderror"
                               required>
                        @error('score_range_min')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label for="score_range_max" class="block text-sm font-medium text-gray-700 mb-1">
                            Max Score <span class="text-red-500">*</span>
                        </label>
                        <input type="number" name="score_range_max" id="score_range_max" min="0" step="0.1"
                               value="{{ old('score_range_max', $scoringRubric->score_range_max) }}"
                               class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('score_range_max') border-red-500 @enderror"
                               required>
                        @error('score_range_max')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Description -->
                <div class="mb-4">
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1">
                        Description <span class="text-red-500">*</span>
                    </label>
                    <textarea name="description" id="description" rows="3"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('description') border-red-500 @enderror"
                              required>{{ old('description', $scoringRubric->description) }}</textarea>
                    @error('description')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Brief description of what this level represents</p>
                </div>

                <!-- Detailed Criteria -->
                <div class="mb-6">
                    <label for="detailed_criteria" class="block text-sm font-medium text-gray-700 mb-1">
                        Detailed Criteria
                    </label>
                    <textarea name="detailed_criteria" id="detailed_criteria" rows="5"
                              class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 @error('detailed_criteria') border-red-500 @enderror">{{ old('detailed_criteria', $scoringRubric->detailed_criteria) }}</textarea>
                    @error('detailed_criteria')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Optional detailed criteria for judges</p>
                </div>

                <!-- Submit Buttons -->
                <div class="flex justify-end space-x-3">
                    <a href="{{ route('admin.scoring-rubrics.index') }}"
                       class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                        Cancel
                    </a>
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
                        Update Rubric
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</x-admin-layout>
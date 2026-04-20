<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900">Scoring Rubric Details</h1>
                <div class="flex space-x-3">
                    <a href="{{ route('admin.scoring-rubrics.edit', $scoringRubric) }}"
                       class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium">
                        Edit Rubric
                    </a>
                    <a href="{{ route('admin.scoring-rubrics.index') }}"
                       class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                        ← Back to Rubrics
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Basic Information -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Basic Information</h3>
                        <dl class="space-y-2">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Criteria</dt>
                                <dd class="text-sm text-gray-900">{{ $scoringRubric->criteria->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Category</dt>
                                <dd class="text-sm text-gray-900">{{ $scoringRubric->criteria->category->name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Event</dt>
                                <dd class="text-sm text-gray-900">{{ $scoringRubric->criteria->event->event_name }}</dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Level</dt>
                                <dd class="text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                        Level {{ $scoringRubric->level }}
                                    </span>
                                </dd>
                            </div>
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Score Range</dt>
                                <dd class="text-sm text-gray-900">{{ $scoringRubric->score_range_min }} - {{ $scoringRubric->score_range_max }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <!-- Description -->
                <div class="space-y-4">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Description</h3>
                        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">
                            {{ $scoringRubric->description }}
                        </p>
                    </div>

                    @if($scoringRubric->detailed_criteria)
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Detailed Criteria</h3>
                            <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg whitespace-pre-line">
                                {{ $scoringRubric->detailed_criteria }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Timestamps -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-gray-900 mb-3">Timestamps</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Created</dt>
                        <dd class="text-sm text-gray-900">{{ $scoringRubric->created_at->format('M j, Y \a\t g:i A') }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Last Updated</dt>
                        <dd class="text-sm text-gray-900">{{ $scoringRubric->updated_at->format('M j, Y \a\t g:i A') }}</dd>
                    </div>
                </div>
            </div>

            <!-- Delete Form -->
            <div class="mt-8 pt-6 border-t border-gray-200">
                <h3 class="text-lg font-medium text-red-900 mb-3">Danger Zone</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Once you delete this rubric, there is no going back. Please be certain.
                </p>
                <form method="POST" action="{{ route('admin.scoring-rubrics.destroy', $scoringRubric) }}"
                      onsubmit="return confirm('Are you sure you want to delete this rubric? This action cannot be undone.')"
                      class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg font-medium">
                        Delete Rubric
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>
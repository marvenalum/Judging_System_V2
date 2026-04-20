<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Scoring Rubrics</h1>
        <a href="{{ route('admin.scoring-rubrics.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            Create New Rubric
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="flex gap-4 items-end">
            <div class="flex-1">
                <label for="criteria_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Criteria</label>
                <select name="criteria_id" id="criteria_id"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Criteria</option>
                    @foreach($criteria as $criterion)
                        <option value="{{ $criterion->id }}" {{ request('criteria_id') == $criterion->id ? 'selected' : '' }}>
                            {{ $criterion->name }} ({{ $criterion->category->name }} - {{ $criterion->event->event_name }})
                        </option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                Filter
            </button>
            <a href="{{ route('admin.scoring-rubrics.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                Clear
            </a>
        </form>
    </div>

    <!-- Rubrics Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Criteria</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Level</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Score Range</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($rubrics as $rubric)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $rubric->criteria->name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $rubric->criteria->category->name }} - {{ $rubric->criteria->event->event_name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    Level {{ $rubric->level }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $rubric->score_range_min }} - {{ $rubric->score_range_max }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate" title="{{ $rubric->description }}">
                                {{ Str::limit($rubric->description, 50) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.scoring-rubrics.show', $rubric) }}"
                                       class="text-blue-600 hover:text-blue-900">View</a>
                                    <a href="{{ route('admin.scoring-rubrics.edit', $rubric) }}"
                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>
                                    <form method="POST" action="{{ route('admin.scoring-rubrics.destroy', $rubric) }}"
                                          onsubmit="return confirm('Are you sure you want to delete this rubric?')"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No scoring rubrics found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($rubrics->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $rubrics->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
</x-admin-layout>
<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold text-gray-900">Result Publications</h1>
        <a href="{{ route('admin.result-publications.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium">
            Create New Publication
        </a>
    </div>

    <!-- Filter Form -->
    <div class="bg-white rounded-lg shadow-md p-6 mb-6">
        <form method="GET" class="flex gap-4 items-end flex-wrap">
            <div class="flex-1 min-w-48">
                <label for="event_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Event</label>
                <select name="event_id" id="event_id"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Events</option>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ request('event_id') == $event->id ? 'selected' : '' }}>
                            {{ $event->event_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-48">
                <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Filter by Category</label>
                <select name="category_id" id="category_id"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Categories</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex-1 min-w-32">
                <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" id="status"
                        class="w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="">All Status</option>
                    <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Published</option>
                    <option value="archived" {{ request('status') == 'archived' ? 'selected' : '' }}>Archived</option>
                </select>
            </div>
            <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium">
                Filter
            </button>
            <a href="{{ route('admin.result-publications.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg font-medium">
                Clear
            </a>
        </form>
    </div>

    <!-- Publications Table -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Event & Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Publication Code</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Published At</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($publications as $publication)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">
                                    {{ $publication->event->event_name }}
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ $publication->category->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono">
                                    {{ $publication->publication_code }}
                                </code>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                    @if($publication->status === 'published') bg-green-100 text-green-800
                                    @elseif($publication->status === 'draft') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($publication->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $publication->published_at ? $publication->published_at->format('M j, Y g:i A') : 'Not published' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex space-x-2">
                                    <a href="{{ route('admin.result-publications.show', $publication) }}"
                                       class="text-blue-600 hover:text-blue-900">View</a>
                                    <a href="{{ route('admin.result-publications.edit', $publication) }}"
                                       class="text-indigo-600 hover:text-indigo-900">Edit</a>

                                    @if($publication->status === 'draft')
                                        <form method="POST" action="{{ route('admin.result-publications.publish', $publication) }}"
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to publish these results?')">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="text-green-600 hover:text-green-900">Publish</button>
                                        </form>
                                    @elseif($publication->status === 'published')
                                        <form method="POST" action="{{ route('admin.result-publications.archive', $publication) }}"
                                              class="inline"
                                              onsubmit="return confirm('Are you sure you want to archive these results?')">
                                            @csrf
                                            @method('POST')
                                            <button type="submit" class="text-orange-600 hover:text-orange-900">Archive</button>
                                        </form>
                                    @endif

                                    <form method="POST" action="{{ route('admin.result-publications.refresh', $publication) }}"
                                          class="inline">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="text-purple-600 hover:text-purple-900">Refresh</button>
                                    </form>

                                    @if($publication->status !== 'published')
                                        <form method="POST" action="{{ route('admin.result-publications.destroy', $publication) }}"
                                              onsubmit="return confirm('Are you sure you want to delete this publication?')"
                                              class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                No result publications found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($publications->hasPages())
            <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
                {{ $publications->appends(request()->query())->links() }}
            </div>
        @endif
    </div>
</div>
</x-admin-layout>
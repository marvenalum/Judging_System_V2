<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900">Result Publication Details</h1>
                <p class="text-gray-600 mt-1">Publication Code: <code class="bg-gray-100 px-2 py-1 rounded text-sm font-mono">{{ $resultPublication->publication_code }}</code></p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('admin.result-publications.edit', $resultPublication) }}"
                   class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    Edit Publication
                </a>
                <a href="{{ route('admin.result-publications.index') }}"
                   class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg font-medium transition-colors">
                    ← Back to Publications
                </a>
            </div>
        </div>

        @if (session('success'))
            <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
            <!-- Publication Details -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Publication Information</h2>
                    <dl class="space-y-4">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Event</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $resultPublication->event->event_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Category</dt>
                            <dd class="text-lg font-semibold text-gray-900">{{ $resultPublication->category->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Status</dt>
                            <dd>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($resultPublication->status === 'published') bg-green-100 text-green-800
                                    @elseif($resultPublication->status === 'draft') bg-yellow-100 text-yellow-800
                                    @else bg-gray-100 text-gray-800 @endif">
                                    {{ ucfirst($resultPublication->status) }}
                                </span>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Published At</dt>
                            <dd class="text-sm text-gray-900">
                                {{ $resultPublication->published_at ? $resultPublication->published_at->format('M j, Y g:i A') : 'Not published' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 mb-1">Public URL</dt>
                            <dd class="text-sm">
                                <a href="{{ $resultPublication->getPublicUrl() }}" target="_blank"
                                   class="text-blue-600 hover:text-blue-900 font-medium truncate block">
                                    {{ $resultPublication->getPublicUrl() }}
                                </a>
                            </dd>
                        </div>
                        @if($resultPublication->metadata)
                            <div>
                                <dt class="text-sm font-medium text-gray-500 mb-1">Participants</dt>
                                <dd class="text-sm text-gray-900">
                                    {{ $resultPublication->metadata['total_participants'] ?? 0 ?? 'N/A' }}
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>

            <!-- Actions -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Quick Actions</h2>
                    <div class="space-y-3">
                        @if($resultPublication->status === 'draft')
                            <form method="POST" action="{{ route('admin.result-publications.publish', $resultPublication) }}"
                                  class="inline-block" onsubmit="return confirm('Publish this results publicly?')">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                    Publish Results
                                </button>
                            </form>
                        @elseif($resultPublication->status === 'published')
                            <form method="POST" action="{{ route('admin.result-publications.archive', $resultPublication) }}"
                                  class="inline-block" onsubmit="return confirm('Archive this publication?')">
                                @csrf
                                <button type="submit" class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                    Archive
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.result-publications.refresh', $resultPublication) }}"
                              class="inline-block" onsubmit="return confirm('Refresh results data?')">
                            @csrf
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                Refresh Results
                            </button>
                        </form>

                        <form method="POST" action="{{ route('admin.result-publications.destroy', $resultPublication) }}"
                              class="inline-block" onsubmit="return confirm('Delete this publication?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-lg font-medium transition-colors">
                                Delete
                            </button>
                        </form>

                        <a href="{{ route('admin.result-publications.edit', $resultPublication) }}"
                           class="block w-full bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-4 rounded-lg font-medium text-center transition-colors">
                            Edit Details
                        </a>
                    </div>
                </div>
            </div>

            <!-- Results Preview (main content) -->
            <div class="lg:col-span-3">
                <div class="bg-white rounded-lg shadow-md border border-gray-200 overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                        <h2 class="text-xl font-semibold text-gray-900">Results Preview ({{ count($resultPublication->results_data ?? []) }} participants)</h2>
                        @if(!$resultPublication->results_data)
                            <p class="text-sm text-gray-500 mt-1">No results computed yet. Use \"Refresh Results\" to generate.</p>
                        @endif
                    </div>
                    @if($resultPublication->results_data && count($resultPublication->results_data) > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Participant</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Score</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Percentage</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($resultPublication->results_data as $result)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold bg-gradient-to-r @if($loop->first) from-yellow-400 to-orange-400 text-black @else from-gray-200 to-gray-300 text-gray-800 @endif">
                                                    #{{ $result['rank'] }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900">{{ $result['participant_name'] }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ number_format($result['total_score'], 1) }} / {{ $result['max_possible'] }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    {{ $result['percentage'] }}%
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <i class="bi bi-trophy text-4xl text-gray-400 mb-4 block mx-auto"></i>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No results available</h3>
                            <p class="text-gray-500 mb-4">Run \"Refresh Results\" to compute rankings based on participant scores.</p>
                            <form method="POST" action="{{ route('admin.result-publications.refresh', $resultPublication) }}" class="inline">
                                @csrf
                                <button type="submit" class="bg-purple-600 hover:bg-purple-700 text-white px-6 py-2 rounded-lg font-medium">
                                    Compute Results
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Timestamps -->
        <div class="bg-white rounded-lg shadow-md p-6 border border-gray-200">
            <h2 class="text-xl font-semibold text-gray-900 mb-4">Activity</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                <div>
                    <span class="text-gray-500">Created</span>
                    <div class="font-medium text-gray-900">{{ $resultPublication->created_at->format('M j, Y g:i A') }}</div>
                </div>
                <div>
                    <span class="text-gray-500">Last Updated</span>
                    <div class="font-medium text-gray-900">{{ $resultPublication->updated_at->format('M j, Y g:i A') }}</div>
                </div>
                @if($resultPublication->metadata && isset($resultPublication->metadata['last_computed_at']))
                    <div>
                        <span class="text-gray-500">Last Computed</span>
                        <div class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($resultPublication->metadata['last_computed_at'])->format('M j, Y g:i A') }}</div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
</x-admin-layout>


<x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Judge Criteria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h1 class="text-2xl font-bold mb-4">Judge Criteria</h1>
                    <p>View all active criteria for scoring.</p>

                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(session('message'))
                        <div class="bg-yellow-100 border border-yellow-400 text-yellow-700 px-4 py-3 rounded mb-4">
                            {{ session('message') }}
                        </div>
                    @endif

                    <!-- Empty State or Criteria Table -->
                    @if($criteria->isEmpty())
                        <div class="mt-8 text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No Criteria Found</h3>
                            <p class="mt-1 text-sm text-gray-500">
                                No active criteria available. Please check back later.
                            </p>
                        </div>
                    @else
                        <!-- Criteria Table -->
                        <div class="mt-8">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">ID</th>
                                        <th class="py-2 px-4 border-b">Event</th>
                                        <th class="py-2 px-4 border-b">Category</th>
                                        <th class="py-2 px-4 border-b">Name</th>
                                        <th class="py-2 px-4 border-b">Description</th>
                                        <th class="py-2 px-4 border-b">Max Score</th>
                                        <th class="py-2 px-4 border-b">Weight</th>
                                        <th class="py-2 px-4 border-b">Status</th>
                                        <th class="py-2 px-4 border-b">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($criteria as $criterion)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $criterion->id }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->event->event_name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->category->name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->name }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->description ?? 'N/A' }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->max_score ?? 'N/A' }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->percentage_weight ?? $criterion->weight ?? 'N/A' }}%</td>
                                            <td class="py-2 px-4 border-b">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $criterion->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($criterion->status) }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                <a href="{{ route('judge.criteria.show', $criterion) }}" class="text-blue-600 hover:text-blue-800 mr-2">View</a>
                                                @if($criterion->status === 'active')
                                                    <a href="{{ route('judge.criteria.createScore', $criterion) }}" class="text-green-600 hover:text-green-800">Enter Score</a>
                                                @else
                                                    <span class="text-gray-400" title="This criterion is inactive">Enter Score</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-judge-layout>

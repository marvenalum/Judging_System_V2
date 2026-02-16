<x-judge-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Judge Criteria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                @include('admin.sidebar.judge')

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">Judge Criteria</h1>
                        <p>View criteria for events you are assigned to judge.</p>

                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <!-- Criteria Table -->
                        <div class="mt-8">
                            <table class="min-w-full bg-white">
                                <thead>
                                    <tr>
                                        <th class="py-2 px-4 border-b">ID</th>
                                        <th class="py-2 px-4 border-b">Name</th>
                                        <th class="py-2 px-4 border-b">Description</th>
                                        <th class="py-2 px-4 border-b">Event</th>
                                        <th class="py-2 px-4 border-b">Percentage Weight</th>
                                        <th class="py-2 px-4 border-b">Status</th>
                                        <th class="py-2 px-4 border-b">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($criteria as $criterion)
                                        <tr>
                                            <td class="py-2 px-4 border-b">{{ $criterion->id }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->name }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->description ?? 'N/A' }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->event->name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->percentage_weight }}%</td>
                                            <td class="py-2 px-4 border-b">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $criterion->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($criterion->status) }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                <a href="{{ route('judge.criteria.show', $criterion) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-judge-layout>

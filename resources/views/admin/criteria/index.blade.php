<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Criteria') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                @include('admin.sidebar.admin')

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">Manage Criteria</h1>
                        <p>View and manage all criteria in the system.</p>

                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="mb-4">
                            <a href="{{ route('admin.criteria.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Create New Criterion
                            </a>
                        </div>

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
                                                <a href="{{ route('admin.criteria.show', $criterion) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                                <span class="mx-2">|</span>
                                                <a href="{{ route('admin.criteria.edit', $criterion) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                                <span class="mx-2">|</span>
                                                <form action="{{ route('admin.criteria.destroy', $criterion) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this criterion?')">Delete</button>
                                                </form>
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
</x-app-layout>

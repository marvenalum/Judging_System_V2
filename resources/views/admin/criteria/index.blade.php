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

                        @if(session('error'))
                            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                                {{ session('error') }}
                            </div>
                        @endif

                        <!-- Status Filter and Create Button -->
                        <div class="mb-4 flex justify-between items-center">
                            <form method="GET" action="{{ route('admin.criteria.index') }}" class="flex items-center">
                                <label for="status" class="mr-2 text-sm font-medium text-gray-700">Filter by Status:</label>
                                <select name="status" id="status" onchange="this.form.submit()" class="border-gray-300 rounded-md shadow-sm">
                                    <option value="">All</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </form>
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
                                        <th class="py-2 px-4 border-b">Event</th>
                                        <th class="py-2 px-4 border-b">Category</th>
                                        <th class="py-2 px-4 border-b">Max Score</th>
                                        <th class="py-2 px-4 border-b">Weight</th>
                                        <th class="py-2 px-4 border-b">Scores</th>
                                        <th class="py-2 px-4 border-b">Status</th>
                                        <th class="py-2 px-4 border-b">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($criteria as $criterion)
                                        <tr class="{{ $criterion->status === 'inactive' ? 'bg-gray-100' : '' }}">
                                            <td class="py-2 px-4 border-b">{{ $criterion->id }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->name }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->event->name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->category->name ?? 'N/A' }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->max_score ?? 'N/A' }}</td>
                                            <td class="py-2 px-4 border-b">{{ $criterion->percentage_weight ?? $criterion->weight ?? 'N/A' }}%</td>
                                            <td class="py-2 px-4 border-b">
                                                @if(isset($scoreCounts[$criterion->id]) && $scoreCounts[$criterion->id] > 0)
                                                    <span class="text-orange-600 font-semibold">{{ $scoreCounts[$criterion->id] }}</span>
                                                    <span class="text-xs text-gray-500">({{ $scoreCounts[$criterion->id] }} score{{ $scoreCounts[$criterion->id] > 1 ? 's' : '' }})</span>
                                                @else
                                                    <span class="text-gray-400">0</span>
                                                @endif
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $criterion->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                                    {{ ucfirst($criterion->status) }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-4 border-b">
                                                <a href="{{ route('admin.criteria.show', $criterion) }}" class="text-blue-600 hover:text-blue-800">View</a>
                                                <span class="mx-1">|</span>
                                                <a href="{{ route('admin.criteria.edit', $criterion) }}" class="text-blue-600 hover:text-blue-800">Edit</a>
                                                <span class="mx-1">|</span>
                                                <!-- Toggle Status Button -->
                                                <form action="{{ route('admin.criteria.toggleStatus', $criterion) }}" method="POST" style="display: inline;">
                                                    @csrf
                                                    @if($criterion->status === 'active')
                                                        <button type="submit" class="text-orange-600 hover:text-orange-800" onclick="return confirm('Are you sure you want to deactivate this criterion?')">
                                                            Deactivate
                                                        </button>
                                                    @else
                                                        <button type="submit" class="text-green-600 hover:text-green-800" onclick="return confirm('Are you sure you want to activate this criterion?')">
                                                            Activate
                                                        </button>
                                                    @endif
                                                </form>
                                                <span class="mx-1">|</span>
                                                <!-- Delete Button (only if no scores exist) -->
                                                @if(!isset($scoreCounts[$criterion->id]) || $scoreCounts[$criterion->id] == 0)
                                                    <form action="{{ route('admin.criteria.destroy', $criterion) }}" method="POST" style="display: inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete this criterion?')">Delete</button>
                                                    </form>
                                                @else
                                                    <span class="text-gray-400 text-sm" title="Cannot delete - scores exist">Delete</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="py-4 px-4 border-b text-center text-gray-500">
                                                No criteria found. <a href="{{ route('admin.criteria.create') }}" class="text-blue-600 hover:underline">Create one</a>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

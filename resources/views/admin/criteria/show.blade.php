<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('View Criterion') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                @include('admin.sidebar.admin')

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h1 class="text-2xl font-bold mb-4">Criterion Details</h1>

                        <div class="mb-4">
                            <strong>Name:</strong> {{ $criteria->name }}
                        </div>

                        <div class="mb-4">
                            <strong>Description:</strong> {{ $criteria->description ?? 'N/A' }}
                        </div>

                        <div class="mb-4">
                            <strong>Event:</strong> {{ $criteria->event->name ?? 'N/A' }}
                        </div>

                        <div class="mb-4">
                            <strong>Percentage Weight:</strong> {{ $criteria->percentage_weight }}%
                        </div>

                        <div class="mb-4">
                            <strong>Status:</strong>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $criteria->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ ucfirst($criteria->status) }}
                            </span>
                        </div>

                        <div class="flex items-center justify-between">
                            <a href="{{ route('admin.criteria.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                                Back to List
                            </a>
                            <a href="{{ route('admin.criteria.edit', ['criterion' => $criteria->id]) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Edit
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

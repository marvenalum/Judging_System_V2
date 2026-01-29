<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Category Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                @include('admin.sidebar.admin')

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <div class="mb-4">
                        <h3 class="text-lg font-semibold">{{ ucfirst($category->name) }}</h3>
                        <p class="text-gray-600">{{ $category->description }}</p>
                        <p><strong>Event:</strong> {{ $category->event->event_name ?? 'N/A' }}</p>
                        <p><strong>Percentage Weight:</strong> {{ $category->percentage_weight }}%</p>
                        <p><strong>Status:</strong>
                            <span class="px-2 py-1 rounded text-xs font-semibold
                                @if($category->status === 'active') bg-green-100 text-green-800
                                @else bg-red-100 text-red-800 @endif">
                                {{ ucfirst($category->status) }}
                            </span>
                        </p>
                    </div>

                    <div class="flex space-x-4">
                        <a href="{{ route('admin.category.edit', $category) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Edit Category
                        </a>
                        <a href="{{ route('admin.category.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Back to Categories
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<x-app-sidebar>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Criteria') }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="p-6">
                    <form method="POST" action="{{ route('participant.criteria.store') }}">
                        @csrf

                        <!-- Name -->
                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                Criteria Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="name" 
                                   id="name" 
                                   value="{{ old('name') }}"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('name') border-red-500 @enderror"
                                   placeholder="Enter criteria name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Event -->
                        <div class="mb-6">
                            <label for="event_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Event <span class="text-red-500">*</span>
                            </label>
                            <select name="event_id" 
                                    id="event_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('event_id') border-red-500 @enderror">
                                <option value="">Select an event</option>
                                @foreach($events as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>{{ $event->event_name }}</option>
                                @endforeach
                            </select>
                            @error('event_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mb-6">
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                Category <span class="text-red-500">*</span>
                            </label>
                            <select name="category_id" 
                                    id="category_id"
                                    class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('category_id') border-red-500 @enderror">
                                <option value="">Select a category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Max Score -->
                        <div class="mb-6">
                            <label for="max_score" class="block text-sm font-medium text-gray-700 mb-2">
                                Max Score <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="max_score" 
                                   id="max_score" 
                                   value="{{ old('max_score') }}"
                                   min="0"
                                   step="0.01"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('max_score') border-red-500 @enderror"
                                   placeholder="Enter max score">
                            @error('max_score')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Weight -->
                        <div class="mb-6">
                            <label for="weight" class="block text-sm font-medium text-gray-700 mb-2">
                                Weight <span class="text-red-500">*</span>
                            </label>
                            <input type="number" 
                                   name="weight" 
                                   id="weight" 
                                   value="{{ old('weight') }}"
                                   min="0"
                                   step="0.01"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('weight') border-red-500 @enderror"
                                   placeholder="Enter weight">
                            @error('weight')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex items-center justify-end gap-4 pt-4 border-t border-gray-200">
                            <a href="{{ route('participant.criteria.index') }}" 
                               class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-colors">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="px-5 py-2.5 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg hover:from-blue-700 hover:to-blue-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all shadow-md hover:shadow-lg">
                                Create Criteria
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-sidebar>

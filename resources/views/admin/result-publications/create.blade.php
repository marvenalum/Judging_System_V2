<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Create Result Publication</h1>
            <p class="text-gray-600 mt-1">Select an event and category to generate results for.</p>
        </div>
        <a href="{{ route('admin.result-publications.index') }}"
           class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
            Back to Publications
        </a>
    </div>

    <!-- Create Form Card -->
    <div class="max-w-md bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('admin.result-publications.store') }}">
                @csrf

                <!-- Event Selection -->
                <div class="mb-6">
                    <label for="event_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Event <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="event_id" 
                        name="event_id" 
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm"
                        {{ old('event_id') ? 'value="'.old('event_id').'"' : '' }}>
                        <option value="">Select an ongoing event...</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>
                                {{ $event->event_name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('event_id')" class="mt-2" />
                </div>

                <!-- Category Selection -->
                <div class="mb-8">
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="category_id" 
                        name="category_id" 
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm"
                        {{ old('category_id') ? 'value="'.old('category_id').'"' : '' }}>
                        <option value="">Select an active category...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->event->event_name ?? 'No event' }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.result-publications.index') }}" 
                       class="flex-1 text-center py-3 px-6 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-200 shadow-sm hover:shadow-md focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        Create Publication
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-8 p-6 bg-blue-50 border border-blue-200 rounded-xl">
        <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
            <i class="bi bi-info-circle text-blue-600 mr-2"></i>
            How it works
        </h3>
        <p class="text-sm text-gray-700 leading-relaxed">
            A unique publication code will be generated automatically. The system will compute rankings based on 
            final scores for all participants in the selected category. You can preview, publish, or refresh results later.
        </p>
    </div>
</div>
</x-admin-layout>


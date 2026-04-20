<x-admin-layout>
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">Edit Result Publication</h1>
            <p class="text-gray-600 mt-1">Update publication details. Changing event or category will recompute results.</p>
        </div>
        <div class="flex space-x-3">
            <a href="{{ route('admin.result-publications.show', $resultPublication) }}"
               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                ← Back to Details
            </a>
            <a href="{{ route('admin.result-publications.index') }}"
               class="bg-gray-600 hover:bg-gray-700 text-white px-6 py-2 rounded-lg font-medium transition-colors">
                All Publications
            </a>
        </div>
    </div>

    <!-- Edit Form Card -->
    <div class="max-w-2xl bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="p-8">
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

            <form method="POST" action="{{ route('admin.result-publications.update', $resultPublication) }}">
                @csrf
                @method('PUT')

                <!-- Publication Info (Read-only) -->
                <div class="bg-gradient-to-r from-gray-50 to-blue-50 p-6 rounded-xl mb-8">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Publication Info</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-sm">
                        <div>
                            <span class="text-gray-500 font-medium">Publication Code:</span>
                            <div class="font-mono bg-white border border-gray-200 px-3 py-1 rounded-lg mt-1 text-sm font-semibold">
                                {{ $resultPublication->publication_code }}
                            </div>
                        </div>
                        <div>
                            <span class="text-gray-500 font-medium">Public URL:</span>
                            <div class="mt-1">
                                <a href="{{ $resultPublication->getPublicUrl() }}" target="_blank" 
                                   class="text-blue-600 hover:text-blue-900 font-medium truncate block">
                                    {{ $resultPublication->getPublicUrl() }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Event Selection -->
                <div class="mb-6">
                    <label for="event_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Event <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="event_id" 
                        name="event_id" 
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm">
                        <option value="">Select an ongoing event...</option>
                        @foreach($events as $event)
                            <option value="{{ $event->id }}" {{ old('event_id', $resultPublication->event_id) == $event->id ? 'selected' : '' }}>
                                {{ $event->event_name }}
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('event_id')" class="mt-2" />
                    <p class="text-xs text-gray-500 mt-1">Current: {{ $resultPublication->event->event_name }}</p>
                </div>

                <!-- Category Selection -->
                <div class="mb-6">
                    <label for="category_id" class="block text-sm font-semibold text-gray-700 mb-2">
                        Category <span class="text-red-500">*</span>
                    </label>
                    <select 
                        id="category_id" 
                        name="category_id" 
                        required
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 bg-white shadow-sm">
                        <option value="">Select an active category...</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $resultPublication->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }} ({{ $category->event->event_name ?? 'No event' }})
                            </option>
                        @endforeach
                    </select>
                    <x-input-error :messages="$errors->get('category_id')" class="mt-2" />
                    <p class="text-xs text-gray-500 mt-1">Current: {{ $resultPublication->category->name }}</p>
                </div>

                <!-- Status Selection -->
                <div class="mb-8">
                    <label class="block text-sm font-semibold text-gray-700 mb-4">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                        @foreach(['draft' => 'Draft', 'published' => 'Published', 'archived' => 'Archived'] as $value => $label)
                            <label class="flex items-center p-4 border border-gray-200 rounded-xl hover:border-gray-300 hover:shadow-sm transition-all cursor-pointer bg-white @if(old('status', $resultPublication->status) == $value) ring-2 ring-blue-500 bg-blue-50 @endif">
                                <input type="radio" name="status" value="{{ $value }}" 
                                       class="w-5 h-5 text-blue-600 border-gray-300 focus:ring-blue-500 mr-3" 
                                       {{ old('status', $resultPublication->status) == $value ? 'checked' : '' }} required>
                                <div>
                                    <div class="font-semibold text-gray-900">{{ $label }}</div>
                                    <div class="text-xs text-gray-500">
                                        @if($value === 'draft') Ready to publish
                                        @elseif($value === 'published') Publicly visible
                                        @else Archived (read-only) @endif
                                    </div>
                                </div>
                            </label>
                        @endforeach
                    </div>
                    <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    <p class="text-xs text-gray-500 mt-1">
                        Current: 
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                            @if($resultPublication->status === 'published') bg-green-100 text-green-800
                            @elseif($resultPublication->status === 'draft') bg-yellow-100 text-yellow-800
                            @else bg-gray-100 text-gray-800 @endif">
                            {{ ucfirst($resultPublication->status) }}
                        </span>
                    </p>
                </div>

                <!-- Action Buttons -->
                <div class="flex items-center gap-3 pt-4 border-t border-gray-100">
                    <a href="{{ route('admin.result-publications.show', $resultPublication) }}" 
                       class="flex-1 text-center py-3 px-6 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-colors">
                        Cancel
                    </a>
                    <button type="submit" 
                            class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white py-3 px-6 rounded-lg font-semibold transition-all duration-200 shadow-sm hover:shadow-md focus:ring-2 focus:ring-indigo-500 focus:outline-none">
                        Update Publication
                    </button>
                </div>
            </form>
        </div>
    </div>

    <div class="mt-8 p-6 bg-amber-50 border border-amber-200 rounded-xl">
        <h3 class="font-semibold text-gray-900 mb-2 flex items-center">
            <i class="bi bi-exclamation-triangle text-amber-600 mr-2"></i>
            Important Notes
        </h3>
        <ul class="text-sm text-gray-700 space-y-1 list-disc list-inside">
            <li>Changing <strong>event or category</strong> will recompute all results data automatically</li>
            <li>Only <strong>ongoing events</strong> and <strong>active categories</strong> are available</li>
            <li>Publication code cannot be changed once generated</li>
            <li>Publishing makes results publicly accessible via the public URL</li>
        </ul>
    </div>
</div>
</x-admin-layout>


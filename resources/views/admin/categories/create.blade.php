<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex">
                @include('admin.sidebar.admin')

                <!-- Main Content -->
                <div class="flex-1 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('admin.category.store') }}">
                        @csrf

                        <div class="mb-4">
                            <x-input-label for="name" :value="__('Category Name')" />
                            <select id="name" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="name" required>
                                <option value="">Select Category</option>
                                <option value="talent" {{ old('name') == 'talent' ? 'selected' : '' }}>Talent</option>
                                <option value="Q&A" {{ old('name') == 'Q&A' ? 'selected' : '' }}>Q&A</option>
                                <option value="presentation" {{ old('name') == 'presentation' ? 'selected' : '' }}>Presentation</option>
                            </select>
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="description" rows="4">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="event_id" :value="__('Event')" />
                            <select id="event_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="event_id" required>
                                <option value="">Select Event</option>
                                
                                @foreach(\App\Models\Event::all() as $event)
                                    <option value="{{ $event->id }}" {{ old('event_id') == $event->id ? 'selected' : '' }}>{{ $event->event_name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('event_id')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="percentage_weight" :value="__('Percentage Weight (%)')" />
                            <x-text-input id="percentage_weight" class="block mt-1 w-full" type="number" name="percentage_weight" :value="old('percentage_weight')" min="0" max="100" step="0.01" />
                            <x-input-error :messages="$errors->get('percentage_weight')" class="mt-2" />
                        </div>

                        <div class="mb-4">
                            <x-input-label for="status" :value="__('Status')" />
                            <select id="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" name="status" required>
                                <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('admin.category.index') }}" class="mr-4 text-gray-600 hover:text-gray-900">Cancel</a>
                            <x-primary-button>
                                {{ __('Create Category') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
